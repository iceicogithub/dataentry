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
            $chapters = Chapter::where('act_id', $id)
            ->with(['Sections' => function ($query) {
                $query->with('subsectionModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('section_id')
                    ->orderBy('section_rank');
                    
            }])
            ->with(['Articles' => function ($query) {
                $query->with('subArticleModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('article_id')
                    ->orderBy('article_rank');
                   
            }])
            ->with(['Rules' => function ($query) {
                $query->with('subruleModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('rule_id')
                    ->orderBy('rule_rank');
                    
            }])
            ->with(['Regulation' => function ($query) {
                $query->with('subRegulationModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('regulation_id')
                    ->orderBy('regulation_rank');
                    
            }])
            ->with(['Lists' => function ($query) {
                $query->with('subListModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('list_id')
                    ->orderBy('list_rank');
                    
            }])
            ->with(['Part' => function ($query) {
                $query->with('subPartModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('part_id')
                    ->orderBy('part_rank');
                  
            }])
            ->with(['Appendices' => function ($query) {
                $query->with('subAppendicesModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('appendices_id')
                    ->orderBy('appendices_rank');
                    
            }])
            ->with(['Order' => function ($query) {
                $query->with('subOrderModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('order_id')
                    ->orderBy('order_rank');
                   
            }])
            ->with(['Annexure' => function ($query) {
                $query->with('subAnnexureModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('annexure_id')
                    ->orderBy('annexure_rank');
                   
            }])
            ->with(['Stschedule' => function ($query) {
                 $query->with('subStscheduleModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('stschedule_id')
                    ->orderBy('stschedule_rank');
                    
            }])
            ->orderBy('chapter_id')
            ->orderBy('serial_no')
            ->get();


            $parts = Parts::where('act_id', $id)
            ->with(['Sections' => function ($query) {
                $query->with('subsectionModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('section_id')
                    ->orderBy('section_rank');
                    
            }])
            ->with(['Articles' => function ($query) {
                $query->with('subArticleModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('article_id')
                    ->orderBy('article_rank');
                   
            }])
            ->with(['Rules' => function ($query) {
                $query->with('subruleModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('rule_id')
                    ->orderBy('rule_rank');
                    
            }])
            ->with(['Regulation' => function ($query) {
                $query->with('subRegulationModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('regulation_id')
                    ->orderBy('regulation_rank');
                    
            }])
            ->with(['Lists' => function ($query) {
                $query->with('subListModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('list_id')
                    ->orderBy('list_rank');
                    
            }])
            ->with(['Part' => function ($query) {
                $query->with('subPartModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('part_id')
                    ->orderBy('part_rank');
                  
            }])
            ->with(['Appendices' => function ($query) {
                $query->with('subAppendicesModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('appendices_id')
                    ->orderBy('appendices_rank');
                    
            }])
            ->with(['Order' => function ($query) {
                $query->with('subOrderModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('order_id')
                    ->orderBy('order_rank');
                   
            }])
            ->with(['Annexure' => function ($query) {
                $query->with('subAnnexureModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('annexure_id')
                    ->orderBy('annexure_rank');
                   
            }])
            ->with(['Stschedule' => function ($query) {
                 $query->with('subStscheduleModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('stschedule_id')
                    ->orderBy('stschedule_rank');
                    
            }])
            ->orderBy('parts_id')
            ->orderBy('serial_no')
            ->get();



            $priliminarys = Priliminary::where('act_id', $id)
            ->with(['Sections' => function ($query) {
                $query->with('subsectionModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('section_id')
                    ->orderBy('section_rank');
                    
            }])
            ->with(['Articles' => function ($query) {
                $query->with('subArticleModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('article_id')
                    ->orderBy('article_rank');
                   
            }])
            ->with(['Rules' => function ($query) {
                $query->with('subruleModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('rule_id')
                    ->orderBy('rule_rank');
                    
            }])
            ->with(['Regulation' => function ($query) {
                $query->with('subRegulationModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('regulation_id')
                    ->orderBy('regulation_rank');
                    
            }])
            ->with(['Lists' => function ($query) {
                $query->with('subListModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('list_id')
                    ->orderBy('list_rank');
                    
            }])
            ->with(['Part' => function ($query) {
                $query->with('subPartModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('part_id')
                    ->orderBy('part_rank');
                  
            }])
            ->with(['Appendices' => function ($query) {
                $query->with('subAppendicesModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('appendices_id')
                    ->orderBy('appendices_rank');
                    
            }])
            ->with(['Order' => function ($query) {
                $query->with('subOrderModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('order_id')
                    ->orderBy('order_rank');
                   
            }])
            ->with(['Annexure' => function ($query) {
                $query->with('subAnnexureModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('annexure_id')
                    ->orderBy('annexure_rank');
                   
            }])
            ->with(['Stschedule' => function ($query) {
                 $query->with('subStscheduleModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('stschedule_id')
                    ->orderBy('stschedule_rank');
                    
            }])
            ->orderBy('priliminary_id')
            ->orderBy('serial_no')
            ->get();

            $schedules = Schedule::where('act_id', $id)
            ->with(['Sections' => function ($query) {
                $query->with('subsectionModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('section_id')
                    ->orderBy('section_rank');
                    
            }])
            ->with(['Articles' => function ($query) {
                $query->with('subArticleModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('article_id')
                    ->orderBy('article_rank');
                   
            }])
            ->with(['Rules' => function ($query) {
                $query->with('subruleModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('rule_id')
                    ->orderBy('rule_rank');
                    
            }])
            ->with(['Regulation' => function ($query) {
                $query->with('subRegulationModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('regulation_id')
                    ->orderBy('regulation_rank');
                    
            }])
            ->with(['Lists' => function ($query) {
                $query->with('subListModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('list_id')
                    ->orderBy('list_rank');
                    
            }])
            ->with(['Part' => function ($query) {
                $query->with('subPartModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('part_id')
                    ->orderBy('part_rank');
                  
            }])
            ->with(['Appendices' => function ($query) {
                $query->with('subAppendicesModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('appendices_id')
                    ->orderBy('appendices_rank');
                    
            }])
            ->with(['Order' => function ($query) {
                $query->with('subOrderModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('order_id')
                    ->orderBy('order_rank');
                   
            }])
            ->with(['Annexure' => function ($query) {
                $query->with('subAnnexureModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('annexure_id')
                    ->orderBy('annexure_rank');
                   
            }])
            ->with(['Stschedule' => function ($query) {
                 $query->with('subStscheduleModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('stschedule_id')
                    ->orderBy('stschedule_rank');
                    
            }])
            ->orderBy('schedule_id')
            ->orderBy('serial_no')
            ->get();

            $appendixes = Appendix::where('act_id', $id)
            ->with(['Sections' => function ($query) {
                $query->with('subsectionModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('section_id')
                    ->orderBy('section_rank');
                    
            }])
            ->with(['Articles' => function ($query) {
                $query->with('subArticleModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('article_id')
                    ->orderBy('article_rank');
                   
            }])
            ->with(['Rules' => function ($query) {
                $query->with('subruleModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('rule_id')
                    ->orderBy('rule_rank');
                    
            }])
            ->with(['Regulation' => function ($query) {
                $query->with('subRegulationModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('regulation_id')
                    ->orderBy('regulation_rank');
                    
            }])
            ->with(['Lists' => function ($query) {
                $query->with('subListModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('list_id')
                    ->orderBy('list_rank');
                    
            }])
            ->with(['Part' => function ($query) {
                $query->with('subPartModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('part_id')
                    ->orderBy('part_rank');
                  
            }])
            ->with(['Appendices' => function ($query) {
                $query->with('subAppendicesModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('appendices_id')
                    ->orderBy('appendices_rank');
                    
            }])
            ->with(['Order' => function ($query) {
                $query->with('subOrderModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('order_id')
                    ->orderBy('order_rank');
                   
            }])
            ->with(['Annexure' => function ($query) {
                $query->with('subAnnexureModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('annexure_id')
                    ->orderBy('annexure_rank');
                   
            }])
            ->with(['Stschedule' => function ($query) {
                 $query->with('subStscheduleModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('stschedule_id')
                    ->orderBy('stschedule_rank');
                    
            }])
            ->orderBy('appendix_id')
            ->orderBy('serial_no')
            ->get();

            $mainOrders = MainOrder::where('act_id', $id)
            ->with(['Sections' => function ($query) {
                $query->with('subsectionModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('section_id')
                    ->orderBy('section_rank');
                    
            }])
            ->with(['Articles' => function ($query) {
                $query->with('subArticleModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('article_id')
                    ->orderBy('article_rank');
                   
            }])
            ->with(['Rules' => function ($query) {
                $query->with('subruleModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('rule_id')
                    ->orderBy('rule_rank');
                    
            }])
            ->with(['Regulation' => function ($query) {
                $query->with('subRegulationModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('regulation_id')
                    ->orderBy('regulation_rank');
                    
            }])
            ->with(['Lists' => function ($query) {
                $query->with('subListModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('list_id')
                    ->orderBy('list_rank');
                    
            }])
            ->with(['Part' => function ($query) {
                $query->with('subPartModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('part_id')
                    ->orderBy('part_rank');
                  
            }])
            ->with(['Appendices' => function ($query) {
                $query->with('subAppendicesModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('appendices_id')
                    ->orderBy('appendices_rank');
                    
            }])
            ->with(['Order' => function ($query) {
                $query->with('subOrderModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('order_id')
                    ->orderBy('order_rank');
                   
            }])
            ->with(['Annexure' => function ($query) {
                $query->with('subAnnexureModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('annexure_id')
                    ->orderBy('annexure_rank');
                   
            }])
            ->with(['Stschedule' => function ($query) {
                 $query->with('subStscheduleModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('stschedule_id')
                    ->orderBy('stschedule_rank');
                    
            }])
            ->orderBy('main_order_id')
            ->orderBy('serial_no')
            ->get();

// dd($parts);
// die();



            $combinedItems = [];

            foreach ($chapters as $chapter) {
                $chapterData = $chapter->toArray();
                $combinedItems[$chapter->serial_no] = $chapterData;
            }

            foreach ($parts as $part) {
                $partData = $part->toArray();
                $combinedItems[$part->serial_no] = $partData;
            }

            foreach ($priliminarys as $priliminary) {
                $priliminaryData = $priliminary->toArray();
                $combinedItems[$priliminary->serial_no] = $priliminaryData;
            }

            foreach ($schedules as $schedule) {
                $scheduleData = $schedule->toArray();
                $combinedItems[$schedule->serial_no] = $scheduleData;
            }

            foreach ($appendixes as $appendix) {
                $appendixData = $appendix->toArray();
                $combinedItems[$appendix->serial_no] = $appendixData;
            }

            foreach ($mainOrders as $mainOrder) {
                $mainOrderData = $mainOrder->toArray();
                $combinedItems[$mainOrder->serial_no] = $mainOrderData;
            }


            // Sort the combined items by their serial_no
            ksort($combinedItems);

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
