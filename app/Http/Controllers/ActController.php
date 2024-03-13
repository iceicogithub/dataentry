<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\ActSummary;
use App\Models\Annexure;
use App\Models\Appendices;
use App\Models\Appendix;
use App\Models\Article;
use App\Models\Category;
use App\Models\Lists;
use App\Models\MainOrder;
use App\Models\MainType;
use App\Models\Orders;
use App\Models\Part;
use App\Models\Parts;
use App\Models\PartsType;
use App\Models\Stschedule;
use App\Models\SubAnnexure;
use App\Models\SubAppendices;
use App\Models\SubArticle;
use App\Models\SubLists;
use App\Models\SubOrders;
use App\Models\SubPart;
use App\Models\SubRegulation;
use App\Models\SubSection;
use App\Models\Footnote;
use App\Models\Chapter;
use App\Models\Form;
use App\Models\Priliminary;
use App\Models\Regulation;
use App\Models\Rules;
use App\Models\Schedule;
use App\Models\Section;
use App\Models\State;
use App\Models\Status;
use App\Models\SubRules;
use App\Models\SubStschedule;
use App\Models\SubType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ActController extends Controller
{

    public function index()
    {
        $act = Act::with('CategoryModel')->get();

        return view('admin.act.index', compact('act'));
    }

    public function get_act_section(Request $request, $id)
    {
        $act_id = $id;
        $act = Act::where('act_id', $act_id)->first();
        if ($act) {
            $act_footnote_titles = json_decode($act->act_footnote_title, true);
            $act_footnote_descriptions = json_decode($act->act_footnote_description, true);
        }



        $act_section = Section::where('act_id', $id)
        ->with('MainTypeModel', 'Schedulemodel', 'Appendixmodel', 'Partmodel', 'ChapterModel', 'PriliminaryModel','MainOrderModel')
        ->get()
        ->sortBy(function ($section) {
            // Sorting conditions
                return [floatval($section->section_rank)];
        });



       
        $act_article = Article::where('act_id', $id)
            ->with('MainTypeModel', 'Schedulemodel', 'Appendixmodel', 'Partmodel', 'ChapterModel', 'PriliminaryModel','MainOrderModel')
            ->get()
            ->sortBy(function ($article) {
                // Sorting conditions
                    return [floatval($article->article_rank)];
            });

        $act_rule = Rules::where('act_id', $id)
            ->with('MainTypeModel', 'Schedulemodel', 'Appendixmodel', 'Partmodel', 'ChapterModel', 'PriliminaryModel','MainOrderModel')
            ->get()
            ->sortBy(function ($rule) {
                // Sorting conditions
                    return [floatval($rule->rule_rank)];
            });

            $mergedCollection = collect([$act_section, $act_article, $act_rule])->flatten(1)->sortBy('serial_no');

        $act_regulation = Regulation::where('act_id', $id)
            ->with('MainTypeModel', 'Schedulemodel', 'Appendixmodel', 'Partmodel', 'ChapterModel', 'PriliminaryModel','MainOrderModel')
            ->get()
            ->sortBy(function ($regulation) {
                $mixstring = $regulation->regulation_no;

                preg_match_all('/(\d+)|([a-zA-Z]+)/', $mixstring, $matches);
                $numericPart = isset($matches[1][0]) ? str_pad($matches[1][0], 10, '0', STR_PAD_LEFT) : '';
                $alphabeticPart = isset($matches[2][0]) ? strtolower($matches[2][0]) : '';

                return $numericPart . $alphabeticPart;
            }, SORT_NATURAL);

        $act_list = Lists::where('act_id', $id)
            ->with('MainTypeModel', 'Schedulemodel', 'Appendixmodel', 'Partmodel', 'ChapterModel', 'PriliminaryModel','MainOrderModel')
            ->get()
            ->sortBy(function ($list) {
                $mixstring = $list->list_no;

                preg_match_all('/(\d+)|([a-zA-Z]+)/', $mixstring, $matches);
                $numericPart = isset($matches[1][0]) ? str_pad($matches[1][0], 10, '0', STR_PAD_LEFT) : '';
                $alphabeticPart = isset($matches[2][0]) ? strtolower($matches[2][0]) : '';

                return $numericPart . $alphabeticPart;
            }, SORT_NATURAL);

        $act_part = Part::where('act_id', $id)
            ->with('MainTypeModel', 'Schedulemodel', 'Appendixmodel', 'Partmodel', 'ChapterModel', 'PriliminaryModel','MainOrderModel')
            ->get()
            ->sortBy(function ($part) {
                $mixstring = $part->part_no;

                preg_match_all('/(\d+)|([a-zA-Z]+)/', $mixstring, $matches);
                $numericPart = isset($matches[1][0]) ? str_pad($matches[1][0], 10, '0', STR_PAD_LEFT) : '';
                $alphabeticPart = isset($matches[2][0]) ? strtolower($matches[2][0]) : '';

                return $numericPart . $alphabeticPart;
            }, SORT_NATURAL);

        $act_appendices = Appendices::where('act_id', $id)
            ->with('MainTypeModel', 'Schedulemodel', 'Appendixmodel', 'Partmodel', 'ChapterModel', 'PriliminaryModel','MainOrderModel')
            ->get()
            ->sortBy(function ($appendices) {
                $mixstring = $appendices->appendices_no;

                preg_match_all('/(\d+)|([a-zA-Z]+)/', $mixstring, $matches);
                $numericPart = isset($matches[1][0]) ? str_pad($matches[1][0], 10, '0', STR_PAD_LEFT) : '';
                $alphabeticPart = isset($matches[2][0]) ? strtolower($matches[2][0]) : '';

                return $numericPart . $alphabeticPart;
            }, SORT_NATURAL);

        $act_order = Orders::where('act_id', $id)
            ->with('MainTypeModel', 'Schedulemodel', 'Appendixmodel', 'Partmodel', 'ChapterModel', 'PriliminaryModel','MainOrderModel')
            ->get()
            ->sortBy(function ($order) {
                $mixstring = $order->order_no;
                preg_match_all('/(\d+)|([a-zA-Z]+)/', $mixstring, $matches);
                $numericPart = isset($matches[1][0]) ? str_pad($matches[1][0], 10, '0', STR_PAD_LEFT) : '';
                $alphabeticPart = isset($matches[2][0]) ? strtolower($matches[2][0]) : '';

                return $numericPart . $alphabeticPart;
            }, SORT_NATURAL);

        $act_annexure = Annexure::where('act_id', $id)
            ->with('MainTypeModel', 'Schedulemodel', 'Appendixmodel', 'Partmodel', 'ChapterModel', 'PriliminaryModel','MainOrderModel')
            ->get()
            ->sortBy(function ($annexure) {
                $mixstring = $annexure->annexure_no;

                preg_match_all('/(\d+)|([a-zA-Z]+)/', $mixstring, $matches);
                $numericPart = isset($matches[1][0]) ? str_pad($matches[1][0], 10, '0', STR_PAD_LEFT) : '';
                $alphabeticPart = isset($matches[2][0]) ? strtolower($matches[2][0]) : '';

                return $numericPart . $alphabeticPart;
            }, SORT_NATURAL);

        $act_stschedule = Stschedule::where('act_id', $id)
            ->with('MainTypeModel', 'Schedulemodel', 'Appendixmodel', 'Partmodel', 'ChapterModel', 'PriliminaryModel','MainOrderModel')
            ->get()
            ->sortBy(function ($stschedule) {
                $mixstring = $stschedule->stschedule_no;

                preg_match_all('/(\d+)|([a-zA-Z]+)/', $mixstring, $matches);
                $numericPart = isset($matches[1][0]) ? str_pad($matches[1][0], 10, '0', STR_PAD_LEFT) : '';
                $alphabeticPart = isset($matches[2][0]) ? strtolower($matches[2][0]) : '';

                return $numericPart . $alphabeticPart;
            }, SORT_NATURAL);

            // dd($act_stschedule);
            // die();

        return view('admin.section.index', compact('mergedCollection','act_section', 'act_id', 'act', 'act_footnote_titles', 'act_footnote_descriptions', 'act_rule', 'act_article', 'act_regulation', 'act_list', 'act_part', 'act_appendices', 'act_stschedule', 'act_order', 'act_annexure'));
    }

    public function create(Request $request, $id)
    {
        $category = Category::all();
        $status = Status::all();
        $states = State::all();
        $mtype = MainType::all();
        $stype = SubType::all();
        $parts = PartsType::all();

        $act = Act::where('act_id', $id)->first();
        $showFormTitle = ($act->act_summary && in_array('6', json_decode($act->act_summary, true)));
        // dd($showFormTitle);
        // die();

        return view('admin.act.create', compact('category', 'status', 'states', 'mtype', 'parts', 'stype', 'act', 'showFormTitle'));
    }

    public function store(Request $request, $id)
    {

        // dd($request);
        // die();

        try {

            $act = Act::find($id);
          
            $act->update([
                'category_id' => $request->category_id,
                'state_id' => $request->state_id ?? null,
                'act_title' => $request->act_title,
                'act_content' => $request->act_content ?? null,
            ]);

           $sectionSerialNo = Section::where('act_id',$id)->pluck('serial_no')->last();
           $articleSerialNo = Article::where('act_id',$id)->pluck('serial_no')->last();
           $ruleSerialNo = Rules::where('act_id',$id)->pluck('serial_no')->last();
           $regulationSerialNo = Regulation::where('act_id',$id)->pluck('serial_no')->last();
           $listSerialNo = Lists::where('act_id',$id)->pluck('serial_no')->last();
           $partSerialNo = Part::where('act_id',$id)->pluck('serial_no')->last();
           $appendicesSerialNo = Appendices::where('act_id',$id)->pluck('serial_no')->last();
           $orderSerialNo = Orders::where('act_id',$id)->pluck('serial_no')->last();
           $annexureSerialNo = Annexure::where('act_id',$id)->pluck('serial_no')->last();
           $stscheduleSerialNo = Stschedule::where('act_id',$id)->pluck('serial_no')->last();
           $lastSerialNo = max(0, $sectionSerialNo, $articleSerialNo, $ruleSerialNo,$regulationSerialNo,$listSerialNo,$partSerialNo,$appendicesSerialNo,$orderSerialNo,$annexureSerialNo,$stscheduleSerialNo);
          
           

            foreach ($request->maintype_id as $key => $maintypeId) {
                $lastSerialNo++;
                if ($maintypeId == "1") {
                    $chapt = new Chapter();
                    $chapt->act_id = $act->act_id ?? null;
                    $chapt->maintype_id = $maintypeId;
                    $chapt->chapter_title = $request->chapter_title[$key] ?? null;
                    $chapt->save();

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
                                $lastSection = max(1, $lastSection);
                                $lastSection = (int) $lastSection;

                                if($lastSection){
                                   
                                   $i = $lastSection;
                                }       
                                $section = Section::create([
                                    'section_rank' => $i + 1,
                                    'section_no' => $currentSectionNo,
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
                            $lastSection = max(1, $lastSection);
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
                            $lastSection = max(1, $lastSection);
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
                            $lastSection = max(1, $lastSection);
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
                            $lastSection = max(1, $lastSection);
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
                            $lastSection = max(1, $lastSection);
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
                            $lastSection = max(1, $lastSection);
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
                            $lastSection = max(1, $lastSection);
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
                            $lastSection = max(1, $lastSection);
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
                            $lastSection = max(1, $lastSection);
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
                    $parts->save();
                  
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
                                $lastSection = max(1, $lastSection);
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
                            $lastSection = max(1, $lastSection);
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
                            $lastSection = max(1, $lastSection);
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
                            $lastSection = max(1, $lastSection);
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
                            $lastSection = max(1, $lastSection);
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

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];


                            $part = Part::create([
                                'part_no' => $currentPartNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'parts_id' => $parts->parts_id,
                                'subtypes_id' => $subtypes_id,
                                'part_title' => $parttitle,
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 7) {

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];


                            $appendices = Appendices::create([
                                'appendices_no' => $currentAppendicesNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'parts_id' => $parts->parts_id,
                                'subtypes_id' => $subtypes_id,
                                'appendices_title' => $appendicestitle,
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 8) {

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];


                            $order = Orders::create([
                                'order_no' => $currentOrderNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'parts_id' => $parts->parts_id,
                                'subtypes_id' => $subtypes_id,
                                'order_title' => $ordertitle,
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 9) {

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];


                            $annexure = Annexure::create([
                                'annexure_no' => $currentAnnexureNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'parts_id' => $parts->parts_id,
                                'subtypes_id' => $subtypes_id,
                                'annexure_title' => $annexuretitle,
                            ]);
                        }
                    }
                     elseif ($request->subtypes_id[$key] == 10) {

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];


                            $stschedule = Stschedule::create([
                                'stschedule_no' => $currentStscheduleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'parts_id' => $parts->parts_id,
                                'subtypes_id' => $subtypes_id,
                                'stschedule_title' => $stscheduletitle,
                            ]);
                        }
                    }
                } elseif ($maintypeId == "3") {
                    $priliminary = new Priliminary();
                    $priliminary->act_id = $act->act_id ?? null;
                    $priliminary->maintype_id = $maintypeId;
                    $priliminary->priliminary_title = $request->priliminary_title[$key] ?? null;
                    $priliminary->save();

                    if (isset($request->subtypes_id[$key]) && $request->subtypes_id[$key] == 1) {
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];

                                // Update SubSection records, Footnote records, etc. (similar to Section)
                                $lastSection = Section::orderBy('section_rank', 'desc')->first();

                                $lastRank = $lastSection ? $lastSection->section_rank : 0;
                                // Create the new section with the updated section_no
                                $section = Section::create([
                                    'section_rank' => $lastRank + 1,
                                    'section_no' => $currentSectionNo,
                                    'act_id' => $act->act_id,
                                    'maintype_id' => $maintypeId,
                                    'priliminary_id' => $priliminary->priliminary_id,
                                    'subtypes_id' => $subtypes_id,
                                    'section_title' => $sectiontitle,
                                ]);
                            }
                        }
                    } elseif ($request->subtypes_id[$key] == 2) {
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];

                            $article = Article::create([
                                'article_no' => $currentArticleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'priliminary_id' => $priliminary->priliminary_id,
                                'subtypes_id' => $subtypes_id,
                                'article_title' => $articletitle,
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 3) {
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];

                            $rule = Rules::create([
                                'rule_no' => $currentRuleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'priliminary_id' => $priliminary->priliminary_id,
                                'subtypes_id' => $subtypes_id,
                                'rule_title' => $ruletitle,
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 4) {

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];


                            $regulation = Regulation::create([
                                'regulation_no' => $currentRegulationNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'priliminary_id' => $priliminary->priliminary_id,
                                'subtypes_id' => $subtypes_id,
                                'regulation_title' => $regulationtitle,
                            ]);

                            // $regulationId = $regulation->regulation_id;

                            // $form = Form::create([
                            //     'regulation_id' => $regulationId,
                            //     'act_id' => $act->act_id,
                            //     'form_title' => $request->form_title,
                            // ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 5) {

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];


                            $list = Lists::create([
                                'list_no' => $currentListNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'priliminary_id' => $priliminary->priliminary_id,
                                'subtypes_id' => $subtypes_id,
                                'list_title' => $listtitle,
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 6) {

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];


                            $part = Part::create([
                                'part_no' => $currentPartNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'priliminary_id' => $priliminary->priliminary_id,
                                'subtypes_id' => $subtypes_id,
                                'part_title' => $parttitle,
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 7) {

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];


                            $appendices = Appendices::create([
                                'appendices_no' => $currentAppendicesNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'priliminary_id' => $priliminary->priliminary_id,
                                'subtypes_id' => $subtypes_id,
                                'appendices_title' => $appendicestitle,
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 8) {

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];


                            $order = Orders::create([
                                'order_no' => $currentOrderNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'priliminary_id' => $priliminary->priliminary_id,
                                'subtypes_id' => $subtypes_id,
                                'order_title' => $ordertitle,
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 9) {

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];


                            $annexure = Annexure::create([
                                'annexure_no' => $currentAnnexureNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'priliminary_id' => $priliminary->priliminary_id,
                                'subtypes_id' => $subtypes_id,
                                'annexure_title' => $annexuretitle,
                            ]);
                        }
                    }
                     elseif ($request->subtypes_id[$key] == 10) {

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];


                            $stschedule = Stschedule::create([
                                'stschedule_no' => $currentStscheduleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'priliminary_id' => $priliminary->priliminary_id,
                                'subtypes_id' => $subtypes_id,
                                'stschedule_title' => $stscheduletitle,
                            ]);
                        }
                    }
                } elseif ($maintypeId == "4") {
                    $schedule = new Schedule();
                    $schedule->act_id = $act->act_id ?? null;
                    $schedule->maintype_id = $maintypeId;
                    $schedule->schedule_title = $request->schedule_title[$key] ?? null;
                    $schedule->save();

                    if (isset($request->subtypes_id[$key]) && $request->subtypes_id[$key] == 1) {
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];

                                // Update SubSection records, Footnote records, etc. (similar to Section)
                                $lastSection = Section::orderBy('section_rank', 'desc')->first();

                                $lastRank = $lastSection ? $lastSection->section_rank : 0;
                                // Create the new section with the updated section_no
                                $section = Section::create([
                                    'section_rank' => $lastRank + 1,
                                    'section_no' => $currentSectionNo,
                                    'act_id' => $act->act_id,
                                    'maintype_id' => $maintypeId,
                                    'schedule_id' => $schedule->schedule_id,
                                    'subtypes_id' => $subtypes_id,
                                    'section_title' => $sectiontitle,
                                ]);
                            }
                        }
                    } elseif ($request->subtypes_id[$key] == 2) {
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];

                            $article = Article::create([
                                'article_no' => $currentArticleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'schedule_id' => $schedule->schedule_id,
                                'subtypes_id' => $subtypes_id,
                                'article_title' => $articletitle,
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 3) {
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];

                            $rule = Rules::create([
                                'rule_no' => $currentRuleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'schedule_id' => $schedule->schedule_id,
                                'subtypes_id' => $subtypes_id,
                                'rule_title' => $ruletitle,
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 4) {

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];


                            $regulation = Regulation::create([
                                'regulation_no' => $currentRegulationNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'schedule_id' => $schedule->schedule_id,
                                'subtypes_id' => $subtypes_id,
                                'regulation_title' => $regulationtitle,
                            ]);

                            // $regulationId = $regulation->regulation_id;

                            // $form = Form::create([
                            //     'regulation_id' => $regulationId,
                            //     'act_id' => $act->act_id,
                            //     'form_title' => $request->form_title,
                            // ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 5) {

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];


                            $list = Lists::create([
                                'list_no' => $currentListNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'schedule_id' => $schedule->schedule_id,
                                'subtypes_id' => $subtypes_id,
                                'list_title' => $listtitle,
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 6) {

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];


                            $part = Part::create([
                                'part_no' => $currentPartNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'schedule_id' => $schedule->schedule_id,
                                'subtypes_id' => $subtypes_id,
                                'part_title' => $parttitle,
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 7) {

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];


                            $appendices = Appendices::create([
                                'appendices_no' => $currentAppendicesNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'schedule_id' => $schedule->schedule_id,
                                'subtypes_id' => $subtypes_id,
                                'appendices_title' => $appendicestitle,
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 8) {

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];


                            $order = Orders::create([
                                'order_no' => $currentOrderNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'schedule_id' => $schedule->schedule_id,
                                'subtypes_id' => $subtypes_id,
                                'order_title' => $ordertitle,
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 9) {

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];


                            $annexure = Annexure::create([
                                'annexure_no' => $currentAnnexureNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'schedule_id' => $schedule->schedule_id,
                                'subtypes_id' => $subtypes_id,
                                'annexure_title' => $annexuretitle,
                            ]);
                        }
                    }
                     elseif ($request->subtypes_id[$key] == 10) {

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];


                            $stschedule = Stschedule::create([
                                'stschedule_no' => $currentStscheduleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'schedule_id' => $schedule->schedule_id,
                                'subtypes_id' => $subtypes_id,
                                'stschedule_title' => $stscheduletitle,
                            ]);
                        }
                    }
                } elseif ($maintypeId == "5") {
                    $appendix = new Appendix();
                    $appendix->act_id = $act->act_id ?? null;
                    $appendix->maintype_id = $maintypeId;
                    $appendix->appendix_title = $request->appendix_title[$key] ?? null;
                    $appendix->save();

                    if (isset($request->subtypes_id[$key]) && $request->subtypes_id[$key] == 1) {
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];

                                // Update SubSection records, Footnote records, etc. (similar to Section)
                                $lastSection = Section::orderBy('section_rank', 'desc')->first();

                                $lastRank = $lastSection ? $lastSection->section_rank : 0;
                                // Create the new section with the updated section_no
                                $section = Section::create([
                                    'section_rank' => $lastRank + 1,
                                    'section_no' => $currentSectionNo,
                                    'act_id' => $act->act_id,
                                    'maintype_id' => $maintypeId,
                                    'appendix_id' => $appendix->appendix_id,
                                    'subtypes_id' => $subtypes_id,
                                    'section_title' => $sectiontitle,
                                ]);
                            }
                        }
                    } elseif ($request->subtypes_id[$key] == 2) {
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];

                            $article = Article::create([
                                'article_no' => $currentArticleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'appendix_id' => $appendix->appendix_id,
                                'subtypes_id' => $subtypes_id,
                                'article_title' => $articletitle,
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 3) {
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];

                            $rule = Rules::create([
                                'rule_no' => $currentRuleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'appendix_id' => $appendix->appendix_id,
                                'subtypes_id' => $subtypes_id,
                                'rule_title' => $ruletitle,
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 4) {

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];


                            $regulation = Regulation::create([
                                'regulation_no' => $currentRegulationNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'appendix_id' => $appendix->appendix_id,
                                'subtypes_id' => $subtypes_id,
                                'regulation_title' => $regulationtitle,
                            ]);

                            // $regulationId = $regulation->regulation_id;

                            // $form = Form::create([
                            //     'regulation_id' => $regulationId,
                            //     'act_id' => $act->act_id,
                            //     'form_title' => $request->form_title,
                            // ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 5) {

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];


                            $list = Lists::create([
                                'list_no' => $currentListNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'appendix_id' => $appendix->appendix_id,
                                'subtypes_id' => $subtypes_id,
                                'list_title' => $listtitle,
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 6) {

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];


                            $part = Part::create([
                                'part_no' => $currentPartNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'appendix_id' => $appendix->appendix_id,
                                'subtypes_id' => $subtypes_id,
                                'part_title' => $parttitle,
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 7) {

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];


                            $appendices = Appendices::create([
                                'appendices_no' => $currentAppendicesNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'appendix_id' => $appendix->appendix_id,
                                'subtypes_id' => $subtypes_id,
                                'appendices_title' => $appendicestitle,
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 8) {

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];


                            $order = Orders::create([
                                'order_no' => $currentOrderNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'appendix_id' => $appendix->appendix_id,
                                'subtypes_id' => $subtypes_id,
                                'order_title' => $ordertitle,
                            ]);
                        }
                    } elseif ($request->subtypes_id[$key] == 9) {

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];


                            $annexure = Annexure::create([
                                'annexure_no' => $currentAnnexureNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'appendix_id' => $appendix->appendix_id,
                                'subtypes_id' => $subtypes_id,
                                'annexure_title' => $annexuretitle,
                            ]);
                        }
                    }
                     elseif ($request->subtypes_id[$key] == 10) {

                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];


                            $stschedule = Stschedule::create([
                                'stschedule_no' => $currentStscheduleNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'appendix_id' => $appendix->appendix_id,
                                'subtypes_id' => $subtypes_id,
                                'stschedule_title' => $stscheduletitle,
                            ]);
                        }
                    }
                } elseif ($maintypeId == "6") {
                    $main_order = new MainOrder();
                    $main_order->act_id = $act->act_id ?? null;
                    $main_order->maintype_id = $maintypeId;
                    $main_order->main_order_title = $request->main_order_title[$key] ?? null;
                    $main_order->save();

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

    public function view(Request $request, $id)
    {
        $export = Act::where('act_id', $id)->get();
        return view('admin.act.view', compact('export'));
    }
    public function update_main_act(Request $request, $id)
    {

        try {
            $validator = Validator::make($request->all(), [
                'act_footnote_title.*' => 'nullable',
                'act_footnote_description.*' => 'nullable',
            ]);

            if ($validator->fails()) {
                throw ValidationException::withMessages($validator->errors()->toArray());
            }

            $act = Act::find($id);
            $act->act_title = $request->act_title;
            $act->ministry = $request->ministry;
            $act->act_no = $request->act_no ?? null;
            $act->act_date = $request->act_date ?? null;
            $act->enactment_date = $request->enactment_date ?? null;
            $act->enforcement_date = $request->enforcement_date ?? null;
            $act->act_description = $request->act_description ?? null;
            $act->act_footnote_title = json_encode($request->act_footnote_title);
            $act->act_footnote_description = json_encode($request->act_footnote_description);
            $act->update();


            return redirect()->back()->with('success', 'Main Act Updated Successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }

    public function new_act()
    {
        $category = Category::all();
        $states = State::all();
        $actSummary = ActSummary::all();
        return view('admin.act.new_act', compact('category', 'states', 'actSummary'));
    }

    public function store_new_act(Request $request)
    {
        try {
            $act = new Act();
            $act->category_id = $request->category_id;
            $act->state_id = $request->state_id ?? null;
            $act->act_title = $request->act_title;
            $actSummaries = ActSummary::pluck('id')->map(function ($id) {
                return (string) $id;
            })->toArray();
            $act->act_summary = json_encode($actSummaries);
            $act->save();

            return redirect()->route('act')->with('success', 'Act created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->route('act')->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }


    public function edit_main_act(Request $request, $id)
    {
        $act_id = $id;
        $mainact = ActSummary::all();
        $act = Act::find($act_id);

        return view('admin.act.main_act', compact('act_id', 'mainact', 'act'));
    }



    public function edit()
    {
        return view('admin.act.edit');
    }

    public function destroy(string $id)
    {
        try {
            $act = Act::find($id);
            if (!$act) {
                return redirect()->back()->withErrors(['error' => 'Act not found.']);
            }

            // Delete related records
            Chapter::where('act_id', $id)->delete();
            Parts::where('act_id', $id)->delete();
            Schedule::where('act_id', $id)->delete();
            Priliminary::where('act_id', $id)->delete();
            Appendix::where('act_id', $id)->delete();
            MainOrder::where('act_id', $id)->delete();

            Section::where('act_id', $id)->delete();
            Rules::where('act_id', $id)->delete();
            Regulation::where('act_id', $id)->delete();
            Stschedule::where('act_id', $id)->delete();
            Orders::where('act_id', $id)->delete();
            Part::where('act_id', $id)->delete();
            Lists::where('act_id', $id)->delete();
            Annexure::where('act_id', $id)->delete();
            Appendices::where('act_id', $id)->delete();
            Article::where('act_id', $id)->delete();

            SubSection::where('act_id', $id)->delete();
            SubRules::where('act_id', $id)->delete();
            SubRegulation::where('act_id', $id)->delete();
            SubStschedule::where('act_id', $id)->delete();
            SubOrders::where('act_id', $id)->delete();
            SubPart::where('act_id', $id)->delete();
            SubLists::where('act_id', $id)->delete();
            SubAnnexure::where('act_id', $id)->delete();
            SubAppendices::where('act_id', $id)->delete();
            SubArticle::where('act_id', $id)->delete();
            Footnote::where('act_id', $id)->delete();

            $act->delete();

            return redirect()->back()->with('success', 'Act and related records deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting act and related records: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete act and related records. Please try again.' . $e->getMessage()]);
        }
    }
}
