<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\Category;
use App\Models\State;
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

class MainSchemeGuidelinesController extends Controller
{
    public function index($id)
    {
        $act_id = $id;
        $act = Act::where('act_id', $act_id)->first();
        $new_scheme_guidelines = NewSchemeGuidelines::where('act_id', $act_id)->get();
       
      
       $perPage = request()->get('perPage') ?: 10;
        $page = request()->get('page') ?: 1;
        $slicedItems = array_slice($new_scheme_guidelines->toArray(), ($page - 1) * $perPage, $perPage);

        $paginatedCollection = new LengthAwarePaginator(
            $slicedItems,
            count($new_scheme_guidelines),
            $perPage,
            $page
        );

        $paginatedCollection->appends(['perPage' => $perPage]);

        $paginatedCollection->withPath(request()->url());
        return view('admin.SchemeGuidelines.index', compact('act','act_id','paginatedCollection'));
 
    }

    public function new_scheme_guidelines($id){
        $category = Category::all();
        $states = State::all();
       return view('admin.SchemeGuidelines.new_scheme_guidelines', compact('id','category','states',));

    }

    public function store_new_scheme_guidelines(Request $request){
        try{
            $newSchemeGuidelines = new NewSchemeGuidelines();
            $newSchemeGuidelines->act_id = $request->act_id ?? null;
            $newSchemeGuidelines->new_scheme_guidelines_title = $request->new_scheme_guidelines_title;
            $newSchemeGuidelines->save();

            return redirect()->route('get_schemes_guidelines', ['id' => $newSchemeGuidelines->act_id])->with('success', 'Scheme/Guidelines created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());
            return redirect()->route('get_schemes_guidelines', ['id' => $newSchemeGuidelines->act_id])->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }

    public function edit_new_scheme_guidelines($id){
        $new_scheme_guidelines_id = $id;
        $newSchemeGuidelines = NewSchemeGuidelines::where('new_scheme_guidelines_id', $new_scheme_guidelines_id)->with('act')->first();
       
        $mainsequence = SchemeGuidelinesMain::where('new_scheme_guidelines_id', $id)
        ->with('mainTypeSchemeGuidelines') 
        ->get()
        ->map(function ($schemeGuidelinesMain) {
            // Sort the ruletbl collection by rules_rank in ascending order
            $schemeGuidelinesMain->load(['schemeGuidelinestbl' => function ($query) {
                $query->orderBy('scheme_guidelines_rank');
            }]);
            return $schemeGuidelinesMain;
        })
        ->sortBy('scheme_guidelines_main_rank');
       
     
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
        
        return view('admin.SchemeGuidelines.show', compact('newSchemeGuidelines','paginatedCollection'));     
   
    }

    public function update_new_scheme_guidelines(Request $request, $id){
        try {
           
            $newSchemeGuidelines = NewSchemeGuidelines::find($id);
            $newSchemeGuidelines->new_scheme_guidelines_title = $request->new_scheme_guidelines_title;
            $newSchemeGuidelines->ministry = $request->ministry;
            $newSchemeGuidelines->new_scheme_guidelines_no = $request->new_scheme_guidelines_no ?? null;
            $newSchemeGuidelines->new_scheme_guidelines_date = $request->new_scheme_guidelines_date ?? null;
            $newSchemeGuidelines->enactment_date = $request->enactment_date ?? null;
            $newSchemeGuidelines->enforcement_date = $request->enforcement_date ?? null;
            $newSchemeGuidelines->new_scheme_guidelines_description = $request->new_scheme_guidelines_description ?? null;
            $newSchemeGuidelines->new_scheme_guidelines_footnote_description = $request->new_scheme_guidelines_footnote_description;
            $newSchemeGuidelines->update();


            return redirect()->back()->with('success', 'Scheme/Guidelines Updated Successfully');
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
        $newSchemeGuidelines = NewSchemeGuidelines::find($id);
        $mtype = MainTypeSchemeGuidelines::all();
        $stype = SubTypeSchemeGuidelines::all();
       

        
        return view('admin.SchemeGuidelines.create', compact('newSchemeGuidelines','mtype','stype'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        try {
           
            $newSchemeGuidelines = NewSchemeGuidelines::find($id);
            $newSchemeGuidelines->update([
                'category_id' => $request->category_id,
                'state_id' => $request->state_id ?? null,
                'new_scheme_guidelines_title' => $request->new_scheme_guidelines_title,
            ]);
        
           $k = 0;
            foreach ($request->scheme_guidelines_maintype_id as $key => $scheme_guidelines_maintype_id) {
                $lastRank = SchemeGuidelinesMain::max('scheme_guidelines_main_rank');
                $lastRank = ceil(floatval($lastRank));
                $lastRank = max(0, $lastRank);
                $lastRank = (int) $lastRank;

                if($lastRank){
                  $k=   $lastRank;
                }

                
                $schemeGuidelinesMain = new SchemeGuidelinesMain();
                $schemeGuidelinesMain->new_scheme_guidelines_id = $newSchemeGuidelines->new_scheme_guidelines_id;
                $schemeGuidelinesMain->scheme_guidelines_main_rank = $k + 1;
                $schemeGuidelinesMain->act_id = $newSchemeGuidelines->act_id ?? null;
                $schemeGuidelinesMain->scheme_guidelines_maintype_id = $scheme_guidelines_maintype_id;
                $schemeGuidelinesMain->scheme_guidelines_main_title = $request->scheme_guidelines_main_title[$key] ?? null;
                $schemeGuidelinesMain->save();

                if (isset($request->scheme_guidelines_subtypes_id[$key])) {
                    $scheme_guidelines_subtypes_id = $request->scheme_guidelines_subtypes_id[$key] ?? null;

                    $i = 0;
                    foreach ($request->section_title[$key] as $index => $sectiontitle) {
                        $currentSectionNo = $request->section_no[$key][$index];
                        $lastRgltnRank= SchemeGuidelinesTable::max('scheme_guidelines_rank');
                        $lastRgltnRank = ceil(floatval($lastRgltnRank));
                        $lastRgltnRank = max(0, $lastRgltnRank);
                        $lastRgltnRank = (int) $lastRgltnRank;

                        if($lastRgltnRank){
                            $i = $lastRgltnRank;
                        }       
                        $section = SchemeGuidelinesTable::create([
                            'new_scheme_guidelines_id' => $newSchemeGuidelines->new_scheme_guidelines_id,
                            'scheme_guidelines_rank' => $i + 1,
                            'scheme_guidelines_no' => $currentSectionNo,
                            'scheme_guidelines_main_id' => $schemeGuidelinesMain->scheme_guidelines_main_id,
                            'scheme_guidelines_subtypes_id' => $scheme_guidelines_subtypes_id,
                            'scheme_guidelines_title' => $sectiontitle,
                        ]);
                        
                    }
                }
                
            }

            return redirect()->route('edit_new_scheme_guidelines', ['id' => $newSchemeGuidelines->new_scheme_guidelines_id])->with('success', 'Index added successfully');

        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }

    public function add_below_new_scheme_guidelines_maintype(Request $request, $newschmid, $id){
         
          $mainSchemeGuidelines = SchemeGuidelinesMain::where('scheme_guidelines_main_id',$id)->with('newschemeGuidelines')->first();
        
          $mtype = MainTypeSchemeGuidelines::all();
          $stype = SubTypeSchemeGuidelines::all();
        //   dd($stype);
        //   die();
      
          return view('admin.SchemeGuidelines.add_new_scheme_guidelines_maintype', compact('mainSchemeGuidelines','mtype','stype'));
    }

   public function store_scheme_guidelines_maintype(Request $request){
    try {

       
        $id =  $request->new_scheme_guidelines_id;
        $k =  $request->click_main_rank;
        $scheme_guidelines_main_id =  $request->scheme_guidelines_main_id;
        $newSchemeGuidelines = NewSchemeGuidelines::find($id);
        $newSchemeGuidelines->update([
            'category_id' => $request->category_id,
            'state_id' => $request->state_id ?? null,
            'new_scheme_guidelines_title' => $request->new_scheme_guidelines_title,
        ]);
    
        foreach ($request->scheme_guidelines_maintype_id as $key => $scheme_guidelines_maintype_id) {
            $nextRank = SchemeGuidelinesMain::where('new_scheme_guidelines_id', $id)
            ->where('scheme_guidelines_main_rank', '>', $k)
            ->min('scheme_guidelines_main_rank');

            if ($nextRank) {
                $rank = ($k + $nextRank) / 2;
            } else {
                // If there's no next rank, add a small value to $i
                $rank = $k + 0.001;
            }


            
                $schemeGuidelinesMain = new SchemeGuidelinesMain();
                $schemeGuidelinesMain->new_scheme_guidelines_id = $newSchemeGuidelines->new_scheme_guidelines_id;
                $schemeGuidelinesMain->scheme_guidelines_main_rank = $rank;
                $schemeGuidelinesMain->act_id = $newSchemeGuidelines->act_id ?? null;
                $schemeGuidelinesMain->scheme_guidelines_maintype_id = $scheme_guidelines_maintype_id;
                $schemeGuidelinesMain->scheme_guidelines_main_title = $request->scheme_guidelines_main_title[$key] ?? null;
                $schemeGuidelinesMain->save();

                if (isset($request->scheme_guidelines_subtypes_id[$key])) {
                    $scheme_guidelines_subtypes_id = $request->scheme_guidelines_subtypes_id[$key] ?? null;
                 

                    $i = 0;
                    foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            
                            $currentSectionNo = $request->section_no[$key][$index];
                            $lastRgltnRank= SchemeGuidelinesTable::max('scheme_guidelines_rank');
                            $lastRgltnRank = ceil(floatval($lastRgltnRank));
                            $lastRgltnRank = max(0, $lastRgltnRank);
                            $lastRgltnRank = (int) $lastRgltnRank;

                            if($lastRgltnRank){
                               $i = $lastRgltnRank;
                            }       
                            $section = SchemeGuidelinesTable::create([
                                'new_scheme_guidelines_id' => $newSchemeGuidelines->new_scheme_guidelines_id,
                                'scheme_guidelines_rank' => $i + 1,
                                'scheme_guidelines_no' => $currentSectionNo,
                                'scheme_guidelines_main_id' => $schemeGuidelinesMain->scheme_guidelines_main_id,
                                'scheme_guidelines_subtypes_id' => $scheme_guidelines_subtypes_id,
                                'scheme_guidelines_title' => $sectiontitle,
                            ]);
                    }
                }
            
        }

        return redirect()->route('edit_new_scheme_guidelines', ['id' => $newSchemeGuidelines->new_scheme_guidelines_id])->with('success', 'Index added successfully');

    } catch (\Exception $e) {
        \Log::error('Error creating Act: ' . $e->getMessage());

        return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
    }
   }

   public function delete_scheme_guidelines_maintype($id){
    try {
        $mainSchemeGuidelines = SchemeGuidelinesMain::findOrFail($id);
        $mainSchemeGuidelines->delete();
        Session::flash('success', 'Main SchemeGuidelines deleted successfully.');
    } catch (\Exception $e) {
        Session::flash('error', 'Failed to delete RuleMain.');
    }
    return redirect()->back()->with('flash_timeout', 10);
   }

   public function edit_schemeGuidelinesTable(Request $request, $id){
        $currentPage = $request->page;
        $schemeGuidelinesTable = SchemeGuidelinesTable::with(['mainschemeGuidelines', 'mainschemeGuidelines.newschemeGuidelines', 'schemeGuidelinesFootnoteModel' => function ($query) {
            $query->whereNull('scheme_guidelines_sub_id');
        }])->where('scheme_guidelines_id', $id)->firstOrFail(); 

        $schemeGuidelinesSubs = SchemeGuidelinesSub::where('scheme_guidelines_id',$id)->with('schemeGuidelinesSubFootnoteModel')->get();
        // dd($ruleSub);
        // die();
        return view('admin.SchemeGuidelines.edit', compact('schemeGuidelinesTable','schemeGuidelinesSubs','currentPage'));
   }

   public function update_main_scheme_guidelines(Request $request,$id){
    try {

       
        $currentPage = $request->currentPage;
        $new_scheme_guidelines_id = $request->new_scheme_guidelines_id;
        $scheme_guidelines_main_id = $request->scheme_guidelines_main_id;
        $scheme_guidelines_subtypes_id = $request->scheme_guidelines_subtypes_id;
        if ($request->has('scheme_guidelines_main_id')) {
            $schemeGuidelinesM = SchemeGuidelinesMain::find($request->scheme_guidelines_main_id);
           
            if ($schemeGuidelinesM) {
                $schemeGuidelinesM->scheme_guidelines_main_title = $request->scheme_guidelines_main_title;
                $schemeGuidelinesM->update();
            }
        }
    
        $SchemeGuidelinesT = SchemeGuidelinesTable::find($id);
        
        
        if ($SchemeGuidelinesT) {
            $SchemeGuidelinesT->scheme_guidelines_content = $request->scheme_guidelines_content ?? null;
            $SchemeGuidelinesT->scheme_guidelines_title = $request->scheme_guidelines_title ?? null;
            $SchemeGuidelinesT->scheme_guidelines_no = $request->scheme_guidelines_no ?? null;
            $SchemeGuidelinesT->update();

            if ($request->has('sec_footnote_content')) {
                $item = $request->sec_footnote_content;
                if ($request->has('sec_footnote_id')) {
        
                    $footnote_id = $request->sec_footnote_id;
        
                    if (isset($footnote_id)) {
                       
                        $foot = MainSchemeGuidelinesFootnote::find($footnote_id);

                        if ($foot) {
                            $foot->footnote_content = $item ?? null;
                            $foot->update();
                        }
                    }
                }else {
                    
                        $footnote = new MainSchemeGuidelinesFootnote();
                        $footnote->scheme_guidelines_id = $id ?? null;
                        $footnote->new_scheme_guidelines_id = $new_scheme_guidelines_id ?? null;
                        $footnote->footnote_content = $item ?? null;
                        $footnote->save();
                    }
            }  
        }


        if ($request->has('scheme_guidelines_sub_no')) {
            foreach ($request->scheme_guidelines_sub_no as $key => $item) {
                $scheme_guidelines_sub_id = $request->scheme_guidelines_sub_id[$key] ?? null;
                $scheme_guidelines_sub_content = $request->scheme_guidelines_sub_content[$key] ?? null;
                 
                // Check if sub_section_id is present and valid
                if ($scheme_guidelines_sub_id && $existingSubSchemeGuidelines = SchemeGuidelinesSub::find($scheme_guidelines_sub_id)) {
                    $existingSubSchemeGuidelines->update([
                        'scheme_guidelines_sub_no' => $item,
                        'scheme_guidelines_sub_content' => $scheme_guidelines_sub_content,
                    ]);

                    if ($request->has('sub_footnote_content') && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                        foreach ($request->sub_footnote_content[$key] as $kys => $footnote_content) {
                            
                            $footnote_id = $request->sub_footnote_id[$key][$kys] ?? null;
                            if ($footnote_id && $foot = MainSchemeGuidelinesFootnote::find($footnote_id)) {
                                $foot->update(['footnote_content' => $footnote_content]);
                            }
                            else {
                             // Create new footnote if ID is not provided or invalid
                                $footnote = new MainSchemeGuidelinesFootnote();
                                $footnote->scheme_guidelines_sub_id = $scheme_guidelines_sub_id;
                                $footnote->scheme_guidelines_id = $id ?? null;
                                $footnote->new_scheme_guidelines_id = $new_scheme_guidelines_id ?? null;
                                $footnote->footnote_content = $footnote_content ?? null;
                                $footnote->save();
                            }
                        }
                    }
                } else {

                    $i = 0;
                    $lastSubRgltnRank= SchemeGuidelinesSub::max('scheme_guidelines_sub_rank');
                    $lastSubRuleRank = ceil(floatval($lastSubRgltnRank));
                    $lastSubRgltnRank = max(0, $lastSubRgltnRank);
                    $lastSubRgltnRank = (int) $lastSubRgltnRank;

                    if($lastSubRgltnRank){
                       $i = $lastSubRgltnRank;
                    }   

                        $subsec = new SchemeGuidelinesSub();
                        $subsec->scheme_guidelines_id = $id ?? null;
                        $subsec->scheme_guidelines_main_id = $scheme_guidelines_main_id ?? null;
                        $subsec->scheme_guidelines_sub_rank = $i + 1;
                        $subsec->scheme_guidelines_subtypes_id = $scheme_guidelines_subtypes_id;
                        $subsec->scheme_guidelines_sub_no = $item ?? null;
                        $subsec->new_scheme_guidelines_id = $new_scheme_guidelines_id ?? null;
                        $subsec->scheme_guidelines_sub_content = $scheme_guidelines_sub_content ?? null;
                        $subsec->save();
        
                    // Handle footnotes for the new sub_section
                    if ($request->has('sub_footnote_content') && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                        foreach ($request->sub_footnote_content[$key] as $kys => $footnote_content) {
                      
                            $footnote = new MainSchemeGuidelinesFootnote();
                            $footnote->scheme_guidelines_sub_id = $subsec->scheme_guidelines_sub_id;
                            $footnote->scheme_guidelines_id = $id ?? null;
                            $footnote->new_scheme_guidelines_id = $new_scheme_guidelines_id ?? null;
                            $footnote->footnote_content = $footnote_content ?? null;
                            $footnote->save();
                        }
                    }
                }
            }
        }
        

        return redirect()->route('edit_new_scheme_guidelines', ['id' => $new_scheme_guidelines_id,'page' => $currentPage])->with('success', 'updated successfully');
    } catch (\Exception $e) {
        \Log::error('Error updating Act: ' . $e->getMessage());
        return redirect()->route('edit_new_scheme_guidelines', ['id' => $new_scheme_guidelines_id])->withErrors(['error' => 'Failed to update Section. Please try again.' . $e->getMessage()]);
    }

   }

   public function view_scheme_guidelines_sub(Request $request, $id){
        $schemeGuidelinesSub = SchemeGuidelinesSub::where('scheme_guidelines_id', $id)->get();

        if ($schemeGuidelinesSub->isEmpty()) {
            // If $regulationSub is empty, redirect back with a flash message
            return redirect()->back()->with('error', 'No data found.');
        }

        return view('admin.SchemeGuidelines.view_scheme_guideline_sub', compact('schemeGuidelinesSub'));
   }

   public function delete_scheme_guidelines_sub(Request $request, $id){
    try {
        $schemeGuidelinesSub = SchemeGuidelinesSub::findOrFail($id);
        $schemeGuidelinesSub->delete();
        Session::flash('success', 'deleted successfully.');
    } catch (\Exception $e) {
        Session::flash('error', 'Failed to delete RuleMain.');
    }
    return redirect()->back()->with('flash_timeout', 10);
   }

   public function delete_schemeGuidelinestbl($id){
    try {
        $schemeGuidelinestbl = SchemeGuidelinesTable::findOrFail($id);
        $schemeGuidelinestbl->delete();
        Session::flash('success', 'deleted successfully.');
    } catch (\Exception $e) {
        Session::flash('error', 'Failed to delete RuleMain.');
    }
    return redirect()->back()->with('flash_timeout', 10);
   }

   public function add_below_new_schemeGuidelinestbl(Request $request, $rgltnId, $id){
    $currentPage = $request->page;
    $schemeGuidelinesTable = SchemeGuidelinesTable::with(['mainschemeGuidelines', 'mainschemeGuidelines.newSchemeGuidelines', 'schemeGuidelinesFootnoteModel' => function ($query) {
        $query->whereNull('scheme_guidelines_sub_id');
    }])->where('scheme_guidelines_id', $id)->firstOrFail(); 

    // dd($schemeGuidelinesTable);
    // die();

    return view('admin.SchemeGuidelines.add_new_schemeGuidelinestbl', compact('schemeGuidelinesTable','currentPage'));
   }


   public function add_new_schemeGuidelinestbl(Request $request){
    try {

      
        $id = $request->click_scheme_guidelines_id;
        $currentPage = $request->currentPage;
        $i = $request->click_scheme_guidelines_rank;
        $new_scheme_guidelines_id = $request->new_scheme_guidelines_id;
        $scheme_guidelines_main_id = $request->scheme_guidelines_main_id;
        $scheme_guidelines_subtypes_id = $request->scheme_guidelines_subtypes_id;

        $nextRank = SchemeGuidelinesTable::where('scheme_guidelines_main_id', $scheme_guidelines_main_id)
        ->where('scheme_guidelines_rank', '>', $i)
        ->min('scheme_guidelines_rank');

        if ($nextRank) {
            $rank = ($i + $nextRank) / 2;
        } else {
            // If there's no next rank, add a small value to $i
            $rank = $i + 0.001;
        }

        $schemeGuidelinesT = new SchemeGuidelinesTable;
        $schemeGuidelinesT->new_scheme_guidelines_id = $new_scheme_guidelines_id;
        $schemeGuidelinesT->scheme_guidelines_main_id = $scheme_guidelines_main_id;
        $schemeGuidelinesT->scheme_guidelines_subtypes_id = $scheme_guidelines_subtypes_id;
        $schemeGuidelinesT->scheme_guidelines_content = $request->scheme_guidelines_content ?? null;
        $schemeGuidelinesT->scheme_guidelines_title = $request->scheme_guidelines_title ?? null;
        $schemeGuidelinesT->scheme_guidelines_no = $request->scheme_guidelines_no ?? null;
        $schemeGuidelinesT->scheme_guidelines_rank = $rank;
        $schemeGuidelinesT->save();

        if ($request->has('sec_footnote_content')) {
            $item = $request->sec_footnote_content;
            $footnote = new MainSchemeGuidelinesFootnote();
            $footnote->scheme_guidelines_id = $schemeGuidelinesT->scheme_guidelines_id ?? null;
            $footnote->new_scheme_guidelines_id = $new_scheme_guidelines_id ?? null;
            $footnote->footnote_content = $item ?? null;
            $footnote->save();       
        }


        if ($request->has('scheme_guidelines_sub_no')) {
            foreach ($request->scheme_guidelines_sub_no as $key => $item) {
                $scheme_guidelines_sub_content = $request->scheme_guidelines_sub_content[$key] ?? null;
                
                    $i = 0;
                    $lastSubRgltnRank= SchemeGuidelinesSub::max('scheme_guidelines_sub_rank');
                    $lastSubRgltnRank = ceil(floatval($lastSubRgltnRank));
                    $lastSubRgltnRank = max(0, $lastSubRgltnRank);
                    $lastSubRgltnRank = (int) $lastSubRgltnRank;

                    if($lastSubRgltnRank){
                       $i = $lastSubRgltnRank;
                    }   

                    $subsec = new SchemeGuidelinesSub();
                    $subsec->scheme_guidelines_id = $schemeGuidelinesT->scheme_guidelines_id ?? null;
                    $subsec->scheme_guidelines_main_id = $scheme_guidelines_main_id ?? null;
                    $subsec->scheme_guidelines_sub_rank = $i + 1;
                    $subsec->scheme_guidelines_subtypes_id = $scheme_guidelines_subtypes_id;
                    $subsec->scheme_guidelines_sub_no = $item ?? null;
                    $subsec->new_scheme_guidelines_id = $new_scheme_guidelines_id ?? null;
                    $subsec->scheme_guidelines_sub_content = $scheme_guidelines_sub_content ?? null;
                    $subsec->save();
        
                    // Handle footnotes for the new sub_section
                    if ($request->has('sub_footnote_content') && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                        foreach ($request->sub_footnote_content[$key] as $kys => $footnote_content) {
                      
                            $footnote = new MainSchemeGuidelinesFootnote();
                            $footnote->scheme_guidelines_sub_id = $subsec->scheme_guidelines_sub_id;
                            $footnote->scheme_guidelines_id = $schemeGuidelinesT->scheme_guidelines_id;
                            $footnote->new_scheme_guidelines_id = $new_scheme_guidelines_id ?? null;
                            $footnote->footnote_content = $footnote_content ?? null;
                            $footnote->save();
                        }
                    }
                
            }
        }
        

        return redirect()->route('edit_new_scheme_guidelines', ['id' => $new_scheme_guidelines_id,'page' => $currentPage])->with('success', 'updated successfully');
    } catch (\Exception $e) {
        \Log::error('Error updating Act: ' . $e->getMessage());
        return redirect()->route('edit_new_scheme_guidelines', ['id' => $new_scheme_guidelines_id])->withErrors(['error' => 'Failed to update. Please try again.' . $e->getMessage()]);
    }
   }


   public function delete_new_scheme_guidelines($id){
    try {

        $newSchemeGuidelines = NewSchemeGuidelines::findOrFail($id);
        $newSchemeGuidelines->delete();
        Session::flash('success', 'deleted successfully.');
    } catch (\Exception $e) {
        Session::flash('error', 'Failed to delete RuleMain.');
    }
    return redirect()->back()->with('flash_timeout', 10);
   }
   public function delete_scheme_guidelines_footnote(Request $request,$id){
    try {

        $schemeGuidelinesFootnote = MainSchemeGuidelinesFootnote::findOrFail($id);
        $schemeGuidelinesFootnote->delete();
        Session::flash('success', 'deleted successfully.');
    } catch (\Exception $e) {
        Session::flash('error', 'Failed to delete RuleMain.');
    }
    return redirect()->back()->with('flash_timeout', 10);
   }

   public function view_new_scheme_guidelines(Request $request, $id){
    $currentPage = $request->query('page');
    $newSchemeGuidelines = NewSchemeGuidelines::findOrFail($id);
    return view('admin.SchemeGuidelines.view_new_scheme_guidelines', compact('newSchemeGuidelines','currentPage'));
    
   }

   public function export_scheme_guidelines_pdf(Request $request, $id){
        try {
            ini_set('memory_limit', '1024M');
            
            // Create Dompdf instance with options
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isJavascriptEnabled', true);
            $dompdf = new Dompdf($options);

            // Fetch data

            $newSchemeGuidelines = NewSchemeGuidelines::where('new_scheme_guidelines_id', $id)
            ->with([
                'schemeGuidelinesMain' => function ($query) {
                    $query->with(['schemeGuidelinestbl' => function ($query) {
                        $query->orderBy('scheme_guidelines_rank');
                    }])->orderBy('scheme_guidelines_main_rank'); // Sort ruleMain by rule_main_rank
                },
                'schemeGuidelinesMain.schemeGuidelinestbl.schemeGuidelinesSub', 'schemeGuidelinesMain.schemeGuidelinestbl.schemeGuidelinesFootnoteModel', 'schemeGuidelinesMain.schemeGuidelinestbl.schemeGuidelinesSub.schemeGuidelinesSubFootnoteModel'
            ])
            ->get();

            $pdf = FacadePdf::loadView('admin.SchemeGuidelines.pdf', ['combinedItems' => $newSchemeGuidelines]);

            // Download PDF with a meaningful file name
            return $pdf->download("{$newSchemeGuidelines[0]->new_scheme_guidelines_title}.pdf");
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
