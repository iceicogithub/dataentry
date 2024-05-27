<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\ActAmendment;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;

class ActAmendmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $act_id = $id;
        $act = Act::where('act_id', $act_id)->first();
       $actAmendment = ActAmendment::where('act_id', $act_id)->get();
      
       $perPage = request()->get('perPage') ?: 10;
       $page = request()->get('page') ?: 1;
       $slicedItems = array_slice($actAmendment->toArray(), ($page - 1) * $perPage, $perPage);

        $paginatedCollection = new LengthAwarePaginator(
            $slicedItems,
            count($actAmendment),
            $perPage,
            $page
        );

        $paginatedCollection->appends(['perPage' => $perPage]);

        $paginatedCollection->withPath(request()->url());
        return view('admin.actAmendment.index', compact('act','act_id','actAmendment','paginatedCollection'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        return view('admin.actAmendment.create', compact('id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $actAmendment = new ActAmendment();
            $actAmendment->act_id = $request->act_id ?? null;
            $actAmendment->act_amendment_title = $request->act_amendment_title;
            $actAmendment->save();
    
            return redirect()->route('get_amendment_act', ['id' => $actAmendment->act_id])->with('success', 'Act Amendment created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Act Amendment: ' . $e->getMessage());
            // In case of exception, $actAmendment may not be defined, so it's better to redirect without using it.
            return redirect()->back()->withErrors(['error' => 'Failed to create Act Amendment. Please try again.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $actAmendment = ActAmendment::findOrFail($id);
        return view('admin.actAmendment.show', compact('actAmendment'));   
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'act_amendment_pdf' => 'required|mimes:pdf|max:2048', // Adjust max file size as needed
        ]);
    
        // Store file
        $file = $request->file('act_amendment_pdf');
        $filename = time(). '_' . $file->getClientOriginalName();
        $file->move(public_path('admin/uploads'), $filename);
    
        // Update ActAmendment record
        $actAmendment = ActAmendment::findOrFail($id);
        $actAmendment->act_amendment_pdf = $filename;
        $actAmendment->save();
    
        return redirect()->back()->with('success', 'Act Amendment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $actAmendment = ActAmendment::findOrFail($id);
            $actAmendment->delete();
            Session::flash('success', 'deleted successfully.');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to delete RuleMain.');
        }
        return redirect()->back()->with('flash_timeout', 10);
        
    }
}
