<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\ActSummaryRelation;
use App\Models\NewRule;
use App\Models\RuleMain;
use App\Models\MainTypeRule;
use App\Models\SubTypeRule;
use App\Models\PartsType;
use App\Models\RuleTable;
use App\Models\MainRuleFootnote;
use App\Models\RuleSub;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Dompdf\Dompdf;
use Dompdf\Options;

class RuleApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        try {
            $new_rule = NewRule::where('act_id', $id)->get();
            
            return response()->json([
                'status' => 200,
                'data' =>   [
                    'new_rule' => $new_rule,
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
        $newRule = NewRule::where('new_rule_id', $id)->first();
        $combinedItems = NewRule::where('new_rule_id', $id)
        ->with([
            'ruleMain' => function ($query) {
                $query->with(['ruletbl' => function ($query) {
                    $query->orderBy('rules_rank');
                }])->orderBy('rule_main_rank'); // Sort ruleMain by rule_main_rank
            },
            'ruleMain.ruletbl.ruleSub',
            'ruleMain.ruletbl.ruleFootnoteModel',
            'ruleMain.ruletbl.ruleSub.ruleSubFootnoteModel'
        ])
        ->get();

        $sideBarList = [];

        foreach ($combinedItems as $newRule) {
            if (isset($newRule->ruleMain)) {
                foreach ($newRule->ruleMain as $ruleMain) {
                    $Data = [
                        'ChapterId' => $ruleMain->rule_main_id,
                        'Name' => $ruleMain->rule_main_title,
                        'SubString' => []
                    ];
                    if (!empty($ruleMain->ruletbl)){
                        foreach ($ruleMain->ruletbl as $ruletblItem) {
                            $Data['SubString'][] = [
                                'SectionId' =>  $ruletblItem->rules_id,
                                'Name' => $ruletblItem->rules_title,
                                
                            ];     
                        }    
                    }
                  
                    $sideBarList[] = $Data;
                }
            }
        }
        
        $MainList = [];
        foreach ($combinedItems as $newRule) {
            if (isset($newRule->ruleMain)) {
                foreach ($newRule->ruleMain as $ruleMain) {
                $Data = [];
                if (!empty($ruleMain->ruletbl)){
                    foreach ($ruleMain->ruletbl as $ruletblItem) {
                        $subSectionsList = [];
                        if (!empty($ruletblItem->ruleSub)) {
                            foreach ($ruletblItem->ruleSub as $ruleSubItem) {
                                $subSectionsList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $ruleSubItem->rule_sub_no . '</div><div>' . $ruleSubItem->rule_sub_content . '</div></div>';
                            }
                        }
                      
                        $footnoteList = [];
                        if (!empty($ruletblItem->ruleFootnoteModel)) {
                            foreach ($ruletblItem->ruleFootnoteModel as $footnoteModel){
                                $footnoteList[] = '<div>' . $footnoteModel->footnote_content . '</div>';
                            }
                        }
                    
                        $subSectionString = implode('', $subSectionsList);
                        $footnoteString = implode('', $footnoteList);
                    
                        // Custom CSS to modify the line-height of <p> inside .judgement-text
                        $sectionCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                    
                        // Construct the HTML content including the custom CSS
                        $sectionHtml = '<div id="' . $ruletblItem->rules_id . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $ruletblItem->rules_no . '</h4><h4 class="font-weight-bold  pl-2">' . $ruletblItem->rules_title . '</h4></div></br><div>' . $ruletblItem->rules_content . '</div><div>' . $subSectionString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    
                        // Append the custom CSS and generated section HTML to $Data array
                        $Data[] = $sectionCss . $sectionHtml;
                    }
                }

                $sectionString = implode('', $Data);
                $MainList[] = '<h2  id="ch-'.$ruleMain->rule_main_id.'" style="text-align:center!important;" ><strong>' .$ruleMain->rule_main_title . '</strong></h2>'. $sectionString;
               }
                       
            }
            
        }

        $firstItems = NewRule::where('new_rule_id', $id)
        ->with([
            'ruleMain' => function ($query) {
                $query->with(['ruletbl' => function ($query) {
                    $query->orderBy('rules_rank')->first(); // Retrieve only the first ruletbl
                }])->orderBy('rule_main_rank')->first(); // Retrieve only the first ruleMain
            },
            'ruleMain.ruletbl.ruleSub',
            'ruleMain.ruletbl.ruleFootnoteModel',
            'ruleMain.ruletbl.ruleSub.ruleSubFootnoteModel'
        ])
        ->get();


        $firstChapter = [];
        foreach ($firstItems as $newRule) {
            if (isset($newRule->ruleMain)) {
                foreach ($newRule->ruleMain as $ruleMain) {
                $Data = [];
                if (!empty($ruleMain->ruletbl)){
                    foreach ($ruleMain->ruletbl as $ruletblItem) {
                        $subSectionsList = [];
                        if (!empty($ruletblItem->ruleSub)) {
                            foreach ($ruletblItem->ruleSub as $ruleSubItem) {
                                $subSectionsList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $ruleSubItem->rule_sub_no . '</div><div>' . $ruleSubItem->rule_sub_content . '</div></div>';
                            }
                        }
                      
                        $footnoteList = [];
                        if (!empty($ruletblItem->ruleFootnoteModel)) {
                            foreach ($ruletblItem->ruleFootnoteModel as $footnoteModel){
                                $footnoteList[] = '<div>' . $footnoteModel->footnote_content . '</div>';
                            }
                        }
                    
                        $subSectionString = implode('', $subSectionsList);
                        $footnoteString = implode('', $footnoteList);
                    
                        // Custom CSS to modify the line-height of <p> inside .judgement-text
                        $sectionCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                    
                        // Construct the HTML content including the custom CSS
                        $sectionHtml = '<div id="' . $ruletblItem->rules_id . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $ruletblItem->rules_no . '</h4><h4 class="font-weight-bold  pl-2">' . $ruletblItem->rules_title . '</h4></div></br><div>' . $ruletblItem->rules_content . '</div><div>' . $subSectionString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    
                        // Append the custom CSS and generated section HTML to $Data array
                        $Data[] = $sectionCss . $sectionHtml;
                    }
                }

                $sectionString = implode('', $Data);
                $firstChapter[] = '<h2  id="ch-'.$ruleMain->rule_main_id.'" style="text-align:center!important;" ><strong>' .$ruleMain->rule_main_title . '</strong></h2>'. $sectionString;
               }
                       
            }
            
        }

        
        $mainFirstListContent = $firstChapter[0];
      

        return response()->json([
            'status' => 200,
            'data' => [
                'ruleId' => $newRule->new_rule_id,
                'ruleNo' => $newRule->new_rule_no ?? '',
                'ruleName' => $newRule->new_rule_title ?? '',
                'enactmentDate' => $newRule->enactment_date ?? '',
                'enforcementDate' => $newRule->enforcement_date ?? '',
                'Ministry' => $newRule->ministry ?? '',
                'Preamble' => $newRule->new_rule_description ?? '',
                'ruleDescription' => '<div id="ruleHead"><h1 id=""><strong>' . ($newRule->new_rule_title ?? '') . '</strong> </h1><div><strong>' . ($newRule->new_rule_no ?? '') . '</strong></div><div><strong>' . ($newRule->new_rule_date ?? '') . '</strong></div></div>' . implode('', $MainList) . '',
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

        $combinedItems = NewRule::where('new_rule_id', $id)
        ->with([
            'ruleMain' => function ($query) {
                $query->with(['ruletbl' => function ($query) {
                    $query->orderBy('rules_rank');
                }])->orderBy('rule_main_rank'); // Sort ruleMain by rule_main_rank
            },
            'ruleMain.ruletbl.ruleSub',
            'ruleMain.ruletbl.ruleFootnoteModel',
            'ruleMain.ruletbl.ruleSub.ruleSubFootnoteModel'
        ])
        ->get();
        $pdf = FacadePdf::loadView('admin.MainRule.pdf', [
            'combinedItems' => $combinedItems,
        ]);

        return $pdf->download("{$combinedItems[0]->new_rule_title}.pdf");
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
