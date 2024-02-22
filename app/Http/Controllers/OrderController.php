<?php

namespace App\Http\Controllers;

use App\Models\Appendices;
use App\Models\Chapter;
use App\Models\Footnote;
use App\Models\Orders;
use App\Models\Parts;
use App\Models\Priliminary;
use App\Models\Schedule;
use App\Models\SubOrders;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    
    public function edit_order($id)
    {
        $order = Orders::with('ChapterModel', 'Partmodel','Appendicesmodel','Schedulemodel','PriliminaryModel')->where('order_id', $id)->first();
        $suborder = Orders::where('order_id', $id)
            ->with(['subOrderModel', 'footnoteModel' => function ($query) {
                $query->whereNull('sub_order_id');
            }])
            ->get();

        $sub_order_f = SubOrders::where('order_id', $id)->with('footnoteModel')->get();

        $count = 0;

        if ($sub_order_f) {
            foreach ($sub_order_f as $sub_order) {
                $count += $sub_order->footnoteModel->count();
            }
        }



        return view('admin.Orders.edit', compact('order', 'suborder', 'sub_order_f', 'count'));
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
            if (!$request->has('order_id')) {
                return redirect()->route('edit-order', ['id' => $id])->withErrors(['error' => 'Order ID is missing']);
            }

            $order = Orders::find($request->order_id);

            // Check if the section is found
            if (!$order) {
                return redirect()->route('edit-order', ['id' => $id])->withErrors(['error' => 'Order not found']);
            }
            if ($order) {

                $order->order_content = $request->order_content ?? null;
                $order->order_title = $request->order_title ?? null;
                $order->order_no = $request->order_no ?? null;
                $order->update();


                if ($request->has('order_footnote_content')) {
                    foreach ($request->order_footnote_content as $key => $items) {
                        // Check if the key exists before using it
                        foreach ($items as $kys => $item) {
                            // Check if the sec_footnote_id exists at the specified index
                            if (isset($request->order_footnote_id[$key][$kys])) {
                                // Use first() instead of get() to get a single model instance
                                $foot = Footnote::find($request->order_footnote_id[$key][$kys]);

                                if ($foot) {
                                    $foot->update([
                                        'footnote_content' => $item ?? null,
                                        'footnote_no' => $request->order_footnote_no[$key][$kys] ?? null,
                                    ]);
                                }
                            } else {
                                // Create a new footnote
                                $footnote = new Footnote();
                                $footnote->order_id = $id ?? null;
                                $footnote->order_no = $order->order_no ?? null;
                                $footnote->act_id = $order->act_id ?? null;
                                $footnote->chapter_id = $order->chapter_id ?? null;
                                $footnote->parts_id = $order->parts_id ?? null;
                                $footnote->priliminary_id = $order->priliminary_id ?? null;
                                $footnote->schedule_id = $order->schedule_id ?? null;
                                $footnote->appendices_id = $order->appendices_id ?? null;
                                $footnote->footnote_content = $item ?? null;
                                $footnote->save();
                            }
                        }
                    }
                }
            }

            // Store Sub-Sections

            if ($request->has('sub_order_no')) {
                foreach ($request->sub_order_no as $key => $item) {
                    // Check if sub_section_id is present in the request
                    if ($request->filled('sub_order_id') && is_array($request->sub_order_id) && array_key_exists($key, $request->sub_order_id)) {

                        $sub_order = SubOrders::find($request->sub_order_id[$key]);

                        // Check if $sub_section is found in the database and the IDs match
                        if ($sub_order && $sub_order->sub_order_id == $request->sub_order_id[$key]) {
                            $sub_order->sub_order_no = $item ?? null;
                            $sub_order->sub_order_content = $request->sub_order_content[$key] ?? null;
                            $sub_order->update();

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
                                        $footnote->sub_order_id = $sub_order->sub_order_id;
                                        $footnote->order_id = $id ?? null;
                                        $footnote->act_id = $order->act_id ?? null;
                                        $footnote->chapter_id = $order->chapter_id ?? null;
                                        $footnote->parts_id = $order->parts_id ?? null;
                                        $footnote->priliminary_id = $order->priliminary_id ?? null;
                                        $footnote->schedule_id = $order->schedule_id ?? null;
                                        $footnote->appendices_id = $order->appendices_id ?? null;
                                        $footnote->footnote_content = $item ?? null;
                                        $footnote->save();
                                    }
                                }
                            }
                        }
                    } else {
                        // Existing subsection not found, create a new one
                        $suborder = new SubOrders();
                        $suborder->order_id = $id ?? null;
                        $suborder->sub_order_no = $item ?? null;
                        $suborder->order_no = $order->order_no ?? null;
                        $suborder->act_id = $order->act_id ?? null;
                        $suborder->chapter_id = $order->chapter_id ?? null;
                        $suborder->parts_id = $order->parts_id ?? null;
                        $suborder->priliminary_id = $order->priliminary_id ?? null;
                        $suborder->schedule_id = $order->schedule_id ?? null;
                        $suborder->appendices_id = $order->appendices_id ?? null;
                        $suborder->sub_order_content = $request->sub_order_content[$key] ?? null;
                        $suborder->save();

                        if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                            foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                                // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                                if (isset($request->sub_footnote_content[$key][$kys])) {
                                    // Create a new footnote for the newly created subsection
                                    $footnote = new Footnote();
                                    $footnote->sub_order_id = $suborder->sub_order_id;
                                    $footnote->order_id = $id ?? null;
                                    $footnote->act_id = $order->act_id ?? null;
                                    $footnote->chapter_id = $order->chapter_id ?? null;
                                    $footnote->parts_id = $order->parts_id ?? null;
                                    $footnote->priliminary_id = $order->priliminary_id ?? null;
                                    $footnote->schedule_id = $order->schedule_id ?? null;
                                    $footnote->appendices_id = $order->appendices_id ?? null;
                                    $footnote->footnote_content = $item ?? null;
                                    $footnote->footnote_no = $request->sub_footnote_no[$key][$kys] ?? null;
                                    $footnote->save();
                                }
                            }
                        }
                    }
                }
            }



            return redirect()->route('get_act_section', ['id' => $order->act_id])->with('success', 'Order updated successfully');
        // } catch (\Exception $e) {
        //     \Log::error('Error updating Act: ' . $e->getMessage());
        //     return redirect()->route('edit-order', ['id' => $id])->withErrors(['error' => 'Failed to update Orders. Please try again.' . $e->getMessage()]);
        // }
    }

    public function add_below_new_order(Request $request, $id, $order_id, $order_rank)
    {
        
        $order_rank = $order_rank;
        $order = Orders::with('ChapterModel', 'Partmodel', 'PriliminaryModel','Appendicesmodel','Schedulemodel')->where('act_id', $id)
            ->where('order_id', $order_id)->first();

        return view('admin.Orders.add_new', compact('order', 'order_rank'));
    }

    public function add_new_order(Request $request)
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
        $order_no = $request->order_no;
        $order_rank = $request->order_rank;
        $maintypeId = $request->maintype_id;

        // Calculate the next section number
        $nextOrderNo = $order_no;
        $nextOrderRank = $order_rank + 0.01;



        // Update the existing sections' section_no in the Section table
        // Section::where('section_no', '>=', $nextSectionNo)
        //     ->increment('section_no');

        // Create the new section with the incremented section_no
        $order = Orders::create([
            'order_rank' => $nextOrderRank ?? 1,
            'order_no' => $nextOrderNo,
            'act_id' => $request->act_id,
            'maintype_id' => $maintypeId,
            'chapter_id' => $request->chapter_id ?? null,
            'priliminary_id' => $request->priliminary_id ?? null,
            'parts_id' => $request->parts_id ?? null,
            'schedule_id' => $request->schedule_id ?? null,
            'appendices_id' => $request->appendices_id ?? null,
            'subtypes_id' => $request->subtypes_id,
            'order_title' => $request->order_title,
            'order_content' => $request->order_content,
        ]);

        if ($request->has('order_footnote_content')) {
            foreach ($request->order_footnote_content as $key => $item) {
                // Check if the key exists before using it
                if (isset($request->order_footnote_content[$key])) {
                    // Create a new footnote
                    $footnote = new Footnote();
                    $footnote->order_id = $order->order_id ?? null;
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

        if ($request->has('sub_order_no')) {
            foreach ($request->sub_order_no as $key => $item) {
                // Existing subsection not found, create a new one
                $sub_order = SubOrders::create([
                    'order_id' => $order->order_id,
                    'sub_order_no' => $item ?? null,
                    'order_no' => $nextOrderNo,
                    'act_id' => $request->act_id,
                    'chapter_id' => $maintypeId == "1" ? $request->chapter_id : null,
                    'parts_id' => $maintypeId == "2" ? $request->parts_id : null,
                    'priliminary_id' => $maintypeId == "3" ? $request->priliminary_id : null,
                    'schedule_id' => $maintypeId == "4" ? $request->schedule_id : null,
                    'appendices_id' => $maintypeId == "5" ? $request->appendices_id : null,
                    'sub_order_content' => $request->sub_order_content[$key] ?? null,
                ]);

                if ($request->has('sub_footnote_content') && is_array($request->sub_footnote_content) && isset($request->sub_footnote_content[$key]) && is_array($request->sub_footnote_content[$key])) {
                    foreach ($request->sub_footnote_content[$key] as $kys => $item) {
                        // Check if the key exists in both sub_footnote_no and sub_footnote_content arrays
                        if (isset($request->sub_footnote_content[$key][$kys])) {
                            // Create a new footnote for the newly created subsection
                            $footnote = new Footnote();
                            $footnote->sub_order_id = $sub_order->sub_order_id;
                            $footnote->order_id = $order->order_id ?? null;
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
   

    public function view_sub_order(Request $request,  $id)
    {
        $order = Orders::where('order_id', $id)->first();
        $sub_order = SubOrders::where('order_id', $id)->with('footnoteModel')->get();
        return view('admin.Orders.view', compact('order','sub_order'));
    }

    public function destroy_sub_order(string $id)
    {
        try {
            $suborder = SubOrders::find($id);

            if (!$suborder) {
                return redirect()->back()->withErrors(['error' => 'Sub-Order not found.']);
            }
            
            Footnote::where('sub_order_id', $id)->delete();

            $suborder->delete();

            return redirect()->back()->with('success', 'Sub-order and related records deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting Sub-order: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete Sub-order. Please try again.' . $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            $order = Orders::find($id);

            if (!$order) {
                return redirect()->back()->withErrors(['error' => 'Order not found.']);
            }
            
            SubOrders::where('order_id', $id)->delete();
            Footnote::where('order_id', $id)->delete();

            $order->delete();

            return redirect()->back()->with('success', 'Order and related records deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting order: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to delete order. Please try again.' . $e->getMessage()]);
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