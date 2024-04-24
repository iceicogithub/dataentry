<?php

namespace App\Http\Controllers;

use App\Models\Annexure;
use App\Models\Appendix;
use App\Models\Chapter;
use App\Models\MainOrder;
use App\Models\Footnote;
use App\Models\Parts;
use App\Models\Priliminary;
use App\Models\Schedule;
use App\Models\SubAnnexure;
use Illuminate\Http\Request;

class AnnexureController extends Controller
{

    public function edit_annexure($id,Request $request)
    {
        $annexure = Annexure::with('ChapterModel', 'Partmodel', 'Appendixmodel', 'Schedulemodel', 'PriliminaryModel','MainOrderModel')->where('annexure_id', $id)->first();
        $subannexure = Annexure::where('annexure_id', $id)
            ->with([
                'subAnnexureModel',
                'footnoteModel' => function ($query) {
                    $query->whereNull('sub_annexure_id');
                }
            ])
            ->get();

        $sub_annexure_f = SubAnnexure::where('annexure_id', $id)->with('footnoteModel')->get();

        $count = 0;

        if ($sub_annexure_f) {
            foreach ($sub_annexure_f as $sub_annexure) {
                $count += $sub_annexure->footnoteModel->count();
            }
        }


        $currentPage = $request->page;
        return view('admin.annexure.edit', compact('annexure', 'subannexure', 'sub_annexure_f', 'count','currentPage'));
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
        if (!$request->has('annexure_id')) {
            return redirect()->route('edit-annexure', ['id' => $id])->withErrors(['error' => 'Annexure ID is missing']);
        }

        $annexure = Annexure::find($request->annexure_id);

        // Check if the section is found
        if (!$annexure) {
            return redirect()->route('edit-annexure', ['id' => $id])->withErrors(['error' => 'Annexure not found']);
        }
        if ($annexure) {

            $annexure->annexure_content = $request->annexure_content ?? null;
            $annexure->annexure_title = $request->annexure_title ?? null;
            $annexure->annexure_no = $request->annexure_no ?? null;
            $annexure->update();


            if ($request->has('annexure_footnote_content')) {
                $item = $request->annexure_footnote_content;
                        if ($request->has('annexure_footnote_id')) {

                            $footnote_id = $request->annexure_footnote_id;
                            if(isset($footnote_id)){
                            $foot = Footnote::find($footnote_id);

                            if ($foot) {
                                $foot->update([
                                    'footnote_content' => $item ?? null,
                                    'footnote_no' => $request->annexure_footnote_no ?? null,
                                ]);
                            }
                            }
                           
                        } else {
                            // Create a new footnote
                            $footnote = new Footnote();
                            $footnote->annexure_id = $id ?? null;
                            $footnote->annexure_no = $annexure->annexure_no ?? null;
                            $footnote->act_id = $annexure->act_id ?? null;
                            $footnote->chapter_id = $annexure->chapter_id ?? null;
                            $footnote->main_order_id = $annexure->main_order_id ?? null;
                            $footnote->parts_id = $annexure->parts_id ?? null;
                            $footnote->priliminary_id = $annexure->priliminary_id ?? null;
                            $footnote->schedule_id = $annexure->schedule_id ?? null;
                            $footnote->appendix_id = $annexure->appendix_id ?? null;
                            $footnote->footnote_content = $item ?? null;
                            $footnote->save();
                        }
            }
        }

        // Store Sub-Sections

        if ($request->has('sub_annexure_no')) {
            foreach ($request->sub_annexure_no as $key => $item) {

                // Initialize variables for reuse
                $sub_annexure_id = $request->sub_annexure_id[$key] ?? null;
                $sub_annexure_content = $request->sub_annexure_content[$key] ?? null;
                 
                // Check if sub_section_id is present in the request
                if ($sub_annexure_id && $existingSubAnnexure = SubAnnexure::find($sub_annexure_id)) {

                    $existingSubAnnexure->update([
                        'sub_annexure_no' => $item,
                        'sub_annexure_content' => $sub_annexure_content,
                    ]);

                    if ($request->has('sub_footnote_content') && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                        foreach ($request->sub_footnote_content[$key] as $kys => $footnote_content) {

                            $footnote_id = $request->sub_footnote_id[$key][$kys] ?? null;
                            if ($footnote_id && $foot = Footnote::find($footnote_id)) {
                                $foot->update(['footnote_content' => $footnote_content]);
                            }
                            else {
                                // Create new footnote if ID is not provided or invalid
                                $footnote = new Footnote();
                                $footnote->sub_annexure_id = $sub_annexure_id;
                                $footnote->annexure_id = $id ?? null;
                                $footnote->act_id = $annexure->act_id ?? null;
                                $footnote->chapter_id = $annexure->chapter_id ?? null;
                                $footnote->main_order_id = $annexure->main_order_id ?? null;
                                $footnote->parts_id = $annexure->parts_id ?? null;
                                $footnote->priliminary_id = $annexure->priliminary_id ?? null;
                                $footnote->schedule_id = $annexure->schedule_id ?? null;
                                $footnote->appendix_id = $annexure->appendix_id ?? null;
                                $footnote->footnote_content = $footnote_content ?? null;
                                $footnote->save();
                            }

                        }
                    }
                } else {
                    // Existing subsection not found, create a new one
                    $subannexure = new SubAnnexure();
                    $subannexure->annexure_id = $id ?? null;
                    $subannexure->sub_annexure_no = $item ?? null;
                    $subannexure->annexure_no = $annexure->annexure_no ?? null;
                    $subannexure->act_id = $annexure->act_id ?? null;
                    $subannexure->chapter_id = $annexure->chapter_id ?? null;
                    $subannexure->main_order_id = $annexure->main_order_id ?? null;
                    $subannexure->parts_id = $annexure->parts_id ?? null;
                    $subannexure->priliminary_id = $annexure->priliminary_id ?? null;
                    $subannexure->schedule_id = $annexure->schedule_id ?? null;
                    $subannexure->appendix_id = $annexure->appendix_id ?? null;
                    $subannexure->sub_annexure_content = $sub_annexure_content ?? null;
                    $subannexure->save();

                    if ($request->has('sub_footnote_content') && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                        foreach ($request->sub_footnote_content[$key] as $kys => $footnote_content) {
                            // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                          
                                $footnote = new Footnote();
                                $footnote->sub_annexure_id = $subannexure->sub_annexure_id;
                                $footnote->annexure_id = $id ?? null;
                                $footnote->act_id = $annexure->act_id ?? null;
                                $footnote->chapter_id = $annexure->chapter_id ?? null;
                                $footnote->main_order_id = $annexure->main_order_id ?? null;
                                $footnote->parts_id = $annexure->parts_id ?? null;
                                $footnote->priliminary_id = $annexure->priliminary_id ?? null;
                                $footnote->schedule_id = $annexure->schedule_id ?? null;
                                $footnote->appendix_id = $annexure->appendix_id ?? null;
                                $footnote->footnote_content = $footnote_content?? null;
                                $footnote->save();
                        }
                    }
                }
            }
        }



        return redirect()->route('get_act_section', ['id' => $annexure->act_id,'page' => $currentPage])->with('success', 'Annexure updated successfully');
        // } catch (\Exception $e) {
        //     \Log::error('Error updating Act: ' . $e->getMessage());
        //     return redirect()->route('edit-annexure', ['id' => $id])->withErrors(['error' => 'Failed to update Annexure. Please try again.' . $e->getMessage()]);
        // }
    }

