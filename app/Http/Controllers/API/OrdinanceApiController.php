<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\Category;
use App\Models\State;
use App\Models\NewOrdinance;
use App\Models\MainOrdinanceFootnote;
use App\Models\OrdinanceMain;
use App\Models\OrdinanceSub;
use App\Models\OrdinanceTable;
use App\Models\MainTypeOrdinance;
use App\Models\SubTypeOrdinance;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Dompdf\Dompdf;
use Dompdf\Options;

class OrdinanceApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        
        try {
            $new_ordinance = NewOrdinance::where('act_id', $id)->with('actSummary')->get();
            return response()->json([
                'status' => 200,
                'data' =>  $new_ordinance
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

            $newOrdinance = NewOrdinance::where('new_ordinance_id', $id)->first();

            $combinedItems = NewOrdinance::where('new_ordinance_id', $id)
            ->with([
                'ordinanceMain' => function ($query) {
                    $query->with(['ordinancetbl' => function ($query) {
                        $query->orderBy('ordinances_rank');
                    }])->orderBy('ordinance_main_rank');
                },
                'ordinanceMain.ordinancetbl.ordinanceSub', 'ordinanceMain.ordinancetbl.ordinanceFootnoteModel', 'ordinanceMain.ordinancetbl.ordinanceSub.ordinanceSubFootnoteModel'
            ])
            ->get();

            // dd($combinedItems);
            // die();
    
            $sideBarList = [];
    
            foreach ($combinedItems as $NewOrdinance){
                if (isset($NewOrdinance->ordinanceMain)) {
                    foreach ($NewOrdinance->ordinanceMain as $ordinanceMain){
                        $Data = [
                            'ChapterId' => $ordinanceMain->ordinance_main_id,
                            'Name' => $ordinanceMain->ordinance_main_title,
                            'SubString' => []
                        ];
                        if (!empty($ordinanceMain->ordinancetbl)){
                            foreach ($ordinanceMain->ordinancetbl as $ordinancetblItem) {
                                $Data['SubString'][] = [
                                    'SectionId' =>  $ordinancetblItem->ordinances_id,
                                    'Name' => $ordinancetblItem->ordinances_title,
                                    
                                ];     
                            }    
                        }
                      
                        $sideBarList[] = $Data;
                    }
                }
            }
            // dd($sideBarList);
            // die();
          
            $MainList = [];
            foreach ($combinedItems as $NewOrdinance){
                if (isset($NewOrdinance->ordinanceMain)) {
                    foreach ($NewOrdinance->ordinanceMain as $ordinanceMain){
                    $Data = [];
                    if (!empty($ordinanceMain->ordinancetbl)){
                        foreach ($ordinanceMain->ordinancetbl as $ordinancetblItem) {
                            $subSectionsList = [];
                            if (!empty($ordinancetblItem->ordinanceSub)) {
                                foreach ($ordinancetblItem->ordinanceSub as $ordinanceSubItem){
                                    $subSectionsList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $ordinanceSubItem->ordinance_sub_no . '</div><div>' . $ordinanceSubItem->ordinance_sub_content . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($ordinancetblItem->ordinanceFootnoteModel)) {
                                foreach ($ordinancetblItem->ordinanceFootnoteModel as $footnoteModel){
                                    $footnoteList[] = '<div>' . $footnoteModel->footnote_content . '</div>';
                                }
                            }
                        
                            $subSectionString = implode('', $subSectionsList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $sectionCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $sectionHtml = '<div id="' . $ordinancetblItem->ordinances_id . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $ordinancetblItem->ordinances_no . '</h4><h4 class="font-weight-bold  pl-2">' . $ordinancetblItem->ordinances_title . '</h4></div></br><div>' . $ordinancetblItem->ordinances_content . '</div><div>' . $subSectionString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $sectionCss . $sectionHtml;
                        }
                    }
    
                    $sectionString = implode('', $Data);
                    $MainList[] = '<h2  id="ch-'.$ordinanceMain->ordinance_main_id.'" style="text-align:center!important;" ><strong>' .$ordinanceMain->ordinance_main_title . '</strong></h2>'. $sectionString;
                   }
                           
                }
                
            }

            // dd($MainList);
            // die();
    
            $firstItems = NewOrdinance::where('new_ordinance_id', $id)
            ->with([
                'ordinanceMain' => function ($query) {
                    $query->with(['ordinancetbl' => function ($query) {
                        $query->orderBy('ordinances_rank')->first(); // Retrieve only the first ruletbl
                    }])->orderBy('ordinance_main_rank')->first(); // Retrieve only the first ruleMain
                },
                'ordinanceMain.ordinancetbl.ordinanceSub', 'ordinanceMain.ordinancetbl.ordinanceFootnoteModel', 'ordinanceMain.ordinancetbl.ordinanceSub.ordinanceSubFootnoteModel'
            ])
            ->get();

            // dd($firstItems);
            // die();
    
    
            $firstChapter = [];
            foreach ($firstItems as $NewOrdinance){
                if (isset($NewOrdinance->ordinanceMain)) {
                    foreach ($NewOrdinance->ordinanceMain as $ordinanceMain){
                    $Data = [];
                    if (!empty($ordinanceMain->ordinancetbl)){
                        foreach ($ordinanceMain->ordinancetbl as $ordinancetblItem) {
                            $subSectionsList = [];
                            if (!empty($ordinancetblItem->ordinanceSub)) {
                                foreach ($ordinancetblItem->ordinanceSub as $ordinanceSubItem){
                                    $subSectionsList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $ordinanceSubItem->ordinance_sub_no . '</div><div>' . $ordinanceSubItem->ordinance_sub_content . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($ordinancetblItem->ordinanceFootnoteModel)) {
                                foreach ($ordinancetblItem->ordinanceFootnoteModel as $footnoteModel){
                                    $footnoteList[] = '<div>' . $footnoteModel->footnote_content . '</div>';
                                }
                            }
                        
                            $subSectionString = implode('', $subSectionsList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $sectionCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $sectionHtml = '<div id="' . $ordinancetblItem->ordinances_id . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $ordinancetblItem->ordinances_no . '</h4><h4 class="font-weight-bold  pl-2">' . $ordinancetblItem->ordinances_title . '</h4></div></br><div>' . $ordinancetblItem->ordinances_content . '</div><div>' . $subSectionString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $sectionCss . $sectionHtml;
                        }
                    }
    
                    $sectionString = implode('', $Data);
                    $firstChapter[] = '<h2  id="ch-'.$ordinanceMain->ordinance_main_id.'" style="text-align:center!important;" ><strong>' .$ordinanceMain->ordinance_main_title . '</strong></h2>'. $sectionString;
                   }
                           
                }
                
            }

            // dd($firstChapter);
            // die();
                $mainFirstListContent = '';
            if($firstChapter){
                $mainFirstListContent = $firstChapter[0];
            }
            
          
    
            return response()->json([
                'status' => 200,
                'data' => [
                    'ordinanceId' => $newOrdinance->new_ordinance_title,
                    'ordinanceNo' => $newOrdinance->new_ordinance_no ?? '',
                    'ordinanceName' => $newOrdinance->new_ordinance_title ?? '',
                    'enactmentDate' => $newOrdinance->enactment_date ?? '',
                    'enforcementDate' => $newOrdinance->enforcement_date ?? '',
                    'Ministry' => $newOrdinance->ministry ?? '',
                    'Preamble' => $newOrdinance->new_ordinance_description ?? '',
                    'ordinanceDescription' => '<div id="ruleHead"><h1 id=""><strong>' . ($newOrdinance->new_ordinance_title ?? '') . '</strong> </h1><div><strong>' . ($newOrdinance->new_ordinance_no ?? '') . '</strong></div><div><strong>' . ($newOrdinance->new_ordinance_date ?? '') . '</strong></div></div>' . implode('', $MainList) . '',
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

        $combinedItems = NewOrdinance::where('new_ordinance_id', $id)
        ->with([
            'ordinanceMain' => function ($query) {
                $query->with(['ordinancetbl' => function ($query) {
                    $query->orderBy('ordinances_rank');
                }])->orderBy('ordinance_main_rank');
            },
            'ordinanceMain.ordinancetbl.ordinanceSub', 'ordinanceMain.ordinancetbl.ordinanceFootnoteModel', 'ordinanceMain.ordinancetbl.ordinanceSub.ordinanceSubFootnoteModel'
        ])
        ->get();
        $pdf = FacadePdf::loadView('admin.MainOrdinance.pdf', [
            'combinedItems' => $combinedItems,
        ]);

        return $pdf->download("{$combinedItems[0]->new_ordinance_title}.pdf");
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
