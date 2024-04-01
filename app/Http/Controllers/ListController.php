<?php

namespace App\Http\Controllers;

use App\Models\Appendix;
use App\Models\Chapter;
use App\Models\Footnote;
use App\Models\Lists;
use App\Models\MainOrder;
use App\Models\Parts;
use App\Models\Priliminary;
use App\Models\Schedule;
use App\Models\SubLists;
use Illuminate\Http\Request;

class ListController extends Controller
{
    public function edit_list($id,Request $request)
    {
       $list = Lists::with('ChapterModel', 'Partmodel','Appendixmodel','Schedulemodel','PriliminaryModel','MainOrderModel')->where('list_id', $id)->first();
       $sublist = Lists::where('list_id', $id)
           ->with(['subListModel', 'footnoteModel' => function ($query) {
               $query->whereNull('sub_list_id');
           }])
           ->get();

       $sub_list_f = SubLists::where('list_id', $id)->with('footnoteModel')->get();

       $count = 0;

       if ($sub_list_f) {
           foreach ($sub_list_f as $sub_list) {
               $count += $sub_list->footnoteModel->count();
           }
       }


       $currentPage = $request->page;
       return view('admin.list.edit', compact('list', 'sublist', 'sub_list_f', 'count','currentPage'));
   }

   public function update(Request $request, $id)
   {
       // dd($request);
       // die();

       // try {
           $currentPage = $request->currentPage;
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
           if ($request->has('main_order_id')) {
            $main_order = MainOrder::find($request->main_order_id);

            if ($main_order) {
                $main_order->main_order_title = $request->main_order_title;
                $main_order->update();
            }
        }
   

           // Check if section_id exists in the request
           if (!$request->has('list_id')) {
               return redirect()->route('edit-list', ['id' => $id])->withErrors(['error' => 'List ID is missing']);
           }

           $list = Lists::find($request->list_id);

           // Check if the section is found
           if (!$list) {
               return redirect()->route('edit-list', ['id' => $id])->withErrors(['error' => 'List not found']);
           }
           if ($list) {

               $list->list_content = $request->list_content ?? null;
               $list->list_title = $request->list_title ?? null;
               $list->list_no = $request->list_no ?? null;
               $list->update();


               if ($request->has('list_footnote_content')) {
                   foreach ($request->list_footnote_content as $key => $items) {
                       // Check if the key exists before using it
                       foreach ($items as $kys => $item) {
                           // Check if the sec_footnote_id exists at the specified index
                           if (isset($request->list_footnote_id[$key][$kys])) {
                               // Use first() instead of get() to get a single model instance
                               $foot = Footnote::find($request->list_footnote_id[$key][$kys]);

                               if ($foot) {
                                   $foot->update([
                                       'footnote_content' => $item ?? null,
                                       'footnote_no' => $request->list_footnote_no[$key][$kys] ?? null,
                                   ]);
                               }
                           } else {
                               // Create a new footnote
                               $footnote = new Footnote();
                               $footnote->list_id = $id ?? null;
                               $footnote->list_no = $list->list_no ?? null;
                               $footnote->act_id = $list->act_id ?? null;
                               $footnote->chapter_id = $list->chapter_id ?? null;
                               $footnote->main_order_id = $list->main_order_id ?? null;
                               $footnote->parts_id = $list->parts_id ?? null;
                               $footnote->priliminary_id = $list->priliminary_id ?? null;
                               $footnote->schedule_id = $list->schedule_id ?? null;
                               $footnote->appendix_id = $list->appendix_id ?? null;
                               $footnote->footnote_content = $item ?? null;
                               $footnote->save();
                           }
                       }
                   }
               }
           }

           // Store Sub-Sections

           if ($request->has('sub_list_no')) {
               foreach ($request->sub_list_no as $key => $item) {
                   // Check if sub_section_id is present in the request
                   if ($request->filled('sub_list_id') && is_array($request->sub_list_id) && array_key_exists($key, $request->sub_list_id)) {

                       $sub_list = SubLists::find($request->sub_list_id[$key]);

                       // Check if $sub_section is found in the database and the IDs match
                       if ($sub_list && $sub_list->sub_list_id == $request->sub_list_id[$key]) {
                           $sub_list->sub_list_no = $item ?? null;
                           $sub_list->sub_list_content = $request->sub_list_content[$key] ?? null;
                           $sub_list->update();

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
                                       $footnote->sub_list_id = $sub_list->sub_list_id;
                                       $footnote->list_id = $id ?? null;
                                       $footnote->act_id = $list->act_id ?? null;
                                       $footnote->chapter_id = $list->chapter_id ?? null;
                                       $footnote->main_order_id = $list->main_order_id ?? null;
                                       $footnote->parts_id = $list->parts_id ?? null;
                                       $footnote->priliminary_id = $list->priliminary_id ?? null;
                                       $footnote->schedule_id = $list->schedule_id ?? null;
                                       $footnote->appendix_id = $list->appendix_id ?? null;
                                       $footnote->footnote_content = $item ?? null;
                                       $footnote->save();
                                   }
                               }
                           }
                       }
                   } else {
                       // Existing subsection not found, create a new one
                       $sublist = new SubLists();
                       $sublist->list_id = $id ?? null;
                       $sublist->sub_list_no = $item ?? null;
                       $sublist->list_no = $list->list_no ?? null;
                       $sublist->act_id = $list->act_id ?? null;
                       $sublist->chapter_id = $list->chapter_id ?? null;
                       $sublist->main_order_id = $list->main_order_id ?? null;
                       $sublist->parts_id = $list->parts_id ?? null;
                       $sublist->priliminary_id = $list->priliminary_id ?? null;
                       $sublist->schedule_id = $list->schedule_id ?? null;
                       $sublist->appendix_id = $list->appendix_id ?? null;
                       $sublist->sub_list_content = $request->sub_list_content[$key] ?? null;
                       $sublist->save();

                       if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                           foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                               // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                               if (isset($request->sub_footnote_content[$key][$kys])) {
                                   // Create a new footnote for the newly created subsection
                                   $footnote = new Footnote();
                                   $footnote->sub_list_id = $sublist->sub_list_id;
                                   $footnote->list_id = $id ?? null;
                                   $footnote->act_id = $list->act_id ?? null;
                                   $footnote->chapter_id = $list->chapter_id ?? null;
                                   $footnote->main_order_id = $list->main_order_id ?? null;
                                   $footnote->parts_id = $list->parts_id ?? null;
                                   $footnote->priliminary_id = $list->priliminary_id ?? null;
                                   $footnote->schedule_id = $list->schedule_id ?? null;
                                   $footnote->appendix_id = $list->appendix_id ?? null;
                                   $footnote->footnote_content = $item ?? null;
                                   $footnote->footnote_no = $request->sub_footnote_no[$key][$kys] ?? null;
                                   $footnote->save();
                               }
                           }
                       }
                   }
               }
           }



           return redirect()->route('get_act_section', ['id' => $list->act_id,'page' => $currentPage])->with('success', 'List updated successfully');
       // } catch (\Exception $e) {
       //     \Log::error('Error updating Act: ' . $e->getMessage());
       //     return redirect()->route('edit-list', ['id' => $id])->withErrors(['error' => 'Failed to update List. Please try again.' . $e->getMessage()]);
       // }
   }

   public function add_below_new_list(Request $request, $id, $list_id)
   {
       
    //    $list_rank = $list_rank;
       $list = Lists::with('ChapterModel', 'Partmodel', 'PriliminaryModel','Appendixmodel','Schedulemodel','MainOrderModel')->where('act_id', $id)
           ->where('list_id', $list_id)->first();
        $currentPage = $request->page;
       return view('admin.list.add_new', compact('list','currentPage'));
   }

   public function add_new_list(Request $request)
   {
       // dd($request);
       // die();
       try {
        $currentPage = $request->currentPage;
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
       if ($request->has('main_order_id')) {
        $main_order = MainOrder::find($request->main_order_id);

        if ($main_order) {
            $main_order->main_order_title = $request->main_order_title;
            $main_order->update();
        }
       }


       $id = $request->act_id;
    //    $list_no = $request->list_no;
       $list_rank = $request->list_rank;
       $maintypeId = $request->maintype_id;

       // Calculate the next section number
    //    $nextListNo = $list_no;
       $oldListRank = $request->click_list_rank;
       $nextListRank = $oldListRank + 0.01;



       // Update the existing sections' section_no in the Section table
       // Section::where('section_no', '>=', $nextSectionNo)
       //     ->increment('section_no');

       // Create the new section with the incremented section_no
       $list = Lists::create([
           'list_rank' => $nextListRank,
           'list_no' => $request->list_no ?? null,
           'act_id' => $request->act_id,
           'maintype_id' => $maintypeId,
           'chapter_id' => $request->chapter_id ?? null,
           'main_order_id' => $request->main_order_id ?? null,
           'priliminary_id' => $request->priliminary_id ?? null,
           'parts_id' => $request->parts_id ?? null,
           'schedule_id' => $request->schedule_id ?? null,
           'appendix_id' => $request->appendix_id ?? null,
           'subtypes_id' => $request->subtypes_id,
           'list_title' => $request->list_title,
           'list_content' => $request->list_content,
           'is_append' => 1,
           'serial_no' => $request->serial_no
       ]);

       if ($request->has('list_footnote_content')) {
           foreach ($request->list_footnote_content as $key => $item) {
               // Check if the key exists before using it
               if (isset($request->list_footnote_content[$key])) {
                   // Create a new footnote
                   $footnote = new Footnote();
                   $footnote->list_id = $list->list_id ?? null;
                   $footnote->act_id = $request->act_id ?? null;
                   $footnote->chapter_id = $request->chapter_id ?? null;
                   $footnote->main_order_id = $request->main_order_id ?? null;
                   $footnote->priliminary_id = $request->priliminary_id ?? null;
                   $footnote->parts_id = $request->parts_id ?? null;
                   $footnote->schedule_id = $request->schedule_id ?? null;
                   $footnote->appendix_id = $request->appendix_id ?? null;
                   $footnote->footnote_content = $item ?? null;
                   $footnote->save();
               }
           }
       }

       if ($request->has('sub_list_no')) {
           foreach ($request->sub_list_no as $key => $item) {
               // Existing subsection not found, create a new one
               $sub_list = SubLists::create([
                   'list_id' => $list->list_id,
                   'sub_list_no' => $item ?? null,
                   'list_no' => $request->list_no ?? null,
                   'act_id' => $request->act_id,
                   'chapter_id' => $maintypeId == "1" ? $request->chapter_id : null,
                   'main_order_id' => $maintypeId == "6" ? $request->main_order_id : null,
                   'parts_id' => $maintypeId == "2" ? $request->parts_id : null,
                   'priliminary_id' => $maintypeId == "3" ? $request->priliminary_id : null,
                   'schedule_id' => $maintypeId == "4" ? $request->schedule_id : null,
                   'appendix_id' => $maintypeId == "5" ? $request->appendix_id : null,
                   'sub_list_content' => $request->sub_list_content[$key] ?? null,
               ]);

               if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                   foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                       // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                       if (isset($request->sub_footnote_content[$key][$kys])) {
                           // Create a new footnote for the newly created subsection
                           $footnote = new Footnote();
                           $footnote->sub_list_id = $sub_list->sub_list_id;
                           $footnote->list_id = $list->list_id ?? null;
                           $footnote->act_id = $request->act_id ?? null;
                           $footnote->chapter_id = $request->chapter_id ?? null;
                           $footnote->main_order_id = $request->main_order_id ?? null;
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

       return redirect()->route('get_act_section', ['id' => $id,'page' => $currentPage])->with('success', 'Ayrticle created successfully');
       } catch (\Exception $e) {
           \Log::error('Error creating Act: ' . $e->getMessage());

           return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
       }
   }

   public function view_sub_list(Request $request,  $id)
   {
       $list = Lists::where('list_id', $id)->first();
       $sub_list = SubLists::where('list_id', $id)->with('footnoteModel')->get();
       $currentPage = $request->page;
       return view('admin.list.view', compact('list','sub_list','currentPage'));
   }


   public function destroy_sub_list(string $id)
   {
       try {
           $sublist = SubLists::find($id);

           if (!$sublist) {
               return redirect()->back()->withErrors(['error' => 'Sub-List not found.']);
           }
           
           Footnote::where('sub_list_id', $id)->delete();

           $sublist->delete();

           return redirect()->back()->with('success', 'Sub-List and related records deleted successfully.');
       } catch (\Exception $e) {
           \Log::error('Error deleting Sub-List: ' . $e->getMessage());

           return redirect()->back()->withErrors(['error' => 'Failed to delete Sub-List. Please try again.' . $e->getMessage()]);
       }
   }

   public function destroy(string $id)
   {
       try {
           $list = Lists::find($id);

           if (!$list) {
               return redirect()->back()->withErrors(['error' => 'List not found.']);
           }
           
           SubLists::where('List_id', $id)->delete();
           Footnote::where('list_id', $id)->delete();

           $list->delete();

           return redirect()->back()->with('success', 'List and related records deleted successfully.');
       } catch (\Exception $e) {
           \Log::error('Error deleting list: ' . $e->getMessage());

           return redirect()->back()->withErrors(['error' => 'Failed to delete list. Please try again.' . $e->getMessage()]);
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
