<?php

namespace App\Http\Controllers;

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

class MainOrderController extends Controller
{
    public function index($id)
    {
        $act_id = $id;
        $act = Act::where('act_id', $act_id)->first();
        $new_order = NewOrder::where('act_id', $act_id)->get();
       
      
        $perPage = request()->get('perPage') ?: 10;
        $page = request()->get('page') ?: 1;
        $slicedItems = array_slice($new_order->toArray(), ($page - 1) * $perPage, $perPage);

        $paginatedCollection = new LengthAwarePaginator(
            $slicedItems,
            count($new_order),
            $perPage,
            $page
        );

        $paginatedCollection->appends(['perPage' => $perPage]);

        $paginatedCollection->withPath(request()->url());
        return view('admin.MainOrder.index', compact('act','act_id','paginatedCollection'));
 
    }

    public function new_order($id){
        $category = Category::all();
        $states = State::all();
       return view('admin.MainOrder.new_order', compact('id','category','states',));

    }

    public function store_new_order(Request $request){
        try{
            $newOrder = new NewOrder();
            $newOrder->act_id = $request->act_id ?? null;
            $newOrder->new_order_title = $request->new_order_title;
            $newOrder->save();

            return redirect()->route('get_orders', ['id' => $newOrder->act_id])->with('success', 'Scheme/Guidelines created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());
            return redirect()->route('get_orders', ['id' => $newOrder->act_id])->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }

    public function edit_new_order($id){
        $new_order_id = $id;
        $newOrder = NewOrder::where('new_order_id', $new_order_id)->with('act')->first();
       
        $mainsequence = OrderMain::where('new_order_id', $id)
        ->with('mainTypeOrder') 
        ->get()
        ->map(function ($orderMain) {
            // Sort the ruletbl collection by rules_rank in ascending order
            $orderMain->load(['ordertbl' => function ($query) {
                $query->orderBy('orders_rank');
            }]);
            return $orderMain;
        })
        ->sortBy('order_main_rank');
       

        $perPage = request()->get('perPage') ?: 10;
        $page = request()->get('page') ?: 1;
        $slicedItems = array_slice($mainsequence->toArray(), ($page - 1) * $perPage, $perPage);

        $paginatedCollection = new LengthAwarePaginator(
            $slicedItems,
            count($mainsequence),
            $perPage,
            $page
        );

        $paginatedCollection->appends(['perPage' => $perPage]);

        $paginatedCollection->withPath(request()->url());
        // dd($paginatedCollection);
        // die();
        
        return view('admin.MainOrder.show', compact('newOrder','paginatedCollection'));     
   
    }

    public function update_new_order(Request $request, $id){
        try {
           
            $newOrder = NewOrder::find($id);
            $newOrder->new_order_title = $request->new_order_title;
            $newOrder->ministry = $request->ministry;
            $newOrder->new_order_no = $request->new_order_no ?? null;
            $newOrder->new_order_date = $request->new_order_date ?? null;
            $newOrder->enactment_date = $request->enactment_date ?? null;
            $newOrder->enforcement_date = $request->enforcement_date ?? null;
            $newOrder->new_order_description = $request->new_order_description ?? null;
            $newOrder->new_order_footnote_description = $request->new_order_footnote_description;
            $newOrder->update();


            return redirect()->back()->with('success', 'Order Updated Successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $newOrder = NewOrder::find($id);
        $mtype = MainTypeOrder::all();
        $stype = SubTypeOrder::all();
       

        
        return view('admin.MainOrder.create', compact('newOrder','mtype','stype'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        try {

            $newOrder = NewOrder::find($id);
            $newOrder->update([
                'category_id' => $request->category_id,
                'state_id' => $request->state_id ?? null,
                'new_order_title' => $request->new_order_title,
            ]);
        
            $k = 0;
            foreach ($request->order_maintype_id as $key => $order_maintype_id) {
                $lastRank = OrderMain::max('order_main_rank');
                $lastRank = ceil(floatval($lastRank));
                $lastRank = max(0, $lastRank);
                $lastRank = (int) $lastRank;

                if($lastRank){
                  $k=   $lastRank;
                }

                $orderMain = new OrderMain();
                $orderMain->new_order_id = $newOrder->new_order_id;
                $orderMain->order_main_rank = $k + 1;
                $orderMain->act_id = $newOrder->act_id ?? null;
                $orderMain->order_maintype_id = $order_maintype_id;
                $orderMain->order_main_title = $request->order_main_title[$key] ?? null;
                $orderMain->save();

                if (isset($request->order_subtypes_id[$key])) {
                    $order_subtypes_id = $request->order_subtypes_id[$key] ?? null;
                  

                    $i = 0;
                    foreach ($request->section_title[$key] as $index => $sectiontitle) {
                        $currentSectionNo = $request->section_no[$key][$index];
                        $lastRgltnRank= OrderTable::max('orders_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                            $i = $lastRgltnRank;
                        }
                        
                        $section = OrderTable::create([
                            'new_order_id' => $newOrder->new_order_id,
                            'orders_rank' => $i + 1,
                            'orders_no' => $currentSectionNo,
                            'order_main_id' => $orderMain->order_main_id,
                            'order_subtypes_id' => $order_subtypes_id,
                            'orders_title' => $sectiontitle,
                        ]);   
                    }
                }
                
            }

            return redirect()->route('edit_new_order', ['id' => $newOrder->new_order_id])->with('success', 'Index added successfully');

        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }

    public function add_below_new_order_maintype(Request $request, $newschmid, $id){
         
          $mainOrder = OrderMain::where('order_main_id',$id)->with('NewOrder')->first();
        
        
          $mtype = MainTypeOrder::all();
        $stype = SubTypeOrder::all();
        //   dd($stype);
        //   die();
      
          return view('admin.MainOrder.add_new_order_maintype', compact('mainOrder','mtype','stype'));
    }

   public function store_order_maintype(Request $request){
    try {

       
        $id =  $request->new_order_id;
        $k =  $request->click_main_rank;
        $order_main_id =  $request->order_main_id;
        $newOrder = NewOrder::find($id);
        $newOrder->update([
            'category_id' => $request->category_id,
            'state_id' => $request->state_id ?? null,
            'new_order_title' => $request->new_order_title,
        ]);
    
        foreach ($request->order_maintype_id as $key => $order_maintype_id) {
            $nextRank = OrderMain::where('new_order_id', $id)
            ->where('order_main_rank', '>', $k)
            ->min('order_main_rank');

            if ($nextRank) {
                $rank = ($k + $nextRank) / 2;
            } else {
                // If there's no next rank, add a small value to $i
                $rank = $k + 0.001;
            }


            
                $orderMain = new OrderMain();
                $orderMain->new_order_id = $newOrder->new_order_id;
                $orderMain->order_main_rank = $rank;
                $orderMain->act_id = $newOrder->act_id ?? null;
                $orderMain->order_maintype_id = $order_maintype_id;
                $orderMain->order_main_title = $request->order_main_title[$key] ?? null;
                $orderMain->save();

                if (isset($request->order_subtypes_id[$key])) {
                    $order_subtypes_id = $request->order_subtypes_id[$key] ?? null;
                 

                    $i = 0;
                    foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            
                            $currentSectionNo = $request->section_no[$key][$index];
                            $lastRgltnRank= OrderTable::max('orders_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = OrderTable::create([
                                'new_order_id' => $newOrder->new_order_id,
                                'orders_rank' => $i + 1,
                                'orders_no' => $currentSectionNo,
                                'order_main_id' => $orderMain->order_main_id,
                                'order_subtypes_id' => $order_subtypes_id,
                                'orders_title' => $sectiontitle,
                            ]);
                    }
                }
            
        }

        return redirect()->route('edit_new_order', ['id' => $newOrder->new_order_id])->with('success', 'Index added successfully');

    } catch (\Exception $e) {
        \Log::error('Error creating Act: ' . $e->getMessage());

        return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
    }
   }

   public function delete_order_maintype($id){
    try {
        $mainOrder = OrderMain::findOrFail($id);
        $mainOrder->delete();
        Session::flash('success', 'Main order deleted successfully.');
    } catch (\Exception $e) {
        Session::flash('error', 'Failed to delete RuleMain.');
    }
    return redirect()->back()->with('flash_timeout', 10);
   }

   public function edit_orderTable(Request $request, $id){
        $currentPage = $request->page;
        $orderTable = OrderTable::with(['mainOrder', 'mainOrder.newOrder', 'orderFootnoteModel' => function ($query) {
            $query->whereNull('order_sub_id');
        }])->where('orders_id', $id)->firstOrFail(); 

        $orderSubs = OrderSub::where('orders_id',$id)->with('orderSubFootnoteModel')->get();

        return view('admin.MainOrder.edit', compact('orderTable','orderSubs','currentPage'));
   }

   public function update_main_order(Request $request,$id){
    try {

       
        $currentPage = $request->currentPage;
        $new_order_id = $request->new_order_id;
        $order_main_id = $request->order_main_id;
        $order_subtypes_id = $request->order_subtypes_id;
        if ($request->has('order_main_id')) {
            $orderM = OrderMain::find($request->order_main_id);
           
            if ($orderM) {
                $orderM->order_main_title = $request->order_main_title;
                $orderM->update();
            }
        }
    
        $orderT = OrderTable::find($id);
        
        
        if ($orderT) {
            $orderT->orders_content = $request->orders_content ?? null;
            $orderT->orders_title = $request->orders_title ?? null;
            $orderT->orders_no = $request->orders_no ?? null;
            $orderT->update();

            if ($request->has('sec_footnote_content')) {
                $item = $request->sec_footnote_content;
                if ($request->has('sec_footnote_id')) {
        
                    $footnote_id = $request->sec_footnote_id;
        
                    if (isset($footnote_id)) {
                       
                        $foot = MainOrderFootnote::find($footnote_id);

                        if ($foot) {
                            $foot->footnote_content = $item ?? null;
                            $foot->update();
                        }
                    }
                }else {
                    
                        $footnote = new MainOrderFootnote();
                        $footnote->orders_id = $id ?? null;
                        $footnote->new_order_id = $new_order_id ?? null;
                        $footnote->footnote_content = $item ?? null;
                        $footnote->save();
                    }
            }  
        }


        if ($request->has('order_sub_no')) {
            foreach ($request->order_sub_no as $key => $item) {
                $order_sub_no = $request->order_sub_no[$key] ?? null;
                $order_sub_content = $request->order_sub_content[$key] ?? null;
                 
                // Check if sub_section_id is present and valid
                if ($order_sub_no && $existingSubOrder = OrderSub::find($order_sub_no)) {
                    $existingSubOrder->update([
                        'order_sub_no' => $item,
                        'order_sub_content' => $order_sub_content,
                    ]);

                    if ($request->has('sub_footnote_content') && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                        foreach ($request->sub_footnote_content[$key] as $kys => $footnote_content) {
                            
                            $footnote_id = $request->sub_footnote_id[$key][$kys] ?? null;
                            if ($footnote_id && $foot = MainOrderFootnote::find($footnote_id)) {
                                $foot->update(['footnote_content' => $footnote_content]);
                            }
                            else {
                             // Create new footnote if ID is not provided or invalid
                                $footnote = new MainOrderFootnote();
                                $footnote->order_sub_id = $order_sub_id;
                                $footnote->orders_id = $id ?? null;
                                $footnote->new_order_id = $new_order_id ?? null;
                                $footnote->footnote_content = $footnote_content ?? null;
                                $footnote->save();
                            }
                        }
                    }
                } else {

                    $i = 0;
                    $lastSubRgltnRank= OrderSub::max('order_sub_rank');
                    $lastSubRuleRank = ceil(floatval($lastSubRgltnRank));
                    $lastSubRgltnRank = max(0, $lastSubRgltnRank);
                    $lastSubRgltnRank = (int) $lastSubRgltnRank;

                    if($lastSubRgltnRank){
                       $i = $lastSubRgltnRank;
                    }   

                        $subsec = new OrderSub();
                        $subsec->orders_id = $id ?? null;
                        $subsec->order_main_id = $order_main_id ?? null;
                        $subsec->order_sub_rank = $i + 1;
                        $subsec->order_subtypes_id = $order_subtypes_id;
                        $subsec->order_sub_no = $item ?? null;
                        $subsec->new_order_id = $new_order_id ?? null;
                        $subsec->order_sub_content = $order_sub_content ?? null;
                        $subsec->save();
        
                    // Handle footnotes for the new sub_section
                    if ($request->has('sub_footnote_content') && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                        foreach ($request->sub_footnote_content[$key] as $kys => $footnote_content) {
                      
                            $footnote = new MainOrderFootnote();
                            $footnote->order_sub_id = $subsec->order_sub_id;
                            $footnote->orders_id = $id ?? null;
                            $footnote->new_order_id = $new_order_id ?? null;
                            $footnote->footnote_content = $footnote_content ?? null;
                            $footnote->save();
                        }
                    }
                }
            }
        }
        

        return redirect()->route('edit_new_order', ['id' => $new_order_id,'page' => $currentPage])->with('success', 'updated successfully');
    } catch (\Exception $e) {
        \Log::error('Error updating Act: ' . $e->getMessage());
        return redirect()->route('edit_new_order', ['id' => $new_order_id])->withErrors(['error' => 'Failed to update Section. Please try again.' . $e->getMessage()]);
    }

   }

   public function view_order_sub(Request $request, $id){
        $orderSub = OrderSub::where('orders_id', $id)->get();

        if ($orderSub->isEmpty()) {
            // If $regulationSub is empty, redirect back with a flash message
            return redirect()->back()->with('error', 'No data found.');
        }

        return view('admin.MainOrder.view_order_sub', compact('orderSub'));
   }

   public function delete_order_sub(Request $request, $id){
    try {
        $orderSub = OrderSub::findOrFail($id);
        $orderSub->delete();
        Session::flash('success', 'deleted successfully.');
    } catch (\Exception $e) {
        Session::flash('error', 'Failed to delete RuleMain.');
    }
    return redirect()->back()->with('flash_timeout', 10);
   }

   public function delete_orderstbl($id){
    try {
        $orderTable = OrderTable::findOrFail($id);
        $orderTable->delete();
        Session::flash('success', 'deleted successfully.');
    } catch (\Exception $e) {
        Session::flash('error', 'Failed to delete RuleMain.');
    }
    return redirect()->back()->with('flash_timeout', 10);
   }

   public function add_below_new_ordertbl(Request $request, $odrMId, $id){
    $currentPage = $request->page;
    $orderTable = OrderTable::with(['mainOrder', 'mainOrder.newOrder', 'orderFootnoteModel' => function ($query) {
        $query->whereNull('order_sub_id');
    }])->where('orders_id', $id)->firstOrFail(); 

    // dd($schemeGuidelinesTable);
    // die();

    return view('admin.MainOrder.add_new_ordertbl', compact('orderTable','currentPage'));
   }


   public function add_new_ordertbl(Request $request){
    try {

      
        $id = $request->click_order_id;
        $currentPage = $request->currentPage;
        $i = $request->click_order_rank;
        $new_order_id = $request->new_order_id;
        $order_main_id = $request->order_main_id;
        $order_subtypes_id = $request->order_subtypes_id;

        $nextRank = OrderTable::where('order_main_id', $order_main_id)
        ->where('orders_rank', '>', $i)
        ->min('orders_rank');

        if ($nextRank) {
            $rank = ($i + $nextRank) / 2;
        } else {
            // If there's no next rank, add a small value to $i
            $rank = $i + 0.001;
        }

        $orderT = new OrderTable;
        $orderT->new_order_id = $new_order_id;
        $orderT->order_main_id = $order_main_id;
        $orderT->order_subtypes_id = $order_subtypes_id;
        $orderT->orders_content = $request->orders_content ?? null;
        $orderT->orders_title = $request->orders_title ?? null;
        $orderT->orders_no = $request->orders_no ?? null;
        $orderT->orders_rank = $rank;
        $orderT->save();

        if ($request->has('sec_footnote_content')) {
            $item = $request->sec_footnote_content;
            $footnote = new MainOrderFootnote();
            $footnote->orders_id = $orderT->orders_id ?? null;
            $footnote->new_order_id = $new_order_id ?? null;
            $footnote->footnote_content = $item ?? null;
            $footnote->save();       
        }


        if ($request->has('order_sub_no')) {
            foreach ($request->order_sub_no as $key => $item) {
                $order_sub_content = $request->order_sub_content[$key] ?? null;
                
                    $i = 0;
                    $lastSubRgltnRank= OrderSub::max('order_sub_rank');
                    $lastSubRgltnRank = ceil(floatval($lastSubRgltnRank));
                    $lastSubRgltnRank = max(0, $lastSubRgltnRank);
                    $lastSubRgltnRank = (int) $lastSubRgltnRank;

                    if($lastSubRgltnRank){
                       $i = $lastSubRgltnRank;
                    }   

                    $subsec = new OrderSub();
                    $subsec->orders_id = $orderT->orders_id ?? null;
                    $subsec->order_main_id = $order_main_id ?? null;
                    $subsec->order_sub_rank = $i + 1;
                    $subsec->order_subtypes_id = $order_subtypes_id;
                    $subsec->order_sub_no = $item ?? null;
                    $subsec->new_order_id = $new_order_id ?? null;
                    $subsec->order_sub_content = $order_sub_content ?? null;
                    $subsec->save();
        
                    // Handle footnotes for the new sub_section
                    if ($request->has('sub_footnote_content') && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                        foreach ($request->sub_footnote_content[$key] as $kys => $footnote_content) {
                      
                            $footnote = new MainOrderFootnote();
                            $footnote->order_sub_id = $subsec->order_sub_id;
                            $footnote->orders_id = $orderT->orders_id;
                            $footnote->new_order_id = $new_order_id ?? null;
                            $footnote->footnote_content = $footnote_content ?? null;
                            $footnote->save();
                        }
                    }
                
            }
        }
        

        return redirect()->route('edit_new_order', ['id' => $new_order_id,'page' => $currentPage])->with('success', 'updated successfully');
    } catch (\Exception $e) {
        \Log::error('Error updating Act: ' . $e->getMessage());
        return redirect()->route('edit_new_order', ['id' => $new_order_id])->withErrors(['error' => 'Failed to update. Please try again.' . $e->getMessage()]);
    }
   }


   public function delete_new_order($id){
    try {

        $newOrder = NewOrder::findOrFail($id);
        $newOrder->delete();
        Session::flash('success', 'deleted successfully.');
    } catch (\Exception $e) {
        Session::flash('error', 'Failed to delete RuleMain.');
    }
    return redirect()->back()->with('flash_timeout', 10);
   }
   public function delete_order_footnote(Request $request,$id){
    try {

        $orderFootnote = MainOrderFootnote::findOrFail($id);
        $orderFootnote->delete();
        Session::flash('success', 'deleted successfully.');
    } catch (\Exception $e) {
        Session::flash('error', 'Failed to delete RuleMain.');
    }
    return redirect()->back()->with('flash_timeout', 10);
   }

   public function view_new_order(Request $request, $id){
    $currentPage = $request->query('page');
    $newOrder = NewOrder::findOrFail($id);
    return view('admin.MainOrder.view_new_order', compact('newOrder','currentPage'));
    
   }

   public function export_order_pdf(Request $request, $id){
        try {
            ini_set('memory_limit', '1024M');
            
            // Create Dompdf instance with options
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isJavascriptEnabled', true);
            $dompdf = new Dompdf($options);

            // Fetch data

            $newOrder = NewOrder::where('new_order_id', $id)
            ->with([
                'orderMain' => function ($query) {
                    $query->with(['ordertbl' => function ($query) {
                        $query->orderBy('orders_rank');
                    }])->orderBy('order_main_rank'); // Sort ruleMain by rule_main_rank
                },
                'orderMain.ordertbl.orderSub', 'orderMain.ordertbl.orderFootnoteModel', 'orderMain.ordertbl.orderSub.orderSubFootnoteModel'
            ])
            ->get();

            $pdf = FacadePdf::loadView('admin.MainOrder.pdf', ['combinedItems' => $newOrder]);
            

            // Download PDF with a meaningful file name
            return $pdf->download("{$newOrder[0]->new_order_title}.pdf");
        } catch (\Exception $e) {
            // Handle any errors
            return redirect()->back()->with('error', 'An error occurred while generating PDF: ' . $e->getMessage());
        }
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
