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
use App\Models\RuleSub;

class MainRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $act = Act::find($id);
      
        return view('admin.MainRule.index', compact('act'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $act = Act::find($id);
        $category = Category::all();
        $states = State::all();
        $mtype = MainTypeRule::all();
        $stype = SubTypeRule::all();
        $parts = PartsType::all();
        $showFormTitle = ($act->act_summary && in_array('6', json_decode($act->act_summary, true)));
        
        return view('admin.MainRule.create', compact('act','category','states','mtype','stype','parts','showFormTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    

        try {

            $act = Act::find($id);
          
            $act->update([
                'category_id' => $request->category_id,
                'state_id' => $request->state_id ?? null,
                'act_title' => $request->act_title,
                'act_content' => $request->act_content ?? null,
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

                if ($maintypeId == "1") {
                    $ruleMain = new RuleMain();
                    $ruleMain->rule_main_rank = $k + 1;
                    $ruleMain->act_id = $act->act_id ?? null;
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
                                $lastSubRank= RuleSub::max('rule_sub_rank');
                                $lastSubRank = ceil(floatval($lastSection));
                                $lastSubRank = max(0, $lastSection);
                                $lastSubRank = (int) $lastSection;

                                if($lastSubRank){
                                   
                                   $i = $lastSubRank;
                                }       
                                $section = RuleSub::create([
                                    'rule_sub_rank' => $i + 1,
                                    'rule_sub_no' => $currentSectionNo,
                                    'act_id' => $act->act_id,
                                    'maintype_id' => $maintypeId,
                                    'chapter_id' => $chapt->chapter_id,
                                    'subtypes_id' => $subtypes_id,
                                    'section_title' => $sectiontitle,
                                    'serial_no' => $lastSerialNo
                                ]);
                            }
                        }
                    } elseif ($request->subtypes_id[$key] == 2) {
                        $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];
                            $lastSection = Article::max('article_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){
                               
                               $i = $lastSection;
                            }


                            $article = Article::create([
                                'article_rank' => $i + 1,
                                'article_no' => $currentArticleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'chapter_id' => $chapt->chapter_id,
                                'subtypes_id' => $subtypes_id,
                                'article_title' => $articletitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 3) {
                        $i = 0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];

                            $lastSection = Rules::max('rule_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }


                            $rule = Rules::create([
                                'rule_rank' => $i + 1,
                                'rule_no' => $currentRuleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'chapter_id' => $chapt->chapter_id,
                                'subtypes_id' => $subtypes_id,
                                'rule_title' => $ruletitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 4) {
                         $i = 0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
                              
                            
                            $lastSection = Regulation::max('regulation_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $regulation = Regulation::create([
                                'regulation_rank' => $i + 1,
                                'regulation_no' => $currentRegulationNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'chapter_id' => $chapt->chapter_id,
                                'subtypes_id' => $subtypes_id,
                                'regulation_title' => $regulationtitle,
                                'serial_no' => $lastSerialNo
                            ]);

                            // $regulationId = $regulation->regulation_id;

                            // $form = Form::create([
                            //     'regulation_id' => $regulationId,
                            //     'act_id' => $act->act_id,
                            //     'form_title' => $request->form_title,
                            // ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 5) {
                        $i = 0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];
                            $lastSection = Lists::max('list_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }


                            $list = Lists::create([
                                'list_rank' => $i + 1,
                                'list_no' => $currentListNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'chapter_id' => $chapt->chapter_id,
                                'subtypes_id' => $subtypes_id,
                                'list_title' => $listtitle,
                                'serial_no' =>$lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 6) {
                             $i = 0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];

                            $lastSection = Part::max('part_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $part = Part::create([
                                'part_rank'=> $i +1,
                                'part_no' => $currentPartNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'chapter_id' => $chapt->chapter_id,
                                'subtypes_id' => $subtypes_id,
                                'part_title' => $parttitle,
                                'serial_no' =>$lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 7) {
                         $i =0; 
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];

                            $lastSection = Appendices::max('appendices_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $appendices = Appendices::create([
                                'appendices_rank' => $i +1,
                                'appendices_no' => $currentAppendicesNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'chapter_id' => $chapt->chapter_id,
                                'subtypes_id' => $subtypes_id,
                                'appendices_title' => $appendicestitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 8) {
                         $i = 0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];
                            $lastSection = Orders::max('order_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $order = Orders::create([
                                'order_rank' => $i +1 ,
                                'order_no' => $currentOrderNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'chapter_id' => $chapt->chapter_id,
                                'subtypes_id' => $subtypes_id,
                                'order_title' => $ordertitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 9) {
                           $i = 0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];
                            $lastSection = Annexure::max('annexure_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $annexure = Annexure::create([
                                'annexure_rank' => $i +1,
                                'annexure_no' => $currentAnnexureNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'chapter_id' => $chapt->chapter_id,
                                'subtypes_id' => $subtypes_id,
                                'annexure_title' => $annexuretitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 10) {
                         $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];

                            $lastSection = Stschedule::max('stschedule_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $stschedule = Stschedule::create([
                                'stschedule_rank'=> $i + 1,
                                'stschedule_no' => $currentStscheduleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'chapter_id' => $chapt->chapter_id,
                                'subtypes_id' => $subtypes_id,
                                'stschedule_title' => $stscheduletitle,
                                'serial_no' =>$lastSerialNo 
                            ]);
                        }
                    }
                } elseif ($maintypeId == "2") {
                    $parts = new Parts();
                    $parts->act_id = $act->act_id ?? null;
                    $parts->maintype_id = $maintypeId;
                    $parts->partstype_id = $request->partstype_id[$key] ?? null;
                    $parts->parts_title = $request->parts_title[$key] ?? null;
                    $parts->serial_no = $lastSerialNo;
                    $parts->save();
                  
                    MainTable::create([
                        'main_rank' =>  $k + 1,
                        'act_id' => $act->act_id,
                        'maintype_id' => $maintypeId,
                        'serial_no' => $lastSerialNo,
                        'parts_id' =>$parts->parts_id
                    ]);
                    if (isset($request->subtypes_id[$key]) && $request->subtypes_id[$key] == 1) {
                        $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];

                                $lastSection = Section::max('section_rank');
                                $lastSection = ceil(floatval($lastSection));
                                $lastSection = max(0, $lastSection);
                                $lastSection = (int) $lastSection;

                                if($lastSection){
                                   
                                   $i = $lastSection;
                                }       
                                $section = Section::create([
                                    'section_rank' => $i + 1,
                                    'section_no' => $currentSectionNo,
                                    'act_id' => $act->act_id,
                                    'maintype_id' => $maintypeId,
                                    'parts_id' => $parts->parts_id,
                                    'subtypes_id' => $subtypes_id,
                                    'section_title' => $sectiontitle,
                                    'serial_no' => $lastSerialNo
                                ]);
                            }
                        }
                    } elseif ($request->subtypes_id[$key] == 2) {
                        $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];
                            $lastSection = Article::max('article_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){
                               
                               $i = $lastSection;
                            }


                            $article = Article::create([
                                'article_rank' => $i +1,
                                'article_no' => $currentArticleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'parts_id' => $parts->parts_id,
                                'subtypes_id' => $subtypes_id,
                                'article_title' => $articletitle,
                                'serial_no'=> $lastSerialNo,
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 3) {
                        $i=0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];
                            $lastSection = Rules::max('rule_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $rule = Rules::create([
                                'rule_rank'=> $i +1,
                                'rule_no' => $currentRuleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'parts_id' => $parts->parts_id,
                                'subtypes_id' => $subtypes_id,
                                'rule_title' => $ruletitle,
                                'serial_no' =>$lastSerialNo,
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 4) {
                         $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
                            $lastSection = Regulation::max('regulation_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $regulation = Regulation::create([
                                'regulation_rank'=> $i +1,
                                'regulation_no' => $currentRegulationNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'parts_id' => $parts->parts_id,
                                'subtypes_id' => $subtypes_id,
                                'regulation_title' => $regulationtitle,
                                'serial_no' => $lastSerialNo
                            ]);

                            // $regulationId = $regulation->regulation_id;

                            // $form = Form::create([
                            //     'regulation_id' => $regulationId,
                            //     'act_id' => $act->act_id,
                            //     'form_title' => $request->form_title,
                            // ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 5) {
                        $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];
                            $lastSection = Lists::max('list_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }


                            $list = Lists::create([
                                'list_rank'=>$i + 1,
                                'list_no' => $currentListNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'parts_id' => $parts->parts_id,
                                'subtypes_id' => $subtypes_id,
                                'list_title' => $listtitle,
                                'serial_no'=>$lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 6) {
                       $i = 0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];
                            $lastSection = Part::max('part_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $part = Part::create([
                                'part_rank' => $i + 1,
                                'part_no' => $currentPartNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'parts_id' => $parts->parts_id,
                                'subtypes_id' => $subtypes_id,
                                'part_title' => $parttitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 7) {
                          $i=0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];
                            $lastSection = Appendices::max('appendices_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $appendices = Appendices::create([
                                'appendices_rank' => $i + 1,
                                'appendices_no' => $currentAppendicesNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'parts_id' => $parts->parts_id,
                                'subtypes_id' => $subtypes_id,
                                'appendices_title' => $appendicestitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 8) {
                          $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];
                            $lastSection = Orders::max('order_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $order = Orders::create([
                                'order_rank' => $i + 1,
                                'order_no' => $currentOrderNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'parts_id' => $parts->parts_id,
                                'subtypes_id' => $subtypes_id,
                                'order_title' => $ordertitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 9) {
                          $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];
                            $lastSection = Annexure::max('annexure_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $annexure = Annexure::create([
                                'annexure_rank' => $i + 1,
                                'annexure_no' => $currentAnnexureNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'parts_id' => $parts->parts_id,
                                'subtypes_id' => $subtypes_id,
                                'annexure_title' => $annexuretitle,
                                'serial_no'=> $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 10) {
                         $i = 0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];
                            $lastSection = Stschedule::max('stschedule_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }


                            $stschedule = Stschedule::create([
                                'stschedule_rank' => $i + 1,
                                'stschedule_no' => $currentStscheduleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'parts_id' => $parts->parts_id,
                                'subtypes_id' => $subtypes_id,
                                'stschedule_title' => $stscheduletitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    }
                } elseif ($maintypeId == "3") {
                    $priliminary = new Priliminary();
                    $priliminary->act_id = $act->act_id ?? null;
                    $priliminary->maintype_id = $maintypeId;
                    $priliminary->priliminary_title = $request->priliminary_title[$key] ?? null;
                    $priliminary->serial_no = $lastSerialNo;
                    $priliminary->save();

                    MainTable::create([
                        'main_rank' =>  $k + 1,
                        'act_id' => $act->act_id,
                        'maintype_id' => $maintypeId,
                        'serial_no' => $lastSerialNo,
                        'priliminary_id' =>$priliminary->priliminary_id 
                    ]);

                    if (isset($request->subtypes_id[$key]) && $request->subtypes_id[$key] == 1) {
                        $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];
                                $lastSection = Section::max('section_rank');
                                $lastSection = ceil(floatval($lastSection));
                                $lastSection = max(0, $lastSection);
                                $lastSection = (int) $lastSection;

                                if($lastSection){
                                   
                                   $i = $lastSection;
                                }     
                                $section = Section::create([
                                    'section_rank' => $i + 1,
                                    'section_no' => $currentSectionNo,
                                    'act_id' => $act->act_id,
                                    'maintype_id' => $maintypeId,
                                    'priliminary_id' => $priliminary->priliminary_id,
                                    'subtypes_id' => $subtypes_id,
                                    'section_title' => $sectiontitle,
                                    'serial_no' => $lastSerialNo
                                ]);
                            }
                        }
                    } elseif ($request->subtypes_id[$key] == 2) {
                        $i= 0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];
                            $lastSection = Article::max('article_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){
                               
                               $i = $lastSection;
                            }
                            $article = Article::create([
                                'article_rank' => $i + 1,
                                'article_no' => $currentArticleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'priliminary_id' => $priliminary->priliminary_id,
                                'subtypes_id' => $subtypes_id,
                                'article_title' => $articletitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 3) {
                        $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];

                            $lastSection = Rules::max('rule_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $rule = Rules::create([
                                'rule_rank'=> $i + 1,
                                'rule_no' => $currentRuleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'priliminary_id' => $priliminary->priliminary_id,
                                'subtypes_id' => $subtypes_id,
                                'rule_title' => $ruletitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 4) {
                         $i = 0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
   
                            $lastSection = Regulation::max('regulation_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $regulation = Regulation::create([
                                'regulation_rank' => $i + 1,
                                'regulation_no' => $currentRegulationNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'priliminary_id' => $priliminary->priliminary_id,
                                'subtypes_id' => $subtypes_id,
                                'regulation_title' => $regulationtitle,
                                'serial_no' => $lastSerialNo
                            ]);

                            // $regulationId = $regulation->regulation_id;

                            // $form = Form::create([
                            //     'regulation_id' => $regulationId,
                            //     'act_id' => $act->act_id,
                            //     'form_title' => $request->form_title,
                            // ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 5) {
                       $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];
                            $lastSection = Lists::max('list_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }


                            $list = Lists::create([
                                'list_rank'=> $i + 1,
                                'list_no' => $currentListNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'priliminary_id' => $priliminary->priliminary_id,
                                'subtypes_id' => $subtypes_id,
                                'list_title' => $listtitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 6) {
                        $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];

                            $lastSection = Part::max('part_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }
                            $part = Part::create([
                                'part_rank' => $i + 1,
                                'part_no' => $currentPartNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'priliminary_id' => $priliminary->priliminary_id,
                                'subtypes_id' => $subtypes_id,
                                'part_title' => $parttitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 7) {
                            $i = 0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];

                            $lastSection = Appendices::max('appendices_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }
                            $appendices = Appendices::create([
                                'appendices_rank' => $i + 1,
                                'appendices_no' => $currentAppendicesNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'priliminary_id' => $priliminary->priliminary_id,
                                'subtypes_id' => $subtypes_id,
                                'appendices_title' => $appendicestitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 8) {
                          $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];
                            $lastSection = Orders::max('order_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }


                            $order = Orders::create([
                                'order_rank' => $i + 1,
                                'order_no' => $currentOrderNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'priliminary_id' => $priliminary->priliminary_id,
                                'subtypes_id' => $subtypes_id,
                                'order_title' => $ordertitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 9) {
                        $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];

                            $lastSection = Annexure::max('annexure_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }
                            $annexure = Annexure::create([
                                'annexure_rank' => $i + 1,
                                'annexure_no' => $currentAnnexureNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'priliminary_id' => $priliminary->priliminary_id,
                                'subtypes_id' => $subtypes_id,
                                'annexure_title' => $annexuretitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    }elseif ($request->subtypes_id[$key] == 10) {
                        $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];
                            $lastSection = Stschedule::max('stschedule_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $stschedule = Stschedule::create([
                                'stschedule_rank' => $i + 1,
                                'stschedule_no' => $currentStscheduleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'priliminary_id' => $priliminary->priliminary_id,
                                'subtypes_id' => $subtypes_id,
                                'stschedule_title' => $stscheduletitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    }
                } elseif ($maintypeId == "4") {
                    $schedule = new Schedule();
                    $schedule->act_id = $act->act_id ?? null;
                    $schedule->maintype_id = $maintypeId;
                    $schedule->schedule_title = $request->schedule_title[$key] ?? null;
                    $schedule->serial_no = $lastSerialNo;
                    $schedule->save();

                    MainTable::create([
                        'main_rank' =>  $k + 1,
                        'act_id' => $act->act_id,
                        'maintype_id' => $maintypeId,
                        'serial_no' => $lastSerialNo,
                        'schedule_id' =>$schedule->schedule_id
                    ]);

                    if (isset($request->subtypes_id[$key]) && $request->subtypes_id[$key] == 1) {
                        $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];

                                $lastSection = Section::max('section_rank');
                                $lastSection = ceil(floatval($lastSection));
                                $lastSection = max(0, $lastSection);
                                $lastSection = (int) $lastSection;

                                if($lastSection){
                                   
                                   $i = $lastSection;
                                }       
                                $section = Section::create([
                                    'section_rank' => $i + 1,
                                    'section_no' => $currentSectionNo,
                                    'act_id' => $act->act_id,
                                    'maintype_id' => $maintypeId,
                                    'schedule_id' => $schedule->schedule_id,
                                    'subtypes_id' => $subtypes_id,
                                    'section_title' => $sectiontitle,
                                    'serial_no' => $lastSerialNo
                                ]);
                            }
                        }
                    } elseif ($request->subtypes_id[$key] == 2) {
                        $i = 0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];
                            $lastSection = Article::max('article_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){
                               
                               $i = $lastSection;
                            }

                            $article = Article::create([
                                'article_rank' => $i + 1,
                                'article_no' => $currentArticleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'schedule_id' => $schedule->schedule_id,
                                'subtypes_id' => $subtypes_id,
                                'article_title' => $articletitle,
                                'serial_no'=> $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 3) {
                        $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];
                            $lastSection = Rules::max('rule_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $rule = Rules::create([
                                'rule_rank' => $i + 1,
                                'rule_no' => $currentRuleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'schedule_id' => $schedule->schedule_id,
                                'subtypes_id' => $subtypes_id,
                                'rule_title' => $ruletitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 4) {
                       $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
                            $lastSection = Regulation::max('regulation_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }


                            $regulation = Regulation::create([
                                'regulation_rank' => $i + 1,
                                'regulation_no' => $currentRegulationNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'schedule_id' => $schedule->schedule_id,
                                'subtypes_id' => $subtypes_id,
                                'regulation_title' => $regulationtitle,
                                'serial_no' => $lastSerialNo,
                            ]);

                            // $regulationId = $regulation->regulation_id;

                            // $form = Form::create([
                            //     'regulation_id' => $regulationId,
                            //     'act_id' => $act->act_id,
                            //     'form_title' => $request->form_title,
                            // ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 5) {
                        $i = 0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];
                            $lastSection = Lists::max('list_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }


                            $list = Lists::create([
                                'list_rank' => $i + 1,
                                'list_no' => $currentListNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'schedule_id' => $schedule->schedule_id,
                                'subtypes_id' => $subtypes_id,
                                'list_title' => $listtitle,
                                'serial_no' => $lastSerialNo,
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 6) {
                        $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];
                            $lastSection = Part::max('part_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $part = Part::create([
                                'part_rank' => $i + 1,
                                'part_no' => $currentPartNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'schedule_id' => $schedule->schedule_id,
                                'subtypes_id' => $subtypes_id,
                                'part_title' => $parttitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 7) {
                           $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];

                            
                            $lastSection = Appendices::max('appendices_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }
                            $appendices = Appendices::create([
                                'appendices_rank'=> $i + 1,
                                'appendices_no' => $currentAppendicesNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'schedule_id' => $schedule->schedule_id,
                                'subtypes_id' => $subtypes_id,
                                'appendices_title' => $appendicestitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 8) {
                        $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];
                            $lastSection = Orders::max('order_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $order = Orders::create([
                                'order_rank' => $i + 1,
                                'order_no' => $currentOrderNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'schedule_id' => $schedule->schedule_id,
                                'subtypes_id' => $subtypes_id,
                                'order_title' => $ordertitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 9) {
                       $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];
                            $lastSection = Annexure::max('annexure_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $annexure = Annexure::create([
                                'annexure_rank' => $i + 1,
                                'annexure_no' => $currentAnnexureNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'schedule_id' => $schedule->schedule_id,
                                'subtypes_id' => $subtypes_id,
                                'annexure_title' => $annexuretitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    }elseif ($request->subtypes_id[$key] == 10) {
                          $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];
                            $lastSection = Stschedule::max('stschedule_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }


                            $stschedule = Stschedule::create([
                                'stschedule_rank' => $i + 1,
                                'stschedule_no' => $currentStscheduleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'schedule_id' => $schedule->schedule_id,
                                'subtypes_id' => $subtypes_id,
                                'stschedule_title' => $stscheduletitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    }
                } elseif ($maintypeId == "5") {
                    $appendix = new Appendix();
                    $appendix->act_id = $act->act_id ?? null;
                    $appendix->maintype_id = $maintypeId;
                    $appendix->appendix_title = $request->appendix_title[$key] ?? null;
                    $appendix->serial_no = $lastSerialNo;
                    $appendix->save();

                    MainTable::create([
                        'main_rank' =>  $k + 1,
                        'act_id' => $act->act_id,
                        'maintype_id' => $maintypeId,
                        'serial_no' => $lastSerialNo,
                        'appendix_id' =>$appendix->appendix_id
                    ]);

                    if (isset($request->subtypes_id[$key]) && $request->subtypes_id[$key] == 1) {
                        $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];

                                $lastSection = Section::max('section_rank');
                                $lastSection = ceil(floatval($lastSection));
                                $lastSection = max(0, $lastSection);
                                $lastSection = (int) $lastSection;

                                if($lastSection){   
                                   $i = $lastSection;
                                }     
                                $section = Section::create([
                                    'section_rank' => $i + 1,
                                    'section_no' => $currentSectionNo,
                                    'act_id' => $act->act_id,
                                    'maintype_id' => $maintypeId,
                                    'appendix_id' => $appendix->appendix_id,
                                    'subtypes_id' => $subtypes_id,
                                    'section_title' => $sectiontitle,
                                    'serial_no' => $lastSerialNo,
                                ]);
                            }
                        }
                    } elseif ($request->subtypes_id[$key] == 2) {
                        $i = 0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];
                            $lastSection = Article::max('article_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){
                               
                               $i = $lastSection;
                            }

                            $article = Article::create([
                                'article_rank' => $i + 1,
                                'article_no' => $currentArticleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'appendix_id' => $appendix->appendix_id,
                                'subtypes_id' => $subtypes_id,
                                'article_title' => $articletitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 3) {
                        $i=0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];
                            $lastSection = Rules::max('rule_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $rule = Rules::create([
                                'rule_rank' => $i + 1,
                                'rule_no' => $currentRuleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'appendix_id' => $appendix->appendix_id,
                                'subtypes_id' => $subtypes_id,
                                'rule_title' => $ruletitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 4) {
                           $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
                            $lastSection = Regulation::max('regulation_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $regulation = Regulation::create([
                                'regulation_rank' => $i + 1,
                                'regulation_no' => $currentRegulationNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'appendix_id' => $appendix->appendix_id,
                                'subtypes_id' => $subtypes_id,
                                'regulation_title' => $regulationtitle,
                                'serial_no' => $lastSerialNo
                            ]);

                            // $regulationId = $regulation->regulation_id;

                            // $form = Form::create([
                            //     'regulation_id' => $regulationId,
                            //     'act_id' => $act->act_id,
                            //     'form_title' => $request->form_title,
                            // ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 5) {
                        $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];
                            $lastSection = Lists::max('list_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }


                            $list = Lists::create([
                                'list_rank' => $i + 1,
                                'list_no' => $currentListNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'appendix_id' => $appendix->appendix_id,
                                'subtypes_id' => $subtypes_id,
                                'list_title' => $listtitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 6) {
                       $i = 0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];
                            $lastSection = Part::max('part_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }


                            $part = Part::create([
                                'part_rank' => $i + 1,
                                'part_no' => $currentPartNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'appendix_id' => $appendix->appendix_id,
                                'subtypes_id' => $subtypes_id,
                                'part_title' => $parttitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 7) {
                          $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];
                            $lastSection = Appendices::max('appendices_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $appendices = Appendices::create([
                                'appendices_rank' => $i + 1,
                                'appendices_no' => $currentAppendicesNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'appendix_id' => $appendix->appendix_id,
                                'subtypes_id' => $subtypes_id,
                                'appendices_title' => $appendicestitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 8) {
                          $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];
                            $lastSection = Orders::max('order_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }


                            $order = Orders::create([
                                'order_rank' => $i + 1,
                                'order_no' => $currentOrderNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'appendix_id' => $appendix->appendix_id,
                                'subtypes_id' => $subtypes_id,
                                'order_title' => $ordertitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 9) {
                        $i = 0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];

                            $lastSection = Annexure::max('annexure_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }
                            $annexure = Annexure::create([
                                'annexure_rank' => $i + 1,
                                'annexure_no' => $currentAnnexureNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'appendix_id' => $appendix->appendix_id,
                                'subtypes_id' => $subtypes_id,
                                'annexure_title' => $annexuretitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    }elseif ($request->subtypes_id[$key] == 10) {
                        $i = 0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];
                            $lastSection = Stschedule::max('stschedule_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $stschedule = Stschedule::create([
                                'stschedule_rank' => $i + 1,
                                'stschedule_no' => $currentStscheduleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'appendix_id' => $appendix->appendix_id,
                                'subtypes_id' => $subtypes_id,
                                'stschedule_title' => $stscheduletitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    }
                } elseif ($maintypeId == "6") {
                    $main_order = new MainOrder();
                    $main_order->act_id = $act->act_id ?? null;
                    $main_order->maintype_id = $maintypeId;
                    $main_order->main_order_title = $request->main_order_title[$key] ?? null;
                    $main_order->serial_no = $lastSerialNo;
                    $main_order->save();

                    MainTable::create([
                        'main_rank' =>  $k + 1,
                        'act_id' => $act->act_id,
                        'maintype_id' => $maintypeId,
                        'serial_no' => $lastSerialNo,
                        'main_order_id' =>$main_order->main_order_id
                    ]);

                    if (isset($request->subtypes_id[$key]) && $request->subtypes_id[$key] == 1) {
                        $subtypes_id = $request->subtypes_id[$key] ?? null;

                        $i = 0;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];

                                $lastSection = Section::max('section_rank');
                                $lastSection = ceil(floatval($lastSection));
                                $lastSection = max(0, $lastSection);
                                $lastSection = (int) $lastSection;

                                if($lastSection){
                                   
                                   $i = $lastSection;
                                }       
                                $section = Section::create([
                                    'section_rank' => $i + 1,
                                    'section_no' => $currentSectionNo,
                                    'act_id' => $act->act_id,
                                    'maintype_id' => $maintypeId,
                                    'main_order_id' => $main_order->main_order_id,
                                    'subtypes_id' => $subtypes_id,
                                    'section_title' => $sectiontitle,
                                    'serial_no' => $lastSerialNo
                                ]);
                            }
                        }
                    } elseif ($request->subtypes_id[$key] == 2) {
                        $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];
                            $lastSection = Article::max('article_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){
                               
                               $i = $lastSection;
                            }


                            $article = Article::create([
                                'article_rank' => $i + 1,
                                'article_no' => $currentArticleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'main_order_id' => $main_order->main_order_id,
                                'subtypes_id' => $subtypes_id,
                                'article_title' => $articletitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 3) {
                        $i = 0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];

                            $lastSection = Rules::max('rule_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }


                            $rule = Rules::create([
                                'rule_rank' => $i + 1,
                                'rule_no' => $currentRuleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'main_order_id' => $main_order->main_order_id,
                                'subtypes_id' => $subtypes_id,
                                'rule_title' => $ruletitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 4) {
                         $i = 0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
                              
                            
                            $lastSection = Regulation::max('regulation_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $regulation = Regulation::create([
                                'regulation_rank' => $i + 1,
                                'regulation_no' => $currentRegulationNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'main_order_id' => $main_order->main_order_id,
                                'subtypes_id' => $subtypes_id,
                                'regulation_title' => $regulationtitle,
                                'serial_no' => $lastSerialNo
                            ]);

                            // $regulationId = $regulation->regulation_id;

                            // $form = Form::create([
                            //     'regulation_id' => $regulationId,
                            //     'act_id' => $act->act_id,
                            //     'form_title' => $request->form_title,
                            // ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 5) {
                        $i = 0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];
                            $lastSection = Lists::max('list_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }


                            $list = Lists::create([
                                'list_rank' => $i + 1,
                                'list_no' => $currentListNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'main_order_id' => $main_order->main_order_id,
                                'subtypes_id' => $subtypes_id,
                                'list_title' => $listtitle,
                                'serial_no' =>$lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 6) {
                             $i = 0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];

                            $lastSection = Part::max('part_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $part = Part::create([
                                'part_rank'=> $i +1,
                                'part_no' => $currentPartNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'main_order_id' => $main_order->main_order_id,
                                'subtypes_id' => $subtypes_id,
                                'part_title' => $parttitle,
                                'serial_no' =>$lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 7) {
                         $i =0; 
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];

                            $lastSection = Appendices::max('appendices_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $appendices = Appendices::create([
                                'appendices_rank' => $i +1,
                                'appendices_no' => $currentAppendicesNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'main_order_id' => $main_order->main_order_id,
                                'subtypes_id' => $subtypes_id,
                                'appendices_title' => $appendicestitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 8) {
                         $i = 0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];
                            $lastSection = Orders::max('order_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $order = Orders::create([
                                'order_rank' => $i +1 ,
                                'order_no' => $currentOrderNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'main_order_id' => $main_order->main_order_id,
                                'subtypes_id' => $subtypes_id,
                                'order_title' => $ordertitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 9) {
                           $i = 0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];
                            $lastSection = Annexure::max('annexure_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $annexure = Annexure::create([
                                'annexure_rank' => $i +1,
                                'annexure_no' => $currentAnnexureNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'main_order_id' => $main_order->main_order_id,
                                'subtypes_id' => $subtypes_id,
                                'annexure_title' => $annexuretitle,
                                'serial_no' => $lastSerialNo
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 10) {
                         $i =0;
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];

                            $lastSection = Stschedule::max('stschedule_rank');
                            $lastSection = ceil(floatval($lastSection));
                            $lastSection = max(0, $lastSection);
                            $lastSection = (int) $lastSection;

                            if($lastSection){ 
                               $i = $lastSection;
                            }

                            $stschedule = Stschedule::create([
                                'stschedule_rank'=> $i + 1,
                                'stschedule_no' => $currentStscheduleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'main_order_id' => $main_order->main_order_id,
                                'subtypes_id' => $subtypes_id,
                                'stschedule_title' => $stscheduletitle,
                                'serial_no' =>$lastSerialNo 
                            ]);
                        }
                    }
                }
                
                
                else {
                    dd("something went wrong - right now we are working only in chapter and parts");
                }
            }

            return redirect()->route('get_act_section', ['id' => $id])->with('success', 'Index added successfully');

        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
