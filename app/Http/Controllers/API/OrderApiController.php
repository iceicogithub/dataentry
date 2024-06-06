<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\Category;
use App\Models\State;
use App\Models\NewOrder;
use App\Models\MainOrderFootnote;
use App\Models\OrderMain;
use App\Models\OrderSub;
use App\Models\OrderTable;
use App\Models\MainTypeOrder;
use App\Models\SubTypeOrder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Dompdf\Dompdf;
use Dompdf\Options;

class OrderApiController extends Controller
{
   
    public function index($id)
    {
        
        try {
            $new_order = NewOrder::where('act_id', $id)->with('actSummary')->get();
            return response()->json([
                'status' => 200,
                'data' =>  $new_order
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

            $newOrder = NewOrder::where('new_order_id', $id)->first();

            $combinedItems = NewOrder::where('new_order_id', $id)
            ->with([
                'orderMain' => function ($query) {
                    $query->with(['ordertbl' => function ($query) {
                        $query->orderBy('orders_rank');
                    }])->orderBy('order_main_rank');
                },
                'orderMain.ordertbl.orderSub', 'orderMain.ordertbl.orderFootnoteModel', 'orderMain.ordertbl.orderSub.orderSubFootnoteModel'
            ])
            ->get();

            // dd($combinedItems);
            // die();
    
            $sideBarList = [];
    
            foreach ($combinedItems as $NewOrder){
                if (isset($NewOrder->orderMain)) {
                    foreach ($NewOrder->orderMain as $orderMain){
                        $Data = [
                            'ChapterId' => $orderMain->order_main_id,
                            'Name' => $orderMain->order_main_title,
                            'SubString' => []
                        ];
                        if (!empty($orderMain->ordertbl)){
                            foreach ($orderMain->ordertbl as $ordertblItem) {
                                $Data['SubString'][] = [
                                    'SectionId' =>  $ordertblItem->orders_id,
                                    'Name' => $ordertblItem->orders_title,
                                    
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
            foreach ($combinedItems as $NewOrder){
                if (isset($NewOrder->orderMain)) {
                    foreach ($NewOrder->orderMain as $orderMain){
                    $Data = [];
                    if (!empty($orderMain->ordertbl)){
                        foreach ($orderMain->ordertbl as $ordertblItem) {
                            $subSectionsList = [];
                            if (!empty($ordertblItem->orderSub)) {
                                foreach ($ordertblItem->orderSub as $orderSubItem){
                                    $subSectionsList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $orderSubItem->order_sub_no . '</div><div>' . $orderSubItem->order_sub_content . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($ordertblItem->orderFootnoteModel)) {
                                foreach ($ordertblItem->orderFootnoteModel as $footnoteModel){
                                    $footnoteList[] = '<div>' . $footnoteModel->footnote_content . '</div>';
                                }
                            }
                        
                            $subSectionString = implode('', $subSectionsList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $sectionCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $sectionHtml = '<div id="' . $ordertblItem->orders_id . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $ordertblItem->orders_no . '</h4><h4 class="font-weight-bold  pl-2">' . $ordertblItem->orders_title . '</h4></div></br><div>' . $ordertblItem->orders_content . '</div><div>' . $subSectionString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $sectionCss . $sectionHtml;
                        }
                    }
    
                    $sectionString = implode('', $Data);
                    $MainList[] = '<h2  id="ch-'.$orderMain->order_main_id.'" style="text-align:center!important;" ><strong>' .$orderMain->order_main_title . '</strong></h2>'. $sectionString;
                   }
                           
                }
                
            }

            // dd($MainList);
            // die();
    
            $firstItems = NewOrder::where('new_order_id', $id)
            ->with([
                'orderMain' => function ($query) {
                    $query->with(['ordertbl' => function ($query) {
                        $query->orderBy('orders_rank')->first(); // Retrieve only the first ruletbl
                    }])->orderBy('order_main_rank')->first(); // Retrieve only the first ruleMain
                },
                'orderMain.ordertbl.orderSub', 'orderMain.ordertbl.orderFootnoteModel', 'orderMain.ordertbl.orderSub.orderSubFootnoteModel'
            ])
            ->get();

            // dd($firstItems);
            // die();
    
    
            $firstChapter = [];
            foreach ($firstItems as $NewOrder){
                if (isset($NewOrder->orderMain)) {
                    foreach ($NewOrder->orderMain as $orderMain){
                    $Data = [];
                    if (!empty($orderMain->ordertbl)){
                        foreach ($orderMain->ordertbl as $ordertblItem) {
                            $subSectionsList = [];
                            if (!empty($ordertblItem->orderSub)) {
                                foreach ($ordertblItem->orderSub as $orderSubItem){
                                    $subSectionsList[] = '<div class="judgement-text" style="display:flex!important;align-items: baseline;"><div>' . $orderSubItem->order_sub_no . '</div><div>' . $orderSubItem->order_sub_content . '</div></div>';
                                }
                            }
                          
                            $footnoteList = [];
                            if (!empty($ordertblItem->orderFootnoteModel)) {
                                foreach ($ordertblItem->orderFootnoteModel as $footnoteModel){
                                    $footnoteList[] = '<div>' . $footnoteModel->footnote_content . '</div>';
                                }
                            }
                        
                            $subSectionString = implode('', $subSectionsList);
                            $footnoteString = implode('', $footnoteList);
                        
                            // Custom CSS to modify the line-height of <p> inside .judgement-text
                            $sectionCss = '<style>.judgement-text p { line-height: 1.3 !important; margin-bottom: 0.3rem !important;  }</style>';
                        
                            // Construct the HTML content including the custom CSS
                            $sectionHtml = '<div id="' . $ordertblItem->orders_id . '"><div style="display:flex!important;align-items: baseline;"><h4 class="font-weight-bold">' . $ordertblItem->orders_no . '</h4><h4 class="font-weight-bold  pl-2">' . $ordertblItem->orders_title . '</h4></div></br><div>' . $ordertblItem->orders_content . '</div><div>' . $subSectionString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                        
                            // Append the custom CSS and generated section HTML to $Data array
                            $Data[] = $sectionCss . $sectionHtml;
                        }
                    }
    
                    $sectionString = implode('', $Data);
                    $firstChapter[] = '<h2  id="ch-'.$orderMain->order_main_id.'" style="text-align:center!important;" ><strong>' .$orderMain->order_main_title . '</strong></h2>'. $sectionString;
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
                    'orderId' => $newOrder->new_order_title,
                    'orderNo' => $newOrder->new_order_no ?? '',
                    'orderName' => $newOrder->new_order_title ?? '',
                    'enactmentDate' => $newOrder->enactment_date ?? '',
                    'enforcementDate' => $newOrder->enforcement_date ?? '',
                    'Ministry' => $newOrder->ministry ?? '',
                    'Preamble' => $newOrder->new_order_description ?? '',
                    'regulationDescription' => '<div id="ruleHead"><h1 id=""><strong>' . ($newOrder->new_order_title ?? '') . '</strong> </h1><div><strong>' . ($newOrder->new_order_no ?? '') . '</strong></div><div><strong>' . ($newOrder->new_order_date ?? '') . '</strong></div></div>' . implode('', $MainList) . '',
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

        $combinedItems = NewOrder::where('new_order_id', $id)
        ->with([
            'orderMain' => function ($query) {
                $query->with(['ordertbl' => function ($query) {
                    $query->orderBy('orders_rank');
                }])->orderBy('order_main_rank');
            },
            'orderMain.ordertbl.orderSub', 'orderMain.ordertbl.orderFootnoteModel', 'orderMain.ordertbl.orderSub.orderSubFootnoteModel'
        ])
        ->get();
        $pdf = FacadePdf::loadView('admin.MainOrder.pdf', [
            'combinedItems' => $combinedItems,
        ]);

        return $pdf->download("{$combinedItems[0]->new_order_title}.pdf");
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
