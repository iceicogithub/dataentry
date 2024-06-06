<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\Notification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,$id)
    {
        $act_id = $id;
        $act = Act::where('act_id', $act_id)->first();
        $currentPage = $request->query('page', 1);
        $notification = Notification::where('act_id', $act_id)->orderBy('notifications_id', 'desc')->paginate(10);
    
        return view('admin.Notification.index', compact('act','act_id','notification','currentPage'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $id)
    {
        $currentPage = request()->query('page', 1);
        return view('admin.Notification.create', compact('id', 'currentPage'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
           
            $notifications = new Notification();
            $notifications->act_id = $request->act_id ?? null;
            $notifications->notifications_title = $request->notifications_title;
            $notifications->notifications_no = $request->notifications_no;
            $notifications->notifications_date = $request->notifications_date;
            $notifications->ministry = $request->ministry;
            $notifications->save();
    
            return redirect()->route('get_notification', ['id' => $notifications->act_id])->with('success', 'Notifications created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Manuals: ' . $e->getMessage());
           
            return redirect()->back()->withErrors(['error' => 'Failed to create Notifications. Please try again.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,string $id)
    {
        $currentPage = request()->query('page', 1);
        $notification = Notification::findOrFail($id);
        return view('admin.Notification.show', compact('notification','currentPage')); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $currentPage = $request->query('page', 1);
        $notification = Notification::findOrFail($id);
        return view('admin.Notification.edit',compact('notification','currentPage'));
    }


    public function update_notifications(Request $request,$id){
        try {

            $notifications = Notification::findOrFail($id);
            $notifications->act_id = $request->act_id ?? null;
            $notifications->notifications_title = $request->notifications_title;
            $notifications->notifications_no = $request->notifications_no;
            $notifications->notifications_date = $request->notifications_date;
            $notifications->ministry = $request->ministry;
            $notifications->update();
            return redirect()->route('get_notification', ['id' => $notifications->act_id])->with('success', 'Notifications updated successfully');
       } catch (\Exception $e) {
            \Log::error('Error creating Manuals: ' . $e->getMessage());
        
            return redirect()->back()->withErrors(['error' => 'Failed to create Manuals. Please try again.']);
        }
        
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       
        $request->validate([
            'notifications_pdf' => 'required',
        ]);
    
        $file = $request->file('notifications_pdf');
        $filename = time(). '_' . $file->getClientOriginalName();
        $file->move(public_path('admin/notification'), $filename);
    
        // Update ActAmendment record
        $notifications = Notification::findOrFail($id);
        $notifications->notifications_pdf = $filename;
        $notifications->save();
        
        return redirect()->back()->with('success', 'Notification updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $notification = Notification::findOrFail($id);
            $notification->delete();
            Session::flash('success', 'deleted successfully.');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to delete RuleMain.');
        }
        return redirect()->back()->with('flash_timeout', 10);
    }
}
