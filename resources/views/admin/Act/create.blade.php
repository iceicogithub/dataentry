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
                        <a href="{{Route('act')}}"><button class="btn btn-success">Back</button></a>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content mt-3">
        <div class="row">
            <div class="col-lg-12">
                <div class="card p-5">
                    <form id="form" action="/store_act" method="post" enctype="multipart/form-data"
                        class="form form-horizontal">
                        @csrf
                        @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                        <div class="row">
                           
                        
                       
                        
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role" class=" form-control-label">Select Category<span
                                            class="text-danger">*</span></label>
                                    <select class="select form-control text-capitalize category" name="category_id">
                                        <option selected disabled>Select Category</option>
                                        @foreach ($category as $value)
                                            <option value="{{ $value->id }}" class="text-capitalize">{{ $value->category }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                           
                            <div class="col-md-6 state" style="display: none;">
                                <div class="form-group">
                                    <label for="state" class=" form-control-label">Select state<span
                                            class="text-danger">*</span></label>
                                    <select class="select form-control text-capitalize" name="state">
                                        <option selected>Select State</option>
                                            <option value="" class="text-capitalize"></option>
                                    </select>
                                </div>
                            </div>
                           
                            
                            <div class="col-md-12">
                                <div class="form-group form-default">
                                    <label class="float-label"> Act <span class="text-danger">*</span></label>
                                    <textarea type="text" name="act" class="form-control"
                                        placeholder="Enter Act">
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role" class=" form-control-label">Select Status<span
                                            class="text-danger">*</span></label>
                                    <select class="select2 form-control " name="general_status_id">
                                        @foreach ($status as $value)
                                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   
<script>
    $(document).ready(function() {
        $('.category').change(function() {
            if ($(this).val() === '2') {
                $('.state').show(); 
            } else {
                $('.state').hide(); 
            }
        });
    });
</script>
@endsection
