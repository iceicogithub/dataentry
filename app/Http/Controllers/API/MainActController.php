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
use App\Models\Regulation;
use App\Models\Section;
use App\Models\SubSection;
use App\Models\SubType;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MainActController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id)
    {
        try {

            $type = MainType::all();
            $act = Act::findOrFail($id);
            $chapter = Chapter::where('act_id', $id)->get();
            $part = Parts::where('act_id', $id)->get();

            $section = Section::where('act_id', $id)
                ->orWhereIn('chapter_id', $chapter->pluck('chapter_id'))
                ->orWhereIn('parts_id', $part->pluck('parts_id'))
                ->with('subsectionModel', 'footnoteModel')
                ->orderBy('section_rank', 'asc')
                ->get();

            $parts = Parts::where('act_id', $id)->get();
            $subType = SubType::all();

            return response()->json([
                'status' => true,
                'data' =>   [
                    'act' => $act,
                    'chapter' => $chapter,
                    'section' => $section,
                    'type' => $type,
                    'parts' => $parts,
                    'subType' => $subType
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Resource not found.' . $e->getMessage(),
                'data' => null
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create($id)
    {
        try {
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new Dompdf($options);

            $type = MainType::all();
            $act = Act::findOrFail($id);
            $chapter = Chapter::where('act_id', $id)->get();
            $part = Parts::where('act_id', $id)->get();

            $section = Section::where('act_id', $id)
                ->orWhereIn('chapter_id', $chapter->pluck('chapter_id'))
                ->orWhereIn('parts_id', $part->pluck('parts_id'))
                ->with('subsectionModel', 'footnoteModel')
                ->orderBy('section_rank', 'asc')
                ->get();

            $partstype = PartsType::all();
            $parts = Parts::where('act_id', $id)->get();
            $regulation = Regulation::where('act_id', $id)->whereIn('chapter_id', $chapter->pluck('chapter_id'))->get();
            $subType = SubType::all();

            $pdf = FacadePdf::loadView('admin.export.pdf', [
                'act' => $act,
                'chapter' => $chapter,
                'section' => $section,
                'type' => $type,
                'partstype' => $partstype,
                'parts' => $parts,
                'regulation' => $regulation,
                'subType' => $subType
            ]);

        // Get PDF contents as a string
        $pdfContents = $pdf->output();

        // Set response headers for PDF download
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $act->act_title . '.pdf"',
        ];

        // Return PDF as response
        return response($pdfContents, 200, $headers);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Resource not found. ' . $e->getMessage(),
                'data' => null,
            ], Response::HTTP_NOT_FOUND);
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
