<?php

namespace App\Http\Controllers;

use App\Models\Appendices;
use App\Models\Appendix;
use App\Models\Chapter;
use App\Models\Footnote;
use App\Models\MainOrder;
use App\Models\Parts;
use App\Models\Priliminary;
use App\Models\Schedule;
use App\Models\SubAppendices;
use Illuminate\Http\Request;

class AppendicesController extends Controller
{

    public function edit_appendices($id,Request $request)
    {
        $appendices = Appendices::with('ChapterModel', 'Partmodel', 'Appendixmodel', 'Schedulemodel', 'PriliminaryModel','MainOrderModel')->where('appendices_id', $id)->first();
        $subappendices = Appendices::where('appendices_id', $id)
            ->with([
                'subAppendicesModel',
                'footnoteModel' => function ($query) {
                    $query->whereNull('sub_appendices_id');
                }
            ])
            ->get();

        $sub_appendices_f = SubAppendices::where('appendices_id', $id)->with('footnoteModel')->get();

        $count = 0;

        if ($sub_appendices_f) {
            foreach ($sub_appendices_f as $sub_appendices) {
                $count += $sub_appendices->footnoteModel->count();
            }
        }


        $currentPage = $request->page;
        return view('admin.appendices.edit', compact('appendices', 'subappendices', 'sub_appendices_f', 'count','currentPage'));
    }


