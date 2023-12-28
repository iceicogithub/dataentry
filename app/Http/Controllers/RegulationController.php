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

class RegulationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id)
    {
        $act_id = $id;
        $act = Act::where('act_id', $act_id)->first();
        $act_regulation = Regulation::where('act_id', $id)->with('MainTypeModel', 'Partmodel', 'ChapterModel')
            ->orderBy('regulation_no', 'asc')->get();

        return view('admin.regulations.index', compact('act_regulation', 'act_id', 'act'));
    }

    public function edit_regulation($id)
    {
        $regulations = Regulation::with('ChapterModel', 'Partmodel')->where('regulation_id', $id)->first();
        $reg = Regulation::where('regulation_id', $id)->with('footnoteModel')->get();
        // dd($reg);
        // die();
        return view('admin.regulations.edit', compact('regulations', 'reg'));
    }

    public function update(Request $request, $id)
    {
        try {
            // Check if section_id exists in the request
            if (!$request->has('regulation_id')) {
                return redirect()->route('edit-regulation', ['id' => $id])->withErrors(['error' => 'Regulation ID is missing']);
            }

            $regulations = Regulation::find($request->regulation_id);

            // Check if the section is found
            if (!$regulations) {
                return redirect()->route('edit-regulation', ['id' => $id])->withErrors(['error' => 'Regulations not found']);
            }
            if ($regulations->regulation_no == $request->regulation_no) {
                $regulations->regulation_content = $request->regulation_content ?? null;
                $regulations->regulation_title = $request->regulation_title ?? null;
                $regulations->regulation_no = $request->regulation_no ?? null;
                $regulations->update();
            } else {
                $currentRegulationNo = $request->regulation_no;

                // Update Section records
                // Regulation::where('regulation_no', '>=', $currentRegulationNo)
                //     ->get()
                //     ->each(function ($regulation) {
                //         $regulation->increment('regulation_no');
                //     });

                // Update Footnote records
                // Footnote::where('regulation_no', '>=', $currentRegulationNo)
                //     ->get()
                //     ->each(function ($footnote) {
                //         $footnote->increment('regulation_no');
                //     });

                $regulations->regulation_content = $request->regulation_content ?? null;
                $regulations->regulation_title = $request->regulation_title ?? null;
                $regulations->regulation_no = $request->regulation_no ?? null;
                $regulations->update();
            }

            // Store Footnotes
            if ($request->has('footnote_title')) {
                foreach ($request->footnote_title as $key => $item) {
                    // Check if the key exists before using it
                    if ($request->filled('footnote_id.' . $key)) {
                        $foot = Footnote::find($request->footnote_id[$key]);

                        if ($foot) {
                            $foot->footnote_title = $request->footnote_title[$key] ?? null;
                            $foot->footnote_content = $request->footnote[$key] ?? null;
                            $foot->update();
                        }
                    } else {
                        $footnote = new Footnote();
                        $footnote->regulation_id = $id ?? null;
                        $footnote->regulation_no = $regulations->regulation_no ?? null;
                        $footnote->act_id = $regulations->act_id ?? null;
                        $footnote->chapter_id = $regulations->chapter_id ?? null;
                        $footnote->parts_id = $regulations->parts_id ?? null;
                        $footnote->footnote_title = $request->footnote_title[$key] ?? null;
                        $footnote->footnote_content = $request->footnote[$key] ?? null;
                        $footnote->save();
                    }
                }
            }

            return redirect()->route('get_act_regulation', ['id' => $regulations->act_id])->with('success', 'Regulation updated successfully');
        } catch (\Exception $e) {
            \Log::error('Error updating Act: ' . $e->getMessage());
            return redirect()->route('edit-section', ['id' => $id])->withErrors(['error' => 'Failed to update Section. Please try again.' . $e->getMessage()]);
        }
    }

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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $regulation = Regulation::find($id);
    
            if (!$regulation) {
                return redirect()->back()->withErrors(['error' => 'Regulation not found.']);
            }
    
            $regulation->delete();
    
            return redirect()->back()->with('success', 'Regulation deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting regulation: ' . $e->getMessage());
    
            return redirect()->back()->withErrors(['error' => 'Failed to delete regulation. Please try again.' . $e->getMessage()]);
        }
    }
}
