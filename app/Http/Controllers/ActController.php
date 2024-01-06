<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\ActSummary;
use App\Models\Category;
use App\Models\MainType;
use App\Models\Parts;
use App\Models\PartsType;
use App\Models\SubSection;
use App\Models\Footnote;
use App\Models\Chapter;
use App\Models\Form;
use App\Models\Regulation;
use App\Models\Section;
use App\Models\State;
use App\Models\Status;
use App\Models\SubType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ActController extends Controller
{

    public function index()
    {
        $act = Act::with('CategoryModel')->get();

        return view('admin.act.index', compact('act'));
    }

    public function get_act_section(Request $request, $id)
    {
        $act_id = $id;
        $act = Act::where('act_id', $act_id)->first();
        if ($act) {
            $act_footnote_titles = json_decode($act->act_footnote_title, true);
            $act_footnote_descriptions = json_decode($act->act_footnote_description, true);
        }
        $act_section = Section::where('act_id', $id)->with('MainTypeModel', 'Partmodel', 'ChapterModel')
            ->orderBy('section_rank', 'asc')->get();

        return view('admin.section.index', compact('act_section', 'act_id', 'act', 'act_footnote_titles', 'act_footnote_descriptions'));
    }

    public function create(Request $request, $id)
    {
        $category = Category::all();
        $status = Status::all();
        $states = State::all();
        $mtype = MainType::all();
        $stype = SubType::all();
        $parts = PartsType::all();

        $act = Act::where('act_id', $id)->first();
        $showFormTitle = ($act->act_summary && in_array('6', json_decode($act->act_summary, true)));
        // dd($showFormTitle);
        // die();

        return view('admin.act.create', compact('category', 'status', 'states', 'mtype', 'parts', 'stype', 'act', 'showFormTitle'));
    }

    public function store(Request $request, $id)
    {

        // dd($request);
        // die();

        try {

            $act = Act::find($id);
            $act->update([
                'category_id' => $request->category_id,
                'state_id' => $request->state_id ?? null,
                'act_title' => $request->act_title,
                'act_content' => $request->act_content ?? null,
            ]);


            foreach ($request->maintype_id as $key => $maintypeId) {
                if ($maintypeId == "1") {
                    $chapt = new Chapter();
                    $chapt->act_id = $act->act_id ?? null;
                    $chapt->maintype_id = $maintypeId;
                    $chapt->chapter_title = $request->chapter_title[$key] ?? null;
                    $chapt->save();

                    if (isset($request->subtypes_id[$key]) && $request->subtypes_id[$key] == 1) {
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];

                                // Update SubSection records, Footnote records, etc. (similar to Section)
                                $lastSection = Section::orderBy('section_rank', 'desc')->first();

                                // dd($lastSection);
                                // die();
                                $lastRank = $lastSection ? $lastSection->section_rank : 0;
                                // Create the new section with the updated section_no
                                $section = Section::create([
                                    'section_rank' => $lastRank + 1,
                                    'section_no' => $currentSectionNo,
                                    'act_id' => $act->act_id,
                                    'maintype_id' => $maintypeId,
                                    'chapter_id' => $chapt->chapter_id,
                                    'subtypes_id' => $subtypes_id,
                                    'section_title' => $sectiontitle,
                                ]);
                            }
                        }
                    } elseif ($request->subtypes_id[$key] == 4) {
                        //  dd($request);
                        //  die();
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
                            // Update Section records
                            // Regulation::where('regulation_no', '>=', $currentRegulationNo)
                            //     ->get()
                            //     ->each(function ($regulation) {
                            //         $regulation->increment('regulation_no');
                            //     });

                            $regulation = Regulation::create([
                                'regulation_no' => $currentRegulationNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'chapter_id' => $chapt->chapter_id,
                                'subtypes_id' => $subtypes_id,
                                'regulation_title' => $regulationtitle,
                            ]);

                            $regulationId = $regulation->regulation_id;

                            $form = Form::create([
                                'regulation_id' => $regulationId,
                                'act_id' => $act->act_id,
                                'form_title' => $request->form_title,
                            ]);
                        }
                    }
                } elseif ($maintypeId == "2") {
                    $parts = new Parts();
                    $parts->act_id = $act->act_id ?? null;
                    $parts->maintype_id = $maintypeId;
                    $parts->partstype_id = $request->partstype_id[$key] ?? null;
                    $parts->parts_title = $request->parts_title[$key] ?? null;
                    $parts->save();

                    $subtypes_id = $request->subtypes_id[$key] ?? null;

                    foreach ($request->section_title[$key] as $index => $sectiontitle) {
                        $currentSectionNo = $request->section_no[$key][$index];
                        //    dd($currentSectionNo);
                        //    die();
                        // Update Section records
                        // Section::where('section_no', '>=', $currentSectionNo)
                        //     ->get()
                        //     ->each(function ($section) {
                        //         $section->increment('section_no');
                        //     });

                        // Update SubSection records
                        // SubSection::where('section_no', '>=', $currentSectionNo)
                        //     ->get()
                        //     ->each(function ($subSection) {
                        //         $subSection->increment('section_no');
                        //     });

                        // Update Footnote records
                        // Footnote::where('section_no', '>=', $currentSectionNo)
                        //     ->get()
                        //     ->each(function ($footnote) {
                        //         $footnote->increment('section_no');
                        //     });

                        $lastSection = Section::orderBy('section_rank', 'desc')->first();
                        $lastRank = $lastSection ? $lastSection->section_rank : 0;
                        // Create the new section with the updated section_no
                        $section = Section::create([
                            'section_rank' => $lastRank + 1,
                            'section_no' => $currentSectionNo,
                            'act_id' => $act->act_id,
                            'maintype_id' => $maintypeId,
                            'parts_id' => $parts->parts_id,
                            'subtypes_id' => $subtypes_id,
                            'section_title' => $sectiontitle,
                        ]);
                    }
                } else {
                    dd("something went wrong - right now we are working only in chapter and parts");
                }
            }
            if ($request->subtypes_id[$key] == 1) {
                return redirect()->route('get_act_section', ['id' => $id])->with('success', 'Section added successfully');
            } elseif ($request->subtypes_id[$key] == 4) {
                return redirect()->route('get_act_regulation', ['id' => $id])->with('success', 'Regulation added successfully');
            }
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }

    public function view(Request $request, $id)
    {
        $export = Act::where('act_id', $id)->get();
        return view('admin.act.view', compact('export'));
    }
    public function update_main_act(Request $request, $id)
    {

        try {
            $validator = Validator::make($request->all(), [
                'act_footnote_title.*' => 'nullable',
                'act_footnote_description.*' => 'nullable',
            ]);

            if ($validator->fails()) {
                throw ValidationException::withMessages($validator->errors()->toArray());
            }

            $act = Act::find($id);
            $act->act_title = $request->act_title;
            $act->act_no = $request->act_no ?? null;
            $act->act_date = $request->act_date ?? null;
            $act->act_description = $request->act_description ?? null;
            $act->act_footnote_title = json_encode($request->act_footnote_title);
            $act->act_footnote_description = json_encode($request->act_footnote_description);
            $act->update();


            return redirect()->back()->with('success', 'Main Act Updated Successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }

    public function new_act()
    {
        $category = Category::all();
        $states = State::all();
        $actSummary = ActSummary::all();
        return view('admin.act.new_act', compact('category', 'states', 'actSummary'));
    }

    public function store_new_act(Request $request)
    {
        try {
            $act = new Act();
            $act->category_id = $request->category_id;
            $act->state_id = $request->state_id ?? null;
            $act->act_title = $request->act_title;
            $actSummaries = ActSummary::pluck('id')->map(function ($id) {
                return (string) $id;
            })->toArray();
            $act->act_summary = json_encode($actSummaries);
            $act->save();

            return redirect()->route('act')->with('success', 'Act created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->route('act')->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }


    public function edit_main_act(Request $request, $id)
    {
        $act_id = $id;
        $mainact = ActSummary::all();
        $act = Act::find($act_id);

        return view('admin.act.main_act', compact('act_id', 'mainact', 'act'));
    }


    public function show(string $id)
    {
        //
    }

    public function edit()
    {
        return view('admin.act.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $act = Act::find($id);
            if (!$act) {
                return redirect()->back()->withErrors(['error' => 'Act not found.']);
            }
    
            // Delete related records
            Chapter::where('act_id', $id)->delete();
            Parts::where('act_id', $id)->delete();
            Section::where('act_id', $id)->delete();
            Regulation::where('act_id', $id)->delete();
            SubSection::where('act_id', $id)->delete();
            Footnote::where('act_id', $id)->delete();
    
            $act->delete();
    
            return redirect()->back()->with('success', 'Act and related records deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting act and related records: ' . $e->getMessage());
    
            return redirect()->back()->withErrors(['error' => 'Failed to delete act and related records. Please try again.' . $e->getMessage()]);
        }
    }
    
}
