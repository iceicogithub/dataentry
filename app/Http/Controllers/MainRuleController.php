<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\Category;
use App\Models\State;
use App\Models\MainTypeRule;
use App\Models\SubTypeRule;
use App\Models\PartsType;
use App\Models\RuleMain;
use App\Models\RuleTable;
use App\Models\NewRule;
use App\Models\MainRuleFootnote;
use App\Models\RuleSub;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;

class MainRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $act_id = $id;
        $act = Act::where('act_id', $act_id)->first();
       $new_rule = NewRule::where('act_id', $act_id)->get();
      
       $perPage = request()->get('perPage') ?: 10;
        $page = request()->get('page') ?: 1;
        $slicedItems = array_slice($new_rule->toArray(), ($page - 1) * $perPage, $perPage);

        $paginatedCollection = new LengthAwarePaginator(
            $slicedItems,
            count($new_rule),
            $perPage,
            $page
        );

        $paginatedCollection->appends(['perPage' => $perPage]);

        $paginatedCollection->withPath(request()->url());
        return view('admin.MainRule.index', compact('act','act_id','new_rule','paginatedCollection'));
    }

    /**
     * Show the form for creating a new resource.
     */

     public function add_new_rule($id){
        $category = Category::all();
        $states = State::all();
       return view('admin.MainRule.new_rule', compact('id','category','states',));
     }


     public function store_new_rule(Request $request)
    {
        try{
            $newRule = new NewRule();
            $newRule->act_id = $request->act_id ?? null;
            $newRule->new_rule_title = $request->new_rule_title;
            $newRule->save();

            return redirect()->route('get_rule', ['id' => $newRule->act_id])->with('success', 'Rule created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->route('get_rule', ['id' => $newRule->act_id])->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }

    public function edit_new_rule(string $id)
    {
        $new_rule_id = $id;
        $newRule = NewRule::where('new_rule_id', $new_rule_id)->first();
        if ($newRule) {
            $new_rule_footnote_title = json_decode($newRule->new_rule_footnote_title, true);
            $new_rule_footnote_description = json_decode($newRule->new_rule_footnote_description, true);
        }

        $mainsequence = RuleMain::where('new_rule_id', $id)
        ->with('mainTypeRule') 
        ->get()
        ->map(function ($ruleMain) {
            // Sort the ruletbl collection by rules_rank in ascending order
            $ruleMain->load(['ruletbl' => function ($query) {
                $query->orderBy('rules_rank');
            }]);
            return $ruleMain;
        })
        ->sortBy('rule_main_rank');
           
        $perPage = request()->get('perPage') ?: 10;
        $page = request()->get('page') ?: 1;
        $slicedItems = array_slice($mainsequence->toArray(), ($page - 1) * $perPage, $perPage);

        $paginatedCollection = new LengthAwarePaginator(
            $slicedItems,
            count($mainsequence),
            $perPage,
            $page
        );

        $paginatedCollection->appends(['perPage' => $perPage]);

        $paginatedCollection->withPath(request()->url());
            
        
        return view('admin.MainRule.show', compact('newRule','new_rule_footnote_title','new_rule_footnote_description','paginatedCollection'));     
    }


    public function create($id)
    {
        $newRule = NewRule::find($id);
        $mtype = MainTypeRule::all();
        $stype = SubTypeRule::all();
        $parts = PartsType::all();
        
        return view('admin.MainRule.create', compact('newRule','mtype','stype','parts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {

        try {

            $newRule = NewRule::find($id);
            $newRule->update([
                'category_id' => $request->category_id,
                'state_id' => $request->state_id ?? null,
                'new_rule_title' => $request->new_rule_title,
            ]);
        
           $k = 0;
            foreach ($request->rule_maintype_id as $key => $rulemaintypeid) {
                $lastRank = RuleMain::max('rule_main_rank');
                $lastRank = ceil(floatval($lastRank));
                $lastRank = max(0, $lastRank);
                $lastRank = (int) $lastRank;

                if($lastRank){
                  $k=   $lastRank;
                }

                if ($rulemaintypeid == "1") {
                    $ruleMain = new RuleMain();
                    $ruleMain->new_rule_id = $newRule->new_rule_id;
                    $ruleMain->rule_main_rank = $k + 1;
                    $ruleMain->act_id = $newRule->act_id ?? null;
                    $ruleMain->rule_maintype_id = $rulemaintypeid;
                    $ruleMain->rule_main_title = $request->chapter_title[$key] ?? null;
                    $ruleMain->save();

                    if (isset($request->rule_subtypes_id[$key]) && $request->rule_subtypes_id[$key] == 1) {
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;

                        $i = 0;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];
                                $lastRuleRank= RuleTable::max('rules_rank');
                                $lastRuleRank = ceil(floatval($lastRuleRank));
                                $lastRuleRank = max(0, $lastRuleRank);
                                $lastRuleRank = (int) $lastRuleRank;

                                if($lastRuleRank){
                                   $i = $lastRuleRank;
                                }       
                                $section = RuleTable::create([
                                    'rules_rank' => $i + 1,
                                    'rules_no' => $currentSectionNo,
                                    'rule_main_id' => $ruleMain->rule_main_id,
                                    'rule_subtypes_id' => $rule_subtypes_id,
                                    'rules_title' => $sectiontitle,
                                ]);
                            }
                        }
                    }elseif ($request->rule_subtypes_id[$key] == 2) {
                        $i =0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentArticleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $articletitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 3) {
                        $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentRuleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $ruletitle,
                            ]);
                           
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 4) {
                         $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentRegulationNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $regulationtitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 5) {
                        $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentListNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $listtitle,
                            ]);

                        }
                    } elseif ($request->rule_subtypes_id[$key] == 6) {
                             $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentPartNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $parttitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 7) {
                         $i =0; 
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentAppendicesNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $appendicestitle,
                            ]);
                           
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 8) {
                         $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentOrderNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $ordertitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 9) {
                           $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentAnnexureNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $annexuretitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 10) {
                         $i =0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentStscheduleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $stscheduletitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 11) {
                        $i =0;
                       $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                       foreach ($request->form_title[$key] as $index => $formtitle) {
                           $currentFormNo = $request->form_no[$key][$index];
                           $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentFormNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $formtitle,
                            ]);
                       }
                   }
                }else if ($rulemaintypeid == "2") {
                    $ruleMain = new RuleMain();
                    $ruleMain->new_rule_id = $newRule->new_rule_id;
                    $ruleMain->rule_main_rank = $k + 1;
                    $ruleMain->act_id = $newRule->act_id ?? null;
                    $ruleMain->rule_maintype_id = $rulemaintypeid;
                    $ruleMain->rule_main_title = $request->schedule_title[$key] ?? null;
                    $ruleMain->save();

                    if (isset($request->rule_subtypes_id[$key]) && $request->rule_subtypes_id[$key] == 1) {
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;

                        $i = 0;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];
                                $lastRuleRank= RuleTable::max('rules_rank');
                                $lastRuleRank = ceil(floatval($lastRuleRank));
                                $lastRuleRank = max(0, $lastRuleRank);
                                $lastRuleRank = (int) $lastRuleRank;

                                if($lastRuleRank){
                                   $i = $lastRuleRank;
                                }       
                                $section = RuleTable::create([
                                    'rules_rank' => $i + 1,
                                    'rules_no' => $currentSectionNo,
                                    'rule_main_id' => $ruleMain->rule_main_id,
                                    'rule_subtypes_id' => $rule_subtypes_id,
                                    'rules_title' => $sectiontitle,
                                ]);
                            }
                        }
                    }elseif ($request->rule_subtypes_id[$key] == 2) {
                        $i =0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentArticleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $articletitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 3) {
                        $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentRuleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $ruletitle,
                            ]);
                           
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 4) {
                         $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentRegulationNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $regulationtitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 5) {
                        $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentListNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $listtitle,
                            ]);

                        }
                    } elseif ($request->rule_subtypes_id[$key] == 6) {
                             $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentPartNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $parttitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 7) {
                         $i =0; 
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentAppendicesNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $appendicestitle,
                            ]);
                           
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 8) {
                         $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentOrderNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $ordertitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 9) {
                           $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentAnnexureNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $annexuretitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 10) {
                         $i =0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentStscheduleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $stscheduletitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 11) {
                        $i =0;
                       $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                       foreach ($request->form_title[$key] as $index => $formtitle) {
                           $currentFormNo = $request->form_no[$key][$index];
                           $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentFormNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $formtitle,
                            ]);
                       }
                   }
                }else if ($rulemaintypeid == "3") {
                    $ruleMain = new RuleMain();
                    $ruleMain->new_rule_id = $newRule->new_rule_id;
                    $ruleMain->rule_main_rank = $k + 1;
                    $ruleMain->act_id = $newRule->act_id ?? null;
                    $ruleMain->rule_maintype_id = $rulemaintypeid;
                    $ruleMain->rule_main_title = $request->main_annexure_title[$key] ?? null;
                    $ruleMain->save();

                    if (isset($request->rule_subtypes_id[$key]) && $request->rule_subtypes_id[$key] == 1) {
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;

                        $i = 0;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];
                                $lastRuleRank= RuleTable::max('rules_rank');
                                $lastRuleRank = ceil(floatval($lastRuleRank));
                                $lastRuleRank = max(0, $lastRuleRank);
                                $lastRuleRank = (int) $lastRuleRank;

                                if($lastRuleRank){
                                   $i = $lastRuleRank;
                                }       
                                $section = RuleTable::create([
                                    'rules_rank' => $i + 1,
                                    'rules_no' => $currentSectionNo,
                                    'rule_main_id' => $ruleMain->rule_main_id,
                                    'rule_subtypes_id' => $rule_subtypes_id,
                                    'rules_title' => $sectiontitle,
                                ]);
                            }
                        }
                    }elseif ($request->rule_subtypes_id[$key] == 2) {
                        $i =0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentArticleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $articletitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 3) {
                        $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentRuleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $ruletitle,
                            ]);
                           
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 4) {
                         $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentRegulationNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $regulationtitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 5) {
                        $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentListNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $listtitle,
                            ]);

                        }
                    } elseif ($request->rule_subtypes_id[$key] == 6) {
                             $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentPartNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $parttitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 7) {
                         $i =0; 
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentAppendicesNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $appendicestitle,
                            ]);
                           
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 8) {
                         $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentOrderNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $ordertitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 9) {
                           $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentAnnexureNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $annexuretitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 10) {
                         $i =0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentStscheduleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $stscheduletitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 11) {
                        $i =0;
                       $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                       foreach ($request->form_title[$key] as $index => $formtitle) {
                           $currentFormNo = $request->form_no[$key][$index];
                           $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentFormNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $formtitle,
                            ]);
                       }
                   }
                }else if ($rulemaintypeid == "4") {
                    $ruleMain = new RuleMain();
                    $ruleMain->new_rule_id = $newRule->new_rule_id;
                    $ruleMain->rule_main_rank = $k + 1;
                    $ruleMain->act_id = $newRule->act_id ?? null;
                    $ruleMain->rule_maintype_id = $rulemaintypeid;
                    $ruleMain->rule_main_title = $request->parts_title[$key] ?? null;
                    $ruleMain->save();

                    if (isset($request->rule_subtypes_id[$key]) && $request->rule_subtypes_id[$key] == 1) {
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;

                        $i = 0;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];
                                $lastRuleRank= RuleTable::max('rules_rank');
                                $lastRuleRank = ceil(floatval($lastRuleRank));
                                $lastRuleRank = max(0, $lastRuleRank);
                                $lastRuleRank = (int) $lastRuleRank;

                                if($lastRuleRank){
                                   $i = $lastRuleRank;
                                }       
                                $section = RuleTable::create([
                                    'rules_rank' => $i + 1,
                                    'rules_no' => $currentSectionNo,
                                    'rule_main_id' => $ruleMain->rule_main_id,
                                    'rule_subtypes_id' => $rule_subtypes_id,
                                    'rules_title' => $sectiontitle,
                                ]);
                            }
                        }
                    }elseif ($request->rule_subtypes_id[$key] == 2) {
                        $i =0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentArticleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $articletitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 3) {
                        $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentRuleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $ruletitle,
                            ]);
                           
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 4) {
                         $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentRegulationNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $regulationtitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 5) {
                        $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentListNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $listtitle,
                            ]);

                        }
                    } elseif ($request->rule_subtypes_id[$key] == 6) {
                             $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentPartNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $parttitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 7) {
                         $i =0; 
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentAppendicesNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $appendicestitle,
                            ]);
                           
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 8) {
                         $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentOrderNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $ordertitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 9) {
                           $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentAnnexureNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $annexuretitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 10) {
                         $i =0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentStscheduleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $stscheduletitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 11) {
                        $i =0;
                       $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                       foreach ($request->form_title[$key] as $index => $formtitle) {
                           $currentFormNo = $request->form_no[$key][$index];
                           $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentFormNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $formtitle,
                            ]);
                       }
                   }
                }else if ($rulemaintypeid == "5") {
                    $ruleMain = new RuleMain();
                    $ruleMain->new_rule_id = $newRule->new_rule_id;
                    $ruleMain->rule_main_rank = $k + 1;
                    $ruleMain->act_id = $newRule->act_id ?? null;
                    $ruleMain->rule_maintype_id = $rulemaintypeid;
                    $ruleMain->rule_main_title = $request->appendix_title[$key] ?? null;
                    $ruleMain->save();

                    if (isset($request->rule_subtypes_id[$key]) && $request->rule_subtypes_id[$key] == 1) {
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;

                        $i = 0;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];
                                $lastRuleRank= RuleTable::max('rules_rank');
                                $lastRuleRank = ceil(floatval($lastRuleRank));
                                $lastRuleRank = max(0, $lastRuleRank);
                                $lastRuleRank = (int) $lastRuleRank;

                                if($lastRuleRank){
                                   $i = $lastRuleRank;
                                }       
                                $section = RuleTable::create([
                                    'rules_rank' => $i + 1,
                                    'rules_no' => $currentSectionNo,
                                    'rule_main_id' => $ruleMain->rule_main_id,
                                    'rule_subtypes_id' => $rule_subtypes_id,
                                    'rules_title' => $sectiontitle,
                                ]);
                            }
                        }
                    }elseif ($request->rule_subtypes_id[$key] == 2) {
                        $i =0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentArticleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $articletitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 3) {
                        $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentRuleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $ruletitle,
                            ]);
                           
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 4) {
                         $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentRegulationNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $regulationtitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 5) {
                        $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentListNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $listtitle,
                            ]);

                        }
                    } elseif ($request->rule_subtypes_id[$key] == 6) {
                             $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentPartNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $parttitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 7) {
                         $i =0; 
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentAppendicesNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $appendicestitle,
                            ]);
                           
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 8) {
                         $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentOrderNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $ordertitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 9) {
                           $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentAnnexureNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $annexuretitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 10) {
                         $i =0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentStscheduleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $stscheduletitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 11) {
                        $i =0;
                       $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                       foreach ($request->form_title[$key] as $index => $formtitle) {
                           $currentFormNo = $request->form_no[$key][$index];
                           $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentFormNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $formtitle,
                            ]);
                       }
                   }
                }else if ($rulemaintypeid == "6") {
                    $ruleMain = new RuleMain();
                    $ruleMain->new_rule_id = $newRule->new_rule_id;
                    $ruleMain->rule_main_rank = $k + 1;
                    $ruleMain->act_id = $newRule->act_id ?? null;
                    $ruleMain->rule_maintype_id = $rulemaintypeid;
                    $ruleMain->rule_main_title = $request->main_form_title[$key] ?? null;
                    $ruleMain->save();

                    if (isset($request->rule_subtypes_id[$key]) && $request->rule_subtypes_id[$key] == 1) {
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;

                        $i = 0;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];
                                $lastRuleRank= RuleTable::max('rules_rank');
                                $lastRuleRank = ceil(floatval($lastRuleRank));
                                $lastRuleRank = max(0, $lastRuleRank);
                                $lastRuleRank = (int) $lastRuleRank;

                                if($lastRuleRank){
                                   $i = $lastRuleRank;
                                }       
                                $section = RuleTable::create([
                                    'rules_rank' => $i + 1,
                                    'rules_no' => $currentSectionNo,
                                    'rule_main_id' => $ruleMain->rule_main_id,
                                    'rule_subtypes_id' => $rule_subtypes_id,
                                    'rules_title' => $sectiontitle,
                                ]);
                            }
                        }
                    }elseif ($request->rule_subtypes_id[$key] == 2) {
                        $i =0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentArticleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $articletitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 3) {
                        $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentRuleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $ruletitle,
                            ]);
                           
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 4) {
                         $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentRegulationNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $regulationtitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 5) {
                        $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentListNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $listtitle,
                            ]);

                        }
                    } elseif ($request->rule_subtypes_id[$key] == 6) {
                             $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentPartNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $parttitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 7) {
                         $i =0; 
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentAppendicesNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $appendicestitle,
                            ]);
                           
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 8) {
                         $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentOrderNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $ordertitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 9) {
                           $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentAnnexureNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $annexuretitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 10) {
                         $i =0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentStscheduleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $stscheduletitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 11) {
                        $i =0;
                       $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                       foreach ($request->form_title[$key] as $index => $formtitle) {
                           $currentFormNo = $request->form_no[$key][$index];
                           $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentFormNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $formtitle,
                            ]);
                       }
                   }
                }
            }

            return redirect()->route('edit_new_rule', ['id' => $newRule->new_rule_id])->with('success', 'Index added successfully');

        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }

    public function add_below_new_rule_maintype(Request $request, $newRuleId, $id){
        // $clickRule= RuleMain::find($id);
        $mainRule = RuleMain::where('rule_main_id',$id)->with('newRule')->first();
        // dd($mainRule);
        // die();
        $mtype = MainTypeRule::all();
        $stype = SubTypeRule::all();
        $parts = PartsType::all();
        
        return view('admin.MainRule.add_new_rule_maintype', compact('mainRule','mtype','stype','parts'));
    }

    public function store_rule_maintype(Request $request){
        try {

            $id =  $request->new_rule_id;
            $k =  $request->click_main_rank;

            $newRule = NewRule::find($id);
            $newRule->update([
                'category_id' => $request->category_id,
                'state_id' => $request->state_id ?? null,
                'new_rule_title' => $request->new_rule_title,
            ]);

        
         
            foreach ($request->rule_maintype_id as $key => $rulemaintypeid) {
               

                if ($rulemaintypeid == "1") {
                    $ruleMain = new RuleMain();
                    $ruleMain->new_rule_id = $newRule->new_rule_id;
                    $ruleMain->rule_main_rank = $k + 0.1;
                    $ruleMain->act_id = $newRule->act_id ?? null;
                    $ruleMain->rule_maintype_id = $rulemaintypeid;
                    $ruleMain->rule_main_title = $request->chapter_title[$key] ?? null;
                    $ruleMain->save();

                    if (isset($request->rule_subtypes_id[$key]) && $request->rule_subtypes_id[$key] == 1) {
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;

                        $i = 0;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];
                                $lastRuleRank= RuleTable::max('rules_rank');
                                $lastRuleRank = ceil(floatval($lastRuleRank));
                                $lastRuleRank = max(0, $lastRuleRank);
                                $lastRuleRank = (int) $lastRuleRank;

                                if($lastRuleRank){
                                   $i = $lastRuleRank;
                                }       
                                $section = RuleTable::create([
                                    'rules_rank' => $i + 1,
                                    'rules_no' => $currentSectionNo,
                                    'rule_main_id' => $ruleMain->rule_main_id,
                                    'rule_subtypes_id' => $rule_subtypes_id,
                                    'rules_title' => $sectiontitle,
                                ]);
                            }
                        }
                    }elseif ($request->rule_subtypes_id[$key] == 2) {
                        $i =0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentArticleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $articletitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 3) {
                        $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentRuleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $ruletitle,
                            ]);
                           
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 4) {
                         $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentRegulationNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $regulationtitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 5) {
                        $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentListNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $listtitle,
                            ]);

                        }
                    } elseif ($request->rule_subtypes_id[$key] == 6) {
                             $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentPartNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $parttitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 7) {
                         $i =0; 
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentAppendicesNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $appendicestitle,
                            ]);
                           
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 8) {
                         $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentOrderNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $ordertitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 9) {
                           $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentAnnexureNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $annexuretitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 10) {
                         $i =0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentStscheduleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $stscheduletitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 11) {
                        $i =0;
                       $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                       foreach ($request->form_title[$key] as $index => $formtitle) {
                           $currentFormNo = $request->form_no[$key][$index];
                           $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentFormNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $formtitle,
                            ]);
                       }
                   }
                }else if ($rulemaintypeid == "2") {
                    $ruleMain = new RuleMain();
                    $ruleMain->new_rule_id = $newRule->new_rule_id;
                    $ruleMain->rule_main_rank = $k + 0.1;
                    $ruleMain->act_id = $newRule->act_id ?? null;
                    $ruleMain->rule_maintype_id = $rulemaintypeid;
                    $ruleMain->rule_main_title = $request->schedule_title[$key] ?? null;
                    $ruleMain->save();

                    if (isset($request->rule_subtypes_id[$key]) && $request->rule_subtypes_id[$key] == 1) {
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;

                        $i = 0;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];
                                $lastRuleRank= RuleTable::max('rules_rank');
                                $lastRuleRank = ceil(floatval($lastRuleRank));
                                $lastRuleRank = max(0, $lastRuleRank);
                                $lastRuleRank = (int) $lastRuleRank;

                                if($lastRuleRank){
                                   $i = $lastRuleRank;
                                }       
                                $section = RuleTable::create([
                                    'rules_rank' => $i + 1,
                                    'rules_no' => $currentSectionNo,
                                    'rule_main_id' => $ruleMain->rule_main_id,
                                    'rule_subtypes_id' => $rule_subtypes_id,
                                    'rules_title' => $sectiontitle,
                                ]);
                            }
                        }
                    }elseif ($request->rule_subtypes_id[$key] == 2) {
                        $i =0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentArticleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $articletitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 3) {
                        $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentRuleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $ruletitle,
                            ]);
                           
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 4) {
                         $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentRegulationNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $regulationtitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 5) {
                        $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentListNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $listtitle,
                            ]);

                        }
                    } elseif ($request->rule_subtypes_id[$key] == 6) {
                             $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentPartNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $parttitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 7) {
                         $i =0; 
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentAppendicesNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $appendicestitle,
                            ]);
                           
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 8) {
                         $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentOrderNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $ordertitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 9) {
                           $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentAnnexureNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $annexuretitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 10) {
                         $i =0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentStscheduleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $stscheduletitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 11) {
                        $i =0;
                       $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                       foreach ($request->form_title[$key] as $index => $formtitle) {
                           $currentFormNo = $request->form_no[$key][$index];
                           $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentFormNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $formtitle,
                            ]);
                       }
                   }
                }else if ($rulemaintypeid == "3") {
                    $ruleMain = new RuleMain();
                    $ruleMain->new_rule_id = $newRule->new_rule_id;
                    $ruleMain->rule_main_rank = $k + 0.1;
                    $ruleMain->act_id = $newRule->act_id ?? null;
                    $ruleMain->rule_maintype_id = $rulemaintypeid;
                    $ruleMain->rule_main_title = $request->main_annexure_title[$key] ?? null;
                    $ruleMain->save();

                    if (isset($request->rule_subtypes_id[$key]) && $request->rule_subtypes_id[$key] == 1) {
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;

                        $i = 0;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];
                                $lastRuleRank= RuleTable::max('rules_rank');
                                $lastRuleRank = ceil(floatval($lastRuleRank));
                                $lastRuleRank = max(0, $lastRuleRank);
                                $lastRuleRank = (int) $lastRuleRank;

                                if($lastRuleRank){
                                   $i = $lastRuleRank;
                                }       
                                $section = RuleTable::create([
                                    'rules_rank' => $i + 1,
                                    'rules_no' => $currentSectionNo,
                                    'rule_main_id' => $ruleMain->rule_main_id,
                                    'rule_subtypes_id' => $rule_subtypes_id,
                                    'rules_title' => $sectiontitle,
                                ]);
                            }
                        }
                    }elseif ($request->rule_subtypes_id[$key] == 2) {
                        $i =0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentArticleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $articletitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 3) {
                        $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentRuleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $ruletitle,
                            ]);
                           
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 4) {
                         $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentRegulationNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $regulationtitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 5) {
                        $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentListNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $listtitle,
                            ]);

                        }
                    } elseif ($request->rule_subtypes_id[$key] == 6) {
                             $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentPartNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $parttitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 7) {
                         $i =0; 
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentAppendicesNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $appendicestitle,
                            ]);
                           
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 8) {
                         $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentOrderNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $ordertitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 9) {
                           $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentAnnexureNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $annexuretitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 10) {
                         $i =0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentStscheduleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $stscheduletitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 11) {
                        $i =0;
                       $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                       foreach ($request->form_title[$key] as $index => $formtitle) {
                           $currentFormNo = $request->form_no[$key][$index];
                           $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentFormNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $formtitle,
                            ]);
                       }
                   }
                }else if ($rulemaintypeid == "4") {
                    $ruleMain = new RuleMain();
                    $ruleMain->new_rule_id = $newRule->new_rule_id;
                    $ruleMain->rule_main_rank = $k + 0.1;
                    $ruleMain->act_id = $newRule->act_id ?? null;
                    $ruleMain->rule_maintype_id = $rulemaintypeid;
                    $ruleMain->rule_main_title = $request->parts_title[$key] ?? null;
                    $ruleMain->save();

                    if (isset($request->rule_subtypes_id[$key]) && $request->rule_subtypes_id[$key] == 1) {
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;

                        $i = 0;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];
                                $lastRuleRank= RuleTable::max('rules_rank');
                                $lastRuleRank = ceil(floatval($lastRuleRank));
                                $lastRuleRank = max(0, $lastRuleRank);
                                $lastRuleRank = (int) $lastRuleRank;

                                if($lastRuleRank){
                                   $i = $lastRuleRank;
                                }       
                                $section = RuleTable::create([
                                    'rules_rank' => $i + 1,
                                    'rules_no' => $currentSectionNo,
                                    'rule_main_id' => $ruleMain->rule_main_id,
                                    'rule_subtypes_id' => $rule_subtypes_id,
                                    'rules_title' => $sectiontitle,
                                ]);
                            }
                        }
                    }elseif ($request->rule_subtypes_id[$key] == 2) {
                        $i =0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentArticleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $articletitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 3) {
                        $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentRuleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $ruletitle,
                            ]);
                           
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 4) {
                         $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentRegulationNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $regulationtitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 5) {
                        $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentListNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $listtitle,
                            ]);

                        }
                    } elseif ($request->rule_subtypes_id[$key] == 6) {
                             $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentPartNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $parttitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 7) {
                         $i =0; 
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentAppendicesNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $appendicestitle,
                            ]);
                           
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 8) {
                         $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentOrderNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $ordertitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 9) {
                           $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentAnnexureNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $annexuretitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 10) {
                         $i =0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentStscheduleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $stscheduletitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 11) {
                        $i =0;
                       $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                       foreach ($request->form_title[$key] as $index => $formtitle) {
                           $currentFormNo = $request->form_no[$key][$index];
                           $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentFormNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $formtitle,
                            ]);
                       }
                   }
                }else if ($rulemaintypeid == "5") {
                    $ruleMain = new RuleMain();
                    $ruleMain->new_rule_id = $newRule->new_rule_id;
                    $ruleMain->rule_main_rank = $k + 0.1;
                    $ruleMain->act_id = $newRule->act_id ?? null;
                    $ruleMain->rule_maintype_id = $rulemaintypeid;
                    $ruleMain->rule_main_title = $request->appendix_title[$key] ?? null;
                    $ruleMain->save();

                    if (isset($request->rule_subtypes_id[$key]) && $request->rule_subtypes_id[$key] == 1) {
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;

                        $i = 0;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];
                                $lastRuleRank= RuleTable::max('rules_rank');
                                $lastRuleRank = ceil(floatval($lastRuleRank));
                                $lastRuleRank = max(0, $lastRuleRank);
                                $lastRuleRank = (int) $lastRuleRank;

                                if($lastRuleRank){
                                   $i = $lastRuleRank;
                                }       
                                $section = RuleTable::create([
                                    'rules_rank' => $i + 1,
                                    'rules_no' => $currentSectionNo,
                                    'rule_main_id' => $ruleMain->rule_main_id,
                                    'rule_subtypes_id' => $rule_subtypes_id,
                                    'rules_title' => $sectiontitle,
                                ]);
                            }
                        }
                    }elseif ($request->rule_subtypes_id[$key] == 2) {
                        $i =0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentArticleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $articletitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 3) {
                        $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentRuleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $ruletitle,
                            ]);
                           
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 4) {
                         $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentRegulationNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $regulationtitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 5) {
                        $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentListNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $listtitle,
                            ]);

                        }
                    } elseif ($request->rule_subtypes_id[$key] == 6) {
                             $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentPartNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $parttitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 7) {
                         $i =0; 
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentAppendicesNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $appendicestitle,
                            ]);
                           
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 8) {
                         $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentOrderNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $ordertitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 9) {
                           $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentAnnexureNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $annexuretitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 10) {
                         $i =0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentStscheduleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $stscheduletitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 11) {
                        $i =0;
                       $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                       foreach ($request->form_title[$key] as $index => $formtitle) {
                           $currentFormNo = $request->form_no[$key][$index];
                           $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentFormNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $formtitle,
                            ]);
                       }
                   }
                }else if ($rulemaintypeid == "6") {
                    $ruleMain = new RuleMain();
                    $ruleMain->new_rule_id = $newRule->new_rule_id;
                    $ruleMain->rule_main_rank = $k + 0.1;
                    $ruleMain->act_id = $newRule->act_id ?? null;
                    $ruleMain->rule_maintype_id = $rulemaintypeid;
                    $ruleMain->rule_main_title = $request->main_form_title[$key] ?? null;
                    $ruleMain->save();

                    if (isset($request->rule_subtypes_id[$key]) && $request->rule_subtypes_id[$key] == 1) {
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;

                        $i = 0;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];
                                $lastRuleRank= RuleTable::max('rules_rank');
                                $lastRuleRank = ceil(floatval($lastRuleRank));
                                $lastRuleRank = max(0, $lastRuleRank);
                                $lastRuleRank = (int) $lastRuleRank;

                                if($lastRuleRank){
                                   $i = $lastRuleRank;
                                }       
                                $section = RuleTable::create([
                                    'rules_rank' => $i + 1,
                                    'rules_no' => $currentSectionNo,
                                    'rule_main_id' => $ruleMain->rule_main_id,
                                    'rule_subtypes_id' => $rule_subtypes_id,
                                    'rules_title' => $sectiontitle,
                                ]);
                            }
                        }
                    }elseif ($request->rule_subtypes_id[$key] == 2) {
                        $i =0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentArticleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $articletitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 3) {
                        $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentRuleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $ruletitle,
                            ]);
                           
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 4) {
                         $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentRegulationNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $regulationtitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 5) {
                        $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentListNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $listtitle,
                            ]);

                        }
                    } elseif ($request->rule_subtypes_id[$key] == 6) {
                             $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentPartNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $parttitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 7) {
                         $i =0; 
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentAppendicesNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $appendicestitle,
                            ]);
                           
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 8) {
                         $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentOrderNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $ordertitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 9) {
                           $i = 0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentAnnexureNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $annexuretitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 10) {
                         $i =0;
                        $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];
                            $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentStscheduleNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $stscheduletitle,
                            ]);
                        }
                    } elseif ($request->rule_subtypes_id[$key] == 11) {
                        $i =0;
                       $rule_subtypes_id = $request->rule_subtypes_id[$key] ?? null;
                       foreach ($request->form_title[$key] as $index => $formtitle) {
                           $currentFormNo = $request->form_no[$key][$index];
                           $lastRuleRank= RuleTable::max('rules_rank');
                            $lastRuleRank = ceil(floatval($lastRuleRank));
                            $lastRuleRank = max(0, $lastRuleRank);
                            $lastRuleRank = (int) $lastRuleRank;

                            if($lastRuleRank){
                               $i = $lastRuleRank;
                            }       
                            $section = RuleTable::create([
                                'rules_rank' => $i + 1,
                                'rules_no' => $currentFormNo,
                                'rule_main_id' => $ruleMain->rule_main_id,
                                'rule_subtypes_id' => $rule_subtypes_id,
                                'rules_title' => $formtitle,
                            ]);
                       }
                   }
                }
            }

            return redirect()->route('edit_new_rule', ['id' => $id])->with('success', 'Index added successfully');

        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }

    }

    public function delete_rule_maintype($id){
        try {
            $mainRule = RuleMain::findOrFail($id);
            $mainRule->delete();
            Session::flash('success', 'RuleMain deleted successfully.');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to delete RuleMain.');
        }
        return redirect()->back()->with('flash_timeout', 10);
    }

    public function edit_ruleTable($id,Request $request){
        $currentPage = $request->page;
        $ruleTable = RuleTable::with(['mainRule', 'mainRule.newRule', 'ruleFootnoteModel' => function ($query) {
            $query->whereNull('rule_sub_id');
        }])->where('rules_id', $id)->firstOrFail(); 

        $ruleSubs = RuleSub::where('rules_id',$id)->with('ruleSubFootnoteModel')->get();
        // dd($ruleSub);
        // die();
        return view('admin.MainRule.edit', compact('ruleTable','ruleSubs','currentPage'));
    }

    public function update_main_rule(Request $request,$id)
    {
       
        try {
            $currentPage = $request->currentPage;
            $new_rule_id = $request->new_rule_id;
            $rule_main_id = $request->rule_main_id;
            $rule_subtypes_id = $request->rule_subtypes_id;
            if ($request->has('rule_main_id')) {
                $rulesM = RuleMain::find($request->rule_main_id);
               
                if ($rulesM) {
                    $rulesM->rule_main_title = $request->rule_main_title;
                    $rulesM->update();
                }
            }
        
            $rulet = RuleTable::find($id);
            
            // Check if the section is found
            if (!$rulet) {
                return redirect()->route('edit-section', ['id' => $id])->withErrors(['error' => 'Section not found']);
            }
            if ($rulet) {
                $rulet->rules_content = $request->rules_content ?? null;
                $rulet->rules_title = $request->rules_title ?? null;
                $rulet->rules_no = $request->rules_no ?? null;
                $rulet->update();

                if ($request->has('sec_footnote_content')) {
                    $item = $request->sec_footnote_content;
                    if ($request->has('sec_footnote_id')) {
            
                        $footnote_id = $request->sec_footnote_id;
            
                        if (isset($footnote_id)) {
                           
                            $foot = MainRuleFootnote::find($footnote_id);

                            if ($foot) {
                                $foot->footnote_content = $item ?? null;
                                $foot->update();
                            }
                        }
                    }else {
                        
                            $footnote = new MainRuleFootnote();
                            $footnote->rules_id = $id ?? null;
                            $footnote->new_rule_id = $new_rule_id ?? null;
                            $footnote->footnote_content = $item ?? null;
                            $footnote->save();
                        }
                }  
            }


            if ($request->has('rule_sub_no')) {
                foreach ($request->rule_sub_no as $key => $item) {
                    $rule_sub_id = $request->rule_sub_id[$key] ?? null;
                    $rule_sub_content = $request->rule_sub_content[$key] ?? null;
                     
                    // Check if sub_section_id is present and valid
                    if ($rule_sub_id && $existingSubRule = RuleSub::find($rule_sub_id)) {
                        $existingSubRule->update([
                            'rule_sub_no' => $item,
                            'rule_sub_content' => $rule_sub_content,
                        ]);

                        if ($request->has('sub_footnote_content') && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                            foreach ($request->sub_footnote_content[$key] as $kys => $footnote_content) {
                                // Check if the footnote with the given ID exists
                                // dd($request->sub_footnote_id[$key][$kys]);
                                // die();
                               
                                $footnote_id = $request->sub_footnote_id[$key][$kys] ?? null;
                                if ($footnote_id && $foot = MainRuleFootnote::find($footnote_id)) {
                                    $foot->update(['footnote_content' => $footnote_content]);
                                }
                                else {
                                 // Create new footnote if ID is not provided or invalid
                                    $footnote = new MainRuleFootnote();
                                    $footnote->rule_sub_id = $rule_sub_id;
                                    $footnote->rules_id = $id ?? null;
                                    $footnote->new_rule_id = $new_rule_id ?? null;
                                    $footnote->footnote_content = $footnote_content ?? null;
                                    $footnote->save();
                                }
                            }
                        }
                    } else {

                        $i = 0;
                        $lastSubRuleRank= RuleSub::max('rule_sub_rank');
                        $lastSubRuleRank = ceil(floatval($lastSubRuleRank));
                        $lastSubRuleRank = max(0, $lastSubRuleRank);
                        $lastSubRuleRank = (int) $lastSubRuleRank;

                        if($lastSubRuleRank){
                           $i = $lastSubRuleRank;
                        }   

                            $subsec = new RuleSub();
                            $subsec->rules_id = $id ?? null;
                            $subsec->rule_main_id = $rule_main_id ?? null;
                            $subsec->rule_sub_rank = $i + 1;
                            $subsec->rule_subtypes_id = $rule_subtypes_id;
                            $subsec->rule_sub_no = $item ?? null;
                            $subsec->new_rule_id = $new_rule_id ?? null;
                            $subsec->rule_sub_content = $rule_sub_content ?? null;
                            $subsec->save();
            
                        // Handle footnotes for the new sub_section
                        if ($request->has('sub_footnote_content') && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                            foreach ($request->sub_footnote_content[$key] as $kys => $footnote_content) {
                          
                                $footnote = new MainRuleFootnote();
                                $footnote->rule_sub_id = $subsec->rule_sub_id;
                                $footnote->rules_id = $id ?? null;
                                $footnote->new_rule_id = $new_rule_id ?? null;
                                $footnote->footnote_content = $footnote_content ?? null;
                                $footnote->save();
                            }
                        }
                    }
                }
            }
            

            return redirect()->route('edit_new_rule', ['id' => $new_rule_id,'page' => $currentPage])->with('success', 'Section updated successfully');
        } catch (\Exception $e) {
            \Log::error('Error updating Act: ' . $e->getMessage());
            return redirect()->route('edit_new_rule', ['id' => $new_rule_id])->withErrors(['error' => 'Failed to update Section. Please try again.' . $e->getMessage()]);
        }
    }

    public function view_rule_sub($id){
        $ruleSub = RuleSub::where('rules_id',$id)->get();
       
        return view('admin.MainRule.view_rule_sub', compact('ruleSub'));
    }

    public function delete_rule_sub($id){
        try {
            $ruleSub = RuleSub::findOrFail($id);
            $ruleSub->delete();
            Session::flash('success', 'deleted successfully.');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to delete RuleMain.');
        }
        return redirect()->back()->with('flash_timeout', 10);
    }

    public function delete_rulestbl($id){
        try {
            $ruletbl = RuleTable::findOrFail($id);
            $ruletbl->delete();
            Session::flash('success', 'deleted successfully.');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to delete RuleMain.');
        }
        return redirect()->back()->with('flash_timeout', 10);
    }


    public function add_below_new_ruletbl(Request $request, $ruleMainId, $id){
        $currentPage = $request->page;
        $ruleTable = RuleTable::with(['mainRule', 'mainRule.newRule', 'ruleFootnoteModel' => function ($query) {
            $query->whereNull('rule_sub_id');
        }])->where('rules_id', $id)->firstOrFail(); 

        return view('admin.MainRule.add_new_ruletbl', compact('ruleTable','currentPage'));
    }


    public function add_new_ruletbl(Request $request){
        try {


            $id = $request->click_rules_id;
            $currentPage = $request->currentPage;
            $i = $request->click_rules_rank;
            $new_rule_id = $request->new_rule_id;
            $rule_main_id = $request->rule_main_id;
            $rule_subtypes_id = $request->rule_subtypes_id;

            $clickRuleTbl = RuleTable::find($id);

        //     if($clickRuleTbl->is_append == 1){
        //         $decreasingFactor = 1 / pow(10, $clickRuleTbl->is_append);
        //         $rulet = new RuleTable;
        //         $rulet->rule_main_id = $rule_main_id;
        //         $rulet->rule_subtypes_id = $rule_subtypes_id;
        //         $rulet->rules_content = $request->rules_content ?? null;
        //         $rulet->rules_title = $request->rules_title ?? null;
        //         $rulet->rules_no = $request->rules_no ?? null;
        //         $rulet->is_append = 1.0;
        //         $rank = $i + $decreasingFactor;
        //         $rulet->rules_rank = $rank;
        //         $rulet->save();

        //         $clickRuleTbl->is_append += 1;
        //         $clickRuleTbl->save();
        //     }
        //     else{
        //         $decreasingFactor = 1 / pow(10, $clickRuleTbl->is_append);
            
        //         $rulet = new RuleTable;
        //         $rulet->rule_main_id = $rule_main_id;
        //         $rulet->rule_subtypes_id = $rule_subtypes_id;
        //         $rulet->rules_content = $request->rules_content ?? null;
        //         $rulet->rules_title = $request->rules_title ?? null;
        //         $rulet->rules_no = $request->rules_no ?? null;
        //         $rulet->is_append = 1.0;
        //         $rank = $i + $decreasingFactor;
        //         $rulet->rules_rank = $rank;
        //         $rulet->save();

        //         $clickRuleTbl->is_append += 1;
        //         $clickRuleTbl->save();
        //    }


            $nextRank = RuleTable::where('rule_main_id', $rule_main_id)
            ->where('rules_rank', '>', $i)
            ->min('rules_rank');

            if ($nextRank) {
                $rank = ($i + $nextRank) / 2;
            } else {
                // If there's no next rank, add a small value to $i
                $rank = $i + 0.001;
            }

            $rulet = new RuleTable;
            $rulet->rule_main_id = $rule_main_id;
            $rulet->rule_subtypes_id = $rule_subtypes_id;
            $rulet->rules_content = $request->rules_content ?? null;
            $rulet->rules_title = $request->rules_title ?? null;
            $rulet->rules_no = $request->rules_no ?? null;
            $rulet->rules_rank = $rank;
            $rulet->save();

            if ($request->has('sec_footnote_content')) {
                $item = $request->sec_footnote_content;
                $footnote = new MainRuleFootnote();
                $footnote->rules_id = $rulet->rules_id ?? null;
                $footnote->new_rule_id = $new_rule_id ?? null;
                $footnote->footnote_content = $item ?? null;
                $footnote->save();       
            }


            if ($request->has('rule_sub_no')) {
                foreach ($request->rule_sub_no as $key => $item) {
                    $rule_sub_content = $request->rule_sub_content[$key] ?? null;
                    
                        $i = 0;
                        $lastSubRuleRank= RuleSub::max('rule_sub_rank');
                        $lastSubRuleRank = ceil(floatval($lastSubRuleRank));
                        $lastSubRuleRank = max(0, $lastSubRuleRank);
                        $lastSubRuleRank = (int) $lastSubRuleRank;

                        if($lastSubRuleRank){
                           $i = $lastSubRuleRank;
                        }   

                        $subsec = new RuleSub();
                        $subsec->rules_id = $rulet->rules_id ?? null;
                        $subsec->rule_main_id = $rule_main_id ?? null;
                        $subsec->rule_sub_rank = $i + 1;
                        $subsec->rule_subtypes_id = $rule_subtypes_id;
                        $subsec->rule_sub_no = $item ?? null;
                        $subsec->new_rule_id = $new_rule_id ?? null;
                        $subsec->rule_sub_content = $rule_sub_content ?? null;
                        $subsec->save();
            
                        // Handle footnotes for the new sub_section
                        if ($request->has('sub_footnote_content') && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                            foreach ($request->sub_footnote_content[$key] as $kys => $footnote_content) {
                          
                                $footnote = new MainRuleFootnote();
                                $footnote->rule_sub_id = $subsec->rule_sub_id;
                                $footnote->rules_id = $rulet->rules_id;
                                $footnote->new_rule_id = $new_rule_id ?? null;
                                $footnote->footnote_content = $footnote_content ?? null;
                                $footnote->save();
                            }
                        }
                    
                }
            }
            

            return redirect()->route('edit_new_rule', ['id' => $new_rule_id,'page' => $currentPage])->with('success', 'Section updated successfully');
        } catch (\Exception $e) {
            \Log::error('Error updating Act: ' . $e->getMessage());
            return redirect()->route('edit_new_rule', ['id' => $new_rule_id])->withErrors(['error' => 'Failed to update Section. Please try again.' . $e->getMessage()]);
        }
    }
   


    public function delete_new_rule(Request $request,$id){
        try {
            $newRule = NewRule::findOrFail($id);
            $newRule->delete();
            Session::flash('success', 'deleted successfully.');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to delete RuleMain.');
        }
        return redirect()->back()->with('flash_timeout', 10);
    }

    
    public function edit(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
