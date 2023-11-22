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
                                        <option selected disabled>Select State</option>
                                        @foreach ($states as $item)
                                            <option value="{{ $item->id }}" class="text-capitalize">
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group form-default border p-3">
                                    <label class="float-label"> Act <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control mb-3" placeholder="Enter Act Title">
                                    <textarea type="text" id="act" name="act" class="form-control ckeditor-replace act">
                                    </textarea>
                                </div>
                            </div>
                            <div class="section-set-container col-md-12">
                                <div class="section-set col-md-12 px-0 mb-2">
                                    <div class="px-0 col-md-6">
                                        <div class="form-group">
                                            <label for="type" class=" form-control-label">Select Type<span
                                                    class="text-danger">*</span></label>
                                            <select class="select form-control text-capitalize type typeSelector"
                                                name="type" id="typeSelector">
                                                <option selected disabled>Select Type</option>
                                                <option value="preliminary" class="text-capitalize">Preliminary</option>
                                                <option value="chapter" class="text-capitalize">Chapter</option>
                                                <option value="part" class="text-capitalize">Parts</option>
                                                <option value="schedules" class="text-capitalize">Schedules</option>
                                                <option value="appendices" class="text-capitalize">Appendices</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group parts" style="display: none">
                                            <label for="parts" class=" form-control-label">Select Part<span
                                                    class="text-danger">*</span></label>
                                            <select class="select form-control text-capitalize" name="part">
                                                <option selected disabled>Select Part</option>
                                                <option value="part-I" class="text-capitalize">Part-I</option>
                                                <option value="part-II" class="text-capitalize">Part-II</option>
                                                <option value="part-III" class="text-capitalize">Part-III</option>
                                                <option value="part-IIV" class="text-capitalize">Part-IIV</option>
                                                <option value="part-V" class="text-capitalize">Part-V</option>
                                                <option value="part-VI" class="text-capitalize">Part-VI</option>
                                                <option value="part-VII" class="text-capitalize">Part-VII</option>
                                                <option value="part-VIII" class="text-capitalize">Part-VIII</option>
                                                <option value="part-IX" class="text-capitalize">Part-IX</option>
                                                <option value="part-X" class="text-capitalize">Part-X</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="section-container border col-md-12 p-3">
                                        <div class="col-md-12 px-0">
                                            <div class="form-group form-default">
                                                {{-- for chapter --}}
                                                <div id="chapterSection" class="chapterSection">
                                                    <label class="float-label"> Chapter <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control mb-3"
                                                        placeholder="Enter Chapter Title" id="chapterTitle">
                                                    <textarea type="text" id="chapter" name="chapter" class="form-control ckeditor-replace chapter"></textarea>
                                                </div>

                                                {{-- for parts --}}
                                                <div id="partSection" class="partSection" style="display: none">
                                                    <label class="float-label"> Part <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control mb-3"
                                                        placeholder="Enter Part Title" id="partTitle">
                                                    <textarea type="text" id="part" name="part" class="form-control ckeditor-replace part"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="additional-section">
                                            <div class="border col-md-12 p-3">
                                                <div>
                                                    <div class="col-md-6 px-0">
                                                        <div class="form-group">
                                                            <label for="select" class="form-control-label">Select<span
                                                                    class="text-danger">*</span></label>
                                                            <select
                                                                class="select form-control text-capitalize sub_textarea"
                                                                name="select" id="select">
                                                                <option selected disabled>Select</option>
                                                                <option value="section" class="text-capitalize">
                                                                    Section</option>
                                                                <option value="article" class="text-capitalize">Article
                                                                </option>
                                                                <option value="order" class="text-capitalize">Order &
                                                                    Rules</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-default col-md-12 px-0" id="sectionDiv"
                                                        style="display:none">
                                                        <div class="form-group form-default" style="display: block">
                                                            <label class="float-label">Section Title<span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control"
                                                                placeholder="Enter Section Title">
                                                        </div>
                                                        <div class="form-group form-default" style="display: block">
                                                            <label class="float-label">Add Section<span
                                                                    class="text-danger">*</span></label>
                                                            <textarea type="text" id="section" name="section"
                                                                class="form-control section-textarea ckeditor-replace section" placeholder="Enter Section"></textarea>
                                                        </div>
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
                                                                    <div class="show-sub_section" style="display:none">
                                                                        <input type="text" class="form-control mb-3"
                                                                            placeholder="Enter Sub-Section Title">
                                                                        <textarea type="text" name="sub_section" class="form-control ckeditor-replace sub_section"></textarea>
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
                                                                    <div class="show-footnote" style="display:none">
                                                                        <input type="text" class="form-control mb-3"
                                                                            placeholder="Enter Footnote Title">
                                                                        <textarea type="text" name="footnote" class="form-control ckeditor-replace footnote"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                                <div class="col-md-12 px-0 py-3">
                                                                    <div class="float-right">
                                                                        <span style="font-size: small;"
                                                                            class="px-2 text-uppercase">
                                                                            ( for add and remove Sub-Section and Footnote )
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

                                                    <div class="form-group form-default" id="articleDiv"
                                                        style="display: none">
                                                        <input type="text" class="form-control mb-3"
                                                            placeholder="Enter Article Title">
                                                        <textarea type="text" id="article" name="article" class="form-control ckeditor-replace article"></textarea>
                                                    </div>

                                                    <div class="form-group form-default" id="orderDiv"
                                                        style="display: none">
                                                        <input type="text" class="form-control mb-3"
                                                            placeholder="Enter Order & Rules Title">
                                                        <textarea type="text" id="order" name="order" class="form-control ckeditor-replace order"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 px-0 py-3">
                                                <div class="float-right">
                                                    <span style="font-size: small;" class="px-2 text-uppercase">
                                                        ( for add and remove section )
                                                    </span>
                                                    <button type="button"
                                                        class="btn btn-sm social facebook p-0 add-additional-section">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                    <button type="button"
                                                        class="btn btn-sm social youtube p-0 remove-additional-section">
                                                        <i class="fa fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 px-0 py-3">
                                        <div class="float-right">
                                            <span style="font-size: small;" class="px-2 text-uppercase">
                                                ( for add and remove chapter )
                                            </span>
                                            <button type="button" class="btn btn-sm social facebook p-0 add-section">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm social youtube p-0 remove-section">
                                                <i class="fa fa-minus"></i>
                                            </button>
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
        function initializeCKEditorForTextarea(textareaId) {
            CKEDITOR.replace(textareaId);
        }

        initializeCKEditorForTextarea('act');
        initializeCKEditorForTextarea('footnote');
        initializeCKEditorForTextarea('sub_section');
        initializeCKEditorForTextarea('section');
        initializeCKEditorForTextarea('chapter');
        initializeCKEditorForTextarea('order');
        initializeCKEditorForTextarea('article');
        initializeCKEditorForTextarea('part');

        function toggleTextareaVisibility(container, textareaClass) {
            var showTextarea = container.find('.show-' + textareaClass);
            showTextarea.toggleClass('d-none');

            if (!showTextarea.hasClass('d-none')) {
                var textarea = showTextarea.find('.' + textareaClass);
                initializeCKEditorForTextarea(textarea[0].id);
                container.find('.add-' + textareaClass + ' i').toggleClass("fa-plus fa-minus");
            } else {
                var textarea = showTextarea.find('.' + textareaClass);
                CKEDITOR.instances[textarea[0].id].destroy();
                container.find('.add-' + textareaClass + ' i').toggleClass("fa-minus fa-plus");
            }
        }

        function initializeCKEditorForNewSectionSet(newSectionSet) {
            toggleTextareaVisibility(newSectionSet, 'act');
            toggleTextareaVisibility(newSectionSet, 'footnote');
            toggleTextareaVisibility(newSectionSet, 'sub_section');
            toggleTextareaVisibility(newSectionSet, 'section');
            toggleTextareaVisibility(newSectionSet, 'chapter');
            toggleTextareaVisibility(newSectionSet, 'order');
            toggleTextareaVisibility(newSectionSet, 'article');
            toggleTextareaVisibility(newSectionSet, 'part');
        }

        $(document).on('change', '.category', function() {
            if ($(this).val() === '2') {
                $('.state').show();
            } else {
                $('.state').hide();
            }
        });

        $(document).on('change', '.type', function() {
            if ($(this).val() === 'part') {
                $(this).closest('.section-set').find('.parts').show();
            } else {
                $(this).closest('.section-set').find('.parts').hide();
            }
        });

        $(".section-set-container").on("click", ".add-section, .add-additional-section", function() {
            var container = $(this).closest('.section-container');

            toggleTextareaVisibility(container, 'act');
            toggleTextareaVisibility(container, 'footnote');
            toggleTextareaVisibility(container, 'sub_section');
            toggleTextareaVisibility(container, 'section');
            toggleTextareaVisibility(container, 'chapter');
            toggleTextareaVisibility(container, 'order');
            toggleTextareaVisibility(container, 'article');
            toggleTextareaVisibility(container, 'part');
        });

        function toggleSubSectionVisibility(container) {
            var showSubSection = container.find(".show-sub_section");
            showSubSection.toggle();

            container.find(".add-sub_section i").toggleClass("fa-plus fa-minus");

            if (!showSubSection.hasClass('d-none')) {
                var subSectionTextarea = showSubSection.find('.sub_section');
                if (!subSectionTextarea.data('ckeditor-initialized')) {
                    initializeCKEditorForTextarea(subSectionTextarea[0].id);
                    subSectionTextarea.data('ckeditor-initialized', true);
                }
            } else {
                var subSectionTextarea = showSubSection.find('.sub_section');
                CKEDITOR.instances[subSectionTextarea[0].id].destroy();
                subSectionTextarea.data('ckeditor-initialized', false);
            }
        }

        function toggleFootnoteVisibility(container) {
            var showFootnote = container.find(".show-footnote");
            showFootnote.toggle();

            container.find(".add-footnote i").toggleClass("fa-plus fa-minus");

            if (!showFootnote.hasClass('d-none')) {
                var footnoteTextarea = showFootnote.find('.footnote');
                if (!footnoteTextarea.data('ckeditor-initialized')) {
                    initializeCKEditorForTextarea(footnoteTextarea[0].id);
                    footnoteTextarea.data('ckeditor-initialized', true);
                }
            } else {
                var footnoteTextarea = showFootnote.find('.footnote');
                CKEDITOR.instances[footnoteTextarea[0].id].destroy();
                footnoteTextarea.data('ckeditor-initialized', false);
            }
        }

        $(document).on("click", ".add-sub_section", function() {
            var container = $(this).closest(".form-group");
            toggleSubSectionVisibility(container);
        });

        $(document).on("click", ".add-additional-section", function() {
            var newSection = $(this).closest(".section-container").find(".additional-section").last();
            toggleSubSectionVisibility(newSection);
        });

        $(document).on("click", ".add-footnote", function() {
            var container = $(this).closest(".form-group");
            toggleFootnoteVisibility(container);
        });

        $(document).on("change", ".sub_textarea", function() {
            var selectedOption = $(this).val();
            var sectionDiv = $(this).closest('.additional-section').find('#' + selectedOption + 'Div');
            sectionDiv.siblings('.form-group.form-default').hide();
            sectionDiv.show();
        });

        function updateCKEditorIds() {
            $(".ckeditor-replace").each(function(index) {
                var textareaId = 'ckeditor-' + (index + 1);
                $(this).attr('id', textareaId);
                initializeCKEditorForTextarea(textareaId);
            });
        }

        $(document).on("click", ".add-section", function() {
            var newSectionSet = $(".section-set").first().clone();
            $(".section-set-container").append(newSectionSet);

            updateCKEditorIds();
        });

        $(document).on("click", ".remove-section", function() {
            var sectionSets = $(".section-set");
            if (sectionSets.length > 1) {
                $(this).closest(".section-set").remove();
                updateCKEditorIds();
            }
        });

        $(document).on("click", ".add-additional-section", function() {
            var newAdditionalSection = $(this).closest('.section-container').find(".additional-section").first()
                .clone();
            $(this).closest('.section-container').append(newAdditionalSection);

            newAdditionalSection.find('input, textarea').val('');
            newAdditionalSection.find('.ckeditor-replace').each(function() {
                var textareaId = 'additional-ckeditor-' + (new Date()).getTime();
                $(this).attr('id', textareaId);
                initializeCKEditorForTextarea(textareaId);
            });
        });

        $(document).on("click", ".remove-additional-section", function() {
            var additionalSections = $(".additional-section");
            if (additionalSections.length > 1) {
                $(this).closest(".additional-section").remove();
                updateCKEditorIds();
            }
        });

        $(document).on("click", ".add-multi-addition", function() {
            var newMultiAddition = $(this).closest('.multi-addition-container').find(".multi-addition").first()
                .clone();
            $(this).closest('.multi-addition-container').append(newMultiAddition);

            newMultiAddition.find('input, textarea').val('');
            newMultiAddition.find('.ckeditor-replace').each(function() {
                var textareaId = 'multi-addition-ckeditor-' + (new Date()).getTime();
                $(this).attr('id', textareaId);
                initializeCKEditorForTextarea(textareaId);
            });
        });

        $(document).on("click", ".remove-multi-addition", function() {
            var multiAdditions = $(".multi-addition");
            if (multiAdditions.length > 1) {
                $(this).closest(".multi-addition").remove();
                updateCKEditorIds();
            }
        });

        $("#typeSelector").change(function() {
            var selectedValue = $(this).val();

            $("#chapterSection, #partSection").hide();
            
            if (selectedValue === "chapter") {
                $("#chapterSection").show();
            } else if (selectedValue === "part") {
                $("#partSection").show();
            }
        });

    </script>
@endsection
