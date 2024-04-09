<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Chapter;
use App\Models\Footnote;
use App\Models\MainType;
use App\Models\Parts;
use App\Models\PartsType;
use App\Models\Priliminary;
use App\Models\Regulation;
use App\Models\Part;
use App\Models\Lists;
use App\Models\Appendices;
use App\Models\Orders;
use App\Models\Annexure;
use App\Models\Stschedule;
use App\Models\Rules;
use App\Models\SubRules;
use App\Models\Schedule;
use App\Models\Section;
use App\Models\MainOrder;
use App\Models\Article;
use App\Models\SubArticle;
use App\Models\Appendix;
use App\Models\SubType;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\MainTable;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PdfExportController extends Controller
{
    public function exportToPdf(Request $request, $id)
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
}
