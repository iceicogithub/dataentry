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
use App\Models\Rules;
use App\Models\Schedule;
use App\Models\Section;
use App\Models\SubSection;
use App\Models\SubType;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PdfExportController extends Controller
{
    public function exportToPdf(Request $request, $id)
    {
        try {

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new Dompdf($options);



            $type = MainType::all();
            $act = Act::findOrFail($id);
            $act_footnotes = Act::where('act_id',$id)->get();
            $chapter = Chapter::where('act_id', $id)->get();
            $parts = Parts::where('act_id', $id)->get();
            $priliminary = Priliminary::where('act_id', $id)->get();
            $schedule = Schedule::where('act_id', $id)->get();

            $section = Section::where('act_id', $id)
                ->orWhereIn('chapter_id', $chapter->pluck('chapter_id'))
                ->orWhereIn('parts_id', $parts->pluck('parts_id'))
                ->orWhereIn('priliminary_id', $priliminary->pluck('priliminary_id'))
                ->with('subsectionModel', 'footnoteModel')
                ->orderByRaw('CAST(section_rank AS SIGNED) ASC')
                ->get();

            $rule = Rules::where('act_id', $id)
                ->whereIn('schedule_id', $schedule->pluck('schedule_id'))
                ->with('footnoteModel','subruleModel')
                ->orderByRaw('CAST(rule_rank AS SIGNED) ASC')
                ->get();

            $partstype = PartsType::all();
            $regulation = Regulation::where('act_id', $id)->whereIn('chapter_id', $chapter->pluck('chapter_id'))->get();
            $subType = SubType::all();


            $pdf = FacadePdf::loadView('admin.export.pdf', [
                'act' => $act,
                'act_footnotes' => $act_footnotes,
                'type' => $type,
                'chapter' => $chapter,
                'priliminary' => $priliminary,
                'schedule' => $schedule,
                'parts' => $parts,
                'partstype' => $partstype,
                'subType' => $subType,
                'section' => $section,
                'rule' => $rule,
                'regulation' => $regulation,
            ]);

            return $pdf->download("{$act->act_title}.pdf");
        } catch (ModelNotFoundException $e) {
            return redirect()->route('act');
        }
    }
}
