<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Chapter;
use App\Models\Category;
use App\Models\Section;
use App\Models\SubSection;
use App\Models\Footnote;
use App\Models\MainType;
use App\Models\Parts;
use App\Models\PartsType;
use App\Models\Priliminary;
use App\Models\State;
use App\Models\Status;
use App\Models\SubType;
use Illuminate\Http\Request;

class SectionController extends Controller
{

    public function index()
    {
        $status = Status::all();

        return view('admin.section.index', compact('status'));
    }

    public function add_below_new_section(Request $request, $id, $section_id, $section_rank)
    {
        
        $section_rank = $section_rank;
        $sections = Section::with('ChapterModel', 'Partmodel', 'PriliminaryModel')->where('act_id', $id)
            ->where('section_id', $section_id)->first();

        return view('admin.section.add_new', compact('sections', 'section_rank'));
    }

    public function add_new_section(Request $request)
    {
        // dd($request);
        // die();
        try {
        if ($request->has('chapter_id')) {
            $chapter = Chapter::find($request->chapter_id);

            if ($chapter) {
                $chapter->chapter_title = $request->chapter_title;
                $chapter->update();
            }
        }
        if ($request->has('priliminary_id')) {
            $priliminary = Priliminary::find($request->priliminary_id);

            if ($priliminary) {
                $priliminary->priliminary_title = $request->priliminary_title;
                $priliminary->update();
            }
        }
        if ($request->has('parts_id')) {
            $part = Parts::find($request->parts_id);

            if ($part) {
                $part->parts_title = $request->parts_title;
                $part->update();
            }
        }


        $id = $request->act_id;
        // $sec_no = $request->section_no;
        $sec_rank = $request->section_rank;
        $maintypeId = $request->maintype_id;

        // Calculate the next section number
        // $nextSectionNo = $sec_no;
        $nextSectionRank = $sec_rank + 0.01;



        // Update the existing sections' section_no in the Section table
        // Section::where('section_no', '>=', $nextSectionNo)
        //     ->increment('section_no');

        // Create the new section with the incremented section_no
        $section = Section::create([
            'section_rank' => $nextSectionRank ?? 1,
            // 'section_no' => $nextSectionNo ?? null,
            'act_id' => $request->act_id,
            'maintype_id' => $maintypeId,
            'chapter_id' => $request->chapter_id ?? null,
            'priliminary_id' => $request->priliminary_id ?? null,
            'parts_id' => $request->parts_id ?? null,
            'subtypes_id' => $request->subtypes_id,
            'section_title' => $request->section_title,
            'section_content' => $request->section_content,
        ]);

        if ($request->has('sec_footnote_content')) {
            foreach ($request->sec_footnote_content as $key => $item) {
                // Check if the key exists before using it
                if (isset($request->sec_footnote_content[$key])) {
                    // Create a new footnote
                    $footnote = new Footnote();
                    $footnote->section_id = $section->section_id ?? null;
                    $footnote->act_id = $request->act_id ?? null;
                    $footnote->chapter_id = $request->chapter_id ?? null;
                    $footnote->priliminary_id = $request->priliminary_id ?? null;
                    $footnote->parts_id = $request->parts_id ?? null;
                    $footnote->footnote_content = $item ?? null;
                    $footnote->save();
                }
            }
        }

        if ($request->has('sub_section_no')) {
            foreach ($request->sub_section_no as $key => $item) {
                // Existing subsection not found, create a new one
                $sub_section = SubSection::create([
                    'section_id' => $section->section_id,
                    'sub_section_no' => $item ?? null,
                    // 'section_no' => $nextSectionNo,
                    'act_id' => $request->act_id,
                    'chapter_id' => $maintypeId == "1" ? $request->chapter_id : null,
                    'parts_id' => $maintypeId == "2" ? $request->parts_id : null,
                    'priliminary_id' => $maintypeId == "3" ? $request->priliminary_id : null,
                    'sub_section_content' => $request->sub_section_content[$key] ?? null,
                ]);

                if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                    foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                        // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                        if (isset($request->sub_footnote_content[$key][$kys])) {
                            // Create a new footnote for the newly created subsection
                            $footnote = new Footnote();
                            $footnote->sub_section_id = $sub_section->sub_section_id;
                            $footnote->section_id = $section->section_id ?? null;
                            $footnote->act_id = $request->act_id ?? null;
                            $footnote->chapter_id = $request->chapter_id ?? null;
                            $footnote->parts_id = $request->parts_id ?? null;
                            $footnote->priliminary_id = $request->priliminary_id ?? null;
                            $footnote->footnote_content = $item ?? null;
                            $footnote->save();
                        }
                    }
                }
            }
        }

        return redirect()->route('get_act_section', ['id' => $id])->with('success', 'Section created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }

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
        $subsec = Section::where('section_id', $id)
            ->with(['subsectionModel', 'footnoteModel' => function ($query) {
                $query->whereNull('sub_section_id');
            }])
            ->get();

        $sub_section_f = SubSection::where('section_id', $id)->with('footnoteModel')->get();

        $count = 0;

        if ($sub_section_f) {
            foreach ($sub_section_f as $sub_section) {
                $count += $sub_section->footnoteModel->count();
            }
        }



        return view('admin.section.edit', compact('sections', 'subsec', 'sub_section_f', 'count'));
    }


    public function update(Request $request, $id)
    {
        // dd($request);
        // die();

        try {
            if ($request->has('chapter_id')) {
                $chapter = Chapter::find($request->chapter_id);

                if ($chapter) {
                    $chapter->chapter_title = $request->chapter_title;
                    $chapter->update();
                }
            }
            if ($request->has('parts_id')) {
                $part = Parts::find($request->parts_id);

                if ($part) {
                    $part->parts_title = $request->parts_title;
                    $part->update();
                }
            }

            // Check if section_id exists in the request
            if (!$request->has('section_id')) {
                return redirect()->route('edit-section', ['id' => $id])->withErrors(['error' => 'Section ID is missing']);
            }

            $sections = Section::find($request->section_id);

            // Check if the section is found
            if (!$sections) {
                return redirect()->route('edit-section', ['id' => $id])->withErrors(['error' => 'Section not found']);
            }
            if ($sections) {

                $sections->section_content = $request->section_content ?? null;
                $sections->section_title = $request->section_title ?? null;
                $sections->section_no = $request->section_no ?? null;
                $sections->update();


                if ($request->has('sec_footnote_content')) {
                    foreach ($request->sec_footnote_content as $key => $items) {
                        // Check if the key exists before using it
                        foreach ($items as $kys => $item) {
                            // Check if the sec_footnote_id exists at the specified index
                            if (isset($request->sec_footnote_id[$key][$kys])) {
                                // Use first() instead of get() to get a single model instance
                                $foot = Footnote::find($request->sec_footnote_id[$key][$kys]);

                                if ($foot) {
                                    $foot->update([
                                        'footnote_content' => $item ?? null,
                                        'footnote_no' => $request->sec_footnote_no[$key][$kys] ?? null,
                                    ]);
                                }
                            } else {
                                // Create a new footnote
                                $footnote = new Footnote();
                                $footnote->section_id = $id ?? null;
                                $footnote->section_no = $sections->section_no ?? null;
                                $footnote->act_id = $sections->act_id ?? null;
                                $footnote->chapter_id = $sections->chapter_id ?? null;
                                $footnote->parts_id = $sections->parts_id ?? null;
                                $footnote->footnote_content = $item ?? null;
                                $footnote->footnote_no = $request->sub_footnote_no[$key][$kys] ?? null;
                                $footnote->save();
                            }
                        }
                    }
                }
            }

            // Store Sub-Sections

            if ($request->has('sub_section_no')) {
                foreach ($request->sub_section_no as $key => $item) {
                    // Check if sub_section_id is present in the request
                    if ($request->filled('sub_section_id') && is_array($request->sub_section_id) && array_key_exists($key, $request->sub_section_id)) {

                        $sub_section = SubSection::find($request->sub_section_id[$key]);

                        // Check if $sub_section is found in the database and the IDs match
                        if ($sub_section && $sub_section->sub_section_id == $request->sub_section_id[$key]) {
                            $sub_section->sub_section_no = $item ?? null;
                            $sub_section->sub_section_content = $request->sub_section_content[$key] ?? null;
                            $sub_section->update();

                            if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                                foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                                    // Check if the sec_footnote_id exists at the specified index
                                    if (isset($request->sub_footnote_id[$key][$kys])) {
                                        // Use first() instead of get() to get a single model instance
                                        $foot = Footnote::find($request->sub_footnote_id[$key][$kys]);

                                        if ($foot) {
                                            $foot->update([
                                                'footnote_content' => $item ?? null,
                                            ]);
                                        }
                                    } else {
                                        // Create a new footnote only if sub_footnote_id does not exist
                                        $footnote = new Footnote();
                                        $footnote->sub_section_id = $sub_section->sub_section_id;
                                        $footnote->section_id = $id ?? null;
                                        $footnote->act_id = $sections->act_id ?? null;
                                        $footnote->chapter_id = $sections->chapter_id ?? null;
                                        $footnote->parts_id = $sections->parts_id ?? null;
                                        $footnote->footnote_content = $item ?? null;
                                        $footnote->save();
                                    }
                                }
                            }
                        }
                    } else {
                        // Existing subsection not found, create a new one
                        $subsec = new SubSection();
                        $subsec->section_id = $id ?? null;
                        $subsec->sub_section_no = $item ?? null;
                        $subsec->section_no = $sections->section_no ?? null;
                        $subsec->act_id = $sections->act_id ?? null;
                        $subsec->chapter_id = $sections->chapter_id ?? null;
                        $subsec->parts_id = $sections->parts_id ?? null;
                        $subsec->sub_section_content = $request->sub_section_content[$key] ?? null;
                        $subsec->save();

                        if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                            foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                                // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                                if (isset($request->sub_footnote_no[$key][$kys], $request->sub_footnote_content[$key][$kys])) { 
                                    // Create a new footnote for the newly created subsection
                                    $footnote = new Footnote();
                                    $footnote->sub_section_id = $subsec->sub_section_id;
                                    $footnote->section_id = $id ?? null;
                                    $footnote->act_id = $sections->act_id ?? null;
                                    $footnote->chapter_id = $sections->chapter_id ?? null;
                                    $footnote->parts_id = $sections->parts_id ?? null;
                                    $footnote->footnote_content = $item ?? null;
                                    $footnote->save();
                                }
                            }
                        }
                    }
                }
            }



            return redirect()->route('get_act_section', ['id' => $sections->act_id])->with('success', 'Section updated successfully');
        } catch (\Exception $e) {
            \Log::error('Error updating Act: ' . $e->getMessage());
            return redirect()->route('edit-section', ['id' => $id])->withErrors(['error' => 'Failed to update Section. Please try again.' . $e->getMessage()]);
        }
    }

    public function view_sub_section(Request $request,  $id)
    {
        $section = Section::where('section_id', $id)->first();
        $sub_section = SubSection::where('section_id', $id)->with('footnoteModel')->get();
        return view('admin.section.view', compact('section','sub_section'));
    }

    public function destroy_sub_section(string $id)
    {
        try {
            $subsection = SubSection::find($id);

            if (!$subsection) {
                return redirect()->back()->withErrors(['error' => 'Sub-Section not found.']);
            }
            
            Footnote::where('sub_section_id', $id)->delete();

            $subsection->delete();

            return redirect()->back()->with('success', 'Sub-Section and related records deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting Sub-Section: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete Sub-Section. Please try again.' . $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            $section = Section::find($id);

            if (!$section) {
                return redirect()->back()->withErrors(['error' => 'Section not found.']);
            }
            
            SubSection::where('section_id', $id)->delete();
            Footnote::where('section_id', $id)->delete();

            $section->delete();

            return redirect()->back()->with('success', 'Section and related records deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting section: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete section. Please try again.' . $e->getMessage()]);
        }
    }
    
}
