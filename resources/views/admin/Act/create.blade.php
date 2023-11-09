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
                    <form id="form" action="/store_act" method="post" enctype="multipart/form-data"
                        class="form form-horizontal">
                        @csrf
                        @if (session('error'))
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
                                            <option value="{{ $value->id }}" class="text-capitalize">
                                                {{ $value->category }}</option>
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
                                        @foreach ($states as $item)
                                            <option value="{{ $item->id }}" class="text-capitalize">
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group form-default">
                                    <label class="float-label"> Act <span class="text-danger">*</span></label>
                                    <textarea type="text" id="act" name="act" class="form-control ckeditor-replace act">
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group form-default">
                                    <label class="float-label"> Chapter <span class="text-danger">*</span></label>
                                    <textarea type="text" id="chapter" name="chapter" class="form-control ckeditor-replace chapter">
                                    </textarea>
                                </div>
                            </div>
                            <div class="section-set-container col-md-12">
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
                                            <textarea type="text" id="section" name="section" class="form-control section-textarea ckeditor-replace section-1"
                                                placeholder="Enter Section"></textarea>
                                        </div>
                                        <div class="form-group form-default fa fa-arrow-circle-o-right px-0 col-md-12">
                                            <label class="float-label">Add Sub-Section
                                                <span class="pl-2">
                                                    <button type="button"
                                                        class="btn btn-sm social facebook p-0 add-sub-section">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </span>
                                            </label>
                                            <div class="show-sub-section d-none">
                                                <input type="text" class="form-control mb-3" placeholder="Enter Title">
                                                <textarea type="text" name="sub_section" class="form-control sub-section-textarea"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group form-default fa fa-arrow-circle-o-right px-0 col-md-12">
                                            <label class="float-label">Add Footnote
                                                <span class="pl-2">
                                                    <button type="button"
                                                        class="btn btn-sm social facebook p-0 add-footnote">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </span>
                                            </label>
                                            <div class="show-footnote d-none">
                                                <input type="text" class="form-control mb-3" placeholder="Enter Title">
                                                <textarea type="text" name="footnote" class="form-control footnote"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group form-default fa fa-arrow-circle-o-right px-0 col-md-12">
                                            <label class="float-label">Add Order
                                                <span class="pl-2">
                                                    <button type="button"
                                                        class="btn btn-sm social facebook p-0 add-order">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </span>
                                            </label>
                                            <div class="show-order d-none">
                                                <input type="text" class="form-control mb-3"
                                                    placeholder="Enter Title">
                                                <textarea type="text" name="order" class="form-control order"></textarea>
                                            </div>
                                        </div>
                                    </div>
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
@section('script')
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.ckeditor-replace').each(function() {
                CKEDITOR.replace(this);
            });
        });
    </script>
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
    <script>
        $(document).ready(function() {
            // sub section
            CKEDITOR.replace('sub-section-1');

            $(".section-set-container").on("click", ".add-sub-section", function() {
                var $showSubSection = $(this).closest('.form-group').find('.show-sub-section');
                $showSubSection.toggleClass('d-none');

                if (!$showSubSection.hasClass('d-none')) {
                    var $subSectionTextarea = $showSubSection.find('.sub-section-textarea');
                    CKEDITOR.replace($subSectionTextarea[0]);
                    $(this).find("i").removeClass("fa-plus").addClass("fa-minus");
                } else {
                    var $subSectionTextarea = $showSubSection.find('.sub-section-textarea');
                    CKEDITOR.instances[$subSectionTextarea[0].name].destroy();
                    $(this).find("i").removeClass("fa-minus").addClass("fa-plus");
                }
            });

            // footnote
            CKEDITOR.replace('footnote-1');

            $(".section-set-container").on("click", ".add-footnote", function() {
                var $showFootnote = $(this).closest('.form-group').find('.show-footnote');
                $showFootnote.toggleClass('d-none');

                if (!$showFootnote.hasClass('d-none')) {
                    var $footnoteTextarea = $showFootnote.find('.footnote');
                    CKEDITOR.replace($footnoteTextarea[0]);
                    $(this).find("i").removeClass("fa-plus").addClass("fa-minus");
                } else {
                    var $footnoteTextarea = $showFootnote.find('.footnote');
                    CKEDITOR.instances[$footnoteTextarea[0].name].destroy();
                    $(this).find("i").removeClass("fa-minus").addClass("fa-plus");
                }
            });

            // order
            CKEDITOR.replace('order-1');
            $(".section-set-container").on("click", ".add-order", function() {
                var $showOrder = $(this).closest('.form-group').find('.show-order');
                $showOrder.toggleClass('d-none');

                if (!$showOrder.hasClass('d-none')) {
                    var $orderTextarea = $showOrder.find('.order');
                    CKEDITOR.replace($orderTextarea[0]);
                    $(this).find("i").removeClass("fa-plus").addClass("fa-minus");
                } else {
                    var $orderTextarea = $showOrder.find('.order');
                    CKEDITOR.instances[$orderTextarea[0].name].destroy();
                    $(this).find("i").removeClass("fa-minus").addClass("fa-plus");
                }
            });

            var sectionNumber = 1;

            $(".section-set-container").on("click", ".add-section", function() {
                var newSectionSet = $(".section-set").first().clone();

                sectionNumber++;
                var newSectionTextareaId = 'section-' + sectionNumber;
                newSectionSet.find('.section-textarea').attr('id', newSectionTextareaId).addClass(
                    newSectionTextareaId);

                CKEDITOR.replace(newSectionTextareaId);
                $(".section-set-container").append(newSectionSet);
            });

            // Remove Section
            $(".section-set-container").on("click", ".remove-section", function() {
                var sectionSets = $(".section-set");
                if (sectionSets.length > 1) {
                    $(this).closest(".section-set").remove();
                }
            });
        });
    </script>
@endsection
