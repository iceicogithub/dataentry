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
                        <a href="{{ Route('section') }}"><button class="btn btn-success">Back</button></a>
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
                            <div class="col-md-12 px-0">
                                <div class="form-group">
                                    <label for="role" class=" form-control-label">Select Act<span
                                            class="text-danger">*</span></label>
                                    <select class="select form-control text-capitalize" name="act">
                                        <option selected>Select Act</option>
                                        {{-- @foreach ($category as $value)
                                            <option value="{{ $value->id }}" class="text-capitalize">{{ $value->category }}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                            </div>
                            <div class="section-set-container col-md-12 px-0">
                                <div class="section-set col-md-12 px-0 mb-2">
                                    <div class="col-md-12 px-0 pb-1">
                                        <div class="float-right">
                                            <button type="button" class="btn btn-sm social facebook p-0 add-section">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm social youtube p-0 remove-section">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="section-container border col-md-12 p-3">
                                        <div class="form-group form-default">
                                            <label class="float-label">Section Title<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Section Title">
                                        </div>
                                        <div class="form-group form-default">
                                            <label class="float-label">Add Section<span class="text-danger">*</span></label>
                                            <textarea type="text" name="section" class="form-control" placeholder="Enter Section"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 px-0 pt-3">
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
@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('section');
    </script>
    <script>
        $(document).ready(function() {
            $(".section-set-container").on("click", ".add-section", function() {
                var newSectionSet = $(".section-set").first().clone();
                $(".section-set-container").append(newSectionSet);
            });

            $(".section-set-container").on("click", ".remove-section", function() {
                var sectionSets = $(".section-set");
                if (sectionSets.length > 1) {
                    $(this).closest(".section-set").remove();
                }
            });
        });
    </script>
@endsection