    public function update(Request $request, $id)
    {
        // dd($request);
        // die();

        // try {
            $currentPage = $request->currentPage;
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
        if ($request->has('appendix_id')) {
            $appendix = Appendix::find($request->appendix_id);

            if ($appendix) {
                $appendix->appendix_title = $request->appendix_title;
                $appendix->update();
            }
        }
        if ($request->has('main_order_id')) {
            $main_order = MainOrder::find($request->main_order_id);

            if ($main_order) {
                $main_order->main_order_title = $request->main_order_title;
                $main_order->update();
            }
        }


        // Check if section_id exists in the request
        if (!$request->has('appendices_id')) {
            return redirect()->route('edit-appendices', ['id' => $id])->withErrors(['error' => 'Appendices ID is missing']);
        }

        $appendices = Appendices::find($request->appendices_id);

        // Check if the section is found
        if (!$appendices) {
            return redirect()->route('edit-appendices', ['id' => $id])->withErrors(['error' => 'Appendices not found']);
        }
        if ($appendices) {

            $appendices->appendices_content = $request->appendices_content ?? null;
            $appendices->appendices_title = $request->appendices_title ?? null;
            $appendices->appendices_no = $request->appendices_no ?? null;
            $appendices->update();


            if ($request->has('appendices_footnote_content')) {
                foreach ($request->appendices_footnote_content as $key => $items) {
                    // Check if the key exists before using it
                    foreach ($items as $kys => $item) {
                        // Check if the sec_footnote_id exists at the specified index
                        if (isset($request->appendices_footnote_id[$key][$kys])) {
                            // Use first() instead of get() to get a single model instance
                            $foot = Footnote::find($request->appendices_footnote_id[$key][$kys]);

                            if ($foot) {
                                $foot->update([
                                    'footnote_content' => $item ?? null,
                                    'footnote_no' => $request->appendices_footnote_no[$key][$kys] ?? null,
                                ]);
                            }
                        } else {
                            // Create a new footnote
                            $footnote = new Footnote();
                            $footnote->appendices_id = $id ?? null;
                            $footnote->appendices_no = $appendices->appendices_no ?? null;
                            $footnote->act_id = $appendices->act_id ?? null;
                            $footnote->chapter_id = $appendices->chapter_id ?? null;
                            $footnote->main_order_id = $appendices->main_order_id ?? null;
                            $footnote->parts_id = $appendices->parts_id ?? null;
                            $footnote->priliminary_id = $appendices->priliminary_id ?? null;
                            $footnote->schedule_id = $appendices->schedule_id ?? null;
                            $footnote->appendix_id = $appendices->appendix_id ?? null;
                            $footnote->footnote_content = $item ?? null;
                            $footnote->save();
                        }
                    }
                }
            }
        }

        // Store Sub-Sections

        if ($request->has('sub_appendices_no')) {
            foreach ($request->sub_appendices_no as $key => $item) {
                // Check if sub_section_id is present in the request
                if ($request->filled('sub_appendices_id') && is_array($request->sub_appendices_id) && array_key_exists($key, $request->sub_appendices_id)) {

                    $sub_appendices = SubAppendices::find($request->sub_appendices_id[$key]);

                    // Check if $sub_section is found in the database and the IDs match
                    if ($sub_appendices && $sub_appendices->sub_appendices_id == $request->sub_appendices_id[$key]) {
                        $sub_appendices->sub_appendices_no = $item ?? null;
                        $sub_appendices->sub_appendices_content = $request->sub_appendices_content[$key] ?? null;
                        $sub_appendices->update();

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
                                    $footnote->sub_appendices_id = $sub_appendices->sub_appendices_id;
                                    $footnote->appendices_id = $id ?? null;
                                    $footnote->act_id = $appendices->act_id ?? null;
                                    $footnote->chapter_id = $appendices->chapter_id ?? null;
                                    $footnote->main_order_id = $appendices->main_order_id ?? null;
                                    $footnote->parts_id = $appendices->parts_id ?? null;
                                    $footnote->priliminary_id = $appendices->priliminary_id ?? null;
                                    $footnote->schedule_id = $appendices->schedule_id ?? null;
                                    $footnote->appendix_id = $appendices->appendix_id ?? null;
                                    $footnote->footnote_content = $item ?? null;
                                    $footnote->save();
                                }
                            }
                        }
                    }
                } else {
                    // Existing subsection not found, create a new one
                    $subappendices = new SubAppendices();
                    $subappendices->appendices_id = $id ?? null;
                    $subappendices->sub_appendices_no = $item ?? null;
                    $subappendices->appendices_no = $appendices->appendices_no ?? null;
                    $subappendices->act_id = $appendices->act_id ?? null;
                    $subappendices->chapter_id = $appendices->chapter_id ?? null;
                    $subappendices->main_order_id = $appendices->main_order_id ?? null;
                    $subappendices->parts_id = $appendices->parts_id ?? null;
                    $subappendices->priliminary_id = $appendices->priliminary_id ?? null;
                    $subappendices->schedule_id = $appendices->schedule_id ?? null;
                    $subappendices->appendix_id = $appendices->appendix_id ?? null;
                    $subappendices->sub_appendices_content = $request->sub_appendices_content[$key] ?? null;
                    $subappendices->save();

                    if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                        foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                            // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                            if (isset($request->sub_footnote_content[$key][$kys])) {
                                // Create a new footnote for the newly created subsection
                                $footnote = new Footnote();
                                $footnote->sub_appendices_id = $subappendices->sub_appendices_id;
                                $footnote->appendices_id = $id ?? null;
                                $footnote->act_id = $appendices->act_id ?? null;
                                $footnote->chapter_id = $appendices->chapter_id ?? null;
                                $footnote->main_order_id = $appendices->main_order_id ?? null;
                                $footnote->parts_id = $appendices->parts_id ?? null;
                                $footnote->priliminary_id = $appendices->priliminary_id ?? null;
                                $footnote->schedule_id = $appendices->schedule_id ?? null;
                                $footnote->appendix_id = $appendices->appendix_id ?? null;
                                $footnote->footnote_content = $item ?? null;
                                $footnote->footnote_no = $request->sub_footnote_no[$key][$kys] ?? null;
                                $footnote->save();
                            }
                        }
                    }
                }
            }
        }



        return redirect()->route('get_act_section', ['id' => $appendices->act_id,'page' => $currentPage])->with('success', 'Appendices updated successfully');
        // } catch (\Exception $e) {
        //     \Log::error('Error updating Act: ' . $e->getMessage());
        //     return redirect()->route('edit-appendices', ['id' => $id])->withErrors(['error' => 'Failed to update Appendices. Please try again.' . $e->getMessage()]);
        // }
    }

    public function add_below_new_appendices(Request $request, $id, $appendices_id)
    {

        
        $appendices = Appendices::with('ChapterModel', 'Partmodel', 'PriliminaryModel', 'Appendixmodel', 'Schedulemodel','MainOrderModel')->where('act_id', $id)
            ->where('appendices_id', $appendices_id)->first();

        return view('admin.appendices.add_new', compact('appendices'));
    }

    public function add_new_appendices(Request $request)
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
        if ($request->has('appendix_id')) {
            $appendix = Appendix::find($request->appendix_id);

            if ($appendix) {
                $appendix->appendix_title = $request->appendix_title;
                $appendix->update();
            }
        }
        if ($request->has('main_order_id')) {
            $main_order = MainOrder::find($request->main_order_id);

            if ($main_order) {
                $main_order->main_order_title = $request->main_order_title;
                $main_order->update();
            }
        }


        $id = $request->act_id;
        // $appendices_no = $request->appendices_no;
        $appendices_rank = $request->appendices_rank;
        $maintypeId = $request->maintype_id;

        // Calculate the next section number
        // $nextAppendicesNo = $appendices_no;
        $oldAppendicesRank = $request->click_appendices_rank;
        $nextAppendicesRank = $oldAppendicesRank + 0.01;



        // Update the existing sections' section_no in the Section table
        // Section::where('section_no', '>=', $nextSectionNo)
        //     ->increment('section_no');

        // Create the new section with the incremented section_no
        $appendices = Appendices::create([
            'appendices_rank' => $nextAppendicesRank,
            'appendices_no' =>$request->appendices_no ?? null,
            'act_id' => $request->act_id,
            'maintype_id' => $maintypeId,
            'chapter_id' => $request->chapter_id ?? null,
            'main_order_id' => $request->main_order_id ?? null,
            'priliminary_id' => $request->priliminary_id ?? null,
            'parts_id' => $request->parts_id ?? null,
            'schedule_id' => $request->schedule_id ?? null,
            'appendix_id' => $request->appendix_id ?? null,
            'subtypes_id' => $request->subtypes_id,
            'appendices_title' => $request->appendices_title,
            'appendices_content' => $request->appendices_content,
            'is_append' => 1,
            'serial_no' => $request->serial_no,

        ]);

        if ($request->has('appendices_footnote_content')) {
            foreach ($request->appendices_footnote_content as $key => $item) {
                // Check if the key exists before using it
                if (isset($request->appendices_footnote_content[$key])) {
                    // Create a new footnote
                    $footnote = new Footnote();
                    $footnote->appendices_id = $appendices->appendices_id ?? null;
                    $footnote->act_id = $request->act_id ?? null;
                    $footnote->chapter_id = $request->chapter_id ?? null;
                    $footnote->main_order_id = $request->main_order_id ?? null;
                    $footnote->priliminary_id = $request->priliminary_id ?? null;
                    $footnote->parts_id = $request->parts_id ?? null;
                    $footnote->schedule_id = $request->schedule_id ?? null;
                    $footnote->appendix_id = $request->appendix_id ?? null;
                    $footnote->footnote_content = $item ?? null;
                    $footnote->save();
                }
            }
        }

        if ($request->has('sub_appendices_no')) {
            foreach ($request->sub_appendices_no as $key => $item) {
                // Existing subsection not found, create a new one
                $sub_appendices = SubAppendices::create([
                    'appendices_id' => $appendices->appendices_id,
                    'sub_appendices_no' => $item ?? null,
                    'appendices_no' =>  $request->appendices_no ?? null,
                    'act_id' => $request->act_id,
                    'chapter_id' => $maintypeId == "1" ? $request->chapter_id : null,
                    'main_order_id' => $maintypeId == "6" ? $request->main_order_id : null,
                    'parts_id' => $maintypeId == "2" ? $request->parts_id : null,
                    'priliminary_id' => $maintypeId == "3" ? $request->priliminary_id : null,
                    'schedule_id' => $maintypeId == "4" ? $request->schedule_id : null,
                    'appendix_id' => $maintypeId == "5" ? $request->appendix_id : null,
                    'sub_appendices_content' => $request->sub_appendices_content[$key] ?? null,
                ]);

                if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                    foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                        // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                        if (isset($request->sub_footnote_content[$key][$kys])) {
                            // Create a new footnote for the newly created subsection
                            $footnote = new Footnote();
                            $footnote->sub_appendices_id = $sub_appendices->sub_appendices_id;
                            $footnote->appendices_id = $appendices->appendices_id ?? null;
                            $footnote->act_id = $request->act_id ?? null;
                            $footnote->chapter_id = $request->chapter_id ?? null;
                            $footnote->main_order_id = $request->main_order_id ?? null;
                            $footnote->parts_id = $request->parts_id ?? null;
                            $footnote->priliminary_id = $request->priliminary_id ?? null;
                            $footnote->schedule_id = $request->schedule_id ?? null;
                            $footnote->appendix_id = $request->appendix_id ?? null;
                            $footnote->footnote_content = $item ?? null;
                            $footnote->save();
                        }
                    }
                }
            }
        }

        return redirect()->route('get_act_section', ['id' =>$appendices->act_id])->with('success', 'Appendices created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }


    public function view_sub_appendices(Request $request, $id)
    {
        $appendices = Appendices::where('appendices_id', $id)->first();
        $sub_appendices = SubAppendices::where('appendices_id', $id)->with('footnoteModel')->get();
        return view('admin.appendices.view', compact('appendices', 'sub_appendices'));
    }

    public function destroy_sub_appendices(string $id)
    {
        try {
            $subappendices = SubAppendices::find($id);

            if (!$subappendices) {
                return redirect()->back()->withErrors(['error' => 'Sub-Appendices not found.']);
            }

            Footnote::where('sub_appendices_id', $id)->delete();

            $subappendices->delete();

            return redirect()->back()->with('success', 'Sub-Appendices and related records deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting Sub-Appendices: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete Sub-Appendices. Please try again.' . $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            $appendices = Appendices::find($id);

            if (!$appendices) {
                return redirect()->back()->withErrors(['error' => 'Appendices not found.']);
            }

            SubAppendices::where('appendices_id', $id)->delete();
            Footnote::where('appendices_id', $id)->delete();

            $appendices->delete();

            return redirect()->back()->with('success', 'Appendices and related records deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting appendices: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete appendices. Please try again.' . $e->getMessage()]);
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

