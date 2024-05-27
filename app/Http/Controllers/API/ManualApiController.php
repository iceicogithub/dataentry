<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\Manual;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;

class ManualApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        try {
            $manuals = Manual::where('act_id', $id)->get();
            $manualsWithUrls = $manuals->map(function ($manual) {
                $manual->manuals_pdf_url = url('admin/manuals/' . $manual->manuals_pdf);
                return $manual;
            });
    
            // Extract PDF URLs
            $pdfFiles = $manualsWithUrls->pluck('manuals_pdf_url');
    
     
            return response()->json([
                'status' => 200,
                'data' =>   [
                    'manuals' => $manuals,
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
            // Retrieve the manual by ID
            $manual = Manual::where('manuals_id', $id)->first();

            // Check if the manual exists
            if (!$manual) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Manual not found with the provided ID.',
                    'data' => null
                ], 404);
            }

            // Generate the full URL for the PDF file
            $pdfUrl = url('admin/manual/' . $manual->manuals_pdf);

            // Return the response with the manual details and PDF URL
            return response()->json([
                'status' => 200,
                'data' => [
                    'manualId' => $manual->manuals_id,
                    'manualNo' => $manual->manuals_no ?? '',
                    'manualName' => $manual->manuals_title ?? '',
                    'Ministry' => $manual->ministry ?? '',
                    'manualDate' => $manual->manuals_date ?? '',
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
