<?php

namespace App\Http\Controllers;

use App\Models\Annexure;
use App\Models\Appendix;
use App\Models\Chapter;
use App\Models\Footnote;
use App\Models\Parts;
use App\Models\Priliminary;
use App\Models\Schedule;
use App\Models\SubAnnexure;
use Illuminate\Http\Request;

class AnnexureController extends Controller
{

    public function edit_annexure($id)
    {
        $annexure = Annexure::with('ChapterModel', 'Partmodel', 'Appendixmodel', 'Schedulemodel', 'PriliminaryModel')->where('annexure_id', $id)->first();
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



        return view('admin.annexure.edit', compact('annexure', 'subannexure', 'sub_annexure_f', 'count'));
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
                foreach ($request->annexure_footnote_content as $key => $items) {
                    // Check if the key exists before using it
                    foreach ($items as $kys => $item) {
                        // Check if the sec_footnote_id exists at the specified index
                        if (isset($request->annexure_footnote_id[$key][$kys])) {
                            // Use first() instead of get() to get a single model instance
                            $foot = Footnote::find($request->annexure_footnote_id[$key][$kys]);

                            if ($foot) {
                                $foot->update([
                                    'footnote_content' => $item ?? null,
                                    'footnote_no' => $request->annexure_footnote_no[$key][$kys] ?? null,
                                ]);
                            }
                        } else {
                            // Create a new footnote
                            $footnote = new Footnote();
                            $footnote->annexure_id = $id ?? null;
                            $footnote->annexure_no = $annexure->annexure_no ?? null;
                            $footnote->act_id = $annexure->act_id ?? null;
                            $footnote->chapter_id = $annexure->chapter_id ?? null;
                            $footnote->parts_id = $annexure->parts_id ?? null;
                            $footnote->priliminary_id = $annexure->priliminary_id ?? null;
                            $footnote->schedule_id = $annexure->schedule_id ?? null;
                            $footnote->appendix_id = $annexure->appendix_id ?? null;
                            $footnote->footnote_content = $item ?? null;
                            $footnote->save();
                        }
                    }
                }
            }
        }

        // Store Sub-Sections

        if ($request->has('sub_annexure_no')) {
            foreach ($request->sub_annexure_no as $key => $item) {
                // Check if sub_section_id is present in the request
                if ($request->filled('sub_annexure_id') && is_array($request->sub_annexure_id) && array_key_exists($key, $request->sub_annexure_id)) {

                    $sub_annexure = SubAnnexure::find($request->sub_annexure_id[$key]);

                    // Check if $sub_section is found in the database and the IDs match
                    if ($sub_annexure && $sub_annexure->sub_annexure_id == $request->sub_annexure_id[$key]) {
                        $sub_annexure->sub_annexure_no = $item ?? null;
                        $sub_annexure->sub_annexure_content = $request->sub_annexure_content[$key] ?? null;
                        $sub_annexure->update();

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
                                    $footnote->sub_annexure_id = $sub_annexure->sub_annexure_id;
                                    $footnote->annexure_id = $id ?? null;
                                    $footnote->act_id = $annexure->act_id ?? null;
                                    $footnote->chapter_id = $annexure->chapter_id ?? null;
                                    $footnote->parts_id = $annexure->parts_id ?? null;
                                    $footnote->priliminary_id = $annexure->priliminary_id ?? null;
                                    $footnote->schedule_id = $annexure->schedule_id ?? null;
                                    $footnote->appendix_id = $annexure->appendix_id ?? null;
                                    $footnote->footnote_content = $item ?? null;
                                    $footnote->save();
                                }
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
                    $subannexure->parts_id = $annexure->parts_id ?? null;
                    $subannexure->priliminary_id = $annexure->priliminary_id ?? null;
                    $subannexure->schedule_id = $annexure->schedule_id ?? null;
                    $subannexure->appendix_id = $annexure->appendix_id ?? null;
                    $subannexure->sub_annexure_content = $request->sub_annexure_content[$key] ?? null;
                    $subannexure->save();

                    if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                        foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                            // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                            if (isset($request->sub_footnote_content[$key][$kys])) {
                                // Create a new footnote for the newly created subsection
                                $footnote = new Footnote();
                                $footnote->sub_annexure_id = $subannexure->sub_annexure_id;
                                $footnote->annexure_id = $id ?? null;
                                $footnote->act_id = $annexure->act_id ?? null;
                                $footnote->chapter_id = $annexure->chapter_id ?? null;
                                $footnote->parts_id = $annexure->parts_id ?? null;
                                $footnote->priliminary_id = $annexure->priliminary_id ?? null;
                                $footnote->schedule_id = $annexure->schedule_id ?? null;
                                $footnote->appendix_id = $annexure->appendix_id ?? null;
                                $footnote->footnote_content = $item ?? null;
                                $footnote->footnote_no = $request->sub_footnote_no[$key][$kys] ?? null;
                                $footnote->save();
                            }
                        }
                    }
                }
            }
        }



        return redirect()->route('get_act_section', ['id' => $annexure->act_id])->with('success', 'Annexure updated successfully');
        // } catch (\Exception $e) {
        //     \Log::error('Error updating Act: ' . $e->getMessage());
        //     return redirect()->route('edit-annexure', ['id' => $id])->withErrors(['error' => 'Failed to update Annexure. Please try again.' . $e->getMessage()]);
        // }
    }

    public function add_below_new_annexure(Request $request, $id, $annexure_id)
    {

        // $annexure_rank = $annexure_rank;
        $annexure = Annexure::with('ChapterModel', 'Partmodel', 'PriliminaryModel', 'Appendixmodel', 'Schedulemodel')->where('act_id', $id)
            ->where('annexure_id', $annexure_id)->first();

        return view('admin.annexure.add_new', compact('annexure'));
    }

    public function add_new_annexure(Request $request)
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


    public function view_sub_annexure(Request $request, $id)
    {
        $annexure = Annexure::where('annexure_id', $id)->first();
        $sub_annexure = SubAnnexure::where('annexure_id', $id)->with('footnoteModel')->get();
        return view('admin.annexure.view', compact('annexure', 'sub_annexure'));
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
