<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\Release;
use Illuminate\Support\Facades\Session;

class ReleaseController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index(Request $request,$id)
    {
        $act_id = $id;
        $act = Act::where('act_id', $act_id)->first();
        $currentPage = $request->query('page', 1);
        $releases = Release::where('act_id', $act_id)->orderBy('release_id', 'desc')->paginate(10);
    
        return view('admin.PressRelease.index', compact('act','act_id','releases','currentPage'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request,$id)
    {
        $currentPage = request()->query('page', 1);
        return view('admin.PressRelease.create', compact('id','currentPage'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
           
            $release = new Release();
            $release->act_id = $request->act_id ?? null;
            $release->release_title = $request->release_title;
            $release->release_no = $request->release_no;
            $release->release_date = $request->release_date;
            $release->ministry = $request->ministry;
            $release->save();
    
            return redirect()->route('get_release', ['id' => $release->act_id])->with('success', 'Release created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Release: ' . $e->getMessage());
           
            return redirect()->back()->withErrors(['error' => 'Failed to create Release. Please try again.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,string $id)
    {
        $currentPage = request()->query('page', 1);
        $release = Release::findOrFail($id);
        return view('admin.PressRelease.show', compact('release','currentPage')); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $currentPage = $request->query('page', 1);
        $release = Release::findOrFail($id);
        return view('admin.PressRelease.edit',compact('release','currentPage'));
    }


    public function update_release(Request $request,$id){
        try {

            $release = Release::findOrFail($id);
            $release->act_id = $request->act_id ?? null;
            $release->release_title = $request->release_title;
            $release->release_no = $request->release_no;
            $release->release_date = $request->release_date;
            $release->ministry = $request->ministry;
            $release->update();
            return redirect()->route('get_release', ['id' => $release->act_id])->with('success', 'Release updated successfully');
       } catch (\Exception $e) {
            \Log::error('Error creating Release: ' . $e->getMessage());
        
            return redirect()->back()->withErrors(['error' => 'Failed to create Release. Please try again.']);
        }     
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'release_pdf' => 'required',
        ]);
    
        $file = $request->file('release_pdf');
        $filename = time(). '_' . $file->getClientOriginalName();
        $file->move(public_path('admin/release'), $filename);
    
        // Update ActAmendment record
        $release = Release::findOrFail($id);
        $release->release_pdf = $filename;
        $release->save();
        
        return redirect()->back()->with('success', 'Release updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {   
            $release = Release::findOrFail($id);
            $release->delete();
            Session::flash('success', 'deleted successfully.');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to delete.');
        }
        return redirect()->back()->with('flash_timeout', 10);
    }
}
