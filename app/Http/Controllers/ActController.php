<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\ActSummary;
use App\Models\Appendices;
use App\Models\Article;
use App\Models\Category;
use App\Models\MainType;
use App\Models\Parts;
use App\Models\PartsType;
use App\Models\SubSection;
use App\Models\Footnote;
use App\Models\Chapter;
use App\Models\Form;
use App\Models\Priliminary;
use App\Models\Regulation;
use App\Models\Rules;
use App\Models\Schedule;
use App\Models\Section;
use App\Models\State;
use App\Models\Status;
use App\Models\SubRules;
use App\Models\SubType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ActController extends Controller
{

    public function index()
    {
        $act = Act::with('CategoryModel')->get();

        return view('admin.act.index', compact('act'));
    }

    public function get_act_section(Request $request, $id)
    {
        $act_id = $id;
        $act = Act::where('act_id', $act_id)->first();
        if ($act) {
            $act_footnote_titles = json_decode($act->act_footnote_title, true);
            $act_footnote_descriptions = json_decode($act->act_footnote_description, true);
        }
        
        $act_section = Section::where('act_id', $id)
        ->with('MainTypeModel', 'Partmodel', 'ChapterModel', 'PriliminaryModel')
        ->get()
        ->sortBy(function ($section) {
            $mixstring = $section->section_no;
    
           // Check if the regular expression matches
            if (preg_match('/^(\d+)([a-zA-Z]*)$/', $mixstring, $matches)) {
                $numericPart = str_pad($matches[1], 10, '0', STR_PAD_LEFT);
                $alphabeticPart = strtolower($matches[2]);
    
                return $numericPart . $alphabeticPart;
            } else {
                // Handle the case where the regular expression doesn't match
                // You can choose to return something specific or handle it in another way
                // return $mixstring; // Default behavior is to return the mixstring as is
            }
        });
        
        $act_rule = Rules::where('act_id', $id)
        ->with('MainTypeModel', 'Schedulemodel', 'footnoteModel')
        ->get()
        ->sortBy(function ($rule) {
            $mixstring = $rule->rule_no;
    
            // Check if the regular expression matches
            if (preg_match('/^(\d+)([a-zA-Z]*)$/', $mixstring, $matches)) {
                $numericPart = str_pad($matches[1], 10, '0', STR_PAD_LEFT);
                $alphabeticPart = strtolower($matches[2]);
    
                return $numericPart . $alphabeticPart;
            } else {
                // Handle the case where the regular expression doesn't match
                // You can choose to return something specific or handle it in another way
                // return $mixstring; // Default behavior is to return the mixstring as is
            }
        });
        
        $act_article = Article::where('act_id', $id)
        ->with('MainTypeModel', 'Schedulemodel','Appendicesmodel','Partmodel','ChapterModel','PriliminaryModel')
        ->get()
        ->sortBy(function ($article) {
            $mixstring = $article->article_no;
    
            // Check if the regular expression matches
            if (preg_match('/^(\d+)([a-zA-Z]*)$/', $mixstring, $matches)) {
                $numericPart = str_pad($matches[1], 10, '0', STR_PAD_LEFT);
                $alphabeticPart = strtolower($matches[2]);
    
                return $numericPart . $alphabeticPart;
            } else {
                // Handle the case where the regular expression doesn't match
                // You can choose to return something specific or handle it in another way
                // return $mixstring; // Default behavior is to return the mixstring as is
            }
        });

    

        return view('admin.section.index', compact('act_section', 'act_id', 'act', 'act_footnote_titles', 'act_footnote_descriptions', 'act_rule','act_article'));
    }

    public function create(Request $request, $id)
    {
        $category = Category::all();
        $status = Status::all();
        $states = State::all();
        $mtype = MainType::all();
        $stype = SubType::all();
        $parts = PartsType::all();

        $act = Act::where('act_id', $id)->first();
        $showFormTitle = ($act->act_summary && in_array('6', json_decode($act->act_summary, true)));
        // dd($showFormTitle);
        // die();

        return view('admin.act.create', compact('category', 'status', 'states', 'mtype', 'parts', 'stype', 'act', 'showFormTitle'));
    }

    public function store(Request $request, $id)
    {

        // dd($request);
        // die();

        try {

            $act = Act::find($id);
            $act->update([
                'category_id' => $request->category_id,
                'state_id' => $request->state_id ?? null,
                'act_title' => $request->act_title,
                'act_content' => $request->act_content ?? null,
            ]);


            foreach ($request->maintype_id as $key => $maintypeId) {

                if ($maintypeId == "1") {
                    $chapt = new Chapter();
                    $chapt->act_id = $act->act_id ?? null;
                    $chapt->maintype_id = $maintypeId;
                    $chapt->chapter_title = $request->chapter_title[$key] ?? null;
                    $chapt->save();

                    if (isset($request->subtypes_id[$key]) && $request->subtypes_id[$key] == 1) {
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->section_title[$key] as $index => $sectiontitle) {
                            if (
                                isset($request->section_no[$key][$index]) &&
                                is_string($request->section_no[$key][$index])
                            ) {
                                $currentSectionNo = $request->section_no[$key][$index];

                                // Update SubSection records, Footnote records, etc. (similar to Section)
                                $lastSection = Section::orderBy('section_rank', 'desc')->first();

                                $lastRank = $lastSection ? $lastSection->section_rank : 0;
                                // Create the new section with the updated section_no
                                $section = Section::create([
                                    'section_rank' => $lastRank + 1,
                                    'section_no' => $currentSectionNo,
                                    'act_id' => $act->act_id,
                                    'maintype_id' => $maintypeId,
                                    'chapter_id' => $chapt->chapter_id,
                                    'subtypes_id' => $subtypes_id,
                                    'section_title' => $sectiontitle,
                                ]);
                            }
                        }
                    }
                    
                    elseif ($request->subtypes_id[$key] == 2){
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach($request->article_title[$key] as $index => $articletitle) {
                            $currentArticleNo = $request->article_no[$key][$index];

                            $article = Article::create([
                               'article_no' => $currentArticleNo,
                               'act_id' => $act->act_id,
                               'maintype_id' => $maintypeId,
                               'chapter_id' => $chapt->chapter_id,
                               'subtypes_id' => $subtypes_id,
                               'article_title' => $articletitle,
                            ]);

                            $articleId = $article->article_id;

                            $form = Form::create([
                                'article_id' => $articleId,
                                'act_id' => $act->act_id,
                                'form_title' => $request->form_title,
                            ]);
                        }
                    }


                    elseif ($request->subtypes_id[$key] == 3){
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach($request->rule_title[$key] as $index => $ruletitle) {
                            $currentRuleNo = $request->rule_no[$key][$index];

                            $rule = Rule::create([
                               'rule_no' => $currentRuleNo,
                               'act_id' => $act->act_id,
                               'maintype_id' => $maintypeId,
                               'chapter_id' => $chapt->chapter_id,
                               'subtypes_id' => $subtypes_id,
                               'rule_title' => $ruletitle,
                            ]);

                            $ruleId = $rule->rule_id;

                            $form = Form::create([
                                'rule_id' => $ruleId,
                                'act_id' => $act->act_id,
                                'form_title' => $request->form_title,
                            ]);
                        }
                    }
                    
                    
                    elseif ($request->subtypes_id[$key] == 4) {
                        
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->regulation_title[$key] as $index => $regulationtitle) {
                            $currentRegulationNo = $request->regulation_no[$key][$index];
                           

                            $regulation = Regulation::create([
                                'regulation_no' => $currentRegulationNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'chapter_id' => $chapt->chapter_id,
                                'subtypes_id' => $subtypes_id,
                                'regulation_title' => $regulationtitle,
                            ]);

                            $regulationId = $regulation->regulation_id;

                            $form = Form::create([
                                'regulation_id' => $regulationId,
                                'act_id' => $act->act_id,
                                'form_title' => $request->form_title,
                            ]);
                        }
                    }


                    elseif ($request->subtypes_id[$key] == 5) {
                        
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->list_title[$key] as $index => $listtitle) {
                            $currentListNo = $request->list_no[$key][$index];
                           

                            $list = List::create([
                                'list_no' => $currentListNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'chapter_id' => $chapt->chapter_id,
                                'subtypes_id' => $subtypes_id,
                                'list_title' => $listtitle,
                            ]);

                            $listId = $list->list_id;

                            $form = Form::create([
                                'list_id' => $listId,
                                'act_id' => $act->act_id,
                                'form_title' => $request->form_title,
                            ]);
                        }
                    }

                    
                    elseif ($request->subtypes_id[$key] == 6) {
                        
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->part_title[$key] as $index => $parttitle) {
                            $currentPartNo = $request->part_no[$key][$index];
                           

                            $part = Part::create([
                                'part_no' => $currentPartNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'chapter_id' => $chapt->chapter_id,
                                'subtypes_id' => $subtypes_id,
                                'part_title' => $parttitle,
                            ]);

                            $partId = $part->part_id;

                            $form = Form::create([
                                'part_id' => $partId,
                                'act_id' => $act->act_id,
                                'form_title' => $request->form_title,
                            ]);
                        }
                    }


                    elseif ($request->subtypes_id[$key] == 7) {
                        
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->appendix_title[$key] as $index => $appendixtitle) {
                            $currentAppendixNo = $request->appendix_no[$key][$index];
                           

                            $appendix = Appendix::create([
                                'appendix_no' => $currentAppendixNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'chapter_id' => $chapt->chapter_id,
                                'subtypes_id' => $subtypes_id,
                                'appendix_title' => $appendixtitle,
                            ]);

                            $appendixId = $appendix->appendix_id;

                            $form = Form::create([
                                'appendix_id' => $appendixId,
                                'act_id' => $act->act_id,
                                'form_title' => $request->form_title,
                            ]);
                        }
                    }


                    elseif ($request->subtypes_id[$key] == 8) {
                        
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->order_title[$key] as $index => $ordertitle) {
                            $currentOrderNo = $request->order_no[$key][$index];
                           

                            $order = Order::create([
                                'order_no' => $currentOrderNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'chapter_id' => $chapt->chapter_id,
                                'subtypes_id' => $subtypes_id,
                                'ordertitle' => $order_title,
                            ]);

                            $orderId = $order->order_id;

                            $form = Form::create([
                                'order_id' => $orderId,
                                'act_id' => $act->act_id,
                                'form_title' => $request->form_title,
                            ]);
                        }
                    }


                    elseif ($request->subtypes_id[$key] == 9) {
                        
                        $subtypes_id = $request->subtypes_id[$key] ?? null;
                        foreach ($request->annexture_title[$key] as $index => $annexturetitle) {
                            $currentAnnextureNo = $request->annexture_no[$key][$index];
                           

                            $annexture = Annexture::create([
                                'annexture_no' => $currentAnnextureNo,
                                'act_id' => $act->act_id,
                                'maintype_id' => $maintypeId,
                                'chapter_id' => $chapt->chapter_id,
                                'subtypes_id' => $subtypes_id,
                                'annexturetitle' => $annexture_title,
                            ]);

                            $annextureId = $annexture->annexture_id;

                            $form = Form::create([
                                'annexture_id' => $annextureId,
                                'act_id' => $act->act_id,
                                'form_title' => $request->form_title,
                            ]);
                        }
                    }


                } elseif ($maintypeId == "2") {
                    $parts = new Parts();
                    $parts->act_id = $act->act_id ?? null;
                    $parts->maintype_id = $maintypeId;
                    $parts->partstype_id = $request->partstype_id[$key] ?? null;
                    $parts->parts_title = $request->parts_title[$key] ?? null;
                    $parts->save();

                    $subtypes_id = $request->subtypes_id[$key] ?? null;

                    foreach ($request->section_title[$key] as $index => $sectiontitle) {
                        $currentSectionNo = $request->section_no[$key][$index];
                       

                        $lastSection = Section::orderBy('section_rank', 'desc')->first();
                        $lastRank = $lastSection ? $lastSection->section_rank : 0;
                        // Create the new section with the updated section_no
                        $section = Section::create([
                            'section_rank' => $lastRank + 1,
                            'section_no' => $currentSectionNo,
                            'act_id' => $act->act_id,
                            'maintype_id' => $maintypeId,
                            'parts_id' => $parts->parts_id,
                            'subtypes_id' => $subtypes_id,
                            'section_title' => $sectiontitle,
                        ]);
                    }

                } elseif ($maintypeId == "3") {
                    $priliminary = new Priliminary();
                    $priliminary->act_id = $act->act_id ?? null;
                    $priliminary->maintype_id = $maintypeId;
                    $priliminary->priliminary_title = $request->priliminary_title[$key] ?? null;
                    $priliminary->save();

                    $subtypes_id = $request->subtypes_id[$key] ?? null;

                    foreach ($request->section_title[$key] as $index => $sectiontitle) {
                        $currentSectionNo = $request->section_no[$key][$index];
                       

                        $lastSection = Section::orderBy('section_rank', 'desc')->first();
                        $lastRank = $lastSection ? $lastSection->section_rank : 0;
                        // Create the new section with the updated section_no
                        $section = Section::create([
                            'section_rank' => $lastRank + 1,
                            'section_no' => $currentSectionNo,
                            'act_id' => $act->act_id,
                            'maintype_id' => $maintypeId,
                            'priliminary_id' => $priliminary->priliminary_id,
                            'subtypes_id' => $subtypes_id,
                            'section_title' => $sectiontitle,
                        ]);
                    }

                } elseif ($maintypeId == "4") {
                    $schedule = new Schedule();
                    $schedule->act_id = $act->act_id ?? null;
                    $schedule->maintype_id = $maintypeId;
                    $schedule->schedule_title = $request->schedule_title[$key] ?? null;
                    $schedule->save();

                    $subtypes_id = $request->subtypes_id[$key] ?? null;

                    foreach ($request->rule_title[$key] as $index => $ruletitle) {
                        $currentruleNo = $request->rule_no[$key][$index];
                       

                        $lastrule = Rules::orderBy('rule_rank', 'desc')->first();
                        $lastRank = $lastrule ? $lastrule->rule_rank : 0;
                        // Create the new section with the updated section_no
                        $rule = Rules::create([
                            'rule_rank' => $lastRank + 1,
                            'rule_no' => $currentruleNo,
                            'act_id' => $act->act_id,
                            'maintype_id' => $maintypeId,
                            'schedule_id' => $schedule->schedule_id,
                            'subtypes_id' => $subtypes_id,
                            'rule_title' => $ruletitle,
                        ]);
                    }

                } elseif ($maintypeId == "5") {
                    $appendices = new Appendices();
                    $appendices->act_id = $act->act_id ?? null;
                    $appendices->maintype_id = $maintypeId;
                    $appendices->appendices_title = $request->appendices_title[$key] ?? null;
                    $appendices->save();

                    $subtypes_id = $request->subtypes_id[$key] ?? null;

                    foreach ($request->article_title[$key] as $index => $articletitle) {
                        $currentarticleNo = $request->article_no[$key][$index];
                       

                        $lastarticle = Article::orderBy('article_rank', 'desc')->first();
                        $lastRank = $lastarticle ? $lastarticle->article_rank : 0;
                        // Create the new section with the updated section_no
                        $article = Article::create([
                            'article_rank' => $lastRank + 1,
                            'article_no' => $currentarticleNo,
                            'act_id' => $act->act_id,
                            'maintype_id' => $maintypeId,
                            'appendices_id' => $appendices->appendices_id,
                            'subtypes_id' => $subtypes_id,
                            'article_title' => $articletitle,
                        ]);
                    }
                    
                }  else {
                    dd("something went wrong - right now we are working only in chapter and parts");
                }
            }
            if ($request->subtypes_id[$key] == 1) {
                return redirect()->route('get_act_section', ['id' => $id])->with('success', 'Section added successfully');
            } elseif ($request->subtypes_id[$key] == 4) {
                return redirect()->route('get_act_regulation', ['id' => $id])->with('success', 'Regulation added successfully');
            } else {
                return redirect()->route('get_act_section', ['id' => $id])->with('success', 'Index added successfully');
            }
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }

    public function view(Request $request, $id)
    {
        $export = Act::where('act_id', $id)->get();
        return view('admin.act.view', compact('export'));
    }
    public function update_main_act(Request $request, $id)
    {

        try {
            $validator = Validator::make($request->all(), [
                'act_footnote_title.*' => 'nullable',
                'act_footnote_description.*' => 'nullable',
            ]);

            if ($validator->fails()) {
                throw ValidationException::withMessages($validator->errors()->toArray());
            }

            $act = Act::find($id);
            $act->act_title = $request->act_title;
            $act->act_no = $request->act_no ?? null;
            $act->act_date = $request->act_date ?? null;
            $act->act_description = $request->act_description ?? null;
            $act->act_footnote_title = json_encode($request->act_footnote_title);
            $act->act_footnote_description = json_encode($request->act_footnote_description);
            $act->update();


            return redirect()->back()->with('success', 'Main Act Updated Successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }

    public function new_act()
    {
        $category = Category::all();
        $states = State::all();
        $actSummary = ActSummary::all();
        return view('admin.act.new_act', compact('category', 'states', 'actSummary'));
    }

    public function store_new_act(Request $request)
    {
        try {
            $act = new Act();
            $act->category_id = $request->category_id;
            $act->state_id = $request->state_id ?? null;
            $act->act_title = $request->act_title;
            $actSummaries = ActSummary::pluck('id')->map(function ($id) {
                return (string) $id;
            })->toArray();
            $act->act_summary = json_encode($actSummaries);
            $act->save();

            return redirect()->route('act')->with('success', 'Act created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->route('act')->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }


    public function edit_main_act(Request $request, $id)
    {
        $act_id = $id;
        $mainact = ActSummary::all();
        $act = Act::find($act_id);

        return view('admin.act.main_act', compact('act_id', 'mainact', 'act'));
    }



    public function edit()
    {
        return view('admin.act.edit');
    }

    public function destroy(string $id)
    {
        try {
            $act = Act::find($id);
            if (!$act) {
                return redirect()->back()->withErrors(['error' => 'Act not found.']);
            }

            // Delete related records
            Chapter::where('act_id', $id)->delete();
            Parts::where('act_id', $id)->delete();
            Section::where('act_id', $id)->delete();
            Rules::where('act_id', $id)->delete();
            Regulation::where('act_id', $id)->delete();
            SubSection::where('act_id', $id)->delete();
            SubRules::where('act_id', $id)->delete();
            Footnote::where('act_id', $id)->delete();

            $act->delete();

            return redirect()->back()->with('success', 'Act and related records deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting act and related records: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete act and related records. Please try again.' . $e->getMessage()]);
        }
    }
}
