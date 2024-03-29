<?php

namespace App\Http\Controllers;

use App\Models\Appendix;
use App\Models\Chapter;
use App\Models\Footnote;
use App\Models\MainOrder;
use App\Models\Rules;
use App\Models\Parts;
use App\Models\Priliminary;
use App\Models\Schedule;
use App\Models\SubRules;
use Illuminate\Http\Request;

class RulesController extends Controller
{
    
    public function edit_rule($id,Request $request)
    {
        $rule = Rules::with('ChapterModel', 'Partmodel','Appendixmodel','Schedulemodel','PriliminaryModel','MainOrderModel')->where('rule_id', $id)->first();
        $subrule = Rules::where('rule_id', $id)
            ->with(['subruleModel', 'footnoteModel' => function ($query) {
                $query->whereNull('sub_rule_id');
            }])
            ->get();

        $sub_rule_f = SubRules::where('rule_id', $id)->with('footnoteModel')->get();

        $count = 0;

        if ($sub_rule_f) {
            foreach ($sub_rule_f as $sub_rule) {
                $count += $sub_rule->footnoteModel->count();
            }
        }


        $currentPage = $request->page;
        return view('admin.rules.edit', compact('rule', 'subrule', 'sub_rule_f', 'count','currentPage'));
    }
    
    public function update(Request $request, $id)
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

        // Check if section_id exists in the request
        if (!$request->has('rule_id')) {
            return redirect()->route('edit-rule', ['id' => $id])->withErrors(['error' => 'Rule ID is missing']);
        }

        $rules = Rules::find($request->rule_id);

        // Check if the section is found
        if (!$rules) {
            return redirect()->route('edit-rule', ['id' => $id])->withErrors(['error' => 'Rule not found']);
        }
        if ($rules) {

            $rules->rule_content = $request->rule_content ?? null;
            $rules->rule_title = $request->rule_title ?? null;
            $rules->rule_no = $request->rule_no ?? null;
            $rules->update();


            if ($request->has('rule_footnote_content')) {
                foreach ($request->rule_footnote_content as $key => $items) {
                    // Check if the key exists before using it
                    foreach ($items as $kys => $item) {
                        // Check if the sec_footnote_id exists at the specified index
                        if (isset($request->rule_footnote_id[$key][$kys])) {
                            // Use first() instead of get() to get a single model instance
                            $foot = Footnote::find($request->rule_footnote_id[$key][$kys]);

                            if ($foot) {
                                $foot->update([
                                    'footnote_content' => $item ?? null,
                                    'footnote_no' => $request->rule_footnote_no[$key][$kys] ?? null,
                                ]);
                            }
                        } else {
                            // Create a new footnote
                            $footnote = new Footnote();
                            $footnote->rule_id = $id ?? null;
                            $footnote->rule_no = $rules->rule_no ?? null;
                            $footnote->act_id = $part->act_id ?? null;
                            $footnote->chapter_id = $part->chapter_id ?? null;
                            $footnote->main_order_id = $part->main_order_id ?? null;
                            $footnote->parts_id = $part->parts_id ?? null;
                            $footnote->priliminary_id = $part->priliminary_id ?? null;
                            $footnote->schedule_id = $part->schedule_id ?? null;
                            $footnote->appendix_id = $part->appendix_id ?? null;
                            $footnote->footnote_content = $item ?? null;
                            $footnote->footnote_no = $request->sub_footnote_no[$key][$kys] ?? null;
                            $footnote->save();
                        }
                    }
                }
            }
        }

        // Store Sub-Sections

