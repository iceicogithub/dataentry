<?php

namespace App\Http\Controllers;

use App\Models\Appendix;
use App\Models\Chapter;
use App\Models\Footnote;
use App\Models\MainOrder;
use App\Models\Parts;
use App\Models\Priliminary;
use App\Models\Schedule;
use App\Models\Stschedule;
use App\Models\SubStschedule;
use Illuminate\Http\Request;

class stscheduleController extends Controller
{
    
    public function edit_stschedule($id)
    {
        $stschedule = Stschedule::with('ChapterModel', 'Partmodel','Appendixmodel','Schedulemodel','PriliminaryModel','MainOrderModel')->where('stschedule_id', $id)->first();
        $substschedule = Stschedule::where('stschedule_id', $id)
            ->with(['subStscheduleModel', 'footnoteModel' => function ($query) {
                $query->whereNull('sub_stschedule_id');
            }])
            ->get();

        $sub_stschedule_f = SubStschedule::where('stschedule_id', $id)->with('footnoteModel')->get();

        $count = 0;

        if ($sub_stschedule_f) {
            foreach ($sub_stschedule_f as $sub_stschedule) {
                $count += $sub_stschedule->footnoteModel->count();
            }
        }



        return view('admin.stschedule.edit', compact('stschedule', 'substschedule', 'sub_stschedule_f', 'count'));
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
            if (!$request->has('stschedule_id')) {
                return redirect()->route('edit-stschedule', ['id' => $id])->withErrors(['error' => 'Schedule ID is missing']);
            }

            $stschedule = Stschedule::find($request->stschedule_id);

            // Check if the section is found
            if (!$stschedule) {
                return redirect()->route('edit-stschedule', ['id' => $id])->withErrors(['error' => 'Schedule not found']);
            }
            if ($stschedule) {

                $stschedule->stschedule_content = $request->stschedule_content ?? null;
                $stschedule->stschedule_title = $request->stschedule_title ?? null;
                $stschedule->stschedule_no = $request->stschedule_no ?? null;
                $stschedule->update();


                if ($request->has('stschedule_footnote_content')) {
                    foreach ($request->stschedule_footnote_content as $key => $items) {
                        // Check if the key exists before using it
                        foreach ($items as $kys => $item) {
                            // Check if the sec_footnote_id exists at the specified index
                            if (isset($request->stschedule_footnote_id[$key][$kys])) {
                                // Use first() instead of get() to get a single model instance
                                $foot = Footnote::find($request->stschedule_footnote_id[$key][$kys]);

                                if ($foot) {
                                    $foot->update([
                                        'footnote_content' => $item ?? null,
                                        'footnote_no' => $request->stschedule_footnote_no[$key][$kys] ?? null,
                                    ]);
                                }
                            } else {
                                // Create a new footnote
                                $footnote = new Footnote();
                                $footnote->stschedule_id = $id ?? null;
                                $footnote->stschedule_no = $stschedule->stschedule_no ?? null;
                                $footnote->act_id = $stschedule->act_id ?? null;
                                $footnote->chapter_id = $stschedule->chapter_id ?? null;
                                $footnote->main_order_id = $stschedule->main_order_id ?? null;
                                $footnote->parts_id = $stschedule->parts_id ?? null;
                                $footnote->priliminary_id = $stschedule->priliminary_id ?? null;
                                $footnote->schedule_id = $stschedule->schedule_id ?? null;
                                $footnote->appendix_id = $stschedule->appendix_id ?? null;
                                $footnote->footnote_content = $item ?? null;
                                $footnote->save();
                            }
                        }
                    }
                }
            }

            // Store Sub-Sections

            if ($request->has('sub_stschedule_no')) {
                foreach ($request->sub_stschedule_no as $key => $item) {
                    // Check if sub_section_id is present in the request
                    if ($request->filled('sub_stschedule_id') && is_array($request->sub_stschedule_id) && array_key_exists($key, $request->sub_stschedule_id)) {

                        $sub_stschedule = SubStschedule::find($request->sub_stschedule_id[$key]);

                        // Check if $sub_section is found in the database and the IDs match
                        if ($sub_stschedule && $sub_stschedule->sub_stschedule_id == $request->sub_stschedule_id[$key]) {
                            $sub_stschedule->sub_stschedule_no = $item ?? null;
                            $sub_stschedule->sub_stschedule_content = $request->sub_stschedule_content[$key] ?? null;
                            $sub_stschedule->update();

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
                                        $footnote->sub_stschedule_id = $sub_stschedule->sub_stschedule_id;
                                        $footnote->stschedule_id = $id ?? null;
                                        $footnote->act_id = $stschedule->act_id ?? null;
                                        $footnote->chapter_id = $stschedule->chapter_id ?? null;
                                        $footnote->main_order_id = $stschedule->main_order_id ?? null;
                                        $footnote->parts_id = $stschedule->parts_id ?? null;
                                        $footnote->priliminary_id = $stschedule->priliminary_id ?? null;
                                        $footnote->schedule_id = $stschedule->schedule_id ?? null;
                                        $footnote->appendix_id = $stschedule->appendix_id ?? null;
                                        $footnote->footnote_content = $item ?? null;
                                        $footnote->save();
                                    }
                                }
                            }
                        }
                    } else {
                        // Existing subsection not found, create a new one
                        $substschedule = new SubStschedule();
                        $substschedule->stschedule_id = $id ?? null;
                        $substschedule->sub_stschedule_no = $item ?? null;
                        $substschedule->stschedule_no = $stschedule->stschedule_no ?? null;
                        $substschedule->chapter_id = $stschedule->chapter_id ?? null;
                        $substschedule->main_order_id = $stschedule->main_order_id ?? null;
                        $substschedule->parts_id = $stschedule->parts_id ?? null;
                        $substschedule->priliminary_id = $stschedule->priliminary_id ?? null;
                        $substschedule->schedule_id = $stschedule->schedule_id ?? null;
                        $substschedule->appendix_id = $stschedule->appendix_id ?? null;
                        $substschedule->sub_stschedule_content = $request->sub_stschedule_content[$key] ?? null;
                        $substschedule->save();

                        if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                            foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                                // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                                if (isset($request->sub_footnote_content[$key][$kys])) {
                                    // Create a new footnote for the newly created subsection
                                    $footnote = new Footnote();
                                    $footnote->sub_stschedule_id = $substschedule->sub_stschedule_id;
                                    $footnote->stschedule_id = $id ?? null;
                                    $footnote->act_id = $stschedule->act_id ?? null;
                                    $footnote->chapter_id = $stschedule->chapter_id ?? null;
                                    $footnote->main_order_id = $stschedule->main_order_id ?? null;
                                    $footnote->parts_id = $stschedule->parts_id ?? null;
                                    $footnote->priliminary_id = $stschedule->priliminary_id ?? null;
                                    $footnote->schedule_id = $stschedule->schedule_id ?? null;
                                    $footnote->appendix_id = $stschedule->appendix_id ?? null;
                                    $footnote->footnote_content = $item ?? null;
                                    $footnote->footnote_no = $request->sub_footnote_no[$key][$kys] ?? null;
                                    $footnote->save();
                                }
                            }
                        }
                    }
                }
            }



            return redirect()->route('get_act_section', ['id' => $stschedule->act_id])->with('success', 'Schedule updated successfully');
        // } catch (\Exception $e) {
        //     \Log::error('Error updating Act: ' . $e->getMessage());
        //     return redirect()->route('edit-stschedule', ['id' => $id])->withErrors(['error' => 'Failed to update Schedule. Please try again.' . $e->getMessage()]);
        // }
    }

    public function add_below_new_stschedule(Request $request, $id, $stschedule_id)
    {
        // dd('hello');
        // die();
        // $stschedule_rank = $stschedule_rank;
        $stschedule = Stschedule::with('ChapterModel', 'Partmodel', 'PriliminaryModel','Appendixmodel','Schedulemodel','MainOrderModel')->where('act_id', $id)
            ->where('stschedule_id', $stschedule_id)->first();

        return view('admin.stschedule.add_new', compact('stschedule'));
    }

    public function add_new_stschedule(Request $request)
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
        // $stschedule_no = $request->stschedule_no;
        $stschedule_rank = $request->stschedule_rank;
        $maintypeId = $request->maintype_id;

        // Calculate the next section number
        // $nextStscheduleNo = $stschedule_no;
        $nextStscheduleRank = $stschedule_rank + 0.01;



        // Update the existing sections' section_no in the Section table
        // Section::where('section_no', '>=', $nextSectionNo)
        //     ->increment('section_no');

        // Create the new section with the incremented section_no
        $stschedule = Stschedule::create([
            'stschedule_rank' => $nextStscheduleRank ?? 1,
            'stschedule_no' => $request->stschedule_no ?? null,
            'act_id' => $request->act_id,
            'maintype_id' => $maintypeId,
            'chapter_id' => $request->chapter_id ?? null,
            'main_order_id' => $request->main_order_id ?? null,
            'priliminary_id' => $request->priliminary_id ?? null,
            'parts_id' => $request->parts_id ?? null,
            'schedule_id' => $request->schedule_id ?? null,
            'appendix_id' => $request->appendix_id ?? null,
            'subtypes_id' => $request->subtypes_id,
            'stschedule_title' => $request->stschedule_title,
            'stschedule_content' => $request->stschedule_content,
        ]);

        if ($request->has('stschedule_footnote_content')) {
            foreach ($request->stschedule_footnote_content as $key => $item) {
                // Check if the key exists before using it
                if (isset($request->stschedule_footnote_content[$key])) {
                    // Create a new footnote
                    $footnote = new Footnote();
                    $footnote->stschedule_id = $stschedule->stschedule_id ?? null;
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

        if ($request->has('sub_stschedule_no')) {
            foreach ($request->sub_stschedule_no as $key => $item) {
                // Existing subsection not found, create a new one
                $sub_stschedule = SubStschedule::create([
                    'stschedule_id' => $stschedule->stschedule_id,
                    'sub_stschedule_no' => $item ?? null,
                    'stschedule_no' => $request->stschedule_no ?? null,
                    'act_id' => $request->act_id,
                    'chapter_id' => $maintypeId == "1" ? $request->chapter_id : null,
                    'main_order_id' => $maintypeId == "6" ? $request->main_order_id : null,
                    'parts_id' => $maintypeId == "2" ? $request->parts_id : null,
                    'priliminary_id' => $maintypeId == "3" ? $request->priliminary_id : null,
                    'schedule_id' => $maintypeId == "4" ? $request->schedule_id : null,
                    'appendix_id' => $maintypeId == "5" ? $request->appendix_id : null,
                    'sub_stschedule_content' => $request->sub_stschedule_content[$key] ?? null,
                ]);

                if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                    foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                        // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                        if (isset($request->sub_footnote_content[$key][$kys])) {
                            // Create a new footnote for the newly created subsection
                            $footnote = new Footnote();
                            $footnote->sub_stschedule_id = $sub_stschedule->sub_stschedule_id;
                            $footnote->stschedule_id = $stschedule->stschedule_id ?? null;
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

        return redirect()->route('get_act_section', ['id' => $id])->with('success', 'Ayrticle created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }
   

    public function view_sub_stschedule(Request $request,  $id)
    {
        $stschedule = Stschedule::where('stschedule_id', $id)->first();
        $sub_stschedule = SubStschedule::where('stschedule_id', $id)->with('footnoteModel')->get();
        return view('admin.stschedule.view', compact('stschedule','sub_stschedule'));
    }

    public function destroy_sub_stschedule(string $id)
    {
        try {
            $substschedule = SubStschedule::find($id);

            if (!$substschedule) {
                return redirect()->back()->withErrors(['error' => 'Sub-Schedule not found.']);
            }
            
            Footnote::where('sub_stschedule_id', $id)->delete();

            $substschedule->delete();

            return redirect()->back()->with('success', 'Sub-Schedule and related records deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting Sub-Schedule: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete Sub-Schedules. Please try again.' . $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            $stschedule = Stschedule::find($id);

            if (!$stschedule) {
                return redirect()->back()->withErrors(['error' => 'Schedule not found.']);
            }
            
            SubStschedule::where('stschedule_id', $id)->delete();
            Footnote::where('stschedule_id', $id)->delete();

            $stschedule->delete();

            return redirect()->back()->with('success', 'Schedule and related records deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting Schedule: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete schedule. Please try again.' . $e->getMessage()]);
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
