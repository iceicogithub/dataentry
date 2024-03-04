<?php

namespace App\Http\Controllers;

use App\Models\Appendices;
use App\Models\Appendix;
use App\Models\Chapter;
use App\Models\Footnote;
use App\Models\Parts;
use App\Models\Priliminary;
use App\Models\Schedule;
use App\Models\SubAppendix;
use Illuminate\Http\Request;

class AppendixController extends Controller
{

    public function edit_appendix($id)
    {
        $appendix = Appendix::with('ChapterModel', 'Partmodel', 'Appendicesmodel', 'Schedulemodel', 'PriliminaryModel')->where('appendix_id', $id)->first();
        $subappendix = Appendix::where('appendix_id', $id)
            ->with([
                'subAppendixModel',
                'footnoteModel' => function ($query) {
                    $query->whereNull('sub_appendix_id');
                }
            ])
            ->get();

        $sub_appendix_f = SubAppendix::where('appendix_id', $id)->with('footnoteModel')->get();

        $count = 0;

        if ($sub_appendix_f) {
            foreach ($sub_appendix_f as $sub_appendix) {
                $count += $sub_appendix->footnoteModel->count();
            }
        }



        return view('admin.appendix.edit', compact('appendix', 'subappendix', 'sub_appendix_f', 'count'));
    }


