<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Category;
use App\Models\Section;
use App\Models\SubSection;
use App\Models\Footnote;
use App\Models\MainType;
use App\Models\PartsType;
use App\Models\State;
use App\Models\Status;
use App\Models\SubType;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $status = Status::all();

        return view('admin.section.index', compact('status'));
    }

    public function add_below_new_section(Request $request, $id, $section_no)
    {
        $sec_no = $section_no;
        $sections = Section::with('ChapterModel', 'Partmodel')->where('act_id', $id)
            ->where('section_no', $section_no)->first();
        // dd($sections);
        // die();

        return view('admin.section.add_new', compact('sections', 'sec_no'));
    }

    public function add_new_section(Request $request)
    {
        try {
            $id = $request->act_id;
            $sec_no = $request->section_no;
            $maintypeId = $request->maintype_id;

            // Calculate the next section number
            $nextSectionNo = $sec_no + 1;

            // Update the existing sections' section_no in the Section table
            Section::where('section_no', '>=', $nextSectionNo)
                ->increment('section_no');

            // Create the new section with the incremented section_no
            $section = Section::create([
                'section_no' => $nextSectionNo,
                'act_id' => $request->act_id,
                'maintype_id' => $maintypeId,
                'chapter_id' => $request->chapter_id ?? null,
                'parts_id' => $request->parts_id ?? null,
                'subtypes_id' => $request->subtypes_id,
                'section_title' => $request->section_title,
                'section_content' => $request->section_content,
            ]);

            if ($maintypeId == "1" || $maintypeId == "2") {
                foreach ($request->sub_section_title as $key => $subSectionTitle) {
                    // Update the existing sections' section_no in the SubSection table
                    SubSection::where('section_no', '>=', $nextSectionNo)
                        ->increment('section_no');

                    $sub_section = SubSection::create([
                        'section_id' => $section->section_id,
                        'section_no' => $nextSectionNo,
                        'act_id' => $request->act_id,
                        'chapter_id' => $maintypeId == "1" ? $request->chapter_id : null,
                        'parts_id' => $maintypeId == "2" ? $request->parts_id : null,
                        'sub_section_title' => $subSectionTitle,
                        'sub_section_content' => $request->sub_section_content[$key],
                    ]);

                    // Update the existing sections' section_no in the Footnote table
                    Footnote::where('section_no', '>=', $nextSectionNo)
                        ->increment('section_no');

                    $footnote = Footnote::create([
                        'section_id' => $section->section_id,
                        'section_no' => $nextSectionNo,
                        'act_id' => $request->act_id,
                        'chapter_id' => $maintypeId == "1" ? $request->chapter_id : null,
                        'parts_id' => $maintypeId == "2" ? $request->parts_id : null,
                        'footnote_title' => $request->footnote_title[$key],
                        'footnote_content' => $request->footnote_content[$key],
                    ]);
                }
            } else {
                return redirect()->back()->withErrors(['error' => 'Invalid maintypeId.']);
            }

            return redirect()->route('get_act_section', ['id' => $id])->with('success', 'Index created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $status = Status::all();
        return view('admin.section.create', compact('status'));
    }

    public function SubSection_Index()
    {
        $status = Status::all();
        return view('admin.sub-section.index', compact('status'));
    }

    public function SubSection_Create()
    {
        $status = Status::all();
        return view('admin.sub-section.create', compact('status'));
    }

    public function edit_section($id)
    {
        $sections = Section::with('ChapterModel', 'Partmodel')->where('section_id', $id)->first();
        $subsec = Section::where('section_id', $id)->with('subsectionModel', 'footnoteModel')->get();
        // dd($subsec);
        // die();
        return view('admin.section.edit', compact('sections', 'subsec'));
    }



    public function update(Request $request, $id)
    {
        try {
            // Check if section_id exists in the request
            if (!$request->has('section_id')) {
                return redirect()->route('edit-section', ['id' => $id])->withErrors(['error' => 'Section ID is missing']);
            }

            $sections = Section::find($request->section_id);

            // Check if the section is found
            if (!$sections) {
                return redirect()->route('edit-section', ['id' => $id])->withErrors(['error' => 'Section not found']);
            }
            if ($sections->section_no == $request->section_no) {
                $sections->section_content = $request->section_content ?? null;
                $sections->section_title = $request->section_title ?? null;
                $sections->section_no = $request->section_no ?? null;
                $sections->update();
            } else {
                $currentSectionNo = (int)$request->section_no;

                // Update Section records
                Section::where('section_no', '>=', $currentSectionNo)
                    ->get()
                    ->each(function ($section) {
                        $section->increment('section_no');
                    });

                // Update SubSection records
                SubSection::where('section_no', '>=', $currentSectionNo)
                    ->get()
                    ->each(function ($subSection) {
                        $subSection->increment('section_no');
                    });

                // Update Footnote records
                Footnote::where('section_no', '>=', $currentSectionNo)
                    ->get()
                    ->each(function ($footnote) {
                        $footnote->increment('section_no');
                    });

                $sections->section_content = $request->section_content ?? null;
                $sections->section_title = $request->section_title ?? null;
                $sections->section_no = $request->section_no ?? null;
                $sections->update();
            }

            // Store Sub-Sections
            if ($request->has('sub_section_title')) {
                foreach ($request->sub_section_title as $key => $item) {
                    // Check if the key exists before using it
                    if ($request->filled('sub_section_id.' . $key)) {
                        $sub_section = SubSection::find($request->sub_section_id[$key]);
                        if ($sub_section) {

                            if ($sub_section->sub_section_no == $request->sub_section_no) {

                                $sub_section->sub_section_title = $request->sub_section_title[$key] ?? null;
                                $sub_section->sub_section_no = $request->sub_section_no[$key];
                                $sub_section->sub_section_content = $request->sub_section[$key] ?? null;
                                $sub_section->update();
                            } else {
                                $new_sub_section_no = $request->sub_section_no[$key];

                                // Update existing SubSections with sub_section_no >= $new_sub_section_no
                                SubSection::where('sub_section_no', '>=', $new_sub_section_no)->get()
                                    ->each(function ($subSection) {
                                        $subSection->increment('sub_section_no');
                                    });
                        
                                // Update the current SubSection
                                $sub_section->sub_section_title = $request->sub_section_title[$key] ?? null;
                                $sub_section->sub_section_no = $new_sub_section_no;
                                $sub_section->sub_section_content = $request->sub_section[$key] ?? null;
                                $sub_section->update();
                            }
                        }
                    } else {
                        SubSection::where('sub_section_no', '>=', $request->sub_section_no[$key])->get()
                            ->each(function ($subSection) {
                                $subSection->increment('sub_section_no');
                            });
                        $subsec = new SubSection();
                        $subsec->section_id = $id ?? null;
                        $subsec->sub_section_no = $sections->sub_section_no[$key];
                        $subsec->section_no = $sections->section_no ?? null;
                        $subsec->act_id = $sections->act_id ?? null;
                        $subsec->chapter_id = $sections->chapter_id ?? null;
                        $subsec->parts_id = $sections->parts_id ?? null;
                        $subsec->sub_section_title = $request->sub_section_title[$key] ?? null;
                        $subsec->sub_section_content = $request->sub_section[$key] ?? null;
                        $subsec->save();
                    }
                }
            }

            // Store Footnotes
            if ($request->has('footnote_title')) {
                foreach ($request->footnote_title as $key => $item) {
                    // Check if the key exists before using it
                    if ($request->filled('footnote_id.' . $key)) {
                        $foot = Footnote::find($request->footnote_id[$key]);

                        if ($foot) {
                            $foot->footnote_title = $request->footnote_title[$key] ?? null;
                            $foot->footnote_content = $request->footnote[$key] ?? null;
                            $foot->update();
                        }
                    } else {
                        $footnote = new Footnote();
                        $footnote->section_id = $id ?? null;
                        $footnote->section_no = $sections->section_no ?? null;
                        $footnote->act_id = $sections->act_id ?? null;
                        $footnote->chapter_id = $sections->chapter_id ?? null;
                        $footnote->parts_id = $sections->parts_id ?? null;
                        $footnote->footnote_title = $request->footnote_title[$key] ?? null;
                        $footnote->footnote_content = $request->footnote[$key] ?? null;
                        $footnote->save();
                    }
                }
            }

            return redirect()->route('get_act_section', ['id' => $sections->act_id])->with('success', 'Section updated successfully');
        } catch (\Exception $e) {
            \Log::error('Error updating Act: ' . $e->getMessage());
            return redirect()->route('edit-section', ['id' => $id])->withErrors(['error' => 'Failed to update Section. Please try again.' . $e->getMessage()]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
