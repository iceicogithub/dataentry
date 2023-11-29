<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Category;
use App\Models\MainType;
use App\Models\Parts;
use App\Models\PartsType;
use App\Models\Section;
use App\Models\State;
use App\Models\Status;
use App\Models\SubType;
use Illuminate\Http\Request;

class ActController extends Controller
{

    public function index()
    {
        $category = Category::all();
        $status = Status::all();
        return view('admin.act.index', compact('category', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = Category::all();
        $status = Status::all();
        $states = State::all();
        $mtype = MainType::all();
        $stype = SubType::all();
        $parts = PartsType::all();
        return view('admin.act.create', compact('category', 'status', 'states', 'mtype', 'parts', 'stype'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {

        // dd($request);
        // die();

        $act = new Act();
        $act->category_id = $request->category_id;
        $act->state_id = $request->state_id ?? null;
        $act->act_title = $request->act_title;
        $act->act_content = $request->act_content ?? null;
        $act->save();

        foreach ($request->maintype_id as $key => $maintypeId) {
            if ($maintypeId == "2") {
                $parts = new Parts();
                $parts->act_id = $act->id ?? null;
                $parts->maintype_id = $maintypeId; // Use $maintypeId instead of $request->maintype_id
                $parts->partstype_id = $request->partstype_id[$key] ?? null;
                $parts->parts_title = $request->parts_title[$key] ?? null;
                $parts->save();

                $subtypes_id = $request->subtypes_id[$key] ?? null;

                foreach ($request->section_title[$key] as $sectiontitle) {
                    $section = Section::create([
                        'act_id' => $act->id,
                        'partstype_id' => $parts->id,
                        'subtypes_id' => $subtypes_id,
                        'section_title' => $sectiontitle,
                    ]);
                }

            } else {
                dd("something went wrong");
            }

        }

        // $actTitles = $request->input('act_title');
        // $chapterTitles = $request->input('chapter_title');
        // $sectionTitles = $request->input('section_title');

        // try {

        //     // Create Act
        //     $act = new Act();
        //     $act->category_id = $request->category_id;
        //     $act->state_id = $request->state_id ?? null;
        //     $act->act_title = $request->act_title;
        //     $act->act_content = $request->act_content ?? null;
        //     $act->save();

        //     // Create Parts
        //     if ($request->has('maintype_id')) {
        //         foreach ($request->maintype_id as $key => $maintypeId) {
        //             // dd($request);
        //             // die();
        //                 $parts = new Parts();
        //                 $parts->act_id = $act->id ?? null;
        //                 $parts->maintype_id = $request->maintype_id ?? null;
        //                 $parts->partstype_id = $request->partstype_id[$key] ?? null;
        //                 $parts->parts_title = $request->parts_title[$key] ?? null;
        //                $a = $parts->save();
        //                if($a){
        //                 dd('inserted');
        //                 die();
        //                }else{
        //                 dd('false');
        //                 die();
        //                }

        //         }
        //     }else{
        // dd($request);
        // die();
        // }

        // Create Sections
        // if ($request->has('subtypes_id')) {
        //     foreach ($request->subtypes_id as $key => $subtypeId) {
        //             $section = new Section();
        //             $section->act_id = $act->id ?? null;
        //             $section->subtypes_id = $subtypeId ?? null;
        //             $section->partstype_id = $request->partstype_id[$key] ?? null;
        //             $section->section_title = $request->section_title[$key] ?? null;
        //             $section->section_content = $request->section_content[$key] ?? null;
        //             $section->save();

        //     }
        // }else{
        //     // dd($request);
        //     // die();
        // }

        //     return redirect()->route('act')->with('success', 'Act created successfully');
        // } catch (\Exception $e) {
        //     \Log::error('Error creating Act: ' . $e->getMessage());

        //     return redirect()->route('act')->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        // }
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
