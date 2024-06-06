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
                    <h1>Rule : {{ $newRule->new_rule_title }}</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <a href="/add-rule/{{ $newRule->new_rule_id }}" class="mr-2"><button class="btn btn-success">Add
                                Index</button></a>
                                <a href="/get_rule/{{ $newRule->act_id }}?page={{ $currentPage }}">
                                    <button class="btn btn-danger">Back</button>
                                </a>
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
                <form id="form" action="/update_new_rule/{{ $newRule->new_rule_id }}" method="post"
                    enctype="multipart/form-data" class="form form-horizontal">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-default">
                                <label class="float-label">Rule<span class="text-danger">*</span></label>
                                <input type="text" name="new_rule_title" value="{{ $newRule->new_rule_title }}"
                                    class="form-control mb-3" placeholder="Enter Act Title">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-default">
                                <label class="float-label">Rule NO.<span class="text-danger">*</span></label>
                                <textarea name="new_rule_no" class="form-control mb-3" placeholder="Enter Act No" id="act_no" cols="30"
                                    rows="3">{{ $newRule->new_rule_no }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-default">
                                <label class="float-label">Enactment Date<span class="text-danger">*</span></label>
                                <input type="date" name="enactment_date" value="{{ $newRule->enactment_date }}"
                                    class="form-control mb-3">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-default">
                                <label class="float-label">Enforcement Date<span class="text-danger">*</span></label>
                                <input type="date" name="enforcement_date" value="{{ $newRule->enforcement_date }}"
                                    class="form-control mb-3">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-default">
                                <label class="float-label">Rule Date<span class="text-danger">*</span></label>
                                <input type="text" name="new_rule_date" value="{{ $newRule->new_rule_date }}"
                                    class="form-control mb-3" placeholder="Enter Act Date">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-default">
                                <label class="float-label"> PREAMBLE <span class="text-danger">*</span></label>

                                <textarea name="new_rule_description" class="form-control mb-3" placeholder="Enter Act Description" id="act_description"
                                    cols="30" rows="3">{{ $newRule->new_rule_description }}</textarea>
                            </div>
                        </div>
                        @if ($newRule->new_rule_footnote_description)
                                <div class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12">
                                    <label class="float-label">
                                        Footnote
                                        <span class="pl-2">
                                            <button type="button" class="btn btn-sm social facebook p-0 add-footnote">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </span>
                                    </label>
                                    <div class="show-footnote" style="">
                                        <div class="footnote-entry">
                                            <textarea type="text" name="new_rule_footnote_description" id="ck"
                                                class="form-control ckeditor-replace footnote" placeholder="Enter Footnote Description">
                                                {{$newRule->new_rule_footnote_description }}</textarea>
                                        </div>

                                    </div>
                                </div>
                        @else

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
                        @endif

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
                                        <div class="card">
                                            <div class="card-header d-flex">
                                                <div style="width: 90%; text-align: center;">
                                                    <a class="card-link accordion-title" data-toggle="collapse"
                                                        href="#collapse_chapter_{{ $item['rule_main_id'] }}">
                                                        {!! preg_replace('/[0-9\[\]\.]/', '', $item['rule_main_title']) !!}
                                                    </a>
                                                </div>
                                                <div
                                                    style="width: 10%; display: flex; justify-content: center; align-items: center;">
                                                    <a href="{{ url('/add_below_new_rule_maintype', ['new_rule_id' => $item['new_rule_id'],'id' => $item['rule_main_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                        title="Add Next Main Type" class="px-1">
                                                        <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                    </a>
                                                    <a href="{{ url('/delete_rule_maintype/' . $item['rule_main_id']) }}"
                                                        title="Delete" class="px-1"
                                                        onclick="return confirm('Are you sure ?')">
                                                        <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            @if (!empty($item['ruletbl']))
                                                <div id="collapse_chapter_{{ $item['rule_main_id'] }}" class="collapse"
                                                    data-parent="#accordion">
                                                    <table class="table table-bordered text-center" id="">
                                                        <tbody>
                                                            @foreach ($item['ruletbl'] as $value)
                                                                <tr>
                                                                    <td>{{$value['rules_no']}}</td>
                                                                    <td>{!! preg_replace('/[0-9\[\]\.]/', '', $value['rules_title']) !!}</td>
                                                                    <td>
                                                                        <a href="/edit_ruleTable/{{ $value['rules_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="Edit"
                                                                            class="px-1">
                                                                            <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="/view_rule_sub/{{ $value['rules_id'] }}?page={{ $paginatedCollection->currentPage() }}" title="View"
                                                                            class="px-1">
                                                                            <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/delete_rulestbl/' . $value['rules_id']) }}"
                                                                            title="Delete" class="px-1"
                                                                            onclick="return confirm('Are you sure ?')">
                                                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                                        </a>
                                                                        <a href="{{ url('/add_below_new_ruletbl', ['rule_main_id' => $value['rule_main_id'], 'rules_id' => $value['rules_id']]) }}?page={{ $paginatedCollection->currentPage() }}"
                                                                             class="px-1">
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
                                @endforeach
                            </div>
                        </div>
                        {{ $paginatedCollection->links() }}
           </div>
        </div> 

    </div>


    <script src="https://cdn.ckeditor.com/4.16.2/full-all/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        CKEDITOR.replace('act_no');
        CKEDITOR.replace('act_description');
        CKEDITOR.replace('act_footnote');
        CKEDITOR.replace('ck');
       

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
                                            <textarea type="text" name="new_rule_footnote_description" class="form-control ckeditor-replace footnote"></textarea>
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
