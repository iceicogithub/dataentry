@extends('admin.layout.main')
@section('style')
<style>
    /* //accordion */

.accordion-title:before {
    float: right !important;
    font-family: FontAwesome;
    content: "\f0d8";
    padding-right: 5px;
}

.accordion-title.collapsed:before {
    float: right !important;
    content: "\f0d7";
}

#accordion .card-header .accordion-title {
    text-decoration: none;
    font-weight: 700;
    color: #47b0ab;
}

#accordion .card {
    margin-top: 0 !important;
}

.card {
    margin-block: 14px;
    min-height: 100%;
}

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

/* //accordion end*/
</style>
@endsection
@section('content')
    <div class="breadcrumbs">
        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1>Dashboard</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <a href="{{ Route('act') }}"><button class="btn btn-success">Back</button></a>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content mt-3">
        <div class="row">
            <div class="col-sm-12">
                @foreach ($export as $item)
                <div class="card p-5">
                    <div class="row">
                        <div class="col-md-2 col-sm-12 col-lg-2 border p-0 p-2 pl-3 font-weight-bold">Document Name</div>
                        <div class="col-md-10 col-sm-12 col-lg-10 border p-0 p-2 pl-3">{{$item->act_title}}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 col-sm-12 col-lg-2 border p-0 p-2 pl-3 font-weight-bold">Document Type</div>
                        <div class="col-md-4 col-sm-12 col-lg-4 border p-0 p-2 pl-3">ACT</div>
                        <div class="col-md-2 col-sm-12 col-lg-2 border p-0 p-2 pl-3 font-weight-bold">State</div>
                        <div class="col-md-4 col-sm-12 col-lg-4 border p-0 p-2 pl-3">{{$item->state->name ?? 'Not defined'}}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 col-sm-12 col-lg-2 border p-0 p-2 pl-3 font-weight-bold">Category</div>
                        <div class="col-md-4 col-sm-12 col-lg-4 border p-0 p-2 pl-3 text-capitalize">{{$item->CategoryModel->category}}</div>
                        <div class="col-md-2 col-sm-12 col-lg-2 border p-0 p-2 pl-3 font-weight-bold">Download PDF</div>
                        <div class="col-md-4 col-sm-12 col-lg-4 border p-0 p-2 pl-3"><a href="{{ route('export-pdf', ['id' => $item]) }}" class="text-primary">click here</a> Download PDF</div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 col-sm-12 col-lg-2 border p-0 p-2 pl-3 font-weight-bold">Created At</div>
                        <div class="col-md-4 col-sm-12 col-lg-4 border p-0 p-2 pl-3">{{$item->created_at}}</div>
                        <div class="col-md-2 col-sm-12 col-lg-2 border p-0 p-2 pl-3 font-weight-bold">Status</div>
                        <div class="col-md-4 col-sm-12 col-lg-4 border p-0 p-2 pl-3">Normal</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="row m-2">
            <div class="col-sm-12">
                   <h3 class="m-3">Main Types</h3>
                    <div class="right-side-treatment pt-0 wow bounceInRight" data-wow-delay="1.5s">
                        <div class="right-side-content-treatment">
                            <div id="accordion">
                                @foreach($paginatedItems as $item)
                                    @if(isset($item['parts_id']))
                                        <div class="card">
                                            <div class="card-header d-flex">
                                                <div style="width: 90%; text-align: center;">
                                                    <a class="card-link accordion-title" data-toggle="collapse" href="#collapse_parts_{{ $item['parts_id'] }}">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $item['parts_title']) !!}
                                                    </a>
                                                </div>
                                                <div style="width: 10%; display: flex; justify-content: center; align-items: center;">
                                                    <a href="{{ url('/delete_parts/' . $item['parts_id']) }}" title="Delete" class="px-1 " onclick="return confirm('Are you sure ?')">
                                                        <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                    </a>
                                                </div>
                                                
                                            </div>
                                            @if (!empty($item['sections']))
                                                <div id="collapse_parts_{{ $item['parts_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['sections'] as $sectionItem)
                                                    <div class="card-body">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $sectionItem['section_title']) !!}
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['articles']))
                                                <div id="collapse_parts_{{ $item['parts_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['articles'] as $articleItem)
                                                    <div class="card-body">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $articleItem['article_title']) !!}
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['rules']))
                                                <div id="collapse_parts_{{ $item['parts_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['rules'] as $ruleItem)
                                                    <div class="card-body">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $ruleItem['rule_title']) !!}
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['regulation']))
                                                <div id="collapse_parts_{{ $item['parts_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['regulation'] as $regulationItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '', $regulationItem['regulation_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['lists']))
                                                <div id="collapse_parts_{{ $item['parts_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['lists'] as $listItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '', $listItem['list_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['part']))
                                                <div id="collapse_parts_{{ $item['parts_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['part'] as $partItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '', $partItem['part_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['appendices']))
                                                <div id="collapse_parts_{{ $item['parts_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['appendices'] as $appendicesItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '',  $appendicesItem['appendices_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['order']))
                                                <div id="collapse_parts_{{ $item['parts_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['order'] as $orderItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '',  $orderItem['order_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['annexure']))
                                                <div id="collapse_parts_{{ $item['parts_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['annexure'] as $annexureItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '',  $annexureItem['annexure_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['stschedule']))
                                                <div id="collapse_parts_{{ $item['parts_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['stschedule'] as $stscheduleItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '',  $stscheduleItem['stschedule_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    @if(isset($item['chapter_id']))
                                        <div class="card">
                                            <div class="card-header d-flex">
                                                <div style="width: 90%; text-align: center;">
                                                    <a class="card-link accordion-title"
                                                        data-toggle="collapse" href="#collapse_chapter_{{ $item['chapter_id'] }}">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $item['chapter_title']) !!}
                                                    </a>
                                                </div>
                                                <div style="width: 10%; display: flex; justify-content: center; align-items: center;">
                                                    <a href="{{ url('/delete_chapter/' . $item['chapter_id']) }}"
                                                        title="Delete" class="px-1"
                                                        onclick="return confirm('Are you sure ?')">
                                                        <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                </div>
                                            </div>
                                            @if (!empty($item['sections']))
                                                <div id="collapse_chapter_{{ $item['chapter_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['sections'] as $sectionItem)
                                                    <div class="card-body">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $sectionItem['section_title']) !!}
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['articles']))
                                                <div id="collapse_chapter_{{ $item['chapter_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['articles'] as $articleItem)
                                                    <div class="card-body">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $articleItem['article_title']) !!}
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['rules']))
                                                <div id="collapse_chapter_{{ $item['chapter_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['rules'] as $ruleItem)
                                                    <div class="card-body">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $ruleItem['rule_title']) !!}
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['regulation']))
                                                <div id="collapse_chapter_{{ $item['chapter_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['regulation'] as $regulationItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '', $regulationItem['regulation_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['lists']))
                                                <div id="collapse_chapter_{{ $item['chapter_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['lists'] as $listItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '', $listItem['list_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['part']))
                                                <div id="collapse_chapter_{{ $item['chapter_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['part'] as $partItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '', $partItem['part_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['appendices']))
                                                <div id="collapse_chapter_{{ $item['chapter_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['appendices'] as $appendicesItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '',  $appendicesItem['appendices_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['order']))
                                                <div id="collapse_chapter_{{ $item['chapter_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['order'] as $orderItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '',  $orderItem['order_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['annexure']))
                                                <div id="collapse_chapter_{{ $item['chapter_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['annexure'] as $annexureItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '',  $annexureItem['annexure_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['stschedule']))
                                                <div id="collapse_chapter_{{ $item['chapter_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['stschedule'] as $stscheduleItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '',  $stscheduleItem['stschedule_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    @if(isset($item['priliminary_id']))
                                        <div class="card">
                                            <div class="card-header d-flex">
                                                <div style="width: 90%; text-align: center;">
                                                    <a class="card-link accordion-title"
                                                        data-toggle="collapse" href="#collapse_priliminary_{{ $item['priliminary_id'] }}">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $item['priliminary_title']) !!}
                                                    </a>
                                                </div>
                                                <div style="width: 10%; display: flex; justify-content: center; align-items: center;">
                                                    <a href="{{ url('/delete_prilimiary/' . $item['priliminary_id']) }}"
                                                        title="Delete" class="px-1"
                                                        onclick="return confirm('Are you sure ?')">
                                                        <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                </div>
                                            </div>
                                            @if (!empty($item['sections']))
                                                <div id="collapse_priliminary_{{ $item['priliminary_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['sections'] as $sectionItem)
                                                    <div class="card-body">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $sectionItem['section_title']) !!}
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['articles']))
                                                <div id="collapse_priliminary_{{ $item['priliminary_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['articles'] as $articleItem)
                                                    <div class="card-body">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $articleItem['article_title']) !!}
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['rules']))
                                                <div id="collapse_priliminary_{{ $item['priliminary_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['rules'] as $ruleItem)
                                                    <div class="card-body">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $ruleItem['rule_title']) !!}
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['regulation']))
                                                <div id="collapse_priliminary_{{ $item['priliminary_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['regulation'] as $regulationItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '', $regulationItem['regulation_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['lists']))
                                                <div id="collapse_priliminary_{{ $item['priliminary_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['lists'] as $listItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '', $listItem['list_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['part']))
                                                <div id="collapse_priliminary_{{ $item['priliminary_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['part'] as $partItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '', $partItem['part_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['appendices']))
                                                <div id="collapse_priliminary_{{ $item['priliminary_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['appendices'] as $appendicesItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '',  $appendicesItem['appendices_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['order']))
                                                <div id="collapse_priliminary_{{ $item['priliminary_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['order'] as $orderItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '',  $orderItem['order_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['annexure']))
                                                <div id="collapse_priliminary_{{ $item['priliminary_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['annexure'] as $annexureItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '',  $annexureItem['annexure_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['stschedule']))
                                                <div id="collapse_priliminary_{{ $item['priliminary_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['stschedule'] as $stscheduleItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '',  $stscheduleItem['stschedule_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    @if(isset($item['schedule_id']))
                                        <div class="card">
                                            <div class="card-header d-flex">
                                                <div style="width: 90%; text-align: center;">
                                                    <a class="card-link accordion-title"
                                                        data-toggle="collapse" href="#collapse_schedule_{{ $item['schedule_id'] }}">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $item['schedule_title']) !!}
                                                    </a>
                                                </div>
                                                <div style="width: 10%; display: flex; justify-content: center; align-items: center;">
                                                    <a href="{{ url('/delete_schedule/' . $item['schedule_id']) }}"
                                                        title="Delete" class="px-1"
                                                        onclick="return confirm('Are you sure ?')">
                                                        <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                </div>
                                            </div>
                                            @if (!empty($item['sections']))
                                                <div id="collapse_schedule_{{ $item['schedule_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['sections'] as $sectionItem)
                                                    <div class="card-body">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $sectionItem['section_title']) !!}
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['articles']))
                                                <div id="collapse_schedule_{{ $item['schedule_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['articles'] as $articleItem)
                                                    <div class="card-body">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $articleItem['article_title']) !!}
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['rules']))
                                                <div id="collapse_schedule_{{ $item['schedule_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['rules'] as $ruleItem)
                                                    <div class="card-body">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $ruleItem['rule_title']) !!}
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['regulation']))
                                                <div id="collapse_schedule_{{ $item['schedule_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['regulation'] as $regulationItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '', $regulationItem['regulation_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['lists']))
                                                <div id="collapse_schedule_{{ $item['schedule_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['lists'] as $listItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '', $listItem['list_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['part']))
                                                <div id="collapse_schedule_{{ $item['schedule_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['part'] as $partItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '', $partItem['part_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['appendices']))
                                                <div id="collapse_schedule_{{ $item['schedule_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['appendices'] as $appendicesItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '',  $appendicesItem['appendices_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['order']))
                                                <div id="collapse_schedule_{{ $item['schedule_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['order'] as $orderItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '',  $orderItem['order_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['annexure']))
                                                <div id="collapse_schedule_{{ $item['schedule_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['annexure'] as $annexureItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '',  $annexureItem['annexure_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['stschedule']))
                                                <div id="collapse_schedule_{{ $item['schedule_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['stschedule'] as $stscheduleItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '',  $stscheduleItem['stschedule_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    @if(isset($item['appendix_id']))
                                        <div class="card">
                                            <div class="card-header d-flex">
                                                <div style="width: 90%; text-align: center;">
                                                    <a class="card-link accordion-title"
                                                        data-toggle="collapse" href="#collapse_appendix_{{ $item['appendix_id'] }}">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $item['appendix_title']) !!}
                                                    </a>
                                                </div>
                                                <div style="width: 10%; display: flex; justify-content: center; align-items: center;">
                                                    <a href="{{ url('/delete_appendix/' . $item['appendix_id']) }}"
                                                        title="Delete" class="px-1"
                                                        onclick="return confirm('Are you sure ?')">
                                                        <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                </div>
                                            </div>
                                            @if (!empty($item['sections']))
                                                <div id="collapse_appendix_{{ $item['appendix_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['sections'] as $sectionItem)
                                                    <div class="card-body">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $sectionItem['section_title']) !!}
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['articles']))
                                                <div id="collapse_appendix_{{ $item['appendix_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['articles'] as $articleItem)
                                                    <div class="card-body">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $articleItem['article_title']) !!}
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['rules']))
                                                <div id="collapse_appendix_{{ $item['appendix_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['rules'] as $ruleItem)
                                                    <div class="card-body">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $ruleItem['rule_title']) !!}
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['regulation']))
                                                <div id="collapse_appendix_{{ $item['appendix_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['regulation'] as $regulationItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '', $regulationItem['regulation_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['lists']))
                                                <div id="collapse_appendix_{{ $item['appendix_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['lists'] as $listItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '', $listItem['list_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['part']))
                                                <div id="collapse_appendix_{{ $item['appendix_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['part'] as $partItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '', $partItem['part_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['appendices']))
                                                <div id="collapse_appendix_{{ $item['appendix_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['appendices'] as $appendicesItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '',  $appendicesItem['appendices_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['order']))
                                                <div id="collapse_appendix_{{ $item['appendix_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['order'] as $orderItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '',  $orderItem['order_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['annexure']))
                                                <div id="collapse_appendix_{{ $item['appendix_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['annexure'] as $annexureItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '',  $annexureItem['annexure_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['stschedule']))
                                                <div id="collapse_appendix_{{ $item['appendix_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['stschedule'] as $stscheduleItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '',  $stscheduleItem['stschedule_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    @if(isset($item['main_order_id']))
                                        <div class="card">
                                            <div class="card-header d-flex">
                                                <div style="width: 90%; text-align: center;">
                                                    <a class="card-link accordion-title"
                                                        data-toggle="collapse" href="#collapse_main_order_{{ $item['main_order_id'] }}">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $item['main_order_title']) !!}
                                                    </a>
                                                </div>
                                                <div style="width: 10%; display: flex; justify-content: center; align-items: center;">
                                                    <a href="{{ url('/delete_main_order/' . $item['main_order_id']) }}"
                                                        title="Delete" class="px-1"
                                                        onclick="return confirm('Are you sure ?')">
                                                        <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                </div>
                                            </div>
                                            @if (!empty($item['sections']))
                                                <div id="collapse_main_order_{{ $item['main_order_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['sections'] as $sectionItem)
                                                    <div class="card-body">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $sectionItem['section_title']) !!}
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['articles']))
                                                <div id="collapse_main_order_{{ $item['main_order_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['articles'] as $articleItem)
                                                    <div class="card-body">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $articleItem['article_title']) !!}
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['rules']))
                                                <div id="collapse_main_order_{{ $item['main_order_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['rules'] as $ruleItem)
                                                    <div class="card-body">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $ruleItem['rule_title']) !!}
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['regulation']))
                                                <div id="collapse_main_order_{{ $item['main_order_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['regulation'] as $regulationItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '', $regulationItem['regulation_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['lists']))
                                                <div id="collapse_main_order_{{ $item['main_order_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['lists'] as $listItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '', $listItem['list_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['part']))
                                                <div id="collapse_main_order_{{ $item['main_order_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['part'] as $partItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '', $partItem['part_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['appendices']))
                                                <div id="collapse_main_order_{{ $item['main_order_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['appendices'] as $appendicesItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '',  $appendicesItem['appendices_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['order']))
                                                <div id="collapse_main_order_{{ $item['main_order_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['order'] as $orderItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '',  $orderItem['order_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['annexure']))
                                                <div id="collapse_main_order_{{ $item['main_order_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['annexure'] as $annexureItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '',  $annexureItem['annexure_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (!empty($item['stschedule']))
                                                <div id="collapse_main_order_{{ $item['main_order_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    @foreach ($item['stschedule'] as $stscheduleItem)
                                                        <div class="card-body">
                                                            {!! preg_replace('/[0-9\[\]\.]/', '',  $stscheduleItem['stschedule_title']) !!}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
            </div>
           
        </div>
        <div class="row">
            <div class="col-sm-12">
                {{ $paginatedItems->links() }}
            </div>
        </div>
    </div>
@endsection
