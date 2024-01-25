<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\ActSummary;
use App\Models\Category;
use App\Models\MainType;
use App\Models\Parts;
use App\Models\PartsType;
use App\Models\SubSection;
use App\Models\Footnote;
use App\Models\Chapter;
use App\Models\Regulation;
use App\Models\Section;
use App\Models\State;
use App\Models\Status;
use App\Models\SubType;
use App\Models\Rules;
use App\Models\Schedule;
use App\Models\SubRules;

class RulesController extends Controller
{
    public function index(Request $request, $id)
    {
    }

    public function add_below_new_rule(Request $request, $id, $rule_no, $rule_rank)
    {
        $rule_no = $rule_no;
        $rule_rank = $rule_rank;
        $rule = Rules::with('Schedulemodel')->where('act_id', $id)
            ->where('rule_no', $rule_no)->first();

        return view('admin.rules.add_new', compact('rule', 'rule_no', 'rule_rank'));
    }

    public function add_new_rule(Request $request)
    {
        // dd($request);
        // die();
        try {
            if ($request->has('schedule_id')) {
                $schedule = Schedule::find($request->schedule_id);

                if ($schedule) {
                    $schedule->schedule_title = $request->schedule_title;
                    $schedule->update();
                }
            }


            $id = $request->act_id;
            $rule_no = $request->rule_no;
            $rule_rank = $request->rule_rank;
            $maintypeId = $request->maintype_id;

            // Calculate the next section number
            $nextRuleNo = $rule_no;
            $nextRuleRank = $rule_rank + 0.01;
            // dd($nextRuleRank);
            // die();



            // Update the existing sections' section_no in the Section table
            // Section::where('section_no', '>=', $nextSectionNo)
            //     ->increment('section_no');

            // Create the new section with the incremented section_no
            $rule = Rules::create([
                'rule_rank'    => $nextRuleRank ?? 1,
                'rule_no'      => $nextRuleNo,
                'act_id'       => $request->act_id,
                'maintype_id'  => $maintypeId,
                'schedule_id'  => $request->schedule_id ?? null,
                'subtypes_id'  => $request->subtypes_id,
                'rule_title'   => $request->rule_title,
                'rule_content' => $request->rule_content,
            ]);

            if ($request->has('rule_footnote_content')) {
                foreach ($request->rule_footnote_content as $key => $item) {
                    // Check if the key exists before using it
                    if (isset($request->rule_footnote_content[$key])) {
                        // Create a new footnote
                        $footnote = new Footnote();
                        $footnote->section_id = $rule->rule_id ?? null;
                        $footnote->act_id = $request->act_id ?? null;
                        $footnote->schedule_id = $request->schedule_id ?? null;
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
                        'rule_no' => $nextRuleNo,
                        'act_id' => $request->act_id,
                        'schedule_id' => $maintypeId == "4" ? $request->schedule_id : null,
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
                                $footnote->schedule_id = $request->schedule_id ?? null;
                                $footnote->footnote_content = $item ?? null;
                                $footnote->save();
                            }
                        }
                    }
                }
            }

            return redirect()->route('get_act_section', ['id' => $id])->with('success', 'Rules created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }


    public function edit_rule($id)
    {
        $rule = Rules::with('Schedulemodel')->where('rule_id', $id)->first();
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



        return view('admin.rules.edit', compact('rule', 'subrule', 'sub_rule_f', 'count'));
    }

    public function update(Request $request, $id)
    {
        // dd($request);
        // die();

        // try {
        if ($request->has('schedule_id')) {
            $schedule = Schedule::find($request->schedule_id);

            if ($schedule) {
                $schedule->schedule_title = $request->schedule_title;
                $schedule->update();
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
                            $footnote->act_id = $rules->act_id ?? null;
                            $footnote->schedule_id = $rules->schedule_id ?? null;
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
                                    $footnote->act_id = $rules->act_id ?? null;
                                    $footnote->schedule_id = $rules->schedule_id ?? null;
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
                    $subrule->act_id = $rules->act_id ?? null;
                    $subrule->schedule_id = $rules->schedule_id ?? null;
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
                                $footnote->act_id = $rules->act_id ?? null;
                                $footnote->schedule_id = $rules->schedule_id ?? null;
                                $footnote->footnote_content = $item ?? null;
                                $footnote->save();
                            }
                        }
                    }
                }
            }
        }



        return redirect()->route('get_act_section', ['id' => $rules->act_id])->with('success', 'Rule updated successfully');
        // } catch (\Exception $e) {
        //     \Log::error('Error updating Act: ' . $e->getMessage());
        //     return redirect()->route('edit-rule', ['id' => $id])->withErrors(['error' => 'Failed to update Section. Please try again.' . $e->getMessage()]);
        // }
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
}
