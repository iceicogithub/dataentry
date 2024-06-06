<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\Circular;
use Illuminate\Support\Facades\Session;
class CircularController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,$id)
    {
        $act_id = $id;
        $act = Act::where('act_id', $act_id)->first();
        $currentPage = $request->query('page', 1);
        $circulars = Circular::where('act_id', $act_id)->orderBy('circulars_id', 'desc')->paginate(10);
    
        return view('admin.Circular.index', compact('act','act_id','circulars','currentPage'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request,$id)
    {
        $currentPage = request()->query('page', 1);
        return view('admin.Circular.create', compact('id','currentPage'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
           
            $circulars = new Circular();
            $circulars->act_id = $request->act_id ?? null;
            $circulars->circulars_title = $request->circulars_title;
            $circulars->circulars_no = $request->circulars_no;
            $circulars->circulars_date = $request->circulars_date;
            $circulars->ministry = $request->ministry;
            $circulars->save();
    
            return redirect()->route('get_circulars', ['id' => $circulars->act_id])->with('success', 'Circular created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Circular: ' . $e->getMessage());
           
            return redirect()->back()->withErrors(['error' => 'Failed to create Circular. Please try again.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,string $id)
    {
        $currentPage = request()->query('page', 1);
        $circular = Circular::findOrFail($id);
        return view('admin.Circular.show', compact('circular','currentPage')); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $currentPage = $request->query('page', 1);
        $circular = Circular::findOrFail($id);
        return view('admin.Circular.edit',compact('circular','currentPage'));
    }


    public function update_circulars(Request $request,$id){
        try {

            $circulars = Circular::findOrFail($id);
            $circulars->act_id = $request->act_id ?? null;
            $circulars->circulars_title = $request->circulars_title;
            $circulars->circulars_no = $request->circulars_no;
            $circulars->circulars_date = $request->circulars_date;
            $circulars->ministry = $request->ministry;
            $circulars->update();
            return redirect()->route('get_circulars', ['id' => $circulars->act_id])->with('success', 'Circulars updated successfully');
       } catch (\Exception $e) {
            \Log::error('Error creating circulars: ' . $e->getMessage());
        
            return redirect()->back()->withErrors(['error' => 'Failed to create circulars. Please try again.']);
        }
        
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
      
        $request->validate([
            'circulars_pdf' => 'required',
        ]);
    
        $file = $request->file('circulars_pdf');
        $filename = time(). '_' . $file->getClientOriginalName();
        $file->move(public_path('admin/circular'), $filename);
    
        // Update ActAmendment record
        $circulars = Circular::findOrFail($id);
        $circulars->circulars_pdf = $filename;
        $circulars->save();
        
        return redirect()->back()->with('success', 'Circular updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $circular = Circular::findOrFail($id);
            $circular->delete();
            Session::flash('success', 'deleted successfully.');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to delete.');
        }
        return redirect()->back()->with('flash_timeout', 10);
    }
}
