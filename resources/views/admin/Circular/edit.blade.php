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
                        <a href="{{ route('get_circulars', array_merge(['id' => $circular->act_id], ['page' => $currentPage])) }}">
                            <button class="btn btn-success">Back</button>
                        </a></ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content mt-3">
        <div class="row">
            <div class="col-lg-12">
                <div class="card p-5">
                    <form id="form" action="/update_circulars/{{$circular->circulars_id}}" method="post" enctype="multipart/form-data"
                        class="form form-horizontal">
                        @csrf
                        <!-- Your Blade View -->
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
                        <input type="hidden" name="act_id" id="act_id" value="{{$circular->act_id}}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form-default">
                                    <label class="float-label">Add Circular<span class="text-danger">*</span></label>
                                    <input type="text" name="circulars_title" class="form-control mb-3"
                                        placeholder="Enter Circular" required value="{{$circular->circulars_title}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group form-default">
                                    <label class="float-label">Circular NO.<span class="text-danger">*</span></label>
                                    <textarea name="circulars_no" id="circulars_no" class="form-control mb-3" placeholder="Enter Circular No" cols="30"
                                        rows="3">{{$circular->circulars_no}}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group form-default">
                                    <label class="float-label">Ministry<span class="text-danger">*</span></label>
                                    <textarea type="text" name="ministry" value=""
                                        class="form-control mb-3" id="ministry">{{$circular->ministry}}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group form-default">
                                    <label class="float-label">Circular Date<span class="text-danger">*</span></label>
                                    <input type="text" name="circulars_date" value="{{$circular->circulars_date}}"
                                        class="form-control mb-3" id="circulars_date" placeholder="Enter Circular Date" >
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn  btn-success">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script src="https://cdn.ckeditor.com/4.16.2/full-all/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>


        CKEDITOR.replace('circulars_no');
        CKEDITOR.replace('ministry');
        
        $(document).ready(function() {
            // for category type
            $(document).on('change', '.category', function() {
                if ($(this).val() === '2') {
                    $('.state').show();
                } else {
                    $('.state').hide();
                }
            });

        });
    </script>
@endsection
