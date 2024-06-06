<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\Policy;
use Illuminate\Support\Facades\Session;

class PolicyController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index(Request $request,$id)
    {
        $act_id = $id;
        $act = Act::where('act_id', $act_id)->first();
        $currentPage = $request->query('page', 1);
        $policy = Policy::where('act_id', $act_id)->orderBy('policy_id', 'desc')->paginate(10);
    
        return view('admin.Policy.index', compact('act','act_id','policy','currentPage'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request,$id)
    {
        $currentPage = request()->query('page', 1);
        return view('admin.Policy.create', compact('id','currentPage'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
           
            $policy = new Policy();
            $policy->act_id = $request->act_id ?? null;
            $policy->policy_title = $request->policy_title;
            $policy->policy_no = $request->policy_no;
            $policy->policy_date = $request->policy_date;
            $policy->ministry = $request->ministry;
            $policy->save();
    
            return redirect()->route('get_policy', ['id' => $policy->act_id])->with('success', 'Policy created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Policy: ' . $e->getMessage());
           
            return redirect()->back()->withErrors(['error' => 'Failed to create Policy. Please try again.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,string $id)
    {
        $currentPage = request()->query('page', 1);
        $policy = Policy::findOrFail($id);
        return view('admin.Policy.show', compact('policy','currentPage')); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $currentPage = $request->query('page', 1);
        $policy = Policy::findOrFail($id);
        return view('admin.Policy.edit',compact('policy','currentPage'));
    }


    public function update_policy(Request $request,$id){
        try {

            $policy = Policy::findOrFail($id);
            $policy->act_id = $request->act_id ?? null;
            $policy->policy_title = $request->policy_title;
            $policy->policy_no = $request->policy_no;
            $policy->policy_date = $request->policy_date;
            $policy->ministry = $request->ministry;
            $policy->update();
            return redirect()->route('get_policy', ['id' => $policy->act_id])->with('success', 'Policy updated successfully');
       } catch (\Exception $e) {
            \Log::error('Error creating Policy: ' . $e->getMessage());
        
            return redirect()->back()->withErrors(['error' => 'Failed to create Policy. Please try again.']);
        }
        
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
      
        $request->validate([
            'policy_pdf' => 'required',
        ]);
    
        $file = $request->file('policy_pdf');
      
        $filename = time(). '_' . $file->getClientOriginalName();
        $file->move(public_path('admin/policy'), $filename);
       
        $policy = Policy::findOrFail($id);
        $policy->policy_pdf = $filename;
        $policy->update();
        
        return redirect()->back()->with('success', 'Policy updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $policy = Policy::findOrFail($id);
            $policy->delete();
            Session::flash('success', 'deleted successfully.');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to delete.');
        }
        return redirect()->back()->with('flash_timeout', 10);
    }
}
