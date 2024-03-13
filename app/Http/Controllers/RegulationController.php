<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\Regulation;
use App\Models\Appendix;
use App\Models\ActSummary;
use App\Models\Category;
use App\Models\MainType;
use App\Models\Parts;
use App\Models\PartsType;
use App\Models\SubSection;
use App\Models\Priliminary;
use App\Models\SubRegulation;
use App\Models\Schedule;
use App\Models\Footnote;
use App\Models\Chapter;
use App\Models\Section;
use App\Models\State;
use App\Models\Status;
use App\Models\SubType;

class RegulationController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function edit_regulation($id)
    {
        $regulation = Regulation::with('ChapterModel', 'Partmodel', 'Appendixmodel', 'Schedulemodel', 'PriliminaryModel')->where('regulation_id', $id)->first();
        $subregulation = Regulation::where('regulation_id', $id)
            ->with([
                'subRegulationModel',
                'footnoteModel' => function ($query) {
                    $query->whereNull('sub_regulation_id');
                }
            ])
            ->get();

        $sub_regulation_f = SubRegulation::where('regulation_id', $id)->with('footnoteModel')->get();

        $count = 0;

        if ($sub_regulation_f) {
            foreach ($sub_regulation_f as $sub_regulation) {
                $count += $sub_regulation->footnoteModel->count();
            }
        }



        return view('admin.regulation.edit', compact('regulation', 'subregulation', 'sub_regulation_f', 'count'));
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
        if (!$request->has('regulation_id')) {
            return redirect()->route('edit-regulation', ['id' => $id])->withErrors(['error' => 'Regulation ID is missing']);
        }

        $regulation = Regulation::find($request->regulation_id);

        // Check if the section is found
        if (!$regulation) {
            return redirect()->route('edit-regulation', ['id' => $id])->withErrors(['error' => 'Regulation not found']);
        }
        if ($regulation) {

            $regulation->regulation_content = $request->regulation_content ?? null;
            $regulation->regulation_title = $request->regulation_title ?? null;
            $regulation->regulation_no = $request->regulation_no ?? null;
            $regulation->update();


            if ($request->has('regulation_footnote_content')) {
                foreach ($request->regulation_footnote_content as $key => $items) {
                    // Check if the key exists before using it
                    foreach ($items as $kys => $item) {
                        // Check if the sec_footnote_id exists at the specified index
                        if (isset($request->regulation_footnote_id[$key][$kys])) {
                            // Use first() instead of get() to get a single model instance
                            $foot = Footnote::find($request->regulation_footnote_id[$key][$kys]);

                            if ($foot) {
                                $foot->update([
                                    'footnote_content' => $item ?? null,
                                    'footnote_no' => $request->regulation_footnote_no[$key][$kys] ?? null,
                                ]);
                            }
                        } else {
                            // Create a new footnote
                            $footnote = new Footnote();
                            $footnote->regulation_id = $id ?? null;
                            $footnote->regulation_no = $regulation->regulation_no ?? null;
                            $footnote->act_id = $regulation->act_id ?? null;
                            $footnote->chapter_id = $regulation->chapter_id ?? null;
                            $footnote->parts_id = $regulation->parts_id ?? null;
                            $footnote->priliminary_id = $regulation->priliminary_id ?? null;
                            $footnote->schedule_id = $regulation->schedule_id ?? null;
                            $footnote->appendix_id = $regulation->appendix_id ?? null;
                            $footnote->footnote_content = $item ?? null;
                            $footnote->save();
                        }
                    }
                }
            }
        }

        // Store Sub-Sections

        if ($request->has('sub_regulation_no')) {
            foreach ($request->sub_regulation_no as $key => $item) {
                // Check if sub_section_id is present in the request
                if ($request->filled('sub_regulation_id') && is_array($request->sub_regulation_id) && array_key_exists($key, $request->sub_regulation_id)) {

                    $sub_regulation = SubRegulation::find($request->sub_regulation_id[$key]);

                    // Check if $sub_section is found in the database and the IDs match
                    if ($sub_regulation && $sub_regulation->sub_regulation_id == $request->sub_regulation_id[$key]) {
                        $sub_regulation->sub_regulation_no = $item ?? null;
                        $sub_regulation->sub_regulation_content = $request->sub_regulation_content[$key] ?? null;
                        $sub_regulation->update();

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
                                    $footnote->sub_regulation_id = $sub_regulation->sub_regulation_id;
                                    $footnote->regulation_id = $id ?? null;
                                    $footnote->act_id = $regulation->act_id ?? null;
                                    $footnote->chapter_id = $regulation->chapter_id ?? null;
                                    $footnote->parts_id = $regulation->parts_id ?? null;
                                    $footnote->priliminary_id = $regulation->priliminary_id ?? null;
                                    $footnote->schedule_id = $regulation->schedule_id ?? null;
                                    $footnote->appendix_id = $regulation->appendix_id ?? null;
                                    $footnote->footnote_content = $item ?? null;
                                    $footnote->save();
                                }
                            }
                        }
                    }
                } else {
                    // Existing subsection not found, create a new one
                    $subregulation = new SubRegulation();
                    $subregulation->regulation_id = $id ?? null;
                    $subregulation->sub_regulation_no = $item ?? null;
                    $subregulation->regulation_no = $regulation->regulation_no ?? null;
                    $subregulation->act_id = $regulation->act_id ?? null;
                    $subregulation->chapter_id = $regulation->chapter_id ?? null;
                    $subregulation->parts_id = $regulation->parts_id ?? null;
                    $subregulation->priliminary_id = $regulation->priliminary_id ?? null;
                    $subregulation->schedule_id = $regulation->schedule_id ?? null;
                    $subregulation->appendix_id = $regulation->appendix_id ?? null;
                    $subregulation->sub_regulation_content = $request->sub_regulation_content[$key] ?? null;
                    $subregulation->save();

                    if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                        foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                            // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                            if (isset($request->sub_footnote_content[$key][$kys])) {
                                // Create a new footnote for the newly created subsection
                                $footnote = new Footnote();
                                $footnote->sub_regulation_id = $subregulation->sub_regulation_id;
                                $footnote->regulation_id = $id ?? null;
                                $footnote->act_id = $regulation->act_id ?? null;
                                $footnote->chapter_id = $regulation->chapter_id ?? null;
                                $footnote->parts_id = $regulation->parts_id ?? null;
                                $footnote->priliminary_id = $regulation->priliminary_id ?? null;
                                $footnote->schedule_id = $regulation->schedule_id ?? null;
                                $footnote->appendix_id = $regulation->appendix_id ?? null;
                                $footnote->footnote_content = $item ?? null;
                                $footnote->footnote_no = $request->sub_footnote_no[$key][$kys] ?? null;
                                $footnote->save();
                            }
                        }
                    }
                }
            }
        }



        return redirect()->route('get_act_section', ['id' => $regulation->act_id])->with('success', 'Regulation updated successfully');
        // } catch (\Exception $e) {
        //     \Log::error('Error updating Act: ' . $e->getMessage());
        //     return redirect()->route('edit-regulation', ['id' => $id])->withErrors(['error' => 'Failed to update Regulation. Please try again.' . $e->getMessage()]);
        // }
    }

    public function add_below_new_regulation(Request $request, $id, $regulation_id)
    {

        // $regulation_rank = $regulation_rank;
        $regulation = Regulation::with('ChapterModel', 'Partmodel', 'PriliminaryModel', 'Appendixmodel', 'Schedulemodel')->where('act_id', $id)
            ->where('regulation_id', $regulation_id)->first();

        return view('admin.regulation.add_new', compact('regulation'));
    }

    public function add_new_regulation(Request $request)
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
        // $regulation_no = $request->regulation_no;
        $regulation_rank = $request->regulation_rank;
        $maintypeId = $request->maintype_id;

        // Calculate the next section number
        // $nextRegulationNo = $regulation_no;
        $oldRegulationRank = $request->click_regulation_rank;
        $nextRegulationRank = $oldRegulationRank + 0.01;



        // Update the existing sections' section_no in the Section table
        // Section::where('section_no', '>=', $nextSectionNo)
        //     ->increment('section_no');

        // Create the new section with the incremented section_no
        $regulation = Regulation::create([
            'regulation_rank' => $nextRegulationRank,
            'regulation_no' => $request->regulation_no ?? null,
            'act_id' => $request->act_id,
            'maintype_id' => $maintypeId,
            'chapter_id' => $request->chapter_id ?? null,
            'priliminary_id' => $request->priliminary_id ?? null,
            'parts_id' => $request->parts_id ?? null,
            'schedule_id' => $request->schedule_id ?? null,
            'appendix_id' => $request->appendix_id ?? null,
            'subtypes_id' => $request->subtypes_id,
            'regulation_title' => $request->regulation_title,
            'regulation_content' => $request->regulation_content,
            'is_append' => 1,
            'serial_no' => $request->serial_no
        ]);

        if ($request->has('regulation_footnote_content')) {
            foreach ($request->regulation_footnote_content as $key => $item) {
                // Check if the key exists before using it
                if (isset($request->regulation_footnote_content[$key])) {
                    // Create a new footnote
                    $footnote = new Footnote();
                    $footnote->regulation_id = $regulation->regulation_id ?? null;
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

        if ($request->has('sub_regulation_no')) {
            foreach ($request->sub_regulation_no as $key => $item) {
                // Existing subsection not found, create a new one
                $sub_regulation = SubRegulation::create([
                    'regulation_id' => $regulation->regulation_id,
                    'sub_regulation_no' => $item ?? null,
                    'regulation_no' => $request->regulation_no ?? null,
                    'act_id' => $request->act_id,
                    'chapter_id' => $maintypeId == "1" ? $request->chapter_id : null,
                    'parts_id' => $maintypeId == "2" ? $request->parts_id : null,
                    'priliminary_id' => $maintypeId == "3" ? $request->priliminary_id : null,
                    'schedule_id' => $maintypeId == "4" ? $request->schedule_id : null,
                    'appendix_id' => $maintypeId == "5" ? $request->appendix_id : null,
                    'sub_regulation_content' => $request->sub_regulation_content[$key] ?? null,
                ]);

                if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                    foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                        // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                        if (isset($request->sub_footnote_content[$key][$kys])) {
                            // Create a new footnote for the newly created subsection
                            $footnote = new Footnote();
                            $footnote->sub_regulation_id = $sub_regulation->sub_regulation_id;
                            $footnote->regulation_id = $regulation->regulation_id ?? null;
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

    public function view_sub_regulation(Request $request, $id)
    {
        $regulation = Regulation::where('regulation_id', $id)->first();
        $sub_regulation = SubRegulation::where('regulation_id', $id)->with('footnoteModel')->get();
        return view('admin.regulation.view', compact('regulation', 'sub_regulation'));
    }


    public function destroy_sub_regulation(string $id)
    {
        try {
            $subregulation = SubRegulation::find($id);

            if (!$subregulation) {
                return redirect()->back()->withErrors(['error' => 'Sub-Regulation not found.']);
            }

            Footnote::where('sub_regulation_id', $id)->delete();

            $subregulation->delete();

            return redirect()->back()->with('success', 'Sub-Regulation and related records deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting Sub-Regulation: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete Sub-Regulation. Please try again.' . $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            $regulation = Regulation::find($id);

            if (!$regulation) {
                return redirect()->back()->withErrors(['error' => 'Regulation not found.']);
            }

            SubRegulation::where('Regulation_id', $id)->delete();
            Footnote::where('regulation_id', $id)->delete();

            $regulation->delete();

            return redirect()->back()->with('success', 'Regulation and related records deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting regulation: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete regulation. Please try again.' . $e->getMessage()]);
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
