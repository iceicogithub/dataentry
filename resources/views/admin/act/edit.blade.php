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
                <div class="card p-5">
                    <div class="col-md-12">
                        <div class="form-group form-default w-50">
                            <label class="float-label font-weight-bold"> Act : </label>
                            <span>The Coastal Aquaculture Authority (Amendment) Act, 2023</span>
                        </div>

                        <div class="form-group form-default">
                            <label class="float-label font-weight-bold"> Act Description<span class="text-danger">*</span></label>
                            <textarea name="act" id="act" cols="30" rows="10"></textarea>
                        </div>
                        <button class="btn btn-success">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script src="https://cdn.ckeditor.com/4.16.2/full-all/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        CKEDITOR.replace('act');
    </script>
@endsection
