<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\Release;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;

class ReleaseApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        try {
            $releases = Release::where('act_id', $id)->with('actSummary')->get();
            $releasesWithUrls = $releases->map(function ($release) {
                $release->release_pdf_url = url('admin/release/' . $release->release_pdf);
                return $release;
            });
    
            // Extract PDF URLs
            $pdfFiles = $releasesWithUrls->pluck('release_pdf_url');
    
     
            return response()->json([
                'status' => 200,
                'data' =>   [
                    'releases' => $releases,
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
            $release = Release::where('release_id', $id)->first();

            if (!$release) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Form not found with the provided ID.',
                    'data' => null
                ], 404);
            }

            
            $pdfUrl = url('admin/release/' . $release->release_pdf);

           
            return response()->json([
                'status' => 200,
                'data' => [
                    'releaseId' => $release->release_title,
                    'releaseNo' => $release->release_no ?? '',
                    'releaseName' => $release->release_title ?? '',
                    'Ministry' => $release->ministry ?? '',
                    'releaseDate' => $release->release_date ?? '',
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
