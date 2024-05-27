<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\Category;
use App\Models\State;
use App\Models\MainTypeRegulation;
use App\Models\SubTypeRegulation;
use App\Models\PartsType;
use App\Models\RegulationMain;
use App\Models\RegulationTable;
use App\Models\NewRegulation;
use App\Models\RegulationSub;
use App\Models\MainRegulationFootnote;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Dompdf\Dompdf;
use Dompdf\Options;

class MainRegulationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $act_id = $id;
        $act = Act::where('act_id', $act_id)->first();
       $new_regulation = NewRegulation::where('act_id', $act_id)->get();
      
       $perPage = request()->get('perPage') ?: 10;
        $page = request()->get('page') ?: 1;
        $slicedItems = array_slice($new_regulation->toArray(), ($page - 1) * $perPage, $perPage);

        $paginatedCollection = new LengthAwarePaginator(
            $slicedItems,
            count($new_regulation),
            $perPage,
            $page
        );

        $paginatedCollection->appends(['perPage' => $perPage]);

        $paginatedCollection->withPath(request()->url());
        return view('admin.MainRegulation.index', compact('act','act_id','paginatedCollection'));
 
    }

    public function add_new_regulation($id){
        $category = Category::all();
        $states = State::all();
       return view('admin.MainRegulation.new_regulation', compact('id','category','states',));

    }

    public function store_new_regulation(Request $request){
        try{
            $newRegulation = new NewRegulation();
            $newRegulation->act_id = $request->act_id ?? null;
            $newRegulation->new_regulation_title = $request->new_regulation_title;
            $newRegulation->save();

            return redirect()->route('get_regulation', ['id' => $newRegulation->act_id])->with('success', 'Rule created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());
            return redirect()->route('get_regulation', ['id' => $newRegulation->act_id])->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }

    public function edit_new_regulation($id){
        $new_regulation_id = $id;
        $newRegulation = NewRegulation::where('new_regulation_id', $new_regulation_id)->with('act')->first();
        $mainsequence = RegulationMain::where('new_regulation_id', $id)
        ->with('mainTypeRegulation') 
        ->get()
        ->map(function ($regulationMain) {
            // Sort the ruletbl collection by rules_rank in ascending order
            $regulationMain->load(['regulationtbl' => function ($query) {
                $query->orderBy('regulations_rank');
            }]);
            return $regulationMain;
        })
        ->sortBy('regulation_main_rank');
           
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
            
        
        return view('admin.MainRegulation.show', compact('newRegulation','paginatedCollection'));     
   
    }

    public function update_new_regulation(Request $request, $id){
        try {
           
            $newRule = NewRegulation::find($id);
            $newRule->new_regulation_title = $request->new_regulation_title;
            $newRule->ministry = $request->ministry;
            $newRule->new_regulation_no = $request->new_regulation_no ?? null;
            $newRule->new_regulation_date = $request->new_regulation_date ?? null;
            $newRule->enactment_date = $request->enactment_date ?? null;
            $newRule->enforcement_date = $request->enforcement_date ?? null;
            $newRule->new_regulation_description = $request->new_regulation_description ?? null;
            $newRule->new_regulation_footnote_description = $request->new_regulation_footnote_description;
            $newRule->update();


            return redirect()->back()->with('success', 'Regulation Updated Successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $newRegulation = NewRegulation::find($id);
        $mtype = MainTypeRegulation::all();
        $stype = SubTypeRegulation::all();
       
        
        return view('admin.MainRegulation.create', compact('newRegulation','mtype','stype'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        try {
           
            $newRegulation = NewRegulation::find($id);
            $newRegulation->update([
                'category_id' => $request->category_id,
                'state_id' => $request->state_id ?? null,
                'new_regulation_title' => $request->new_regulation_title,
            ]);
        
           $k = 0;
            foreach ($request->regulation_maintype_id as $key => $regulationmaintypeid) {
                $lastRank = RegulationMain::max('regulation_main_rank');
                $lastRank = ceil(floatval($lastRank));
                $lastRank = max(0, $lastRank);
                $lastRank = (int) $lastRank;

                if($lastRank){
                  $k=   $lastRank;
                }

                if ($regulationmaintypeid == "1") {
                    $regulationMain = new RegulationMain();
                    $regulationMain->new_regulation_id = $newRegulation->new_regulation_id;
                    $regulationMain->regulation_main_rank = $k + 1;
                    $regulationMain->act_id = $newRegulation->act_id ?? null;
                    $regulationMain->regulation_maintype_id = $regulationmaintypeid;
                    $regulationMain->regulation_main_title = $request->chapter_title[$key] ?? null;
                    $regulationMain->save();

                    if (isset($request->regulation_subtypes_id[$key]) && $request->regulation_subtypes_id[$key] == 1) {
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;

                        $i = 0;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];
                                $lastRgltnRank= RegulationTable::max('regulations_rank');
                                $lastRgltnRank = ceil(floatval($lastRgltnRank));
                                $lastRgltnRank = max(0, $lastRgltnRank);
                                $lastRgltnRank = (int) $lastRgltnRank;

                                if($lastRgltnRank){
                                   $i = $lastRgltnRank;
                                }       
                                $section = RegulationTable::create([
                                    'new_regulation_id' => $newRegulation->new_regulation_id,
                                    'regulations_rank' => $i + 1,
                                    'regulations_no' => $currentSectionNo,
                                    'regulation_main_id' => $regulationMain->regulation_main_id,
                                    'regulation_subtypes_id' => $regulation_subtypes_id,
                                    'regulations_title' => $sectiontitle,
                                ]);
                            }
                        }
                    }elseif ($request->regulation_subtypes_id[$key] == 2) {
                        $i =0;
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentArticleNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $articletitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 3) {
                        $i = 0;
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentRuleNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $ruletitle,
                            ]);
                           
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 4) {
                         $i = 0;
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentRegulationNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $regulationtitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 5) {
                        $i = 0;
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentListNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $listtitle,
                            ]);

                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 6) {
                             $i = 0;
                             $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentPartNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $parttitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 7) {
                         $i =0; 
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentAppendicesNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $appendicestitle,
                            ]);
                           
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 8) {
                         $i = 0;
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentOrderNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $ordertitle,
                            ]);
                           
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 9) {
                           $i = 0;
                           $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentAnnexureNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $annexuretitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 10) {
                         $i =0;
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentStscheduleNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $stscheduletitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 11) {
                        $i =0;
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                       foreach ($request->form_title[$key] as $index => $formtitle) {
                           $currentFormNo = $request->form_no[$key][$index];
                           $lastRgltnRank= RegulationTable::max('regulations_rank');
                           $lastRgltnRank = ceil(floatval($lastRgltnRank));
                           $lastRgltnRank = max(0, $lastRgltnRank);
                           $lastRgltnRank = (int) $lastRgltnRank;

                           if($lastRgltnRank){
                              $i = $lastRgltnRank;
                           }       
                           $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                               'regulations_rank' => $i + 1,
                               'regulations_no' => $currentFormNo,
                               'regulation_main_id' => $regulationMain->regulation_main_id,
                               'regulation_subtypes_id' => $regulation_subtypes_id,
                               'regulations_title' => $formtitle,
                           ]);
                       }
                   }
                }else if($regulationmaintypeid == "2"){
                    $regulationMain = new RegulationMain();
                    $regulationMain->new_regulation_id = $newRegulation->new_regulation_id;
                    $regulationMain->regulation_main_rank = $k + 1;
                    $regulationMain->act_id = $newRegulation->act_id ?? null;
                    $regulationMain->regulation_maintype_id = $regulationmaintypeid;
                    $regulationMain->regulation_main_title = $request->schedule_title[$key] ?? null;
                    $regulationMain->save();

                    if (isset($request->regulation_subtypes_id[$key]) && $request->regulation_subtypes_id[$key] == 1) {
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;

                        $i = 0;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];
                                $lastRgltnRank= RegulationTable::max('regulations_rank');
                                $lastRgltnRank = ceil(floatval($lastRgltnRank));
                                $lastRgltnRank = max(0, $lastRgltnRank);
                                $lastRgltnRank = (int) $lastRgltnRank;

                                if($lastRgltnRank){
                                   $i = $lastRgltnRank;
                                }       
                                $section = RegulationTable::create([
                                    'new_regulation_id' => $newRegulation->new_regulation_id,
                                    'regulations_rank' => $i + 1,
                                    'regulations_no' => $currentSectionNo,
                                    'regulation_main_id' => $regulationMain->regulation_main_id,
                                    'regulation_subtypes_id' => $regulation_subtypes_id,
                                    'regulations_title' => $sectiontitle,
                                ]);
                            }
                        }
                    }elseif ($request->regulation_subtypes_id[$key] == 2) {
                        $i =0;
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentArticleNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $articletitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 3) {
                        $i = 0;
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentRuleNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $ruletitle,
                            ]);
                           
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 4) {
                         $i = 0;
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentRegulationNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $regulationtitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 5) {
                        $i = 0;
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentListNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $listtitle,
                            ]);

                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 6) {
                             $i = 0;
                             $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentPartNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $parttitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 7) {
                         $i =0; 
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentAppendicesNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $appendicestitle,
                            ]);
                           
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 8) {
                         $i = 0;
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentOrderNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $ordertitle,
                            ]);
                           
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 9) {
                           $i = 0;
                           $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentAnnexureNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $annexuretitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 10) {
                         $i =0;
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentStscheduleNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $stscheduletitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 11) {
                        $i =0;
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                       foreach ($request->form_title[$key] as $index => $formtitle) {
                           $currentFormNo = $request->form_no[$key][$index];
                           $lastRgltnRank= RegulationTable::max('regulations_rank');
                           $lastRgltnRank = ceil(floatval($lastRgltnRank));
                           $lastRgltnRank = max(0, $lastRgltnRank);
                           $lastRgltnRank = (int) $lastRgltnRank;

                           if($lastRgltnRank){
                              $i = $lastRgltnRank;
                           }       
                           $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                               'regulations_rank' => $i + 1,
                               'regulations_no' => $currentFormNo,
                               'regulation_main_id' => $regulationMain->regulation_main_id,
                               'regulation_subtypes_id' => $regulation_subtypes_id,
                               'regulations_title' => $formtitle,
                           ]);
                       }
                   }
                }else if($regulationmaintypeid == "3"){
                    $regulationMain = new RegulationMain();
                    $regulationMain->new_regulation_id = $newRegulation->new_regulation_id;
                    $regulationMain->regulation_main_rank = $k + 1;
                    $regulationMain->act_id = $newRegulation->act_id ?? null;
                    $regulationMain->regulation_maintype_id = $regulationmaintypeid;
                    $regulationMain->regulation_main_title = $request->main_annexure_title[$key] ?? null;
                    $regulationMain->save();

                    if (isset($request->regulation_subtypes_id[$key]) && $request->regulation_subtypes_id[$key] == 1) {
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;

                        $i = 0;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];
                                $lastRgltnRank= RegulationTable::max('regulations_rank');
                                $lastRgltnRank = ceil(floatval($lastRgltnRank));
                                $lastRgltnRank = max(0, $lastRgltnRank);
                                $lastRgltnRank = (int) $lastRgltnRank;

                                if($lastRgltnRank){
                                   $i = $lastRgltnRank;
                                }       
                                $section = RegulationTable::create([
                                    'new_regulation_id' => $newRegulation->new_regulation_id,
                                    'regulations_rank' => $i + 1,
                                    'regulations_no' => $currentSectionNo,
                                    'regulation_main_id' => $regulationMain->regulation_main_id,
                                    'regulation_subtypes_id' => $regulation_subtypes_id,
                                    'regulations_title' => $sectiontitle,
                                ]);
                            }
                        }
                    }elseif ($request->regulation_subtypes_id[$key] == 2) {
                        $i =0;
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentArticleNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $articletitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 3) {
                        $i = 0;
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentRuleNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $ruletitle,
                            ]);
                           
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 4) {
                         $i = 0;
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentRegulationNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $regulationtitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 5) {
                        $i = 0;
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentListNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $listtitle,
                            ]);

                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 6) {
                             $i = 0;
                             $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentPartNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $parttitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 7) {
                         $i =0; 
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentAppendicesNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $appendicestitle,
                            ]);
                           
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 8) {
                         $i = 0;
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentOrderNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $ordertitle,
                            ]);
                           
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 9) {
                           $i = 0;
                           $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentAnnexureNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $annexuretitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 10) {
                         $i =0;
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentStscheduleNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $stscheduletitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 11) {
                        $i =0;
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                       foreach ($request->form_title[$key] as $index => $formtitle) {
                           $currentFormNo = $request->form_no[$key][$index];
                           $lastRgltnRank= RegulationTable::max('regulations_rank');
                           $lastRgltnRank = ceil(floatval($lastRgltnRank));
                           $lastRgltnRank = max(0, $lastRgltnRank);
                           $lastRgltnRank = (int) $lastRgltnRank;

                           if($lastRgltnRank){
                              $i = $lastRgltnRank;
                           }       
                           $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                               'regulations_rank' => $i + 1,
                               'regulations_no' => $currentFormNo,
                               'regulation_main_id' => $regulationMain->regulation_main_id,
                               'regulation_subtypes_id' => $regulation_subtypes_id,
                               'regulations_title' => $formtitle,
                           ]);
                       }
                   }
                }else if($regulationmaintypeid == "4"){
                    $regulationMain = new RegulationMain();
                    $regulationMain->new_regulation_id = $newRegulation->new_regulation_id;
                    $regulationMain->regulation_main_rank = $k + 1;
                    $regulationMain->act_id = $newRegulation->act_id ?? null;
                    $regulationMain->regulation_maintype_id = $regulationmaintypeid;
                    $regulationMain->regulation_main_title = $request->parts_title[$key] ?? null;
                    $regulationMain->save();

                    if (isset($request->regulation_subtypes_id[$key]) && $request->regulation_subtypes_id[$key] == 1) {
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;

                        $i = 0;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];
                                $lastRgltnRank= RegulationTable::max('regulations_rank');
                                $lastRgltnRank = ceil(floatval($lastRgltnRank));
                                $lastRgltnRank = max(0, $lastRgltnRank);
                                $lastRgltnRank = (int) $lastRgltnRank;

                                if($lastRgltnRank){
                                   $i = $lastRgltnRank;
                                }       
                                $section = RegulationTable::create([
                                    'new_regulation_id' => $newRegulation->new_regulation_id,
                                    'regulations_rank' => $i + 1,
                                    'regulations_no' => $currentSectionNo,
                                    'regulation_main_id' => $regulationMain->regulation_main_id,
                                    'regulation_subtypes_id' => $regulation_subtypes_id,
                                    'regulations_title' => $sectiontitle,
                                ]);
                            }
                        }
                    }elseif ($request->regulation_subtypes_id[$key] == 2) {
                        $i =0;
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentArticleNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $articletitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 3) {
                        $i = 0;
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentRuleNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $ruletitle,
                            ]);
                           
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 4) {
                         $i = 0;
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentRegulationNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $regulationtitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 5) {
                        $i = 0;
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentListNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $listtitle,
                            ]);

                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 6) {
                             $i = 0;
                             $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentPartNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $parttitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 7) {
                         $i =0; 
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentAppendicesNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $appendicestitle,
                            ]);
                           
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 8) {
                         $i = 0;
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentOrderNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $ordertitle,
                            ]);
                           
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 9) {
                           $i = 0;
                           $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentAnnexureNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $annexuretitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 10) {
                         $i =0;
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentStscheduleNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $stscheduletitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 11) {
                        $i =0;
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                       foreach ($request->form_title[$key] as $index => $formtitle) {
                           $currentFormNo = $request->form_no[$key][$index];
                           $lastRgltnRank= RegulationTable::max('regulations_rank');
                           $lastRgltnRank = ceil(floatval($lastRgltnRank));
                           $lastRgltnRank = max(0, $lastRgltnRank);
                           $lastRgltnRank = (int) $lastRgltnRank;

                           if($lastRgltnRank){
                              $i = $lastRgltnRank;
                           }       
                           $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                               'regulations_rank' => $i + 1,
                               'regulations_no' => $currentFormNo,
                               'regulation_main_id' => $regulationMain->regulation_main_id,
                               'regulation_subtypes_id' => $regulation_subtypes_id,
                               'regulations_title' => $formtitle,
                           ]);
                       }
                   }
                }else if($regulationmaintypeid == "5"){
                    $regulationMain = new RegulationMain();
                    $regulationMain->new_regulation_id = $newRegulation->new_regulation_id;
                    $regulationMain->regulation_main_rank = $k + 1;
                    $regulationMain->act_id = $newRegulation->act_id ?? null;
                    $regulationMain->regulation_maintype_id = $regulationmaintypeid;
                    $regulationMain->regulation_main_title = $request->appendix_title[$key] ?? null;
                    $regulationMain->save();

                    if (isset($request->regulation_subtypes_id[$key]) && $request->regulation_subtypes_id[$key] == 1) {
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;

                        $i = 0;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];
                                $lastRgltnRank= RegulationTable::max('regulations_rank');
                                $lastRgltnRank = ceil(floatval($lastRgltnRank));
                                $lastRgltnRank = max(0, $lastRgltnRank);
                                $lastRgltnRank = (int) $lastRgltnRank;

                                if($lastRgltnRank){
                                   $i = $lastRgltnRank;
                                }       
                                $section = RegulationTable::create([
                                    'new_regulation_id' => $newRegulation->new_regulation_id,
                                    'regulations_rank' => $i + 1,
                                    'regulations_no' => $currentSectionNo,
                                    'regulation_main_id' => $regulationMain->regulation_main_id,
                                    'regulation_subtypes_id' => $regulation_subtypes_id,
                                    'regulations_title' => $sectiontitle,
                                ]);
                            }
                        }
                    }elseif ($request->regulation_subtypes_id[$key] == 2) {
                        $i =0;
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentArticleNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $articletitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 3) {
                        $i = 0;
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentRuleNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $ruletitle,
                            ]);
                           
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 4) {
                         $i = 0;
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentRegulationNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $regulationtitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 5) {
                        $i = 0;
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentListNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $listtitle,
                            ]);

                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 6) {
                             $i = 0;
                             $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentPartNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $parttitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 7) {
                         $i =0; 
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentAppendicesNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $appendicestitle,
                            ]);
                           
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 8) {
                         $i = 0;
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentOrderNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $ordertitle,
                            ]);
                           
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 9) {
                           $i = 0;
                           $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentAnnexureNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $annexuretitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 10) {
                         $i =0;
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentStscheduleNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $stscheduletitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 11) {
                        $i =0;
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                       foreach ($request->form_title[$key] as $index => $formtitle) {
                           $currentFormNo = $request->form_no[$key][$index];
                           $lastRgltnRank= RegulationTable::max('regulations_rank');
                           $lastRgltnRank = ceil(floatval($lastRgltnRank));
                           $lastRgltnRank = max(0, $lastRgltnRank);
                           $lastRgltnRank = (int) $lastRgltnRank;

                           if($lastRgltnRank){
                              $i = $lastRgltnRank;
                           }       
                           $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                               'regulations_rank' => $i + 1,
                               'regulations_no' => $currentFormNo,
                               'regulation_main_id' => $regulationMain->regulation_main_id,
                               'regulation_subtypes_id' => $regulation_subtypes_id,
                               'regulations_title' => $formtitle,
                           ]);
                       }
                   }
                }else if($regulationmaintypeid == "6"){
                    $regulationMain = new RegulationMain();
                    $regulationMain->new_regulation_id = $newRegulation->new_regulation_id;
                    $regulationMain->regulation_main_rank = $k + 1;
                    $regulationMain->act_id = $newRegulation->act_id ?? null;
                    $regulationMain->regulation_maintype_id = $regulationmaintypeid;
                    $regulationMain->regulation_main_title = $request->main_section_title[$key] ?? null;
                    $regulationMain->save();

                    if (isset($request->regulation_subtypes_id[$key]) && $request->regulation_subtypes_id[$key] == 1) {
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;

                        $i = 0;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];
                                $lastRgltnRank= RegulationTable::max('regulations_rank');
                                $lastRgltnRank = ceil(floatval($lastRgltnRank));
                                $lastRgltnRank = max(0, $lastRgltnRank);
                                $lastRgltnRank = (int) $lastRgltnRank;

                                if($lastRgltnRank){
                                   $i = $lastRgltnRank;
                                }       
                                $section = RegulationTable::create([
                                    'new_regulation_id' => $newRegulation->new_regulation_id,
                                    'regulations_rank' => $i + 1,
                                    'regulations_no' => $currentSectionNo,
                                    'regulation_main_id' => $regulationMain->regulation_main_id,
                                    'regulation_subtypes_id' => $regulation_subtypes_id,
                                    'regulations_title' => $sectiontitle,
                                ]);
                            }
                        }
                    }elseif ($request->regulation_subtypes_id[$key] == 2) {
                        $i =0;
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentArticleNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $articletitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 3) {
                        $i = 0;
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentRuleNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $ruletitle,
                            ]);
                           
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 4) {
                         $i = 0;
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentRegulationNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $regulationtitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 5) {
                        $i = 0;
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentListNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $listtitle,
                            ]);

                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 6) {
                             $i = 0;
                             $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentPartNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $parttitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 7) {
                         $i =0; 
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                            $currentAppendicesNo = $request->appendices_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentAppendicesNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $appendicestitle,
                            ]);
                           
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 8) {
                         $i = 0;
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentOrderNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $ordertitle,
                            ]);
                           
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 9) {
                           $i = 0;
                           $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                            $currentAnnexureNo = $request->annexure_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentAnnexureNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $annexuretitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 10) {
                         $i =0;
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                        foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                            $currentStscheduleNo = $request->stschedule_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentStscheduleNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $stscheduletitle,
                            ]);
                        }
                    } elseif ($request->regulation_subtypes_id[$key] == 11) {
                        $i =0;
                        $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                       foreach ($request->form_title[$key] as $index => $formtitle) {
                           $currentFormNo = $request->form_no[$key][$index];
                           $lastRgltnRank= RegulationTable::max('regulations_rank');
                           $lastRgltnRank = ceil(floatval($lastRgltnRank));
                           $lastRgltnRank = max(0, $lastRgltnRank);
                           $lastRgltnRank = (int) $lastRgltnRank;

                           if($lastRgltnRank){
                              $i = $lastRgltnRank;
                           }       
                           $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                               'regulations_rank' => $i + 1,
                               'regulations_no' => $currentFormNo,
                               'regulation_main_id' => $regulationMain->regulation_main_id,
                               'regulation_subtypes_id' => $regulation_subtypes_id,
                               'regulations_title' => $formtitle,
                           ]);
                       }
                   }
                }
            }

            return redirect()->route('edit_new_regulation', ['id' => $newRegulation->new_regulation_id])->with('success', 'Index added successfully');

        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }

    public function add_below_new_regulation_maintype(Request $request, $newRgltId, $id){
         
          $mainRegulation = RegulationMain::where('regulation_main_id',$id)->with('newRegulation')->first();
        
          $mtype = MainTypeRegulation::all();
          $stype = SubTypeRegulation::all();
      
          return view('admin.MainRegulation.add_new_regulation_maintype', compact('mainRegulation','mtype','stype'));
    }

   public function store_regulation_maintype(Request $request){
    try {
        $id =  $request->new_regulation_id;
        $k =  $request->click_main_rank;
        $regulation_main_id =  $request->regulation_main_id;
        $newRegulation = NewRegulation::find($id);
        $newRegulation->update([
            'category_id' => $request->category_id,
            'state_id' => $request->state_id ?? null,
            'new_regulation_title' => $request->new_regulation_title,
        ]);
    
        foreach ($request->regulation_maintype_id as $key => $regulationmaintypeid) {
            $nextRank = RegulationMain::where('new_regulation_id', $id)
            ->where('regulation_main_rank', '>', $k)
            ->min('regulation_main_rank');

            if ($nextRank) {
                $rank = ($k + $nextRank) / 2;
            } else {
                // If there's no next rank, add a small value to $i
                $rank = $k + 0.001;
            }


            if ($regulationmaintypeid == "1") {
                $regulationMain = new RegulationMain();
                $regulationMain->new_regulation_id = $newRegulation->new_regulation_id;
                $regulationMain->regulation_main_rank = $rank;
                $regulationMain->act_id = $newRegulation->act_id ?? null;
                $regulationMain->regulation_maintype_id = $regulationmaintypeid;
                $regulationMain->regulation_main_title = $request->chapter_title[$key] ?? null;
                $regulationMain->save();

                if (isset($request->regulation_subtypes_id[$key]) && $request->regulation_subtypes_id[$key] == 1) {
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;

                    $i = 0;
                    foreach ($request->section_title[$key] as $index => $sectiontitle) {
                        if (
                            isset($request->section_no[$key][$index]) &&
                            is_string($request->section_no[$key][$index])
                        ) {
                            $currentSectionNo = $request->section_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentSectionNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $sectiontitle,
                            ]);
                        }
                    }
                }elseif ($request->regulation_subtypes_id[$key] == 2) {
                    $i =0;
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->article_title[$key] as $index => $articletitle) {
                        $currentArticleNo = $request->article_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentArticleNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $articletitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 3) {
                    $i = 0;
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->rule_title[$key] as $index => $ruletitle) {
                        $currentRuleNo = $request->rule_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentRuleNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $ruletitle,
                        ]);
                       
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 4) {
                     $i = 0;
                     $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                        $currentRegulationNo = $request->regulation_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentRegulationNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $regulationtitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 5) {
                    $i = 0;
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->list_title[$key] as $index => $listtitle) {
                        $currentListNo = $request->list_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentListNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $listtitle,
                        ]);

                    }
                } elseif ($request->regulation_subtypes_id[$key] == 6) {
                         $i = 0;
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->part_title[$key] as $index => $parttitle) {
                        $currentPartNo = $request->part_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentPartNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $parttitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 7) {
                     $i =0; 
                     $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                        $currentAppendicesNo = $request->appendices_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentAppendicesNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $appendicestitle,
                        ]);
                       
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 8) {
                     $i = 0;
                     $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->order_title[$key] as $index => $ordertitle) {
                        $currentOrderNo = $request->order_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentOrderNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $ordertitle,
                        ]);
                       
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 9) {
                       $i = 0;
                       $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                        $currentAnnexureNo = $request->annexure_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentAnnexureNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $annexuretitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 10) {
                     $i =0;
                     $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                        $currentStscheduleNo = $request->stschedule_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentStscheduleNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $stscheduletitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 11) {
                    $i =0;
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                   foreach ($request->form_title[$key] as $index => $formtitle) {
                       $currentFormNo = $request->form_no[$key][$index];
                       $lastRgltnRank= RegulationTable::max('regulations_rank');
                       $lastRgltnRank = ceil(floatval($lastRgltnRank));
                       $lastRgltnRank = max(0, $lastRgltnRank);
                       $lastRgltnRank = (int) $lastRgltnRank;

                       if($lastRgltnRank){
                          $i = $lastRgltnRank;
                       }       
                       $section = RegulationTable::create([
                           'new_regulation_id' => $newRegulation->new_regulation_id,
                           'regulations_rank' => $i + 1,
                           'regulations_no' => $currentFormNo,
                           'regulation_main_id' => $regulationMain->regulation_main_id,
                           'regulation_subtypes_id' => $regulation_subtypes_id,
                           'regulations_title' => $formtitle,
                       ]);
                   }
               }
            }else if($regulationmaintypeid == "2"){
                $regulationMain = new RegulationMain();
                $regulationMain->new_regulation_id = $newRegulation->new_regulation_id;
                $regulationMain->regulation_main_rank = $rank;
                $regulationMain->act_id = $newRegulation->act_id ?? null;
                $regulationMain->regulation_maintype_id = $regulationmaintypeid;
                $regulationMain->regulation_main_title = $request->schedule_title[$key] ?? null;
                $regulationMain->save();

                if (isset($request->regulation_subtypes_id[$key]) && $request->regulation_subtypes_id[$key] == 1) {
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;

                    $i = 0;
                    foreach ($request->section_title[$key] as $index => $sectiontitle) {
                        if (
                            isset($request->section_no[$key][$index]) &&
                            is_string($request->section_no[$key][$index])
                        ) {
                            $currentSectionNo = $request->section_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentSectionNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $sectiontitle,
                            ]);
                        }
                    }
                }elseif ($request->regulation_subtypes_id[$key] == 2) {
                    $i =0;
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->article_title[$key] as $index => $articletitle) {
                        $currentArticleNo = $request->article_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentArticleNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $articletitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 3) {
                    $i = 0;
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->rule_title[$key] as $index => $ruletitle) {
                        $currentRuleNo = $request->rule_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentRuleNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $ruletitle,
                        ]);
                       
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 4) {
                     $i = 0;
                     $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                        $currentRegulationNo = $request->regulation_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentRegulationNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $regulationtitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 5) {
                    $i = 0;
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->list_title[$key] as $index => $listtitle) {
                        $currentListNo = $request->list_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentListNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $listtitle,
                        ]);

                    }
                } elseif ($request->regulation_subtypes_id[$key] == 6) {
                         $i = 0;
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->part_title[$key] as $index => $parttitle) {
                        $currentPartNo = $request->part_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentPartNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $parttitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 7) {
                     $i =0; 
                     $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                        $currentAppendicesNo = $request->appendices_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentAppendicesNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $appendicestitle,
                        ]);
                       
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 8) {
                     $i = 0;
                     $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->order_title[$key] as $index => $ordertitle) {
                        $currentOrderNo = $request->order_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentOrderNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $ordertitle,
                        ]);
                       
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 9) {
                       $i = 0;
                       $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                        $currentAnnexureNo = $request->annexure_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentAnnexureNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $annexuretitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 10) {
                     $i =0;
                     $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                        $currentStscheduleNo = $request->stschedule_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentStscheduleNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $stscheduletitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 11) {
                    $i =0;
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                   foreach ($request->form_title[$key] as $index => $formtitle) {
                       $currentFormNo = $request->form_no[$key][$index];
                       $lastRgltnRank= RegulationTable::max('regulations_rank');
                       $lastRgltnRank = ceil(floatval($lastRgltnRank));
                       $lastRgltnRank = max(0, $lastRgltnRank);
                       $lastRgltnRank = (int) $lastRgltnRank;

                       if($lastRgltnRank){
                          $i = $lastRgltnRank;
                       }       
                       $section = RegulationTable::create([
                           'new_regulation_id' => $newRegulation->new_regulation_id,
                           'regulations_rank' => $i + 1,
                           'regulations_no' => $currentFormNo,
                           'regulation_main_id' => $regulationMain->regulation_main_id,
                           'regulation_subtypes_id' => $regulation_subtypes_id,
                           'regulations_title' => $formtitle,
                       ]);
                   }
               }
            }else if($regulationmaintypeid == "3"){
                $regulationMain = new RegulationMain();
                $regulationMain->new_regulation_id = $newRegulation->new_regulation_id;
                $regulationMain->regulation_main_rank = $rank;
                $regulationMain->act_id = $newRegulation->act_id ?? null;
                $regulationMain->regulation_maintype_id = $regulationmaintypeid;
                $regulationMain->regulation_main_title = $request->main_annexure_title[$key] ?? null;
                $regulationMain->save();

                if (isset($request->regulation_subtypes_id[$key]) && $request->regulation_subtypes_id[$key] == 1) {
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;

                    $i = 0;
                    foreach ($request->section_title[$key] as $index => $sectiontitle) {
                        if (
                            isset($request->section_no[$key][$index]) &&
                            is_string($request->section_no[$key][$index])
                        ) {
                            $currentSectionNo = $request->section_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentSectionNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $sectiontitle,
                            ]);
                        }
                    }
                }elseif ($request->regulation_subtypes_id[$key] == 2) {
                    $i =0;
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->article_title[$key] as $index => $articletitle) {
                        $currentArticleNo = $request->article_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentArticleNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $articletitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 3) {
                    $i = 0;
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->rule_title[$key] as $index => $ruletitle) {
                        $currentRuleNo = $request->rule_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentRuleNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $ruletitle,
                        ]);
                       
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 4) {
                     $i = 0;
                     $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                        $currentRegulationNo = $request->regulation_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentRegulationNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $regulationtitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 5) {
                    $i = 0;
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->list_title[$key] as $index => $listtitle) {
                        $currentListNo = $request->list_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentListNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $listtitle,
                        ]);

                    }
                } elseif ($request->regulation_subtypes_id[$key] == 6) {
                         $i = 0;
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->part_title[$key] as $index => $parttitle) {
                        $currentPartNo = $request->part_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentPartNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $parttitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 7) {
                     $i =0; 
                     $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                        $currentAppendicesNo = $request->appendices_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentAppendicesNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $appendicestitle,
                        ]);
                       
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 8) {
                     $i = 0;
                     $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->order_title[$key] as $index => $ordertitle) {
                        $currentOrderNo = $request->order_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentOrderNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $ordertitle,
                        ]);
                       
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 9) {
                       $i = 0;
                       $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                        $currentAnnexureNo = $request->annexure_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentAnnexureNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $annexuretitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 10) {
                     $i =0;
                     $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                        $currentStscheduleNo = $request->stschedule_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentStscheduleNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $stscheduletitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 11) {
                    $i =0;
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                   foreach ($request->form_title[$key] as $index => $formtitle) {
                       $currentFormNo = $request->form_no[$key][$index];
                       $lastRgltnRank= RegulationTable::max('regulations_rank');
                       $lastRgltnRank = ceil(floatval($lastRgltnRank));
                       $lastRgltnRank = max(0, $lastRgltnRank);
                       $lastRgltnRank = (int) $lastRgltnRank;

                       if($lastRgltnRank){
                          $i = $lastRgltnRank;
                       }       
                       $section = RegulationTable::create([
                           'new_regulation_id' => $newRegulation->new_regulation_id,
                           'regulations_rank' => $i + 1,
                           'regulations_no' => $currentFormNo,
                           'regulation_main_id' => $regulationMain->regulation_main_id,
                           'regulation_subtypes_id' => $regulation_subtypes_id,
                           'regulations_title' => $formtitle,
                       ]);
                   }
               }
            }else if($regulationmaintypeid == "4"){
                $regulationMain = new RegulationMain();
                $regulationMain->new_regulation_id = $newRegulation->new_regulation_id;
                $regulationMain->regulation_main_rank = $rank;
                $regulationMain->act_id = $newRegulation->act_id ?? null;
                $regulationMain->regulation_maintype_id = $regulationmaintypeid;
                $regulationMain->regulation_main_title = $request->parts_title[$key] ?? null;
                $regulationMain->save();

                if (isset($request->regulation_subtypes_id[$key]) && $request->regulation_subtypes_id[$key] == 1) {
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;

                    $i = 0;
                    foreach ($request->section_title[$key] as $index => $sectiontitle) {
                        if (
                            isset($request->section_no[$key][$index]) &&
                            is_string($request->section_no[$key][$index])
                        ) {
                            $currentSectionNo = $request->section_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentSectionNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $sectiontitle,
                            ]);
                        }
                    }
                }elseif ($request->regulation_subtypes_id[$key] == 2) {
                    $i =0;
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->article_title[$key] as $index => $articletitle) {
                        $currentArticleNo = $request->article_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentArticleNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $articletitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 3) {
                    $i = 0;
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->rule_title[$key] as $index => $ruletitle) {
                        $currentRuleNo = $request->rule_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentRuleNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $ruletitle,
                        ]);
                       
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 4) {
                     $i = 0;
                     $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                        $currentRegulationNo = $request->regulation_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentRegulationNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $regulationtitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 5) {
                    $i = 0;
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->list_title[$key] as $index => $listtitle) {
                        $currentListNo = $request->list_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentListNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $listtitle,
                        ]);

                    }
                } elseif ($request->regulation_subtypes_id[$key] == 6) {
                         $i = 0;
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->part_title[$key] as $index => $parttitle) {
                        $currentPartNo = $request->part_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentPartNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $parttitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 7) {
                     $i =0; 
                     $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                        $currentAppendicesNo = $request->appendices_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentAppendicesNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $appendicestitle,
                        ]);
                       
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 8) {
                     $i = 0;
                     $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->order_title[$key] as $index => $ordertitle) {
                        $currentOrderNo = $request->order_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentOrderNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $ordertitle,
                        ]);
                       
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 9) {
                       $i = 0;
                       $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                        $currentAnnexureNo = $request->annexure_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentAnnexureNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $annexuretitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 10) {
                     $i =0;
                     $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                        $currentStscheduleNo = $request->stschedule_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentStscheduleNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $stscheduletitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 11) {
                    $i =0;
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                   foreach ($request->form_title[$key] as $index => $formtitle) {
                       $currentFormNo = $request->form_no[$key][$index];
                       $lastRgltnRank= RegulationTable::max('regulations_rank');
                       $lastRgltnRank = ceil(floatval($lastRgltnRank));
                       $lastRgltnRank = max(0, $lastRgltnRank);
                       $lastRgltnRank = (int) $lastRgltnRank;

                       if($lastRgltnRank){
                          $i = $lastRgltnRank;
                       }       
                       $section = RegulationTable::create([
                           'new_regulation_id' => $newRegulation->new_regulation_id,
                           'regulations_rank' => $i + 1,
                           'regulations_no' => $currentFormNo,
                           'regulation_main_id' => $regulationMain->regulation_main_id,
                           'regulation_subtypes_id' => $regulation_subtypes_id,
                           'regulations_title' => $formtitle,
                       ]);
                   }
               }
            }else if($regulationmaintypeid == "5"){
                $regulationMain = new RegulationMain();
                $regulationMain->new_regulation_id = $newRegulation->new_regulation_id;
                $regulationMain->regulation_main_rank = $rank;
                $regulationMain->act_id = $newRegulation->act_id ?? null;
                $regulationMain->regulation_maintype_id = $regulationmaintypeid;
                $regulationMain->regulation_main_title = $request->appendix_title[$key] ?? null;
                $regulationMain->save();

                if (isset($request->regulation_subtypes_id[$key]) && $request->regulation_subtypes_id[$key] == 1) {
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;

                    $i = 0;
                    foreach ($request->section_title[$key] as $index => $sectiontitle) {
                        if (
                            isset($request->section_no[$key][$index]) &&
                            is_string($request->section_no[$key][$index])
                        ) {
                            $currentSectionNo = $request->section_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentSectionNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $sectiontitle,
                            ]);
                        }
                    }
                }elseif ($request->regulation_subtypes_id[$key] == 2) {
                    $i =0;
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->article_title[$key] as $index => $articletitle) {
                        $currentArticleNo = $request->article_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentArticleNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $articletitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 3) {
                    $i = 0;
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->rule_title[$key] as $index => $ruletitle) {
                        $currentRuleNo = $request->rule_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentRuleNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $ruletitle,
                        ]);
                       
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 4) {
                     $i = 0;
                     $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                        $currentRegulationNo = $request->regulation_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentRegulationNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $regulationtitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 5) {
                    $i = 0;
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->list_title[$key] as $index => $listtitle) {
                        $currentListNo = $request->list_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentListNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $listtitle,
                        ]);

                    }
                } elseif ($request->regulation_subtypes_id[$key] == 6) {
                         $i = 0;
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->part_title[$key] as $index => $parttitle) {
                        $currentPartNo = $request->part_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentPartNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $parttitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 7) {
                     $i =0; 
                     $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                        $currentAppendicesNo = $request->appendices_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentAppendicesNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $appendicestitle,
                        ]);
                       
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 8) {
                     $i = 0;
                     $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->order_title[$key] as $index => $ordertitle) {
                        $currentOrderNo = $request->order_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentOrderNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $ordertitle,
                        ]);
                       
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 9) {
                       $i = 0;
                       $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                        $currentAnnexureNo = $request->annexure_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentAnnexureNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $annexuretitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 10) {
                     $i =0;
                     $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                        $currentStscheduleNo = $request->stschedule_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentStscheduleNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $stscheduletitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 11) {
                    $i =0;
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                   foreach ($request->form_title[$key] as $index => $formtitle) {
                       $currentFormNo = $request->form_no[$key][$index];
                       $lastRgltnRank= RegulationTable::max('regulations_rank');
                       $lastRgltnRank = ceil(floatval($lastRgltnRank));
                       $lastRgltnRank = max(0, $lastRgltnRank);
                       $lastRgltnRank = (int) $lastRgltnRank;

                       if($lastRgltnRank){
                          $i = $lastRgltnRank;
                       }       
                       $section = RegulationTable::create([
                           'new_regulation_id' => $newRegulation->new_regulation_id,
                           'regulations_rank' => $i + 1,
                           'regulations_no' => $currentFormNo,
                           'regulation_main_id' => $regulationMain->regulation_main_id,
                           'regulation_subtypes_id' => $regulation_subtypes_id,
                           'regulations_title' => $formtitle,
                       ]);
                   }
               }
            }else if($regulationmaintypeid == "6"){
                $regulationMain = new RegulationMain();
                $regulationMain->new_regulation_id = $newRegulation->new_regulation_id;
                $regulationMain->regulation_main_rank = $rank;
                $regulationMain->act_id = $newRegulation->act_id ?? null;
                $regulationMain->regulation_maintype_id = $regulationmaintypeid;
                $regulationMain->regulation_main_title = $request->main_section_title[$key] ?? null;
                $regulationMain->save();

                if (isset($request->regulation_subtypes_id[$key]) && $request->regulation_subtypes_id[$key] == 1) {
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;

                    $i = 0;
                    foreach ($request->section_title[$key] as $index => $sectiontitle) {
                        if (
                            isset($request->section_no[$key][$index]) &&
                            is_string($request->section_no[$key][$index])
                        ) {
                            $currentSectionNo = $request->section_no[$key][$index];
                            $lastRgltnRank= RegulationTable::max('regulations_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = RegulationTable::create([
                                'new_regulation_id' => $newRegulation->new_regulation_id,
                                'regulations_rank' => $i + 1,
                                'regulations_no' => $currentSectionNo,
                                'regulation_main_id' => $regulationMain->regulation_main_id,
                                'regulation_subtypes_id' => $regulation_subtypes_id,
                                'regulations_title' => $sectiontitle,
                            ]);
                        }
                    }
                }elseif ($request->regulation_subtypes_id[$key] == 2) {
                    $i =0;
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->article_title[$key] as $index => $articletitle) {
                        $currentArticleNo = $request->article_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentArticleNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $articletitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 3) {
                    $i = 0;
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->rule_title[$key] as $index => $ruletitle) {
                        $currentRuleNo = $request->rule_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentRuleNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $ruletitle,
                        ]);
                       
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 4) {
                     $i = 0;
                     $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                        $currentRegulationNo = $request->regulation_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentRegulationNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $regulationtitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 5) {
                    $i = 0;
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->list_title[$key] as $index => $listtitle) {
                        $currentListNo = $request->list_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentListNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $listtitle,
                        ]);

                    }
                } elseif ($request->regulation_subtypes_id[$key] == 6) {
                         $i = 0;
                         $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->part_title[$key] as $index => $parttitle) {
                        $currentPartNo = $request->part_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentPartNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $parttitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 7) {
                     $i =0; 
                     $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->appendices_title[$key] as $index => $appendicestitle) {
                        $currentAppendicesNo = $request->appendices_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentAppendicesNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $appendicestitle,
                        ]);
                       
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 8) {
                     $i = 0;
                     $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->order_title[$key] as $index => $ordertitle) {
                        $currentOrderNo = $request->order_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentOrderNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $ordertitle,
                        ]);
                       
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 9) {
                       $i = 0;
                       $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->annexure_title[$key] as $index => $annexuretitle) {
                        $currentAnnexureNo = $request->annexure_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentAnnexureNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $annexuretitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 10) {
                     $i =0;
                     $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                    foreach ($request->stschedule_title[$key] as $index => $stscheduletitle) {
                        $currentStscheduleNo = $request->stschedule_no[$key][$index];
                        $lastRgltnRank= RegulationTable::max('regulations_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                           $i = $lastRgltnRank;
                        }       
                        $section = RegulationTable::create([
                            'new_regulation_id' => $newRegulation->new_regulation_id,
                            'regulations_rank' => $i + 1,
                            'regulations_no' => $currentStscheduleNo,
                            'regulation_main_id' => $regulationMain->regulation_main_id,
                            'regulation_subtypes_id' => $regulation_subtypes_id,
                            'regulations_title' => $stscheduletitle,
                        ]);
                    }
                } elseif ($request->regulation_subtypes_id[$key] == 11) {
                    $i =0;
                    $regulation_subtypes_id = $request->regulation_subtypes_id[$key] ?? null;
                   foreach ($request->form_title[$key] as $index => $formtitle) {
                       $currentFormNo = $request->form_no[$key][$index];
                       $lastRgltnRank= RegulationTable::max('regulations_rank');
                       $lastRgltnRank = ceil(floatval($lastRgltnRank));
                       $lastRgltnRank = max(0, $lastRgltnRank);
                       $lastRgltnRank = (int) $lastRgltnRank;

                       if($lastRgltnRank){
                          $i = $lastRgltnRank;
                       }       
                       $section = RegulationTable::create([
                           'new_regulation_id' => $newRegulation->new_regulation_id,
                           'regulations_rank' => $i + 1,
                           'regulations_no' => $currentFormNo,
                           'regulation_main_id' => $regulationMain->regulation_main_id,
                           'regulation_subtypes_id' => $regulation_subtypes_id,
                           'regulations_title' => $formtitle,
                       ]);
                   }
               }
            }
        }

        return redirect()->route('edit_new_regulation', ['id' => $newRegulation->new_regulation_id])->with('success', 'Index added successfully');

    } catch (\Exception $e) {
        \Log::error('Error creating Act: ' . $e->getMessage());

        return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
    }
   }

   public function delete_regulation_maintype($id){
    try {
        $mainRegulation = RegulationMain::findOrFail($id);
        $mainRegulation->delete();
        Session::flash('success', 'Main Regulation deleted successfully.');
    } catch (\Exception $e) {
        Session::flash('error', 'Failed to delete RuleMain.');
    }
    return redirect()->back()->with('flash_timeout', 10);
   }

   public function edit_regulationTable(Request $request, $id){
        $currentPage = $request->page;
        $regulationTable = RegulationTable::with(['mainRegulation', 'mainRegulation.newRegulation', 'regulationFootnoteModel' => function ($query) {
            $query->whereNull('regulation_sub_id');
        }])->where('regulations_id', $id)->firstOrFail(); 

        $regulationSubs = RegulationSub::where('regulations_id',$id)->with('regulationSubFootnoteModel')->get();
        // dd($ruleSub);
        // die();
        return view('admin.MainRegulation.edit', compact('regulationTable','regulationSubs','currentPage'));
   }

   public function update_main_regulation(Request $request,$id){
    try {

       
        $currentPage = $request->currentPage;
        $new_regulation_id = $request->new_regulation_id;
        $regulation_main_id = $request->regulation_main_id;
        $regulation_subtypes_id = $request->regulation_subtypes_id;
        if ($request->has('regulation_main_id')) {
            $regulationsM = RegulationMain::find($request->regulation_main_id);
           
            if ($regulationsM) {
                $regulationsM->regulation_main_title = $request->regulation_main_title;
                $regulationsM->update();
            }
        }
    
        $regulationt = RegulationTable::find($id);
        
        
        if ($regulationt) {
            $regulationt->regulations_content = $request->regulations_content ?? null;
            $regulationt->regulations_title = $request->regulations_title ?? null;
            $regulationt->regulations_no = $request->regulations_no ?? null;
            $regulationt->update();

            if ($request->has('sec_footnote_content')) {
                $item = $request->sec_footnote_content;
                if ($request->has('sec_footnote_id')) {
        
                    $footnote_id = $request->sec_footnote_id;
        
                    if (isset($footnote_id)) {
                       
                        $foot = MainRegulationFootnote::find($footnote_id);

                        if ($foot) {
                            $foot->footnote_content = $item ?? null;
                            $foot->update();
                        }
                    }
                }else {
                    
                        $footnote = new MainRegulationFootnote();
                        $footnote->regulations_id = $id ?? null;
                        $footnote->new_regulation_id = $new_regulation_id ?? null;
                        $footnote->footnote_content = $item ?? null;
                        $footnote->save();
                    }
            }  
        }


        if ($request->has('regulation_sub_no')) {
            foreach ($request->regulation_sub_no as $key => $item) {
                $regulation_sub_id = $request->regulation_sub_id[$key] ?? null;
                $regulation_sub_content = $request->regulation_sub_content[$key] ?? null;
                 
                // Check if sub_section_id is present and valid
                if ($regulation_sub_id && $existingSubRegulation = RegulationSub::find($regulation_sub_id)) {
                    $existingSubRegulation->update([
                        'regulation_sub_no' => $item,
                        'regulation_sub_content' => $regulation_sub_content,
                    ]);

                    if ($request->has('sub_footnote_content') && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                        foreach ($request->sub_footnote_content[$key] as $kys => $footnote_content) {
                            // Check if the footnote with the given ID exists
                            // dd($request->sub_footnote_id[$key][$kys]);
                            // die();
                           
                            $footnote_id = $request->sub_footnote_id[$key][$kys] ?? null;
                            if ($footnote_id && $foot = MainRegulationFootnote::find($footnote_id)) {
                                $foot->update(['footnote_content' => $footnote_content]);
                            }
                            else {
                             // Create new footnote if ID is not provided or invalid
                                $footnote = new MainRegulationFootnote();
                                $footnote->regulation_sub_id = $regulation_sub_id;
                                $footnote->regulations_id = $id ?? null;
                                $footnote->new_regulation_id = $new_regulation_id ?? null;
                                $footnote->footnote_content = $footnote_content ?? null;
                                $footnote->save();
                            }
                        }
                    }
                } else {

                    $i = 0;
                    $lastSubRgltnRank= RegulationSub::max('regulation_sub_rank');
                    $lastSubRuleRank = ceil(floatval($lastSubRgltnRank));
                    $lastSubRgltnRank = max(0, $lastSubRgltnRank);
                    $lastSubRgltnRank = (int) $lastSubRgltnRank;

                    if($lastSubRgltnRank){
                       $i = $lastSubRgltnRank;
                    }   

                        $subsec = new RegulationSub();
                        $subsec->regulations_id = $id ?? null;
                        $subsec->regulation_main_id = $regulation_main_id ?? null;
                        $subsec->regulation_sub_rank = $i + 1;
                        $subsec->regulation_subtypes_id = $regulation_subtypes_id;
                        $subsec->regulation_sub_no = $item ?? null;
                        $subsec->new_regulation_id = $new_regulation_id ?? null;
                        $subsec->regulation_sub_content = $regulation_sub_content ?? null;
                        $subsec->save();
        
                    // Handle footnotes for the new sub_section
                    if ($request->has('sub_footnote_content') && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                        foreach ($request->sub_footnote_content[$key] as $kys => $footnote_content) {
                      
                            $footnote = new MainRegulationFootnote();
                            $footnote->regulation_sub_id = $subsec->regulation_sub_id;
                            $footnote->regulations_id = $id ?? null;
                            $footnote->new_regulation_id = $new_regulation_id ?? null;
                            $footnote->footnote_content = $footnote_content ?? null;
                            $footnote->save();
                        }
                    }
                }
            }
        }
        

        return redirect()->route('edit_new_regulation', ['id' => $new_regulation_id,'page' => $currentPage])->with('success', 'updated successfully');
    } catch (\Exception $e) {
        \Log::error('Error updating Act: ' . $e->getMessage());
        return redirect()->route('edit_new_regulation', ['id' => $new_regulation_id])->withErrors(['error' => 'Failed to update Section. Please try again.' . $e->getMessage()]);
    }

   }

   public function view_regulation_sub(Request $request, $id){
        $regulationSub = RegulationSub::where('regulations_id', $id)->get();

        if ($regulationSub->isEmpty()) {
            // If $regulationSub is empty, redirect back with a flash message
            return redirect()->back()->with('error', 'No data found.');
        }

        return view('admin.MainRegulation.view_regulation_sub', compact('regulationSub'));
   }

   public function delete_regulation_sub(Request $request, $id){
    try {
        $regulationSub = RegulationSub::findOrFail($id);
        $regulationSub->delete();
        Session::flash('success', 'deleted successfully.');
    } catch (\Exception $e) {
        Session::flash('error', 'Failed to delete RuleMain.');
    }
    return redirect()->back()->with('flash_timeout', 10);
   }

   public function delete_regulationstbl($id){
    try {
        $regulationtbl = RegulationTable::findOrFail($id);
        $regulationtbl->delete();
        Session::flash('success', 'deleted successfully.');
    } catch (\Exception $e) {
        Session::flash('error', 'Failed to delete RuleMain.');
    }
    return redirect()->back()->with('flash_timeout', 10);
   }

   public function add_below_new_rgtlntbl(Request $request, $rgltnId, $id){
    $currentPage = $request->page;
    $regulationTable = RegulationTable::with(['mainRegulation', 'mainRegulation.newRegulation', 'regulationFootnoteModel' => function ($query) {
        $query->whereNull('regulation_sub_id');
    }])->where('regulations_id', $id)->firstOrFail(); 

    return view('admin.MainRegulation.add_new_regulationtbl', compact('regulationTable','currentPage'));
   }


   public function add_new_regulationtbl(Request $request){
    try {

      
        $id = $request->click_regulations_id;
        $currentPage = $request->currentPage;
        $i = $request->click_regulations_rank;
        $new_regulation_id = $request->new_regulation_id;
        $regulation_main_id = $request->regulation_main_id;
        $regulation_subtypes_id = $request->regulation_subtypes_id;

        $nextRank = RegulationTable::where('regulation_main_id', $regulation_main_id)
        ->where('regulations_rank', '>', $i)
        ->min('regulations_rank');

        if ($nextRank) {
            $rank = ($i + $nextRank) / 2;
        } else {
            // If there's no next rank, add a small value to $i
            $rank = $i + 0.001;
        }

        $regulationT = new RegulationTable;
        $regulationT->new_regulation_id = $new_regulation_id;
        $regulationT->regulation_main_id = $regulation_main_id;
        $regulationT->regulation_subtypes_id = $regulation_subtypes_id;
        $regulationT->regulations_content = $request->regulations_content ?? null;
        $regulationT->regulations_title = $request->regulations_title ?? null;
        $regulationT->regulations_no = $request->regulations_no ?? null;
        $regulationT->regulations_rank = $rank;
        $regulationT->save();

        if ($request->has('sec_footnote_content')) {
            $item = $request->sec_footnote_content;
            $footnote = new MainRegulationFootnote();
            $footnote->regulations_id = $regulationT->regulations_id ?? null;
            $footnote->new_regulation_id = $new_regulation_id ?? null;
            $footnote->footnote_content = $item ?? null;
            $footnote->save();       
        }


        if ($request->has('regulation_sub_no')) {
            foreach ($request->regulation_sub_no as $key => $item) {
                $regulation_sub_content = $request->regulation_sub_content[$key] ?? null;
                
                    $i = 0;
                    $lastSubRgltnRank= RegulationSub::max('regulation_sub_rank');
                    $lastSubRgltnRank = ceil(floatval($lastSubRgltnRank));
                    $lastSubRgltnRank = max(0, $lastSubRgltnRank);
                    $lastSubRgltnRank = (int) $lastSubRgltnRank;

                    if($lastSubRgltnRank){
                       $i = $lastSubRgltnRank;
                    }   

                    $subsec = new RegulationSub();
                    $subsec->regulations_id = $regulationT->regulations_id ?? null;
                    $subsec->regulation_main_id = $regulation_main_id ?? null;
                    $subsec->regulation_sub_rank = $i + 1;
                    $subsec->regulation_subtypes_id = $regulation_subtypes_id;
                    $subsec->regulation_sub_no = $item ?? null;
                    $subsec->new_regulation_id = $new_regulation_id ?? null;
                    $subsec->regulation_sub_content = $regulation_sub_content ?? null;
                    $subsec->save();
        
                    // Handle footnotes for the new sub_section
                    if ($request->has('sub_footnote_content') && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                        foreach ($request->sub_footnote_content[$key] as $kys => $footnote_content) {
                      
                            $footnote = new MainRegulationFootnote();
                            $footnote->regulation_sub_id = $subsec->regulation_sub_id;
                            $footnote->regulations_id = $regulationT->regulations_id;
                            $footnote->new_regulation_id = $new_regulation_id ?? null;
                            $footnote->footnote_content = $footnote_content ?? null;
                            $footnote->save();
                        }
                    }
                
            }
        }
        

        return redirect()->route('edit_new_regulation', ['id' => $new_regulation_id,'page' => $currentPage])->with('success', 'updated successfully');
    } catch (\Exception $e) {
        \Log::error('Error updating Act: ' . $e->getMessage());
        return redirect()->route('edit_new_regulation', ['id' => $new_regulation_id])->withErrors(['error' => 'Failed to update. Please try again.' . $e->getMessage()]);
    }
   }


   public function delete_new_regulation($id){
    try {

        $newRegulation = NewRegulation::findOrFail($id);
        $newRegulation->delete();
        Session::flash('success', 'deleted successfully.');
    } catch (\Exception $e) {
        Session::flash('error', 'Failed to delete RuleMain.');
    }
    return redirect()->back()->with('flash_timeout', 10);
   }
   public function delete_regulation_footnote(Request $request,$id){
    try {

        $regulationFootnote = MainRegulationFootnote::findOrFail($id);
        $regulationFootnote->delete();
        Session::flash('success', 'deleted successfully.');
    } catch (\Exception $e) {
        Session::flash('error', 'Failed to delete RuleMain.');
    }
    return redirect()->back()->with('flash_timeout', 10);
   }

   public function view_new_regulation(Request $request, $id){
    $currentPage = $request->query('page');
    $newRegulation = NewRegulation::findOrFail($id);
    return view('admin.MainRegulation.view_new_regulation', compact('newRegulation','currentPage'));
    
   }

   public function export_regulation_pdf(Request $request, $id){
        try {
            ini_set('memory_limit', '1024M');
            
            // Create Dompdf instance with options
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isJavascriptEnabled', true);
            $dompdf = new Dompdf($options);

            // Fetch data

            $newRegulation = NewRegulation::where('new_regulation_id', $id)
            ->with([
                'regulationMain' => function ($query) {
                    $query->with(['regulationtbl' => function ($query) {
                        $query->orderBy('regulations_rank');
                    }])->orderBy('regulation_main_rank'); // Sort ruleMain by rule_main_rank
                },
                'regulationMain.regulationtbl.regulationSub', 'regulationMain.regulationtbl.regulationFootnoteModel', 'regulationMain.regulationtbl.regulationSub.regulationSubFootnoteModel'
            ])
            ->get();

            // Load view and generate PDF
            $pdf = FacadePdf::loadView('admin.MainRegulation.pdf', ['combinedItems' => $newRegulation]);

            // Download PDF with a meaningful file name
            return $pdf->download("{$newRegulation[0]->new_regulation_title}.pdf");
        } catch (\Exception $e) {
            // Handle any errors
            return redirect()->back()->with('error', 'An error occurred while generating PDF: ' . $e->getMessage());
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
