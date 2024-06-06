<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\Circular;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;

class CircularApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        try {
            $circulars = Circular::where('act_id', $id)->with('actSummary')->get();
            $circularsWithUrls = $circulars->map(function ($circular) {
                $circular->circulars_pdf_url = url('admin/circular/' . $circular->circulars_pdf);
                return $circular;
            });
    
            // Extract PDF URLs
            $pdfFiles = $circularsWithUrls->pluck('circulars_pdf_url');
    
     
            return response()->json([
                'status' => 200,
                'data' =>   [
                    'circulars' => $circulars,
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
            $circular = Circular::where('circulars_id', $id)->first();

            // Check if the manual exists
            if (!$circular) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Circular not found with the provided ID.',
                    'data' => null
                ], 404);
            }

            // Generate the full URL for the PDF file
            $pdfUrl = url('admin/circular/' . $circular->circulars_pdf);

            // Return the response with the manual details and PDF URL
            return response()->json([
                'status' => 200,
                'data' => [
                    'circularId' => $circular->circulars_id,
                    'circularNo' => $circular->circulars_no ?? '',
                    'circularName' => $circular->circulars_title ?? '',
                    'Ministry' => $circular->ministry ?? '',
                    'circularDate' => $circular->circulars_date ?? '',
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
