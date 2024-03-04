<?php

namespace App\Http\Controllers;

use App\Models\Annexture;
use App\Models\Appendices;
use App\Models\Chapter;
use App\Models\Footnote;
use App\Models\Parts;
use App\Models\Priliminary;
use App\Models\Schedule;
use App\Models\SubAnnexture;
use Illuminate\Http\Request;

class AnnextureController extends Controller
{

    public function edit_annexture($id)
    {
        $annexture = Annexture::with('ChapterModel', 'Partmodel', 'Appendicesmodel', 'Schedulemodel', 'PriliminaryModel')->where('annexture_id', $id)->first();
        $subannexture = Annexture::where('annexture_id', $id)
            ->with([
                'subAnnextureModel',
                'footnoteModel' => function ($query) {
                    $query->whereNull('sub_annexture_id');
                }
            ])
            ->get();

        $sub_annexture_f = SubAnnexture::where('annexture_id', $id)->with('footnoteModel')->get();

        $count = 0;

        if ($sub_annexture_f) {
            foreach ($sub_annexture_f as $sub_annexture) {
                $count += $sub_annexture->footnoteModel->count();
            }
        }



        return view('admin.annexture.edit', compact('annexture', 'subannexture', 'sub_annexture_f', 'count'));
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
        if (!$request->has('annexture_id')) {
            return redirect()->route('edit-annexture', ['id' => $id])->withErrors(['error' => 'Annexture ID is missing']);
        }

        $annexture = Annexture::find($request->annexture_id);

        // Check if the section is found
        if (!$annexture) {
            return redirect()->route('edit-annexture', ['id' => $id])->withErrors(['error' => 'Annexture not found']);
        }
        if ($annexture) {

            $annexture->annexture_content = $request->annexture_content ?? null;
            $annexture->annexture_title = $request->annexture_title ?? null;
            $annexture->annexture_no = $request->annexture_no ?? null;
            $annexture->update();


            if ($request->has('annexture_footnote_content')) {
                foreach ($request->annexture_footnote_content as $key => $items) {
                    // Check if the key exists before using it
                    foreach ($items as $kys => $item) {
                        // Check if the sec_footnote_id exists at the specified index
                        if (isset($request->annexture_footnote_id[$key][$kys])) {
                            // Use first() instead of get() to get a single model instance
                            $foot = Footnote::find($request->annexture_footnote_id[$key][$kys]);

                            if ($foot) {
                                $foot->update([
                                    'footnote_content' => $item ?? null,
                                    'footnote_no' => $request->annexture_footnote_no[$key][$kys] ?? null,
                                ]);
                            }
                        } else {
                            // Create a new footnote
                            $footnote = new Footnote();
                            $footnote->annexture_id = $id ?? null;
                            $footnote->annexture_no = $annexture->annexture_no ?? null;
                            $footnote->act_id = $annexture->act_id ?? null;
                            $footnote->chapter_id = $annexture->chapter_id ?? null;
                            $footnote->parts_id = $annexture->parts_id ?? null;
                            $footnote->priliminary_id = $annexture->priliminary_id ?? null;
                            $footnote->schedule_id = $annexture->schedule_id ?? null;
                            $footnote->appendices_id = $annexture->appendices_id ?? null;
                            $footnote->footnote_content = $item ?? null;
                            $footnote->save();
                        }
                    }
                }
            }
        }

        // Store Sub-Sections

        if ($request->has('sub_annexture_no')) {
            foreach ($request->sub_annexture_no as $key => $item) {
                // Check if sub_section_id is present in the request
                if ($request->filled('sub_annexture_id') && is_array($request->sub_annexture_id) && array_key_exists($key, $request->sub_annexture_id)) {

                    $sub_annexture = SubAnnexture::find($request->sub_annexture_id[$key]);

                    // Check if $sub_section is found in the database and the IDs match
                    if ($sub_annexture && $sub_annexture->sub_annexture_id == $request->sub_annexture_id[$key]) {
                        $sub_annexture->sub_annexture_no = $item ?? null;
                        $sub_annexture->sub_annexture_content = $request->sub_annexture_content[$key] ?? null;
                        $sub_annexture->update();

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
                                    $footnote->sub_annexture_id = $sub_annexture->sub_annexture_id;
                                    $footnote->annexture_id = $id ?? null;
                                    $footnote->act_id = $annexture->act_id ?? null;
                                    $footnote->chapter_id = $annexture->chapter_id ?? null;
                                    $footnote->parts_id = $annexture->parts_id ?? null;
                                    $footnote->priliminary_id = $annexture->priliminary_id ?? null;
                                    $footnote->schedule_id = $annexture->schedule_id ?? null;
                                    $footnote->appendices_id = $annexture->appendices_id ?? null;
                                    $footnote->footnote_content = $item ?? null;
                                    $footnote->save();
                                }
                            }
                        }
                    }
                } else {
                    // Existing subsection not found, create a new one
                    $subannexture = new SubAnnexture();
                    $subannexture->annexture_id = $id ?? null;
                    $subannexture->sub_annexture_no = $item ?? null;
                    $subannexture->annexture_no = $annexture->annexture_no ?? null;
                    $subannexture->act_id = $annexture->act_id ?? null;
                    $subannexture->chapter_id = $annexture->chapter_id ?? null;
                    $subannexture->parts_id = $annexture->parts_id ?? null;
                    $subannexture->priliminary_id = $annexture->priliminary_id ?? null;
                    $subannexture->schedule_id = $annexture->schedule_id ?? null;
                    $subannexture->appendices_id = $annexture->appendices_id ?? null;
                    $subannexture->sub_annexture_content = $request->sub_annexture_content[$key] ?? null;
                    $subannexture->save();

                    if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                        foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                            // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                            if (isset($request->sub_footnote_content[$key][$kys])) {
                                // Create a new footnote for the newly created subsection
                                $footnote = new Footnote();
                                $footnote->sub_annexture_id = $subannexture->sub_annexture_id;
                                $footnote->annexture_id = $id ?? null;
                                $footnote->act_id = $annexture->act_id ?? null;
                                $footnote->chapter_id = $annexture->chapter_id ?? null;
                                $footnote->parts_id = $annexture->parts_id ?? null;
                                $footnote->priliminary_id = $annexture->priliminary_id ?? null;
                                $footnote->schedule_id = $annexture->schedule_id ?? null;
                                $footnote->appendices_id = $annexture->appendices_id ?? null;
                                $footnote->footnote_content = $item ?? null;
                                $footnote->footnote_no = $request->sub_footnote_no[$key][$kys] ?? null;
                                $footnote->save();
                            }
                        }
                    }
                }
            }
        }



        return redirect()->route('get_act_section', ['id' => $annexture->act_id])->with('success', 'Annexture updated successfully');
        // } catch (\Exception $e) {
        //     \Log::error('Error updating Act: ' . $e->getMessage());
        //     return redirect()->route('edit-annexture', ['id' => $id])->withErrors(['error' => 'Failed to update Annexture. Please try again.' . $e->getMessage()]);
        // }
    }

    public function add_below_new_annexture(Request $request, $id, $annexture_id, $annexture_rank)
    {

        $annexture_rank = $annexture_rank;
        $annexture = Annexture::with('ChapterModel', 'Partmodel', 'PriliminaryModel', 'Appendicesmodel', 'Schedulemodel')->where('act_id', $id)
            ->where('annexture_id', $annexture_id)->first();

        return view('admin.annexture.add_new', compact('annexture', 'annexture_rank'));
    }

    public function add_new_annexture(Request $request)
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
        // $annexture_no = $request->annexture_no;
        $annexture_rank = $request->annexture_rank;
        $maintypeId = $request->maintype_id;

        // Calculate the next section number
        // $nextAnnextureNo = $annexture_no;
        $nextAnnextureRank = $annexture_rank + 0.01;



        // Update the existing sections' section_no in the Section table
        // Section::where('section_no', '>=', $nextSectionNo)
        //     ->increment('section_no');

        // Create the new section with the incremented section_no
        $annexture = Annexture::create([
            'annexture_rank' => $nextAnnextureRank ?? 1,
            'annexture_no' => $request->annexture_no ?? null,
            'act_id' => $request->act_id,
            'maintype_id' => $maintypeId,
            'chapter_id' => $request->chapter_id ?? null,
            'priliminary_id' => $request->priliminary_id ?? null,
            'parts_id' => $request->parts_id ?? null,
            'schedule_id' => $request->schedule_id ?? null,
            'appendices_id' => $request->appendices_id ?? null,
            'subtypes_id' => $request->subtypes_id,
            'annexture_title' => $request->annexture_title,
            'annexture_content' => $request->annexture_content,
        ]);

        if ($request->has('annexture_footnote_content')) {
            foreach ($request->annexture_footnote_content as $key => $item) {
                // Check if the key exists before using it
                if (isset($request->annexture_footnote_content[$key])) {
                    // Create a new footnote
                    $footnote = new Footnote();
                    $footnote->annexture_id = $annexture->annexture_id ?? null;
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

        if ($request->has('sub_annexture_no')) {
            foreach ($request->sub_annexture_no as $key => $item) {
                // Existing subsection not found, create a new one
                $sub_annexture = SubAnnexture::create([
                    'annexture_id' => $annexture->annexture_id,
                    'sub_annexture_no' => $item ?? null,
                    'annexture_no' =>  $request->annexture_no ?? null,
                    'act_id' => $request->act_id,
                    'chapter_id' => $maintypeId == "1" ? $request->chapter_id : null,
                    'parts_id' => $maintypeId == "2" ? $request->parts_id : null,
                    'priliminary_id' => $maintypeId == "3" ? $request->priliminary_id : null,
                    'schedule_id' => $maintypeId == "4" ? $request->schedule_id : null,
                    'appendices_id' => $maintypeId == "5" ? $request->appendices_id : null,
                    'sub_annexture_content' => $request->sub_annexture_content[$key] ?? null,
                ]);

                if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                    foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                        // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                        if (isset($request->sub_footnote_content[$key][$kys])) {
                            // Create a new footnote for the newly created subsection
                            $footnote = new Footnote();
                            $footnote->sub_annexture_id = $sub_annexture->sub_annexture_id;
                            $footnote->annexture_id = $annexture->annexture_id ?? null;
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


    public function view_sub_annexture(Request $request, $id)
    {
        $annexture = Annexture::where('annexture_id', $id)->first();
        $sub_annexture = SubAnnexture::where('annexture_id', $id)->with('footnoteModel')->get();
        return view('admin.annexture.view', compact('annexture', 'sub_annexture'));
    }

    public function destroy_sub_annexture(string $id)
    {
        try {
            $subannexture = SubAnnexture::find($id);

            if (!$subannexture) {
                return redirect()->back()->withErrors(['error' => 'Sub-Annexture not found.']);
            }

            Footnote::where('sub_annexture_id', $id)->delete();

            $subannexture->delete();

            return redirect()->back()->with('success', 'Sub-Annexture and related records deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting Sub-Annexture: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete Sub-Annexture. Please try again.' . $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            $annexture = Annexture::find($id);

            if (!$annexture) {
                return redirect()->back()->withErrors(['error' => 'Annexture not found.']);
            }

            SubAnnexture::where('annexture_id', $id)->delete();
            Footnote::where('annexture_id', $id)->delete();

            $annexture->delete();

            return redirect()->back()->with('success', 'Annexture and related records deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting annexture: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete annexture. Please try again.' . $e->getMessage()]);
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
