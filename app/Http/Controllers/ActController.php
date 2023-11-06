<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Act;
use App\Models\Status;
use Illuminate\Http\Request;

class ActController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::all();
        $status = Status::all();
        return view('admin.Act.index', compact('category', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = Category::all();
        $status = Status::all();
        return view('admin.Act.create', compact('category', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        try {
            $act = new Act();

            $act->category_id = $request->category_id;
            $act->act = $request->act;
            if ($request->state) {
                $act->state = $request->state;
            }
            $act->status = $request->status;

            $act->save();

            return redirect()->route('act')->with('success', 'Act created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to create Act. Please try again.');
        }
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
