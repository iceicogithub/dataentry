<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\Notification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;

class NotificationApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        try {
            $notifications = Notification::where('act_id', $id)->with('actSummary')->get();
            $notificationsWithUrls = $notifications->map(function ($notification) {
                $notification->notifications_pdf_url = url('admin/notification/' . $notification->notifications_pdf);
                return $notification;
            });
    
            // Extract PDF URLs
            $pdfFiles = $notificationsWithUrls->pluck('notifications_pdf_url');
    
     
            return response()->json([
                'status' => 200,
                'data' =>   [
                    'notifications' => $notifications,
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
            $notification = Notification::where('notifications_id', $id)->first();

            // Check if the manual exists
            if (!$notification) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Notification not found with the provided ID.',
                    'data' => null
                ], 404);
            }

            // Generate the full URL for the PDF file
            $pdfUrl = url('admin/notification/' . $notification->notifications_pdf);

            // Return the response with the manual details and PDF URL
            return response()->json([
                'status' => 200,
                'data' => [
                    'notificationId' => $notification->notifications_id,
                    'notificationNo' => $notification->notifications_no ?? '',
                    'notificationName' => $notification->notifications_title ?? '',
                    'Ministry' => $notification->ministry ?? '',
                    'notificationDate' => $notification->notifications_date ?? '',
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
