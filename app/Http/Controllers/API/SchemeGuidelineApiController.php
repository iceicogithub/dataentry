<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Act;
use Illuminate\Http\Request;
use App\Models\MainTypeSchemeGuidelines;
use App\Models\SubTypeSchemeGuidelines;
use App\Models\PartsType;
use App\Models\SchemeGuidelinesMain;
use App\Models\SchemeGuidelinesTable;
use App\Models\NewSchemeGuidelines;
use App\Models\SchemeGuidelinesSub;
use App\Models\MainSchemeGuidelinesFootnote;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Dompdf\Dompdf;
use Dompdf\Options;

class SchemeGuidelineApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        try {
            $new_schemeGuidelines = NewSchemeGuidelines::where('act_id', $id)->get();
            
            return response()->json([
                'status' => 200,
                'data' =>   [
                    'new_schemeGuidelines' => $new_schemeGuidelines,
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

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        try{
            $newSchemeGuidelines = NewSchemeGuidelines::where('new_scheme_guidelines_id', $id)->first();

            $combinedItems = NewSchemeGuidelines::where('new_scheme_guidelines_id', $id)
            ->with([
                'schemeGuidelinesMain' => function ($query) {
                    $query->with(['schemeGuidelinestbl' => function ($query) {
                        $query->orderBy('scheme_guidelines_rank');
                    }])->orderBy('scheme_guidelines_main_rank'); // Sort ruleMain by rule_main_rank
                },
                'schemeGuidelinesMain.schemeGuidelinestbl.schemeGuidelinesSub', 'schemeGuidelinesMain.schemeGuidelinestbl.schemeGuidelinesFootnoteModel', 'schemeGuidelinesMain.schemeGuidelinestbl.schemeGuidelinesSub.schemeGuidelinesSubFootnoteModel'
            ])
            ->get();
           
            // dd($combinedItems);
            // die();
    
            $sideBarList = [];
    
            foreach ($combinedItems as $NewSchemeGuidelines){
                if (isset($NewSchemeGuidelines->schemeGuidelinesMain)) {
                    foreach ($NewSchemeGuidelines->schemeGuidelinesMain as $schemeGuidelinesMain){
                        $Data = [
                            'ChapterId' => $schemeGuidelinesMain->scheme_guidelines_main_id,
                            'Name' => $schemeGuidelinesMain->scheme_guidelines_main_title,
                            'SubString' => []
                        ];
                        if (!empty($schemeGuidelinesMain->schemeGuidelinestbl)){
                            foreach ($schemeGuidelinesMain->schemeGuidelinestbl as $schemeGuidelinestblItem) {
                                $Data['SubString'][] = [
                                    'SectionId' =>  $schemeGuidelinestblItem->scheme_guidelines_id,
                                    'Name' => $schemeGuidelinestblItem->scheme_guidelines_title,
                                    
                                ];     
                            }    
                        }
                      
                        $sideBarList[] = $Data;
                    }
                }
            }
            
        //   dd($sideBarList);
        //   die();
            $MainList = [];
            foreach ($combinedItems as $NewSchemeGuidelines){
                if (isset($NewSchemeGuidelines->schemeGuidelinesMain)) {
                    foreach ($NewSchemeGuidelines->schemeGuidelinesMain as $schemeGuidelinesMain){
                    $Data = [];
                    if (!empty($schemeGuidelinesMain->schemeGuidelinestbl)){
                        foreach ($schemeGuidelinesMain->schemeGuidelinestbl as $schemeGuidelinestblItem) {
                            $subSectionsList = [];
                            if (!empty($schemeGuidelinestblItem->schemeGuidelinesSub)) {
                                foreach ($schemeGuidelinestblItem->schemeGuidelinesSub as $schemeGuidelinesSubItem){
                                    $subSectionsList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $schemeGuidelinesSubItem->scheme_guidelines_sub_no . '</div><div>' . $schemeGuidelinesSubItem->scheme_guidelines_sub_content . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($schemeGuidelinestblItem->schemeGuidelinesFootnoteModel)) {
                                foreach ($schemeGuidelinestblItem->schemeGuidelinesFootnoteModel as $footnoteModel){
                                    $footnoteList[] = '<div>' . $footnoteModel->footnote_content . '</div>';
                                }
                            }
                        
                            $subSectionString = implode('', $subSectionsList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $sectionCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $sectionHtml = '<div id="' . $schemeGuidelinestblItem->scheme_guidelines_id . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $schemeGuidelinestblItem->scheme_guidelines_no . '</h4><h4 class="font-weight-bold  pl-2">' . $schemeGuidelinestblItem->scheme_guidelines_title . '</h4></div></br><div>' . $schemeGuidelinestblItem->scheme_guidelines_content . '</div><div>' . $subSectionString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $sectionCss . $sectionHtml;
                        }
                    }
    
                    $sectionString = implode('', $Data);
                    $MainList[] = '<h2  id="ch-'.$schemeGuidelinesMain->scheme_guidelines_main_id.'" style="text-align:center!important;" ><strong>' .$schemeGuidelinesMain->scheme_guidelines_main_title . '</strong></h2>'. $sectionString;
                   }
                           
                }
                
            }

            // dd($MainList);
            // die();
            
            $firstItems = NewSchemeGuidelines::where('new_scheme_guidelines_id', $id)
            ->with([
                'schemeGuidelinesMain' => function ($query) {
                    $query->with(['schemeGuidelinestbl' => function ($query) {
                        $query->orderBy('scheme_guidelines_rank')->first();
                    }])->orderBy('scheme_guidelines_main_rank')->first(); // Sort ruleMain by rule_main_rank
                },
                'schemeGuidelinesMain.schemeGuidelinestbl.schemeGuidelinesSub', 'schemeGuidelinesMain.schemeGuidelinestbl.schemeGuidelinesFootnoteModel', 'schemeGuidelinesMain.schemeGuidelinestbl.schemeGuidelinesSub.schemeGuidelinesSubFootnoteModel'
            ])
            ->get();
    
          

            // dd($firstItems);
            // die();
    
    
            $firstChapter = [];
            foreach ($firstItems as $NewSchemeGuidelines){
                if (isset($NewSchemeGuidelines->schemeGuidelinesMain)) {
                    foreach ($NewSchemeGuidelines->schemeGuidelinesMain as $schemeGuidelinesMain){
                    $Data = [];
                    if (!empty($schemeGuidelinesMain->schemeGuidelinestbl)){
                        foreach ($schemeGuidelinesMain->schemeGuidelinestbl as $schemeGuidelinestblItem) {
                            $subSectionsList = [];
                            if (!empty($schemeGuidelinestblItem->schemeGuidelinesSub)) {
                                foreach ($schemeGuidelinestblItem->schemeGuidelinesSub as $schemeGuidelinesSubItem){
                                    $subSectionsList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $schemeGuidelinesSubItem->scheme_guidelines_sub_no . '</div><div>' . $schemeGuidelinesSubItem->scheme_guidelines_sub_content . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($schemeGuidelinestblItem->schemeGuidelinesFootnoteModel)) {
                                foreach ($schemeGuidelinestblItem->schemeGuidelinesFootnoteModel as $footnoteModel){
                                    $footnoteList[] = '<div>' . $footnoteModel->footnote_content . '</div>';
                                }
                            }
                        
                            $subSectionString = implode('', $subSectionsList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $sectionCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $sectionHtml = '<div id="' . $schemeGuidelinestblItem->scheme_guidelines_id . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $schemeGuidelinestblItem->scheme_guidelines_no . '</h4><h4 class="font-weight-bold  pl-2">' . $schemeGuidelinestblItem->scheme_guidelines_title . '</h4></div></br><div>' . $schemeGuidelinestblItem->scheme_guidelines_content . '</div><div>' . $subSectionString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $sectionCss . $sectionHtml;
                        }
                    }
    
                    $sectionString = implode('', $Data);
                    $firstChapter[] = '<h2  id="ch-'.$schemeGuidelinesMain->scheme_guidelines_main_id.'" style="text-align:center!important;" ><strong>' .$schemeGuidelinesMain->scheme_guidelines_main_title . '</strong></h2>'. $sectionString;
                   }
                           
                }
                
            }

            // dd($firstChapter);
            // die();
           
            $mainFirstListContent = $firstChapter[0];
            // dd($mainFirstListContent);
            // die();
    
            return response()->json([
                'status' => 200,
                'data' => [
                    'schemeId' => $newSchemeGuidelines->new_scheme_guidelines_id,
                    'schemeNo' => $newSchemeGuidelines->new_scheme_guidelines_no ?? '',
                    'schemeName' => $newSchemeGuidelines->new_scheme_guidelines_title ?? '',
                    'enactmentDate' => $newSchemeGuidelines->enactment_date ?? '',
                    'enforcementDate' => $newSchemeGuidelines->enforcement_date ?? '',
                    'Ministry' => $newSchemeGuidelines->ministry ?? '',
                    'Preamble' => $newSchemeGuidelines->new_scheme_guidelines_description ?? '',
                    'schemeDescription' => '<div id="schemeHead"><h1 id=""><strong>' . ($newSchemeGuidelines->new_scheme_guidelines_title ?? '') . '</strong> </h1><div><strong>' . ($newSchemeGuidelines->new_scheme_guidelines_no ?? '') . '</strong></div><div><strong>' . ($newSchemeGuidelines->new_scheme_guidelines_date ?? '') . '</strong></div></div>' . implode('', $MainList) . '',
                    'mainFirstChapterContent' => $mainFirstListContent,
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

    public function pdf($id){
        try{
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);

        $combinedItems = NewSchemeGuidelines::where('new_scheme_guidelines_id', $id)
        ->with([
            'schemeGuidelinesMain' => function ($query) {
                $query->with(['schemeGuidelinestbl' => function ($query) {
                    $query->orderBy('scheme_guidelines_rank');
                }])->orderBy('scheme_guidelines_main_rank'); // Sort ruleMain by rule_main_rank
            },
            'schemeGuidelinesMain.schemeGuidelinestbl.schemeGuidelinesSub', 'schemeGuidelinesMain.schemeGuidelinestbl.schemeGuidelinesFootnoteModel', 'schemeGuidelinesMain.schemeGuidelinestbl.schemeGuidelinesSub.schemeGuidelinesSubFootnoteModel'
        ])
        ->get();
        $pdf = FacadePdf::loadView('admin.SchemeGuidelines.pdf', [
            'combinedItems' => $combinedItems,
        ]);

        return $pdf->download("{$combinedItems[0]->new_scheme_guidelines_title}.pdf");
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
