@extends('admin.layout.main')
@section('content')
    <div class="breadcrumbs">
        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">

                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <a href="/get_act_section/{{ $sections->act_id }}"><button class="btn btn-success">Back</button></a>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content mt-3">
        <div class="row">
            <div class="col-lg-12">
                <form id="form" action="/add_new_section" method="post" enctype="multipart/form-data"
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
                    <input type="hidden" name="section_no" value="{{ $sec_no }}">
                    <input type="hidden" name="maintype_id" value="{{ $sections->maintype_id }}">
                    <input type="hidden" name="act_id" value="{{ $sections->act_id }}">
                    @if ($sections->chapter_id)
                        <input type="hidden" name="chapter_id" value="{{ $sections->chapter_id }}">
                    @endif
                    @if ($sections->parts_id)
                        <input type="hidden" name="parts_id" value="{{ $sections->parts_id }}">
                    @endif
                    <div class="card p-5">
                        <div class="additional-section">
                            <div class="border col-md-12 p-3">
                                <div>
                                    <div class="form-group form-default col-md-12 px-0" id="sectionDiv">
                                        <div class="form-group form-default" style="display: block">
                                            @if ($sections->ChapterModel)
                                                <label class="float-label font-weight-bold">Chapter :</label>
                                                <span> <input type="text" name="chapter_title" class="form-control mb-3"
                                                        value="{{ $sections->ChapterModel->chapter_title }}"></span>
                                            @else
                                                <label class="float-label font-weight-bold">Parts :</label>
                                                <span> <input type="text" name="chapter_title" class="form-control mb-3"
                                                        value="{{ $sections->Partmodel->parts_title }}"></span>
                                            @endif
                                        </div>
                                        <div class="form-group form-default" style="display: block">
                                            <label class="float-label font-weight-bold">Section Title :</label>
                                            <span> <input type="text" name="section_title"
                                                    class="form-control mb-3"></span>

                                        </div>
                                        <div class="form-group form-default" style="display: block">
                                            <label class="float-label">Section Description<span
                                                    class="text-danger">*</span></label>
                                            <textarea type="text" id="section" name="section_content"
                                                class="form-control section-textarea ckeditor-replace section" placeholder="Enter Section"></textarea>
                                        </div>

                                        <!-- If there are no subsections or footnotes, show the default section -->
                                        <div class="multi-addition-container col-md-12 px-0">
                                            <div class="multi-addition">
                                                <div class="border col-md-12 p-3">
                                                    <div
                                                        class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12">
                                                        <label class="float-label">
                                                            Add Sub-Section
                                                            <span class="pl-2">
                                                                <button type="button"
                                                                    class="btn btn-sm social facebook p-0 add-sub_section">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                            </span>
                                                        </label>
                                                        <div class="show-sub_section" style="display: none">
                                                            <input type="text" name="sub_section_title[]"
                                                                class="form-control mb-3"
                                                                placeholder="Enter Sub-Section Title">
                                                            <textarea type="text" name="sub_section_content[]" class="form-control ckeditor-replace sub_section"></textarea>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12">
                                                        <label class="float-label">
                                                            Add Footnote
                                                            <span class="pl-2">
                                                                <button type="button"
                                                                    class="btn btn-sm social facebook p-0 add-footnote">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                            </span>
                                                        </label>
                                                        <div class="show-footnote" style="display: none">
                                                            <input type="text" name="footnote_title[]"
                                                                class="form-control mb-3"
                                                                placeholder="Enter Footnote Title">
                                                            <textarea type="text" name="footnote_content[]" class="form-control ckeditor-replace footnote"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 px-0 py-3">
                                                    <div class="float-right">
                                                        <span style="font-size: small;"
                                                            class="px-2 text-uppercase font-weight-bold">
                                                            (for add and remove Sub-Section and Footnote)
                                                        </span>
                                                        <button type="button"
                                                            class="btn btn-sm social facebook p-0 add-multi-addition">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-sm social youtube p-0 remove-multi-addition">
                                                            <i class="fa fa-minus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group form-default" id="articleDiv" style="display: none">
                                        <input type="text" class="form-control mb-3"
                                            placeholder="Enter Article Title">
                                        <textarea type="text" id="article" name="article" class="form-control ckeditor-replace article"></textarea>
                                    </div>

                                    <div class="form-group form-default" id="orderDiv" style="display: none">
                                        <input type="text" class="form-control mb-3"
                                            placeholder="Enter Order & Rules Title">
                                        <textarea type="text" id="order" name="order" class="form-control ckeditor-replace order"></textarea>
                                    </div>
                                    <button class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            CKEDITOR.replace('section');
            CKEDITOR.replace('state_amendment');

            // Initialize CKEditor for existing sections
            $('.ckeditor-replace.sub_section').each(function() {
                CKEDITOR.replace($(this).attr('name'));
            });

            // Initialize CKEditor for existing footnotes
            $('.ckeditor-replace.footnote').each(function() {
                CKEDITOR.replace($(this).attr('name'));
            });

            $(document).on('click', '.add-sub_section', function() {
                var icon = $(this).find('i');
                var section = $(this).closest('.form-default').find('.show-sub_section');
                section.slideToggle();
                icon.toggleClass('fa-plus fa-minus');

                // Initialize CKEditor for the new textarea
                CKEDITOR.replace(section.find('.ckeditor-replace.sub_section')[0]);
            });

            $(document).on('click', '.add-footnote', function() {
                var icon = $(this).find('i');
                var section = $(this).closest('.form-default').find('.show-footnote');
                section.slideToggle();
                icon.toggleClass('fa-plus fa-minus');

                // Initialize CKEditor for the new textarea
                CKEDITOR.replace(section.find('.ckeditor-replace.footnote')[0]);
            });



            let sectionCounter = 1; // Initialize a counter variable

            $(document).on('click', '.add-multi-addition', function() {
                var newSection = `
                                <div class="multi-addition">
                                    <div class="border col-md-12 p-3">
                                    <div class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12">
                                        <label class="float-label">
                                        Add Sub-Section
                                        <span class="pl-2">
                                            <button type="button" class="btn btn-sm social facebook p-0 add-sub_section">
                                            <i class="fa fa-plus"></i>
                                            </button>
                                        </span>
                                        </label>
                                        <div class="show-sub_section" style="display: none">
                                        <input type="text" name="sub_section_title[${sectionCounter}]" class="form-control mb-3" placeholder="Enter Sub-Section Title">
                                        <textarea type="text" name="sub_section[${sectionCounter}]" class="form-control ckeditor-replace sub_section"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12">
                                        <label class="float-label">
                                        Add Footnote
                                        <span class="pl-2">
                                            <button type="button" class="btn btn-sm social facebook p-0 add-footnote">
                                            <i class="fa fa-plus"></i>
                                            </button>
                                        </span>
                                        </label>
                                        <div class="show-footnote" style="display: none">
                                        <input type="text" name="footnote_title[${sectionCounter}]" class="form-control mb-3" placeholder="Enter Footnote Title">
                                        <textarea type="text" name="footnote[${sectionCounter}]" class="form-control ckeditor-replace footnote"></textarea>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-12 px-0 py-3">
                                    <div class="float-right">
                                        <span style="font-size: small;" class="px-2 text-uppercase font-weight-bold">
                                        ( for add and remove Sub-Section and Footnote )
                                        </span>
                                        <button type="button" class="btn btn-sm social facebook p-0 add-multi-addition">
                                        <i class="fa fa-plus"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm social youtube p-0 remove-multi-addition">
                                        <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    </div>
                                </div>
                                `;

                $('.multi-addition-container').append(newSection);

                CKEDITOR.replace($('.multi-addition:last').find('.ckeditor-replace')[0]);
                CKEDITOR.replace($('.multi-addition:last').find('.ckeditor-replace')[1]);

                sectionCounter++; // Increment the counter for the next section
            });


            $(document).on('click', '.remove-multi-addition', function() {
                if ($('.multi-addition').length > 1) {
                    $('.multi-addition:last').remove();
                }
            });
        });
    </script>
@endsection
