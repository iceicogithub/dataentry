<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\Manual;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;

class ManualsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $act_id = $id;
        $act = Act::where('act_id', $act_id)->first();
       $manual = Manual::where('act_id', $act_id)->get();
      
       $perPage = request()->get('perPage') ?: 10;
       $page = request()->get('page') ?: 1;
       $slicedItems = array_slice($manual->toArray(), ($page - 1) * $perPage, $perPage);

        $paginatedCollection = new LengthAwarePaginator(
            $slicedItems,
            count($manual),
            $perPage,
            $page
        );

        $paginatedCollection->appends(['perPage' => $perPage]);

        $paginatedCollection->withPath(request()->url());
        return view('admin.manual.index', compact('act','act_id','manual','paginatedCollection'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        return view('admin.manual.create', compact('id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
           
            $manuals = new Manual();
            $manuals->act_id = $request->act_id ?? null;
            $manuals->manuals_title = $request->manuals_title;
            $manuals->manuals_no = $request->manuals_no;
            $manuals->manuals_date = $request->manuals_date;
            $manuals->ministry = $request->ministry;
            $manuals->save();
    
            return redirect()->route('get_manuals', ['id' => $manuals->act_id])->with('success', 'Manuals created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Manuals: ' . $e->getMessage());
           
            return redirect()->back()->withErrors(['error' => 'Failed to create Manuals. Please try again.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $currentPage = request()->query('page', 1);
        $manual = Manual::findOrFail($id);
        return view('admin.manual.show', compact('manual','currentPage')); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $manual = Manual::findOrFail($id);
        return view('admin.manual.edit',compact('manual'));
    }


    public function update_manuals(Request $request,$id){
        try {
            $manuals = Manual::findOrFail($id);
            $manuals->manuals_title = $request->manuals_title;
            $manuals->manuals_no = $request->manuals_no;
            $manuals->manuals_date = $request->manuals_date;
            $manuals->ministry = $request->ministry;
            $manuals->update();
            return redirect()->route('get_manuals', ['id' => $manuals->act_id])->with('success', 'Manuals created successfully');
       } catch (\Exception $e) {
            \Log::error('Error creating Manuals: ' . $e->getMessage());
        
            return redirect()->back()->withErrors(['error' => 'Failed to create Manuals. Please try again.']);
        }
        
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       
        $request->validate([
            'manuals_pdf' => 'required|mimes:pdf|max:2048',
        ]);
    
        $file = $request->file('manuals_pdf');
        $filename = time(). '_' . $file->getClientOriginalName();
        $file->move(public_path('admin/manuals'), $filename);
    
        // Update ActAmendment record
        $manual = Manual::findOrFail($id);
        $manual->manuals_pdf = $filename;
        $manual->save();
        
        return redirect()->back()->with('success', 'Manual updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
