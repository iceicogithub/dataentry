<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\Policy;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;

class PolicyApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        try {
            $policys = Policy::where('act_id', $id)->with('actSummary')->get();
            $policysWithUrls = $policys->map(function ($policy) {
                $policy->policy_pdf_url = url('admin/policy/' . $policy->policy_pdf);
                return $policy;
            });
    
            // Extract PDF URLs
            $pdfFiles = $policysWithUrls->pluck('policy_pdf_url');
    
     
            return response()->json([
                'status' => 200,
                'data' =>   [
                    'policy' => $policys,
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
            $policy = Policy::where('policy_id', $id)->first();

            // Check if the manual exists
            if (!$policy) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Policy not found with the provided ID.',
                    'data' => null
                ], 404);
            }

            // Generate the full URL for the PDF file
            $pdfUrl = url('admin/policy/' . $policy->policy_pdf);

            // Return the response with the manual details and PDF URL
            return response()->json([
                'status' => 200,
                'data' => [
                    'policyId' => $policy->policy_id,
                    'policyNo' => $policy->policy_no ?? '',
                    'policyName' => $policy->policy_title ?? '',
                    'Ministry' => $policy->ministry ?? '',
                    'policyDate' => $policy->policy_date ?? '',
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
