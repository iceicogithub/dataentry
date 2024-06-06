<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\Form;
use Illuminate\Support\Facades\Session;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,$id)
    {
        $act_id = $id;
        $act = Act::where('act_id', $act_id)->first();
        $currentPage = $request->query('page', 1);
        $forms = Form::where('act_id', $act_id)->orderBy('forms_id', 'desc')->paginate(10);
    
        return view('admin.Form.index', compact('act','act_id','forms','currentPage'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request,$id)
    {
        $currentPage = request()->query('page', 1);
        return view('admin.Form.create', compact('id','currentPage'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
           
            $forms = new Form();
            $forms->act_id = $request->act_id ?? null;
            $forms->forms_title = $request->forms_title;
            $forms->forms_no = $request->forms_no;
            $forms->forms_date = $request->forms_date;
            $forms->ministry = $request->ministry;
            $forms->save();
    
            return redirect()->route('get_forms', ['id' => $forms->act_id])->with('success', 'Form created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Form: ' . $e->getMessage());
           
            return redirect()->back()->withErrors(['error' => 'Failed to create Form. Please try again.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,string $id)
    {
        $currentPage = request()->query('page', 1);
        $form = Form::findOrFail($id);
        return view('admin.Form.show', compact('form','currentPage')); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $currentPage = $request->query('page', 1);
        $form = Form::findOrFail($id);
        return view('admin.Form.edit',compact('form','currentPage'));
    }


    public function update_form(Request $request,$id){
        try {

            $form = Form::findOrFail($id);
            $form->act_id = $request->act_id ?? null;
            $form->forms_title = $request->forms_title;
            $form->forms_no = $request->forms_no;
            $form->forms_date = $request->forms_date;
            $form->ministry = $request->ministry;
            $form->update();
            return redirect()->route('get_forms', ['id' => $form->act_id])->with('success', 'Form updated successfully');
       } catch (\Exception $e) {
            \Log::error('Error creating Form: ' . $e->getMessage());
        
            return redirect()->back()->withErrors(['error' => 'Failed to create Form. Please try again.']);
        }
        
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
      
        $request->validate([
            'forms_pdf' => 'required',
        ]);
    
        $file = $request->file('forms_pdf');
        $filename = time(). '_' . $file->getClientOriginalName();
        $file->move(public_path('admin/form'), $filename);
    
        // Update ActAmendment record
        $forms = Form::findOrFail($id);
        $forms->forms_pdf = $filename;
        $forms->save();
        
        return redirect()->back()->with('success', 'Form updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $form = Form::findOrFail($id);
            $form->delete();
            Session::flash('success', 'deleted successfully.');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to delete.');
        }
        return redirect()->back()->with('flash_timeout', 10);
    }
}
