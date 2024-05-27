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
            margin-block: 0;
            min-height: 100%;
        }

        .pagination-links {
            margin-top: 20px;
            /* Adjust margin as needed */
            text-align: right;
            /* Center the pagination links horizontally */
        }

        /* Style the pagination links */
        .pagination-links ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .pagination-links ul li {
            display: inline-block;
            margin-right: 5px;
            /* Adjust spacing between pagination items */
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
            font-size: 12px;
            /* Small size font */
            padding: 5px;
            /* Adjust padding */
        }

        .pagination-links ul li.prev a,
        .pagination-links ul li.next a {
            padding: 5px;
            /* Adjust padding */
        }

        .pagination-links ul li.prev.disabled,
        .pagination-links ul li.next.disabled {
            pointer-events: none;
            /* Disable clicking on disabled arrows */
            opacity: 0.5;
            /* Reduce opacity of disabled arrows */
        }

        .pagination-links .hidden {
            text-align: left !important;
        }

        .pagination-links .w-5 {
            display: none;
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
                        <a href="{{ route('act', ['page' => $currentPage]) }}"><button class="btn btn-success">Back</button></a>
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
                            <div class="col-md-2 col-sm-12 col-lg-2 border p-0 p-2 pl-3 font-weight-bold">Document Name
                            </div>
                            <div class="col-md-10 col-sm-12 col-lg-10 border p-0 p-2 pl-3">{{ $item->act_title }}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 col-sm-12 col-lg-2 border p-0 p-2 pl-3 font-weight-bold">Document Type
                            </div>
                            <div class="col-md-4 col-sm-12 col-lg-4 border p-0 p-2 pl-3">ACT</div>
                            <div class="col-md-2 col-sm-12 col-lg-2 border p-0 p-2 pl-3 font-weight-bold">State</div>
                            <div class="col-md-4 col-sm-12 col-lg-4 border p-0 p-2 pl-3">
                                {{ $item->state->name ?? 'Not defined' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 col-sm-12 col-lg-2 border p-0 p-2 pl-3 font-weight-bold">Category</div>
                            <div class="col-md-4 col-sm-12 col-lg-4 border p-0 p-2 pl-3 text-capitalize">
                                {{ $item->CategoryModel->category }}</div>
                            <div class="col-md-2 col-sm-12 col-lg-2 border p-0 p-2 pl-3 font-weight-bold">Download PDF</div>
                            <div class="col-md-4 col-sm-12 col-lg-4 border p-0 p-2 pl-3"><a
                                    href="{{ route('export-pdf', ['id' => $item]) }}" class="text-primary">click here</a>
                                Download PDF</div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 col-sm-12 col-lg-2 border p-0 p-2 pl-3 font-weight-bold">Created At</div>
                            <div class="col-md-4 col-sm-12 col-lg-4 border p-0 p-2 pl-3">{{ $item->created_at }}</div>
                            <div class="col-md-2 col-sm-12 col-lg-2 border p-0 p-2 pl-3 font-weight-bold">Status</div>
                            <div class="col-md-4 col-sm-12 col-lg-4 border p-0 p-2 pl-3">Normal</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
