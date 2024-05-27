<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\Category;
use App\Models\State;
use App\Models\MainTypeRegulation;
use App\Models\SubTypeRegulation;
use App\Models\PartsType;
use App\Models\RegulationMain;
use App\Models\RegulationTable;
use App\Models\NewRegulation;
use App\Models\RegulationSub;
use App\Models\MainRegulationFootnote;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Dompdf\Dompdf;
use Dompdf\Options;

class RegulationApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        
        try {
            $new_regulation = NewRegulation::where('act_id', $id)->get();
            return response()->json([
                'status' => 200,
                'data' =>   [
                    'new_regulation' => $new_regulation,
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
            $newRegulation = NewRegulation::where('new_regulation_id', $id)->first();

            $combinedItems = NewRegulation::where('new_regulation_id', $id)
            ->with([
                'regulationMain' => function ($query) {
                    $query->with(['regulationtbl' => function ($query) {
                        $query->orderBy('regulations_rank');
                    }])->orderBy('regulation_main_rank'); // Sort ruleMain by rule_main_rank
                },
                'regulationMain.regulationtbl.regulationSub', 'regulationMain.regulationtbl.regulationFootnoteModel', 'regulationMain.regulationtbl.regulationSub.regulationSubFootnoteModel'
            ])
            ->get();

            // dd($combinedItems);
            // die();
    
            $sideBarList = [];
    
            foreach ($combinedItems as $newRegulation){
                if (isset($newRegulation->regulationMain)) {
                    foreach ($newRegulation->regulationMain as $regulationMain){
                        $Data = [
                            'ChapterId' => $regulationMain->regulation_main_id,
                            'Name' => $regulationMain->regulation_main_title,
                            'SubString' => []
                        ];
                        if (!empty($regulationMain->regulationtbl)){
                            foreach ($regulationMain->regulationtbl as $regulationtblItem) {
                                $Data['SubString'][] = [
                                    'SectionId' =>  $regulationtblItem->regulations_id,
                                    'Name' => $regulationtblItem->regulations_title,
                                    
                                ];     
                            }    
                        }
                      
                        $sideBarList[] = $Data;
                    }
                }
            }
            
          
            $MainList = [];
            foreach ($combinedItems as $newRegulation){
                if (isset($newRegulation->regulationMain)) {
                    foreach ($newRegulation->regulationMain as $regulationMain){
                    $Data = [];
                    if (!empty($regulationMain->regulationtbl)){
                        foreach ($regulationMain->regulationtbl as $regulationtblItem) {
                            $subSectionsList = [];
                            if (!empty($regulationtblItem->regulationSub)) {
                                foreach ($regulationtblItem->regulationSub as $regulationSubItem){
                                    $subSectionsList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $regulationSubItem->regulation_sub_no . '</div><div>' . $regulationSubItem->regulation_sub_content . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($regulationtblItem->regulationFootnoteModel)) {
                                foreach ($regulationtblItem->regulationFootnoteModel as $footnoteModel){
                                    $footnoteList[] = '<div>' . $footnoteModel->footnote_content . '</div>';
                                }
                            }
                        
                            $subSectionString = implode('', $subSectionsList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $sectionCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $sectionHtml = '<div id="' . $regulationtblItem->regulations_id . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $regulationtblItem->regulations_no . '</h4><h4 class="font-weight-bold  pl-2">' . $regulationtblItem->regulations_title . '</h4></div></br><div>' . $regulationtblItem->regulations_content . '</div><div>' . $subSectionString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $sectionCss . $sectionHtml;
                        }
                    }
    
                    $sectionString = implode('', $Data);
                    $MainList[] = '<h2  id="ch-'.$regulationMain->regulation_main_id.'" style="text-align:center!important;" ><strong>' .$regulationMain->regulation_main_title . '</strong></h2>'. $sectionString;
                   }
                           
                }
                
            }

            
    
            $firstItems = NewRegulation::where('new_regulation_id', $id)
            ->with([
                'regulationMain' => function ($query) {
                    $query->with(['regulationtbl' => function ($query) {
                        $query->orderBy('regulations_rank')->first(); // Retrieve only the first ruletbl
                    }])->orderBy('regulation_main_rank')->first(); // Retrieve only the first ruleMain
                },
                'regulationMain.regulationtbl.regulationSub', 'regulationMain.regulationtbl.regulationFootnoteModel', 'regulationMain.regulationtbl.regulationSub.regulationSubFootnoteModel'
            ])
            ->get();

            // dd($firstItems);
            // die();
    
    
            $firstChapter = [];
            foreach ($firstItems as $newRegulation){
                if (isset($newRegulation->regulationMain)) {
                    foreach ($newRegulation->regulationMain as $regulationMain){
                    $Data = [];
                    if (!empty($regulationMain->regulationtbl)){
                        foreach ($regulationMain->regulationtbl as $regulationtblItem) {
                            $subSectionsList = [];
                            if (!empty($regulationtblItem->regulationSub)) {
                                foreach ($regulationtblItem->regulationSub as $regulationSubItem){
                                    $subSectionsList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $regulationSubItem->regulation_sub_no . '</div><div>' . $regulationSubItem->regulation_sub_content . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($regulationtblItem->regulationFootnoteModel)) {
                                foreach ($regulationtblItem->regulationFootnoteModel as $footnoteModel){
                                    $footnoteList[] = '<div>' . $footnoteModel->footnote_content . '</div>';
                                }
                            }
                        
                            $subSectionString = implode('', $subSectionsList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $sectionCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $sectionHtml = '<div id="' . $regulationtblItem->regulations_id . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $regulationtblItem->regulations_no . '</h4><h4 class="font-weight-bold  pl-2">' . $regulationtblItem->regulations_title . '</h4></div></br><div>' . $regulationtblItem->regulations_content . '</div><div>' . $subSectionString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $sectionCss . $sectionHtml;
                        }
                    }
    
                    $sectionString = implode('', $Data);
                    $firstChapter[] = '<h2  id="ch-'.$regulationMain->regulation_main_id.'" style="text-align:center!important;" ><strong>' .$regulationMain->regulation_main_title . '</strong></h2>'. $sectionString;
                   }
                           
                }
                
            }

           
            $mainFirstListContent = $firstChapter[0];
          
    
            return response()->json([
                'status' => 200,
                'data' => [
                    'regulationId' => $newRegulation->new_regulation_title,
                    'regulationNo' => $newRegulation->new_regulation_no ?? '',
                    'regulationName' => $newRegulation->new_regulation_title ?? '',
                    'enactmentDate' => $newRegulation->enactment_date ?? '',
                    'enforcementDate' => $newRegulation->enforcement_date ?? '',
                    'Ministry' => $newRegulation->ministry ?? '',
                    'Preamble' => $newRegulation->new_regulation_description ?? '',
                    'regulationDescription' => '<div id="ruleHead"><h1 id=""><strong>' . ($newRegulation->new_regulation_title ?? '') . '</strong> </h1><div><strong>' . ($newRegulation->new_regulation_no ?? '') . '</strong></div><div><strong>' . ($newRegulation->new_regulation_date ?? '') . '</strong></div></div>' . implode('', $MainList) . '',
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
