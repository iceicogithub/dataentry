<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\ActSummary;
use App\Models\Category;
use App\Models\MainType;
use App\Models\Parts;
use App\Models\PartsType;
use App\Models\SubSection;
use App\Models\Footnote;
use App\Models\Chapter;
use App\Models\Regulation;
use App\Models\Section;
use App\Models\State;
use App\Models\Status;
use App\Models\SubType;
use App\Models\Rules;

class RulesController extends Controller
{
    public function index(Request $request, $id)
    {
        $act_id = $id;
        $act = Act::where('act_id', $act_id)->first();
        $act_rule = Rules::where('act_id', $id)->with('MainTypeModel', 'Partmodel', 'ChapterModel')
            ->orderBy('rule_no', 'asc')->get();

        return view('admin.rules.index', compact('act_rule', 'act_id', 'act'));
    }

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
