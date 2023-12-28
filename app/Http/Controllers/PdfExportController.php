<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Chapter;
use App\Models\MainType;
use App\Models\Parts;
use App\Models\PartsType;
use App\Models\Regulation;
use App\Models\Section;
use App\Models\SubType;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PdfExportController extends Controller
{
    public function exportToPdf(Request $request, $id)
    {
        try {
            $type = MainType::all();
            $act = Act::findOrFail($id);
            $chapter = Chapter::where('act_id', $id)->get();
            $section = Section::where('act_id', $id)->whereIn('chapter_id', $chapter->pluck('chapter_id'))->orderBy('section_rank', 'asc')->get();
            $partstype = PartsType::all();
            $forparts = Section::where('maintype_id',2)->orderBy('section_rank', 'asc')->get();
            $parts = Parts::where('act_id', $id)->get();
            $regulation = Regulation::where('act_id', $id)->whereIn('chapter_id', $chapter->pluck('chapter_id'))->get();
            $subType = SubType::all();
            // dd($regulation);
            // die();

            $pdf = FacadePdf::loadView('admin.export.pdf', [
                'act' => $act,
                'chapter' => $chapter,
                'section' => $section,
                'type' => $type,
                'partstype' => $partstype,
                'parts' => $parts,
                'forparts' => $forparts,
                'regulation' => $regulation,
                'subType' => $subType
            ]);

            return $pdf->download('invoice.pdf');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('act');
        }
    }
}
