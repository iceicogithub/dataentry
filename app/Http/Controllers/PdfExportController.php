<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Chapter;
use App\Models\Section;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PdfExportController extends Controller
{
    public function exportToPdf(Request $request, $id)
    {
        try {
            $act = Act::findOrFail($id);
            $chapter = Chapter::where('act_id', $id)->get();
            $section = Section::where('act_id', $id)
                ->whereIn('chapter_id', $chapter->pluck('chapter_id'))
                ->get();

            // dd($section);
            // die();

            $pdf = FacadePdf::loadView('admin.export.pdf', [
                'act' => $act,
                'chapter' => $chapter,
                'section' => $section
            ]);

            return $pdf->download('invoice.pdf');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('act');
        }
    }
}
