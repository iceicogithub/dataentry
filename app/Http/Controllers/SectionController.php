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
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;


class SectionController extends Controller
{

    public function index()
    {
        $status = Status::all();

        return view('admin.section.index', compact('status'));
    }

    public function add_below_new_section(Request $request, $id, $section_id)
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

            $sections = Section::with('ChapterModel', 'Partmodel', 'PriliminaryModel','Schedulemodel','Appendixmodel','MainOrderModel')
                ->where('act_id', $id)
                ->where('section_id', $section_id)
                ->first();

            if (!$sections) {
                // Handle the scenario where no sections are found
                abort(404); // or redirect, or return a message
            }
             
            $currentPage = $request->page; 
            return view('admin.section.add_new', compact('category', 'status', 'states', 'mtype', 'parts', 'stype', 'act', 'showFormTitle','sections','currentPage'));
        }
    // public function add_new_section(Request $request)
    // {
    //     // dd($request);
    //     // die();
    //     try {
    //         if ($request->has('chapter_id')) {
    //             $chapter = Chapter::find($request->chapter_id);
     
    //             if ($chapter) {
    //                 $chapter->chapter_title = $request->chapter_title;
    //                 $chapter->update();
    //             }
    //         }
    //         if ($request->has('priliminary_id')) {
    //             $priliminary = Priliminary::find($request->priliminary_id);
     
    //             if ($priliminary) {
    //                 $priliminary->priliminary_title = $request->priliminary_title;
    //                 $priliminary->update();
    //             }
    //         }
    //         if ($request->has('parts_id')) {
    //             $part = Parts::find($request->parts_id);
     
    //             if ($part) {
    //                 $part->parts_title = $request->parts_title;
    //                 $part->update();
    //             }
    //         }
    //         if ($request->has('schedule_id')) {
    //             $schedule = Schedule::find($request->schedule_id);
     
    //             if ($schedule) {
    //                 $schedule->schedule_title = $request->schedule_title;
    //                 $schedule->update();
    //             }
    //         }
    //         if ($request->has('appendix_id')) {
    //             $appendix = Appendix::find($request->appendix_id);
     
    //             if ($appendix) {
    //                 $appendix->appendix_title = $request->appendix_title;
    //                 $appendix->update();
    //             }
    //         }
    //         if ($request->has('main_order_id')) {
    //             $main_order = MainOrder::find($request->main_order_id);
    
    //             if ($main_order) {
    //                 $main_order->main_order_title = $request->main_order_title;
    //                 $main_order->update();
    //             }
    //         }


    //     $id = $request->act_id;
    //     // $sec_no = $request->section_no;
    //     $sec_rank = $request->section_rank;
    //     $maintypeId = $request->maintype_id;

    //     // Calculate the next section number
    //     // $nextSectionNo = $sec_no;
    //     $oldSectionRank = $request->click_section_rank;
    //     $nextSectionRank = $oldSectionRank + 0.01;



    //     // Update the existing sections' section_no in the Section table
    //     // Section::where('section_no', '>=', $nextSectionNo)
    //     //     ->increment('section_no');

    //     // $click_section_no = $request->click_section_no;
    //     // $newsection = $request->section_no;

      

    //     // Create the new section with the incremented section_no
    //     $section = Section::create([
    //         'section_rank' => $nextSectionRank,
    //         'section_no' => $request->section_no ?? null,
    //         'act_id' => $request->act_id,
    //         'maintype_id' => $maintypeId,
    //         'chapter_id' => $request->chapter_id ?? null,
    //         'main_order_id' => $request->main_order_id ?? null,
    //         'priliminary_id' => $request->priliminary_id ?? null,
    //         'parts_id' => $request->parts_id ?? null,
    //         'schedule_id' => $request->schedule_id ?? null,
    //         'appendix_id' => $request->appendix_id ?? null,
    //         'subtypes_id' => $request->subtypes_id,
    //         'section_title' => $request->section_title,
    //         'section_content' => $request->section_content,
    //         'is_append' => 1,
    //         'serial_no' =>$request->serial_no
    //     ]);

    //     if ($request->has('sec_footnote_content')) {
    //         foreach ($request->sec_footnote_content as $key => $item) {
    //             // Check if the key exists before using it
    //             if (isset($request->sec_footnote_content[$key])) {
    //                 // Create a new footnote
    //                 $footnote = new Footnote();
    //                 $footnote->section_id = $section->section_id ?? null;
    //                 $footnote->act_id = $request->act_id ?? null;
    //                 $footnote->chapter_id = $request->chapter_id ?? null;
    //                 $footnote->main_order_id = $request->main_order_id ?? null;
    //                 $footnote->priliminary_id = $request->priliminary_id ?? null;
    //                 $footnote->parts_id = $request->parts_id ?? null;
    //                 $footnote->schedule_id = $request->schedule_id ?? null;
    //                 $footnote->appendix_id = $request->appendix_id ?? null;
    //                 $footnote->parts_id = $request->parts_id ?? null;
    //                 $footnote->footnote_content = $item ?? null;
    //                 $footnote->save();
    //             }
    //         }
    //     }

    //     if ($request->has('sub_section_no')) {
    //         foreach ($request->sub_section_no as $key => $item) {
    //             // Existing subsection not found, create a new one
    //             $sub_section = SubSection::create([
    //                 'section_id' => $section->section_id,
    //                 'sub_section_no' => $item ?? null,
    //                 'section_no' => $request->section_no ?? null,
    //                 'act_id' => $request->act_id,
    //                 'chapter_id' => $maintypeId == "1" ? $request->chapter_id : null,
    //                 'main_order_id' => $maintypeId == "6" ? $request->main_order_id : null,
    //                 'parts_id' => $maintypeId == "2" ? $request->parts_id : null,
    //                 'priliminary_id' => $maintypeId == "3" ? $request->priliminary_id : null,
    //                 'schedule_id' => $maintypeId == "4" ? $request->schedule_id : null,
    //                 'appendix_id' => $maintypeId == "5" ? $request->appendix_id : null,
    //                 'sub_section_content' => $request->sub_section_content[$key] ?? null,
    //             ]);

    //             if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
    //                 foreach ($request->sub_footnote_content[$key] as $kys => $item) {
    //                     // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
    //                     if (isset($request->sub_footnote_content[$key][$kys])) {
    //                         // Create a new footnote for the newly created subsection
    //                         $footnote = new Footnote();
    //                         $footnote->sub_section_id = $sub_section->sub_section_id;
    //                         $footnote->section_id = $section->section_id ?? null;
    //                         $footnote->act_id = $request->act_id ?? null;
    //                         $footnote->chapter_id = $request->chapter_id ?? null;
    //                         $footnote->main_order_id = $request->main_order_id ?? null;
    //                         $footnote->parts_id = $request->parts_id ?? null;
    //                         $footnote->priliminary_id = $request->priliminary_id ?? null;
    //                         $footnote->schedule_id = $request->schedule_id ?? null;
    //                         $footnote->appendix_id = $request->appendix_id ?? null;
    //                         $footnote->footnote_content = $item ?? null;
    //                         $footnote->save();
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     return redirect()->route('get_act_section', ['id' => $id])->with('success', 'Section created successfully');
    //     } catch (\Exception $e) {
    //         \Log::error('Error creating Act: ' . $e->getMessage());

    //         return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
    //     }
    // }

    public function add_new_section(Request $request, $id){
        {

            // dd($request);
            // die();
    
            try {
                
                $currentPage = $request->currentPage;
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
            //    $lastSerialNo = max(0, $sectionSerialNo, $articleSerialNo, $ruleSerialNo,$regulationSerialNo,$listSerialNo,$partSerialNo,$appendicesSerialNo,$orderSerialNo,$annexureSerialNo,$stscheduleSerialNo);
              
            $lastSerialNo = $request->serial_no;
            $oldSectionRank = $request->click_section_rank;
    
                foreach ($request->maintype_id as $key => $maintypeId) {
                    if ($maintypeId == "1") {
                        $chapt = new Chapter();
                        $chapt->act_id = $act->act_id ?? null;
                        $chapt->maintype_id = $maintypeId;
                        $chapt->chapter_title = $request->chapter_title[$key] ?? null;
                        $chapt->serial_no = $lastSerialNo;
                        $chapt->save();
    
                        if (isset($request->subtypes_id[$key]) && $request->subtypes_id[$key] == 1) {
                            $subtypes_id = $request->subtypes_id[$key] ?? null;
    
                            $i = $oldSectionRank ?? 0; 
                            foreach ($request->section_title[$key] as $index => $sectiontitle) {
                                if (
                                    isset($request->section_no[$key][$index]) &&
                                    is_string($request->section_no[$key][$index])
                                ) {
                                    $currentSectionNo = $request->section_no[$key][$index];
    
                                    // $lastSection = Section::max('section_rank');
                                    // $lastSection = ceil(floatval($lastSection));
                                    // $lastSection = max(0, $lastSection);
                                    // $lastSection = (int) $lastSection;

                                    
                                    $section = Section::create([
                                        'section_rank' => $i + 0.01,
                                        'section_no' => $currentSectionNo,
                                        'act_id' => $act->act_id,
                                        'maintype_id' => $maintypeId,
                                        'chapter_id' => $chapt->chapter_id,
                                        'subtypes_id' => $subtypes_id,
                                        'section_title' => $sectiontitle,
                                        'serial_no' => $lastSerialNo
                                    ]);
                                    $i += 0.01;
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
    
                return redirect()->route('get_act_section', ['id' => $id,'page' => $currentPage])->with('success', 'Index added successfully');
    
            } catch (\Exception $e) {
                \Log::error('Error creating Act: ' . $e->getMessage());
    
                return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
            }
        }
    }


    public function create()
    {
        $status = Status::all();
        return view('admin.section.create', compact('status'));
    }

    public function SubSection_Index()
    {
        $status = Status::all();
        return view('admin.sub-section.index', compact('status'));
    }

    public function SubSection_Create()
    {
        $status = Status::all();
        return view('admin.sub-section.create', compact('status'));
    }

    public function edit_section($id,Request $request)
    {
        $sections = Section::with('ChapterModel', 'Partmodel','Appendixmodel','Schedulemodel','PriliminaryModel','MainOrderModel')->where('section_id', $id)->first();
        $subsec = Section::where('section_id', $id)
            ->with(['subsectionModel', 'footnoteModel' => function ($query) {
                $query->whereNull('sub_section_id');
            }])
            ->get();

        $sub_section_f = SubSection::where('section_id', $id)->with('footnoteModel')->get();

        $count = 0;

        if ($sub_section_f) {
            foreach ($sub_section_f as $sub_section) {
                $count += $sub_section->footnoteModel->count();
            }
        }

        $currentPage = $request->page;
        return view('admin.section.edit', compact('sections', 'subsec', 'sub_section_f', 'count','currentPage'));
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
            if (!$request->has('section_id')) {
                return redirect()->route('edit-section', ['id' => $id])->withErrors(['error' => 'Section ID is missing']);
            }

            $sections = Section::find($request->section_id);

            // Check if the section is found
            if (!$sections) {
                return redirect()->route('edit-section', ['id' => $id])->withErrors(['error' => 'Section not found']);
            }
            if ($sections) {
                $sections->section_content = $request->section_content ?? null;
                $sections->section_title = $request->section_title ?? null;
                $sections->section_no = $request->section_no ?? null;
                $sections->update();


                if ($request->has('sec_footnote_content')) {
                    foreach ($request->sec_footnote_content as $key => $items) {
                        // Check if the key exists before using it
                        foreach ($items as $kys => $item) {
                            // Check if the sec_footnote_id exists at the specified index
                            if (isset($request->sec_footnote_id[$key][$kys])) {
                                // Use first() instead of get() to get a single model instance
                                $foot = Footnote::find($request->sec_footnote_id[$key][$kys]);

                                if ($foot) {
                                    $foot->update([
                                        'footnote_content' => $item ?? null,
                                        'footnote_no' => $request->sec_footnote_no[$key][$kys] ?? null,
                                    ]);
                                }
                            } else {
                                // Create a new footnote
                                $footnote = new Footnote();
                                $footnote->section_id = $id ?? null;
                                $footnote->section_no = $sections->section_no ?? null;
                                $footnote->act_id = $sections->act_id ?? null;
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

            if ($request->has('sub_section_no')) {

                foreach ($request->sub_section_no as $key => $item) {
                    // Check if sub_section_id is present in the request
                    if ($request->filled('sub_section_id') && is_array($request->sub_section_id) && array_key_exists($key, $request->sub_section_id)) {

                        $sub_section = SubSection::find($request->sub_section_id[$key]);

                        // Check if $sub_section is found in the database and the IDs match
                        if ($sub_section && $sub_section->sub_section_id == $request->sub_section_id[$key]) {
                            $sub_section->sub_section_no = $item ?? null;
                            $sub_section->sub_section_content = $request->sub_section_content[$key] ?? null;
                            $sub_section->update();

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
                                        $footnote->sub_section_id = $sub_section->sub_section_id;
                                        $footnote->section_id = $id ?? null;
                                        $footnote->act_id = $sections->act_id ?? null;
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
                    } else {
                        // Existing subsection not found, create a new one
                        $subsec = new SubSection();
                        $subsec->section_id = $id ?? null;
                        $subsec->sub_section_no = $item ?? null;
                        $subsec->section_no = $sections->section_no ?? null;
                        $subsec->act_id = $sections->act_id ?? null;
                        $subsec->chapter_id = $part->chapter_id ?? null;
                        $subsec->main_order_id = $part->main_order_id ?? null;
                        $subsec->parts_id = $part->parts_id ?? null;
                        $subsec->priliminary_id = $part->priliminary_id ?? null;
                        $subsec->schedule_id = $part->schedule_id ?? null;
                        $subsec->appendix_id = $part->appendix_id ?? null;
                        $subsec->sub_section_content = $request->sub_section_content[$key] ?? null;
                        $subsec->save();

                        if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                            foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                                // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                                if (isset($request->sub_footnote_no[$key][$kys], $request->sub_footnote_content[$key][$kys])) { 
                                    // Create a new footnote for the newly created subsection
                                    $footnote = new Footnote();
                                    $footnote->sub_section_id = $subsec->sub_section_id;
                                    $footnote->section_id = $id ?? null;
                                    $footnote->act_id = $sections->act_id ?? null;
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



            return redirect()->route('get_act_section', ['id' => $sections->act_id,'page' => $currentPage])->with('success', 'Section updated successfully');
        } catch (\Exception $e) {
            \Log::error('Error updating Act: ' . $e->getMessage());
            return redirect()->route('edit-section', ['id' => $id])->withErrors(['error' => 'Failed to update Section. Please try again.' . $e->getMessage()]);
        }
    }

    public function view_sub_section(Request $request,  $id)
    {
        $section = Section::where('section_id', $id)->first();
        $sub_section = SubSection::where('section_id', $id)->with('footnoteModel')->get();
        $currentPage = $request->page;
        return view('admin.section.view', compact('section','sub_section','currentPage'));
    }

    public function destroy_sub_section(string $id)
    {
        try {
            $subsection = SubSection::find($id);

            if (!$subsection) {
                return redirect()->back()->withErrors(['error' => 'Sub-Section not found.']);
            }
            
            Footnote::where('sub_section_id', $id)->delete();

            $subsection->delete();

            return redirect()->back()->with('success', 'Sub-Section and related records deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting Sub-Section: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete Sub-Section. Please try again.' . $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            $section = Section::find($id);

            if (!$section) {
                return redirect()->back()->withErrors(['error' => 'Section not found.']);
            }
            
            SubSection::where('section_id', $id)->delete();
            Footnote::where('section_id', $id)->delete();

            $section->delete();

            return redirect()->back()->with('success', 'Section and related records deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting section: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete section. Please try again.' . $e->getMessage()]);
        }
    }
    
}
