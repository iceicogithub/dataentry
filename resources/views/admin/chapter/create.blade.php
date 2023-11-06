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
                        <a href="{{Route('chapter')}}"><button class="btn btn-success">Back</button></a>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content mt-3">
        <div class="row">
            <div class="col-lg-12">
                <div class="card p-5">
                    <form id="form" action="" method="post" enctype="multipart/form-data"
                        class="form form-horizontal">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role" class=" form-control-label">Select Sub-Section<span
                                            class="text-danger">*</span></label>
                                    <select class="select form-control text-capitalize" name="sub_section">
                                        <option selected>Select Sub-Section</option>
                                        {{-- @foreach ($category as $value)
                                            <option value="{{ $value->id }}" class="text-capitalize">{{ $value->category }}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group form-default">
                                    <label class="float-label"> Add Chapter<span class="text-danger">*</span></label>
                                    <textarea type="text" name="chapter" class="form-control">
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
@endsection
