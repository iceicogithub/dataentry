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
use App\Models\MainTable;
use App\Models\SubRules;
use App\Models\SubStschedule;
use App\Models\SubType;
use App\Models\ActSummaryRelation;
use App\Models\OtherMainAct;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class ActController extends Controller
{

    public function index(Request $request)
    {
        $currentPage = $request->query('page', 1);
        $perPage = $request->query('perPage', 10); // Default to 10 if not set
        $search = $request->query('search', ''); // Get the search query
    
        $query = Act::with('CategoryModel');
    
        // Apply search filter if search term is present
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('legislation_name', 'like', '%' . $search . '%')
                    ->orWhereHas('CategoryModel', function ($q) use ($search) {
                        $q->where('category', 'like', '%' . $search . '%');
                    });
            });
        }
    
        $acts = $query->orderBy('act_id', 'desc')->paginate($perPage);
    
        return view('admin.act.index', compact('acts', 'currentPage', 'perPage', 'search'));
    }
    

    public function get_act_section(Request $request, $id)
    {
        $act_id = $id;
        $act = Act::where('act_id', $act_id)->first();
        if ($act) {
            $act_footnote_titles = json_decode($act->act_footnote_title, true);
            $act_footnote_descriptions = json_decode($act->act_footnote_description, true);
        }




        // $act_section = Section::where('act_id', $id)
        // ->with('MainTypeModel', 'Schedulemodel', 'Appendixmodel', 'Partmodel', 'ChapterModel', 'PriliminaryModel','MainOrderModel')
        // ->get()
        // ->sortBy(function ($section) {
        //         return [floatval($section->section_rank)];
        // });
   
        // $act_article = Article::where('act_id', $id)
        //     ->with('MainTypeModel', 'Schedulemodel', 'Appendixmodel', 'Partmodel', 'ChapterModel', 'PriliminaryModel','MainOrderModel')
        //     ->get()
        //     ->sortBy(function ($article) {
        //             return [floatval($article->article_rank)];
        //     });

        // $act_rule = Rules::where('act_id', $id)
        //     ->with('MainTypeModel', 'Schedulemodel', 'Appendixmodel', 'Partmodel', 'ChapterModel', 'PriliminaryModel','MainOrderModel')
        //     ->get()
        //     ->sortBy(function ($rule) {
        //             return [floatval($rule->rule_rank)];
        //     });

        // $act_regulation = Regulation::where('act_id', $id)
        //     ->with('MainTypeModel', 'Schedulemodel', 'Appendixmodel', 'Partmodel', 'ChapterModel', 'PriliminaryModel','MainOrderModel')
        //     ->get()
        //     ->sortBy(function ($regulation) {
        //         return [floatval($regulation->regulation_rank)];
        //     });

        // $act_list = Lists::where('act_id', $id)
        //     ->with('MainTypeModel', 'Schedulemodel', 'Appendixmodel', 'Partmodel', 'ChapterModel', 'PriliminaryModel','MainOrderModel')
        //     ->get()
        //     ->sortBy(function ($list) {
        //         return [floatval($list->list_rank)];
        //     });

        // $act_part = Part::where('act_id', $id)
        //     ->with('MainTypeModel', 'Schedulemodel', 'Appendixmodel', 'Partmodel', 'ChapterModel', 'PriliminaryModel','MainOrderModel')
        //     ->get()
        //     ->sortBy(function ($part) {
        //         return [floatval($part->part_rank)];
        //     });

        // $act_appendices = Appendices::where('act_id', $id)
        //     ->with('MainTypeModel', 'Schedulemodel', 'Appendixmodel', 'Partmodel', 'ChapterModel', 'PriliminaryModel','MainOrderModel')->get()
        //     ->sortBy(function ($appendices) {
        //         return [floatval($appendices->appendices_rank)];
        //     });

        // $act_order = Orders::where('act_id', $id)
        //     ->with('MainTypeModel', 'Schedulemodel', 'Appendixmodel', 'Partmodel', 'ChapterModel', 'PriliminaryModel','MainOrderModel')
        //     ->get()
        //     ->sortBy(function ($order) {
        //         return [floatval($order->order_rank)];
        //     });

        // $act_annexure = Annexure::where('act_id', $id)
        //     ->with('MainTypeModel', 'Schedulemodel', 'Appendixmodel', 'Partmodel', 'ChapterModel', 'PriliminaryModel','MainOrderModel')
        //     ->get()
        //     ->sortBy(function ($annexure) {
        //         return [floatval($annexure->annexure_rank)];
        //     });

        // $act_stschedule = Stschedule::where('act_id', $id)
        //     ->with('MainTypeModel', 'Schedulemodel', 'Appendixmodel', 'Partmodel', 'ChapterModel', 'PriliminaryModel','MainOrderModel')
        //     ->get()
        //     ->sortBy(function ($stschedule) {
        //         return [floatval($stschedule->stschedule_rank)];
        //     });

        $mainsequence = MainTable::where('act_id', $id)
        ->with([
            'chapters' => function ($query) {
                $query->with([
                    'Sections' => function ($query) {
                        $query->with('subsectionModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('section_rank');
                    },
                    'Articles' => function ($query) {
                        $query->with('subArticleModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('article_rank');
                    },
                    'Rules' => function ($query) {
                        $query->with('subruleModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('rule_rank');
                    },
                    'Regulation' => function ($query) {
                        $query->with('subRegulationModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('regulation_rank');
                    },
                    'Lists' => function ($query) {
                        $query->with('subListModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('list_rank');
                    },
                    'Part' => function ($query) {
                        $query->with('subPartModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('part_rank');
                    },
                    'Appendices' => function ($query) {
                        $query->with('subAppendicesModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('appendices_rank');
                    },
                    'Order' => function ($query) {
                        $query->with('subOrderModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('order_rank');
                    },
                    'Annexure' => function ($query) {
                        $query->with('subAnnexureModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('annexure_rank');
                    },
                    'Stschedule' => function ($query) {
                        $query->with('subStscheduleModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('stschedule_rank');
                    },
                ]);
            }
        ])
        ->with([
            'parts' => function ($query) {
                $query->with([
                    'Sections' => function ($query) {
                        $query->with('subsectionModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('section_rank');
                    },
                    'Articles' => function ($query) {
                        $query->with('subArticleModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('article_rank');
                    },
                    'Rules' => function ($query) {
                        $query->with('subruleModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('rule_rank');
                    },
                    'Regulation' => function ($query) {
                        $query->with('subRegulationModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('regulation_rank');
                    },
                    'Lists' => function ($query) {
                        $query->with('subListModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('list_rank');
                    },
                    'Part' => function ($query) {
                        $query->with('subPartModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('part_rank');
                    },
                    'Appendices' => function ($query) {
                        $query->with('subAppendicesModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('appendices_rank');
                    },
                    'Order' => function ($query) {
                        $query->with('subOrderModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('order_rank');
                    },
                    'Annexure' => function ($query) {
                        $query->with('subAnnexureModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('annexure_rank');
                    },
                    'Stschedule' => function ($query) {
                        $query->with('subStscheduleModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('stschedule_rank');
                    },
                ]);
            }
        ])
        ->with([
            'priliminarys' => function ($query) {
                $query->with([
                    'Sections' => function ($query) {
                        $query->with('subsectionModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('section_rank');
                    },
                    'Articles' => function ($query) {
                        $query->with('subArticleModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('article_rank');
                    },
                    'Rules' => function ($query) {
                        $query->with('subruleModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('rule_rank');
                    },
                    'Regulation' => function ($query) {
                        $query->with('subRegulationModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('regulation_rank');
                    },
                    'Lists' => function ($query) {
                        $query->with('subListModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('list_rank');
                    },
                    'Part' => function ($query) {
                        $query->with('subPartModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('part_rank');
                    },
                    'Appendices' => function ($query) {
                        $query->with('subAppendicesModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('appendices_rank');
                    },
                    'Order' => function ($query) {
                        $query->with('subOrderModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('order_rank');
                    },
                    'Annexure' => function ($query) {
                        $query->with('subAnnexureModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('annexure_rank');
                    },
                    'Stschedule' => function ($query) {
                        $query->with('subStscheduleModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('stschedule_rank');
                    },
                ]);
            }
        ])
        ->with([
            'schedules' => function ($query) {
                $query->with([
                    'Sections' => function ($query) {
                        $query->with('subsectionModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('section_rank');
                    },
                    'Articles' => function ($query) {
                        $query->with('subArticleModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('article_rank');
                    },
                    'Rules' => function ($query) {
                        $query->with('subruleModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('rule_rank');
                    },
                    'Regulation' => function ($query) {
                        $query->with('subRegulationModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('regulation_rank');
                    },
                    'Lists' => function ($query) {
                        $query->with('subListModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('list_rank');
                    },
                    'Part' => function ($query) {
                        $query->with('subPartModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('part_rank');
                    },
                    'Appendices' => function ($query) {
                        $query->with('subAppendicesModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('appendices_rank');
                    },
                    'Order' => function ($query) {
                        $query->with('subOrderModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('order_rank');
                    },
                    'Annexure' => function ($query) {
                        $query->with('subAnnexureModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('annexure_rank');
                    },
                    'Stschedule' => function ($query) {
                        $query->with('subStscheduleModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('stschedule_rank');
                    },
                ]);
            }
        ])
        ->with([
            'appendixes' => function ($query) {
                $query->with([
                    'Sections' => function ($query) {
                        $query->with('subsectionModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('section_rank');
                    },
                    'Articles' => function ($query) {
                        $query->with('subArticleModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('article_rank');
                    },
                    'Rules' => function ($query) {
                        $query->with('subruleModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('rule_rank');
                    },
                    'Regulation' => function ($query) {
                        $query->with('subRegulationModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('regulation_rank');
                    },
                    'Lists' => function ($query) {
                        $query->with('subListModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('list_rank');
                    },
                    'Part' => function ($query) {
                        $query->with('subPartModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('part_rank');
                    },
                    'Appendices' => function ($query) {
                        $query->with('subAppendicesModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('appendices_rank');
                    },
                    'Order' => function ($query) {
                        $query->with('subOrderModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('order_rank');
                    },
                    'Annexure' => function ($query) {
                        $query->with('subAnnexureModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('annexure_rank');
                    },
                    'Stschedule' => function ($query) {
                        $query->with('subStscheduleModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('stschedule_rank');
                    },
                ]);
            }
        ])
        ->with([
            'mainOrders' => function ($query) {
                $query->with([
                    'Sections' => function ($query) {
                        $query->with('subsectionModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('section_rank');
                    },
                    'Articles' => function ($query) {
                        $query->with('subArticleModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('article_rank');
                    },
                    'Rules' => function ($query) {
                        $query->with('subruleModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('rule_rank');
                    },
                    'Regulation' => function ($query) {
                        $query->with('subRegulationModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('regulation_rank');
                    },
                    'Lists' => function ($query) {
                        $query->with('subListModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('list_rank');
                    },
                    'Part' => function ($query) {
                        $query->with('subPartModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('part_rank');
                    },
                    'Appendices' => function ($query) {
                        $query->with('subAppendicesModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('appendices_rank');
                    },
                    'Order' => function ($query) {
                        $query->with('subOrderModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('order_rank');
                    },
                    'Annexure' => function ($query) {
                        $query->with('subAnnexureModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('annexure_rank');
                    },
                    'Stschedule' => function ($query) {
                        $query->with('subStscheduleModel', 'footnoteModel', 'MainTypeModel')
                            ->orderBy('stschedule_rank');
                    },
                ]);
            }
        ])
        ->orderBy('main_rank')
        ->get();

            
        $combinedItems = collect([]);

        foreach ($mainsequence as $mainItem) {
            foreach ($mainItem->chapters as $chapter) {
                $chapterData = $chapter->toArray();
                $chapterData['main_id'] = $mainItem->main_id;
                $combinedItems->push($chapterData);
            }
        
            foreach ($mainItem->parts as $part) {
                $partData = $part->toArray();
                $partData['main_id'] = $mainItem->main_id;
                $combinedItems->push($partData);
            }
        
            foreach ($mainItem->priliminarys as $preliminary) {
                $preliminaryData = $preliminary->toArray();
                $preliminaryData['main_id'] = $mainItem->main_id;
                $combinedItems->push($preliminaryData);
            }
        
            foreach ($mainItem->schedules as $schedule) {
                $scheduleData = $schedule->toArray();
                $scheduleData['main_id'] = $mainItem->main_id;
                $combinedItems->push($scheduleData);
            }
        
            foreach ($mainItem->appendixes as $appendix) {
                $appendixData = $appendix->toArray();
                $appendixData['main_id'] = $mainItem->main_id;
                $combinedItems->push($appendixData);
            }
        
            foreach ($mainItem->mainOrders as $mainOrder) {
                $mainOrderData = $mainOrder->toArray();
                $mainOrderData['main_id'] = $mainItem->main_id;
                $combinedItems->push($mainOrderData);
            }
        }
        
            $perPage = request()->get('perPage') ?: 10;
            $page = request()->get('page') ?: 1;

            $paginatedCollection = new LengthAwarePaginator(
                $combinedItems->forPage($page, $perPage),
                $combinedItems->count(),
                $perPage,
                $page
            );

            $paginatedCollection->appends(['perPage' => $perPage]);

            $paginatedCollection->withPath(request()->url());
            
            return view('admin.section.index', compact('paginatedCollection', 'act_id', 'act', 'act_footnote_titles', 'act_footnote_descriptions')); 
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

           $mainSerialNo = MainTable::where('act_id', $id)->pluck('serial_no')->last();
           $lastSerialNo = max(0,$mainSerialNo);
    
        //    $sectionSerialNo = Section::where('act_id',$id)->pluck('serial_no')->last();
        //    $articleSerialNo = Article::where('act_id',$id)->pluck('serial_no')->last();
        //    $ruleSerialNo = Rules::where('act_id',$id)->pluck('serial_no')->last();
        //    $regulationSerialNo = Regulation::where('act_id',$id)->pluck('serial_no')->last();
        //    $listSerialNo = Lists::where('act_id',$id)->pluck('serial_no')->last();
        //    $partSerialNo = Part::where('act_id',$id)->pluck('serial_no')->last();
        //    $appendicesSerialNo = Appendices::where('act_id',$id)->pluck('serial_no')->last();
        //    $orderSerialNo = Orders::where('act_id',$id)->pluck('serial_no')->last();
        //    $annexureSerialNo = Annexure::where('act_id',$id)->pluck('serial_no')->last();
        //    $stscheduleSerialNo = Stschedule::where('act_id',$id)->pluck('serial_no')->last();
        //    $lastSerialNo = max(0, $sectionSerialNo, $articleSerialNo, $ruleSerialNo,$regulationSerialNo,$listSerialNo,$partSerialNo,$appendicesSerialNo,$orderSerialNo,$annexureSerialNo,$stscheduleSerialNo);
          
           
           $k = 0;
            foreach ($request->maintype_id as $key => $maintypeId) {
                $lastRank = MainTable::max('main_rank');
                $lastRank = ceil(floatval($lastRank));
                $lastRank = max(0, $lastRank);
                $lastRank = (int) $lastRank;

                if($lastRank){
                  $k=   $lastRank;
                }
            
                $lastSerialNo++;
                if ($maintypeId == "1") {
                    $chapt = new Chapter();
                    $chapt->act_id = $act->act_id ?? null;
                    $chapt->maintype_id = $maintypeId;
                    $chapt->chapter_title = $request->chapter_title[$key] ?? null;
                    $chapt->serial_no = $lastSerialNo;
                    $chapt->save();

                    MainTable::create([
                        'main_rank' =>  $k + 1,
                        'act_id' => $act->act_id,
                        'maintype_id' => $maintypeId,
                        'serial_no' => $lastSerialNo,
                        'chapter_id' =>$chapt->chapter_id
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

    public function view(Request $request, $id)
    {
        $currentPage = $request->query('page', 1); 
      
        $export = Act::where('act_id', $id)->get();
       return view('admin.act.view', compact('export','currentPage'));
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
        $actSummary = ActSummary::all();
        $category = Category::all();
        $states = State::all();
        return view('admin.act.new_act', compact('category', 'states', 'actSummary'));
    }

    public function store_new_act(Request $request)
    {
        try {

            $contains_id_1 = in_array(1, $request->act_summary_id);
    
            $act = new Act();
            $act->category_id = $request->category_id;
            $act->state_id = $request->state_id ?? null;
            $act->legislation_name = $request->legislation_name;
            $act->act_summary_id = $contains_id_1 ? 1 : null;
            $act->save();

            foreach ($request->act_summary_id as $act_summary_id) {
                ActSummaryRelation::create([
                    'act_id' => $act->act_id,
                    'act_summary_id' => $act_summary_id,
                ]);
            }

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
        $currentPage = $request->query('page', 1); 
        $relatedActSummaries = ActSummaryRelation::where('act_id', $act_id)->with('actSummary')->get();
     
        return view('admin.act.main_act', compact('act_id', 'mainact', 'act','relatedActSummaries','currentPage'));
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

    public function delete_parts($id) {
        $parts = Parts::find($id);
        if ($parts) {
            try {
                $parts->delete();
                return redirect()->back()->with('success', 'Parts deleted successfully');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to delete parts. Error: ' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Parts not found');
        }
    }

    public function delete_chapter($id) {
        $chapter = Chapter::find($id);
        if ($chapter) {
            try {
                
                $chapter->delete();
                return redirect()->back()->with('success', 'chapter deleted successfully');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to delete chapter. Error: ' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'chapter not found');
        }
    }

    public function delete_priliminary($id) {
        $priliminary = Priliminary::find($id);
        if ($priliminary) {
            try {
                
                $priliminary->delete();
                return redirect()->back()->with('success', 'priliminary deleted successfully');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to delete priliminary. Error: ' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'priliminary not found');
        }
    }

    public function delete_schedule($id) {
        $schedule = Schedule::find($id);
        if ($schedule) {
            try {
                
                $schedule->delete();
                return redirect()->back()->with('success', 'schedule deleted successfully');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to delete schedule. Error: ' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'schedule not found');
        }
    }

    public function delete_appendix($id) {
        $appendix = Appendix::find($id);
        if ($appendix) {
            try {
                
                $appendix->delete();
                return redirect()->back()->with('success', 'appendix deleted successfully');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to delete appendix. Error: ' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'appendix not found');
        }
    }

    public function delete_main_order($id) {
        $main_order = MainOrder::find($id);
        if ($main_order) {
            try {
                
                $main_order->delete();
                return redirect()->back()->with('success', 'order deleted successfully');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to delete order. Error: ' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'order not found');
        }
    }

    public function store_new_main_type(Request $request, $id)  {

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

           $lastSerialNo = $request->click_serial_no;          
           $k = $request->click_main_rank; 
            foreach ($request->maintype_id as $key => $maintypeId) {
              
                if ($maintypeId == "1") {
                    $chapt = new Chapter();
                    $chapt->act_id = $act->act_id ?? null;
                    $chapt->maintype_id = $maintypeId;
                    $chapt->chapter_title = $request->chapter_title[$key] ?? null;
                    $chapt->serial_no = $lastSerialNo;
                    $chapt->save();

                    MainTable::create([
                        'main_rank' =>  $k + 0.1,
                        'act_id' => $act->act_id,
                        'maintype_id' => $maintypeId,
                        'serial_no' => $lastSerialNo,
                        'chapter_id' =>$chapt->chapter_id
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
                        'main_rank' =>  $k + 0.1,
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
                        'main_rank' =>  $k + 0.1,
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
                        'main_rank' =>  $k + 0.1,
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
                        'main_rank' =>  $k + 0.1,
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
                        'main_rank' =>  $k + 0.1,
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

    
    public function add_new_main_type(Request $request, $act_id, $main_id, $id){
        $category = Category::all();
        $status = Status::all();
        $states = State::all();
        $mtype = MainType::all();
        $stype = SubType::all();
        $parts = PartsType::all();

        $act = Act::where('act_id', $act_id)->first();
        $showFormTitle = ($act->act_summary && in_array('6', json_decode($act->act_summary, true)));
        $data = MainTable::where('main_id',$main_id)->first();
        
          return view('admin.act.add_new_maintype', compact('data','category', 'status', 'states', 'mtype', 'parts', 'stype', 'act', 'showFormTitle'));
    }

    public function edit_legislation_name(Request $request,$id){
       $legislation = Act::find($id);
       $category = Category::all();
       $states = State::all();
       $actSummary = ActSummary::all();
       $actSummaryRltn = ActSummaryRelation::where('act_id',$id)->get();
       $currentPage = $request->query('page', 1); 
       return view('admin.act.edit_legislation',compact('legislation','category','states','actSummary','actSummaryRltn','currentPage'));
    }

    public function update_legislation(Request $request, $id){
        
        try {
            $legislation = Act::findOrFail($id); // Use findOrFail to throw an exception if the Act is not found
            $legislation->category_id = $request->category_id;
            $legislation->state_id = $request->has('state_id') ? $request->state_id : null;
            $legislation->legislation_name = $request->legislation_name;
            $legislation->update();
            ActSummaryRelation::where('act_id', $id)->delete();

            // Create new ActSummaryRelation records based on the selected Act summaries
            if ($request->has('act_summary_id') && is_array($request->act_summary_id)) {
                foreach ($request->act_summary_id as $act_summary_id) {
                    ActSummaryRelation::create([
                        'act_id' => $legislation->act_id, // Use $legislation->act_id instead of $act->act_id
                        'act_summary_id' => $act_summary_id,
                    ]);
                }
            }
            
            return redirect()->route('act')->with('success', 'Legislation updated successfully');
        } catch (\Exception $e) {
            // Redirect to the same route if an exception occurs
            return redirect()->route('act')->with('error', 'Failed to update legislation: ' . $e->getMessage());
        }
    }


    public function get_other_main_acts($id){
        $act = Act::findOrFail($id);
        $otherMainAct = OtherMainAct::where('act_id',$id)->with('acts')->first();

       return view('admin.act.other_main_acts',compact('act','otherMainAct'));
    }

    public function create_others_main_act(Request $request){

        try {
            // dd($request);
            // die();
            $otherAct = OtherMainAct::create([ 
               'act_id' => $request->act_id,
               'introduction' => $request->introduction,
               'effective_date' => $request->effective_date,
               'object_reasons' => $request->object_reasons,
               'legislative_history' => $request->legislative_history,
               'financial_implication' => $request->financial_implication,
            ]);
            
            
            return redirect()->back()->with('success', 'created successfully');
        } catch (\Exception $e) {
            // Redirect to the same route if an exception occurs
            return redirect()->back()->with('error', 'Failed to update: ' . $e->getMessage());
        }
    }


    public function update_others_main_act(Request $request, $id){
        try {
            // dd($request);
            // die();
            $otherAct = OtherMainAct::findOrFail($id);
            $otherAct->act_id =  $request->act_id;
            $otherAct->introduction =  $request->introduction;
            $otherAct->effective_date = $request->effective_date;
            $otherAct->object_reasons = $request->object_reasons;
            $otherAct->legislative_history = $request->legislative_history;
            $otherAct->financial_implication = $request->financial_implication;
            $otherAct->update();
            
            
            return redirect()->back()->with('success', 'updated successfully');
        } catch (\Exception $e) {
            // Redirect to the same route if an exception occurs
            return redirect()->back()->with('error', 'Failed to update: ' . $e->getMessage());
        }
    }
}
