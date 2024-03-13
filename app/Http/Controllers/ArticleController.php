<?php

namespace App\Http\Controllers;

use App\Models\Appendix;
use App\Models\Article;
use App\Models\Chapter;
use App\Models\Footnote;
use App\Models\Parts;
use App\Models\Priliminary;
use App\Models\Schedule;
use App\Models\SubArticle;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    
    public function edit_article($id)
    {
        $article = Article::with('ChapterModel', 'Partmodel','Appendixmodel','Schedulemodel','PriliminaryModel')->where('article_id', $id)->first();
        $subarticle = Article::where('article_id', $id)
            ->with(['subArticleModel', 'footnoteModel' => function ($query) {
                $query->whereNull('sub_article_id');
            }])
            ->get();

        $sub_article_f = SubArticle::where('article_id', $id)->with('footnoteModel')->get();

        $count = 0;

        if ($sub_article_f) {
            foreach ($sub_article_f as $sub_article) {
                $count += $sub_article->footnoteModel->count();
            }
        }



        return view('admin.article.edit', compact('article', 'subarticle', 'sub_article_f', 'count'));
    }


    public function update(Request $request, $id)
    {
        // dd($request);
        // die();

        // try {
            if ($request->has('chapter_id')) {
                $chapter = Chapter::find($request->chapter_id);
    
                if ($chapter) {
                    $chapter->chapter_title = $request->chapter_title;
                    $chapter->update();
                }
            }
            if ($request->has('priliminary_id')) {
                $priliminary = Priliminary::find($request->priliminary_id);
    
                if ($priliminary) {
                    $priliminary->priliminary_title = $request->priliminary_title;
                    $priliminary->update();
                }
            }
            if ($request->has('parts_id')) {
                $part = Parts::find($request->parts_id);
    
                if ($part) {
                    $part->parts_title = $request->parts_title;
                    $part->update();
                }
            }
            if ($request->has('schedule_id')) {
                $schedule = Schedule::find($request->schedule_id);
    
                if ($schedule) {
                    $schedule->schedule_title = $request->schedule_title;
                    $schedule->update();
                }
            }
            if ($request->has('appendix_id')) {
                $appendix = Appendix::find($request->appendix_id);
    
                if ($appendix) {
                    $appendix->appendix_title = $request->appendix_title;
                    $appendix->update();
                }
            }
    

            // Check if section_id exists in the request
            if (!$request->has('article_id')) {
                return redirect()->route('edit-article', ['id' => $id])->withErrors(['error' => 'Article ID is missing']);
            }

            $article = Article::find($request->article_id);

            // Check if the section is found
            if (!$article) {
                return redirect()->route('edit-article', ['id' => $id])->withErrors(['error' => 'Article not found']);
            }
            if ($article) {

                $article->article_content = $request->article_content ?? null;
                $article->article_title = $request->article_title ?? null;
                $article->article_no = $request->article_no ?? null;
                $article->update();


                if ($request->has('article_footnote_content')) {
                    foreach ($request->article_footnote_content as $key => $items) {
                        // Check if the key exists before using it
                        foreach ($items as $kys => $item) {
                            // Check if the sec_footnote_id exists at the specified index
                            if (isset($request->article_footnote_id[$key][$kys])) {
                                // Use first() instead of get() to get a single model instance
                                $foot = Footnote::find($request->article_footnote_id[$key][$kys]);

                                if ($foot) {
                                    $foot->update([
                                        'footnote_content' => $item ?? null,
                                        'footnote_no' => $request->article_footnote_no[$key][$kys] ?? null,
                                    ]);
                                }
                            } else {
                                // Create a new footnote
                                $footnote = new Footnote();
                                $footnote->article_id = $id ?? null;
                                $footnote->article_no = $article->article_no ?? null;
                                $footnote->act_id = $article->act_id ?? null;
                                $footnote->chapter_id = $article->chapter_id ?? null;
                                $footnote->parts_id = $article->parts_id ?? null;
                                $footnote->priliminary_id = $article->priliminary_id ?? null;
                                $footnote->schedule_id = $article->schedule_id ?? null;
                                $footnote->appendix_id = $article->appendix_id ?? null;
                                $footnote->footnote_content = $item ?? null;
                                $footnote->save();
                            }
                        }
                    }
                }
            }

            // Store Sub-Sections

            if ($request->has('sub_article_no')) {
                foreach ($request->sub_article_no as $key => $item) {
                    // Check if sub_section_id is present in the request
                    if ($request->filled('sub_article_id') && is_array($request->sub_article_id) && array_key_exists($key, $request->sub_article_id)) {

                        $sub_article = SubArticle::find($request->sub_article_id[$key]);

                        // Check if $sub_section is found in the database and the IDs match
                        if ($sub_article && $sub_article->sub_article_id == $request->sub_article_id[$key]) {
                            $sub_article->sub_article_no = $item ?? null;
                            $sub_article->sub_article_content = $request->sub_article_content[$key] ?? null;
                            $sub_article->update();

                            if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                                foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                                    // Check if the sec_footnote_id exists at the specified index
                                    if (isset($request->sub_footnote_id[$key][$kys])) {
                                        // Use first() instead of get() to get a single model instance
                                        $foot = Footnote::find($request->sub_footnote_id[$key][$kys]);

                                        if ($foot) {
                                            $foot->update([
                                                'footnote_content' => $item ?? null,
                                            ]);
                                        }
                                    } else {
                                        // Create a new footnote only if sub_footnote_id does not exist
                                        $footnote = new Footnote();
                                        $footnote->sub_article_id = $sub_article->sub_article_id;
                                        $footnote->article_id = $id ?? null;
                                        $footnote->act_id = $article->act_id ?? null;
                                        $footnote->chapter_id = $article->chapter_id ?? null;
                                        $footnote->parts_id = $article->parts_id ?? null;
                                        $footnote->priliminary_id = $article->priliminary_id ?? null;
                                        $footnote->schedule_id = $article->schedule_id ?? null;
                                        $footnote->appendix_id = $article->appendix_id ?? null;
                                        $footnote->footnote_content = $item ?? null;
                                        $footnote->save();
                                    }
                                }
                            }
                        }
                    } else {
                        // Existing subsection not found, create a new one
                        $subarticle = new SubArticle();
                        $subarticle->article_id = $id ?? null;
                        $subarticle->sub_article_no = $item ?? null;
                        $subarticle->article_no = $article->article_no ?? null;
                        $subarticle->act_id = $article->act_id ?? null;
                        $subarticle->chapter_id = $article->chapter_id ?? null;
                        $subarticle->parts_id = $article->parts_id ?? null;
                        $subarticle->priliminary_id = $article->priliminary_id ?? null;
                        $subarticle->schedule_id = $article->schedule_id ?? null;
                        $subarticle->appendix_id = $article->appendix_id ?? null;
                        $subarticle->sub_article_content = $request->sub_article_content[$key] ?? null;
                        $subarticle->save();

                        if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                            foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                                // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                                if (isset($request->sub_footnote_content[$key][$kys])) {
                                    // Create a new footnote for the newly created subsection
                                    $footnote = new Footnote();
                                    $footnote->sub_article_id = $subarticle->sub_article_id;
                                    $footnote->article_id = $id ?? null;
                                    $footnote->act_id = $article->act_id ?? null;
                                    $footnote->chapter_id = $article->chapter_id ?? null;
                                    $footnote->parts_id = $article->parts_id ?? null;
                                    $footnote->priliminary_id = $article->priliminary_id ?? null;
                                    $footnote->schedule_id = $article->schedule_id ?? null;
                                    $footnote->appendix_id = $article->appendix_id ?? null;
                                    $footnote->footnote_content = $item ?? null;
                                    $footnote->footnote_no = $request->sub_footnote_no[$key][$kys] ?? null;
                                    $footnote->save();
                                }
                            }
                        }
                    }
                }
            }



            return redirect()->route('get_act_section', ['id' => $article->act_id])->with('success', 'Article updated successfully');
        // } catch (\Exception $e) {
        //     \Log::error('Error updating Act: ' . $e->getMessage());
        //     return redirect()->route('edit-article', ['id' => $id])->withErrors(['error' => 'Failed to update Article. Please try again.' . $e->getMessage()]);
        // }
    }

    public function add_below_new_article(Request $request, $id, $article_id)
{
    $article = Article::with('ChapterModel', 'Partmodel', 'PriliminaryModel','Appendixmodel','Schedulemodel')
        ->where('act_id', $id)
        ->where('article_id', $article_id)
        ->first();

    if (!$article) {
        // Handle the scenario where no article is found
        abort(404); // or redirect, or return a message
    }

    return view('admin.article.add_new', compact('article'));
}
    public function add_new_article(Request $request)
    {
        // dd($request);
        // die();
        try {
        if ($request->has('chapter_id')) {
            $chapter = Chapter::find($request->chapter_id);

            if ($chapter) {
                $chapter->chapter_title = $request->chapter_title;
                $chapter->update();
            }
        }
        if ($request->has('priliminary_id')) {
            $priliminary = Priliminary::find($request->priliminary_id);

            if ($priliminary) {
                $priliminary->priliminary_title = $request->priliminary_title;
                $priliminary->update();
            }
        }
        if ($request->has('parts_id')) {
            $part = Parts::find($request->parts_id);

            if ($part) {
                $part->parts_title = $request->parts_title;
                $part->update();
            }
        }
        if ($request->has('schedule_id')) {
            $schedule = Schedule::find($request->schedule_id);

            if ($schedule) {
                $schedule->schedule_title = $request->schedule_title;
                $schedule->update();
            }
        }
        if ($request->has('appendix_id')) {
            $appendix = Appendix::find($request->appendix_id);

            if ($appendix) {
                $appendix->appendix_title = $request->appendix_title;
                $appendix->update();
            }
        }


        $id = $request->act_id;
        // $article_no = $request->article_no;
        $article_rank = $request->article_rank;
        $maintypeId = $request->maintype_id;

        // Calculate the next section number
        // $nextArticleNo = $article_no;
        $oldArticleRank = $request->click_article_rank;
        $nextArticleRank = $oldArticleRank + 0.01;



        // Update the existing sections' section_no in the Section table
        // Section::where('section_no', '>=', $nextSectionNo)
        //     ->increment('section_no');

        // Create the new section with the incremented section_no
        $article = Article::create([
            'article_rank' => $nextArticleRank,
            'article_no' => $request->article_no ?? null, 
            'act_id' => $request->act_id,
            'maintype_id' => $maintypeId,
            'chapter_id' => $request->chapter_id ?? null,
            'priliminary_id' => $request->priliminary_id ?? null,
            'parts_id' => $request->parts_id ?? null,
            'schedule_id' => $request->schedule_id ?? null,
            'appendix_id' => $request->appendix_id ?? null,
            'subtypes_id' => $request->subtypes_id,
            'article_title' => $request->article_title,
            'article_content' => $request->article_content,
            'serial_no' => $request->serial_no,
        ]);

        if ($request->has('article_footnote_content')) {
            foreach ($request->article_footnote_content as $key => $item) {
                // Check if the key exists before using it
                if (isset($request->article_footnote_content[$key])) {
                    // Create a new footnote
                    $footnote = new Footnote();
                    $footnote->article_id = $article->article_id ?? null;
                    $footnote->act_id = $request->act_id ?? null;
                    $footnote->chapter_id = $request->chapter_id ?? null;
                    $footnote->priliminary_id = $request->priliminary_id ?? null;
                    $footnote->parts_id = $request->parts_id ?? null;
                    $footnote->schedule_id = $request->schedule_id ?? null;
                    $footnote->appendix_id = $request->appendix_id ?? null;
                    $footnote->footnote_content = $item ?? null;
                    $footnote->save();
                }
            }
        }

        if ($request->has('sub_article_no')) {
            foreach ($request->sub_article_no as $key => $item) {
                // Existing subsection not found, create a new one
                $sub_article = SubArticle::create([
                    'article_id' => $article->article_id,
                    'sub_article_no' => $item ?? null,
                    'article_no' => $request->article_no ?? null,
                    'act_id' => $request->act_id,
                    'chapter_id' => $maintypeId == "1" ? $request->chapter_id : null,
                    'parts_id' => $maintypeId == "2" ? $request->parts_id : null,
                    'priliminary_id' => $maintypeId == "3" ? $request->priliminary_id : null,
                    'schedule_id' => $maintypeId == "4" ? $request->schedule_id : null,
                    'appendix_id' => $maintypeId == "5" ? $request->appendix_id : null,
                    'sub_article_content' => $request->sub_article_content[$key] ?? null,
                ]);

                if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                    foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                        // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                        if (isset($request->sub_footnote_content[$key][$kys])) {
                            // Create a new footnote for the newly created subsection
                            $footnote = new Footnote();
                            $footnote->sub_article_id = $sub_article->sub_article_id;
                            $footnote->article_id = $article->article_id ?? null;
                            $footnote->act_id = $request->act_id ?? null;
                            $footnote->chapter_id = $request->chapter_id ?? null;
                            $footnote->parts_id = $request->parts_id ?? null;
                            $footnote->priliminary_id = $request->priliminary_id ?? null;
                            $footnote->schedule_id = $request->schedule_id ?? null;
                            $footnote->appendix_id = $request->appendix_id ?? null;
                            $footnote->footnote_content = $item ?? null;
                            $footnote->save();
                        }
                    }
                }
            }
        }

        return redirect()->route('get_act_section', ['id' =>$article->act_id])->with('success', 'Ayrticle created successfully');
        } catch (\Exception $e) {
            \Log::error('Error creating Act: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
        }
    }
   

    public function view_sub_article(Request $request,  $id)
    {
        $article = Article::where('article_id', $id)->first();
        $sub_article = SubArticle::where('article_id', $id)->with('footnoteModel')->get();
        return view('admin.article.view', compact('article','sub_article'));
    }

    public function destroy_sub_article(string $id)
    {
        try {
            $subarticle = SubArticle::find($id);

            if (!$subarticle) {
                return redirect()->back()->withErrors(['error' => 'Sub-Article not found.']);
            }
            
            Footnote::where('sub_article_id', $id)->delete();

            $subarticle->delete();

            return redirect()->back()->with('success', 'Sub-Article and related records deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting Sub-Article: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete Sub-Article. Please try again.' . $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            $article = Article::find($id);

            if (!$article) {
                return redirect()->back()->withErrors(['error' => 'Article not found.']);
            }
            
            SubArticle::where('article_id', $id)->delete();
            Footnote::where('article_id', $id)->delete();

            $article->delete();

            return redirect()->back()->with('success', 'Article and related records deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting article: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete article. Please try again.' . $e->getMessage()]);
        }
    }
    public function delete_footnote(string $id)
    {
        try {
            $footnote = Footnote::find($id);

            if (!$footnote) {
                return redirect()->back()->withErrors(['error' => 'Footnote not found.']);
            }
            

            $footnote->delete();

            return redirect()->back()->with('success', 'Footnote deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting footnote: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete footnote. Please try again.' . $e->getMessage()]);
        }
    }

}
