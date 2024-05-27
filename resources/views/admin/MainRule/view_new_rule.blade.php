@extends('admin.layout.main')

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
                        <a href="{{ url('/get_rule/' . $newRule->act_id . '?perPage=10&page=' . $currentPage) }}"><button class="btn btn-danger">Back</button></a>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content mt-3">
        <div class="row">
            <div class="col-sm-12">
               
                    <div class="card p-5">
                        <div class="row">
                            <div class="col-md-2 col-sm-12 col-lg-2 border p-0 p-2 pl-3 font-weight-bold">Document Name
                            </div>
                            <div class="col-md-10 col-sm-12 col-lg-10 border p-0 p-2 pl-3">{{ $newRule->new_rule_title }}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 col-sm-12 col-lg-2 border p-0 p-2 pl-3 font-weight-bold">Document Type
                            </div>
                            <div class="col-md-10 col-sm-12 col-lg-10 border p-0 p-2 pl-3">Rule</div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 col-sm-12 col-lg-2 border p-0 p-2 pl-3 font-weight-bold">Download PDF</div>
                            <div class="col-md-10 col-sm-12 col-lg-10 border p-0 p-2 pl-3"><a
                                    href="{{ route('export_rule_pdf', ['id' => $newRule->new_rule_id]) }}" class="text-primary">click here</a>
                                Download PDF</div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 col-sm-12 col-lg-2 border p-0 p-2 pl-3 font-weight-bold">Created At</div>
                            <div class="col-md-10 col-sm-12 col-lg-10 border p-0 p-2 pl-3">{{ $newRule->created_at }}</div>
                        </div>
                    </div>  
            </div>
        </div>
    </div>
@endsection
