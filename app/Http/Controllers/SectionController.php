<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\SubSection;
use App\Models\Footnote;
use App\Models\Status;
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

    public function add_below_new_section(Request $request, $id)
    {
        
        
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
        $sections = Section::where('section_id', $id)->first();
        $subsec = Section::where('section_id', $id)->with('subsectionModel', 'footnoteModel')->get();
        // dd($subsec);
        // die();
        return view('admin.section.edit', compact('sections', 'subsec'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function update(Request $request, $id)
    {

        // dd($request);
        // die();

        try {
            $sections = Section::find($request->section_id);

            // dd($sections);
            // die();
            $sections->section_content = $request->section_content;
            $sections->section_title = $request->section_title;
            $sections->update();

            // Store Sub-Sections
            foreach ($request->sub_section_title as $key => $item) {

                $sub_section = SubSection::find($request->sub_section_id[$key]);
                //  dd($sub_section);
                //  die();
                if ($sub_section) {
                    $sub_section->section_id = $id;
                    $sub_section->act_id = $sections->act_id ?? null;
                    $sub_section->sub_section_title = $request->sub_section_title[$key] ?? null;
                    $sub_section->sub_section_content = $request->sub_section[$key] ?? null;
                    $sub_section->update();
                } else {
                    $subsec = new SubSection();
                    $subsec->section_id = $id;
                    $subsec->act_id = $sections->act_id ?? null;
                    $subsec->sub_section_title = $request->sub_section_title[$key] ?? null;
                    $subsec->sub_section_content = $request->sub_section[$key] ?? null;
                    $subsec->save();
                }
            }

            // Store Footnotes
            foreach ($request->footnote_title as $key => $item) {
                $foot = Footnote::find($request->footnote_id[$key]);
                // dd($foot);
                // die();
                if ($foot) {
                    $foot->section_id = $id;
                    $foot->act_id = $sections->act_id;
                    $foot->footnote_title = $request->footnote_title[$key] ?? null;
                    $foot->footnote_content = $request->footnote[$key] ?? null;
                    $foot->update();
                } else {
                    $footnote = new Footnote();
                    $footnote->section_id = $id;
                    $footnote->act_id = $sections->act_id;
                    $footnote->footnote_title = $request->footnote_title[$key] ?? null;
                    $footnote->footnote_content = $request->footnote[$key] ?? null;
                    $footnote->save();
                }
            }

            return redirect()->route('get_act_section', ['id' => $id])->with('success', 'Section updated successfully');
        } catch (\Exception $e) {
            \Log::error('Error updating Act: ' . $e->getMessage());
            return redirect()->route('get_act_section', ['id' => $id])->withErrors(['error' => 'Failed to update Section. Please try again.' . $e->getMessage()]);
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
