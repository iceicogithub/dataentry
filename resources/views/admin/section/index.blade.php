@extends('admin.layout.main')
@section('style')
<style>
.pagination-links {
    margin-top: 20px; /* Adjust margin as needed */
    text-align: center; /* Center the pagination links horizontally */
}

/* Style the pagination links */
.pagination-links ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.pagination-links ul li {
    display: inline-block;
    margin-right: 5px; /* Adjust spacing between pagination items */
}

.pagination-links ul li a,
.pagination-links ul li span {
    text-decoration: none;
    padding: 5px 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    color: #333;
}

.pagination-links ul li.active a {
    background-color: #007bff;
    color: #fff;
    border-color: #007bff;
}

/* Style the pagination arrows */
.pagination-links ul li.prev,
.pagination-links ul li.next {
    font-size: 14px; /* Adjust arrow size */
    padding: 5px; /* Adjust padding */
}

.pagination-links ul li.prev a,
.pagination-links ul li.next a {
    padding: 5px; /* Adjust padding */
}

.pagination-links ul li.prev.disabled,
.pagination-links ul li.next.disabled {
    pointer-events: none; /* Disable clicking on disabled arrows */
    opacity: 0.5; /* Reduce opacity of disabled arrows */
}
</style>
@endsection('style')
@section('content')
    <div class="breadcrumbs">
        <div class="col-sm-8">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1>Main Act : {{ $act->act_title }}</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <a href="/add-act/{{ $act_id }}" class="mr-2"><button class="btn btn-success">Add
                                Index</button></a>
                        <a href="/edit-main-act/{{ $act_id }}"><button class="btn btn-danger">Back</button></a>
                    </ol>

                </div>
            </div>
        </div>

        <div class="col-sm-12">

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->has('error'))
                <div class="alert alert-danger">
                    {{ $errors->first('error') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-success">
                    {{ session('error') }}
                </div>
            @endif

            <div class="card p-5">
                <form id="form" action="/update_main_act/{{ $act->act_id }}" method="post"
                    enctype="multipart/form-data" class="form form-horizontal">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-default">
                                <label class="float-label"> Act<span class="text-danger">*</span></label>
                                <input type="text" name="act_title" value="{{ $act->act_title }}"
                                    class="form-control mb-3" placeholder="Enter Act Title">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-default">
                                <label class="float-label"> Act NO.<span class="text-danger">*</span></label>
                                <textarea name="act_no" class="form-control mb-3" placeholder="Enter Act No" id="act_no" cols="30"
                                    rows="3">{{ $act->act_no }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-default">
                                <label class="float-label">Enactment Date<span class="text-danger">*</span></label>
                                <input type="date" name="enactment_date" value="{{ $act->enactment_date }}"
                                    class="form-control mb-3">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-default">
                                <label class="float-label">Enforcement Date<span class="text-danger">*</span></label>
                                <input type="date" name="enforcement_date" value="{{ $act->enforcement_date }}"
                                    class="form-control mb-3">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-default">
                                <label class="float-label">Act Date<span class="text-danger">*</span></label>
                                <input type="text" name="act_date" value="{{ $act->act_date }}"
                                    class="form-control mb-3" placeholder="Enter Act Date">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-default">
                                <label class="float-label">Ministry<span class="text-danger">*</span></label>
                                <input type="text" name="ministry" value="{{ $act->ministry }}"
                                    class="form-control mb-3" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-default">
                                <label class="float-label"> PREAMBLE <span class="text-danger">*</span></label>

                                <textarea name="act_description" class="form-control mb-3" placeholder="Enter Act Description" id="act_description"
                                    cols="30" rows="3">{{ $act->act_description }}</textarea>
                            </div>
                        </div>
                        @if ($act_footnote_descriptions)
                            @foreach ($act_footnote_descriptions as $key => $act_footnote_description)
                                <div class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12">
                                    <label class="float-label">
                                        Footnote
                                        <span class="pl-2">
                                            <button type="button" class="btn btn-sm social facebook p-0 add-footnote">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </span>
                                    </label>
                                    <div class="show-footnote" style="">
                                        <div class="footnote-entry">
                                            <textarea type="text" name="act_footnote_description[]" id="ck[{{ $key }}]"
                                                class="form-control ckeditor-replace footnote" placeholder="Enter Footnote Description">
                                                {{ $act_footnote_description }}</textarea>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        @endIf

                        <div class="footnote-addition-container float-right col-md-12 ">

                            <div class="px-0 py-3">
                                <div class="float-right">
                                    <span style="font-size: small;" class="px-2 text-uppercase font-weight-bold">
                                        (add Footnote)
                                    </span>
                                    <button type="button" class="btn btn-sm social facebook p-0 add-multi-footnote">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm social youtube p-0 remove-multi-footnote">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-12 text-right mt-2">
                            <div class="form-group">
                                <button type="submit" class="btn  btn-success">Update Data</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="content mt-3">
        <div class="row">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Arrangement of Sections</strong>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered text-center" id="myTable">
                            <thead class="thead-light">
                                @php
                                    use App\Models\Chapter;
                                    use App\Models\Parts;
                                    use App\Models\Priliminary;
                                    use App\Models\Schedule;
                                    use App\Models\Appendix;
                                    use App\Models\MainOrder;

                                    $chapter = Chapter::with('ChapterType')->where('act_id', $act_id)->first();
                                    $parts = Parts::with('PartsType')->where('act_id', $act_id)->first();
                                    $priliminary = Priliminary::with('PriliminaryType')->where('act_id', $act_id)->first();
                                    $schedule = Schedule::with('ScheduleType')->where('act_id', $act_id)->first();
                                    $appendix = Appendix::with('AppendixType')->where('act_id', $act_id)->first();
                                    $main_order = MainOrder::with('MainOrderType')->where('act_id', $act_id)->first();

                                @endphp

                                <tr>
                                    <th scope="col" class="text-center">Sr.No</th>
                                    <th scope="col" class="text-center">
                                        @if ($chapter && $chapter->maintype_id)
                                            @if ($chapter->ChapterType->maintype_id == '1')
                                                Chapter,
                                            @endif
                                        @endif
                                        @if ($parts && $parts->maintype_id)
                                            @if ($parts->PartsType->maintype_id == '2')
                                                Parts,
                                            @endif
                                        @endif
                                        @if ($priliminary && $priliminary->maintype_id)
                                            @if ($priliminary->PriliminaryType->maintype_id == '3')
                                                Priliminary,
                                            @endif
                                        @endif
                                        @if ($schedule && $schedule->maintype_id)
                                            @if ($schedule->ScheduleType->maintype_id == '4')
                                                Schedule,
                                            @endif
                                        @endif
                                        @if ($appendix && $appendix->maintype_id)
                                            @if ($appendix->AppendixType->maintype_id == '5')
                                                Appendix,
                                            @endif
                                        @endif
                                        @if ($main_order && $main_order->maintype_id)
                                            @if ($main_order->MainOrderType->maintype_id == '6')
                                                Order,
                                            @endif
                                        @endif

                                    </th>
                                    <th scope="col" class="text-center">
                                        @if ($chapter && $chapter->maintype_id)
                                            @if ($chapter->ChapterType->maintype_id == '1')
                                                Section No. ,
                                            @endif
                                        @endif
                                        @if ($schedule && $schedule->maintype_id)
                                            @if ($schedule->ScheduleType->maintype_id == '4')
                                                Rule No. ,
                                            @endif
                                        @endif
                                        @if ($appendix && $appendix->maintype_id)
                                            @if ($appendix->AppendixType->maintype_id == '5')
                                                Article No. ,
                                            @endif
                                        @endif

                                    </th>
                                    <th scope="col" class="text-center">
                                        @if ($chapter && $chapter->maintype_id)
                                            @if ($chapter->ChapterType->maintype_id == '1')
                                                Section ,
                                            @endif
                                        @endif
                                        @if ($schedule && $schedule->maintype_id)
                                            @if ($schedule->ScheduleType->maintype_id == '4')
                                                Rule ,
                                            @endif
                                        @endif
                                        @if ($appendix && $appendix->maintype_id)
                                            @if ($appendix->AppendixType->maintype_id == '5')
                                                Article ,
                                            @endif
                                        @endif
                                    </th>
                                    <th scope="col" class="text-center">Date of changes</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $i = 0;

                                // dd($paginatedCollection->toArray());
                                // die();

                                @endphp 

                                @foreach($paginatedCollection as $item)

                                    <tr>
                                        <td>@php echo $i++; @endphp </td>
                                        <td>
                                            @if ($item->maintype_id == 1)
                                            {!! $item->ChapterModel->chapter_title !!}
                                        @elseif($item->maintype_id == 2)
                                            {!! $item->Partmodel->parts_title !!}
                                        @elseif($item->maintype_id == 3)
                                            {!! $item->PriliminaryModel->priliminary_title !!}
                                        @elseif($item->maintype_id == 4)
                                            {!! $item->Schedulemodel->schedule_title !!}
                                        @elseif($item->maintype_id == 5)
                                            {!! $item->Appendixmodel->appendix_title !!}
                                        @elseif($item->maintype_id == 6)
                                            {!! $item->MainOrderModel->main_order_title !!}
                                        @else
                                            null
                                        @endif
                                        </td>
                                        <td>
                                        @if ($item->section_no)
                                            {{ $item->section_no}}
                                        @elseif($item->article_no)
                                            {{ $item->article_no }}
                                        @elseif($item->rule_no)
                                            {{ $item->rule_no}}
                                        @elseif($item->regulation_no)
                                            {{$item->regulation_no}}
                                        @elseif($item->list_no)
                                            {{ $item->list_no}}
                                        @elseif($item->part_no)
                                            {{ $item->part_no }}
                                        @elseif($item->appendices_no)
                                            {{ $item->appendices_no}}
                                        @elseif($item->order_no)
                                            {{$item->order_no}}
                                        @elseif($item->annexure_no)
                                            {{ $item->annexure_no}}
                                        @elseif($item->stschedule_no)
                                            {{ $item->stschedule_no}}
                                        @else
                                            null
                                        @endif
                                        </td>
                                        <td>
                                        @if ($item->section_title)
                                            {!! $item->section_title !!}
                                        @elseif($item->article_title)
                                            {!! $item->article_title !!}
                                        @elseif($item->rule_title)
                                            {!! $item->rule_title!!}
                                        @elseif($item->regulation_title)
                                            {!!$item->regulation_title!!}
                                        @elseif($item->list_title)
                                            {{  $item->list_title }}
                                        @elseif($item->part_title)
                                            {{ $item->part_title }}
                                        @elseif($item->appendices_title)
                                            {!! $item->appendices_title!!}
                                        @elseif($item->order_title)
                                            {!!$item->order_title!!}
                                        @elseif($item->annexure_title)
                                            {!! $item->annexure_title!!}
                                        @elseif($item->stschedule_title)
                                            {!! $item->stschedule_title!!}
                                        @else
                                            null
                                        @endif
                                        </td>
                                        <td></td>
                                        <td>
                                        @if ($item->section_id)

                                        <a href="/edit-section/{{ $item->section_id }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                            class="px-1">
                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                        </a>
                                        <a href="/view-sub-section/{{ $item->section_id }}" title="View"
                                            class="px-1">
                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                        </a>
                                        <a href="#" title="Delete" class="px-1" data-toggle="modal" data-target="#deleteModal"
                                            data-chapterid="{{ $item->chapter_id }}" data-partsid="{{ $item->parts_id }}"
                                            data-preliminaryid="{{ $item->preliminary_id }}" data-scheduleid="{{ $item->schedule_id }}"
                                            data-mainorderid="{{ $item->main_order_id }}" data-sectionid="{{ $item->section_id }}">
                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                        </a>
                                        <a href="{{ url('/add_below_new_section', ['act_id' => $item->act_id, 'section_id' => $item->section_id]) }}"
                                            title="Add Next Section" class="px-1">
                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                        </a>
                                        @elseif($item->article_id)

                                        <a href="/edit-article/{{ $item->article_id }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                            class="px-1">
                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                        </a>
                                        <a href="/view-sub-article/{{ $item->article_id }}" title="View"
                                            class="px-1">
                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                        </a>
                                        <a href="{{ url('/delete_article/' . $item->article_id) }}"
                                            title="Delete" class="px-1"
                                            onclick="return confirm('Are you sure ?')">
                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                        </a>
                                        {{-- <a href="{{ url('/add_below_new_article', ['act_id' => $item->act_id, 'article_id' => $item->article_id, 'article_rank' => $item->article_rank]) }}"
                                            title="Add Next Article" class="px-1">
                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                        </a> --}}
                                        <a href="{{ route('add_below_new_article', ['id' => $item->act_id, 'article_id' => $item->article_id]) }}"
                                            title="Add Next Article" class="px-1">
                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                        </a>
                                        
                                        @elseif($item->rule_id)
                                            <a href="/edit-rule/{{ $item->rule_id }}?page={{ $paginatedCollection->currentPage() }}" title="Edit" class="px-1">
                                                        <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                    </a>
                                                    <a href="/view-sub-rule/{{ $item->rule_id }}" title="View"
                                                        class="px-1">
                                                        <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                    </a>
                                                    <a href="{{ url('/delete_rule/' . $item->rule_id) }}" title="Delete"
                                                        class="px-1" onclick="return confirm('Are you sure ?')">
                                                        <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                    </a>
                                                    <a href="{{ route('add_below_new_rule', ['id' => $item->act_id, 'rule_id' => $item->rule_id]) }}"
                                                        title="Add Next Rule" class="px-1">
                                                        <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                    </a>
                                        @elseif($item->regulation_id)
                                        <a href="/edit-regulation/{{ $item->regulation_id }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                            class="px-1">
                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                        </a>
                                        <a href="/view-sub-regulation/{{ $item->regulation_id }}" title="View"
                                            class="px-1">
                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                        </a>
                                        <a href="{{ url('/delete_regulation/' . $item->regulation_id) }}"
                                            title="Delete" class="px-1"
                                            onclick="return confirm('Are you sure ?')">
                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                        </a>
                                        {{-- <a href="{{ url('/add_below_new_regulation', ['act_id' => $item->act_id, 'regulation_id' => $item->regulation_id, 'regulation_rank' => $item->regulation_rank]) }}"
                                            title="Add Next Regulation" class="px-1">
                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                        </a> --}}
                                        <a href="{{ route('add_below_new_regulation', ['id' => $item->act_id, 'regulation_id' => $item->regulation_id]) }}"
                                            title="Add Next Regulation" class="px-1">
                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                        </a>
                                        @elseif($item->list_id)
                                        <a href="/edit-list/{{ $item->list_id }}?page={{ $paginatedCollection->currentPage() }}" title="Edit" class="px-1">
                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                        </a>
                                        <a href="/view-sub-list/{{ $item->list_id }}" title="View"
                                            class="px-1">
                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                        </a>
                                        <a href="{{ url('/delete_list/' . $item->list_id) }}" title="Delete"
                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                        </a>
                                        
                                        <a href="{{ route('add_below_new_list', ['id' => $item->act_id, 'list_id' => $item->list_id]) }}"
                                            title="Add Next List" class="px-1">
                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                        </a>
                                        @elseif($item->part_id)
                                        <a href="/edit-part/{{ $item->part_id }}?page={{ $paginatedCollection->currentPage() }}" title="Edit" class="px-1">
                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                        </a>
                                        <a href="/view-sub-part/{{ $item->part_id }}" title="View"
                                            class="px-1">
                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                        </a>
                                        <a href="{{ url('/delete_part/' . $item->part_id) }}" title="Delete"
                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                        </a>
                                        {{-- <a href="{{ url('/add_below_new_part', ['act_id' => $item->act_id, 'part_id' => $item->part_id, 'part_rank' => $item->part_rank]) }}"
                                            title="Add Next Part" class="px-1">
                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                        </a> --}}
                                        <a href="{{ route('add_below_new_part', ['id' => $item->act_id, 'part_id' => $item->part_id]) }}"
                                            title="Add Next Part" class="px-1">
                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                        </a>
                                        @elseif($item->appendices_id)
                                        <a href="/edit-appendices/{{ $item->appendices_id }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                            class="px-1">
                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                        </a>
                                        <a href="/view-sub-appendices/{{ $item->appendices_id }}" title="View"
                                            class="px-1">
                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                        </a>
                                        <a href="{{ url('/delete_appendices/' . $item->appendices_id) }}"
                                            title="Delete" class="px-1"
                                            onclick="return confirm('Are you sure ?')">
                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                        </a>
                                        <a href="{{ route('add_below_new_appendices', ['id' => $item->act_id, 'appendices_id' => $item->appendices_id]) }}"
                                            title="Add Next Appendices" class="px-1">
                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                        </a>
                                        @elseif($item->order_id)
                                        <a href="/edit-order/{{ $item->order_id }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                            class="px-1">
                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                        </a>
                                        <a href="/view-sub-order/{{ $item->order_id }}" title="View"
                                            class="px-1">
                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                        </a>
                                        <a href="{{ url('/delete_order/' . $item->order_id) }}" title="Delete"
                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                        </a>
                                        <a href="{{ route('add_below_new_order', ['id' => $item->act_id, 'order_id' => $item->order_id]) }}"
                                            title="Add Next Order" class="px-1">
                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                        </a>
                                        @elseif($item->annexure_id)
                                        <a href="/edit-annexure/{{ $item->annexure_id }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                            class="px-1">
                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                        </a>
                                        <a href="/view-sub-annexure/{{ $item->annexure_id }}" title="View"
                                            class="px-1">
                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                        </a>
                                        <a href="{{ url('/delete_annexure/' . $item->annexure_id) }}"
                                            title="Delete" class="px-1"
                                            onclick="return confirm('Are you sure ?')">
                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                        </a>
                                        <a href="{{ route('add_below_new_annexure', ['id' => $item->act_id, 'annexure_id' => $item->annexure_id]) }}"
                                            title="Add Next Annexure" class="px-1">
                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                        </a>
                                        @elseif($item->stschedule_id)
                                        <a href="/edit-stschedule/{{ $item->stschedule_id }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                            class="px-1">
                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                        </a>
                                        <a href="/view-sub-stschedule/{{ $item->stschedule_id }}" title="View"
                                            class="px-1">
                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                        </a>
                                        <a href="{{ url('/delete_stschedule/' . $item->stschedule_id) }}"
                                            title="Delete" class="px-1"
                                            onclick="return confirm('Are you sure ?')">
                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                        </a>
                                        <a href="{{ route('add_below_new_stschedule', ['id' => $item->act_id, 'stschedule_id' => $item->stschedule_id]) }}"
                                            title="Add Next Schedule" class="px-1">
                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                        </a> 
                                        @else
                                            null
                                        @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="pagination-links">
                            {{ $paginatedCollection->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Delete Type</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>What type of item do you want to delete?</p>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="deleteType" id="mainTypeRadio" value="mainType">
                            <label class="form-check-label" for="mainTypeRadio">
                                Main Type
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="deleteType" id="subTypeRadio" value="subType">
                            <label class="form-check-label" for="subTypeRadio">
                                Sub Type
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="confirmDelete()">Delete</button>
                    </div>
                </div>
            </div>
        </div> 

    </div>


    <script src="https://cdn.ckeditor.com/4.16.2/full-all/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @if ($act_footnote_descriptions)
        <script>
            $(document).ready(function() {
                CKEDITOR.replaceAll('ckeditor-replace', {
                    // Additional configuration options if needed
                    // ...
                });
            });
        </script>
    @endif
    <script>
        CKEDITOR.replace('act_no');
        CKEDITOR.replace('act_description');
        CKEDITOR.replace('act_footnote');


        $(document).on('click', '.add-footnote', function() {
            var icon = $(this).find('i');
            var section = $(this).closest('.form-default').find('.show-footnote');
            section.slideToggle();
            icon.toggleClass('fa-plus fa-minus');

            // Initialize CKEditor for the new textarea
            CKEDITOR.replace(section.find('.ckeditor-replace.footnote')[0]);
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.add-multi-footnote', function() {


                var newSection = `<div class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12 footnote-addition">
                                        <label class="float-label">
                                        Add Footnote
                                        <span class="pl-2">
                                            <button type="button" class="btn btn-sm social facebook p-0 add-footnote">
                                            <i class="fa fa-plus"></i>
                                            </button>
                                        </span>
                                        </label>
                                        <div class="show-footnote" style="display: none">
                                            <textarea type="text" name="act_footnote_description[]" class="form-control ckeditor-replace footnote"></textarea>
                                        </div>
                                   
                                       
                                    </div>
                                    
                                `;

                $('.footnote-addition-container').append(newSection);

                CKEDITOR.replace($('.footnote-addition:last').find('.ckeditor-replace')[0]);
            });

            $(document).on('click', '.remove-multi-footnote', function() {
                if ($('.footnote-addition').length > 0) {
                    $('.footnote-addition:last').remove();
                }
            });

        });

        function confirmDelete() {
        var deleteType = $('input[name=deleteType]:checked').val();
        var chapterId = $('#deleteModal').data('chapterid');
        var partId = $('#deleteModal').data('partsid');
        var preliminaryId = $('#deleteModal').data('preliminaryid');
        var sectionId = $('#deleteModal').data('sectionid');
        console.log(chapterId);
        console.log(partId);
        console.log(preliminaryId);
        console.log(sectionId);

        var url;
        if (deleteType === 'mainType') {
            if (chapterId !== null) {
                url = '/delete_chapter/' + chapterId;
            } else if (partId !== null) {
                url = '/delete_part/' + partId;
            } else if (preliminaryId !== null) {
                url = '/delete_preliminary/' + preliminaryId;
            } else {
                // Handle the case where no ID is available
                console.error('No ID available for deletion.');
                return;
            }
        } else {
            // Perform deletion for sub type
            url = '/delete_section/' + sectionId;
        }

        // Redirect to delete endpoint
        window.location.href = url;
    }

     $(document).ready(function() {
        console.log("Document ready!");
        $('body').on('show.bs.modal', '#deleteModal', function(event) {
        console.log("Modal show event triggered!");
        var button = $(event.relatedTarget);
        console.log(button);
        var chapterId = button.data('chapterid');
        var partId = button.data('partsid');
        var preliminaryId = button.data('preliminaryid');
        var sectionId = button.data('sectionid');
        console.log(chapterId);
        console.log(partId);
        console.log(preliminaryId);
        console.log(sectionId);

        $(this).find('#mainTypeRadio').val('mainType');

        // Set data attributes for chapter, part, preliminary, and section IDs
        $(this).data('chapterid', chapterId);
        $(this).data('partsid', partId);
        $(this).data('preliminaryid', preliminaryId);
        $(this).data('sectionid', sectionId);
        });
        });


     
    </script>
    
@endsection