    public function update(Request $request, $id)
    {
        // dd($request);
        // die();

        // try {
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
        if ($request->has('schedule_id')) {
            $schedule = Schedule::find($request->schedule_id);

            if ($schedule) {
                $schedule->schedule_title = $request->schedule_title;
                $schedule->update();
            }
        }
        if ($request->has('appendices_id')) {
            $appendices = Appendices::find($request->appendices_id);

            if ($appendices) {
                $appendices->appendices_title = $request->appendices_title;
                $appendices->update();
            }
        }


        // Check if section_id exists in the request
        if (!$request->has('appendix_id')) {
            return redirect()->route('edit-appendix', ['id' => $id])->withErrors(['error' => 'Appendix ID is missing']);
        }

        $appendix = Appendix::find($request->appendix_id);

        // Check if the section is found
        if (!$appendix) {
            return redirect()->route('edit-appendix', ['id' => $id])->withErrors(['error' => 'Appendix not found']);
        }
        if ($appendix) {

            $appendix->appendix_content = $request->appendix_content ?? null;
            $appendix->appendix_title = $request->appendix_title ?? null;
            $appendix->appendix_no = $request->appendix_no ?? null;
            $appendix->update();


            if ($request->has('appendix_footnote_content')) {
                foreach ($request->appendix_footnote_content as $key => $items) {
                    // Check if the key exists before using it
                    foreach ($items as $kys => $item) {
                        // Check if the sec_footnote_id exists at the specified index
                        if (isset($request->appendix_footnote_id[$key][$kys])) {
                            // Use first() instead of get() to get a single model instance
                            $foot = Footnote::find($request->appendix_footnote_id[$key][$kys]);

                            if ($foot) {
                                $foot->update([
                                    'footnote_content' => $item ?? null,
                                    'footnote_no' => $request->appendix_footnote_no[$key][$kys] ?? null,
                                ]);
                            }
                        } else {
                            // Create a new footnote
                            $footnote = new Footnote();
                            $footnote->appendix_id = $id ?? null;
                            $footnote->appendix_no = $appendix->appendix_no ?? null;
                            $footnote->act_id = $appendix->act_id ?? null;
                            $footnote->chapter_id = $appendix->chapter_id ?? null;
                            $footnote->parts_id = $appendix->parts_id ?? null;
                            $footnote->priliminary_id = $appendix->priliminary_id ?? null;
                            $footnote->schedule_id = $appendix->schedule_id ?? null;
                            $footnote->appendices_id = $appendix->appendices_id ?? null;
                            $footnote->footnote_content = $item ?? null;
                            $footnote->save();
                        }
                    }
                }
            }
        }

        // Store Sub-Sections

        if ($request->has('sub_appendix_no')) {
            foreach ($request->sub_appendix_no as $key => $item) {
                // Check if sub_section_id is present in the request
                if ($request->filled('sub_appendix_id') && is_array($request->sub_appendix_id) && array_key_exists($key, $request->sub_appendix_id)) {

                    $sub_appendix = SubAppendix::find($request->sub_appendix_id[$key]);

                    // Check if $sub_section is found in the database and the IDs match
                    if ($sub_appendix && $sub_appendix->sub_appendix_id == $request->sub_appendix_id[$key]) {
                        $sub_appendix->sub_appendix_no = $item ?? null;
                        $sub_appendix->sub_appendix_content = $request->sub_appendix_content[$key] ?? null;
                        $sub_appendix->update();

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
                                    $footnote->sub_appendix_id = $sub_appendix->sub_appendix_id;
                                    $footnote->appendix_id = $id ?? null;
                                    $footnote->act_id = $appendix->act_id ?? null;
                                    $footnote->chapter_id = $appendix->chapter_id ?? null;
                                    $footnote->parts_id = $appendix->parts_id ?? null;
                                    $footnote->priliminary_id = $appendix->priliminary_id ?? null;
                                    $footnote->schedule_id = $appendix->schedule_id ?? null;
                                    $footnote->appendices_id = $appendix->appendices_id ?? null;
                                    $footnote->footnote_content = $item ?? null;
                                    $footnote->save();
                                }
                            }
                        }
                    }
                } else {
                    // Existing subsection not found, create a new one
                    $subappendix = new SubAppendix();
                    $subappendix->appendix_id = $id ?? null;
                    $subappendix->sub_appendix_no = $item ?? null;
                    $subappendix->appendix_no = $appendix->appendix_no ?? null;
                    $subappendix->act_id = $appendix->act_id ?? null;
                    $subappendix->chapter_id = $appendix->chapter_id ?? null;
                    $subappendix->parts_id = $appendix->parts_id ?? null;
                    $subappendix->priliminary_id = $appendix->priliminary_id ?? null;
                    $subappendix->schedule_id = $appendix->schedule_id ?? null;
                    $subappendix->appendices_id = $appendix->appendices_id ?? null;
                    $subappendix->sub_appendix_content = $request->sub_appendix_content[$key] ?? null;
                    $subappendix->save();

                    if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                        foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                            // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                            if (isset($request->sub_footnote_content[$key][$kys])) {
                                // Create a new footnote for the newly created subsection
                                $footnote = new Footnote();
                                $footnote->sub_appendix_id = $subappendix->sub_appendix_id;
                                $footnote->appendix_id = $id ?? null;
                                $footnote->act_id = $appendix->act_id ?? null;
                                $footnote->chapter_id = $appendix->chapter_id ?? null;
                                $footnote->parts_id = $appendix->parts_id ?? null;
                                $footnote->priliminary_id = $appendix->priliminary_id ?? null;
                                $footnote->schedule_id = $appendix->schedule_id ?? null;
                                $footnote->appendices_id = $appendix->appendices_id ?? null;
                                $footnote->footnote_content = $item ?? null;
                                $footnote->footnote_no = $request->sub_footnote_no[$key][$kys] ?? null;
                                $footnote->save();
                            }
                        }
                    }
                }
            }
        }



        return redirect()->route('get_act_section', ['id' => $appendix->act_id])->with('success', 'Appendix updated successfully');
        // } catch (\Exception $e) {
        //     \Log::error('Error updating Act: ' . $e->getMessage());
        //     return redirect()->route('edit-appendix', ['id' => $id])->withErrors(['error' => 'Failed to update Appendix. Please try again.' . $e->getMessage()]);
        // }
    }

    public function add_below_new_appendix(Request $request, $id, $appendix_id, $appendix_rank)
    {

        $appendix_rank = $appendix_rank;
        $appendix = Appendix::with('ChapterModel', 'Partmodel', 'PriliminaryModel', 'Appendicesmodel', 'Schedulemodel')->where('act_id', $id)
            ->where('appendix_id', $appendix_id)->first();

        return view('admin.appendix.add_new', compact('appendix', 'appendix_rank'));
    }

    public function add_new_appendix(Request $request)
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
        if ($request->has('schedule_id')) {
            $schedule = Schedule::find($request->schedule_id);

            if ($schedule) {
                $schedule->schedule_title = $request->schedule_title;
                $schedule->update();
            }
        }
        if ($request->has('appendices_id')) {
            $appendices = Appendices::find($request->appendices_id);

            if ($appendices) {
                $appendices->appendices_title = $request->appendices_title;
                $appendices->update();
            }
        }


        $id = $request->act_id;
        // $appendix_no = $request->appendix_no;
        $appendix_rank = $request->appendix_rank;
        $maintypeId = $request->maintype_id;

        // Calculate the next section number
        // $nextAppendixNo = $appendix_no;
        $nextAppendixRank = $appendix_rank + 0.01;



        // Update the existing sections' section_no in the Section table
        // Section::where('section_no', '>=', $nextSectionNo)
        //     ->increment('section_no');

        // Create the new section with the incremented section_no
        $appendix = Appendix::create([
            'appendix_rank' => $nextAppendixRank ?? 1,
            'appendix_no' =>$request->appendix_no ?? null,
            'act_id' => $request->act_id,
            'maintype_id' => $maintypeId,
            'chapter_id' => $request->chapter_id ?? null,
            'priliminary_id' => $request->priliminary_id ?? null,
            'parts_id' => $request->parts_id ?? null,
            'schedule_id' => $request->schedule_id ?? null,
            'appendices_id' => $request->appendices_id ?? null,
            'subtypes_id' => $request->subtypes_id,
            'appendix_title' => $request->appendix_title,
            'appendix_content' => $request->appendix_content,
        ]);

        if ($request->has('appendix_footnote_content')) {
            foreach ($request->appendix_footnote_content as $key => $item) {
                // Check if the key exists before using it
                if (isset($request->appendix_footnote_content[$key])) {
                    // Create a new footnote
                    $footnote = new Footnote();
                    $footnote->appendix_id = $appendix->appendix_id ?? null;
                    $footnote->act_id = $request->act_id ?? null;
                    $footnote->chapter_id = $request->chapter_id ?? null;
                    $footnote->priliminary_id = $request->priliminary_id ?? null;
                    $footnote->parts_id = $request->parts_id ?? null;
                    $footnote->schedule_id = $request->schedule_id ?? null;
                    $footnote->appendices_id = $request->appendices_id ?? null;
                    $footnote->footnote_content = $item ?? null;
                    $footnote->save();
                }
            }
        }

        if ($request->has('sub_appendix_no')) {
            foreach ($request->sub_appendix_no as $key => $item) {
                // Existing subsection not found, create a new one
                $sub_appendix = SubAppendix::create([
                    'appendix_id' => $appendix->appendix_id,
                    'sub_appendix_no' => $item ?? null,
                    'appendix_no' =>  $request->appendix_no ?? null,
                    'act_id' => $request->act_id,
                    'chapter_id' => $maintypeId == "1" ? $request->chapter_id : null,
                    'parts_id' => $maintypeId == "2" ? $request->parts_id : null,
                    'priliminary_id' => $maintypeId == "3" ? $request->priliminary_id : null,
                    'schedule_id' => $maintypeId == "4" ? $request->schedule_id : null,
                    'appendices_id' => $maintypeId == "5" ? $request->appendices_id : null,
                    'sub_appendix_content' => $request->sub_appendix_content[$key] ?? null,
                ]);

                if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                    foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                        // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                        if (isset($request->sub_footnote_content[$key][$kys])) {
                            // Create a new footnote for the newly created subsection
                            $footnote = new Footnote();
                            $footnote->sub_appendix_id = $sub_appendix->sub_appendix_id;
                            $footnote->appendix_id = $appendix->appendix_id ?? null;
                            $footnote->act_id = $request->act_id ?? null;
                            $footnote->chapter_id = $request->chapter_id ?? null;
                            $footnote->parts_id = $request->parts_id ?? null;
                            $footnote->priliminary_id = $request->priliminary_id ?? null;
                            $footnote->schedule_id = $request->schedule_id ?? null;
                            $footnote->appendices_id = $request->appendices_id ?? null;
                            $footnote->footnote_content = $item ?? null;
                            $footnote->save();
                        }
                    }
                }
            }
        }

        return redirect()->route('get_act_section', ['id' => $id])->with('success', 'Ayrticle created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }


    public function view_sub_appendix(Request $request, $id)
    {
        $appendix = Appendix::where('appendix_id', $id)->first();
        $sub_appendix = SubAppendix::where('appendix_id', $id)->with('footnoteModel')->get();
        return view('admin.appendix.view', compact('appendix', 'sub_appendix'));
    }

    public function destroy_sub_appendix(string $id)
    {
        try {
            $subappendix = SubAppendix::find($id);

            if (!$subappendix) {
                return redirect()->back()->withErrors(['error' => 'Sub-Appendix not found.']);
            }

            Footnote::where('sub_appendix_id', $id)->delete();

            $subappendix->delete();

            return redirect()->back()->with('success', 'Sub-Appendix and related records deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting Sub-Appendix: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete Sub-Appendix. Please try again.' . $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            $appendix = Appendix::find($id);

            if (!$appendix) {
                return redirect()->back()->withErrors(['error' => 'Appendix not found.']);
            }

            SubAppendix::where('appendix_id', $id)->delete();
            Footnote::where('appendix_id', $id)->delete();

            $appendix->delete();

            return redirect()->back()->with('success', 'Appendix and related records deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting appendix: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete appendix. Please try again.' . $e->getMessage()]);
        }
    }
    public function delete_footnote(string $id)
    {
        try {
            $footnote = Footnote::find($id);

            if (!$footnote) {
                return redirect()->back()->withErrors(['error' => 'Footnote not found.']);
            }


            $footnote->delete();

            return redirect()->back()->with('success', 'Footnote deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting footnote: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete footnote. Please try again.' . $e->getMessage()]);
        }
    }

}

