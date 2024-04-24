<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\Chapter;
use App\Models\Footnote;
use App\Models\MainType;
use App\Models\Parts;
use App\Models\PartsType;
use App\Models\Priliminary;
use App\Models\Rules;
use App\Models\Schedule;
use App\Models\Regulation;
use App\Models\Section;
use App\Models\SubSection;
use App\Models\Article;
use App\Models\Appendices;
use App\Models\SubType;
use App\Models\Lists;
use App\Models\Part;
use App\Models\SubPart;
use App\Models\Appendix;
use App\Models\MainOrder;
use App\Models\SubAppendix;
use App\Models\Orders;
use App\Models\SubOrders;
use App\Models\Annexure;
use App\Models\SubAnnexure;
use App\Models\Stschedule;
use App\Models\SubStschedule;
use App\Models\MainTable;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MainActController extends Controller
{

    public function show()
    {
        try {
            $act = Act::all();
            return response()->json([
                'status' => 200,
                'data' =>   [
                    'act' => $act,
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => 'Resource not found.' . $e->getMessage(),
                'data' => null
            ]);
        }
    }

    public function index(Request $request, $id)
    {
        try {

            $act = Act::findOrFail($id);
            $type = MainType::all();
           

            // Sort the combined items by their serial_no
            // ksort($combinedItems);

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

           
            $sideBarList = [];

            foreach($combinedItems as $item) {
                if(isset($item['parts_id'])) {
                    $Data = [
                        'PartsId' => $item['parts_id'],
                        'Name' => $item['parts_title'],
                        'SubString' => [] // Array to store sections for the current chapter
                    ];
            
                    if(!empty($item['sections'])){
                        foreach ($item['sections'] as $section) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'SectionId' => $section['section_id'],
                                'SectionNo' => $section['section_no'],
                                'SectionName' => $section['section_title'],
                            ];
                        }        
                    }

                    if(!empty($item['articles'])){
                        foreach ($item['articles'] as $article) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'ArticleId' => $article['article_id'],
                                'ArticleNo' => $article['article_no'],
                                'ArticleName' => $article['article_title'],
                            ];
                        }        
                    }

                    if(!empty($item['rules'])){
                        foreach ($item['rules'] as $rule) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'RuleId' => $rule['rule_id'],
                                'RuleNo' => $rule['rule_no'],
                                'RuleName' => $rule['rule_title'],
                            ];
                        }        
                    }

                    if(!empty($item['regulation'])){
                        foreach ($item['regulation'] as $regulation) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'RegulationId' => $regulation['regulation_id'],
                                'RegulationNo' => $regulation['regulation_no'],
                                'RegulationName' => $regulation['regulation_title'],
                            ];
                        }        
                    }

                    if(!empty($item['lists'])){
                        foreach ($item['lists'] as $list) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'ListId' => $list['list_id'],
                                'ListNo' => $list['list_no'],
                                'ListName' => $list['list_title'],
                            ];
                        }        
                    }

                    if(!empty($item['part'])){
                        foreach ($item['part'] as $part) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'PartId' => $part['part_id'],
                                'PartNo' => $part['part_no'],
                                'PartName' => $part['part_title'],
                            ];
                        }        
                    }

                    if(!empty($item['appendices'])){
                        foreach ($item['appendices'] as $appedices) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'AppedicesId' => $appedices['appendices_id'],
                                'AppedicesNo' => $appedices['appendices_no'],
                                'AppedicesName' => $appedices['appendices_title'],
                            ];
                        }        
                    }

                    if(!empty($item['order'])){
                        foreach ($item['order'] as $order) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'OrderId' => $order['order_id'],
                                'OrderNo' => $order['order_no'],
                                'OrderName' => $order['order_title'],
                            ];
                        }        
                    }

                    if(!empty($item['annexure'])){
                        foreach ($item['annexure'] as $annexure) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'AnnexureId' => $annexure['annexure_id'],
                                'AnnexureNo' => $annexure['annexure_no'],
                                'AnnexureName' => $annexure['annexure_title'],
                            ];
                        }        
                    }

                    if(!empty($item['stschedule'])){
                        foreach ($item['stschedule'] as $stschedule) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'StscheduleId' => $stschedule['stschedule_id'],
                                'StscheduleNo' => $stschedule['stschedule_no'],
                                'StscheduleName' => $stschedule['stschedule_title'],
                            ];
                        }        
                    }

                    $sideBarList[] = $Data; // corrected variable name
                    
                }

                if(isset($item['chapter_id'])) {
                    $Data = [
                        'ChapterId' => $item['chapter_id'],
                        'Name' => $item['chapter_title'],
                        'SubString' => [] // Array to store sections for the current chapter
                    ];
            
                    if(!empty($item['sections'])){
                        foreach ($item['sections'] as $section) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'SectionId' => $section['section_id'],
                                'SectionNo' => $section['section_no'],
                                'SectionName' => $section['section_title'],
                            ];
                        }        
                    }

                    if(!empty($item['articles'])){
                        foreach ($item['articles'] as $article) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'ArticleId' => $article['article_id'],
                                'ArticleNo' => $article['article_no'],
                                'ArticleName' => $article['article_title'],
                            ];
                        }        
                    }

                    if(!empty($item['rules'])){
                        foreach ($item['rules'] as $rule) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'RuleId' => $rule['rule_id'],
                                'RuleNo' => $rule['rule_no'],
                                'RuleName' => $rule['rule_title'],
                            ];
                        }        
                    }

                    if(!empty($item['regulation'])){
                        foreach ($item['regulation'] as $regulation) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'RegulationId' => $regulation['regulation_id'],
                                'RegulationNo' => $regulation['regulation_no'],
                                'RegulationName' => $regulation['regulation_title'],
                            ];
                        }        
                    }

                    if(!empty($item['lists'])){
                        foreach ($item['lists'] as $list) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'ListId' => $list['list_id'],
                                'ListNo' => $list['list_no'],
                                'ListName' => $list['list_title'],
                            ];
                        }        
                    }

                    if(!empty($item['part'])){
                        foreach ($item['part'] as $part) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'PartId' => $part['part_id'],
                                'PartNo' => $part['part_no'],
                                'PartName' => $part['part_title'],
                            ];
                        }        
                    }

                    if(!empty($item['appendices'])){
                        foreach ($item['appendices'] as $appedices) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'AppedicesId' => $appedices['appendices_id'],
                                'AppedicesNo' => $appedices['appendices_no'],
                                'AppedicesName' => $appedices['appendices_title'],
                            ];
                        }        
                    }

                    if(!empty($item['order'])){
                        foreach ($item['order'] as $order) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'OrderId' => $order['order_id'],
                                'OrderNo' => $order['order_no'],
                                'OrderName' => $order['order_title'],
                            ];
                        }        
                    }

                    if(!empty($item['annexure'])){
                        foreach ($item['annexure'] as $annexure) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'AnnexureId' => $annexure['annexure_id'],
                                'AnnexureNo' => $annexure['annexure_no'],
                                'AnnexureName' => $annexure['annexure_title'],
                            ];
                        }        
                    }

                    if(!empty($item['stschedule'])){
                        foreach ($item['stschedule'] as $stschedule) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'StscheduleId' => $stschedule['stschedule_id'],
                                'StscheduleNo' => $stschedule['stschedule_no'],
                                'StscheduleName' => $stschedule['stschedule_title'],
                            ];
                        }        
                    }

                    $sideBarList[] = $Data; // corrected variable name
                    
                }

                if(isset($item['priliminary_id'])) {
                    $Data = [
                        'PrilimiaryId' => $item['priliminary_id'],
                        'Name' => $item['priliminary_title'],
                        'SubString' => [] // Array to store sections for the current chapter
                    ];
            
                    if(!empty($item['sections'])){
                        foreach ($item['sections'] as $section) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'SectionId' => $section['section_id'],
                                'SectionNo' => $section['section_no'],
                                'SectionName' => $section['section_title'],
                            ];
                        }        
                    }

                    if(!empty($item['articles'])){
                        foreach ($item['articles'] as $article) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'ArticleId' => $article['article_id'],
                                'ArticleNo' => $article['article_no'],
                                'ArticleName' => $article['article_title'],
                            ];
                        }        
                    }

                    if(!empty($item['rules'])){
                        foreach ($item['rules'] as $rule) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'RuleId' => $rule['rule_id'],
                                'RuleNo' => $rule['rule_no'],
                                'RuleName' => $rule['rule_title'],
                            ];
                        }        
                    }

                    if(!empty($item['regulation'])){
                        foreach ($item['regulation'] as $regulation) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'RegulationId' => $regulation['regulation_id'],
                                'RegulationNo' => $regulation['regulation_no'],
                                'RegulationName' => $regulation['regulation_title'],
                            ];
                        }        
                    }

                    if(!empty($item['lists'])){
                        foreach ($item['lists'] as $list) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'ListId' => $list['list_id'],
                                'ListNo' => $list['list_no'],
                                'ListName' => $list['list_title'],
                            ];
                        }        
                    }

                    if(!empty($item['part'])){
                        foreach ($item['part'] as $part) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'PartId' => $part['part_id'],
                                'PartNo' => $part['part_no'],
                                'PartName' => $part['part_title'],
                            ];
                        }        
                    }

                    if(!empty($item['appendices'])){
                        foreach ($item['appendices'] as $appedices) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'AppedicesId' => $appedices['appendices_id'],
                                'AppedicesNo' => $appedices['appendices_no'],
                                'AppedicesName' => $appedices['appendices_title'],
                            ];
                        }        
                    }

                    if(!empty($item['order'])){
                        foreach ($item['order'] as $order) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'OrderId' => $order['order_id'],
                                'OrderNo' => $order['order_no'],
                                'OrderName' => $order['order_title'],
                            ];
                        }        
                    }

                    if(!empty($item['annexure'])){
                        foreach ($item['annexure'] as $annexure) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'AnnexureId' => $annexure['annexure_id'],
                                'AnnexureNo' => $annexure['annexure_no'],
                                'AnnexureName' => $annexure['annexure_title'],
                            ];
                        }        
                    }

                    if(!empty($item['stschedule'])){
                        foreach ($item['stschedule'] as $stschedule) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'StscheduleId' => $stschedule['stschedule_id'],
                                'StscheduleNo' => $stschedule['stschedule_no'],
                                'StscheduleName' => $stschedule['stschedule_title'],
                            ];
                        }        
                    }

                    $sideBarList[] = $Data; // corrected variable name
                    
                }

                if(isset($item['schedule_id'])) {
                    $Data = [
                        'ScheduleId' => $item['schedule_id'],
                        'Name' => $item['schedule_title'],
                        'SubString' => [] // Array to store sections for the current chapter
                    ];
            
                    if(!empty($item['sections'])){
                        foreach ($item['sections'] as $section) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'SectionId' => $section['section_id'],
                                'SectionNo' => $section['section_no'],
                                'SectionName' => $section['section_title'],
                            ];
                        }        
                    }

                    if(!empty($item['articles'])){
                        foreach ($item['articles'] as $article) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'ArticleId' => $article['article_id'],
                                'ArticleNo' => $article['article_no'],
                                'ArticleName' => $article['article_title'],
                            ];
                        }        
                    }

                    if(!empty($item['rules'])){
                        foreach ($item['rules'] as $rule) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'RuleId' => $rule['rule_id'],
                                'RuleNo' => $rule['rule_no'],
                                'RuleName' => $rule['rule_title'],
                            ];
                        }        
                    }

                    if(!empty($item['regulation'])){
                        foreach ($item['regulation'] as $regulation) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'RegulationId' => $regulation['regulation_id'],
                                'RegulationNo' => $regulation['regulation_no'],
                                'RegulationName' => $regulation['regulation_title'],
                            ];
                        }        
                    }

                    if(!empty($item['lists'])){
                        foreach ($item['lists'] as $list) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'ListId' => $list['list_id'],
                                'ListNo' => $list['list_no'],
                                'ListName' => $list['list_title'],
                            ];
                        }        
                    }

                    if(!empty($item['part'])){
                        foreach ($item['part'] as $part) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'PartId' => $part['part_id'],
                                'PartNo' => $part['part_no'],
                                'PartName' => $part['part_title'],
                            ];
                        }        
                    }

                    if(!empty($item['appendices'])){
                        foreach ($item['appendices'] as $appedices) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'AppedicesId' => $appedices['appendices_id'],
                                'AppedicesNo' => $appedices['appendices_no'],
                                'AppedicesName' => $appedices['appendices_title'],
                            ];
                        }        
                    }

                    if(!empty($item['order'])){
                        foreach ($item['order'] as $order) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'OrderId' => $order['order_id'],
                                'OrderNo' => $order['order_no'],
                                'OrderName' => $order['order_title'],
                            ];
                        }        
                    }

                    if(!empty($item['annexure'])){
                        foreach ($item['annexure'] as $annexure) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'AnnexureId' => $annexure['annexure_id'],
                                'AnnexureNo' => $annexure['annexure_no'],
                                'AnnexureName' => $annexure['annexure_title'],
                            ];
                        }        
                    }

                    if(!empty($item['stschedule'])){
                        foreach ($item['stschedule'] as $stschedule) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'StscheduleId' => $stschedule['stschedule_id'],
                                'StscheduleNo' => $stschedule['stschedule_no'],
                                'StscheduleName' => $stschedule['stschedule_title'],
                            ];
                        }        
                    }

                    $sideBarList[] = $Data; // corrected variable name
                    
                }

                if(isset($item['appendix_id'])) {
                    $Data = [
                        'AppendixId' => $item['appendix_id'],
                        'Name' => $item['appendix_title'],
                        'SubString' => [] // Array to store sections for the current chapter
                    ];
            
                    if(!empty($item['sections'])){
                        foreach ($item['sections'] as $section) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'SectionId' => $section['section_id'],
                                'SectionNo' => $section['section_no'],
                                'SectionName' => $section['section_title'],
                            ];
                        }        
                    }

                    if(!empty($item['articles'])){
                        foreach ($item['articles'] as $article) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'ArticleId' => $article['article_id'],
                                'ArticleNo' => $article['article_no'],
                                'ArticleName' => $article['article_title'],
                            ];
                        }        
                    }

                    if(!empty($item['rules'])){
                        foreach ($item['rules'] as $rule) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'RuleId' => $rule['rule_id'],
                                'RuleNo' => $rule['rule_no'],
                                'RuleName' => $rule['rule_title'],
                            ];
                        }        
                    }

                    if(!empty($item['regulation'])){
                        foreach ($item['regulation'] as $regulation) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'RegulationId' => $regulation['regulation_id'],
                                'RegulationNo' => $regulation['regulation_no'],
                                'RegulationName' => $regulation['regulation_title'],
                            ];
                        }        
                    }

                    if(!empty($item['lists'])){
                        foreach ($item['lists'] as $list) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'ListId' => $list['list_id'],
                                'ListNo' => $list['list_no'],
                                'ListName' => $list['list_title'],
                            ];
                        }        
                    }

                    if(!empty($item['part'])){
                        foreach ($item['part'] as $part) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'PartId' => $part['part_id'],
                                'PartNo' => $part['part_no'],
                                'PartName' => $part['part_title'],
                            ];
                        }        
                    }

                    if(!empty($item['appendices'])){
                        foreach ($item['appendices'] as $appedices) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'AppedicesId' => $appedices['appendices_id'],
                                'AppedicesNo' => $appedices['appendices_no'],
                                'AppedicesName' => $appedices['appendices_title'],
                            ];
                        }        
                    }

                    if(!empty($item['order'])){
                        foreach ($item['order'] as $order) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'OrderId' => $order['order_id'],
                                'OrderNo' => $order['order_no'],
                                'OrderName' => $order['order_title'],
                            ];
                        }        
                    }

                    if(!empty($item['annexure'])){
                        foreach ($item['annexure'] as $annexure) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'AnnexureId' => $annexure['annexure_id'],
                                'AnnexureNo' => $annexure['annexure_no'],
                                'AnnexureName' => $annexure['annexure_title'],
                            ];
                        }        
                    }

                    if(!empty($item['stschedule'])){
                        foreach ($item['stschedule'] as $stschedule) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'StscheduleId' => $stschedule['stschedule_id'],
                                'StscheduleNo' => $stschedule['stschedule_no'],
                                'StscheduleName' => $stschedule['stschedule_title'],
                            ];
                        }        
                    }

                    $sideBarList[] = $Data; // corrected variable name
                    
                }

                if(isset($item['main_order_id'])) {
                    $Data = [
                        'MainOrderId' => $item['main_order_id'],
                        'Name' => $item['main_order_title'],
                        'SubString' => [] // Array to store sections for the current chapter
                    ];
            
                    if(!empty($item['sections'])){
                        foreach ($item['sections'] as $section) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'SectionId' => $section['section_id'],
                                'SectionNo' => $section['section_no'],
                                'SectionName' => $section['section_title'],
                            ];
                        }        
                    }

                    if(!empty($item['articles'])){
                        foreach ($item['articles'] as $article) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'ArticleId' => $article['article_id'],
                                'ArticleNo' => $article['article_no'],
                                'ArticleName' => $article['article_title'],
                            ];
                        }        
                    }

                    if(!empty($item['rules'])){
                        foreach ($item['rules'] as $rule) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'RuleId' => $rule['rule_id'],
                                'RuleNo' => $rule['rule_no'],
                                'RuleName' => $rule['rule_title'],
                            ];
                        }        
                    }

                    if(!empty($item['regulation'])){
                        foreach ($item['regulation'] as $regulation) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'RegulationId' => $regulation['regulation_id'],
                                'RegulationNo' => $regulation['regulation_no'],
                                'RegulationName' => $regulation['regulation_title'],
                            ];
                        }        
                    }

                    if(!empty($item['lists'])){
                        foreach ($item['lists'] as $list) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'ListId' => $list['list_id'],
                                'ListNo' => $list['list_no'],
                                'ListName' => $list['list_title'],
                            ];
                        }        
                    }

                    if(!empty($item['part'])){
                        foreach ($item['part'] as $part) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'PartId' => $part['part_id'],
                                'PartNo' => $part['part_no'],
                                'PartName' => $part['part_title'],
                            ];
                        }        
                    }

                    if(!empty($item['appendices'])){
                        foreach ($item['appendices'] as $appedices) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'AppedicesId' => $appedices['appendices_id'],
                                'AppedicesNo' => $appedices['appendices_no'],
                                'AppedicesName' => $appedices['appendices_title'],
                            ];
                        }        
                    }

                    if(!empty($item['order'])){
                        foreach ($item['order'] as $order) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'OrderId' => $order['order_id'],
                                'OrderNo' => $order['order_no'],
                                'OrderName' => $order['order_title'],
                            ];
                        }        
                    }

                    if(!empty($item['annexure'])){
                        foreach ($item['annexure'] as $annexure) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'AnnexureId' => $annexure['annexure_id'],
                                'AnnexureNo' => $annexure['annexure_no'],
                                'AnnexureName' => $annexure['annexure_title'],
                            ];
                        }        
                    }

                    if(!empty($item['stschedule'])){
                        foreach ($item['stschedule'] as $stschedule) { // assuming sections are directly nested in $item
                            $Data['SubString'][] = [
                                'StscheduleId' => $stschedule['stschedule_id'],
                                'StscheduleNo' => $stschedule['stschedule_no'],
                                'StscheduleName' => $stschedule['stschedule_title'],
                            ];
                        }        
                    }

                    $sideBarList[] = $Data; // corrected variable name
                    
                }
            }

            // dd($sideBarList);
            // die();
           
           

            $MainList = [];
            foreach ($combinedItems as $item) {
               
                if (isset($item['parts_id'])) {
            
                    $Data = [];
                    if (!empty($item['sections'])) {
                         foreach ($item['sections'] as $section) {
                            $subSectionsList = [];
                            if (!empty($section['subsection_model'])) {
                                foreach ($section['subsection_model'] as $subsection) {
                                    $subSectionsList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subsection['sub_section_no'] . '</div><div>' . $subsection['sub_section_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($section['footnote_model'])) {
                                foreach ($section['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subSectionString = implode('', $subSectionsList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $sectionCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $sectionHtml = '<div id="' . $section['section_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $section['section_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $section['section_title'] . '</h4></div></br><div>' . $section['section_content'] . '</div><div>' . $subSectionString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $sectionCss . $sectionHtml;
                        }
                    }
                
                    if(!empty($item['articles'])){
                        foreach ($item['articles'] as $article) {
                            $subArticleList = [];
                            if (!empty($article['sub_article_model'])) {
                                foreach ($article['sub_article_model'] as $subarticle) {
                                    $subArticleList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subarticle['sub_article_no'] . '</div><div>' . $subarticle['sub_article_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($article['footnote_model'])) {
                                foreach ($article['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subArticleString = implode('', $subArticleList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $articleCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $articleHtml = '<div id="' . $article['article_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $article['article_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $article['article_title'] . '</h4></div></br><div>' . $article['article_content'] . '</div><div>' . $subArticleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $articleCss . $articleHtml;
                        }
                    }

                    if(!empty($item['rules'])){
                        
                        foreach ($item['rules'] as $rule) {
                            $subRuleList = [];
                            if (!empty($rule['subrule_model'])) {
                                foreach ($rule['subrule_model'] as $subrule) {
                                    $subRuleList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subrule['sub_rule_no'] . '</div><div>' . $subrule['sub_rule_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($rule['footnote_model'])) {
                                foreach ($rule['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subRuleString = implode('', $subRuleList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $ruleCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $ruleHtml = '<div id="' . $rule['rule_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $rule['rule_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $rule['rule_title'] . '</h4></div></br><div>' . $rule['rule_content'] . '</div><div>' . $subRuleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $ruleCss . $ruleHtml;
                        }
                    }

                    if(!empty($item['regulation'])){
                        foreach ($item['regulation'] as $regulation) {
                            $subRegulationList = [];
                            if (!empty($regulation['sub_regulation_model'])) {
                                foreach ($regulation['sub_regulation_model'] as $subRegulation) {
                                    $subRegulationList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subRegulation['sub_regulation_no'] . '</div><div>' . $subRegulation['sub_regulation_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($regulation['footnote_model'])) {
                                foreach ($regulation['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subRegulationString = implode('', $subRegulationList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $regulationCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $regulationHtml = '<div id="' . $regulation['regulation_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $regulation['regulation_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $regulation['regulation_title'] . '</h4></div></br><div>' . $regulation['regulation_content'] . '</div><div>' . $subRegulationString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $regulationCss . $regulationHtml;
                        }
                    }

                    if(!empty($item['lists'])){
                        
                        foreach ($item['lists'] as $list) {
                            $subListList = [];
                            if (!empty($list['sub_list_model'])) {
                                foreach ($list['sub_list_model'] as $subList) {
                                    $subListList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subList['sub_list_no'] . '</div><div>' . $subList['sub_list_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($list['footnote_model'])) {
                                foreach ($list['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subListString = implode('', $subListList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $listCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $listHtml = '<div id="' . $list['list_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $list['list_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $list['list_title'] . '</h4></div></br><div>' . $list['list_content'] . '</div><div>' . $subListString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $listCss . $listHtml;
                        }
                    }

                    if(!empty($item['part'])){
                        foreach ($item['part'] as $part) {
                            $subPartList = [];
                            if (!empty($part['sub_part_model'])) {
                                foreach ($part['sub_part_model'] as $subPartsOfPart) {
                                    $subPartList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subPartsOfPart['sub_part_no'] . '</div><div>' . $subPartsOfPart['sub_part_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($part['footnote_model'])) {
                                foreach ($part['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subPartString = implode('', $subPartList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $partCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $partHtml = '<div id="' . $part['part_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $part['part_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $part['part_title'] . '</h4></div></br><div>' . $part['part_content'] . '</div><div>' . $subPartString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $partCss . $partHtml;
                        }
                    }

                    if(!empty($item['appendices'])){
                        foreach ($item['appendices'] as $appendices) {
                            $subAppendiceList = [];
                            if (!empty($appendices['sub_appendices_model'])) {
                                foreach ($appendices['sub_appendices_model'] as $subAppendice) {
                                    $subAppendiceList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subAppendice['sub_appendices_no'] . '</div><div>' . $subAppendice['sub_appendices_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($appendices['footnote_model'])) {
                                foreach ($appendices['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subAppendiceString = implode('', $subAppendiceList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $appendicesCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $appendicesHtml = '<div id="' . $appendices['appendices_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $appendices['appendices_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $appendices['appendices_title'] . '</h4></div></br><div>' . $appendices['appendices_content'] . '</div><div>' . $subAppendiceString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $appendicesCss . $appendicesHtml;
                        }        
                    }

                    if(!empty($item['order'])){
                       
                        foreach ($item['order'] as $order) {
                            $subOrderList = [];
                            if (!empty($order['sub_order_model'])) {
                                foreach ($order['sub_order_model'] as $subOrder) {
                                    $subOrderList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subOrder['sub_order_no'] . '</div><div>' . $subOrder['sub_order_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($order['footnote_model'])) {
                                foreach ($order['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subOrderString = implode('', $subOrderList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $orderCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $orderHtml = '<div id="' . $order['order_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $order['order_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $order['order_title'] . '</h4></div></br><div>' . $order['order_content'] . '</div><div>' . $subOrderString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $orderCss . $orderHtml;
                        } 
                    }

                    if(!empty($item['annexure'])){
                        
                        foreach ($item['annexure'] as $annexure) {
                            $subAnnexureList = [];
                            if (!empty($annexure['sub_annexure_model'])) {
                                foreach ($annexure['sub_annexure_model'] as $subAnnexure) {
                                    $subAnnexureList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subAnnexure['sub_annexure_no'] . '</div><div>' . $subAnnexure['sub_annexure_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($annexure['footnote_model'])) {
                                foreach ($annexure['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subAnnexureString = implode('', $subAnnexureList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $annexureCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $annexureHtml = '<div id="' . $annexure['annexure_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $annexure['annexure_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $annexure['annexure_title'] . '</h4></div></br><div>' . $annexure['annexure_content'] . '</div><div>' . $subAnnexureString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $annexureCss . $annexureHtml;
                        } 
                    }
                   
                    if(!empty($item['stschedule'])){
                         
                        foreach ($item['stschedule'] as $stschedule) {
                            $subStscheduleList = [];
                            if (!empty($stschedule['sub_stschedule_model'])) {
                                foreach ($stschedule['sub_stschedule_model'] as $subStschedule) {
                                    $subStscheduleList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subStschedule['sub_stschedule_no'] . '</div><div>' . $subStschedule['sub_stschedule_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($stschedule['footnote_model'])) {
                                foreach ($stschedule['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subStscheduleString = implode('', $subStscheduleList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $stscheduleCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $stscheduleHtml = '<div id="' . $stschedule['stschedule_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $stschedule['stschedule_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $stschedule['stschedule_title'] . '</h4></div></br><div>' . $stschedule['stschedule_content'] . '</div><div>' . $subStscheduleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $stscheduleCss . $stscheduleHtml;
                        } 
                    }

                    $sectionString = implode('', $Data);
                    $MainList[] = '<h2 style="text-align:center!important;" id=""><strong>' . $item['parts_title'] . '</strong></h2>'. $sectionString;
                            
                }

                if (isset($item['chapter_id'])) {
            
                    $Data = [];
                    if (!empty($item['sections'])) {
                        foreach ($item['sections'] as $section) {
                            $subSectionsList = [];
                            if (!empty($section['subsection_model'])) {
                                foreach ($section['subsection_model'] as $subsection) {
                                    $subSectionsList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subsection['sub_section_no'] . '</div><div>' . $subsection['sub_section_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($section['footnote_model'])) {
                                foreach ($section['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subSectionString = implode('', $subSectionsList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $sectionCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $sectionHtml = '<div id="' . $section['section_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $section['section_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $section['section_title'] . '</h4></div></br><div>' . $section['section_content'] . '</div><div>' . $subSectionString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $sectionCss . $sectionHtml;
                        }
                    }
                
                    if(!empty($item['articles'])){
                        foreach ($item['articles'] as $article) {
                            $subArticleList = [];
                            if (!empty($article['sub_article_model'])) {
                                foreach ($article['sub_article_model'] as $subarticle) {
                                    $subArticleList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subarticle['sub_article_no'] . '</div><div>' . $subarticle['sub_article_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($article['footnote_model'])) {
                                foreach ($article['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subArticleString = implode('', $subArticleList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $articleCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $articleHtml = '<div id="' . $article['article_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $article['article_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $article['article_title'] . '</h4></div></br><div>' . $article['article_content'] . '</div><div>' . $subArticleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $articleCss . $articleHtml;
                        }        
                    }

                    if(!empty($item['rules'])){
                        foreach ($item['rules'] as $rule) {
                            $subRuleList = [];
                            if (!empty($rule['subrule_model'])) {
                                foreach ($rule['subrule_model'] as $subrule) {
                                    $subRuleList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subrule['sub_rule_no'] . '</div><div>' . $subrule['sub_rule_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($rule['footnote_model'])) {
                                foreach ($rule['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subRuleString = implode('', $subRuleList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $ruleCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $ruleHtml = '<div id="' . $rule['rule_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $rule['rule_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $rule['rule_title'] . '</h4></div></br><div>' . $rule['rule_content'] . '</div><div>' . $subRuleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $ruleCss . $ruleHtml;
                        }       
                    }

                    if(!empty($item['regulation'])){
                        foreach ($item['regulation'] as $regulation) {
                            $subRegulationList = [];
                            if (!empty($regulation['sub_regulation_model'])) {
                                foreach ($regulation['sub_regulation_model'] as $subRegulation) {
                                    $subRegulationList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subRegulation['sub_regulation_no'] . '</div><div>' . $subRegulation['sub_regulation_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($regulation['footnote_model'])) {
                                foreach ($regulation['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subRegulationString = implode('', $subRegulationList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $regulationCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $regulationHtml = '<div id="' . $regulation['regulation_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $regulation['regulation_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $regulation['regulation_title'] . '</h4></div></br><div>' . $regulation['regulation_content'] . '</div><div>' . $subRegulationString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $regulationCss . $regulationHtml;
                        }        
                    }

                    if(!empty($item['lists'])){
                        foreach ($item['lists'] as $list) {
                            $subListList = [];
                            if (!empty($list['
                            '])) {
                                foreach ($list['sub_list_model'] as $subList) {
                                    $subListList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subList['sub_list_no'] . '</div><div>' . $subList['sub_list_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($list['footnote_model'])) {
                                foreach ($list['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subListString = implode('', $subListList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $listCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $listHtml = '<div id="' . $list['list_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $list['list_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $list['list_title'] . '</h4></div></br><div>' . $list['list_content'] . '</div><div>' . $subListString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $listCss . $listHtml;
                        }       
                    }

                    if(!empty($item['part'])){
                        foreach ($item['part'] as $part) {
                            $subPartList = [];
                            if (!empty($part['sub_part_model'])) {
                                foreach ($part['sub_part_model'] as $subPartsOfPart) {
                                    $subPartList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subPartsOfPart['sub_part_no'] . '</div><div>' . $subPartsOfPart['sub_part_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($part['footnote_model'])) {
                                foreach ($part['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subPartString = implode('', $subPartList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $partCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $partHtml = '<div id="' . $part['part_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $part['part_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $part['part_title'] . '</h4></div></br><div>' . $part['part_content'] . '</div><div>' . $subPartString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $partCss . $partHtml;
                        }       
                    }

                    if(!empty($item['appendices'])){
                        foreach ($item['appendices'] as $appendices) {
                            $subAppendiceList = [];
                            if (!empty($appendices['sub_appendices_model'])) {
                                foreach ($appendices['sub_appendices_model'] as $subAppendice) {
                                    $subAppendiceList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subAppendice['sub_appendices_no'] . '</div><div>' . $subAppendice['sub_appendices_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($appendices['footnote_model'])) {
                                foreach ($appendices['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subAppendiceString = implode('', $subAppendiceList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $appendicesCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $appendicesHtml = '<div id="' . $appendices['appendices_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $appendices['appendices_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $appendices['appendices_title'] . '</h4></div></br><div>' . $appendices['appendices_content'] . '</div><div>' . $subAppendiceString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $appendicesCss . $appendicesHtml;
                        }        
                    }

                    if(!empty($item['order'])){
                        foreach ($item['order'] as $order) {
                            $subOrderList = [];
                            if (!empty($order['sub_order_model'])) {
                                foreach ($order['sub_order_model'] as $subOrder) {
                                    $subOrderList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subOrder['sub_order_no'] . '</div><div>' . $subOrder['sub_order_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($order['footnote_model'])) {
                                foreach ($order['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subOrderString = implode('', $subOrderList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $orderCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $orderHtml = '<div id="' . $order['order_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $order['order_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $order['order_title'] . '</h4></div></br><div>' . $order['order_content'] . '</div><div>' . $subOrderString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $orderCss . $orderHtml;
                        }         
                    }

                    if(!empty($item['annexure'])){
                        foreach ($item['annexure'] as $annexure) {
                            $subAnnexureList = [];
                            if (!empty($annexure['sub_annexure_model'])) {
                                foreach ($annexure['sub_annexure_model'] as $subAnnexure) {
                                    $subAnnexureList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subAnnexure['sub_annexure_no'] . '</div><div>' . $subAnnexure['sub_annexure_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($annexure['footnote_model'])) {
                                foreach ($annexure['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subAnnexureString = implode('', $subAnnexureList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $annexureCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $annexureHtml = '<div id="' . $annexure['annexure_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $annexure['annexure_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $annexure['annexure_title'] . '</h4></div></br><div>' . $annexure['annexure_content'] . '</div><div>' . $subAnnexureString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $annexureCss . $annexureHtml;
                        }        
                    }
                   
                    if(!empty($item['stschedule'])){
                        foreach ($item['stschedule'] as $stschedule) {
                            $subStscheduleList = [];
                            if (!empty($stschedule['sub_stschedule_model'])) {
                                foreach ($stschedule['sub_stschedule_model'] as $subStschedule) {
                                    $subStscheduleList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subStschedule['sub_stschedule_no'] . '</div><div>' . $subStschedule['sub_stschedule_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($stschedule['footnote_model'])) {
                                foreach ($stschedule['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subStscheduleString = implode('', $subStscheduleList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $stscheduleCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $stscheduleHtml = '<div id="' . $stschedule['stschedule_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $stschedule['stschedule_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $stschedule['stschedule_title'] . '</h4></div></br><div>' . $stschedule['stschedule_content'] . '</div><div>' . $subStscheduleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $stscheduleCss . $stscheduleHtml;
                        }        
                    }

                    $sectionString = implode('', $Data);
                    $MainList[] = '<h2 style="text-align:center!important;" id=""><strong>' . $item['chapter_title'] . '</strong></h2>'. $sectionString;
                            
                }

                if (isset($item['priliminary_id'])) {
            
                    $Data = [];
                    if (!empty($item['sections'])) {
                        foreach ($item['sections'] as $section) {
                            $subSectionsList = [];
                            if (!empty($section['subsection_model'])) {
                                foreach ($section['subsection_model'] as $subsection) {
                                    $subSectionsList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subsection['sub_section_no'] . '</div><div>' . $subsection['sub_section_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($section['footnote_model'])) {
                                foreach ($section['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subSectionString = implode('', $subSectionsList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $sectionCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $sectionHtml = '<div id="' . $section['section_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $section['section_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $section['section_title'] . '</h4></div></br><div>' . $section['section_content'] . '</div><div>' . $subSectionString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $sectionCss . $sectionHtml;
                        }
                    }
                
                    if(!empty($item['articles'])){
                        foreach ($item['articles'] as $article) {
                            $subArticleList = [];
                            if (!empty($article['sub_article_model'])) {
                                foreach ($article['sub_article_model'] as $subarticle) {
                                    $subArticleList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subarticle['sub_article_no'] . '</div><div>' . $subarticle['sub_article_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($article['footnote_model'])) {
                                foreach ($article['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subArticleString = implode('', $subArticleList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $articleCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $articleHtml = '<div id="' . $article['article_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $article['article_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $article['article_title'] . '</h4></div></br><div>' . $article['article_content'] . '</div><div>' . $subArticleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $articleCss . $articleHtml;
                        }       
                    }

                    if(!empty($item['rules'])){
                        foreach ($item['rules'] as $rule) {
                            $subRuleList = [];
                            if (!empty($rule['subrule_model'])) {
                                foreach ($rule['subrule_model'] as $subrule) {
                                    $subRuleList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subrule['sub_rule_no'] . '</div><div>' . $subrule['sub_rule_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($rule['footnote_model'])) {
                                foreach ($rule['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subRuleString = implode('', $subRuleList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $ruleCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $ruleHtml = '<div id="' . $rule['rule_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $rule['rule_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $rule['rule_title'] . '</h4></div></br><div>' . $rule['rule_content'] . '</div><div>' . $subRuleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $ruleCss . $ruleHtml;
                        }       
                    }

                    if(!empty($item['regulation'])){
                        foreach ($item['regulation'] as $regulation) {
                            $subRegulationList = [];
                            if (!empty($regulation['sub_regulation_model'])) {
                                foreach ($regulation['sub_regulation_model'] as $subRegulation) {
                                    $subRegulationList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subRegulation['sub_regulation_no'] . '</div><div>' . $subRegulation['sub_regulation_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($regulation['footnote_model'])) {
                                foreach ($regulation['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subRegulationString = implode('', $subRegulationList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $regulationCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $regulationHtml = '<div id="' . $regulation['regulation_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $regulation['regulation_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $regulation['regulation_title'] . '</h4></div></br><div>' . $regulation['regulation_content'] . '</div><div>' . $subRegulationString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $regulationCss . $regulationHtml;
                        }        
                    }

                    if(!empty($item['lists'])){
                        foreach ($item['lists'] as $list) {
                            $subListList = [];
                            if (!empty($list['sub_list_model'])) {
                                foreach ($list['sub_list_model'] as $subList) {
                                    $subListList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subList['sub_list_no'] . '</div><div>' . $subList['sub_list_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($list['footnote_model'])) {
                                foreach ($list['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subListString = implode('', $subListList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $listCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $listHtml = '<div id="' . $list['list_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $list['list_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $list['list_title'] . '</h4></div></br><div>' . $list['list_content'] . '</div><div>' . $subListString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $listCss . $listHtml;
                        }      
                    }

                    if(!empty($item['part'])){
                        foreach ($item['part'] as $part) {
                            $subPartList = [];
                            if (!empty($part['sub_part_model'])) {
                                foreach ($part['sub_part_model'] as $subPartsOfPart) {
                                    $subPartList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subPartsOfPart['sub_part_no'] . '</div><div>' . $subPartsOfPart['sub_part_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($part['footnote_model'])) {
                                foreach ($part['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subPartString = implode('', $subPartList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $partCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $partHtml = '<div id="' . $part['part_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $part['part_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $part['part_title'] . '</h4></div></br><div>' . $part['part_content'] . '</div><div>' . $subPartString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $partCss . $partHtml;
                        }      
                    }

                    if(!empty($item['appendices'])){
                        foreach ($item['appendices'] as $appendices) {
                            $subAppendiceList = [];
                            if (!empty($appendices['sub_appendices_model'])) {
                                foreach ($appendices['sub_appendices_model'] as $subAppendice) {
                                    $subAppendiceList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subAppendice['sub_appendices_no'] . '</div><div>' . $subAppendice['sub_appendices_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($appendices['footnote_model'])) {
                                foreach ($appendices['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subAppendiceString = implode('', $subAppendiceList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $appendicesCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $appendicesHtml = '<div id="' . $appendices['appendices_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $appendices['appendices_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $appendices['appendices_title'] . '</h4></div></br><div>' . $appendices['appendices_content'] . '</div><div>' . $subAppendiceString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $appendicesCss . $appendicesHtml;
                        }         
                    }

                    if(!empty($item['order'])){
                          
                        foreach ($item['order'] as $order) {
                            $subOrderList = [];
                            if (!empty($order['sub_order_model'])) {
                                foreach ($order['sub_order_model'] as $subOrder) {
                                    $subOrderList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subOrder['sub_order_no'] . '</div><div>' . $subOrder['sub_order_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($order['footnote_model'])) {
                                foreach ($order['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subOrderString = implode('', $subOrderList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $orderCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $orderHtml = '<div id="' . $order['order_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $order['order_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $order['order_title'] . '</h4></div></br><div>' . $order['order_content'] . '</div><div>' . $subOrderString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $orderCss . $orderHtml;
                        } 
                    }

                    if(!empty($item['annexure'])){
                        foreach ($item['annexure'] as $annexure) {
                            $subAnnexureList = [];
                            if (!empty($annexure['sub_annexure_model'])) {
                                foreach ($annexure['sub_annexure_model'] as $subAnnexure) {
                                    $subAnnexureList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subAnnexure['sub_annexure_no'] . '</div><div>' . $subAnnexure['sub_annexure_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($annexure['footnote_model'])) {
                                foreach ($annexure['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subAnnexureString = implode('', $subAnnexureList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $annexureCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $annexureHtml = '<div id="' . $annexure['annexure_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $annexure['annexure_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $annexure['annexure_title'] . '</h4></div></br><div>' . $annexure['annexure_content'] . '</div><div>' . $subAnnexureString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $annexureCss . $annexureHtml;
                        }         
                    }
                   
                    if(!empty($item['stschedule'])){
                        foreach ($item['stschedule'] as $stschedule) {
                            $subStscheduleList = [];
                            if (!empty($stschedule['sub_stschedule_model'])) {
                                foreach ($stschedule['sub_stschedule_model'] as $subStschedule) {
                                    $subStscheduleList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subStschedule['sub_stschedule_no'] . '</div><div>' . $subStschedule['sub_stschedule_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($stschedule['footnote_model'])) {
                                foreach ($stschedule['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subStscheduleString = implode('', $subStscheduleList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $stscheduleCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $stscheduleHtml = '<div id="' . $stschedule['stschedule_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $stschedule['stschedule_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $stschedule['stschedule_title'] . '</h4></div></br><div>' . $stschedule['stschedule_content'] . '</div><div>' . $subStscheduleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $stscheduleCss . $stscheduleHtml;
                        }        
                    }

                    $sectionString = implode('', $Data);
                    $MainList[] = '<h2 style="text-align:center!important;" id=""><strong>' . $item['priliminary_title'] . '</strong></h2>'. $sectionString;
                            
                }

                if (isset($item['schedule_id'])) {
            
                    $Data = [];
                    if (!empty($item['sections'])) {
                         foreach ($item['sections'] as $section) {
                            $subSectionsList = [];
                            if (!empty($section['subsection_model'])) {
                                foreach ($section['subsection_model'] as $subsection) {
                                    $subSectionsList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subsection['sub_section_no'] . '</div><div>' . $subsection['sub_section_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($section['footnote_model'])) {
                                foreach ($section['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subSectionString = implode('', $subSectionsList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $sectionCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $sectionHtml = '<div id="' . $section['section_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $section['section_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $section['section_title'] . '</h4></div></br><div>' . $section['section_content'] . '</div><div>' . $subSectionString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $sectionCss . $sectionHtml;
                        }
                    }
                
                    if(!empty($item['articles'])){
                        foreach ($item['articles'] as $article) {
                            $subArticleList = [];
                            if (!empty($article['sub_article_model'])) {
                                foreach ($article['sub_article_model'] as $subarticle) {
                                    $subArticleList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subarticle['sub_article_no'] . '</div><div>' . $subarticle['sub_article_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($article['footnote_model'])) {
                                foreach ($article['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subArticleString = implode('', $subArticleList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $articleCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $articleHtml = '<div id="' . $article['article_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $article['article_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $article['article_title'] . '</h4></div></br><div>' . $article['article_content'] . '</div><div>' . $subArticleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $articleCss . $articleHtml;
                        }       
                    }

                    if(!empty($item['rules'])){
                        foreach ($item['rules'] as $rule) {
                            $subRuleList = [];
                            if (!empty($rule['subrule_model'])) {
                                foreach ($rule['subrule_model'] as $subrule) {
                                    $subRuleList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subrule['sub_rule_no'] . '</div><div>' . $subrule['sub_rule_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($rule['footnote_model'])) {
                                foreach ($rule['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subRuleString = implode('', $subRuleList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $ruleCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $ruleHtml = '<div id="' . $rule['rule_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $rule['rule_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $rule['rule_title'] . '</h4></div></br><div>' . $rule['rule_content'] . '</div><div>' . $subRuleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $ruleCss . $ruleHtml;
                        }        
                    }

                    if(!empty($item['regulation'])){
                        foreach ($item['regulation'] as $regulation) {
                            $subRegulationList = [];
                            if (!empty($regulation['sub_regulation_model'])) {
                                foreach ($regulation['sub_regulation_model'] as $subRegulation) {
                                    $subRegulationList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subRegulation['sub_regulation_no'] . '</div><div>' . $subRegulation['sub_regulation_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($regulation['footnote_model'])) {
                                foreach ($regulation['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subRegulationString = implode('', $subRegulationList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $regulationCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $regulationHtml = '<div id="' . $regulation['regulation_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $regulation['regulation_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $regulation['regulation_title'] . '</h4></div></br><div>' . $regulation['regulation_content'] . '</div><div>' . $subRegulationString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $regulationCss . $regulationHtml;
                        }        
                    }

                    if(!empty($item['lists'])){
                        foreach ($item['lists'] as $list) {
                            $subListList = [];
                            if (!empty($list['sub_list_model'])) {
                                foreach ($list['sub_list_model'] as $subList) {
                                    $subListList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subList['sub_list_no'] . '</div><div>' . $subList['sub_list_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($list['footnote_model'])) {
                                foreach ($list['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subListString = implode('', $subListList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $listCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $listHtml = '<div id="' . $list['list_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $list['list_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $list['list_title'] . '</h4></div></br><div>' . $list['list_content'] . '</div><div>' . $subListString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $listCss . $listHtml;
                        }       
                    }

                    if(!empty($item['part'])){
                        foreach ($item['part'] as $part) {
                            $subPartList = [];
                            if (!empty($part['sub_part_model'])) {
                                foreach ($part['sub_part_model'] as $subPartsOfPart) {
                                    $subPartList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subPartsOfPart['sub_part_no'] . '</div><div>' . $subPartsOfPart['sub_part_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($part['footnote_model'])) {
                                foreach ($part['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subPartString = implode('', $subPartList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $partCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $partHtml = '<div id="' . $part['part_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $part['part_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $part['part_title'] . '</h4></div></br><div>' . $part['part_content'] . '</div><div>' . $subPartString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $partCss . $partHtml;
                        }       
                    }

                    if(!empty($item['appendices'])){
                        foreach ($item['appendices'] as $appendices) {
                            $subAppendiceList = [];
                            if (!empty($appendices['sub_appendices_model'])) {
                                foreach ($appendices['sub_appendices_model'] as $subAppendice) {
                                    $subAppendiceList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subAppendice['sub_appendices_no'] . '</div><div>' . $subAppendice['sub_appendices_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($appendices['footnote_model'])) {
                                foreach ($appendices['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subAppendiceString = implode('', $subAppendiceList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $appendicesCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $appendicesHtml = '<div id="' . $appendices['appendices_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $appendices['appendices_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $appendices['appendices_title'] . '</h4></div></br><div>' . $appendices['appendices_content'] . '</div><div>' . $subAppendiceString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $appendicesCss . $appendicesHtml;
                        }        
                    }

                    if(!empty($item['order'])){
                        foreach ($item['order'] as $order) {
                            $subOrderList = [];
                            if (!empty($order['sub_order_model'])) {
                                foreach ($order['sub_order_model'] as $subOrder) {
                                    $subOrderList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subOrder['sub_order_no'] . '</div><div>' . $subOrder['sub_order_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($order['footnote_model'])) {
                                foreach ($order['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subOrderString = implode('', $subOrderList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $orderCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $orderHtml = '<div id="' . $order['order_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $order['order_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $order['order_title'] . '</h4></div></br><div>' . $order['order_content'] . '</div><div>' . $subOrderString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $orderCss . $orderHtml;
                        }         
                    }

                    if(!empty($item['annexure'])){
                        foreach ($item['annexure'] as $annexure) {
                            $subAnnexureList = [];
                            if (!empty($annexure['sub_annexure_model'])) {
                                foreach ($annexure['sub_annexure_model'] as $subAnnexure) {
                                    $subAnnexureList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subAnnexure['sub_annexure_no'] . '</div><div>' . $subAnnexure['sub_annexure_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($annexure['footnote_model'])) {
                                foreach ($annexure['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subAnnexureString = implode('', $subAnnexureList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $annexureCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $annexureHtml = '<div id="' . $annexure['annexure_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $annexure['annexure_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $annexure['annexure_title'] . '</h4></div></br><div>' . $annexure['annexure_content'] . '</div><div>' . $subAnnexureString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $annexureCss . $annexureHtml;
                        }       
                    }
                   
                    if(!empty($item['stschedule'])){
                        foreach ($item['stschedule'] as $stschedule) {
                            $subStscheduleList = [];
                            if (!empty($stschedule['sub_stschedule_model'])) {
                                foreach ($stschedule['sub_stschedule_model'] as $subStschedule) {
                                    $subStscheduleList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subStschedule['sub_stschedule_no'] . '</div><div>' . $subStschedule['sub_stschedule_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($stschedule['footnote_model'])) {
                                foreach ($stschedule['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subStscheduleString = implode('', $subStscheduleList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $stscheduleCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $stscheduleHtml = '<div id="' . $stschedule['stschedule_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $stschedule['stschedule_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $stschedule['stschedule_title'] . '</h4></div></br><div>' . $stschedule['stschedule_content'] . '</div><div>' . $subStscheduleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $stscheduleCss . $stscheduleHtml;
                        }         
                    }

                    $sectionString = implode('', $Data);
                    $MainList[] = '<h2 style="text-align:center!important;" id=""><strong>' . $item['schedule_title'] . '</strong></h2>'. $sectionString;
                            
                }

                if (isset($item['appendix_id'])) {
            
                    $Data = [];
                    if (!empty($item['sections'])) {
                         foreach ($item['sections'] as $section) {
                            $subSectionsList = [];
                            if (!empty($section['subsection_model'])) {
                                foreach ($section['subsection_model'] as $subsection) {
                                    $subSectionsList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subsection['sub_section_no'] . '</div><div>' . $subsection['sub_section_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($section['footnote_model'])) {
                                foreach ($section['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subSectionString = implode('', $subSectionsList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $sectionCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $sectionHtml = '<div id="' . $section['section_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $section['section_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $section['section_title'] . '</h4></div></br><div>' . $section['section_content'] . '</div><div>' . $subSectionString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $sectionCss . $sectionHtml;
                        }
                    }
                
                    if(!empty($item['articles'])){
                       foreach ($item['articles'] as $article) {
                            $subArticleList = [];
                            if (!empty($article['sub_article_model'])) {
                                foreach ($article['sub_article_model'] as $subarticle) {
                                    $subArticleList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subarticle['sub_article_no'] . '</div><div>' . $subarticle['sub_article_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($article['footnote_model'])) {
                                foreach ($article['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subArticleString = implode('', $subArticleList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $articleCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $articleHtml = '<div id="' . $article['article_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $article['article_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $article['article_title'] . '</h4></div></br><div>' . $article['article_content'] . '</div><div>' . $subArticleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $articleCss . $articleHtml;
                        }       
                    }

                    if(!empty($item['rules'])){
                        foreach ($item['rules'] as $rule) {
                            $subRuleList = [];
                            if (!empty($rule['subrule_model'])) {
                                foreach ($rule['subrule_model'] as $subrule) {
                                    $subRuleList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subrule['sub_rule_no'] . '</div><div>' . $subrule['sub_rule_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($rule['footnote_model'])) {
                                foreach ($rule['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subRuleString = implode('', $subRuleList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $ruleCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $ruleHtml = '<div id="' . $rule['rule_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $rule['rule_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $rule['rule_title'] . '</h4></div></br><div>' . $rule['rule_content'] . '</div><div>' . $subRuleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $ruleCss . $ruleHtml;
                        }       
                    }

                    if(!empty($item['regulation'])){
                        foreach ($item['regulation'] as $regulation) {
                            $subRegulationList = [];
                            if (!empty($regulation['sub_regulation_model'])) {
                                foreach ($regulation['sub_regulation_model'] as $subRegulation) {
                                    $subRegulationList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subRegulation['sub_regulation_no'] . '</div><div>' . $subRegulation['sub_regulation_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($regulation['footnote_model'])) {
                                foreach ($regulation['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subRegulationString = implode('', $subRegulationList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $regulationCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $regulationHtml = '<div id="' . $regulation['regulation_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $regulation['regulation_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $regulation['regulation_title'] . '</h4></div></br><div>' . $regulation['regulation_content'] . '</div><div>' . $subRegulationString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $regulationCss . $regulationHtml;
                        }        
                    }

                    if(!empty($item['lists'])){
                        foreach ($item['lists'] as $list) {
                            $subListList = [];
                            if (!empty($list['sub_list_model'])) {
                                foreach ($list['sub_list_model'] as $subList) {
                                    $subListList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subList['sub_list_no'] . '</div><div>' . $subList['sub_list_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($list['footnote_model'])) {
                                foreach ($list['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subListString = implode('', $subListList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $listCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $listHtml = '<div id="' . $list['list_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $list['list_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $list['list_title'] . '</h4></div></br><div>' . $list['list_content'] . '</div><div>' . $subListString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $listCss . $listHtml;
                        }     
                    }

                    if(!empty($item['part'])){
                        foreach ($item['part'] as $part) {
                            $subPartList = [];
                            if (!empty($part['sub_part_model'])) {
                                foreach ($part['sub_part_model'] as $subPartsOfPart) {
                                    $subPartList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subPartsOfPart['sub_part_no'] . '</div><div>' . $subPartsOfPart['sub_part_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($part['footnote_model'])) {
                                foreach ($part['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subPartString = implode('', $subPartList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $partCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $partHtml = '<div id="' . $part['part_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $part['part_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $part['part_title'] . '</h4></div></br><div>' . $part['part_content'] . '</div><div>' . $subPartString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $partCss . $partHtml;
                        }       
                    }

                    if(!empty($item['appendices'])){
                        foreach ($item['appendices'] as $appendices) {
                            $subAppendiceList = [];
                            if (!empty($appendices['sub_appendices_model'])) {
                                foreach ($appendices['sub_appendices_model'] as $subAppendice) {
                                    $subAppendiceList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subAppendice['sub_appendices_no'] . '</div><div>' . $subAppendice['sub_appendices_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($appendices['footnote_model'])) {
                                foreach ($appendices['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subAppendiceString = implode('', $subAppendiceList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $appendicesCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $appendicesHtml = '<div id="' . $appendices['appendices_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $appendices['appendices_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $appendices['appendices_title'] . '</h4></div></br><div>' . $appendices['appendices_content'] . '</div><div>' . $subAppendiceString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $appendicesCss . $appendicesHtml;
                        }        
                    }

                    if(!empty($item['order'])){
                        foreach ($item['order'] as $order) {
                            $subOrderList = [];
                            if (!empty($order['sub_order_model'])) {
                                foreach ($order['sub_order_model'] as $subOrder) {
                                    $subOrderList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subOrder['sub_order_no'] . '</div><div>' . $subOrder['sub_order_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($order['footnote_model'])) {
                                foreach ($order['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subOrderString = implode('', $subOrderList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $orderCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $orderHtml = '<div id="' . $order['order_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $order['order_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $order['order_title'] . '</h4></div></br><div>' . $order['order_content'] . '</div><div>' . $subOrderString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $orderCss . $orderHtml;
                        }        
                    }

                    if(!empty($item['annexure'])){
                        foreach ($item['annexure'] as $annexure) {
                            $subAnnexureList = [];
                            if (!empty($annexure['sub_annexure_model'])) {
                                foreach ($annexure['sub_annexure_model'] as $subAnnexure) {
                                    $subAnnexureList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subAnnexure['sub_annexure_no'] . '</div><div>' . $subAnnexure['sub_annexure_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($annexure['footnote_model'])) {
                                foreach ($annexure['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subAnnexureString = implode('', $subAnnexureList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $annexureCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $annexureHtml = '<div id="' . $annexure['annexure_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $annexure['annexure_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $annexure['annexure_title'] . '</h4></div></br><div>' . $annexure['annexure_content'] . '</div><div>' . $subAnnexureString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $annexureCss . $annexureHtml;
                        }        
                    }
                   
                    if(!empty($item['stschedule'])){
                        foreach ($item['stschedule'] as $stschedule) {
                            $subStscheduleList = [];
                            if (!empty($stschedule['sub_stschedule_model'])) {
                                foreach ($stschedule['sub_stschedule_model'] as $subStschedule) {
                                    $subStscheduleList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subStschedule['sub_stschedule_no'] . '</div><div>' . $subStschedule['sub_stschedule_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($stschedule['footnote_model'])) {
                                foreach ($stschedule['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subStscheduleString = implode('', $subStscheduleList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $stscheduleCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $stscheduleHtml = '<div id="' . $stschedule['stschedule_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $stschedule['stschedule_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $stschedule['stschedule_title'] . '</h4></div></br><div>' . $stschedule['stschedule_content'] . '</div><div>' . $subStscheduleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $stscheduleCss . $stscheduleHtml;
                        }         
                    }

                    $sectionString = implode('', $Data);
                    $MainList[] = '<h2 style="text-align:center!important;" id=""><strong>' . $item['appendix_title'] . '</strong></h2>'. $sectionString;
                            
                }

                if (isset($item['main_order_id'])) {
            
                    $Data = [];
                    if (!empty($item['sections'])) {
                        foreach ($item['sections'] as $section) {
                            $subSectionsList = [];
                            if (!empty($section['subsection_model'])) {
                                foreach ($section['subsection_model'] as $subsection) {
                                    $subSectionsList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subsection['sub_section_no'] . '</div><div>' . $subsection['sub_section_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($section['footnote_model'])) {
                                foreach ($section['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subSectionString = implode('', $subSectionsList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $sectionCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $sectionHtml = '<div id="' . $section['section_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $section['section_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $section['section_title'] . '</h4></div></br><div>' . $section['section_content'] . '</div><div>' . $subSectionString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $sectionCss . $sectionHtml;
                        }
                    }
                
                    if(!empty($item['articles'])){
                        foreach ($item['articles'] as $article) {
                            $subArticleList = [];
                            if (!empty($article['sub_article_model'])) {
                                foreach ($article['sub_article_model'] as $subarticle) {
                                    $subArticleList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subarticle['sub_article_no'] . '</div><div>' . $subarticle['sub_article_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($article['footnote_model'])) {
                                foreach ($article['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subArticleString = implode('', $subArticleList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $articleCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $articleHtml = '<div id="' . $article['article_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $article['article_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $article['article_title'] . '</h4></div></br><div>' . $article['article_content'] . '</div><div>' . $subArticleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $articleCss . $articleHtml;
                        }       
                    }

                    if(!empty($item['rules'])){
                        foreach ($item['rules'] as $rule) {
                            $subRuleList = [];
                            if (!empty($rule['subrule_model'])) {
                                foreach ($rule['subrule_model'] as $subrule) {
                                    $subRuleList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subrule['sub_rule_no'] . '</div><div>' . $subrule['sub_rule_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($rule['footnote_model'])) {
                                foreach ($rule['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subRuleString = implode('', $subRuleList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $ruleCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $ruleHtml = '<div id="' . $rule['rule_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $rule['rule_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $rule['rule_title'] . '</h4></div></br><div>' . $rule['rule_content'] . '</div><div>' . $subRuleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $ruleCss . $ruleHtml;
                        }        
                    }

                    if(!empty($item['regulation'])){
                        foreach ($item['regulation'] as $regulation) {
                            $subRegulationList = [];
                            if (!empty($regulation['sub_regulation_model'])) {
                                foreach ($regulation['sub_regulation_model'] as $subRegulation) {
                                    $subRegulationList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subRegulation['sub_regulation_no'] . '</div><div>' . $subRegulation['sub_regulation_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($regulation['footnote_model'])) {
                                foreach ($regulation['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subRegulationString = implode('', $subRegulationList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $regulationCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $regulationHtml = '<div id="' . $regulation['regulation_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $regulation['regulation_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $regulation['regulation_title'] . '</h4></div></br><div>' . $regulation['regulation_content'] . '</div><div>' . $subRegulationString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $regulationCss . $regulationHtml;
                        }       
                    }

                    if(!empty($item['lists'])){
                        foreach ($item['lists'] as $list) {
                            $subListList = [];
                            if (!empty($list['sub_list_model'])) {
                                foreach ($list['sub_list_model'] as $subList) {
                                    $subListList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subList['sub_list_no'] . '</div><div>' . $subList['sub_list_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($list['footnote_model'])) {
                                foreach ($list['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subListString = implode('', $subListList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $listCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $listHtml = '<div id="' . $list['list_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $list['list_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $list['list_title'] . '</h4></div></br><div>' . $list['list_content'] . '</div><div>' . $subListString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $listCss . $listHtml;
                        }       
                    }

                    if(!empty($item['part'])){
                        foreach ($item['part'] as $part) {
                            $subPartList = [];
                            if (!empty($part['sub_part_model'])) {
                                foreach ($part['sub_part_model'] as $subPartsOfPart) {
                                    $subPartList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subPartsOfPart['sub_part_no'] . '</div><div>' . $subPartsOfPart['sub_part_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($part['footnote_model'])) {
                                foreach ($part['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subPartString = implode('', $subPartList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $partCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $partHtml = '<div id="' . $part['part_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $part['part_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $part['part_title'] . '</h4></div></br><div>' . $part['part_content'] . '</div><div>' . $subPartString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $partCss . $partHtml;
                        }        
                    }

                    if(!empty($item['appendices'])){
                        
                        foreach ($item['appendices'] as $appendices) {
                            $subAppendiceList = [];
                            if (!empty($appendices['sub_appendices_model'])) {
                                foreach ($appendices['sub_appendices_model'] as $subAppendice) {
                                    $subAppendiceList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subAppendice['sub_appendices_no'] . '</div><div>' . $subAppendice['sub_appendices_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($appendices['footnote_model'])) {
                                foreach ($appendices['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subAppendiceString = implode('', $subAppendiceList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $appendicesCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $appendicesHtml = '<div id="' . $appendices['appendices_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $appendices['appendices_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $appendices['appendices_title'] . '</h4></div></br><div>' . $appendices['appendices_content'] . '</div><div>' . $subAppendiceString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $appendicesCss . $appendicesHtml;
                        }   
                        
                        
                    }

                    if(!empty($item['order'])){
                        foreach ($item['order'] as $order) {
                            $subOrderList = [];
                            if (!empty($order['sub_order_model'])) {
                                foreach ($order['sub_order_model'] as $subOrder) {
                                    $subOrderList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subOrder['sub_order_no'] . '</div><div>' . $subOrder['sub_order_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($order['footnote_model'])) {
                                foreach ($order['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subOrderString = implode('', $subOrderList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $orderCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $orderHtml = '<div id="' . $order['order_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $order['order_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $order['order_title'] . '</h4></div></br><div>' . $order['order_content'] . '</div><div>' . $subOrderString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $orderCss . $orderHtml;
                        }        
                    }

                    if(!empty($item['annexure'])){
                        foreach ($item['annexure'] as $annexure) {
                            $subAnnexureList = [];
                            if (!empty($annexure['sub_annexure_model'])) {
                                foreach ($annexure['sub_annexure_model'] as $subAnnexure) {
                                    $subAnnexureList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subAnnexure['sub_annexure_no'] . '</div><div>' . $subAnnexure['sub_annexure_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($annexure['footnote_model'])) {
                                foreach ($annexure['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subAnnexureString = implode('', $subAnnexureList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $annexureCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $annexureHtml = '<div id="' . $annexure['annexure_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $annexure['annexure_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $annexure['annexure_title'] . '</h4></div></br><div>' . $annexure['annexure_content'] . '</div><div>' . $subAnnexureString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $annexureCss . $annexureHtml;
                        }        
                    }
                   
                    if(!empty($item['stschedule'])){
                        foreach ($item['stschedule'] as $stschedule) {
                            $subStscheduleList = [];
                            if (!empty($stschedule['sub_stschedule_model'])) {
                                foreach ($stschedule['sub_stschedule_model'] as $subStschedule) {
                                    $subStscheduleList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $subStschedule['sub_stschedule_no'] . '</div><div>' . $subStschedule['sub_stschedule_content'] . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($stschedule['footnote_model'])) {
                                foreach ($stschedule['footnote_model'] as $footnote) {
                                    $footnoteList[] = '<div>' . $footnote['footnote_content'] . '</div>';
                                }
                            }
                        
                            $subStscheduleString = implode('', $subStscheduleList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $stscheduleCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $stscheduleHtml = '<div id="' . $stschedule['stschedule_id'] . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $stschedule['stschedule_no'] . '</h4><h4 class="font-weight-bold  pl-2">' . $stschedule['stschedule_title'] . '</h4></div></br><div>' . $stschedule['stschedule_content'] . '</div><div>' . $subStscheduleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $stscheduleCss . $stscheduleHtml;
                        }        
                    }

                    $sectionString = implode('', $Data);
                    $MainList[] = '<h2 style="text-align:center!important;" id=""><strong>' . $item['main_order_title'] . '</strong></h2>'. $sectionString;
                            
                }
             
            }
          
             
    
              

            return response()->json([
                'status' => 200,
                'data' => [
                    'actId' => $act->act_id,
                    'Act No.' => $act->act_no,
                    'actName' => $act->act_title,
                    'Enactment Date' => $act->enactment_date,
                    'Enforcement Date' => $act->enforcement_date,
                    'Ministry' => $act->ministry,
                    'Preamble' => $act->act_description,
                    'actDescription' => '<h1 id=""><strong>' . $act->act_title . '</strong> </h1><div><strong>' . $act->act_no . '</strong></div><div><strong>' . $act->act_date . '</strong></div>' . implode('', $MainList) . '',
                    'sideBarList' => $sideBarList,
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 400,
                'message' => 'Act not found with the provided ID.',
                'data' => null
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server Error: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }




    public function create($id)
    {
        try {

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new Dompdf($options);
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
                    $combinedItems->push($chapterData);
                }
            
                foreach ($mainItem->parts as $part) {
                    $partData = $part->toArray();
                    $combinedItems->push($partData);
                }
            
                foreach ($mainItem->priliminarys as $preliminary) {
                    $preliminaryData = $preliminary->toArray();
                    $combinedItems->push($preliminaryData);
                }
            
                foreach ($mainItem->schedules as $schedule) {
                    $scheduleData = $schedule->toArray();
                    $combinedItems->push($scheduleData);
                }
            
                foreach ($mainItem->appendixes as $appendix) {
                    $appendixData = $appendix->toArray();
                    $combinedItems->push($appendixData);
                }
            
                foreach ($mainItem->mainOrders as $mainOrder) {
                    $mainOrderData = $mainOrder->toArray();
                    $combinedItems->push($mainOrderData);
                }
            }


            // dd($combinedItems);
            // die();

            $type = MainType::all();
            $act = Act::findOrFail($id);
            $act_footnotes = Act::where('act_id',$id)->get();
            $chapter = Chapter::where('act_id', $id)->get();
            $parts = Parts::where('act_id', $id)->get();
            $priliminary = Priliminary::where('act_id', $id)->get();
            $schedule = Schedule::where('act_id', $id)->get();

          
            // $partstype = PartsType::all();
            // $regulation = Regulation::where('act_id', $id)->whereIn('chapter_id', $chapter->pluck('chapter_id'))->get();
            $subType = SubType::all();


            $pdf = FacadePdf::loadView('admin.export.pdf', [
                'act' => $act,
                'act_footnotes' => $act_footnotes,
                'type' => $type,
                'subType' => $subType,
                'combinedItems' => $combinedItems,
            ]);

            return $pdf->download("{$act->act_title}.pdf");
        } catch (ModelNotFoundException $e) {
            return redirect()->route('act');
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */


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
