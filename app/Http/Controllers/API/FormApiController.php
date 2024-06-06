<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\Form;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;
class FormApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        try {
            $forms = Form::where('act_id', $id)->with('actSummary')->get();
            $formsWithUrls = $forms->map(function ($form) {
                $form->forms_pdf_url = url('admin/form/' . $form->forms_pdf);
                return $form;
            });
    
            // Extract PDF URLs
            $pdfFiles = $formsWithUrls->pluck('forms_pdf_url');
    
     
            return response()->json([
                'status' => 200,
                'data' =>   [
                    'forms' => $forms,
                    'pdf_files' => $pdfFiles,
                ]
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
        try {
            $form = Form::where('forms_id', $id)->first();

            if (!$form) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Form not found with the provided ID.',
                    'data' => null
                ], 404);
            }

            
            $pdfUrl = url('admin/form/' . $form->forms_pdf);

           
            return response()->json([
                'status' => 200,
                'data' => [
                    'formId' => $form->forms_id,
                    'formNo' => $form->forms_no ?? '',
                    'formName' => $form->forms_title ?? '',
                    'Ministry' => $form->ministry ?? '',
                    'formDate' => $form->forms_date ?? '',
                    'pdfUrl' => $pdfUrl,
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server Error: ' . $e->getMessage(),
                'data' => null
            ], 500);
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
