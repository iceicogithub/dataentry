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
                        <a href="{{ Route('act') }}"><button class="btn btn-success">Back</button></a>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content mt-3">
        <div class="row">
            <div class="col-lg-12">
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
                        <div class="col-md-2 col-sm-12 col-lg-2 border p-0 p-2 pl-3 font-weight-bold">Download</div>
                        <div class="col-md-4 col-sm-12 col-lg-4 border p-0 p-2 pl-3"><a href="" class="text-primary">click here</a> to download original document</div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 col-sm-12 col-lg-2 border p-0 p-2 pl-3 font-weight-bold">Download PDF</div>
                        <div class="col-md-4 col-sm-12 col-lg-4 border p-0 p-2 pl-3"><a href="{{ route('export-pdf', ['id' => $item]) }}" class="text-primary">click here</a> Download PDF</div>
                        <div class="col-md-2 col-sm-12 col-lg-2 border p-0 p-2 pl-3 font-weight-bold">Created At</div>
                        <div class="col-md-4 col-sm-12 col-lg-4 border p-0 p-2 pl-3">{{$item->created_at}}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 col-sm-12 col-lg-2 border p-0 p-2 pl-3 font-weight-bold">Status</div>
                        <div class="col-md-4 col-sm-12 col-lg-4 border p-0 p-2 pl-3">Normal</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
