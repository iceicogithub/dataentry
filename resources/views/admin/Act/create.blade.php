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
                        <a href="/get_act_section/{{ $act->act_id }}"><button class="btn btn-danger">Back</button></a>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content mt-3">
        <div class="row">
            <div class="col-lg-12">
                <div class="card p-5">
                    <form id="form" action="/store_act/{{ $act->act_id }}" method="post"
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role" class=" form-control-label">Select Category<span
                                            class="text-danger">*</span></label>
                                    <select class="select form-control text-capitalize category" name="category_id">
                                        <option selected disabled>Select Category</option>
                                        @foreach ($category as $value)
                                            <option value="{{ $value->category_id }}" class="text-capitalize"
                                                {{ $act->category_id == $value->category_id ? 'selected' : '' }}>
                                                {{ $value->category }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 state" style="display: none;">
                                <div class="form-group">
                                    <label for="state" class=" form-control-label">Select state<span
                                            class="text-danger">*</span></label>
                                    <select class="select form-control text-capitalize" name="state_id">
                                        <option selected disabled>Select State</option>
                                        @foreach ($states as $item)
                                            <option value="{{ $item->state_id }}" class="text-capitalize"
                                                {{ $act->state_id == $item->state_id ? 'selected' : '' }}>
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group form-default">
                                    <label class="float-label"> Act <span class="text-danger">*</span></label>
                                    <input type="text" name="act_title" class="form-control mb-3"
                                        placeholder="Enter Act Title" value="{{ $act->act_title }}">
                                </div>
                            </div>
                            <div class="section-set-container col-md-12">
                                <div class="section-set col-md-12 px-0 mb-2">
                                    <div class="px-0 col-md-6">
                                        <div class="form-group">
                                            <label for="type" class=" form-control-label">Select Type<span
                                                    class="text-danger">*</span></label>
                                            <select class="select form-control text-capitalize type typeSelector"
                                                name="maintype_id[]" id="typeSelector">
                                                @foreach ($mtype as $item)
                                                    <option value="{{ $item->maintype_id }}" class="text-capitalize">
                                                        {{ $item->type }}</option>
                                                @endforeach

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
                                                    <textarea name="chapter_title[]" class="form-control mb-3 chapter_title" placeholder="Enter Chapter Title"
                                                        id="chapter_title"></textarea>
                                                </div>

                                                {{-- for parts --}}
                                                <div id="partSection" class="partSection" style="display: none">
                                                    <label class="float-label"> Part <span
                                                            class="text-danger">*</span></label>
                                                    <textarea name="parts_title[]" class="form-control mb-3 parts_title" placeholder="Enter Part Title" id="parts_title"></textarea>
                                                </div>
                                                 {{-- for Priliminary --}}
                                                <div id="priliminarySection" class="priliminarySection"
                                                    style="display: none">
                                                    <label class="float-label"> Priliminary <span
                                                            class="text-danger">*</span></label>
                                                    <textarea name="priliminary_title[]" class="form-control mb-3 priliminary_title" placeholder="Enter Priliminary Title"
                                                        id="priliminary_title"></textarea>
                                                </div>
                                            </div>
                                            {{-- for schedule --}}
                                            <div id="scheduleSection" class="scheduleSection" style="display: none">
                                                <label class="float-label"> Schedule <span
                                                        class="text-danger">*</span></label>
                                                <textarea name="schedule_title[]" class="form-control mb-3 schedule_title" placeholder="Enter Schedule Title"
                                                    id="schedule_title"></textarea>
                                            </div>
                                            {{-- for appendix --}}
                                            <div id="appendixSection" class="appendixSection" style="display: none">
                                                <label class="float-label"> Appendix <span
                                                        class="text-danger">*</span></label>
                                                <textarea name="appendix_title[]" class="form-control mb-3 appendix_title" placeholder="Enter Appendix Title"
                                                    id="appendix_title"></textarea>
                                            </div>
                                             {{-- for MainOrder --}}
                                             <div id="mainOrderSection" class="mainOrderSection" style="display: none;">
                                                <label class="float-label"> Order <span
                                                        class="text-danger">*</span></label>
                                                <textarea name="main_order_title[]" class="form-control mb-3 main_order_title" placeholder="Enter Order Title"
                                                    id="main_order_title"></textarea>
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
                                                                name="subtypes_id[]" id="select">
                                                                <option selected disabled>Select</option>
                                                                @foreach ($stype as $item)
                                                                    <option value="{{ $item->subtypes_id }}"
                                                                        class="text-capitalize">
                                                                        {{ $item->type }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-default col-md-12 px-0" id="1Div"
                                                        style="display:none">
                                                        <div class="form-group form-default sectionTitleMain"
                                                            style="display: block">
                                                            <label class="float-label">Section Title<span
                                                                    class="text-danger">*</span></label>
                                                            <div class="d-flex sectionTitle my-1">
                                                                <input type="text" name="section_no[][]"
                                                                    class="form-control" style="width: 20%;"
                                                                    placeholder="Enter Section NO.">
                                                                <input type="text" name="section_title[][]"
                                                                    class="form-control"
                                                                    placeholder="Enter Section Title">
                                                                <button type="button"
                                                                    class="add-sectionTitle btn btn-sm facebook mx-2 p-0 social">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-sm social youtube p-0 remove-sectionTitle">
                                                                    <i class="fa fa-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-default col-md-12 px-0" id="3Div"
                                                        style="display:none">
                                                        <div class="form-group form-default ruleTitleMain"
                                                            style="display: block">
                                                            <label class="float-label">Rules Title<span
                                                                    class="text-danger">*</span></label>
                                                            <div class="d-flex ruleTitle my-1">
                                                                <input type="text" name="rule_no[][]"
                                                                    class="form-control" style="width: 20%;"
                                                                    placeholder="Enter Rule NO.">
                                                                <input type="text" name="rule_title[][]"
                                                                    class="form-control" placeholder="Enter Rule Title">
                                                                <button type="button"
                                                                    class="add-ruleTitle btn btn-sm facebook mx-2 p-0 social">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-sm social youtube p-0 remove-ruleTitle">
                                                                    <i class="fa fa-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group form-default col-md-12 px-0" id="2Div"
                                                        style="display: none">
                                                        <div class="form-group form-default ArticleTitleMain"
                                                            style="display: block">
                                                            <label class="float-label">Article Title<span
                                                                    class="text-danger">*</span></label>
                                                            <div class="d-flex ArticleTitle my-1">
                                                                <input type="text" name="article_no[][]"
                                                                    class="form-control" style="width: 20%;"
                                                                    placeholder="Enter Article NO.">
                                                                <input type="text" name="article_title[][]"
                                                                    class="form-control"
                                                                    placeholder="Enter Article Title">
                                                                <button type="button"
                                                                    class="add-ArticleTitle btn btn-sm facebook mx-2 p-0 social">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-sm social youtube p-0 remove-ArticleTitle">
                                                                    <i class="fa fa-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group form-default col-md-12 px-0" id="4Div"
                                                        style="display:none">
                                                        <div class="form-group form-default RegulationTitleMain"
                                                            style="display: block">
                                                            <label class="float-label">Regulation Title<span
                                                                    class="text-danger">*</span></label>
                                                            <div class="d-flex RegulationTitle my-1">
                                                                <input type="text" name="regulation_no[][]"
                                                                    class="form-control" style="width: 20%;"
                                                                    placeholder="Enter Regulation NO.">
                                                                <input type="text" name="regulation_title[][]"
                                                                    class="form-control"
                                                                    placeholder="Enter Regulation Title">
                                                                <button type="button"
                                                                    class="add-RegulationTitle btn btn-sm facebook mx-2 p-0 social">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-sm social youtube p-0 remove-RegulationTitle">
                                                                    <i class="fa fa-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group form-default col-md-12 px-0" id="5Div"
                                                        style="display:none">
                                                        <div class="form-group form-default ListTitleMain"
                                                            style="display: block">
                                                            <label class="float-label">List Title<span
                                                                    class="text-danger">*</span></label>
                                                            <div class="d-flex ListTitle my-1">
                                                                <input type="text" name="list_no[][]"
                                                                    class="form-control" style="width: 20%;"
                                                                    placeholder="Enter List NO.">
                                                                <input type="text" name="list_title[][]"
                                                                    class="form-control" placeholder="Enter List Title">
                                                                <button type="button"
                                                                    class="add-ListTitle btn btn-sm facebook mx-2 p-0 social">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-sm social youtube p-0 remove-ListTitle">
                                                                    <i class="fa fa-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group form-default col-md-12 px-0" id="6Div"
                                                        style="display:none">
                                                        <div class="form-group form-default PartTitleMain"
                                                            style="display: block">
                                                            <label class="float-label">Part Title<span
                                                                    class="text-danger">*</span></label>
                                                            <div class="d-flex PartTitle my-1">
                                                                <input type="text" name="part_no[][]"
                                                                    class="form-control" style="width: 20%;"
                                                                    placeholder="Enter Part NO.">
                                                                <input type="text" name="part_title[][]"
                                                                    class="form-control" placeholder="Enter Part Title">
                                                                <button type="button"
                                                                    class="add-PartTitle btn btn-sm facebook mx-2 p-0 social">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-sm social youtube p-0 remove-PartTitle">
                                                                    <i class="fa fa-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group form-default col-md-12 px-0" id="7Div"
                                                        style="display:none">
                                                        <div class="form-group form-default AppendicesTitleMain"
                                                            style="display: block">
                                                            <label class="float-label">Appendices Title<span
                                                                    class="text-danger">*</span></label>
                                                            <div class="d-flex AppendicesTitle my-1">
                                                                <input type="text" name="appendices_no[][]"
                                                                    class="form-control" style="width: 20%;"
                                                                    placeholder="Enter Appendices NO.">
                                                                <input type="text" name="appendices_title[][]"
                                                                    class="form-control"
                                                                    placeholder="Enter Appendices Title">
                                                                <button type="button"
                                                                    class="add-AppendicesTitle btn btn-sm facebook mx-2 p-0 social">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-sm social youtube p-0 remove-AppendicesTitle">
                                                                    <i class="fa fa-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group form-default col-md-12 px-0" id="8Div"
                                                        style="display:none">
                                                        <div class="form-group form-default OrderTitleMain"
                                                            style="display: block">
                                                            <label class="float-label">Order Title<span
                                                                    class="text-danger">*</span></label>
                                                            <div class="d-flex OrderTitle my-1">
                                                                <input type="text" name="order_no[][]"
                                                                    class="form-control" style="width: 20%;"
                                                                    placeholder="Enter Order NO.">
                                                                <input type="text" name="order_title[][]"
                                                                    class="form-control" placeholder="Enter Order Title">
                                                                <button type="button"
                                                                    class="add-OrderTitle btn btn-sm facebook mx-2 p-0 social">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-sm social youtube p-0 remove-OrderTitle">
                                                                    <i class="fa fa-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group form-default col-md-12 px-0" id="9Div"
                                                        style="display:none">
                                                        <div class="form-group form-default AnnexureTitleMain"
                                                            style="display: block">
                                                            <label class="float-label">Annexure Title<span
                                                                    class="text-danger">*</span></label>
                                                            <div class="d-flex AnnexureTitle my-1">
                                                                <input type="text" name="annexure_no[][]"
                                                                    class="form-control" style="width: 20%;"
                                                                    placeholder="Enter Annexure NO.">
                                                                <input type="text" name="annexure_title[][]"
                                                                    class="form-control"
                                                                    placeholder="Enter Annexure Title">
                                                                <button type="button"
                                                                    class="add-AnnexureTitle btn btn-sm facebook mx-2 p-0 social">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-sm social youtube p-0 remove-AnnexureTitle">
                                                                    <i class="fa fa-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group form-default col-md-12 px-0" id="10Div"
                                                        style="display:none">
                                                        <div class="form-group form-default ScheduleTitleMain"
                                                            style="display: block">
                                                            <label class="float-label">Schedule Title<span
                                                                    class="text-danger">*</span></label>
                                                            <div class="d-flex ScheduleTitle my-1">
                                                                <input type="text" name="stschedule_no[][]"
                                                                    class="form-control" style="width: 20%;"
                                                                    placeholder="Enter Schedule NO.">
                                                                <input type="text" name="stschedule_title[][]"
                                                                    class="form-control"
                                                                    placeholder="Enter Schedule Title">
                                                                <button type="button"
                                                                    class="add-ScheduleTitle btn btn-sm facebook mx-2 p-0 social">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-sm social youtube p-0 remove-ScheduleTitle">
                                                                    <i class="fa fa-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 px-0 py-3">
                                        <div class="float-right">
                                            <span style="font-size: small;" class="px-2 text-uppercase font-weight-bold">
                                                ( for add and remove Chapter )
                                            </span>
                                            <button type="button" class="btn btn-sm social facebook p-0 add-chapter">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm social youtube p-0 remove-chapter">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @php
                                $displayStyle = $showFormTitle ? 'block' : 'none';
                            @endphp
                            <!--<div class="col-md-12 px-3" style="display: {{ $displayStyle }}">-->
                            <!--    <div class="form-group form-default">-->
                            <!--        <label class="float-label"> Form Title <span class="text-danger">*</span></label>-->
                            <!--        <input type="text" name="form_title" class="form-control mb-3"-->
                            <!--            placeholder="Enter Form Title" value="{{ $act->form_title }}">-->
                            <!--    </div>-->
                            <!--</div>-->


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
    {{-- <script>
            $(document).ready(function() {
                CKEDITOR.replaceAll('chapter_title', {
                   
                });
            });
            $(document).ready(function() {
                CKEDITOR.replaceAll('parts_title', {
                
                });
            });
        </script> --}}
    <script>
        $(document).ready(function() {
            CKEDITOR.replace('chapter_title');
            CKEDITOR.replace('parts_title');
            CKEDITOR.replace('priliminary_title');
            CKEDITOR.replace('schedule_title');
            CKEDITOR.replace('appendix_title');
            CKEDITOR.replace('main_order_title');

            // for category type
            $(document).on('change', '.category', function() {
                if ($(this).val() === '2') {
                    $('.state').show();
                } else {
                    $('.state').hide();
                }
            });

            // for part list dropdown
            $(document).on('change', '.type', function() {
                if ($(this).val() == '2') {
                    $(this).closest('.section-set').find('.parts').show();
                } else {
                    $(this).closest('.section-set').find('.parts').hide();
                }
            });

            //select type from dropdown list
            $(document).on('change', '.typeSelector', function() {
                var selectedValue = $(this).val();
                var sectionContainer = $(this).closest('.section-set');
                var chapterSection = sectionContainer.find('.chapterSection');
                var partSection = sectionContainer.find('.partSection');
                var priliminarySection = sectionContainer.find('.priliminarySection');
                var scheduleSection = sectionContainer.find('.scheduleSection');
                var appendixSection = sectionContainer.find('.appendixSection');
                var mainOrderSection = sectionContainer.find('.mainOrderSection');

                chapterSection.hide();
                partSection.hide();
                priliminarySection.hide();
                scheduleSection.hide();
                appendixSection.hide();
                mainOrderSection.hide();

                if (selectedValue == '1') {
                    chapterSection.show();
                } else if (selectedValue == '2') {
                    partSection.show();
                } else if (selectedValue == '3') {
                    priliminarySection.show();
                } else if (selectedValue == '4') {
                    scheduleSection.show();
                } else if (selectedValue == '5') {
                    appendixSection.show();
                } else if (selectedValue == '6') {
                    mainOrderSection.show();
                }
            });

            //Select Dropdown for section / articles / orders and rules / regulation
            $(document).on("change", ".sub_textarea", function() {
                var selectedOption = $(this).val();
                var sectionDiv = $(this).closest('.additional-section').find('#' + selectedOption + 'Div');
                sectionDiv.siblings('.form-group.form-default').hide();
                sectionDiv.show();
            });


            // Add -Remove Chapter
            $(document).ready(function() {

                let chapterCount = 0;

                $(document).on('click', '.add-chapter', function() {
                    chapterCount++;

                    var newSection = `
                    <div class="section-set col-md-12 px-0 mb-2" data-chapter-count="${chapterCount}">
                                    <div class="px-0 col-md-6">
                                        <div class="form-group">
                                            <label for="type" class=" form-control-label">Select Type<span
                                                    class="text-danger">*</span></label>
                                            <select class="select form-control text-capitalize type typeSelector"
                                                name="maintype_id[]" id="typeSelector">
                                                @foreach ($mtype as $item)
                                                    <option value="{{ $item->maintype_id }}" class="text-capitalize">
                                                        {{ $item->type }}</option>
                                                @endforeach

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
                                                    <textarea name="chapter_title[]" class="form-control mb-3 chapter_title" placeholder="Enter Chapter Title"
                                                        id="chapter_title"></textarea>
                                                </div>

                                                {{-- for parts --}}
                                                <div id="partSection" class="partSection" style="display: none">
                                                    <label class="float-label"> Part <span
                                                            class="text-danger">*</span></label>
                                                    <textarea name="parts_title[]" class="form-control mb-3 parts_title" placeholder="Enter Part Title" id="parts_title"></textarea>
                                                </div>

                                                <div id="priliminarySection" class="priliminarySection" style="display: none">
                                                    <label class="float-label"> Priliminary <span
                                                            class="text-danger">*</span></label>
                                                    <textarea name="priliminary_title[]" class="form-control mb-3 priliminary_title" placeholder="Enter Priliminary Title" id="priliminary_title"></textarea>
                                                </div>

                                                {{-- for schedule --}}
                                                <div id="scheduleSection" class="scheduleSection" style="display: none">
                                                    <label class="float-label"> Schedule <span class="text-danger">*</span></label>
                                                    <textarea name="schedule_title[]" class="form-control mb-3 schedule_title" placeholder="Enter Schedule Title" id="schedule_title"></textarea>  
                                                </div>
                                                {{-- for appendix --}}
                                                <div id="appendixSection" class="appendixSection" style="display: none">
                                                    <label class="float-label"> Appendix <span
                                                            class="text-danger">*</span></label>
                                                    <textarea name="appendix_title[]" class="form-control mb-3 appendix_title" placeholder="Enter Appendix Title"
                                                        id="appendix_title"></textarea>
                                                </div>
                                                {{-- for mainOrder --}}
                                                <div id="mainOrderSection" class="mainOrderSection" style="display: none">
                                                    <label class="float-label"> Ordersa <span
                                                            class="text-danger">*</span></label>
                                                    <textarea name="main_order_title[]" class="form-control mb-3 main_order_title" placeholder="Enter Order Title"
                                                        id="main_order_title"></textarea>
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
                                                            <select class="select form-control text-capitalize sub_textarea"
                                                                name="subtypes_id[]" id="select">
                                                                <option selected disabled>Select</option>
                                                                @foreach ($stype as $item)
                                                                    <option value="{{ $item->subtypes_id }}"
                                                                        class="text-capitalize">
                                                                        {{ $item->type }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-default col-md-12 px-0" id="1Div"
                                                        style="display:none">
                                                        <div class="form-group form-default sectionTitleMain"
                                                            style="display: block">
                                                            <label class="float-label">Section Title<span
                                                                    class="text-danger">*</span></label>
                                                            <div class="d-flex sectionTitle my-1">
                                                                <input type="text" name="section_no[${chapterCount}][]"
                                                                    class="form-control" style="width: 20%;"
                                                                    placeholder="Enter Section NO.">
                                                                <input type="text" name="section_title[${chapterCount}][]"
                                                                    class="form-control"
                                                                    placeholder="Enter Section Title">
                                                                <button type="button"
                                                                    class="add-sectionTitle btn btn-sm facebook mx-2 p-0 social">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-sm social youtube p-0 remove-sectionTitle">
                                                                    <i class="fa fa-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-default col-md-12 px-0" id="3Div"
                                                        style="display:none">
                                                        <div class="form-group form-default ruleTitleMain"
                                                            style="display: block">
                                                            <label class="float-label">Rules Title<span
                                                                    class="text-danger">*</span></label>
                                                            <div class="d-flex ruleTitle my-1">
                                                                <input type="text" name="rule_no[${chapterCount}][]"
                                                                    class="form-control" style="width: 20%;"
                                                                    placeholder="Enter Rule NO.">
                                                                <input type="text" name="rule_title[${chapterCount}][]"
                                                                    class="form-control"
                                                                    placeholder="Enter Rule Title">
                                                                <button type="button"
                                                                    class="add-ruleTitle btn btn-sm facebook mx-2 p-0 social">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-sm social youtube p-0 remove-ruleTitle">
                                                                    <i class="fa fa-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-default col-md-12 px-0" id="2Div"
                                                        style="display: none">
                                                        <div class="form-group form-default ArticleTitleMain"
                                                            style="display: block">
                                                            <label class="float-label">Article Title<span
                                                                    class="text-danger">*</span></label>
                                                            <div class="d-flex ArticleTitle my-1">
                                                                <input type="text" name="article_no[${chapterCount}][]"
                                                                    class="form-control" style="width: 20%;"
                                                                    placeholder="Enter Article NO.">
                                                                <input type="text" name="article_title[${chapterCount}][]"
                                                                    class="form-control"
                                                                    placeholder="Enter Article Title">
                                                                <button type="button"
                                                                    class="add-ArticleTitle btn btn-sm facebook mx-2 p-0 social">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-sm social youtube p-0 remove-ArticleTitle">
                                                                    <i class="fa fa-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-default col-md-12 px-0" id="4Div"
                                                        style="display:none">
                                                        <div class="form-group form-default RegulationTitleMain"
                                                            style="display: block">
                                                            <label class="float-label">Regulation Title<span
                                                                    class="text-danger">*</span></label>
                                                            <div class="d-flex RegulationTitle my-1">
                                                                <input type="text" name="regulation_no[${chapterCount}][]"
                                                                    class="form-control" style="width: 20%;"
                                                                    placeholder="Enter Regulation NO.">
                                                                <input type="text" name="regulation_title[${chapterCount}][]"
                                                                    class="form-control"
                                                                    placeholder="Enter Regulation Title">
                                                                <button type="button"
                                                                    class="add-RegulationTitle btn btn-sm facebook mx-2 p-0 social">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-sm social youtube p-0 remove-RegulationTitle">
                                                                    <i class="fa fa-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="form-group form-default col-md-12 px-0" id="5Div"
                                                        style="display:none">
                                                        <div class="form-group form-default ListTitleMain"
                                                            style="display: block">
                                                            <label class="float-label">List Title<span
                                                                    class="text-danger">*</span></label>
                                                            <div class="d-flex ListTitle my-1">
                                                                <input type="text" name="list_no[${chapterCount}][]"
                                                                    class="form-control" style="width: 20%;"
                                                                    placeholder="Enter List NO.">
                                                                <input type="text" name="list_title[${chapterCount}][]"
                                                                    class="form-control"
                                                                    placeholder="Enter List Title">
                                                                <button type="button"
                                                                    class="add-ListTitle btn btn-sm facebook mx-2 p-0 social">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-sm social youtube p-0 remove-ListTitle">
                                                                    <i class="fa fa-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group form-default col-md-12 px-0" id="6Div"
                                                        style="display:none">
                                                        <div class="form-group form-default PartTitleMain"
                                                            style="display: block">
                                                            <label class="float-label">Part Title<span
                                                                    class="text-danger">*</span></label>
                                                            <div class="d-flex PartTitle my-1">
                                                                <input type="text" name="part_no[${chapterCount}][]"
                                                                    class="form-control" style="width: 20%;"
                                                                    placeholder="Enter Part NO.">
                                                                <input type="text" name="part_title[${chapterCount}][]"
                                                                    class="form-control"
                                                                    placeholder="Enter Part Title">
                                                                <button type="button"
                                                                    class="add-PartTitle btn btn-sm facebook mx-2 p-0 social">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-sm social youtube p-0 remove-PartTitle">
                                                                    <i class="fa fa-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group form-default col-md-12 px-0" id="7Div"
                                                        style="display:none">
                                                        <div class="form-group form-default AppendicesTitleMain"
                                                            style="display: block">
                                                            <label class="float-label">Appendices Title<span
                                                                    class="text-danger">*</span></label>
                                                            <div class="d-flex AppendicesTitle my-1">
                                                                <input type="text" name="appendices_no[${chapterCount}][]"
                                                                    class="form-control" style="width: 20%;"
                                                                    placeholder="Enter Appendices NO.">
                                                                <input type="text" name="appendices_title[${chapterCount}][]"
                                                                    class="form-control"
                                                                    placeholder="Enter Appendices Title">
                                                                <button type="button"
                                                                    class="add-AppendicesTitle btn btn-sm facebook mx-2 p-0 social">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-sm social youtube p-0 remove-AppendicesTitle">
                                                                    <i class="fa fa-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group form-default col-md-12 px-0" id="8Div"
                                                        style="display:none">
                                                        <div class="form-group form-default OrderTitleMain"
                                                            style="display: block">
                                                            <label class="float-label">Order Title<span
                                                                    class="text-danger">*</span></label>
                                                            <div class="d-flex OrderTitle my-1">
                                                                <input type="text" name="order_no[${chapterCount}][]"
                                                                    class="form-control" style="width: 20%;"
                                                                    placeholder="Enter Order NO.">
                                                                <input type="text" name="order_title[${chapterCount}][]"
                                                                    class="form-control"
                                                                    placeholder="Enter Order Title">
                                                                <button type="button"
                                                                    class="add-OrderTitle btn btn-sm facebook mx-2 p-0 social">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-sm social youtube p-0 remove-OrderTitle">
                                                                    <i class="fa fa-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group form-default col-md-12 px-0" id="9Div"
                                                        style="display:none">
                                                        <div class="form-group form-default AnnexureTitleMain"
                                                            style="display: block">
                                                            <label class="float-label">Annexure Title<span
                                                                    class="text-danger">*</span></label>
                                                            <div class="d-flex AnnexureTitle my-1">
                                                                <input type="text" name="annexure_no[${chapterCount}][]"
                                                                    class="form-control" style="width: 20%;"
                                                                    placeholder="Enter Annexure NO.">
                                                                <input type="text" name="annexure_title[${chapterCount}][]"
                                                                    class="form-control"
                                                                    placeholder="Enter Annexure Title">
                                                                <button type="button"
                                                                    class="add-AnnexureTitle btn btn-sm facebook mx-2 p-0 social">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-sm social youtube p-0 remove-AnnexureTitle">
                                                                    <i class="fa fa-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group form-default col-md-12 px-0" id="10Div"
                                                        style="display:none">
                                                        <div class="form-group form-default ScheduleTitleMain"
                                                            style="display: block">
                                                            <label class="float-label">Schedule Title<span
                                                                    class="text-danger">*</span></label>
                                                            <div class="d-flex ScheduleTitle my-1">
                                                                <input type="text" name="stschedule_no[${chapterCount}][]"
                                                                    class="form-control" style="width: 20%;"
                                                                    placeholder="Enter Schedule NO.">
                                                                <input type="text" name="stschedule_title[${chapterCount}][]"
                                                                    class="form-control"
                                                                    placeholder="Enter Schedule Title">
                                                                <button type="button"
                                                                    class="add-ScheduleTitle btn btn-sm facebook mx-2 p-0 social">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-sm social youtube p-0 remove-ScheduleTitle">
                                                                    <i class="fa fa-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 px-0 py-3">
                                        <div class="float-right">
                                            <span style="font-size: small;" class="px-2 text-uppercase font-weight-bold">
                                                ( for add and remove Chapter )
                                            </span>
                                            <button type="button" class="btn btn-sm social facebook p-0 add-chapter">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm social youtube p-0 remove-chapter">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                        `;

                    // Append the new section to the container
                    $('.section-set-container').append(newSection);

                    // Increment CKEditor IDs and replace them
                    // CKEDITOR.replace($('.section-set:last').find('.chapter_title')[0]);
                    // CKEDITOR.replace($('.section-set:last').find('.parts_title')[0]);

                    // Update chapter title input name attribute
                    // Update chapter title input name and id attributes
                    $('.section-set:last').find('textarea[name^="chapter_title"]').attr('name',
                        'chapter_title[' + chapterCount + ']');
                    $('.section-set:last').find('textarea[name^="chapter_title"]').attr('id',
                        'chapter_title[' + chapterCount + ']');

                    // Update parts title input name and id attributes
                    $('.section-set:last').find('textarea[name^="parts_title"]').attr('name',
                        'parts_title[' + chapterCount + ']');
                    $('.section-set:last').find('textarea[name^="parts_title"]').attr('id',
                        'parts_title[' + chapterCount + ']');

                    // Update section select input name attribute
                    $('.section-set:last').find('select[name^="subtypes_id"]').attr('name',
                        'subtypes_id[' + chapterCount + ']');

                    // Update section no input name attribute
                    $('.section-set:last').find('input[name^="section_no"]').each(function(index) {
                        $(this).attr('name', 'section_no[' + chapterCount + '][' + index +
                            ']');
                    });

                    // Update section title input name attribute
                    $('.section-set:last').find('input[name^="section_title"]').each(function(
                        index) {
                        $(this).attr('name', 'section_title[' + chapterCount + '][' +
                            index + ']');
                    });

                    // Increment CKEditor IDs and replace them for the new section
                    CKEDITOR.replace($('.section-set:last').find('.chapter_title')[0]);
                    CKEDITOR.replace($('.section-set:last').find('.parts_title')[0]);
                    CKEDITOR.replace($('.section-set:last').find('.priliminary_title')[0]);
                    CKEDITOR.replace($('.section-set:last').find('.schedule_title')[0]);
                    CKEDITOR.replace($('.section-set:last').find('.appendix_title')[0]);
                    CKEDITOR.replace($('.section-set:last').find('.main_order_title')[0]);

                    // Increment sectionCounter if needed
                    sectionCounter++;

                    // Reset sub_sectionCounter
                    sub_sectionCounter = 0;
                });

                $(document).on('click', '.remove-chapter', function() {
                    // Ensure there is always at least one section
                    if ($('.section-set').length > 1) {
                        $(this).closest('.section-set').remove();
                    }
                });


                // for sections 
                $(document).on('click', '.add-sectionTitle', function() {
                    let sectionTitleMain = $(this).closest('.section-set').find(
                        '.sectionTitleMain');
                    let clonedSectionTitle = sectionTitleMain.find('.sectionTitle:first').clone(
                        true);
                    clonedSectionTitle.find('input').val('');

                    // Get the chapter count from the global variable
                    let currentChapterCount = chapterCount;

                    // Increment the section index for the new section title
                    let lastIndex = sectionTitleMain.find('.sectionTitle').length;

                    // Update the input name attribute with the new chapter and section indexes
                    clonedSectionTitle.find('input[name^="section_no"]').each(function(index) {
                        $(this).attr('name',
                            `section_no[${currentChapterCount}][${lastIndex + index}]`);
                    });

                    // Update the input name attribute with the new chapter and section indexes
                    clonedSectionTitle.find('input[name^="section_title"]').each(function(index) {
                        $(this).attr('name',
                            `section_title[${currentChapterCount}][${lastIndex + index}]`
                        );
                    });

                    sectionTitleMain.append(clonedSectionTitle);
                });

                $(document).on('click', '.remove-sectionTitle', function() {
                    let sectionTitles = $(this).closest('.sectionTitleMain').find('.sectionTitle');
                    if (sectionTitles.length > 1) {
                        $(this).closest('.sectionTitle').remove();
                    }
                });


                // for rule 
                $(document).ready(function() {
                    $(document).on('click', '.add-ruleTitle', function() {
                        let ruleTitleMain = $(this).closest('.section-set').find(
                            '.ruleTitleMain');
                        let clonedruleTitle = ruleTitleMain.find('.ruleTitle:first')
                            .clone(
                                true);
                        clonedruleTitle.find('input').val('');


                        // Get the chapter count from the data attribute of the closest .section-set
                        let chapterCount = parseInt($(this).closest('.section-set').data(
                            'chapter-count')) || 0;

                        // Increment the section index for the new section title
                        let lastIndex = ruleTitleMain.find('.ruleTitle').length;

                        // Update the input name attribute with the new chapter and section indexes
                        clonedruleTitle.find('input[name^="rule_no"]').each(function(
                            index) {
                            $(this).attr('name', 'rule_no[' + chapterCount + '][' +
                                (
                                    lastIndex + index) + ']');
                        });

                        // Update the input name attribute with the new chapter and section indexes
                        clonedruleTitle.find('input[name^="rule_title"]').each(function(
                            index) {
                            $(this).attr('name', 'rule_title[' + chapterCount +
                                '][' + (
                                    lastIndex + index) + ']');
                        });

                        ruleTitleMain.append(clonedruleTitle);
                    });
                });

                $(document).on('click', '.remove-ruleTitle', function() {
                    let ruleTitles = $(this).closest('.ruleTitleMain').find(
                        '.ruleTitle');
                    if (ruleTitles.length > 1) {
                        $(this).closest('.ruleTitle').remove();
                    }
                });

                // for regulations 
                $(document).on('click', '.add-RegulationTitle', function() {
                    let RegulationTitleMain = $(this).closest('.section-set').find(
                        '.RegulationTitleMain');
                    let clonedRegulationTitle = RegulationTitleMain.find('.RegulationTitle:first')
                        .clone(
                            true);
                    clonedRegulationTitle.find('input').val('');

                    // Get the chapter count from the data attribute of the closest .section-set
                    let chapterCount = parseInt($(this).closest('.section-set').data(
                        'chapter-count')) || 0;

                    // Increment the section index for the new section title
                    let lastIndex = RegulationTitleMain.find('.RegulationTitle').length;

                    // Update the input name attribute with the new chapter and section indexes
                    clonedRegulationTitle.find('input[name^="regulation_no"]').each(function(
                        index) {
                        $(this).attr('name', 'regulation_no[' + chapterCount + '][' + (
                            lastIndex + index) + ']');
                    });

                    // Update the input name attribute with the new chapter and section indexes
                    clonedRegulationTitle.find('input[name^="regulation_title"]').each(function(
                        index) {
                        $(this).attr('name', 'regulation_title[' + chapterCount + '][' + (
                            lastIndex + index) + ']');
                    });

                    RegulationTitleMain.append(clonedRegulationTitle);
                });

                $(document).on('click', '.remove-RegulationTitle', function() {
                    let RegulationTitles = $(this).closest('.RegulationTitleMain').find(
                        '.RegulationTitle');
                    if (RegulationTitles.length > 1) {
                        $(this).closest('.RegulationTitle').remove();
                    }
                });

                // for articles 
                $(document).on('click', '.add-ArticleTitle', function() {
                    let ArticleTitleMain = $(this).closest('.section-set').find(
                        '.ArticleTitleMain');
                    let clonedArticleTitle = ArticleTitleMain.find('.ArticleTitle:first')
                        .clone(
                            true);
                    clonedArticleTitle.find('input').val('');

                    // Get the chapter count from the data attribute of the closest .section-set
                    let chapterCount = parseInt($(this).closest('.section-set').data(
                        'chapter-count')) || 0;

                    // Increment the section index for the new section title
                    let lastIndex = ArticleTitleMain.find('.ArticleTitle').length;

                    // Update the input name attribute with the new chapter and section indexes
                    clonedArticleTitle.find('input[name^="article_no"]').each(function(
                        index) {
                        $(this).attr('name', 'article_no[' + chapterCount + '][' + (
                            lastIndex + index) + ']');
                    });

                    // Update the input name attribute with the new chapter and section indexes
                    clonedArticleTitle.find('input[name^="article_title"]').each(function(
                        index) {
                        $(this).attr('name', 'article_title[' + chapterCount + '][' + (
                            lastIndex + index) + ']');
                    });

                    ArticleTitleMain.append(clonedArticleTitle);
                });

                $(document).on('click', '.remove-ArticleTitle', function() {
                    let ArticleTitles = $(this).closest('.ArticleTitleMain').find(
                        '.ArticleTitle');
                    if (ArticleTitles.length > 1) {
                        $(this).closest('.ArticleTitle').remove();
                    }
                });

                // for list 
                $(document).on('click', '.add-ListTitle', function() {
                    let ListTitleMain = $(this).closest('.section-set').find(
                        '.ListTitleMain');
                    let clonedListTitle = ListTitleMain.find('.ListTitle:first')
                        .clone(
                            true);
                    clonedListTitle.find('input').val('');

                    // Get the chapter count from the data attribute of the closest .section-set
                    let chapterCount = parseInt($(this).closest('.section-set').data(
                        'chapter-count')) || 0;

                    // Increment the section index for the new section title
                    let lastIndex = ListTitleMain.find('.ListTitle').length;

                    // Update the input name attribute with the new chapter and section indexes
                    clonedListTitle.find('input[name^="list_no"]').each(function(
                        index) {
                        $(this).attr('name', 'list_no[' + chapterCount + '][' + (
                            lastIndex + index) + ']');
                    });

                    // Update the input name attribute with the new chapter and section indexes
                    clonedListTitle.find('input[name^="list_title"]').each(function(
                        index) {
                        $(this).attr('name', 'list_title[' + chapterCount + '][' + (
                            lastIndex + index) + ']');
                    });

                    ListTitleMain.append(clonedListTitle);
                });

                $(document).on('click', '.remove-ListTitle', function() {
                    let ListTitles = $(this).closest('.ListTitleMain').find(
                        '.ListTitle');
                    if (ListTitles.length > 1) {
                        $(this).closest('.ListTitle').remove();
                    }
                });

                // for Part 
                $(document).on('click', '.add-PartTitle', function() {
                    let PartTitleMain = $(this).closest('.section-set').find(
                        '.PartTitleMain');
                    let clonedPartTitle = PartTitleMain.find('.PartTitle:first')
                        .clone(
                            true);
                    clonedPartTitle.find('input').val('');

                    // Get the chapter count from the data attribute of the closest .section-set
                    let chapterCount = parseInt($(this).closest('.section-set').data(
                        'chapter-count')) || 0;

                    // Increment the section index for the new section title
                    let lastIndex = PartTitleMain.find('.PartTitle').length;

                    // Update the input name attribute with the new chapter and section indexes
                    clonedPartTitle.find('input[name^="part_no"]').each(function(
                        index) {
                        $(this).attr('name', 'part_no[' + chapterCount + '][' + (
                            lastIndex + index) + ']');
                    });

                    // Update the input name attribute with the new chapter and section indexes
                    clonedPartTitle.find('input[name^="part_title"]').each(function(
                        index) {
                        $(this).attr('name', 'part_title[' + chapterCount + '][' + (
                            lastIndex + index) + ']');
                    });

                    PartTitleMain.append(clonedPartTitle);
                });

                $(document).on('click', '.remove-PartTitle', function() {
                    let PartTitles = $(this).closest('.PartTitleMain').find(
                        '.PartTitle');
                    if (PartTitles.length > 1) {
                        $(this).closest('.PartTitle').remove();
                    }
                });

                // for Appendices 
                $(document).on('click', '.add-AppendicesTitle', function() {
                    let AppendicesTitleMain = $(this).closest('.section-set').find(
                        '.AppendicesTitleMain');
                    let clonedAppendicesTitle = AppendicesTitleMain.find('.AppendicesTitle:first')
                        .clone(
                            true);
                    clonedAppendicesTitle.find('input').val('');

                    // Get the chapter count from the data attribute of the closest .section-set
                    let chapterCount = parseInt($(this).closest('.section-set').data(
                        'chapter-count')) || 0;

                    // Increment the section index for the new section title
                    let lastIndex = AppendicesTitleMain.find('.AppendicesTitle').length;

                    // Update the input name attribute with the new chapter and section indexes
                    clonedAppendicesTitle.find('input[name^="appendices_no"]').each(function(
                        index) {
                        $(this).attr('name', 'appendices_no[' + chapterCount + '][' + (
                            lastIndex + index) + ']');
                    });

                    // Update the input name attribute with the new chapter and section indexes
                    clonedAppendicesTitle.find('input[name^="appendices_title"]').each(function(
                        index) {
                        $(this).attr('name', 'appendices_title[' + chapterCount + '][' + (
                            lastIndex + index) + ']');
                    });

                    AppendicesTitleMain.append(clonedAppendicesTitle);
                });

                $(document).on('click', '.remove-AppendicesTitle', function() {
                    let AppendicesTitles = $(this).closest('.AppendicesTitleMain').find(
                        '.AppendicesTitle');
                    if (AppendicesTitles.length > 1) {
                        $(this).closest('.AppendicesTitle').remove();
                    }
                });

                // for Order 
                $(document).on('click', '.add-OrderTitle', function() {
                    let OrderTitleMain = $(this).closest('.section-set').find(
                        '.OrderTitleMain');
                    let clonedOrderTitle = OrderTitleMain.find('.OrderTitle:first')
                        .clone(
                            true);
                    clonedOrderTitle.find('input').val('');

                    // Get the chapter count from the data attribute of the closest .section-set
                    let chapterCount = parseInt($(this).closest('.section-set').data(
                        'chapter-count')) || 0;

                    // Increment the section index for the new section title
                    let lastIndex = OrderTitleMain.find('.OrderTitle').length;

                    // Update the input name attribute with the new chapter and section indexes
                    clonedOrderTitle.find('input[name^="order_no"]').each(function(
                        index) {
                        $(this).attr('name', 'order_no[' + chapterCount + '][' + (
                            lastIndex + index) + ']');
                    });

                    // Update the input name attribute with the new chapter and section indexes
                    clonedOrderTitle.find('input[name^="order_title"]').each(function(
                        index) {
                        $(this).attr('name', 'order_title[' + chapterCount + '][' + (
                            lastIndex + index) + ']');
                    });

                    OrderTitleMain.append(clonedOrderTitle);
                });

                $(document).on('click', '.remove-OrderTitle', function() {
                    let OrderTitles = $(this).closest('.OrderTitleMain').find(
                        '.OrderTitle');
                    if (OrderTitles.length > 1) {
                        $(this).closest('.OrderTitle').remove();
                    }
                });

                // for Annexure 
                $(document).on('click', '.add-AnnexureTitle', function() {
                    let AnnexureTitleMain = $(this).closest('.section-set').find(
                        '.AnnexureTitleMain');
                    let clonedAnnexureTitle = AnnexureTitleMain.find('.AnnexureTitle:first')
                        .clone(
                            true);
                    clonedAnnexureTitle.find('input').val('');

                    // Get the chapter count from the data attribute of the closest .section-set
                    let chapterCount = parseInt($(this).closest('.section-set').data(
                        'chapter-count')) || 0;

                    // Increment the section index for the new section title
                    let lastIndex = AnnexureTitleMain.find('.AnnexureTitle').length;

                    // Update the input name attribute with the new chapter and section indexes
                    clonedAnnexureTitle.find('input[name^="annexure_no"]').each(function(
                        index) {
                        $(this).attr('name', 'annexure_no[' + chapterCount + '][' + (
                            lastIndex + index) + ']');
                    });

                    // Update the input name attribute with the new chapter and section indexes
                    clonedAnnexureTitle.find('input[name^="annexure_title"]').each(function(
                        index) {
                        $(this).attr('name', 'annexure_title[' + chapterCount + '][' + (
                            lastIndex + index) + ']');
                    });

                    AnnexureTitleMain.append(clonedAnnexureTitle);
                });

                $(document).on('click', '.remove-AnnexureTitle', function() {
                    let AnnexureTitles = $(this).closest('.AnnexureTitleMain').find(
                        '.AnnexureTitle');
                    if (AnnexureTitles.length > 1) {
                        $(this).closest('.AnnexureTitle').remove();
                    }
                });

                // for Schedule 
                $(document).on('click', '.add-ScheduleTitle', function() {
                    let ScheduleTitleMain = $(this).closest('.section-set').find(
                        '.ScheduleTitleMain');
                    let clonedScheduleTitle = ScheduleTitleMain.find('.ScheduleTitle:first')
                        .clone(
                            true);
                    clonedScheduleTitle.find('input').val('');

                    // Get the chapter count from the data attribute of the closest .section-set
                    let chapterCount = parseInt($(this).closest('.section-set').data(
                        'chapter-count')) || 0;

                    // Increment the section index for the new section title
                    let lastIndex = ScheduleTitleMain.find('.ScheduleTitle').length;

                    // Update the input name attribute with the new chapter and section indexes
                    clonedScheduleTitle.find('input[name^="stschedule_no"]').each(function(
                        index) {
                        $(this).attr('name', 'stschedule_no[' + chapterCount + '][' + (
                            lastIndex + index) + ']');
                    });

                    // Update the input name attribute with the new chapter and section indexes
                    clonedScheduleTitle.find('input[name^="stschedule_title"]').each(function(
                        index) {
                        $(this).attr('name', 'stschedule_title[' + chapterCount + '][' + (
                            lastIndex + index) + ']');
                    });

                    ScheduleTitleMain.append(clonedScheduleTitle);
                });

                $(document).on('click', '.remove-ScheduleTitle', function() {
                    let ScheduleTitles = $(this).closest('.ScheduleTitleMain').find(
                        '.ScheduleTitle');
                    if (ScheduleTitles.length > 1) {
                        $(this).closest('.ScheduleTitle').remove();
                    }
                });

            });

        });
    </script>
@endsection
