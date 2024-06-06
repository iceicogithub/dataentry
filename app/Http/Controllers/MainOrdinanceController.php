<?php

namespace App\Http\Controllers;

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
class MainOrdinanceController extends Controller
{
    public function index(Request $request,$id)
    {
        $act_id = $id;
        $act = Act::where('act_id', $act_id)->first();
        $new_ordinance = NewOrdinance::where('act_id', $act_id)->orderBy('new_ordinance_id', 'desc')->paginate(10);
        $currentPage = $request->query('page', 1);
      
        return view('admin.MainOrdinance.index', compact('act','act_id','new_ordinance','currentPage'));
 
    }

    public function new_ordiance($id){
        $category = Category::all();
        $states = State::all();
       return view('admin.MainOrdinance.new_ordinance', compact('id','category','states',));

    }

    public function store_new_ordinance(Request $request){
        try{
            $newOrdinance = new NewOrdinance();
            $newOrdinance->act_id = $request->act_id ?? null;
            $newOrdinance->new_ordinance_title = $request->new_ordinance_title;
            $newOrdinance->save();

            return redirect()->route('get_ordinance', ['id' => $newOrdinance->act_id])->with('success', 'Ordinance created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Ordinance: ' . $e->getMessage());
            return redirect()->route('get_ordinance', ['id' => $newOrdinance->act_id])->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }

    public function edit_new_ordinance($id){
        $new_ordinance_id = $id;
        $newOrdinance = NewOrdinance::where('new_ordinance_id', $new_ordinance_id)->with('act')->first();
       
        $mainsequence = OrdinanceMain::where('new_ordinance_id', $id)
        ->with('mainTypeOrdinance') 
        ->get()
        ->map(function ($ordinanceMain) {
            $ordinanceMain->load(['ordinancetbl' => function ($query) {
                $query->orderBy('ordinances_rank');
            }]);
            return $ordinanceMain;
        })
        ->sortBy('ordinance_main_rank');

        
       

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
        
        return view('admin.MainOrdinance.show', compact('newOrdinance','paginatedCollection'));     
   
    }

    public function update_new_ordinance(Request $request, $id){
        try {
           
            $newOrdinance = NewOrdinance::find($id);
            $newOrdinance->new_ordinance_title = $request->new_ordinance_title;
            $newOrdinance->ministry = $request->ministry;
            $newOrdinance->new_ordinance_no = $request->new_ordinance_no ?? null;
            $newOrdinance->new_ordinance_date = $request->new_ordinance_date ?? null;
            $newOrdinance->enactment_date = $request->enactment_date ?? null;
            $newOrdinance->enforcement_date = $request->enforcement_date ?? null;
            $newOrdinance->new_ordinance_description = $request->new_ordinance_description ?? null;
            $newOrdinance->new_ordinance_footnote_description = $request->new_ordinance_footnote_description;
            $newOrdinance->update();


            return redirect()->back()->with('success', 'Ordinance Updated Successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Ordinance: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create Ordinance. Please try again.' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $newOrdinance = NewOrdinance::find($id);
        $mtype = MainTypeOrdinance::all();
        $stype = SubTypeOrdinance::all();
       

        
        return view('admin.MainOrdinance.create', compact('newOrdinance','mtype','stype'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        try {

            $newOrdinance = NewOrdinance::find($id);
            $newOrdinance->update([
                'category_id' => $request->category_id,
                'state_id' => $request->state_id ?? null,
                'new_ordinance_title' => $request->new_ordinance_title,
            ]);
        
            $k = 0;
            foreach ($request->ordinance_maintype_id as $key => $ordinance_maintype_id) {
                $lastRank = OrdinanceMain::max('ordinance_main_rank');
                $lastRank = ceil(floatval($lastRank));
                $lastRank = max(0, $lastRank);
                $lastRank = (int) $lastRank;

                if($lastRank){
                  $k=   $lastRank;
                }

                $ordinanceMain = new OrdinanceMain();
                $ordinanceMain->new_ordinance_id = $newOrdinance->new_ordinance_id;
                $ordinanceMain->ordinance_main_rank = $k + 1;
                $ordinanceMain->act_id = $newOrdinance->act_id ?? null;
                $ordinanceMain->ordinance_maintype_id = $ordinance_maintype_id;
                $ordinanceMain->ordinance_main_title = $request->ordinance_main_title[$key] ?? null;
                $ordinanceMain->save();

                if (isset($request->ordinance_subtypes_id[$key])) {
                    $ordinance_subtypes_id = $request->ordinance_subtypes_id[$key] ?? null;
                  

                    $i = 0;
                    foreach ($request->section_title[$key] as $index => $sectiontitle) {
                        $currentSectionNo = $request->section_no[$key][$index];
                        $lastRgltnRank= OrdinanceTable::max('ordinances_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                            $i = $lastRgltnRank;
                        }
                        
                        $section = OrdinanceTable::create([
                            'new_ordinance_id' => $newOrdinance->new_ordinance_id,
                            'ordinances_rank' => $i + 1,
                            'ordinances_no' => $currentSectionNo,
                            'ordinance_main_id' => $ordinanceMain->ordinance_main_id,
                            'ordinance_subtypes_id' => $ordinance_subtypes_id,
                            'ordinances_title' => $sectiontitle,
                        ]);   
                    }
                }
                
            }

            return redirect()->route('edit_new_ordinance', ['id' => $newOrdinance->new_ordinance_id])->with('success', 'Index added successfully');

        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }

    public function add_below_new_ordinance_maintype(Request $request, $newordid, $id){
        $currentPage = $request->page;
          $mainOrdinance = OrdinanceMain::where('ordinance_main_id',$id)->with('newOrdinance')->first();
        
        
          $mtype = MainTypeOrdinance::all();
        $stype = SubTypeOrdinance::all();
        //   dd($stype);
        //   die();
      
          return view('admin.MainOrdinance.add_new_ordinance_maintype', compact('mainOrdinance','mtype','stype','currentPage'));
    }

   public function store_ordinance_maintype(Request $request){
    try {

       
        $id =  $request->new_ordinance_id;
        $k =  $request->click_main_rank;
        $ordinance_main_id =  $request->ordinance_main_id;
        $newOrdinance = NewOrdinance::find($id);
        $newOrdinance->update([
            'category_id' => $request->category_id,
            'state_id' => $request->state_id ?? null,
            'new_ordiance_title' => $request->new_ordiance_title,
        ]);
    
        foreach ($request->ordinance_maintype_id as $key => $ordinance_maintype_id) {
            $nextRank = OrdinanceMain::where('new_ordinance_id', $id)
            ->where('ordinance_main_rank', '>', $k)
            ->min('ordinance_main_rank');

            if ($nextRank) {
                $rank = ($k + $nextRank) / 2;
            } else {
                // If there's no next rank, add a small value to $i
                $rank = $k + 0.001;
            }


            
                $ordinanceMain = new OrdinanceMain();
                $ordinanceMain->new_ordinance_id = $newOrdinance->new_ordinance_id;
                $ordinanceMain->ordinance_main_rank = $rank;
                $ordinanceMain->act_id = $newOrdinance->act_id ?? null;
                $ordinanceMain->ordinance_maintype_id = $ordinance_maintype_id;
                $ordinanceMain->ordinance_main_title = $request->ordinance_main_title[$key] ?? null;
                $ordinanceMain->save();

                if (isset($request->ordinance_subtypes_id[$key])) {
                    $ordinance_subtypes_id = $request->ordinance_subtypes_id[$key] ?? null;
                 

                    $i = 0;
                    foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            
                            $currentSectionNo = $request->section_no[$key][$index];
                            $lastRgltnRank= OrdinanceTable::max('ordinances_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = OrdinanceTable::create([
                                'new_ordinance_id' => $newOrdinance->new_ordinance_id,
                                'ordinances_rank' => $i + 1,
                                'ordinances_no' => $currentSectionNo,
                                'ordinance_main_id' => $ordinanceMain->ordinance_main_id,
                                'ordinance_subtypes_id' => $ordinance_subtypes_id,
                                'ordinances_title' => $sectiontitle,
                            ]);
                    }
                }
            
        }

        return redirect()->route('edit_new_ordinance', ['id' => $newOrdinance->new_ordinance_id])->with('success', 'Index added successfully');

    } catch (\Exception $e) {
        \Log::error('Error creating Act: ' . $e->getMessage());

        return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
    }
   }

   public function delete_ordinance_maintype($id){
    try {
        $mainOrdinance = OrdinanceMain::findOrFail($id);
        $mainOrdinance->delete();
        Session::flash('success', 'deleted successfully.');
    } catch (\Exception $e) {
        Session::flash('error', 'Failed to delete RuleMain.');
    }
    return redirect()->back()->with('flash_timeout', 10);
   }

   public function edit_ordinanceTable(Request $request, $id){
        $currentPage = $request->page;
        $ordinanceTable = OrdinanceTable::with(['mainOrdinance', 'mainOrdinance.newOrdinance', 'ordinanceFootnoteModel' => function ($query) {
            $query->whereNull('ordinance_sub_id');
        }])->where('ordinances_id', $id)->firstOrFail(); 

        $ordinanceSubs = OrdinanceSub::where('ordinances_id',$id)->with('ordinanceSubFootnoteModel')->get();

        return view('admin.MainOrdinance.edit', compact('ordinanceTable','ordinanceSubs','currentPage'));
   }

   public function update_main_ordinance(Request $request,$id){
    try {

       
        $currentPage = $request->currentPage;
        $new_ordinance_id = $request->new_ordinance_id;
        $ordinance_main_id = $request->ordinance_main_id;
        $ordinance_subtypes_id = $request->ordinance_subtypes_id;
        if ($request->has('ordinance_main_id')) {
            $ordinanceM = OrdinanceMain::find($request->ordinance_main_id);
           
            if ($ordinanceM) {
                $ordinanceM->ordinance_main_title = $request->ordinance_main_title;
                $ordinanceM->update();
            }
        }
    
        $OrdinanceT = OrdinanceTable::find($id);
        
        
        if ($OrdinanceT) {
            $OrdinanceT->ordinances_content = $request->ordinances_content ?? null;
            $OrdinanceT->ordinances_title = $request->ordinances_title ?? null;
            $OrdinanceT->ordinances_no = $request->ordinances_no ?? null;
            $OrdinanceT->update();

            if ($request->has('sec_footnote_content')) {
                $item = $request->sec_footnote_content;
                if ($request->has('sec_footnote_id')) {
        
                    $footnote_id = $request->sec_footnote_id;
        
                    if (isset($footnote_id)) {
                       
                        $foot = MainOrdinanceFootnote::find($footnote_id);

                        if ($foot) {
                            $foot->footnote_content = $item ?? null;
                            $foot->update();
                        }
                    }
                }else {
                    
                        $footnote = new MainOrdinanceFootnote();
                        $footnote->ordinances_id = $id ?? null;
                        $footnote->new_ordinance_id = $new_ordinance_id ?? null;
                        $footnote->footnote_content = $item ?? null;
                        $footnote->save();
                    }
            }  
        }


        if ($request->has('ordinance_sub_no')) {
            foreach ($request->ordinance_sub_no as $key => $item) {
                $ordinance_sub_id = $request->ordinance_sub_id[$key] ?? null;
                $ordinance_sub_content = $request->ordinance_sub_content[$key] ?? null;
                 
                // Check if sub_section_id is present and valid
                if ($ordinance_sub_id && $existingSubOrdinance = OrdinanceSub::find($ordinance_sub_id)) {
                    $existingSubOrdinance->update([
                        'ordinance_sub_no' => $item,
                        'ordinance_sub_content' => $ordinance_sub_content,
                    ]);

                    if ($request->has('sub_footnote_content') && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                        foreach ($request->sub_footnote_content[$key] as $kys => $footnote_content) {
                            
                            $footnote_id = $request->sub_footnote_id[$key][$kys] ?? null;
                            if ($footnote_id && $foot = MainOrdinanceFootnote::find($footnote_id)) {
                                $foot->update(['footnote_content' => $footnote_content]);
                            }
                            else {
                             // Create new footnote if ID is not provided or invalid
                                $footnote = new MainOrdinanceFootnote();
                                $footnote->ordinance_sub_id = $ordinance_sub_id;
                                $footnote->ordinances_id = $id ?? null;
                                $footnote->new_ordinance_id = $new_ordinance_id ?? null;
                                $footnote->footnote_content = $footnote_content ?? null;
                                $footnote->save();
                            }
                        }
                    }
                } else {

                    $i = 0;
                    $lastSubRgltnRank= OrdinanceSub::max('ordinance_sub_rank');
                    $lastSubRuleRank = ceil(floatval($lastSubRgltnRank));
                    $lastSubRgltnRank = max(0, $lastSubRgltnRank);
                    $lastSubRgltnRank = (int) $lastSubRgltnRank;

                    if($lastSubRgltnRank){
                       $i = $lastSubRgltnRank;
                    }   

                        $subsec = new OrdinanceSub();
                        $subsec->ordinances_id = $id ?? null;
                        $subsec->ordinance_main_id = $ordinance_main_id ?? null;
                        $subsec->ordinance_sub_rank = $i + 1;
                        $subsec->ordinance_subtypes_id = $ordinance_subtypes_id;
                        $subsec->ordinance_sub_no = $item ?? null;
                        $subsec->new_ordinance_id = $new_ordinance_id ?? null;
                        $subsec->ordinance_sub_content = $ordinance_sub_content ?? null;
                        $subsec->save();
        
                    // Handle footnotes for the new sub_section
                    if ($request->has('sub_footnote_content') && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                        foreach ($request->sub_footnote_content[$key] as $kys => $footnote_content) {
                      
                            $footnote = new MainOrdinanceFootnote();
                            $footnote->ordinance_sub_id = $subsec->ordinance_sub_id;
                            $footnote->ordinances_id = $id ?? null;
                            $footnote->new_ordinance_id = $new_ordinance_id ?? null;
                            $footnote->footnote_content = $footnote_content ?? null;
                            $footnote->save();
                        }
                    }
                }
            }
        }
        

        return redirect()->route('edit_new_ordinance', ['id' => $new_ordinance_id,'page' => $currentPage])->with('success', 'updated successfully');
    } catch (\Exception $e) {
        \Log::error('Error updating Act: ' . $e->getMessage());
        return redirect()->route('edit_new_ordinance', ['id' => $new_ordinance_id])->withErrors(['error' => 'Failed to update Section. Please try again.' . $e->getMessage()]);
    }

   }

   public function view_ordinance_sub(Request $request, $id){
        $ordinanceSub = OrdinanceSub::where('ordinances_id', $id)->get();

        if ($ordinanceSub->isEmpty()) {
            // If $regulationSub is empty, redirect back with a flash message
            return redirect()->back()->with('error', 'No data found.');
        }

        return view('admin.MainOrdinance.view_ordinance_sub', compact('ordinanceSub'));
   }

   public function delete_ordinance_sub(Request $request, $id){
    try {
        $ordinanceSub = OrdinanceSub::findOrFail($id);
        $ordinanceSub->delete();
        Session::flash('success', 'deleted successfully.');
    } catch (\Exception $e) {
        Session::flash('error', 'Failed to delete RuleMain.');
    }
    return redirect()->back()->with('flash_timeout', 10);
   }

   public function delete_ordinancestbl($id){
    try {
        $ordinanceTable = OrdinanceTable::findOrFail($id);
        $ordinanceTable->delete();
        Session::flash('success', 'deleted successfully.');
    } catch (\Exception $e) {
        Session::flash('error', 'Failed to delete RuleMain.');
    }
    return redirect()->back()->with('flash_timeout', 10);
   }

   public function add_below_new_ordinancetbl(Request $request, $odrMId, $id){
    $currentPage = $request->page;
    $ordinanceTable = OrdinanceTable::with(['mainOrdinance', 'mainOrdinance.newOrdinance', 'ordinanceFootnoteModel' => function ($query) {
        $query->whereNull('ordinance_sub_id');
    }])->where('ordinances_id', $id)->firstOrFail(); 

    // dd($schemeGuidelinesTable);
    // die();

    return view('admin.MainOrdinance.add_new_ordinancetbl', compact('ordinanceTable','currentPage'));
   }


   public function add_new_ordinancetbl(Request $request){
    try {

      
        $id = $request->click_ordinance_id;
        $currentPage = $request->currentPage;
        $i = $request->click_ordinance_rank;
        $new_ordinance_id = $request->new_ordinance_id;
        $ordinance_main_id = $request->ordinance_main_id;
        $ordinance_subtypes_id = $request->ordinance_subtypes_id;

        $nextRank = OrdinanceTable::where('ordinance_main_id', $ordinance_main_id)
        ->where('ordinances_rank', '>', $i)
        ->min('ordinances_rank');

        if ($nextRank) {
            $rank = ($i + $nextRank) / 2;
        } else {
            // If there's no next rank, add a small value to $i
            $rank = $i + 0.001;
        }

        $ordinanceT = new OrdinanceTable;
        $ordinanceT->new_ordinance_id = $new_ordinance_id;
        $ordinanceT->ordinance_main_id = $ordinance_main_id;
        $ordinanceT->ordinance_subtypes_id = $ordinance_subtypes_id;
        $ordinanceT->ordinances_content = $request->ordinances_content ?? null;
        $ordinanceT->ordinances_title = $request->ordinances_title ?? null;
        $ordinanceT->ordinances_no = $request->ordinances_no ?? null;
        $ordinanceT->ordinances_rank = $rank;
        $ordinanceT->save();

        if ($request->has('sec_footnote_content')) {
            $item = $request->sec_footnote_content;
            $footnote = new MainOrdinanceFootnote();
            $footnote->ordinances_id = $ordinanceT->ordinances_id ?? null;
            $footnote->new_ordinance_id = $new_ordinance_id ?? null;
            $footnote->footnote_content = $item ?? null;
            $footnote->save();       
        }


        if ($request->has('ordinance_sub_no')) {
            foreach ($request->ordinance_sub_no as $key => $item) {
                $ordinance_sub_content = $request->ordinance_sub_content[$key] ?? null;
                
                    $i = 0;
                    $lastSubRgltnRank= OrdinanceSub::max('ordinance_sub_rank');
                    $lastSubRgltnRank = ceil(floatval($lastSubRgltnRank));
                    $lastSubRgltnRank = max(0, $lastSubRgltnRank);
                    $lastSubRgltnRank = (int) $lastSubRgltnRank;

                    if($lastSubRgltnRank){
                       $i = $lastSubRgltnRank;
                    }   

                    $subsec = new OrdinanceSub();
                    $subsec->ordinances_id = $ordinanceT->ordinances_id ?? null;
                    $subsec->ordinance_main_id = $ordinance_main_id ?? null;
                    $subsec->ordinance_sub_rank = $i + 1;
                    $subsec->ordinance_subtypes_id = $ordinance_subtypes_id;
                    $subsec->ordinance_sub_no = $item ?? null;
                    $subsec->new_ordinance_id = $new_ordinance_id ?? null;
                    $subsec->ordinance_sub_content = $ordinance_sub_content ?? null;
                    $subsec->save();
        
                    // Handle footnotes for the new sub_section
                    if ($request->has('sub_footnote_content') && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                        foreach ($request->sub_footnote_content[$key] as $kys => $footnote_content) {
                      
                            $footnote = new MainOrdinanceFootnote();
                            $footnote->ordinance_sub_id = $subsec->ordinance_sub_id;
                            $footnote->ordinances_id = $ordinanceT->ordinances_id;
                            $footnote->new_ordinance_id = $new_ordinance_id ?? null;
                            $footnote->footnote_content = $footnote_content ?? null;
                            $footnote->save();
                        }
                    }
                
            }
        }
        

        return redirect()->route('edit_new_ordinance', ['id' => $new_ordinance_id,'page' => $currentPage])->with('success', 'updated successfully');
    } catch (\Exception $e) {
        \Log::error('Error updating Act: ' . $e->getMessage());
        return redirect()->route('edit_new_ordinance', ['id' => $new_ordinance_id])->withErrors(['error' => 'Failed to update. Please try again.' . $e->getMessage()]);
    }
   }


   public function delete_new_ordinance($id){
    try {

        $newOrdinance = NewOrdinance::findOrFail($id);
        $newOrdinance->delete();
        Session::flash('success', 'deleted successfully.');
    } catch (\Exception $e) {
        Session::flash('error', 'Failed to delete RuleMain.');
    }
    return redirect()->back()->with('flash_timeout', 10);
   }
   public function delete_ordinance_footnote(Request $request,$id){
    try {

        $ordinanceFootnote = MainOrdinanceFootnote::findOrFail($id);
        $ordinanceFootnote->delete();
        Session::flash('success', 'deleted successfully.');
    } catch (\Exception $e) {
        Session::flash('error', 'Failed to delete RuleMain.');
    }
    return redirect()->back()->with('flash_timeout', 10);
   }

   public function view_new_ordinance(Request $request, $id){
    $currentPage = $request->query('page');
    $newOrdinance = NewOrdinance::findOrFail($id);
    return view('admin.MainOrdinance.view_new_ordinance', compact('newOrdinance','currentPage'));
    
   }

   public function export_ordinance_pdf(Request $request, $id){
        try {
            ini_set('memory_limit', '1024M');
            
            // Create Dompdf instance with options
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isJavascriptEnabled', true);
            $dompdf = new Dompdf($options);

            // Fetch data

            $newOrdinance = NewOrdinance::where('new_ordinance_id', $id)
            ->with([
                'ordinanceMain' => function ($query) {
                    $query->with(['ordinancetbl' => function ($query) {
                        $query->orderBy('ordinances_rank');
                    }])->orderBy('ordinance_main_rank'); // Sort ruleMain by rule_main_rank
                },
                'ordinanceMain.ordinancetbl.ordinanceSub', 'ordinanceMain.ordinancetbl.ordinanceFootnoteModel', 'ordinanceMain.ordinancetbl.ordinanceSub.ordinanceSubFootnoteModel'
            ])
            ->get();
            // dd($newOrdinance);
            // die();

            $pdf = FacadePdf::loadView('admin.MainOrdinance.pdf', ['combinedItems' => $newOrdinance]);
            

            // Download PDF with a meaningful file name
            return $pdf->download("{$newOrdinance[0]->new_ordinance_title}.pdf");
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
