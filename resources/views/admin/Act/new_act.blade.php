@extends('admin.layout.main')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/css/select2.min.css" rel="stylesheet" />

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
                    <form id="form" action="/store_new_act" method="post" enctype="multipart/form-data"
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role" class=" form-control-label">Select Category<span
                                            class="text-danger">*</span></label>
                                    <select class="select form-control text-capitalize category" name="category_id" required>
                                        <option value="" selected disabled>Select Category</option>
                                        @foreach ($category as $value)
                                            <option value="{{ $value->category_id }}" class="text-capitalize">
                                                {{ $value->category }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 state" style="display: none;">
                                <div class="form-group">
                                    <label for="state" class=" form-control-label">Select state<span
                                            class="text-danger">*</span></label>
                                    <select class="select form-control text-capitalize" name="state_id" >
                                        <option value="" selected disabled>Select State</option>
                                        @foreach ($states as $item)
                                            <option value="{{ $item->state_id }}" class="text-capitalize">
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group form-default">
                                    <label class="float-label"> Legislation <span class="text-danger">*</span></label>
                                    <input type="text" name="legislation_name" class="form-control mb-3"
                                        placeholder="Enter legislation Title" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class=" form-control-label">Select<span class="text-danger">*</span></label>
                                    <div class="checkbox-list">
                                        @foreach ($actSummary as $item)
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="act_summary_id[]" value="{{ $item->id }}">
                                                {{ $item->title }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/js/select2.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var jq = $.noConflict();
    jq(document).ready(function() {

        jq('.select').select2({
            placeholder: 'Select States',
            allowClear: true, // option to clear selected items
            width: '100%' // adjust the width as needed
        });


        jq(document).on('change', '.category', function() {
            if (jq(this).val() === '2') {
                jq('.state').show();
            } else {
                jq('.state').hide();
            }
        });

    });
</script>
@endsection