    public function add_below_new_annexure(Request $request, $id, $annexure_id)
    {

        // $annexure_rank = $annexure_rank;
        $annexure = Annexure::with('ChapterModel', 'Partmodel', 'PriliminaryModel', 'Appendixmodel', 'Schedulemodel','MainOrderModel')->where('act_id', $id)
            ->where('annexure_id', $annexure_id)->first();
        $currentPage = $request->page;
        return view('admin.annexure.add_new', compact('annexure','currentPage'));
    }

    public function add_new_annexure(Request $request)
    {
        // dd($request);
        // die();
        try {
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


        $id = $request->act_id;
        // $annexure_no = $request->annexure_no;
        $annexure_rank = $request->annexure_rank;
        $maintypeId = $request->maintype_id;

        // Calculate the next section number
        // $nextAnnexureNo = $annexure_no;
        $oldAnnexureRank = $request->click_annexure_rank;
        $nextAnnexureRank = $oldAnnexureRank + 0.01;



        // Update the existing sections' section_no in the Section table
        // Section::where('section_no', '>=', $nextSectionNo)
        //     ->increment('section_no');

        // Create the new section with the incremented section_no
        $annexure = Annexure::create([
            'annexure_rank' => $nextAnnexureRank,
            'annexure_no' => $request->annexure_no ?? null,
            'act_id' => $request->act_id,
            'maintype_id' => $maintypeId,
            'chapter_id' => $request->chapter_id ?? null,
            'main_order_id' => $request->main_order_id ?? null,
            'priliminary_id' => $request->priliminary_id ?? null,
            'parts_id' => $request->parts_id ?? null,
            'schedule_id' => $request->schedule_id ?? null,
            'appendix_id' => $request->appendix_id ?? null,
            'subtypes_id' => $request->subtypes_id,
            'annexure_title' => $request->annexure_title,
            'annexure_content' => $request->annexure_content,
            'is_append'=> 1,
            'serial_no' => $request->serial_no,
        ]);

        if ($request->has('annexure_footnote_content')) {
            foreach ($request->annexure_footnote_content as $key => $item) {
                // Check if the key exists before using it
                if (isset($request->annexure_footnote_content[$key])) {
                    // Create a new footnote
                    $footnote = new Footnote();
                    $footnote->annexure_id = $annexure->annexure_id ?? null;
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

        if ($request->has('sub_annexure_no')) {
            foreach ($request->sub_annexure_no as $key => $item) {
                // Existing subsection not found, create a new one
                $sub_annexure = SubAnnexure::create([
                    'annexure_id' => $annexure->annexure_id,
                    'sub_annexure_no' => $item ?? null,
                    'annexure_no' =>  $request->annexure_no ?? null,
                    'act_id' => $request->act_id,
                    'chapter_id' => $maintypeId == "1" ? $request->chapter_id : null,
                    'main_order_id' => $maintypeId == "6" ? $request->main_order_id : null,
                    'parts_id' => $maintypeId == "2" ? $request->parts_id : null,
                    'priliminary_id' => $maintypeId == "3" ? $request->priliminary_id : null,
                    'schedule_id' => $maintypeId == "4" ? $request->schedule_id : null,
                    'appendix_id' => $maintypeId == "5" ? $request->appendix_id : null,
                    'sub_annexure_content' => $request->sub_annexure_content[$key] ?? null,
                ]);

                if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                    foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                        // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                        if (isset($request->sub_footnote_content[$key][$kys])) {
                            // Create a new footnote for the newly created subsection
                            $footnote = new Footnote();
                            $footnote->sub_annexure_id = $sub_annexure->sub_annexure_id;
                            $footnote->annexure_id = $annexure->annexure_id ?? null;
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

        return redirect()->route('get_act_section', ['id' => $id,'page' => $currentPage])->with('success', 'Ayrticle created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }


    public function view_sub_annexure(Request $request, $id)
    {
        $annexure = Annexure::where('annexure_id', $id)->first();
        $sub_annexure = SubAnnexure::where('annexure_id', $id)->with('footnoteModel')->get();
        $currentPage = $request->page;
        return view('admin.annexure.view', compact('annexure', 'sub_annexure','currentPage'));
    }

    public function destroy_sub_annexure(string $id)
    {
        try {
            $subannexure = SubAnnexure::find($id);

            if (!$subannexure) {
                return redirect()->back()->withErrors(['error' => 'Sub-Annexure not found.']);
            }

            Footnote::where('sub_annexure_id', $id)->delete();

            $subannexure->delete();

            return redirect()->back()->with('success', 'Sub-Annexure and related records deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting Sub-Annexure: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete Sub-Annexure. Please try again.' . $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            $annexure = Annexure::find($id);

            if (!$annexure) {
                return redirect()->back()->withErrors(['error' => 'Annexure not found.']);
            }

            SubAnnexure::where('annexure_id', $id)->delete();
            Footnote::where('annexure_id', $id)->delete();

            $annexure->delete();

            return redirect()->back()->with('success', 'Annexure and related records deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting annexure: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete annexure. Please try again.' . $e->getMessage()]);
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
