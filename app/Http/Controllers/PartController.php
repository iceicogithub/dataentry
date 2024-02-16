<?php

namespace App\Http\Controllers;

use App\Models\Footnote;
use App\Models\Part;
use App\Models\SubPart;
use Illuminate\Http\Request;

class PartController extends Controller
{public function edit_part($id)
    {
       $part = Part::with('ChapterModel', 'Partmodel','Appendicesmodel','Schedulemodel','PriliminaryModel')->where('part_id', $id)->first();
       $subpart = Part::where('part_id', $id)
           ->with(['subPartModel', 'footnoteModel' => function ($query) {
               $query->whereNull('sub_part_id');
           }])
           ->get();

       $sub_part_f = SubPart::where('part_id', $id)->with('footnoteModel')->get();

       $count = 0;

       if ($sub_part_f) {
           foreach ($sub_part_f as $sub_part) {
               $count += $sub_part->footnoteModel->count();
           }
       }



       return view('admin.part.edit', compact('part', 'subpart', 'sub_part_f', 'count'));
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
           if ($request->has('appendices_id')) {
               $appendices = Appendices::find($request->appendices_id);
   
               if ($appendices) {
                   $appendices->appendices_title = $request->appendices_title;
                   $appendices->update();
               }
           }
   

           // Check if section_id exists in the request
           if (!$request->has('part_id')) {
               return redirect()->route('edit-part', ['id' => $id])->withErrors(['error' => 'Part ID is missing']);
           }

           $part = Part::find($request->part_id);

           // Check if the section is found
           if (!$part) {
               return redirect()->route('edit-part', ['id' => $id])->withErrors(['error' => 'Part not found']);
           }
           if ($part) {

               $part->part_content = $request->part_content ?? null;
               $part->part_title = $request->part_title ?? null;
               $part->part_no = $request->part_no ?? null;
               $part->update();


               if ($request->has('part_footnote_content')) {
                   foreach ($request->part_footnote_content as $key => $items) {
                       // Check if the key exists before using it
                       foreach ($items as $kys => $item) {
                           // Check if the sec_footnote_id exists at the specified index
                           if (isset($request->part_footnote_id[$key][$kys])) {
                               // Use first() instead of get() to get a single model instance
                               $foot = Footnote::find($request->part_footnote_id[$key][$kys]);

                               if ($foot) {
                                   $foot->update([
                                       'footnote_content' => $item ?? null,
                                       'footnote_no' => $request->part_footnote_no[$key][$kys] ?? null,
                                   ]);
                               }
                           } else {
                               // Create a new footnote
                               $footnote = new Footnote();
                               $footnote->part_id = $id ?? null;
                               $footnote->part_no = $part->part_no ?? null;
                               $footnote->act_id = $part->act_id ?? null;
                               $footnote->chapter_id = $part->chapter_id ?? null;
                               $footnote->parts_id = $part->parts_id ?? null;
                               $footnote->priliminary_id = $part->priliminary_id ?? null;
                               $footnote->schedule_id = $part->schedule_id ?? null;
                               $footnote->appendices_id = $part->appendices_id ?? null;
                               $footnote->footnote_content = $item ?? null;
                               $footnote->save();
                           }
                       }
                   }
               }
           }

           // Store Sub-Sections

           if ($request->has('sub_part_no')) {
               foreach ($request->sub_part_no as $key => $item) {
                   // Check if sub_section_id is present in the request
                   if ($request->filled('sub_part_id') && is_array($request->sub_part_id) && array_key_exists($key, $request->sub_part_id)) {

                       $sub_part = SubPart::find($request->sub_part_id[$key]);

                       // Check if $sub_section is found in the database and the IDs match
                       if ($sub_part && $sub_part->sub_part_id == $request->sub_part_id[$key]) {
                           $sub_part->sub_part_no = $item ?? null;
                           $sub_part->sub_part_content = $request->sub_part_content[$key] ?? null;
                           $sub_part->update();

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
                                       $footnote->sub_part_id = $sub_part->sub_part_id;
                                       $footnote->part_id = $id ?? null;
                                       $footnote->act_id = $part->act_id ?? null;
                                       $footnote->chapter_id = $part->chapter_id ?? null;
                                       $footnote->parts_id = $part->parts_id ?? null;
                                       $footnote->priliminary_id = $part->priliminary_id ?? null;
                                       $footnote->schedule_id = $part->schedule_id ?? null;
                                       $footnote->appendices_id = $part->appendices_id ?? null;
                                       $footnote->footnote_content = $item ?? null;
                                       $footnote->save();
                                   }
                               }
                           }
                       }
                   } else {
                       // Existing subsection not found, create a new one
                       $subpart = new SubPart();
                       $subpart->part_id = $id ?? null;
                       $subpart->sub_part_no = $item ?? null;
                       $subpart->part_no = $part->part_no ?? null;
                       $subpart->act_id = $part->act_id ?? null;
                       $subpart->chapter_id = $part->chapter_id ?? null;
                       $subpart->parts_id = $part->parts_id ?? null;
                       $subpart->priliminary_id = $part->priliminary_id ?? null;
                       $subpart->schedule_id = $part->schedule_id ?? null;
                       $subpart->appendices_id = $part->appendices_id ?? null;
                       $subpart->sub_part_content = $request->sub_part_content[$key] ?? null;
                       $subpart->save();

                       if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                           foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                               // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                               if (isset($request->sub_footnote_content[$key][$kys])) {
                                   // Create a new footnote for the newly created subsection
                                   $footnote = new Footnote();
                                   $footnote->sub_part_id = $subpart->sub_part_id;
                                   $footnote->part_id = $id ?? null;
                                   $footnote->act_id = $part->act_id ?? null;
                                   $footnote->chapter_id = $part->chapter_id ?? null;
                                   $footnote->parts_id = $part->parts_id ?? null;
                                   $footnote->priliminary_id = $part->priliminary_id ?? null;
                                   $footnote->schedule_id = $part->schedule_id ?? null;
                                   $footnote->appendices_id = $part->appendices_id ?? null;
                                   $footnote->footnote_content = $item ?? null;
                                   $footnote->footnote_no = $request->sub_footnote_no[$key][$kys] ?? null;
                                   $footnote->save();
                               }
                           }
                       }
                   }
               }
           }



           return redirect()->route('get_act_section', ['id' => $part->act_id])->with('success', 'Part updated successfully');
       // } catch (\Exception $e) {
       //     \Log::error('Error updating Act: ' . $e->getMessage());
       //     return redirect()->route('edit-part', ['id' => $id])->withErrors(['error' => 'Failed to update Part. Please try again.' . $e->getMessage()]);
       // }
   }

   public function add_below_new_part(Request $request, $id, $part_id, $part_rank)
   {
       
       $part_rank = $part_rank;
       $part = Part::with('ChapterModel', 'Partmodel', 'PriliminaryModel','Appendicesmodel','Schedulemodel')->where('act_id', $id)
           ->where('part_id', $part_id)->first();

       return view('admin.part.add_new', compact('part', 'part_rank'));
   }

   public function add_new_part(Request $request)
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
       if ($request->has('appendices_id')) {
           $appendices = Appendices::find($request->appendices_id);

           if ($appendices) {
               $appendices->appendices_title = $request->appendices_title;
               $appendices->update();
           }
       }


       $id = $request->act_id;
       $part_no = $request->part_no;
       $part_rank = $request->part_rank;
       $maintypeId = $request->maintype_id;

       // Calculate the next section number
       $nextPartNo = $part_no;
       $nextPartRank = $part_rank + 0.01;



       // Update the existing sections' section_no in the Section table
       // Section::where('section_no', '>=', $nextSectionNo)
       //     ->increment('section_no');

       // Create the new section with the incremented section_no
       $part = Part::create([
           'part_rank' => $nextPartRank ?? 1,
           'part_no' => $nextPartNo,
           'act_id' => $request->act_id,
           'maintype_id' => $maintypeId,
           'chapter_id' => $request->chapter_id ?? null,
           'priliminary_id' => $request->priliminary_id ?? null,
           'parts_id' => $request->parts_id ?? null,
           'schedule_id' => $request->schedule_id ?? null,
           'appendices_id' => $request->appendices_id ?? null,
           'subtypes_id' => $request->subtypes_id,
           'part_title' => $request->part_title,
           'part_content' => $request->part_content,
       ]);

       if ($request->has('part_footnote_content')) {
           foreach ($request->part_footnote_content as $key => $item) {
               // Check if the key exists before using it
               if (isset($request->part_footnote_content[$key])) {
                   // Create a new footnote
                   $footnote = new Footnote();
                   $footnote->part_id = $part->part_id ?? null;
                   $footnote->act_id = $request->act_id ?? null;
                   $footnote->chapter_id = $request->chapter_id ?? null;
                   $footnote->priliminary_id = $request->priliminary_id ?? null;
                   $footnote->parts_id = $request->parts_id ?? null;
                   $footnote->schedule_id = $request->schedule_id ?? null;
                   $footnote->appendices_id = $request->appendices_id ?? null;
                   $footnote->footnote_content = $item ?? null;
                   $footnote->save();
               }
           }
       }

       if ($request->has('sub_part_no')) {
           foreach ($request->sub_part_no as $key => $item) {
               // Existing subsection not found, create a new one
               $sub_part = SubPart::create([
                   'part_id' => $part->part_id,
                   'sub_part_no' => $item ?? null,
                   'part_no' => $nextPartNo,
                   'act_id' => $request->act_id,
                   'chapter_id' => $maintypeId == "1" ? $request->chapter_id : null,
                   'parts_id' => $maintypeId == "2" ? $request->parts_id : null,
                   'priliminary_id' => $maintypeId == "3" ? $request->priliminary_id : null,
                   'schedule_id' => $maintypeId == "4" ? $request->schedule_id : null,
                   'appendices_id' => $maintypeId == "5" ? $request->appendices_id : null,
                   'sub_part_content' => $request->sub_part_content[$key] ?? null,
               ]);

               if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                   foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                       // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                       if (isset($request->sub_footnote_content[$key][$kys])) {
                           // Create a new footnote for the newly created subsection
                           $footnote = new Footnote();
                           $footnote->sub_part_id = $sub_part->sub_part_id;
                           $footnote->part_id = $part->part_id ?? null;
                           $footnote->act_id = $request->act_id ?? null;
                           $footnote->chapter_id = $request->chapter_id ?? null;
                           $footnote->parts_id = $request->parts_id ?? null;
                           $footnote->priliminary_id = $request->priliminary_id ?? null;
                           $footnote->schedule_id = $request->schedule_id ?? null;
                           $footnote->appendices_id = $request->appendices_id ?? null;
                           $footnote->footnote_content = $item ?? null;
                           $footnote->save();
                       }
                   }
               }
           }
       }

       return redirect()->route('get_act_section', ['id' => $id])->with('success', 'Ayrticle created successfully');
       // } catch (\Exception $e) {
       //     \Log::error('Error creating Act: ' . $e->getMessage());

       //     return redirect()->back()->withErrors(['error' => 'Failed to create Act. Please try again.' . $e->getMessage()]);
       // }
   }

   public function view_sub_part(Request $request,  $id)
   {
       $part = Part::where('part_id', $id)->first();
       $sub_part = SubPart::where('part_id', $id)->with('footnoteModel')->get();
       return view('admin.part.view', compact('part','sub_part'));
   }


   public function destroy_sub_part(string $id)
   {
       try {
           $subpart = SubPart::find($id);

           if (!$subpart) {
               return redirect()->back()->withErrors(['error' => 'Sub-Part not found.']);
           }
           
           Footnote::where('sub_part_id', $id)->delete();

           $subpart->delete();

           return redirect()->back()->with('success', 'Sub-Part and related records deleted successfully.');
       } catch (\Exception $e) {
           \Log::error('Error deleting Sub-Part: ' . $e->getMessage());

           return redirect()->back()->withErrors(['error' => 'Failed to delete Sub-Part. Please try again.' . $e->getMessage()]);
       }
   }

   public function destroy(string $id)
   {
       try {
           $part = Part::find($id);

           if (!$part) {
               return redirect()->back()->withErrors(['error' => 'Part not found.']);
           }
           
           SubPart::where('Part_id', $id)->delete();
           Footnote::where('part_id', $id)->delete();

           $part->delete();

           return redirect()->back()->with('success', 'Part and related records deleted successfully.');
       } catch (\Exception $e) {
           \Log::error('Error deleting part: ' . $e->getMessage());

           return redirect()->back()->withErrors(['error' => 'Failed to delete part. Please try again.' . $e->getMessage()]);
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
   }//
}
