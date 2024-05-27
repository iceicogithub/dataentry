@extends('admin.layout.main')
@section('style')
<style>
/* .pagination-links {
    margin-top: 20px; 
    text-align: right; 
}


.pagination-links ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.pagination-links ul li {
    display: inline-block;
    margin-right: 5px; 
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


.pagination-links ul li.prev,
.pagination-links ul li.next {
    font-size: 12px;
    padding: 5px; 
}

.pagination-links ul li.prev a,
.pagination-links ul li.next a {
    padding: 5px;
}

.pagination-links ul li.prev.disabled,
.pagination-links ul li.next.disabled {
    pointer-events: none; 
    opacity: 0.5; 
}
.pagination-links .hidden {
    text-align: left!important;
}
.pagination-links .w-5  {
    display:none;
} */


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
            margin-block: 0;
            min-height: 100%;
        }

       
</style>
@endsection('style')
@section('content')
    <div class="breadcrumbs">
        <div class="col-sm-8">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1>Legislation Name: {{ $act->legislation_name }}</h1>
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
        <div class="row m-2">
            <div class="col-sm-12">
                    <h3 class="m-3">Main Types</h3>
                    <div class="right-side-treatment pt-0 wow bounceInRight" data-wow-delay="1.5s">
                        <div class="pagination-links">
                            <form action="{{ request()->url() }}" method="GET" class="form-inline">
                                <label for="perPage">Show:</label>
                                <select name="perPage" id="perPage" class="form-control mx-2" onchange="this.form.submit()">
                                    <option value="10" {{ request()->get('perPage') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request()->get('perPage') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request()->get('perPage') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request()->get('perPage') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                                <span>entries</span>
                            </form>
                        </div>
                        <div class="right-side-content-treatment">
                            <div id="accordion">
                                @php
                                   $i = 0; 
                                @endphp
                                @foreach($paginatedCollection as $item)
                                    @if(isset($item['parts_id']))
                                        <div class="card">
                                            <div class="card-header d-flex">
                                                <div style="width: 90%; text-align: center;">
                                                    <a class="card-link accordion-title" data-toggle="collapse"
                                                        href="#collapse_parts_{{ $item['parts_id'] }}">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $item['parts_title']) !!}
                                                    </a>
                                                </div>
                                                <div style="width: 10%; display: flex; justify-content: center; align-items: center;">
                                                    <a href="{{ url('/add_below_new_maintype', ['act_id' => $item['act_id'], 'main_id' => $item['main_id'], 'id' => $item['parts_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                        title="Add Next Main Type" class="px-1">
                                                        <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                    </a>
                                                    <a href="{{ url('/delete_parts/' . $item['parts_id']) }}" title="Delete" class="px-1 " onclick="return confirm('Are you sure ?')">
                                                        <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            @if (!empty($item['sections']))
                                                <div id="collapse_parts_{{ $item['parts_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['sections'] as $sectionItem)
                                                                <tr>
                                                                    <td>{{$sectionItem['section_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $sectionItem['section_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-section/{{ $sectionItem['section_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-section/{{ $sectionItem['section_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_section/' . $sectionItem['section_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/add_below_new_section', ['act_id' => $sectionItem['act_id'], 'section_id' => $sectionItem['section_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Section" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>     
                                                </div>
                                            @endif
                                            @if (!empty($item['articles']))
                                                <div id="collapse_parts_{{ $item['parts_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['articles'] as $articleItem)
                                                                <tr>
                                                                    <td>{{$articleItem['article_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $articleItem['article_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-article/{{ $articleItem['article_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-article/{{ $articleItem['article_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_article/' . $articleItem['article_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_article', ['id' => $articleItem['act_id'], 'article_id' => $articleItem['article_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Article" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['rules']))
                                                <div id="collapse_parts_{{ $item['parts_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['rules'] as $ruleItem)
                                                                <tr>
                                                                    <td>{{$ruleItem['rule_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $ruleItem['rule_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-rule/{{ $ruleItem['rule_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit" class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-rule/{{ $ruleItem['rule_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_rule/' . $ruleItem['rule_id']) }}" title="Delete"
                                                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_rule', ['id' => $ruleItem['act_id'],'rule_id' => $ruleItem['rule_id']]) }}"
                                                                            title="Add Next Rule" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['regulation']))
                                                <div id="collapse_parts_{{ $item['parts_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['regulation'] as $regulationItem)
                                                                <tr>
                                                                    <td>{{$regulationItem['regulation_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $regulationItem['regulation_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-regulation/{{ $regulationItem['regulation_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-regulation/{{ $regulationItem['regulation_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_regulation/' . $regulationItem['regulation_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_regulation', ['id' => $regulationItem['act_id'], 'regulation_id' => $regulationItem['regulation_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Regulation" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['lists']))
                                                <div id="collapse_parts_{{ $item['parts_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['lists'] as $listItem)
                                                                <tr>
                                                                    <td>{{$listItem['list_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $listItem['list_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-list/{{ $listItem['list_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit" class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-list/{{ $listItem['list_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_list/' . $listItem['list_id']) }}" title="Delete"
                                                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        
                                                                        <a href="{{ route('add_below_new_list', ['id' => $listItem['act_id'], 'list_id' => $listItem['list_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next List" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['part']))
                                                <div id="collapse_parts_{{ $item['parts_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['part'] as $partItem)
                                                                <tr>
                                                                    <td>{{$partItem['part_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $partItem['part_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-part/{{ $partItem['part_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit" class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-part/{{ $partItem['part_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_part/' . $partItem['part_id']) }}" title="Delete"
                                                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_part', ['id' => $partItem['act_id'], 'part_id' => $partItem['part_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Part" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['appendices']))
                                                <div id="collapse_parts_{{ $item['parts_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['appendices'] as $appendicesItem)
                                                                <tr>
                                                                    <td>{{$appendicesItem['appendices_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $appendicesItem['appendices_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-appendices/{{ $appendicesItem['appendices_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-appendices/{{ $appendicesItem['appendices_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_appendices/' . $appendicesItem['appendices_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_appendices', ['id' => $appendicesItem['act_id'], 'appendices_id' => $appendicesItem['appendices_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Appendices" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['order']))
                                                <div id="collapse_parts_{{ $item['parts_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['order'] as $orderItem)
                                                                <tr>
                                                                    <td>{{$orderItem['order_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $orderItem['order_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-order/{{ $orderItem['order_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-order/{{ $orderItem['order_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_order/' . $orderItem['order_id']) }}" title="Delete"
                                                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_order', ['id' => $orderItem['act_id'], 'order_id' => $orderItem['order_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Order" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>                        
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['annexure']))
                                                <div id="collapse_parts_{{ $item['parts_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['annexure'] as $annexureItem)
                                                                <tr>
                                                                    <td>{{$annexureItem['annexure_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $annexureItem['annexure_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-annexure/{{ $annexureItem['annexure_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-annexure/{{ $annexureItem['annexure_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_annexure/' . $annexureItem['annexure_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_annexure', ['id' => $annexureItem['act_id'], 'annexure_id' => $annexureItem['annexure_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Annexure" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>                      
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['stschedule']))
                                                <div id="collapse_parts_{{ $item['parts_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['stschedule'] as $stscheduleItem)
                                                                <tr>
                                                                    <td>{{$stscheduleItem['stschedule_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $stscheduleItem['stschedule_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-stschedule/{{ $stscheduleItem['stschedule_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-stschedule/{{ $stscheduleItem['stschedule_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_stschedule/' . $stscheduleItem['stschedule_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_stschedule', ['id' => $stscheduleItem['act_id'], 'stschedule_id' => $stscheduleItem['stschedule_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Schedule" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>                      
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    @if (isset($item['chapter_id']))
                                        <div class="card">
                                            <div class="card-header d-flex">
                                                <div style="width: 90%; text-align: center;">
                                                    <a class="card-link accordion-title" data-toggle="collapse"
                                                        href="#collapse_chapter_{{ $item['chapter_id'] }}">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $item['chapter_title']) !!}
                                                    </a>
                                                </div>
                                                <div
                                                    style="width: 10%; display: flex; justify-content: center; align-items: center;">
                                                    <a href="{{ url('/add_below_new_maintype', ['act_id' => $item['act_id'],'main_id' => $item['main_id'], 'id' => $item['chapter_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                        title="Add Next Main Type" class="px-1">
                                                        <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                    </a>
                                                    <a href="{{ url('/delete_chapter/' . $item['chapter_id']) }}"
                                                        title="Delete" class="px-1"
                                                        onclick="return confirm('Are you sure ?')">
                                                        <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            @if (!empty($item['sections']))
                                                <div id="collapse_chapter_{{ $item['chapter_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['sections'] as $sectionItem)
                                                                <tr>
                                                                    <td>{{$sectionItem['section_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $sectionItem['section_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-section/{{ $sectionItem['section_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-section/{{ $sectionItem['section_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_section/' . $sectionItem['section_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/add_below_new_section', ['act_id' => $sectionItem['act_id'], 'section_id' => $sectionItem['section_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Section" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>     
                                                </div>
                                            @endif
                                            @if (!empty($item['articles']))
                                                <div id="collapse_chapter_{{ $item['chapter_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['articles'] as $articleItem)
                                                                <tr>
                                                                    <td>{{$articleItem['article_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $articleItem['article_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-article/{{ $articleItem['article_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-article/{{ $articleItem['article_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_article/' . $articleItem['article_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_article', ['id' => $articleItem['act_id'], 'article_id' => $articleItem['article_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Article" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['rules']))
                                                <div id="collapse_chapter_{{ $item['chapter_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['rules'] as $ruleItem)
                                                                <tr>
                                                                    <td>{{$ruleItem['rule_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $ruleItem['rule_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-rule/{{ $ruleItem['rule_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit" class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-rule/{{ $ruleItem['rule_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_rule/' . $ruleItem['rule_id']) }}" title="Delete"
                                                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_rule', ['id' => $ruleItem['act_id'],'rule_id' => $ruleItem['rule_id']]) }}"
                                                                            title="Add Next Rule" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['regulation']))
                                                <div id="collapse_chapter_{{ $item['chapter_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['regulation'] as $regulationItem)
                                                                <tr>
                                                                    <td>{{$regulationItem['regulation_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $regulationItem['regulation_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-regulation/{{ $regulationItem['regulation_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-regulation/{{ $regulationItem['regulation_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_regulation/' . $regulationItem['regulation_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_regulation', ['id' => $regulationItem['act_id'], 'regulation_id' => $regulationItem['regulation_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Regulation" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['lists']))
                                                <div id="collapse_chapter_{{ $item['chapter_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['lists'] as $listItem)
                                                                <tr>
                                                                    <td>{{$listItem['list_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $listItem['list_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-list/{{ $listItem['list_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit" class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-list/{{ $listItem['list_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_list/' . $listItem['list_id']) }}" title="Delete"
                                                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        
                                                                        <a href="{{ route('add_below_new_list', ['id' => $listItem['act_id'], 'list_id' => $listItem['list_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next List" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['part']))
                                                <div id="collapse_chapter_{{ $item['chapter_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['part'] as $partItem)
                                                                <tr>
                                                                    <td>{{$partItem['part_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $partItem['part_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-part/{{ $partItem['part_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit" class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-part/{{ $partItem['part_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_part/' . $partItem['part_id']) }}" title="Delete"
                                                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_part', ['id' => $partItem['act_id'], 'part_id' => $partItem['part_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Part" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['appendices']))
                                                <div id="collapse_chapter_{{ $item['chapter_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['appendices'] as $appendicesItem)
                                                                <tr>
                                                                    <td>{{$appendicesItem['appendices_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $appendicesItem['appendices_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-appendices/{{ $appendicesItem['appendices_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-appendices/{{ $appendicesItem['appendices_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_appendices/' . $appendicesItem['appendices_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_appendices', ['id' => $appendicesItem['act_id'], 'appendices_id' => $appendicesItem['appendices_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Appendices" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['order']))
                                                <div id="collapse_chapter_{{ $item['chapter_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['order'] as $orderItem)
                                                                <tr>
                                                                    <td>{{$orderItem['order_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $orderItem['order_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-order/{{ $orderItem['order_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-order/{{ $orderItem['order_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_order/' . $orderItem['order_id']) }}" title="Delete"
                                                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_order', ['id' => $orderItem['act_id'], 'order_id' => $orderItem['order_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Order" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>                        
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['annexure']))
                                                <div id="collapse_chapter_{{ $item['chapter_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['annexure'] as $annexureItem)
                                                                <tr>
                                                                    <td>{{$annexureItem['annexure_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $annexureItem['annexure_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-annexure/{{ $annexureItem['annexure_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-annexure/{{ $annexureItem['annexure_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_annexure/' . $annexureItem['annexure_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_annexure', ['id' => $annexureItem['act_id'], 'annexure_id' => $annexureItem['annexure_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Annexure" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>                      
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['stschedule']))
                                                <div id="collapse_chapter_{{ $item['chapter_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['stschedule'] as $stscheduleItem)
                                                                <tr>
                                                                    <td>{{$stscheduleItem['stschedule_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $stscheduleItem['stschedule_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-stschedule/{{ $stscheduleItem['stschedule_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-stschedule/{{ $stscheduleItem['stschedule_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_stschedule/' . $stscheduleItem['stschedule_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_stschedule', ['id' => $stscheduleItem['act_id'], 'stschedule_id' => $stscheduleItem['stschedule_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Schedule" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>                      
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    @if (isset($item['priliminary_id']))
                                        <div class="card">
                                            <div class="card-header d-flex">
                                                <div style="width: 90%; text-align: center;">
                                                    <a class="card-link accordion-title" data-toggle="collapse"
                                                        href="#collapse_priliminary_{{ $item['priliminary_id'] }}">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $item['priliminary_title']) !!}
                                                    </a>
                                                </div>
                                                <div
                                                    style="width: 10%; display: flex; justify-content: center; align-items: center;">
                                                    <a href="{{ url('/add_below_new_maintype', ['act_id' => $item['act_id'], 'main_id' => $item['main_id'],'id' => $item['priliminary_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                        title="Add Next Main Type" class="px-1">
                                                        <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                    </a>
                                                    <a href="{{ url('/delete_prilimiary/' . $item['priliminary_id']) }}"
                                                        title="Delete" class="px-1"
                                                        onclick="return confirm('Are you sure ?')">
                                                        <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                    </a>  
                                                </div>
                                            </div>
                                            @if (!empty($item['sections']))
                                                <div id="collapse_priliminary_{{ $item['priliminary_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['sections'] as $sectionItem)
                                                                <tr>
                                                                    <td>{{$sectionItem['section_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $sectionItem['section_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-section/{{ $sectionItem['section_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-section/{{ $sectionItem['section_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_section/' . $sectionItem['section_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/add_below_new_section', ['act_id' => $sectionItem['act_id'], 'section_id' => $sectionItem['section_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Section" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>     
                                                </div>
                                            @endif
                                            @if (!empty($item['articles']))
                                                <div id="collapse_priliminary_{{ $item['priliminary_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['articles'] as $articleItem)
                                                                <tr>
                                                                    <td>{{$articleItem['article_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $articleItem['article_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-article/{{ $articleItem['article_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-article/{{ $articleItem['article_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_article/' . $articleItem['article_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_article', ['id' => $articleItem['act_id'], 'article_id' => $articleItem['article_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Article" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['rules']))
                                                <div id="collapse_priliminary_{{ $item['priliminary_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['rules'] as $ruleItem)
                                                                <tr>
                                                                    <td>{{$ruleItem['rule_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $ruleItem['rule_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-rule/{{ $ruleItem['rule_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit" class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-rule/{{ $ruleItem['rule_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_rule/' . $ruleItem['rule_id']) }}" title="Delete"
                                                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_rule', ['id' => $ruleItem['act_id'],'rule_id' => $ruleItem['rule_id']]) }}"
                                                                            title="Add Next Rule" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['regulation']))
                                                <div id="collapse_priliminary_{{ $item['priliminary_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['regulation'] as $regulationItem)
                                                                <tr>
                                                                    <td>{{$regulationItem['regulation_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $regulationItem['regulation_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-regulation/{{ $regulationItem['regulation_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-regulation/{{ $regulationItem['regulation_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_regulation/' . $regulationItem['regulation_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_regulation', ['id' => $regulationItem['act_id'], 'regulation_id' => $regulationItem['regulation_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Regulation" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['lists']))
                                                <div id="collapse_priliminary_{{ $item['priliminary_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['lists'] as $listItem)
                                                                <tr>
                                                                    <td>{{$listItem['list_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $listItem['list_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-list/{{ $listItem['list_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit" class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-list/{{ $listItem['list_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_list/' . $listItem['list_id']) }}" title="Delete"
                                                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        
                                                                        <a href="{{ route('add_below_new_list', ['id' => $listItem['act_id'], 'list_id' => $listItem['list_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next List" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['part']))
                                                <div id="collapse_priliminary_{{ $item['priliminary_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['part'] as $partItem)
                                                                <tr>
                                                                    <td>{{$partItem['part_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $partItem['part_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-part/{{ $partItem['part_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit" class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-part/{{ $partItem['part_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_part/' . $partItem['part_id']) }}" title="Delete"
                                                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_part', ['id' => $partItem['act_id'], 'part_id' => $partItem['part_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Part" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['appendices']))
                                                <div id="collapse_priliminary_{{ $item['priliminary_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['appendices'] as $appendicesItem)
                                                                <tr>
                                                                    <td>{{$appendicesItem['appendices_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $appendicesItem['appendices_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-appendices/{{ $appendicesItem['appendices_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-appendices/{{ $appendicesItem['appendices_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_appendices/' . $appendicesItem['appendices_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_appendices', ['id' => $appendicesItem['act_id'], 'appendices_id' => $appendicesItem['appendices_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Appendices" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['order']))
                                                <div id="collapse_priliminary_{{ $item['priliminary_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['order'] as $orderItem)
                                                                <tr>
                                                                    <td>{{$orderItem['order_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $orderItem['order_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-order/{{ $orderItem['order_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-order/{{ $orderItem['order_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_order/' . $orderItem['order_id']) }}" title="Delete"
                                                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_order', ['id' => $orderItem['act_id'], 'order_id' => $orderItem['order_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Order" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>                        
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['annexure']))
                                                <div id="collapse_priliminary_{{ $item['priliminary_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['annexure'] as $annexureItem)
                                                                <tr>
                                                                    <td>{{$annexureItem['annexure_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $annexureItem['annexure_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-annexure/{{ $annexureItem['annexure_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-annexure/{{ $annexureItem['annexure_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_annexure/' . $annexureItem['annexure_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_annexure', ['id' => $annexureItem['act_id'], 'annexure_id' => $annexureItem['annexure_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Annexure" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>                      
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['stschedule']))
                                                <div id="collapse_priliminary_{{ $item['priliminary_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['stschedule'] as $stscheduleItem)
                                                                <tr>
                                                                    <td>{{$stscheduleItem['stschedule_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $stscheduleItem['stschedule_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-stschedule/{{ $stscheduleItem['stschedule_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-stschedule/{{ $stscheduleItem['stschedule_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_stschedule/' . $stscheduleItem['stschedule_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_stschedule', ['id' => $stscheduleItem['act_id'], 'stschedule_id' => $stscheduleItem['stschedule_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Schedule" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>                      
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    @if (isset($item['schedule_id']))
                                        <div class="card">
                                            <div class="card-header d-flex">
                                                <div style="width: 90%; text-align: center;">
                                                    <a class="card-link accordion-title" data-toggle="collapse"
                                                        href="#collapse_schedule_{{ $item['schedule_id'] }}">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $item['schedule_title']) !!}
                                                    </a>
                                                </div>
                                                <div
                                                    style="width: 10%; display: flex; justify-content: center; align-items: center;">
                                                    <a href="{{ url('/add_below_new_maintype', ['act_id' => $item['act_id'], 'main_id' => $item['main_id'], 'id' => $item['schedule_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                        title="Add Next Main Type" class="px-1">
                                                        <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                    </a>
                                                    <a href="{{ url('/delete_schedule/' . $item['schedule_id']) }}"
                                                        title="Delete" class="px-1"
                                                        onclick="return confirm('Are you sure ?')">
                                                        <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            @if (!empty($item['sections']))
                                                <div id="collapse_schedule_{{ $item['schedule_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['sections'] as $sectionItem)
                                                                <tr>
                                                                    <td>{{$sectionItem['section_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $sectionItem['section_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-section/{{ $sectionItem['section_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-section/{{ $sectionItem['section_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_section/' . $sectionItem['section_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/add_below_new_section', ['act_id' => $sectionItem['act_id'], 'section_id' => $sectionItem['section_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Section" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>     
                                                </div>
                                            @endif
                                            @if (!empty($item['articles']))
                                                <div id="collapse_schedule_{{ $item['schedule_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['articles'] as $articleItem)
                                                                <tr>
                                                                    <td>{{$articleItem['article_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $articleItem['article_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-article/{{ $articleItem['article_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-article/{{ $articleItem['article_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_article/' . $articleItem['article_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_article', ['id' => $articleItem['act_id'], 'article_id' => $articleItem['article_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Article" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['rules']))
                                                <div id="collapse_schedule_{{ $item['schedule_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['rules'] as $ruleItem)
                                                                <tr>
                                                                    <td>{{$ruleItem['rule_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $ruleItem['rule_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-rule/{{ $ruleItem['rule_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit" class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-rule/{{ $ruleItem['rule_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_rule/' . $ruleItem['rule_id']) }}" title="Delete"
                                                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_rule', ['id' => $ruleItem['act_id'],'rule_id' => $ruleItem['rule_id']]) }}"
                                                                            title="Add Next Rule" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['regulation']))
                                                <div id="collapse_schedule_{{ $item['schedule_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['regulation'] as $regulationItem)
                                                                <tr>
                                                                    <td>{{$regulationItem['regulation_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $regulationItem['regulation_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-regulation/{{ $regulationItem['regulation_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-regulation/{{ $regulationItem['regulation_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_regulation/' . $regulationItem['regulation_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_regulation', ['id' => $regulationItem['act_id'], 'regulation_id' => $regulationItem['regulation_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Regulation" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['lists']))
                                                <div id="collapse_schedule_{{ $item['schedule_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['lists'] as $listItem)
                                                                <tr>
                                                                    <td>{{$listItem['list_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $listItem['list_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-list/{{ $listItem['list_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit" class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-list/{{ $listItem['list_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_list/' . $listItem['list_id']) }}" title="Delete"
                                                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        
                                                                        <a href="{{ route('add_below_new_list', ['id' => $listItem['act_id'], 'list_id' => $listItem['list_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next List" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['part']))
                                                <div id="collapse_schedule_{{ $item['schedule_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['part'] as $partItem)
                                                                <tr>
                                                                    <td>{{$partItem['part_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $partItem['part_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-part/{{ $partItem['part_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit" class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-part/{{ $partItem['part_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_part/' . $partItem['part_id']) }}" title="Delete"
                                                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_part', ['id' => $partItem['act_id'], 'part_id' => $partItem['part_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Part" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['appendices']))
                                                <div id="collapse_schedule_{{ $item['schedule_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['appendices'] as $appendicesItem)
                                                                <tr>
                                                                    <td>{{$appendicesItem['appendices_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $appendicesItem['appendices_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-appendices/{{ $appendicesItem['appendices_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-appendices/{{ $appendicesItem['appendices_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_appendices/' . $appendicesItem['appendices_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_appendices', ['id' => $appendicesItem['act_id'], 'appendices_id' => $appendicesItem['appendices_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Appendices" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['order']))
                                                <div id="collapse_schedule_{{ $item['schedule_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['order'] as $orderItem)
                                                                <tr>
                                                                    <td>{{$orderItem['order_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $orderItem['order_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-order/{{ $orderItem['order_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-order/{{ $orderItem['order_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_order/' . $orderItem['order_id']) }}" title="Delete"
                                                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_order', ['id' => $orderItem['act_id'], 'order_id' => $orderItem['order_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Order" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>                        
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['annexure']))
                                                <div id="collapse_schedule_{{ $item['schedule_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['annexure'] as $annexureItem)
                                                                <tr>
                                                                    <td>{{$annexureItem['annexure_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $annexureItem['annexure_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-annexure/{{ $annexureItem['annexure_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-annexure/{{ $annexureItem['annexure_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_annexure/' . $annexureItem['annexure_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_annexure', ['id' => $annexureItem['act_id'], 'annexure_id' => $annexureItem['annexure_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Annexure" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>                      
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['stschedule']))
                                                <div id="collapse_schedule_{{ $item['schedule_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['stschedule'] as $stscheduleItem)
                                                                <tr>
                                                                    <td>{{$stscheduleItem['stschedule_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $stscheduleItem['stschedule_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-stschedule/{{ $stscheduleItem['stschedule_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-stschedule/{{ $stscheduleItem['stschedule_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_stschedule/' . $stscheduleItem['stschedule_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_stschedule', ['id' => $stscheduleItem['act_id'], 'stschedule_id' => $stscheduleItem['stschedule_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Schedule" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>                      
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    @if (isset($item['appendix_id']))
                                        <div class="card">
                                            <div class="card-header d-flex">
                                                <div style="width: 90%; text-align: center;">
                                                    <a class="card-link accordion-title" data-toggle="collapse"
                                                        href="#collapse_appendix_{{ $item['appendix_id'] }}">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $item['appendix_title']) !!}
                                                    </a>
                                                </div>
                                                <div
                                                    style="width: 10%; display: flex; justify-content: center; align-items: center;">
                                                    <a href="{{ url('/add_below_new_maintype', ['act_id' => $item['act_id'],'main_id' => $item['main_id'], 'id' => $item['appendix_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                        title="Add Next Main Type" class="px-1">
                                                        <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                    </a>
                                                    <a href="{{ url('/delete_appendix/' . $item['appendix_id']) }}"
                                                        title="Delete" class="px-1"
                                                        onclick="return confirm('Are you sure ?')">
                                                        <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            @if (!empty($item['sections']))
                                                <div id="collapse_appendix_{{ $item['appendix_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['sections'] as $sectionItem)
                                                                <tr>
                                                                    <td>{{$sectionItem['section_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $sectionItem['section_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-section/{{ $sectionItem['section_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-section/{{ $sectionItem['section_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_section/' . $sectionItem['section_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/add_below_new_section', ['act_id' => $sectionItem['act_id'], 'section_id' => $sectionItem['section_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Section" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>     
                                                </div>
                                            @endif
                                            @if (!empty($item['articles']))
                                                <div id="collapse_appendix_{{ $item['appendix_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['articles'] as $articleItem)
                                                                <tr>
                                                                    <td>{{$articleItem['article_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $articleItem['article_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-article/{{ $articleItem['article_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-article/{{ $articleItem['article_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_article/' . $articleItem['article_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_article', ['id' => $articleItem['act_id'], 'article_id' => $articleItem['article_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Article" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['rules']))
                                                <div id="collapse_appendix_{{ $item['appendix_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['rules'] as $ruleItem)
                                                                <tr>
                                                                    <td>{{$ruleItem['rule_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $ruleItem['rule_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-rule/{{ $ruleItem['rule_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit" class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-rule/{{ $ruleItem['rule_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_rule/' . $ruleItem['rule_id']) }}" title="Delete"
                                                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_rule', ['id' => $ruleItem['act_id'],'rule_id' => $ruleItem['rule_id']]) }}"
                                                                            title="Add Next Rule" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['regulation']))
                                                <div id="collapse_appendix_{{ $item['appendix_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['regulation'] as $regulationItem)
                                                                <tr>
                                                                    <td>{{$regulationItem['regulation_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $regulationItem['regulation_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-regulation/{{ $regulationItem['regulation_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-regulation/{{ $regulationItem['regulation_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_regulation/' . $regulationItem['regulation_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_regulation', ['id' => $regulationItem['act_id'], 'regulation_id' => $regulationItem['regulation_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Regulation" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['lists']))
                                                <div id="collapse_appendix_{{ $item['appendix_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['lists'] as $listItem)
                                                                <tr>
                                                                    <td>{{$listItem['list_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $listItem['list_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-list/{{ $listItem['list_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit" class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-list/{{ $listItem['list_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_list/' . $listItem['list_id']) }}" title="Delete"
                                                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        
                                                                        <a href="{{ route('add_below_new_list', ['id' => $listItem['act_id'], 'list_id' => $listItem['list_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next List" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['part']))
                                                <div id="collapse_appendix_{{ $item['appendix_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['part'] as $partItem)
                                                                <tr>
                                                                    <td>{{$partItem['part_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $partItem['part_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-part/{{ $partItem['part_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit" class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-part/{{ $partItem['part_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_part/' . $partItem['part_id']) }}" title="Delete"
                                                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_part', ['id' => $partItem['act_id'], 'part_id' => $partItem['part_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Part" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['appendices']))
                                                <div id="collapse_appendix_{{ $item['appendix_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['appendices'] as $appendicesItem)
                                                                <tr>
                                                                    <td>{{$appendicesItem['appendices_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $appendicesItem['appendices_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-appendices/{{ $appendicesItem['appendices_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-appendices/{{ $appendicesItem['appendices_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_appendices/' . $appendicesItem['appendices_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_appendices', ['id' => $appendicesItem['act_id'], 'appendices_id' => $appendicesItem['appendices_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Appendices" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['order']))
                                                <div id="collapse_appendix_{{ $item['appendix_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['order'] as $orderItem)
                                                                <tr>
                                                                    <td>{{$orderItem['order_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $orderItem['order_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-order/{{ $orderItem['order_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-order/{{ $orderItem['order_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_order/' . $orderItem['order_id']) }}" title="Delete"
                                                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_order', ['id' => $orderItem['act_id'], 'order_id' => $orderItem['order_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Order" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>                        
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['annexure']))
                                                <div id="collapse_appendix_{{ $item['appendix_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['annexure'] as $annexureItem)
                                                                <tr>
                                                                    <td>{{$annexureItem['annexure_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $annexureItem['annexure_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-annexure/{{ $annexureItem['annexure_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-annexure/{{ $annexureItem['annexure_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_annexure/' . $annexureItem['annexure_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_annexure', ['id' => $annexureItem['act_id'], 'annexure_id' => $annexureItem['annexure_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Annexure" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>                      
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['stschedule']))
                                                <div id="collapse_appendix_{{ $item['appendix_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['stschedule'] as $stscheduleItem)
                                                                <tr>
                                                                    <td>{{$stscheduleItem['stschedule_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $stscheduleItem['stschedule_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-stschedule/{{ $stscheduleItem['stschedule_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-stschedule/{{ $stscheduleItem['stschedule_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_stschedule/' . $stscheduleItem['stschedule_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_stschedule', ['id' => $stscheduleItem['act_id'], 'stschedule_id' => $stscheduleItem['stschedule_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Schedule" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>                      
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    @if (isset($item['main_order_id']))
                                        <div class="card">
                                            <div class="card-header d-flex">
                                                <div style="width: 90%; text-align: center;">
                                                    <a class="card-link accordion-title" data-toggle="collapse"
                                                        href="#collapse_main_order_{{ $item['main_order_id'] }}">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $item['main_order_title']) !!}
                                                    </a>
                                                </div>
                                                <div
                                                    style="width: 10%; display: flex; justify-content: center; align-items: center;">
                                                    <a href="{{ url('/add_below_new_maintype', ['act_id' => $item['act_id'],'main_id' => $item['main_id'], 'id' => $item['main_order_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                        title="Add Next Main Type" class="px-1">
                                                        <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                    </a>
                                                    <a href="{{ url('/delete_main_order/' . $item['main_order_id']) }}"
                                                        title="Delete" class="px-1"
                                                        onclick="return confirm('Are you sure ?')">
                                                        <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            @if (!empty($item['sections']))
                                                <div id="collapse_main_order_{{ $item['main_order_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['sections'] as $sectionItem)
                                                                <tr>
                                                                    <td>{{$sectionItem['section_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $sectionItem['section_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-section/{{ $sectionItem['section_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-section/{{ $sectionItem['section_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_section/' . $sectionItem['section_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/add_below_new_section', ['act_id' => $sectionItem['act_id'], 'section_id' => $sectionItem['section_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Section" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>     
                                                </div>
                                            @endif
                                            @if (!empty($item['articles']))
                                                <div id="collapse_main_order_{{ $item['main_order_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['articles'] as $articleItem)
                                                                <tr>
                                                                    <td>{{$articleItem['article_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $articleItem['article_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-article/{{ $articleItem['article_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-article/{{ $articleItem['article_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_article/' . $articleItem['article_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_article', ['id' => $articleItem['act_id'], 'article_id' => $articleItem['article_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Article" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['rules']))
                                                <div id="collapse_main_order_{{ $item['main_order_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['rules'] as $ruleItem)
                                                                <tr>
                                                                    <td>{{$ruleItem['rule_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $ruleItem['rule_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-rule/{{ $ruleItem['rule_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit" class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-rule/{{ $ruleItem['rule_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_rule/' . $ruleItem['rule_id']) }}" title="Delete"
                                                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_rule', ['id' => $ruleItem['act_id'],'rule_id' => $ruleItem['rule_id']]) }}"
                                                                            title="Add Next Rule" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['regulation']))
                                                <div id="collapse_main_order_{{ $item['main_order_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['regulation'] as $regulationItem)
                                                                <tr>
                                                                    <td>{{$regulationItem['regulation_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $regulationItem['regulation_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-regulation/{{ $regulationItem['regulation_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-regulation/{{ $regulationItem['regulation_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_regulation/' . $regulationItem['regulation_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_regulation', ['id' => $regulationItem['act_id'], 'regulation_id' => $regulationItem['regulation_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Regulation" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['lists']))
                                                <div id="collapse_main_order_{{ $item['main_order_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['lists'] as $listItem)
                                                                <tr>
                                                                    <td>{{$listItem['list_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $listItem['list_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-list/{{ $listItem['list_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit" class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-list/{{ $listItem['list_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_list/' . $listItem['list_id']) }}" title="Delete"
                                                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        
                                                                        <a href="{{ route('add_below_new_list', ['id' => $listItem['act_id'], 'list_id' => $listItem['list_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next List" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['part']))
                                                <div id="collapse_main_order_{{ $item['main_order_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['part'] as $partItem)
                                                                <tr>
                                                                    <td>{{$partItem['part_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $partItem['part_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-part/{{ $partItem['part_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit" class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-part/{{ $partItem['part_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_part/' . $partItem['part_id']) }}" title="Delete"
                                                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_part', ['id' => $partItem['act_id'], 'part_id' => $partItem['part_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Part" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['appendices']))
                                                <div id="collapse_main_order_{{ $item['main_order_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['appendices'] as $appendicesItem)
                                                                <tr>
                                                                    <td>{{$appendicesItem['appendices_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $appendicesItem['appendices_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-appendices/{{ $appendicesItem['appendices_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-appendices/{{ $appendicesItem['appendices_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_appendices/' . $appendicesItem['appendices_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_appendices', ['id' => $appendicesItem['act_id'], 'appendices_id' => $appendicesItem['appendices_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Appendices" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['order']))
                                                <div id="collapse_main_order_{{ $item['main_order_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['order'] as $orderItem)
                                                                <tr>
                                                                    <td>{{$orderItem['order_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $orderItem['order_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-order/{{ $orderItem['order_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-order/{{ $orderItem['order_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_order/' . $orderItem['order_id']) }}" title="Delete"
                                                                            class="px-1" onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_order', ['id' => $orderItem['act_id'], 'order_id' => $orderItem['order_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Order" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>                        
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['annexure']))
                                                <div id="collapse_main_order_{{ $item['main_order_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['annexure'] as $annexureItem)
                                                                <tr>
                                                                    <td>{{$annexureItem['annexure_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $annexureItem['annexure_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-annexure/{{ $annexureItem['annexure_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-annexure/{{ $annexureItem['annexure_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_annexure/' . $annexureItem['annexure_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_annexure', ['id' => $annexureItem['act_id'], 'annexure_id' => $annexureItem['annexure_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Annexure" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>                      
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @if (!empty($item['stschedule']))
                                                <div id="collapse_main_order_{{ $item['main_order_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['stschedule'] as $stscheduleItem)
                                                                <tr>
                                                                    <td>{{$stscheduleItem['stschedule_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $stscheduleItem['stschedule_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit-stschedule/{{ $stscheduleItem['stschedule_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view-sub-stschedule/{{ $stscheduleItem['stschedule_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_stschedule/' . $stscheduleItem['stschedule_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ route('add_below_new_stschedule', ['id' => $stscheduleItem['act_id'], 'stschedule_id' => $stscheduleItem['stschedule_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                            title="Add Next Schedule" class="px-1">
                                                                            <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                                        </a>                      
                                                                    </td>
                                                                </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        {{ $paginatedCollection->links() }}
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

    $(document).ready(function() {
        CKEDITOR.replace('act_description', {
            toolbar: 'Full'
        });
        CKEDITOR.replace('act_no', {
            toolbar: 'Full'
        });
    });

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

      
     
    </script>
    <script>
        let table = new DataTable('#myTable', {
            sorting: false,
            paging: false,
            info: false
        });
    </script>
@endsection
