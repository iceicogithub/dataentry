<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\ActSummary;
use App\Models\Category;
use App\Models\MainType;
use App\Models\Parts;
use App\Models\PartsType;
use App\Models\Chapter;
use App\Models\Section;
use App\Models\State;
use App\Models\Status;
use App\Models\SubType;
use Illuminate\Http\Request;

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
        $act_section = Section::where('act_id', $id)->with('MainTypeModel','Partmodel', 'ChapterModel')->get();
        
        return view('admin.section.index', compact('act_section','act_id','act'));
    }

    public function update_main_act(Request $request, $id)
    {
        try {

            $act = Act::find($id);
            $act->act_title = $request->act_title;
            $act->act_no = $request->act_no ?? null;
            $act->act_date = $request->act_date ?? null;
            $act->act_description = $request->act_description ?? null;
            $act->update();


            return redirect()->back()->with('success', 'Main Act Updated Successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $id)
    {
        $category = Category::all();
        $status = Status::all();
        $states = State::all();
        $mtype = MainType::all();
        $stype = SubType::all();
        $parts = PartsType::all();

        $act = Act::where('act_id',$id)->first();
       

        return view('admin.act.create', compact('category', 'status', 'states', 'mtype', 'parts', 'stype','act'));
    }
    public function new_act()
    {
        $category = Category::all();
        $states = State::all();
        return view('admin.act.new_act', compact('category', 'states'));
    }
    public function store_new_act(Request $request)
    {
        try {

            $act = new Act();
            $act->category_id = $request->category_id;
            $act->state_id = $request->state_id ?? null;
            $act->act_title = $request->act_title;
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
        return view('admin.act.main_act',compact('act_id','mainact'));
    }

    public function store(Request $request, $id)
    {

        // dd($request);
        // die();

        try {

            $act = Act::find($id);
            $act->category_id = $request->category_id;
            $act->state_id = $request->state_id ?? null;
            $act->act_title = $request->act_title;
            $act->act_content = $request->act_content ?? null;
            $act->update();

            foreach ($request->maintype_id as $key => $maintypeId) {
                if ($maintypeId == "1") {
                    $chapt = new Chapter();
                    $chapt->act_id = $act->act_id ?? null;
                    $chapt->maintype_id = $maintypeId;
                    $chapt->chapter_title = $request->chapter_title[$key] ?? null;
                    $chapt->save();


                    $subtypes_id = $request->subtypes_id[$key] ?? null;

                    foreach ($request->section_title[$key] as $sectiontitle) {
                        //    dd($maintypeId);
                        //    die();

                        $section = Section::create([
                            'act_id' => $act->act_id,
                            'maintype_id' => $maintypeId,
                            'chapter_id' => $chapt->chapter_id,
                            'subtypes_id' => $subtypes_id,
                            'section_title' => $sectiontitle,
                        ]);
                    }
                } elseif ($maintypeId == "2") {
                    $parts = new Parts();
                    $parts->act_id = $act->act_id ?? null;
                    $parts->maintype_id = $maintypeId;
                    $parts->partstype_id = $request->partstype_id[$key] ?? null;
                    $parts->parts_title = $request->parts_title[$key] ?? null;
                    $parts->save();

                    $subtypes_id = $request->subtypes_id[$key] ?? null;

                    foreach ($request->section_title[$key] as $sectiontitle) {
                        $section = Section::create([
                            'act_id' => $act->act_id,
                            'maintype_id' => $maintypeId,
                            'parts_id' => $parts->id,
                            'subtypes_id' => $subtypes_id,
                            'section_title' => $sectiontitle,
                        ]);
                    }
                } else {
                    dd("something went wrong");
                }
            }

            return redirect()->route('get_act_section', ['id' => $id])->with('success', 'Index created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
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
        //
    }
}
