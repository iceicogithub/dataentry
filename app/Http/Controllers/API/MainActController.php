<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Act;
use App\Models\Chapter;
use App\Models\Footnote;
use App\Models\MainType;
use App\Models\Parts;
use App\Models\PartsType;
use App\Models\Priliminary;
use App\Models\Rules;
use App\Models\Schedule;
use App\Models\Regulation;
use App\Models\Section;
use App\Models\SubSection;
use App\Models\Article;
use App\Models\Appendices;
use App\Models\SubType;
use App\Models\Lists;
use App\Models\Part;
use App\Models\SubPart;
use App\Models\Appendix;
use App\Models\SubAppendix;
use App\Models\Orders;
use App\Models\SubOrders;
use App\Models\Annexure;
use App\Models\SubAnnexure;
use App\Models\Stschedule;
use App\Models\SubStschedule;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MainActController extends Controller
{

    public function show()
    {
        try {
            $act = Act::all();
            return response()->json([
                'status' => 200,
                'data' =>   [
                    'act' => $act,
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

    public function index(Request $request, $id)
    {
        try {

            $act = Act::findOrFail($id);
            $type = MainType::all();
            $chapter = Chapter::where('act_id', $id)->get();
            $part = Parts::where('act_id', $id)->get();
            $priliminarys = Priliminary::where('act_id', $id)->get();
            $schedules = Schedule::where('act_id', $id)->get();
            $Appendix = Appendix::where('act_id', $id)->get();
         
          

            $sections = Section::where('act_id', $id)
                ->with('subsectionModel', 'footnoteModel', 'Partmodel', 'ChapterModel','PriliminaryModel','Schedulemodel','Appendixmodel')
                ->get()
                ->sortBy(function ($section) {
                    // Sorting conditions
                        return [floatval($section->section_rank)];
            });

            dd($sections);
            die();


            $articles = Article::where('act_id', $id)
                    ->orWhereIn('chapter_id', $chapter->pluck('chapter_id'))
                    ->orWhereIn('parts_id', $part->pluck('part_id'))
                    ->orWhereIn('priliminary_id', $priliminarys->pluck('priliminary_id'))
                    ->orWhereIn('schedule_id', $schedules->pluck('schedule_id'))
                    ->orWhereIn('appendix_id', $Appendix->pluck('appendix_id'))
                    ->with('subArticleModel','footnoteModel', 'Partmodel', 'ChapterModel','PriliminaryModel','Schedulemodel','Appendixmodel')
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
                            return $mixstring; // Default behavior is to return the mixstring as is
                        }
            }, SORT_NATURAL);

            $rules = Rules::where('act_id', $id)
                ->orWhereIn('chapter_id', $chapter->pluck('chapter_id'))
                ->orWhereIn('parts_id', $part->pluck('part_id'))
                ->orWhereIn('priliminary_id', $priliminarys->pluck('priliminary_id'))
                ->orWhereIn('schedule_id', $schedules->pluck('schedule_id'))
                ->orWhereIn('appendix_id', $Appendix->pluck('appendix_id'))
                ->with('subruleModel','footnoteModel', 'Partmodel', 'ChapterModel','PriliminaryModel','Schedulemodel','Appendixmodel')
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
                    return $mixstring; // Default behavior is to return the mixstring as is
                }
            }, SORT_NATURAL);

            $regulations = Regulation::where('act_id', $id)
                ->orWhereIn('chapter_id', $chapter->pluck('chapter_id'))
                ->orWhereIn('parts_id', $part->pluck('part_id'))
                ->orWhereIn('priliminary_id', $priliminarys->pluck('priliminary_id'))
                ->orWhereIn('schedule_id', $schedules->pluck('schedule_id'))
                ->orWhereIn('appendix_id', $Appendix->pluck('appendix_id'))
                ->with('subRegulationModel','footnoteModel', 'Partmodel', 'ChapterModel','PriliminaryModel','Schedulemodel','Appendixmodel')
                ->get()
                ->sortBy(function ($regulation) {
                $mixstring = $regulation->regulation_no;

                // Check if the regular expression matches
                if (preg_match('/^(\d+)([a-zA-Z]*)$/', $mixstring, $matches)) {
                    $numericPart = str_pad($matches[1], 10, '0', STR_PAD_LEFT);
                    $alphabeticPart = strtolower($matches[2]);

                    return $numericPart . $alphabeticPart;
                } else {
                    // Handle the case where the regular expression doesn't match
                    return $mixstring; // Default behavior is to return the mixstring as is
                }
            }, SORT_NATURAL);


            $lists = Lists::where('act_id', $id)
                ->orWhereIn('chapter_id', $chapter->pluck('chapter_id'))
                ->orWhereIn('parts_id', $part->pluck('part_id'))
                ->orWhereIn('priliminary_id', $priliminarys->pluck('priliminary_id'))
                ->orWhereIn('schedule_id', $schedules->pluck('schedule_id'))
                ->orWhereIn('appendix_id', $Appendix->pluck('appendix_id'))
                ->with('subListModel','footnoteModel', 'Partmodel', 'ChapterModel','PriliminaryModel','Schedulemodel','Appendixmodel')
                ->get()
                ->sortBy(function ($list) {
                $mixstring = $list->list_no;

                // Check if the regular expression matches
                if (preg_match('/^(\d+)([a-zA-Z]*)$/', $mixstring, $matches)) {
                    $numericPart = str_pad($matches[1], 10, '0', STR_PAD_LEFT);
                    $alphabeticPart = strtolower($matches[2]);

                    return $numericPart . $alphabeticPart;
                } else {
                    // Handle the case where the regular expression doesn't match
                    return $mixstring; // Default behavior is to return the mixstring as is
                }
            }, SORT_NATURAL);

            $partsubs = Part::where('act_id', $id)
                ->orWhereIn('chapter_id', $chapter->pluck('chapter_id'))
                ->orWhereIn('parts_id', $part->pluck('part_id'))
                ->orWhereIn('priliminary_id', $priliminarys->pluck('priliminary_id'))
                ->orWhereIn('schedule_id', $schedules->pluck('schedule_id'))
                ->orWhereIn('appendix_id', $Appendix->pluck('appendix_id'))
                ->with('subPartModel','footnoteModel', 'Partmodel', 'ChapterModel','PriliminaryModel','Schedulemodel','Appendixmodel')
                ->get()
                ->sortBy(function ($partsub) {
                $mixstring = $partsub->part_no;

                // Check if the regular expression matches
                if (preg_match('/^(\d+)([a-zA-Z]*)$/', $mixstring, $matches)) {
                    $numericPart = str_pad($matches[1], 10, '0', STR_PAD_LEFT);
                    $alphabeticPart = strtolower($matches[2]);

                    return $numericPart . $alphabeticPart;
                } else {
                    // Handle the case where the regular expression doesn't match
                    return $mixstring; // Default behavior is to return the mixstring as is
                }
            }, SORT_NATURAL);

            $Appendices = Appendices::where('act_id', $id)
                ->orWhereIn('chapter_id', $chapter->pluck('chapter_id'))
                ->orWhereIn('parts_id', $part->pluck('part_id'))
                ->orWhereIn('priliminary_id', $priliminarys->pluck('priliminary_id'))
                ->orWhereIn('schedule_id', $schedules->pluck('schedule_id'))
                ->orWhereIn('appendix_id', $Appendix->pluck('appendix_id'))
                ->with('subAppendicesModel','footnoteModel', 'Partmodel', 'ChapterModel','PriliminaryModel','Schedulemodel','Appendixmodel')
                ->get()
                ->sortBy(function ($Appendix) {
                    $mixstring = $Appendix->appendix_no;

                // Check if the regular expression matches
                if (preg_match('/^(\d+)([a-zA-Z]*)$/', $mixstring, $matches)) {
                    $numericPart = str_pad($matches[1], 10, '0', STR_PAD_LEFT);
                    $alphabeticPart = strtolower($matches[2]);

                    return $numericPart . $alphabeticPart;
                } else {
                    // Handle the case where the regular expression doesn't match
                    return $mixstring; // Default behavior is to return the mixstring as is
                }
            }, SORT_NATURAL);

            $Orders = Orders::where('act_id', $id)
                ->orWhereIn('chapter_id', $chapter->pluck('chapter_id'))
                ->orWhereIn('parts_id', $part->pluck('part_id'))
                ->orWhereIn('priliminary_id', $priliminarys->pluck('priliminary_id'))
                ->orWhereIn('schedule_id', $schedules->pluck('schedule_id'))
                ->orWhereIn('appendix_id', $Appendix->pluck('appendix_id'))
                ->with('subOrderModel','footnoteModel', 'Partmodel', 'ChapterModel','PriliminaryModel','Schedulemodel','Appendixmodel')
                ->get()
                ->sortBy(function ($Order) {
                $mixstring = $Order->order_no;

                // Check if the regular expression matches
                if (preg_match('/^(\d+)([a-zA-Z]*)$/', $mixstring, $matches)) {
                    $numericPart = str_pad($matches[1], 10, '0', STR_PAD_LEFT);
                    $alphabeticPart = strtolower($matches[2]);

                    return $numericPart . $alphabeticPart;
                } else {
                    // Handle the case where the regular expression doesn't match
                    return $mixstring; // Default behavior is to return the mixstring as is
                }
            }, SORT_NATURAL);

            $Annexures = Annexure::where('act_id', $id)
                ->orWhereIn('chapter_id', $chapter->pluck('chapter_id'))
                ->orWhereIn('parts_id', $part->pluck('part_id'))
                ->orWhereIn('priliminary_id', $priliminarys->pluck('priliminary_id'))
                ->orWhereIn('schedule_id', $schedules->pluck('schedule_id'))
                ->orWhereIn('appendix_id', $Appendix->pluck('appendix_id'))
                ->with('subAnnextureModel','footnoteModel', 'Partmodel', 'ChapterModel','PriliminaryModel','Schedulemodel','Appendixmodel')
                ->get()
                ->sortBy(function ($Annexure) {
                $mixstring = $Annexure->annexure_no;

                // Check if the regular expression matches
                if (preg_match('/^(\d+)([a-zA-Z]*)$/', $mixstring, $matches)) {
                    $numericPart = str_pad($matches[1], 10, '0', STR_PAD_LEFT);
                    $alphabeticPart = strtolower($matches[2]);

                    return $numericPart . $alphabeticPart;
                } else {
                    // Handle the case where the regular expression doesn't match
                    return $mixstring; // Default behavior is to return the mixstring as is
                }
            }, SORT_NATURAL);


            $Stschedules = Stschedule::where('act_id', $id)
                ->orWhereIn('chapter_id', $chapter->pluck('chapter_id'))
                ->orWhereIn('parts_id', $part->pluck('part_id'))
                ->orWhereIn('priliminary_id', $priliminarys->pluck('priliminary_id'))
                ->orWhereIn('schedule_id', $schedules->pluck('schedule_id'))
                ->orWhereIn('appendix_id', $Appendix->pluck('appendix_id'))
                ->with('subStscheduleModel','footnoteModel', 'Partmodel', 'ChapterModel','PriliminaryModel','Schedulemodel','Appendixmodel')
                ->get()
                ->sortBy(function ($Stschedule) {
                $mixstring = $Stschedule->stschedule_no;

                // Check if the regular expression matches
                if (preg_match('/^(\d+)([a-zA-Z]*)$/', $mixstring, $matches)) {
                    $numericPart = str_pad($matches[1], 10, '0', STR_PAD_LEFT);
                    $alphabeticPart = strtolower($matches[2]);

                    return $numericPart . $alphabeticPart;
                } else {
                    // Handle the case where the regular expression doesn't match
                    return $mixstring; // Default behavior is to return the mixstring as is
                }
            }, SORT_NATURAL);








            // dd($Stschedules);
            // die();
   



            $chapterList = [];

            foreach ($chapter as $item) {
                $chapterSections = [];

                foreach ($sections as $index => $section) {
                    if ($section->chapter_id == $item->chapter_id) {
                        $subSectionsList = [];

                        foreach ($section->subsectionModel as $subsection) {
                            if ($subsection->chapter_id == $item->chapter_id) {
                                $subSectionsList[] = '<div style="display:flex!important;"><div>' . $subsection->sub_section_no . '</div><div>' . $subsection->sub_section_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($section->footnoteModel as $footnote) {
                            if ($footnote->chapter_id == $item->chapter_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subSectionString = implode('', $subSectionsList);
                        $footnoteString = implode('', $footnoteList);

                        $chapterSections[] = '<div id="' . $section->section_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $section->section_no . '</h4><h4 class="font-weight-bold mb-3">' . $section->section_title . '</h4></div></br><div>' . $section->section_content . '</div><div>' . $subSectionString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($articles as $index => $article) {
                    if ($article->chapter_id == $item->chapter_id) {
                        $subArticleList = [];

                        foreach ($article->subArticleModel as $subarticle) {
                            if ($subarticle->chapter_id == $item->chapter_id) {
                                $subArticleList[] = '<div style="display:flex!important;"><div>' . $subarticle->sub_article_no . '</div><div>' . $subarticle->sub_article_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($article->footnoteModel as $footnote) {
                            if ($footnote->chapter_id == $item->chapter_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subArticleString = implode('', $subArticleList);
                        $footnoteString = implode('', $footnoteList);

                        $chapterSections[] = '<div id="' . $article->article_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $article->article_no . '</h4><h4 class="font-weight-bold mb-3">' . $article->article_title . '</h4></div></br><div>' . $article->article_content . '</div><div>' . $subArticleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }
             
                foreach ($rules as $index => $rule) {
                    if ($rule->chapter_id == $item->chapter_id) {
                        $subRuleList = [];

                        foreach ($rule->subruleModel as $subrule) {
                            if ($subrule->chapter_id == $item->chapter_id) {
                                $subRuleList[] = '<div style="display:flex!important;"><div>' . $subrule->sub_rule_no . '</div><div>' . $subarticle->sub_rule_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($rule->footnoteModel as $footnote) {
                            if ($footnote->chapter_id == $item->chapter_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subRuleString = implode('', $subRuleList);
                        $footnoteString = implode('', $footnoteList);

                        $chapterSections[] = '<div id="' . $rule->rule_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $rule->rule_no . '</h4><h4 class="font-weight-bold mb-3">' . $rule->rule_title . '</h4></div></br><div>' . $rule->rule_content . '</div><div>' . $subRuleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($regulations as $index => $regulation) {
                    if ($regulation->chapter_id == $item->chapter_id) {
                        $subRegulationList = [];

                        foreach ($regulation->subRegulationModel as $subRegulation) {
                            if ($subRegulation->chapter_id == $item->chapter_id) {
                                $subRegulationList[] = '<div style="display:flex!important;"><div>' . $subRegulation->sub_regulation_no . '</div><div>' . $subRegulation->sub_regulation_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($regulation->footnoteModel as $footnote) {
                            if ($footnote->chapter_id == $item->chapter_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subRegulationString = implode('', $subRegulationList);
                        $footnoteString = implode('', $footnoteList);

                        $chapterSections[] = '<div id="' . $regulation->regulation_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $regulation->regulation_no . '</h4><h4 class="font-weight-bold mb-3">' . $regulation->regulation_title . '</h4></div></br><div>' . $regulation->regulation_content . '</div><div>' . $subRegulationString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($lists as $index => $list) {
                    if ($list->chapter_id == $item->chapter_id) {
                        $subListList = [];

                        foreach ($list->subListModel as $subList) {
                            if ($subList->chapter_id == $item->chapter_id) {
                                $subListList[] = '<div style="display:flex!important;"><div>' . $subList->sub_list_no . '</div><div>' . $subList->sub_list_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($list->footnoteModel as $footnote) {
                            if ($footnote->chapter_id == $item->chapter_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subListString = implode('', $subListList);
                        $footnoteString = implode('', $footnoteList);

                        $chapterSections[] = '<div id="' . $list->list_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $list->list_no . '</h4><h4 class="font-weight-bold mb-3">' . $list->list_title . '</h4></div></br><div>' . $list->list_content . '</div><div>' . $subListString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($partsubs as $index => $partsub) {
                    if ($partsub->chapter_id == $item->chapter_id) {
                        $subPartList = [];

                        foreach ($partsub->subPartModel as $subPartsOfPart) {
                            if ($subPartsOfPart->chapter_id == $item->chapter_id) {
                                $subPartList[] = '<div style="display:flex!important;"><div>' . $subPartsOfPart->sub_part_no . '</div><div>' . $subPartsOfPart->sub_part_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($partsub->footnoteModel as $footnote) {
                            if ($footnote->chapter_id == $item->chapter_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subPartString = implode('', $subPartList);
                        $footnoteString = implode('', $footnoteList);

                        $chapterSections[] = '<div id="' . $partsub->part_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $partsub->part_no . '</h4><h4 class="font-weight-bold mb-3">' . $partsub->part_title . '</h4></div></br><div>' . $partsub->part_content . '</div><div>' . $subPartString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($Appendices as $index => $Appendice) {
                    if ($Appendice->chapter_id == $item->chapter_id) {
                        $subAppendiceList = [];

                        foreach ($Appendice->subAppendicesModel as $subAppendice) {
                            if ($subAppendice->chapter_id == $item->chapter_id) {
                                $subAppendiceList[] = '<div style="display:flex!important;"><div>' . $subAppendice->sub_appendices_no . '</div><div>' . $subAppendice->sub_appendices_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($Appendice->footnoteModel as $footnote) {
                            if ($footnote->chapter_id == $item->chapter_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subAppendiceString = implode('', $subAppendiceList);
                        $footnoteString = implode('', $footnoteList);

                        $chapterSections[] = '<div id="' . $Appendice->appendices_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $Appendice->appendices_no . '</h4><h4 class="font-weight-bold mb-3">' . $Appendice->appendices_title . '</h4></div></br><div>' . $Appendice->appendices_content . '</div><div>' . $subAppendiceString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($Orders as $index => $Order) {
                    if ($Order->chapter_id == $item->chapter_id) {
                        $subOrderList = [];

                        foreach ($Order->subOrderModel as $subOrder) {
                            if ($subOrder->chapter_id == $item->chapter_id) {
                                $subOrderList[] = '<div style="display:flex!important;"><div>' . $subOrder->sub_order_no . '</div><div>' . $subOrder->sub_order_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($Order->footnoteModel as $footnote) {
                            if ($footnote->chapter_id == $item->chapter_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subOrderString = implode('', $subOrderList);
                        $footnoteString = implode('', $footnoteList);

                        $chapterSections[] = '<div id="' . $Order->order_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $Order->order_no . '</h4><h4 class="font-weight-bold mb-3">' . $Order->order_title . '</h4></div></br><div>' . $Order->order_content . '</div><div>' . $subOrderString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($Annexures as $index => $Annexure) {
                    if ($Annexure->chapter_id == $item->chapter_id) {
                        $subAnnexureList = [];

                        foreach ($Annexure->subAnnexureModel as $subAnnexure) {
                            if ($subAnnexure->chapter_id == $item->chapter_id) {
                                $subAnnexureList[] = '<div style="display:flex!important;"><div>' . $subAnnexure->sub_annexure_no . '</div><div>' . $subAnnexure->sub_annexure_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($Annexure->footnoteModel as $footnote) {
                            if ($footnote->chapter_id == $item->chapter_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subAnnexureString = implode('', $subAnnexureList);
                        $footnoteString = implode('', $footnoteList);

                        $chapterSections[] = '<div id="' . $Annexure->annexure_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $Annexture->annexure_no . '</h4><h4 class="font-weight-bold mb-3">' . $Annexure->annexure_title . '</h4></div></br><div>' . $Annexure->annexure_content . '</div><div>' . $subAnnexureString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($Stschedules as $index => $Stschedule) {
                    if ($Stschedule->chapter_id == $item->chapter_id) {
                        $subStscheduleList = [];

                        foreach ($Stschedule->subStscheduleModel as $subStschedule) {
                            if ($subStschedule->chapter_id == $item->chapter_id) {
                                $subStscheduleList[] = '<div style="display:flex!important;"><div>' . $subStschedule->sub_stschedule_no . '</div><div>' . $subStschedule->sub_stschedule_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($Stschedule->footnoteModel as $footnote) {
                            if ($footnote->chapter_id == $item->chapter_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subStscheduleString = implode('', $subStscheduleList);
                        $footnoteString = implode('', $footnoteList);

                        $chapterSections[] = '<div id="' . $Stschedule->stschedule_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $Stschedule->stschedule_no . '</h4><h4 class="font-weight-bold mb-3">' . $Stschedule->stschedule_title . '</h4></div></br><div>' . $Stschedule->stschedule_content . '</div><div>' . $subStscheduleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }



                $String = implode('', $chapterSections);

                $chapterList[] = '<h2 style="text-align:center!important;" id=""><strong>' . $item->chapter_title . '</strong></h2><div>' . $item->chapter_content . '</div>' . $String;
            }

        //   dd($chapterList);
        //   die();
            $partList = [];

            foreach ($part as $item) {
                $partSections = [];

                foreach ($sections as $index => $section) {
                    if ($section->parts_id == $item->parts_id) {
                        $subSectionsList = [];

                        foreach ($section->subsectionModel as $subsection) {
                            if ($subsection->parts_id == $item->parts_id) {
                                $subSectionsList[] = '<div style="display:flex!important;"><div>' . $subsection->sub_section_no . '</div><div>' . $subsection->sub_section_content . '</div ></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($section->footnoteModel as $footnote) {
                            if ($footnote->parts_id == $item->parts_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subSectionString = implode('', $subSectionsList);
                        $footnoteString = implode('', $footnoteList);

                        $partSections[] = '<div id="' . $section->section_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $section->section_no . '</h4><h4 class="font-weight-bold mb-3">' . $section->section_title . '</h4></div><div>' . $section->section_content . '</div><div>' . $subSectionString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                
                foreach ($articles as $index => $article) {
                    if ($article->parts_id == $item->parts_id) {
                        $subArticleList = [];

                        foreach ($article->subArticleModel as $subarticle) {
                            if ($subarticle->parts_id == $item->parts_id) {
                                $subArticleList[] = '<div style="display:flex!important;"><div>' . $subarticle->sub_article_no . '</div><div>' . $subarticle->sub_article_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($article->footnoteModel as $footnote) {
                            if ($footnote->parts_id == $item->parts_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subArticleString = implode('', $subArticleList);
                        $footnoteString = implode('', $footnoteList);

                        $partSections[] = '<div id="' . $article->article_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $article->article_no . '</h4><h4 class="font-weight-bold mb-3">' . $article->article_title . '</h4></div></br><div>' . $article->article_content . '</div><div>' . $subArticleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }
             
                foreach ($rules as $index => $rule) {
                    if ($rule->parts_id == $item->parts_id) {
                        $subRuleList = [];

                        foreach ($rule->subruleModel as $subrule) {
                            if ($subrule->parts_id == $item->parts_id) {
                                $subRuleList[] = '<div style="display:flex!important;"><div>' . $subrule->sub_rule_no . '</div><div>' . $subarticle->sub_rule_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($rule->footnoteModel as $footnote) {
                            if ($footnote->parts_id == $item->parts_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subRuleString = implode('', $subRuleList);
                        $footnoteString = implode('', $footnoteList);

                        $partSections[] = '<div id="' . $rule->rule_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $rule->rule_no . '</h4><h4 class="font-weight-bold mb-3">' . $rule->rule_title . '</h4></div></br><div>' . $rule->rule_content . '</div><div>' . $subRuleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($regulations as $index => $regulation) {
                    if ($regulation->parts_id == $item->parts_id) {
                        $subRegulationList = [];

                        foreach ($regulation->subRegulationModel as $subRegulation) {
                            if ($subRegulation->parts_id == $item->parts_id) {
                                $subRegulationList[] = '<div style="display:flex!important;"><div>' . $subRegulation->sub_regulation_no . '</div><div>' . $subRegulation->sub_regulation_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($regulation->footnoteModel as $footnote) {
                            if ($footnote->parts_id == $item->parts_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subRegulationString = implode('', $subRegulationList);
                        $footnoteString = implode('', $footnoteList);

                        $partSections[] = '<div id="' . $regulation->regulation_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $regulation->regulation_no . '</h4><h4 class="font-weight-bold mb-3">' . $regulation->regulation_title . '</h4></div></br><div>' . $regulation->regulation_content . '</div><div>' . $subRegulationString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($lists as $index => $list) {
                    if ($list->parts_id == $item->parts_id) {
                        $subListList = [];

                        foreach ($list->subListModel as $subList) {
                            if ($subList->parts_id == $item->parts_id) {
                                $subListList[] = '<div style="display:flex!important;"><div>' . $subList->sub_list_no . '</div><div>' . $subList->sub_list_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($list->footnoteModel as $footnote) {
                            if ($footnote->parts_id == $item->parts_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subListString = implode('', $subListList);
                        $footnoteString = implode('', $footnoteList);

                        $partSections[] = '<div id="' . $list->list_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $list->list_no . '</h4><h4 class="font-weight-bold mb-3">' . $list->list_title . '</h4></div></br><div>' . $list->list_content . '</div><div>' . $subListString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($partsubs as $index => $partsub) {
                    if ($partsub->parts_id == $item->parts_id) {
                        $subPartList = [];

                        foreach ($partsub->subPartModel as $subPartsOfPart) {
                            if ($subPartsOfPart->parts_id == $item->parts_id) {
                                $subPartList[] = '<div style="display:flex!important;"><div>' . $subPartsOfPart->sub_part_no . '</div><div>' . $subPartsOfPart->sub_part_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($partsub->footnoteModel as $footnote) {
                            if ($footnote->parts_id == $item->parts_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subPartString = implode('', $subPartList);
                        $footnoteString = implode('', $footnoteList);

                        $partSections[] = '<div id="' . $partsub->part_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $partsub->part_no . '</h4><h4 class="font-weight-bold mb-3">' . $partsub->part_title . '</h4></div></br><div>' . $partsub->part_content . '</div><div>' . $subPartString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($Appendices as $index => $Appendice) {
                    if ($Appendice->parts_id == $item->parts_id) {
                        $subAppendiceList = [];

                        foreach ($Appendice->subAppendicesModel as $subAppendice) {
                            if ($subAppendice->parts_id == $item->parts_id) {
                                $subAppendiceList[] = '<div style="display:flex!important;"><div>' . $subAppendice->sub_appendices_no . '</div><div>' . $subAppendice->sub_appendices_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($Appendice->footnoteModel as $footnote) {
                            if ($footnote->parts_id == $item->parts_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subAppendiceString = implode('', $subAppendiceList);
                        $footnoteString = implode('', $footnoteList);

                        $partSections[] = '<div id="' . $Appendice->appendices_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $Appendice->appendices_no . '</h4><h4 class="font-weight-bold mb-3">' . $Appendice->appendices_title . '</h4></div></br><div>' . $Appendice->appendices_content . '</div><div>' . $subAppendiceString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($Orders as $index => $Order) {
                    if ($Order->parts_id == $item->parts_id) {
                        $subOrderList = [];

                        foreach ($Order->subOrderModel as $subOrder) {
                            if ($subOrder->parts_id == $item->parts_id) {
                                $subOrderList[] = '<div style="display:flex!important;"><div>' . $subOrder->sub_order_no . '</div><div>' . $subOrder->sub_order_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($Order->footnoteModel as $footnote) {
                            if ($footnote->parts_id == $item->parts_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subOrderString = implode('', $subOrderList);
                        $footnoteString = implode('', $footnoteList);

                        $partSections[] = '<div id="' . $Order->order_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $Order->order_no . '</h4><h4 class="font-weight-bold mb-3">' . $Order->order_title . '</h4></div></br><div>' . $Order->order_content . '</div><div>' . $subOrderString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($Annexures as $index => $Annexure) {
                    if ($Annexure->parts_id == $item->parts_id) {
                        $subAnnexureList = [];

                        foreach ($Annexure->subAnnexureModel as $subAnnexure) {
                            if ($subAnnexure->parts_id == $item->parts_id) {
                                $subAnnexureList[] = '<div style="display:flex!important;"><div>' . $subAnnexure->sub_annexure_no . '</div><div>' . $subAnnexure->sub_annexure_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($Annexure->footnoteModel as $footnote) {
                            if ($footnote->parts_id == $item->parts_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subAnnexureString = implode('', $subAnnexureList);
                        $footnoteString = implode('', $footnoteList);

                        $partSections[] = '<div id="' . $Annexure->annexure_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $Annexture->annexure_no . '</h4><h4 class="font-weight-bold mb-3">' . $Annexure->annexure_title . '</h4></div></br><div>' . $Annexure->annexure_content . '</div><div>' . $subAnnexureString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }
    
                foreach ($Stschedules as $index => $Stschedule) {
                    if ($Stschedule->parts_id == $item->parts_id) {
                        $subStscheduleList = [];

                        foreach ($Stschedule->subStscheduleModel as $subStschedule) {
                            if ($subStschedule->parts_id == $item->parts_id) {
                                $subStscheduleList[] = '<div style="display:flex!important;"><div>' . $subStschedule->sub_stschedule_no . '</div><div>' . $subStschedule->sub_stschedule_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($Stschedule->footnoteModel as $footnote) {
                            if ($footnote->parts_id == $item->parts_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subStscheduleString = implode('', $subStscheduleList);
                        $footnoteString = implode('', $footnoteList);

                        $partSections[] = '<div id="' . $Stschedule->stschedule_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $Stschedule->stschedule_no . '</h4><h4 class="font-weight-bold mb-3">' . $Stschedule->stschedule_title . '</h4></div></br><div>' . $Stschedule->stschedule_content . '</div><div>' . $subStscheduleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }


                $sectionString = implode('', $partSections);

                $partList[] = '<h2 style="text-align:center!important;" id=""><strong>' . $item->parts_title . '</strong></h2>'. $sectionString;
            }

            // dd($partList);
            // die();

            $priliminaryList = [];

            foreach ($priliminarys as $item) {
                $priliminarySections = [];

                foreach ($sections as $index => $section) {
                    if ($section->priliminary_id == $item->priliminary_id) {
                        $subSectionsList = [];

                        foreach ($section->subsectionModel as $subsection) {
                            if ($subsection->priliminary_id == $item->priliminary_id) {
                                $subSectionsList[] = '<div style="display:flex!important;"><div>' . $subsection->sub_section_no . '</div><div>' . $subsection->sub_section_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];
                        foreach ($section->footnoteModel as $footnote) {
                            if ($footnote->priliminary_id == $item->priliminary_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subSectionString = implode('', $subSectionsList);
                        $footnoteString = implode('', $footnoteList);

                        $priliminarySections[] = '<div id="' . $section->section_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $section->section_no . '</h4><h4 class="font-weight-bold mb-3">' . $section->section_title . '</h4></div></br><div>' . $section->section_content . '</div><div>' . $subSectionString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }
       
                foreach ($articles as $index => $article) {
                    if ($article->priliminary_id == $item->priliminary_id) {
                        $subArticleList = [];

                        foreach ($article->subArticleModel as $subarticle) {
                            if ($subarticle->priliminary_id == $item->priliminary_id) {
                                $subArticleList[] = '<div style="display:flex!important;"><div>' . $subarticle->sub_article_no . '</div><div>' . $subarticle->sub_article_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($article->footnoteModel as $footnote) {
                            if ($footnote->priliminary_id == $item->priliminary_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subArticleString = implode('', $subArticleList);
                        $footnoteString = implode('', $footnoteList);

                        $priliminarySections[] = '<div id="' . $article->article_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $article->article_no . '</h4><h4 class="font-weight-bold mb-3">' . $article->article_title . '</h4></div></br><div>' . $article->article_content . '</div><div>' . $subArticleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }
             
                foreach ($rules as $index => $rule) {
                    if ($rule->priliminary_id == $item->priliminary_id) {
                        $subRuleList = [];

                        foreach ($rule->subruleModel as $subrule) {
                            if ($subrule->priliminary_id == $item->priliminary_id) {
                                $subRuleList[] = '<div style="display:flex!important;"><div>' . $subrule->sub_rule_no . '</div><div>' . $subarticle->sub_rule_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($rule->footnoteModel as $footnote) {
                            if ($footnote->priliminary_id == $item->priliminary_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subRuleString = implode('', $subRuleList);
                        $footnoteString = implode('', $footnoteList);

                        $priliminarySections[] = '<div id="' . $rule->rule_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $rule->rule_no . '</h4><h4 class="font-weight-bold mb-3">' . $rule->rule_title . '</h4></div></br><div>' . $rule->rule_content . '</div><div>' . $subRuleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($regulations as $index => $regulation) {
                    if ($regulation->priliminary_id == $item->priliminary_id) {
                        $subRegulationList = [];

                        foreach ($regulation->subRegulationModel as $subRegulation) {
                            if ($subRegulation->priliminary_id == $item->priliminary_id) {
                                $subRegulationList[] = '<div style="display:flex!important;"><div>' . $subRegulation->sub_regulation_no . '</div><div>' . $subRegulation->sub_regulation_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($regulation->footnoteModel as $footnote) {
                            if ($footnote->priliminary_id == $item->priliminary_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subRegulationString = implode('', $subRegulationList);
                        $footnoteString = implode('', $footnoteList);

                        $priliminarySections[] = '<div id="' . $regulation->regulation_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $regulation->regulation_no . '</h4><h4 class="font-weight-bold mb-3">' . $regulation->regulation_title . '</h4></div></br><div>' . $regulation->regulation_content . '</div><div>' . $subRegulationString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($lists as $index => $list) {
                    if ($list->priliminary_id == $item->priliminary_id) {
                        $subListList = [];

                        foreach ($list->subListModel as $subList) {
                            if ($subList->priliminary_id == $item->priliminary_id) {
                                $subListList[] = '<div style="display:flex!important;"><div>' . $subList->sub_list_no . '</div><div>' . $subList->sub_list_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($list->footnoteModel as $footnote) {
                            if ($footnote->priliminary_id == $item->priliminary_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subListString = implode('', $subListList);
                        $footnoteString = implode('', $footnoteList);

                        $priliminarySections[] = '<div id="' . $list->list_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $list->list_no . '</h4><h4 class="font-weight-bold mb-3">' . $list->list_title . '</h4></div></br><div>' . $list->list_content . '</div><div>' . $subListString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($partsubs as $index => $partsub) {
                    if ($partsub->priliminary_id == $item->priliminary_id) {
                        $subPartList = [];

                        foreach ($partsub->subPartModel as $subPartsOfPart) {
                            if ($subPartsOfPart->priliminary_id == $item->priliminary_id) {
                                $subPartList[] = '<div style="display:flex!important;"><div>' . $subPartsOfPart->sub_part_no . '</div><div>' . $subPartsOfPart->sub_part_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($partsub->footnoteModel as $footnote) {
                            if ($footnote->priliminary_id == $item->priliminary_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subPartString = implode('', $subPartList);
                        $footnoteString = implode('', $footnoteList);

                        $priliminarySections[] = '<div id="' . $partsub->part_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $partsub->part_no . '</h4><h4 class="font-weight-bold mb-3">' . $partsub->part_title . '</h4></div></br><div>' . $partsub->part_content . '</div><div>' . $subPartString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($Appendices as $index => $Appendice) {
                    if ($Appendice->priliminary_id == $item->priliminary_id) {
                        $subAppendiceList = [];

                        foreach ($Appendice->subAppendicesModel as $subAppendice) {
                            if ($subAppendice->priliminary_id == $item->priliminary_id) {
                                $subAppendiceList[] = '<div style="display:flex!important;"><div>' . $subAppendice->sub_appendices_no . '</div><div>' . $subAppendice->sub_appendices_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($Appendice->footnoteModel as $footnote) {
                            if ($footnote->priliminary_id == $item->priliminary_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subAppendiceString = implode('', $subAppendiceList);
                        $footnoteString = implode('', $footnoteList);

                        $priliminarySections[] = '<div id="' . $Appendice->appendices_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $Appendice->appendices_no . '</h4><h4 class="font-weight-bold mb-3">' . $Appendice->appendices_title . '</h4></div></br><div>' . $Appendice->appendices_content . '</div><div>' . $subAppendiceString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($Orders as $index => $Order) {
                    if ($Order->priliminary_id == $item->priliminary_id) {
                        $subOrderList = [];

                        foreach ($Order->subOrderModel as $subOrder) {
                            if ($subOrder->priliminary_id == $item->priliminary_id) {
                                $subOrderList[] = '<div style="display:flex!important;"><div>' . $subOrder->sub_order_no . '</div><div>' . $subOrder->sub_order_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($Order->footnoteModel as $footnote) {
                            if ($footnote->priliminary_id == $item->priliminary_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subOrderString = implode('', $subOrderList);
                        $footnoteString = implode('', $footnoteList);

                        $priliminarySections[] = '<div id="' . $Order->order_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $Order->order_no . '</h4><h4 class="font-weight-bold mb-3">' . $Order->order_title . '</h4></div></br><div>' . $Order->order_content . '</div><div>' . $subOrderString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($Annexures as $index => $Annexure) {
                    if ($Annexure->priliminary_id == $item->priliminary_id) {
                        $subAnnexureList = [];

                        foreach ($Annexure->subAnnexureModel as $subAnnexure) {
                            if ($subAnnexure->priliminary_id == $item->priliminary_id) {
                                $subAnnexureList[] = '<div style="display:flex!important;"><div>' . $subAnnexure->sub_annexure_no . '</div><div>' . $subAnnexure->sub_annexure_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($Annexure->footnoteModel as $footnote) {
                            if ($footnote->priliminary_id == $item->priliminary_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subAnnexureString = implode('', $subAnnexureList);
                        $footnoteString = implode('', $footnoteList);

                        $priliminarySections[] = '<div id="' . $Annexure->annexure_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $Annexture->annexure_no . '</h4><h4 class="font-weight-bold mb-3">' . $Annexure->annexure_title . '</h4></div></br><div>' . $Annexure->annexure_content . '</div><div>' . $subAnnexureString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($Stschedules as $index => $Stschedule) {
                    if ($Stschedule->priliminary_id == $item->priliminary_id) {
                        $subStscheduleList = [];

                        foreach ($Stschedule->subStscheduleModel as $subStschedule) {
                            if ($subStschedule->priliminary_id == $item->priliminary_id) {
                                $subStscheduleList[] = '<div style="display:flex!important;"><div>' . $subStschedule->sub_stschedule_no . '</div><div>' . $subStschedule->sub_stschedule_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($Stschedule->footnoteModel as $footnote) {
                            if ($footnote->priliminary_id == $item->priliminary_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subStscheduleString = implode('', $subStscheduleList);
                        $footnoteString = implode('', $footnoteList);

                        $priliminarySections[] = '<div id="' . $Stschedule->stschedule_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $Stschedule->stschedule_no . '</h4><h4 class="font-weight-bold mb-3">' . $Stschedule->stschedule_title . '</h4></div></br><div>' . $Stschedule->stschedule_content . '</div><div>' . $subStscheduleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                $sectionString = implode('', $priliminarySections);

                $priliminaryList[] = '<h2 style="text-align:center!important;" id=""><strong>' . $item->priliminary_title . '</strong></h2><div>' . $item->chapter_content . '</div>' . $sectionString;
            }


            // dd($priliminaryList);
            // die();
            $ScheduleList = [];

            foreach ($schedules as $item) {
                $scheduleSections = [];

                foreach ($sections as $index => $section) {
                    if ($section->schedule_id == $item->schedule_id) {
                        $subSectionsList = [];

                        foreach ($section->subsectionModel as $subsection) {
                            if ($subsection->schedule_id == $item->schedule_id) {
                                $subSectionsList[] = '<div style="display:flex!important;"><div>' . $subsection->sub_section_no . '</div><div>' . $subsection->sub_section_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];
                        foreach ($section->footnoteModel as $footnote) {
                            if ($footnote->schedule_id == $item->schedule_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subSectionString = implode('', $subSectionsList);
                        $footnoteString = implode('', $footnoteList);

                        $scheduleSections[] = '<div id="' . $section->section_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $section->section_no . '</h4><h4 class="font-weight-bold mb-3">' . $section->section_title . '</h4></div></br><div>' . $section->section_content . '</div><div>' . $subSectionString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }


                foreach ($articles as $index => $article) {
                    if ($article->schedule_id == $item->schedule_id) {
                        $subArticleList = [];

                        foreach ($article->subArticleModel as $subarticle) {
                            if ($subarticle->schedule_id == $item->schedule_id) {
                                $subArticleList[] = '<div style="display:flex!important;"><div>' . $subarticle->sub_article_no . '</div><div>' . $subarticle->sub_article_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($article->footnoteModel as $footnote) {
                            if ($footnote->schedule_id == $item->schedule_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subArticleString = implode('', $subArticleList);
                        $footnoteString = implode('', $footnoteList);

                        $scheduleSections[] = '<div id="' . $article->article_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $article->article_no . '</h4><h4 class="font-weight-bold mb-3">' . $article->article_title . '</h4></div></br><div>' . $article->article_content . '</div><div>' . $subArticleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }
             
                foreach ($rules as $index => $rule) {
                    if ($rule->schedule_id == $item->schedule_id) {
                        $subRuleList = [];

                        foreach ($rule->subruleModel as $subrule) {
                            if ($subrule->schedule_id == $item->schedule_id) {
                                $subRuleList[] = '<div style="display:flex!important;"><div>' . $subrule->sub_rule_no . '</div><div>' . $subarticle->sub_rule_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($rule->footnoteModel as $footnote) {
                            if ($footnote->schedule_id == $item->schedule_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subRuleString = implode('', $subRuleList);
                        $footnoteString = implode('', $footnoteList);

                        $scheduleSections[] = '<div id="' . $rule->rule_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $rule->rule_no . '</h4><h4 class="font-weight-bold mb-3">' . $rule->rule_title . '</h4></div></br><div>' . $rule->rule_content . '</div><div>' . $subRuleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($regulations as $index => $regulation) {
                    if ($regulation->schedule_id == $item->schedule_id) {
                        $subRegulationList = [];

                        foreach ($regulation->subRegulationModel as $subRegulation) {
                            if ($subRegulation->schedule_id == $item->schedule_id) {
                                $subRegulationList[] = '<div style="display:flex!important;"><div>' . $subRegulation->sub_regulation_no . '</div><div>' . $subRegulation->sub_regulation_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($regulation->footnoteModel as $footnote) {
                            if ($footnote->schedule_id == $item->schedule_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subRegulationString = implode('', $subRegulationList);
                        $footnoteString = implode('', $footnoteList);

                        $scheduleSections[] = '<div id="' . $regulation->regulation_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $regulation->regulation_no . '</h4><h4 class="font-weight-bold mb-3">' . $regulation->regulation_title . '</h4></div></br><div>' . $regulation->regulation_content . '</div><div>' . $subRegulationString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($lists as $index => $list) {
                    if ($list->schedule_id == $item->schedule_id) {
                        $subListList = [];

                        foreach ($list->subListModel as $subList) {
                            if ($subList->schedule_id == $item->schedule_id) {
                                $subListList[] = '<div style="display:flex!important;"><div>' . $subList->sub_list_no . '</div><div>' . $subList->sub_list_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($list->footnoteModel as $footnote) {
                            if ($footnote->schedule_id == $item->schedule_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subListString = implode('', $subListList);
                        $footnoteString = implode('', $footnoteList);

                        $scheduleSections[] = '<div id="' . $list->list_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $list->list_no . '</h4><h4 class="font-weight-bold mb-3">' . $list->list_title . '</h4></div></br><div>' . $list->list_content . '</div><div>' . $subListString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($partsubs as $index => $partsub) {
                    if ($partsub->schedule_id == $item->schedule_id) {
                        $subPartList = [];

                        foreach ($partsub->subPartModel as $subPartsOfPart) {
                            if ($subPartsOfPart->schedule_id == $item->schedule_id) {
                                $subPartList[] = '<div style="display:flex!important;"><div>' . $subPartsOfPart->sub_part_no . '</div><div>' . $subPartsOfPart->sub_part_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($partsub->footnoteModel as $footnote) {
                            if ($footnote->schedule_id == $item->schedule_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subPartString = implode('', $subPartList);
                        $footnoteString = implode('', $footnoteList);

                        $scheduleSections[] = '<div id="' . $partsub->part_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $partsub->part_no . '</h4><h4 class="font-weight-bold mb-3">' . $partsub->part_title . '</h4></div></br><div>' . $partsub->part_content . '</div><div>' . $subPartString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($Appendices as $index => $Appendice) {
                    if ($Appendice->schedule_id == $item->schedule_id) {
                        $subAppendiceList = [];

                        foreach ($Appendice->subAppendicesModel as $subAppendice) {
                            if ($subAppendice->schedule_id == $item->schedule_id) {
                                $subAppendiceList[] = '<div style="display:flex!important;"><div>' . $subAppendice->sub_appendices_no . '</div><div>' . $subAppendice->sub_appendices_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($Appendice->footnoteModel as $footnote) {
                            if ($footnote->schedule_id == $item->schedule_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subAppendiceString = implode('', $subAppendiceList);
                        $footnoteString = implode('', $footnoteList);

                        $scheduleSections[] = '<div id="' . $Appendice->appendices_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $Appendice->appendices_no . '</h4><h4 class="font-weight-bold mb-3">' . $Appendice->appendices_title . '</h4></div></br><div>' . $Appendice->appendices_content . '</div><div>' . $subAppendiceString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($Orders as $index => $Order) {
                    if ($Order->schedule_id == $item->schedule_id) {
                        $subOrderList = [];

                        foreach ($Order->subOrderModel as $subOrder) {
                            if ($subOrder->schedule_id == $item->schedule_id) {
                                $subOrderList[] = '<div style="display:flex!important;"><div>' . $subOrder->sub_order_no . '</div><div>' . $subOrder->sub_order_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($Order->footnoteModel as $footnote) {
                            if ($footnote->schedule_id == $item->schedule_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subOrderString = implode('', $subOrderList);
                        $footnoteString = implode('', $footnoteList);

                        $scheduleSections[] = '<div id="' . $Order->order_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $Order->order_no . '</h4><h4 class="font-weight-bold mb-3">' . $Order->order_title . '</h4></div></br><div>' . $Order->order_content . '</div><div>' . $subOrderString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($Annexures as $index => $Annexure) {
                    if ($Annexure->schedule_id == $item->schedule_id) {
                        $subAnnexureList = [];

                        foreach ($Annexure->subAnnexureModel as $subAnnexure) {
                            if ($subAnnexure->schedule_id == $item->schedule_id) {
                                $subAnnexureList[] = '<div style="display:flex!important;"><div>' . $subAnnexure->sub_annexure_no . '</div><div>' . $subAnnexure->sub_annexure_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($Annexure->footnoteModel as $footnote) {
                            if ($footnote->schedule_id == $item->schedule_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subAnnexureString = implode('', $subAnnexureList);
                        $footnoteString = implode('', $footnoteList);

                        $scheduleSections[] = '<div id="' . $Annexure->annexure_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $Annexure->annexture_no . '</h4><h4 class="font-weight-bold mb-3">' . $Annexure->annexure_title . '</h4></div></br><div>' . $Annexure->annexure_content . '</div><div>' . $subAnnexureString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($Stschedules as $index => $Stschedule) {
                    if ($Stschedule->schedule_id == $item->schedule_id) {
                        $subStscheduleList = [];

                        foreach ($Stschedule->subStscheduleModel as $subStschedule) {
                            if ($subStschedule->schedule_id == $item->schedule_id) {
                                $subStscheduleList[] = '<div style="display:flex!important;"><div>' . $subStschedule->sub_stschedule_no . '</div><div>' . $subStschedule->sub_stschedule_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($Stschedule->footnoteModel as $footnote) {
                            if ($footnote->schedule_id == $item->schedule_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subStscheduleString = implode('', $subStscheduleList);
                        $footnoteString = implode('', $footnoteList);

                        $scheduleSections[] = '<div id="' . $Stschedule->stschedule_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $Stschedule->stschedule_no . '</h4><h4 class="font-weight-bold mb-3">' . $Stschedule->stschedule_title . '</h4></div></br><div>' . $Stschedule->stschedule_content . '</div><div>' . $subStscheduleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }


                $sectionString = implode('', $scheduleSections);

                $ScheduleList[] = '<h2 style="text-align:center!important;" id=""><strong>' . $item->schedule_title . '</strong></h2>' . $sectionString;
            }


            $AppendixList = [];

            foreach ($Appendixs as $item) {
                $appendixSections = [];

                foreach ($sections as $index => $section) {
                    if ($section->appendix_id == $item->appendix_id) {
                        $subSectionsList = [];
                        foreach ($section->subsectionModel as $subsection) {
                            if ($subsection->appendix_id == $item->appendix_id) {
                                $subSectionsList[] = '<div style="display:flex!important;"><div>' . $subsection->sub_section_no . '</div><div>' . $subsection->sub_section_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];
                        foreach ($section->footnoteModel as $footnote) {
                            if ($footnote->appendix_id == $item->appendix_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subSectionString = implode('', $subSectionsList);
                        $footnoteString = implode('', $footnoteList);

                        $appendixSections[] = '<div id="' . $section->section_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $section->section_no . '</h4><h4 class="font-weight-bold mb-3">' . $section->section_title . '</h4></div></br><div>' . $section->section_content . '</div><div>' . $subSectionString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($articles as $index => $article) {
                    if ($article->appendix_id == $item->appendix_id) {
                        $subArticleList = [];

                        foreach ($article->subArticleModel as $subarticle) {
                            if ($subarticle->appendix_id == $item->appendix_id) {
                                $subArticleList[] = '<div style="display:flex!important;"><div>' . $subarticle->sub_article_no . '</div><div>' . $subarticle->sub_article_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($article->footnoteModel as $footnote) {
                            if ($footnote->appendix_id == $item->appendix_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subArticleString = implode('', $subArticleList);
                        $footnoteString = implode('', $footnoteList);

                        $appendixSections[] = '<div id="' . $article->article_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $article->article_no . '</h4><h4 class="font-weight-bold mb-3">' . $article->article_title . '</h4></div></br><div>' . $article->article_content . '</div><div>' . $subArticleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }
             
                foreach ($rules as $index => $rule) {
                    if ($rule->appendix_id == $item->appendix_id) {
                        $subRuleList = [];

                        foreach ($rule->subruleModel as $subrule) {
                            if ($subrule->appendix_id == $item->appendix_id) {
                                $subRuleList[] = '<div style="display:flex!important;"><div>' . $subrule->sub_rule_no . '</div><div>' . $subarticle->sub_rule_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($rule->footnoteModel as $footnote) {
                            if ($footnote->appendix_id == $item->appendix_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subRuleString = implode('', $subRuleList);
                        $footnoteString = implode('', $footnoteList);

                        $appendixSections[] = '<div id="' . $rule->rule_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $rule->rule_no . '</h4><h4 class="font-weight-bold mb-3">' . $rule->rule_title . '</h4></div></br><div>' . $rule->rule_content . '</div><div>' . $subRuleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($regulations as $index => $regulation) {
                    if ($regulation->appendix_id == $item->appendix_id) {
                        $subRegulationList = [];

                        foreach ($regulation->subRegulationModel as $subRegulation) {
                            if ($subRegulation->appendix_id == $item->appendix_id) {
                                $subRegulationList[] = '<div style="display:flex!important;"><div>' . $subRegulation->sub_regulation_no . '</div><div>' . $subRegulation->sub_regulation_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($regulation->footnoteModel as $footnote) {
                            if ($footnote->appendix_id == $item->appendix_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subRegulationString = implode('', $subRegulationList);
                        $footnoteString = implode('', $footnoteList);

                        $appendixSections[] = '<div id="' . $regulation->regulation_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $regulation->regulation_no . '</h4><h4 class="font-weight-bold mb-3">' . $regulation->regulation_title . '</h4></div></br><div>' . $regulation->regulation_content . '</div><div>' . $subRegulationString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($lists as $index => $list) {
                    if ($list->appendix_id == $item->appendix_id) {
                        $subListList = [];

                        foreach ($list->subListModel as $subList) {
                            if ($subList->appendix_id == $item->appendix_id) {
                                $subListList[] = '<div style="display:flex!important;"><div>' . $subList->sub_list_no . '</div><div>' . $subList->sub_list_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($list->footnoteModel as $footnote) {
                            if ($footnote->appendix_id == $item->appendix_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subListString = implode('', $subListList);
                        $footnoteString = implode('', $footnoteList);

                        $appendixSections[] = '<div id="' . $list->list_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $list->list_no . '</h4><h4 class="font-weight-bold mb-3">' . $list->list_title . '</h4></div></br><div>' . $list->list_content . '</div><div>' . $subListString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($partsubs as $index => $partsub) {
                    if ($partsub->appendix_id == $item->appendix_id) {
                        $subPartList = [];

                        foreach ($partsub->subPartModel as $subPartsOfPart) {
                            if ($subPartsOfPart->appendix_id == $item->appendix_id) {
                                $subPartList[] = '<div style="display:flex!important;"><div>' . $subPartsOfPart->sub_part_no . '</div><div>' . $subPartsOfPart->sub_part_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($partsub->footnoteModel as $footnote) {
                            if ($footnote->appendix_id == $item->appendix_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subPartString = implode('', $subPartList);
                        $footnoteString = implode('', $footnoteList);

                        $appendixSections[] = '<div id="' . $partsub->part_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $partsub->part_no . '</h4><h4 class="font-weight-bold mb-3">' . $partsub->part_title . '</h4></div></br><div>' . $partsub->part_content . '</div><div>' . $subPartString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($Appendices as $index => $Appendice) {
                    if ($Appendice->appendix_id == $item->appendix_id) {
                        $subAppendiceList = [];

                        foreach ($Appendice->subAppendicesModel as $subAppendice) {
                            if ($subAppendice->appendix_id == $item->appendix_id) {
                                $subAppendiceList[] = '<div style="display:flex!important;"><div>' . $subAppendice->sub_appendices_no . '</div><div>' . $subAppendice->sub_appendices_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($Appendice->footnoteModel as $footnote) {
                            if ($footnote->appendix_id == $item->appendix_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subAppendiceString = implode('', $subAppendiceList);
                        $footnoteString = implode('', $footnoteList);

                        $appendixSections[] = '<div id="' . $Appendice->appendices_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $Appendice->appendices_no . '</h4><h4 class="font-weight-bold mb-3">' . $Appendice->appendices_title . '</h4></div></br><div>' . $Appendice->appendices_content . '</div><div>' . $subAppendiceString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($Orders as $index => $Order) {
                    if ($Order->appendix_id == $item->appendix_id) {
                        $subOrderList = [];

                        foreach ($Order->subOrderModel as $subOrder) {
                            if ($subOrder->appendix_id == $item->appendix_id) {
                                $subOrderList[] = '<div style="display:flex!important;"><div>' . $subOrder->sub_order_no . '</div><div>' . $subOrder->sub_order_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($Order->footnoteModel as $footnote) {
                            if ($footnote->appendix_id == $item->appendix_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subOrderString = implode('', $subOrderList);
                        $footnoteString = implode('', $footnoteList);

                        $appendixSections[] = '<div id="' . $Order->order_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $Order->order_no . '</h4><h4 class="font-weight-bold mb-3">' . $Order->order_title . '</h4></div></br><div>' . $Order->order_content . '</div><div>' . $subOrderString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($Annexures as $index => $Annexure) {
                    if ($Annexure->appendix_id == $item->appendix_id) {
                        $subAnnexureList = [];

                        foreach ($Annexure->subAnnexureModel as $subAnnexure) {
                            if ($subAnnexure->appendix_id == $item->appendix_id) {
                                $subAnnexureList[] = '<div style="display:flex!important;"><div>' . $subAnnexure->sub_annexure_no . '</div><div>' . $subAnnexure->sub_annexure_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($Annexure->footnoteModel as $footnote) {
                            if ($footnote->appendix_id == $item->appendix_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subAnnexureString = implode('', $subAnnexureList);
                        $footnoteString = implode('', $footnoteList);

                        $appendixSections[] = '<div id="' . $Annexure->annexure_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $Annexure->annexture_no . '</h4><h4 class="font-weight-bold mb-3">' . $Annexure->annexure_title . '</h4></div></br><div>' . $Annexure->annexure_content . '</div><div>' . $subAnnexureString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                foreach ($Stschedules as $index => $Stschedule) {
                    if ($Stschedule->appendix_id == $item->appendix_id) {
                        $subStscheduleList = [];

                        foreach ($Stschedule->subStscheduleModel as $subStschedule) {
                            if ($subStschedule->appendix_id == $item->appendix_id) {
                                $subStscheduleList[] = '<div style="display:flex!important;"><div>' . $subStschedule->sub_stschedule_no . '</div><div>' . $subStschedule->sub_stschedule_content . '</div></div>';
                            }
                        }

                        $footnoteList = [];

                        foreach ($Stschedule->footnoteModel as $footnote) {
                            if ($footnote->appendix_id == $item->appendix_id) {
                                $footnoteList[] = '<div>' . $footnote->footnote_content . '</div>';
                            }
                        }

                        $subStscheduleString = implode('', $subStscheduleList);
                        $footnoteString = implode('', $footnoteList);

                        $appendixSections[] = '<div id="' . $Stschedule->stschedule_id . '"><div style="display:flex!important"><h4 class="font-weight-bold mb-3">' . $Stschedule->stschedule_no . '</h4><h4 class="font-weight-bold mb-3">' . $Stschedule->stschedule_title . '</h4></div></br><div>' . $Stschedule->stschedule_content . '</div><div>' . $subStscheduleString . '</div><hr style="width:10%!important;margin: 10px auto !important;">' . $footnoteString . '</div>';
                    }
                }

                $sectionString = implode('', $appendixSections);

                $AppendixList[] = '<h2 style="text-align:center!important;" id=""><strong>' . $item->appendix_title . '</strong></h2>' . $sectionString;
            }

            $sidechapters = Chapter::where('act_id', $id)
            ->with(['Sections' => function ($query) {
                $query->with('subsectionModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('section_rank');
            }])
            ->with(['Articles' => function ($query) {
                $query->with('subArticleModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('article_rank');
            }])
            ->with(['Rules' => function ($query) {
                $query->with('subruleModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('rule_rank');
            }])
            ->with(['Regulation' => function ($query) {
                $query->with('subRegulationModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('regulation_rank');
            }])
            ->with(['Lists' => function ($query) {
                $query->with('subListModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('list_rank');
            }])
            ->with(['Part' => function ($query) {
                $query->with('subPartModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('part_rank');
            }])
            ->with(['Appendices' => function ($query) {
                $query->with('subAppendicesModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('appendices_rank');
            }])
            ->with(['Order' => function ($query) {
                $query->with('subOrderModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('order_rank');
            }])
            ->with(['Annexure' => function ($query) {
                $query->with('subAnnexureModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('annexure_rank');
            }])
            ->with(['Stschedule' => function ($query) {
                $query->with('subStscheduleModel', 'footnoteModel', 'MainTypeModel')
                    ->orderBy('stschedule_rank');
            }])
            ->orderBy('serial_no')
            ->get();


            


            $sideBarList = [];

            foreach ($sidechapters as $chapt) {
                $chapterData = [
                    'ChapterId' => $chapt->chapter_id,
                    'Name' => $chapt->chapter_title,
                    'SubString' => [] // Array to store sections for the current chapter
                ];
            
                foreach ($sections as $section) {
                    if ($chapt->chapter_id == $section->chapter_id) {
                        $chapterData['SubString'][] = [
                            'SectionId' => $section->section_id,
                            'SectionNo' => $section->section_no,
                            'SectionName' => $section->section_title,
                        ];
                    }
                }
            
                $sideBarList[] = $chapterData;
            }

            foreach ($part as $value) {
                $partData = [
                    'ChapterId' => $value->chapter_id,
                    'Name' => $value->chapter_title,
                    'Sections' => [] // Array to store sections for the current chapter
                ];
            
                foreach ($sections as $section) {
                    if ($chapt->chapter_id == $section->chapter_id) {
                        $chapterData['Sections'][] = [
                            'SectionId' => $section->section_id,
                            'SectionNo' => $section->section_no,
                            'SectionName' => $section->section_title,
                        ];
                    }
                }
            
                $sideBarList[] = $chapterData;
            }

            return response()->json([
                'status' => 200,
                'data' => [
                    'actId' => $act->act_id,
                    'actName' => $act->act_title,
                    'actDescription' => '<h1 id=""><strong>' . $act->act_title . '</strong> </h1><div><strong>' . $act->act_no . '</strong></div><div><strong>' . $act->act_date . '</strong></div>' . implode('', $chapterList) . '' . implode('', $partList) . '' . implode('', $priliminaryList) .'' . implode('', $ScheduleList) .'' . implode('', $AppendiceList) . '',
                    'sideBarList' => $sideBarList,
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 400,
                'message' => 'Act not found with the provided ID.',
                'data' => null
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server Error: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }




    public function create($id)
    {
        try {
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new Dompdf($options);

            $type = MainType::all();
            $act = Act::findOrFail($id);
            $act_footnotes = Act::where('act_id', $id)->get();
            $chapter = Chapter::where('act_id', $id)->get();
            $parts = Parts::where('act_id', $id)->get();
            $priliminary = Priliminary::where('act_id', $id)->get();
            $schedule = Schedule::where('act_id', $id)->get();

            $section = Section::where('act_id', $id)
                ->orWhereIn('chapter_id', $chapter->pluck('chapter_id'))
                ->orWhereIn('parts_id', $parts->pluck('parts_id'))
                ->orWhereIn('priliminary_id', $priliminary->pluck('priliminary_id'))
                ->with('subsectionModel', 'footnoteModel')
                ->orderBy('section_rank', 'asc')
                ->get();

            $rule = Rules::where('act_id', $id)
                ->whereIn('schedule_id', $schedule->pluck('schedule_id'))
                ->with('footnoteModel')
                ->orderBy('rule_rank', 'asc')
                ->get();

            $partstype = PartsType::all();
            $regulation = Regulation::where('act_id', $id)->whereIn('chapter_id', $chapter->pluck('chapter_id'))->get();
            $subType = SubType::all();

            $pdf = FacadePdf::loadView('admin.export.pdf', [
                'act' => $act,
                'act_footnotes' => $act_footnotes,
                'type' => $type,
                'chapter' => $chapter,
                'priliminary' => $priliminary,
                'schedule' => $schedule,
                'parts' => $parts,
                'partstype' => $partstype,
                'subType' => $subType,
                'section' => $section,
                'rule' => $rule,
                'regulation' => $regulation,
            ]);

            // Get PDF contents as a string
            $pdfContents = $pdf->output();

            // Set response headers for PDF download
            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $act->act_title . '.pdf"',
            ];

            // Return PDF as response
            return response($pdfContents, 200, $headers);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Resource not found. ' . $e->getMessage(),
                'data' => null,
            ], Response::HTTP_NOT_FOUND);
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
