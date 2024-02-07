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
            $chapter = Chapter::where('act_id', $id)->get();
            $part = Parts::where('act_id', $id)->get();

            $sections = Section::where('act_id', $id)
                ->orWhereIn('chapter_id', $chapter->pluck('chapter_id'))
                ->orWhereIn('parts_id', $part->pluck('part_id'))
                ->with('subsectionModel', 'footnoteModel', 'Partmodel', 'ChapterModel')
                ->get()
                ->sortBy(function ($section) {
                    $mixstring = $section->section_no;

                    // Check if the regular expression matches
                    if (preg_match('/^(\d+)([a-zA-Z]*)$/', $mixstring, $matches)) {
                        $numericPart = str_pad($matches[1], 10, '0', STR_PAD_LEFT);
                        $alphabeticPart = strtolower($matches[2]);

                        return $numericPart . $alphabeticPart;
                    } else {
                        // Handle the case where the regular expression doesn't match
                        return $mixstring; // Default behavior is to return the mixstring as is
                    }
                });

            $chapterList = [];

            foreach ($chapter as $item) {
                $chapterSections = [];

                foreach ($sections as $index => $section) {
                    if ($section->chapter_id == $item->chapter_id) {
                        $subSectionsList = [];

                        foreach ($section->subsectionModel as $subsection) {
                            if ($subsection->chapter_id == $item->chapter_id) {
                                $subSectionsList[] = '<div style="display:flex!important;"><div>' . $subsection->sub_section_no . '</div><div>' . $subsection->sub_section_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($section->footnoteModel as $footnote) {
                            if ($footnote->chapter_id == $item->chapter_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subSectionString = implode('', $subSectionsList);
                        $footnoteString = implode('', $footnoteList);

                        $chapterSections[] = '<div id="' . $section->section_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $section->section_no . '</h4><h4 class="font-weight-bold mb-3">' . $section->section_title . '</h4></div></br><div>' . $section->section_content . '</div><div>' . $subSectionString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                $sectionString = implode('', $chapterSections);

                $chapterList[] = '<h2 style="text-align:center!important;" id=""><strong>' . $item->chapter_title . '</strong></h2><div>' . $item->chapter_content . '</div>' . $sectionString;
            }

            $partList = [];

            foreach ($part as $item) {
                $partSections = [];

                foreach ($sections as $index => $section) {
                    if ($section->part_id == $item->part_id) {
                        $subSectionsList = [];

                        foreach ($section->subsectionModel as $subsection) {
                            if ($subsection->part_id == $item->part_id) {
                                $subSectionsList[] = '<div style="display:flex!important;"><div>' . $subsection->sub_section_no . '</div><div>' . $subsection->sub_section_content . '</div ></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($section->footnoteModel as $footnote) {
                            if ($footnote->part_id == $item->part_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subSectionString = implode('', $subSectionsList);
                        $footnoteString = implode('', $footnoteList);

                        $partSections[] = '<div id="' . $section->section_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $section->section_no . '</h4><h4 class="font-weight-bold mb-3">' . $section->section_title . '</h4></div><div>' . $section->section_content . '</div><div>' . $subSectionString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                $sectionString = implode('', $partSections);

                $partList[] = '<h2 style="text-align:center!important;" id=""><strong>' . $item->part_title . '</strong></h2><p>' . $item->part_content . '</p>' . $sectionString;
            }

            $sectionList = [];

            foreach ($sections as $section) {
                $sectionList[] = [
                    'SectionId' => $section->section_id,
                    'Name' => $section->section_title,
                ];
            }

            return response()->json([
                'status' => 200,
                'data' => [
                    'actId' => $act->act_id,
                    'actName' => $act->act_title,
                    'actDescription' => '<h1 id=""><strong>' . $act->act_title . '</strong> </h1><div><strong>' . $act->act_no . '</strong></div><div><strong>' . $act->act_date . '</strong></div>' . implode('', $chapterList) . '' . implode('', $partList) . '',
                    'sectionList' => $sectionList,
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

            $type = MainType::all();
            $act = Act::findOrFail($id);
            $act_footnotes = Act::where('act_id', $id)->get();
            $chapter = Chapter::where('act_id', $id)->get();
            $parts = Parts::where('act_id', $id)->get();
            $priliminary = Priliminary::where('act_id', $id)->get();
            $schedule = Schedule::where('act_id', $id)->get();

            $section = Section::where('act_id', $id)
                ->orWhereIn('chapter_id', $chapter->pluck('chapter_id'))
                ->orWhereIn('parts_id', $parts->pluck('parts_id'))
                ->orWhereIn('priliminary_id', $priliminary->pluck('priliminary_id'))
                ->with('subsectionModel', 'footnoteModel')
                ->orderBy('section_rank', 'asc')
                ->get();

            $rule = Rules::where('act_id', $id)
                ->whereIn('schedule_id', $schedule->pluck('schedule_id'))
                ->with('footnoteModel')
                ->orderBy('rule_rank', 'asc')
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
