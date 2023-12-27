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
        // dd($request);
        // die();
        try {
            $chapter = Chapter::find($request->chapter_id);
            $chapter->chapter_title = $request->chapter_title;
            $chapter->update();

            $id = $request->act_id;
            $sec_no = $request->section_no;
            $maintypeId = $request->maintype_id;

            // Calculate the next section number
            $nextSectionNo = $sec_no + 1;

            // Update the existing sections' section_no in the Section table
            // Section::where('section_no', '>=', $nextSectionNo)
            //     ->increment('section_no');

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
                    // SubSection::where('section_no', '>=', $nextSectionNo)
                    //     ->increment('section_no');

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
                    // Footnote::where('section_no', '>=', $nextSectionNo)
                    //     ->increment('section_no');

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
        // dd($sections);
        // die();
        return view('admin.section.edit', compact('sections', 'subsec'));
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
            } elseif ($request->has('parts_id')) {
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
            }

            // Store Sub-Sections
            if ($request->has('sub_section_title')) {
                foreach ($request->sub_section_title as $key => $item) {
                    // Check if the key exists before using it
                    if ($request->filled('sub_section_id.' . $key)) {
                        $sub_section = SubSection::find($request->sub_section_id[$key]);

                        if ($sub_section->sub_section_id == $request->sub_section_id) {
                            $sub_section->sub_section_title = $item ?? null;
                            $sub_section->sub_section_no = $request->sub_section_no[$key] ?? null;
                            $sub_section->sub_section_content = $request->sub_section[$key] ?? null;
                            $sub_section->update();
                        } else {
                            $subsec = new SubSection();
                            $subsec->section_id = $id ?? null;
                            $subsec->sub_section_no = $request->sub_section_no[$key] ?? null;
                            $subsec->section_no = $sections->section_no ?? null;
                            $subsec->act_id = $sections->act_id ?? null;
                            $subsec->chapter_id = $sections->chapter_id ?? null;
                            $subsec->parts_id = $sections->parts_id ?? null;
                            $subsec->sub_section_title = $item ?? null;
                            $subsec->sub_section_content = $request->sub_section[$key] ?? null;
                            $subsec->save();
                        }
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

    public function destroy(string $id)
    {
        try {
            $section = Section::find($id);
    
            if (!$section) {
                return redirect()->back()->withErrors(['error' => 'Section not found.']);
            }
    
            $section->delete();
    
            return redirect()->back()->with('success', 'Section deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting section: ' . $e->getMessage());
    
            return redirect()->back()->withErrors(['error' => 'Failed to delete section. Please try again.' . $e->getMessage()]);
        }
    }
    
}
