<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\Category;
use App\Models\State;
use App\Models\MainTypeRegulation;
use App\Models\SubTypeRegulation;
use App\Models\PartsType;
use App\Models\RegulationMain;
use App\Models\RegulationTable;
use App\Models\NewRegulation;
use Illuminate\Pagination\LengthAwarePaginator;

class MainRegulationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $act_id = $id;
        $act = Act::where('act_id', $act_id)->first();
       $new_rule = NewRegulation::where('act_id', $act_id)->get();
      
       $perPage = request()->get('perPage') ?: 10;
        $page = request()->get('page') ?: 1;
        $slicedItems = array_slice($new_rule->toArray(), ($page - 1) * $perPage, $perPage);

        $paginatedCollection = new LengthAwarePaginator(
            $slicedItems,
            count($new_rule),
            $perPage,
            $page
        );

        $paginatedCollection->appends(['perPage' => $perPage]);

        $paginatedCollection->withPath(request()->url());
        return view('admin.MainRegulation.index', compact('act','act_id','new_rule','paginatedCollection'));
 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