        if ($request->has('sub_rule_no')) {
            foreach ($request->sub_rule_no as $key => $item) {
                // Check if sub_section_id is present in the request
                if ($request->filled('sub_rule_id') && is_array($request->sub_rule_id) && array_key_exists($key, $request->sub_rule_id)) {

                    $sub_rule = SubRules::find($request->sub_rule_id[$key]);

                    // Check if $sub_section is found in the database and the IDs match
                    if ($sub_rule && $sub_rule->sub_rule_id == $request->sub_rule_id[$key]) {
                        $sub_rule->sub_rule_no = $item ?? null;
                        $sub_rule->sub_rule_content = $request->sub_rule_content[$key] ?? null;
                        $sub_rule->update();

                        if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                            foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                                // Check if the sec_footnote_id exists at the specified index
                                if (isset($request->sub_footnote_id[$key][$kys])) {
                                    // Use first() instead of get() to get a single model instance
                                    $foot = Footnote::find($request->sub_footnote_id[$key][$kys]);

                                    if ($foot) {
                                        $foot->update([
                                            'footnote_content' => $item ?? null,
                                            'footnote_no' => $request->sub_footnote_no[$key][$kys] ?? null,
                                        ]);
                                    }
                                } else {
                                    $footnote = new Footnote();
                                    $footnote->sub_rule_id = $sub_rule->sub_rule_id;
                                    $footnote->rule_id = $id ?? null;
                                    $footnote->act_id = $part->act_id ?? null;
                                    $footnote->chapter_id = $part->chapter_id ?? null;
                                    $footnote->main_order_id = $part->main_order_id ?? null;
                                    $footnote->parts_id = $part->parts_id ?? null;
                                    $footnote->priliminary_id = $part->priliminary_id ?? null;
                                    $footnote->schedule_id = $part->schedule_id ?? null;
                                    $footnote->appendix_id = $part->appendix_id ?? null;
                                    $footnote->footnote_content = $item ?? null;
                                    $footnote->footnote_no = $request->sub_footnote_no[$key][$kys] ?? null;
                                    $footnote->save();
                                }
                            }
                        }
                    }
                } else {
                    // Existing subsection not found, create a new one
                    $subrule = new SubRules();
                    $subrule->rule_id = $id ?? null;
                    $subrule->sub_rule_no = $item ?? null;
                    $subrule->rule_no = $rules->rule_no ?? null;
                    $subrule->act_id = $part->act_id ?? null;
                    $subrule->chapter_id = $part->chapter_id ?? null;
                    $subrule->main_order_id = $part->main_order_id ?? null;
                    $subrule->parts_id = $part->parts_id ?? null;
                    $subrule->priliminary_id = $part->priliminary_id ?? null;
                    $subrule->schedule_id = $part->schedule_id ?? null;
                    $subrule->appendix_id = $part->appendix_id ?? null;
                    $subrule->sub_rule_content = $request->sub_rule_content[$key] ?? null;
                    $subrule->save();

                    if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                        foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                            // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                            if (isset($request->sub_footnote_content[$key][$kys])) {
                                // Create a new footnote for the newly created subsection
                                $footnote = new Footnote();
                                $footnote->sub_rule_id = $subrule->sub_rule_id;
                                $footnote->rule_id = $id ?? null;
                                $footnote->act_id = $part->act_id ?? null;
                                $footnote->chapter_id = $part->chapter_id ?? null;
                                $footnote->main_order_id = $part->main_order_id ?? null;
                                $footnote->parts_id = $part->parts_id ?? null;
                                $footnote->priliminary_id = $part->priliminary_id ?? null;
                                $footnote->schedule_id = $part->schedule_id ?? null;
                                $footnote->appendix_id = $part->appendix_id ?? null;
                                $footnote->footnote_content = $item ?? null;
                                $footnote->save();
                            }
                        }
                    }
                }
            }
        }



        return redirect()->route('get_act_section', ['id' => $rules->act_id,'page' => $currentPage])->with('success', 'Rule updated successfully');
        } catch (\Exception $e) {
            \Log::error('Error updating Act: ' . $e->getMessage());
            return redirect()->route('edit-rule', ['id' => $id])->withErrors(['error' => 'Failed to update Section. Please try again.' . $e->getMessage()]);
        }
    }
    
    public function add_below_new_rule(Request $request, $id, $rule_id)
    {
       
        // $rule_rank = $rule_rank;
        $rule = Rules::with('ChapterModel', 'Partmodel', 'PriliminaryModel','Appendixmodel','Schedulemodel','MainOrderModel')->where('act_id', $id)
            ->where('rule_id', $rule_id)->first();
          
        $currentPage = $request->page;
        return view('admin.rules.add_new', compact('rule','currentPage'));
    }

    public function add_new_rule(Request $request)
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
            // $rule_no = $request->rule_no;
            $rule_rank = $request->rule_rank;
            $maintypeId = $request->maintype_id;

            // Calculate the next section number
            // $nextRuleNo = $rule_no;
            $oldSectionRank = $request->click_section_rank;
            $nextRuleRank = $oldSectionRank + 0.01;
            // dd($nextRuleRank);
            // die();



            // Update the existing sections' section_no in the Section table
            // Section::where('section_no', '>=', $nextSectionNo)
            //     ->increment('section_no');

            // Create the new section with the incremented section_no
            $rule = Rules::create([
                'rule_rank'    => $nextRuleRank,
                'rule_no'      => $request->rule_no ?? null,
                'act_id'       => $request->act_id,
                'maintype_id'  => $maintypeId,
                'chapter_id' => $request->chapter_id ?? null,
                'main_order_id' => $request->main_order_id ?? null,
                'priliminary_id' => $request->priliminary_id ?? null,
                'parts_id' => $request->parts_id ?? null,
                'schedule_id' => $request->schedule_id ?? null,
                'appendix_id' => $request->appendix_id ?? null,
                'subtypes_id'  => $request->subtypes_id,
                'rule_title'   => $request->rule_title,
                'rule_content' => $request->rule_content,
                'serial_no'=>$request->serial_no
            ]);

            if ($request->has('rule_footnote_content')) {
                foreach ($request->rule_footnote_content as $key => $item) {
                    // Check if the key exists before using it
                    if (isset($request->rule_footnote_content[$key])) {
                        // Create a new footnote
                        $footnote = new Footnote();
                        $footnote->section_id = $rule->rule_id ?? null;
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

            if ($request->has('sub_rule_no')) {
                foreach ($request->sub_rule_no as $key => $item) {
                    // Existing subsection not found, create a new one
                    $sub_rule = SubRules::create([
                        'rule_id' => $rule->rule_id,
                        'sub_rule_no' => $item ?? null,
                        'rule_no' => $request->rule_no ?? null,
                        'act_id' => $request->act_id,
                        'chapter_id' => $maintypeId == "1" ? $request->chapter_id : null,
                        'main_order_id' => $maintypeId == "6" ? $request->main_order_id : null,
                        'parts_id' => $maintypeId == "2" ? $request->parts_id : null,
                        'priliminary_id' => $maintypeId == "3" ? $request->priliminary_id : null,
                        'schedule_id' => $maintypeId == "4" ? $request->schedule_id : null,
                        'appendix_id' => $maintypeId == "5" ? $request->appendix_id : null,
                        'sub_rule_content' => $request->sub_rule_content[$key] ?? null,
                    ]);

                    if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                        foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                            // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                            if (isset($request->sub_footnote_content[$key][$kys])) {
                                // Create a new footnote for the newly created subsection
                                $footnote = new Footnote();
                                $footnote->sub_rule_id = $sub_rule->sub_rule_id;
                                $footnote->rule_id = $rule->rule_id ?? null;
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

            return redirect()->route('get_act_section', ['id' => $id,'page' => $currentPage])->with('success', 'Rules created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }

    public function view_sub_rule(Request $request,  $id)
    {
        $rule = Rules::where('rule_id', $id)->first();
        $sub_rule = SubRules::where('rule_id', $id)->with('footnoteModel')->get();
        $currentPage = $request->page;
        return view('admin.rules.view', compact('rule','sub_rule','currentPage'));
    }

    public function destroy_sub_rule(string $id)
    {
        try {
            $subrule = SubRules::find($id);

            if (!$subrule) {
                return redirect()->back()->withErrors(['error' => 'Sub-Rule not found.']);
            }
            
            Footnote::where('sub_rule_id', $id)->delete();

            $subrule->delete();

            return redirect()->back()->with('success', 'Sub-Rule and related records deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting Sub-Rule: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete Sub-Rule. Please try again.' . $e->getMessage()]);
        }
    }
    public function destroy(string $id)
    {
        try {
            $rule = Rules::find($id);

            if (!$rule) {
                return redirect()->back()->withErrors(['error' => 'Rule not found.']);
            }

            SubRules::where('rule_id', $id)->delete();
            Footnote::where('rule_id', $id)->delete();

            $rule->delete();

            return redirect()->back()->with('success', 'Rule and related records deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting section: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete section. Please try again.' . $e->getMessage()]);
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
