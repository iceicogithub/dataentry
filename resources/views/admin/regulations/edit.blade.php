@extends('admin.layout.main')
@section('content')
    <div class="breadcrumbs">
        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1>
                        @if ($regulations->maintype_id == 1)
                            Chapter
                        @elseif($regulations->maintype_id == 2)
                            Parts
                        @elseif($regulations->maintype_id == 4)
                            Regulations
                        @else
                            Appendices
                        @endif

                        <span> :
                            @if ($regulations->maintype_id == 1)
                                {{ $regulations->ChapterModel->chapter_title }}
                            @elseif($regulations->maintype_id == 2)
                                {{ $regulations->Partmodel->parts_title }}
                            @else
                                No data
                            @endif
                        </span>
                    </h1>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <a href="/get_act_regulation/{{ $regulations->act_id }}"><button class="btn btn-success">Back</button></a>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content mt-3">
        <div class="row">
            <div class="col-lg-12">
                <form id="form" action="/update_all_regulation/{{ $regulations->regulation_id }}" method="post"
                    enctype="multipart/form-data" class="form form-horizontal">
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
                    <input type="hidden" name="regulation_id" value="{{ $regulations->regulation_id }}">
                    <div class="card p-5">
                        <div class="additional-section">
                            <div class="border col-md-12 p-3">
                                <div>
                                    <div class="form-group form-default col-md-12 px-0" id="sectionDiv">
                                        <div class="form-group form-default" style="display: block">
                                            <label class="float-label font-weight-bold">Regulation :</label>
                                            <span class="d-flex">
                                                <input type="text" name="regulation_no" class="form-control"
                                                    style="width: 20%;" placeholder="Enter Regulation NO."
                                                    value="{{ $regulations->regulation_no }}">
                                                <input type="text" name="regulation_title"
                                                    value="{{ $regulations->regulation_title }}" class="form-control mb-3">
                                            </span>

                                        </div>
                                        <div class="form-group form-default" style="display: block">
                                            <label class="float-label">Regulations Description<span
                                                    class="text-danger">*</span></label>
                                            <textarea type="text" id="section" name="regulation_content"
                                                class="form-control section-textarea ckeditor-replace section" placeholder="Enter Section">{{ $regulations->regulation_content }}</textarea>
                                        </div>


                                        @if (!empty($reg))
                                            @foreach ($reg as $key => $item)
                                                <div class="multi-addition-container col-md-12 px-0">
                                                    <div class="multi-addition">
                                                        {{-- @foreach ($item->subsectionModel as $k => $subSectionItem)
                                                            <input type="hidden"
                                                                name="sub_section_id[{{ $k }}]"
                                                                value="{{ $subSectionItem->sub_section_id }}">
                                                            <div class="border col-md-12 p-3">
                                                                <div
                                                                    class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12">
                                                                    <label class="float-label">
                                                                        Add Sub-Section
                                                                        <span class="pl-2">
                                                                            <button type="button"
                                                                                class="btn btn-sm social facebook p-0 add-sub_section">
                                                                                <i
                                                                                    class="fa {{ !empty($subSectionItem->sub_section_title) ? 'fa-minus' : 'fa-plus' }}"></i>
                                                                            </button>
                                                                        </span>
                                                                    </label>
                                                                    <div class="show-sub_section">
                                                                        <span class="d-flex">
                                                                            <input type="number"
                                                                                name="sub_section_no[{{ $k }}]"
                                                                                class="form-control mb-3"
                                                                                value="{{ $subSectionItem->sub_section_no ?? '' }}"
                                                                                placeholder="Enter Sub-Section No."
                                                                                style="width: 20%;">
                                                                            <input type="text"
                                                                                name="sub_section_title[{{ $k }}]"
                                                                                class="form-control mb-3"
                                                                                value="{{ $subSectionItem->sub_section_title ?? '' }}"
                                                                                placeholder="Enter Sub-Section Title">
                                                                        </span>
                                                                        <textarea type="text" name="sub_section[{{ $k }}]" class="form-control ckeditor-replace sub_section">{{ $subSectionItem->sub_section_content ?? '' }}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach --}}

                                                        @foreach ($item->footnoteModel as $a => $footnoteItem)
                                                            <input type="hidden" name="footnote_id[{{ $a }}]"
                                                                value="{{ $footnoteItem->footnote_id }}">
                                                            <div class="border col-md-12 p-3">
                                                                <div
                                                                    class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12">
                                                                    <label class="float-label">
                                                                        Add Footnote
                                                                        <span class="pl-2">
                                                                            <button type="button"
                                                                                class="btn btn-sm social facebook p-0 add-footnote">
                                                                                <i
                                                                                    class="fa {{ !empty($footnoteItem->footnote_title) ? 'fa-minus' : 'fa-plus' }}"></i>
                                                                            </button>
                                                                        </span>
                                                                    </label>
                                                                    <div class="show-footnote">
                                                                        <input type="text"
                                                                            name="footnote_title[{{ $a }}]"
                                                                            class="form-control mb-3"
                                                                            value="{{ $footnoteItem->footnote_title ?? '' }}"
                                                                            placeholder="Enter Footnote Title">
                                                                        <textarea type="text" name="footnote_content[{{ $a }}]" class="form-control ckeditor-replace footnote">{{ $footnoteItem->footnote_content ?? '' }}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                        <div class="col-md-12 px-0 py-3">
                                                            <div class="float-right">
                                                                <span style="font-size: small;"
                                                                    class="px-2 text-uppercase font-weight-bold">
                                                                    (for add Footnote)
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
                                            @endforeach
                                        @else
                                            <!-- If there are no subsections or footnotes, show the default section -->
                                            <div class="multi-addition-container col-md-12 px-0">
                                                <div class="multi-addition">
                                                    <div class="border col-md-12 p-3">
                                                        {{-- <div
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
                                                                <span class="d-flex">
                                                                <input type="number" name="sub_section_no[]"
                                                                    class="form-control mb-3"
                                                                    placeholder="Enter Sub-Section No."
                                                                    style="width: 20%;">
                                                                <input type="text" name="sub_section_title[]"
                                                                    class="form-control mb-3"
                                                                    placeholder="Enter Sub-Section Title">
                                                            </span>
                                                                <textarea type="text" name="sub_section[]" class="form-control ckeditor-replace sub_section"></textarea>
                                                            </div>
                                                        </div> --}}
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
                                                                (for add and remove Footnote)
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
                                        @endif
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
                                        Add Footnote
                                        <span class="pl-2">
                                            <button type="button" class="btn btn-sm social facebook p-0 add-footnote">
                                            <i class="fa fa-plus"></i>
                                            </button>
                                        </span>
                                        </label>
                                        <div class="show-footnote" style="display: none">
                                        <input type="text" name="footnote_title[${sectionCounter}]" class="form-control mb-3" placeholder="Enter Footnote Title">
                                        <textarea type="text" name="footnote_content[${sectionCounter}]" class="form-control ckeditor-replace footnote"></textarea>
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
