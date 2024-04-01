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
                        <a href="/get_act_section/{{ $stschedule->act_id }}"><button class="btn btn-success">Back</button></a>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content mt-3">
        <div class="row">
            <div class="col-lg-12">
                <form id="form" action="/add_new_stschedule" method="post" enctype="multipart/form-data"
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
                    <input type="hidden" name="click_stschedule_rank" value="{{ $stschedule->stschedule_rank }}">
                    <input type="hidden" name="serial_no" value="{{ $stschedule->serial_no }}">
                    <input type="hidden" name="maintype_id" value="{{ $stschedule->maintype_id }}">
                    <input type="hidden" name="currentPage" value="{{ $currentPage }}">
                    <input type="hidden" name="act_id" value="{{ $stschedule->act_id }}">
                    {{-- <input type="hidden" name="section_rank" value="{{ $stschedule_rank }}"> --}}
                    @if ($stschedule->chapter_id)
                        <input type="hidden" name="chapter_id" value="{{ $stschedule->chapter_id }}">
                    @endif
                    @if ($stschedule->parts_id)
                        <input type="hidden" name="parts_id" value="{{ $stschedule->parts_id }}">
                    @endif
                    @if ($stschedule->priliminary_id)
                        <input type="hidden" name="priliminary_id" value="{{ $stschedule->priliminary_id }}">
                    @endif
                    @if ($stschedule->schedule_id)
                        <input type="hidden" name="schedule_id" value="{{ $stschedule->schedule_id }}">
                    @endif
                    @if ($stschedule->appendix_id)
                        <input type="hidden" name="appendix_id" value="{{ $stschedule->appendix_id }}">
                    @endif
                    @if ($stschedule->main_order_id)
                        <input type="hidden" name="main_order_id" value="{{ $stschedule->main_order_id }}">
                    @endif
                    
                    <div class="card p-5">
                        <div class="additional-section">
                            <div class="border col-md-12 p-3">
                                <div>
                                    <div class="form-group form-default col-md-12 px-0" id="sectionDiv">
                                        <div class="form-group form-default" style="display: block">
                                            @if ($stschedule->maintype_id == 1)
                                                <label class="float-label font-weight-bold">Chapter :</label>

                                                <textarea name="chapter_title" class="form-control mb-3 chapter_title" placeholder="Enter Chapter Title" id="c_title">{{ $stschedule->ChapterModel->chapter_title }}</textarea>
                                            @elseif($stschedule->maintype_id == 2)
                                                <label class="float-label font-weight-bold">Parts :</label>

                                                <textarea name="parts_title" class="form-control mb-3 parts_title" placeholder="Enter Parts Title" id="part_title">{{ $stschedule->Partmodel->parts_title }}</textarea>
                                            @elseif($stschedule->maintype_id == 3)
                                                <label class="float-label font-weight-bold">Priliminary :</label>

                                                <textarea name="priliminary_title" class="form-control mb-3 priliminary_title" placeholder="Enter Priliminary Title" id="p_title">{{ $stschedule->Priliminarymodel->priliminary_title }}</textarea>
                                            @elseif($stschedule->maintype_id == 4)
                                                <label class="float-label font-weight-bold">Schedule :</label>

                                                <textarea name="schedule_title" class="form-control mb-3 schedule_title" placeholder="Enter Schedule Title" id="s_title">{{ $stschedule->Schedulemodel->schedule_title }}</textarea>
                                            @elseif($stschedule->maintype_id == 5)
                                                <label class="float-label font-weight-bold">Appendix :</label>

                                                <textarea name="appendix_title" class="form-control mb-3 appendix_title" placeholder="Enter Appendix Title" id="a_title">{{ $stschedule->Appendixmodel->appendix_title }}</textarea>
                                            @elseif($stschedule->maintype_id == 6)
                                                <label class="float-label font-weight-bold">Order :</label>

                                                <textarea name="main_order_title" class="form-control mb-3 main_order_title" placeholder="Enter Order Title" id="m_title">{{ $stschedule->MainOrderModel->main_order_title }}</textarea>
                                            @else
                                                null
                                            @endif
                                        </div>
                                        <div class="form-group form-default" style="display: block">
                                            <label class="float-label font-weight-bold">Schedule :</label>
                                            <span class="d-flex">
                                                <input type="text" name="stschedule_no" class="form-control mb-3"
                                                    style="width:20%" placeholder="Schedule No.">
                                                <input type="text" name="stschedule_title" class="form-control mb-3"
                                                    placeholder="Schedule Title">
                                            </span>

                                        </div>
                                        <div class="form-group form-default" style="display: block">
                                            <label class="float-label">Schedule Description<span
                                                    class="text-danger">*</span></label>
                                            <textarea type="text" id="stschedule" name="stschedule_content"
                                                class="form-control stschedule-textarea ckeditor-replace stschedule mb-3" placeholder="Enter Schedule"></textarea>
                                            <div class="footnote-addition-container">
                                              

                                                <div class="col-md-12 px-0 py-3">
                                                    <div class="float-right">
                                                        <span style="font-size: small;"
                                                            class="px-2 text-uppercase font-weight-bold">
                                                            (Add footnote for Schedule)
                                                        </span>
                                                        <button type="button"
                                                            class="btn btn-sm social facebook p-0 add-multi-footnote">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-sm social youtube p-0 remove-multi-footnote">
                                                            <i class="fa fa-minus"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                        <!-- If there are no subsections or footnotes, show the default section -->
                                        <div class="multi-addition-container col-md-12 px-0">
                                            <div class="multi-addition">
                                               
                                                <div class="col-md-12 px-0 py-3">
                                                    <div class="float-right">
                                                        <span style="font-size: small;"
                                                            class="px-2 text-uppercase font-weight-bold">
                                                            (for add and remove Sub-Schedule and Footnote)
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

                                    <div class="form-group form-default" id="orderDiv" style="display: none">
                                        <input type="text" class="form-control mb-3"
                                            placeholder="Enter Order & Rules Title">
                                        <textarea type="text" id="order" name="order" class="form-control ckeditor-replace order"></textarea>
                                    </div>
                                    <button class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>f
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="https://cdn.ckeditor.com/4.16.2/full-all/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            CKEDITOR.replace('c_title');
            CKEDITOR.replace('part_title');
            CKEDITOR.replace('p_title');
            CKEDITOR.replace('s_title');
            CKEDITOR.replace('a_title');
            CKEDITOR.replace('m_title');
            CKEDITOR.replace('stschedule');
            CKEDITOR.replace('state_amendment');

            // Initialize CKEditor for existing sections
            $('.ckeditor-replace.sub_stschedule').each(function() {
                CKEDITOR.replace($(this).attr('name'));
            });

            // Initialize CKEditor for existing footnotes
            $('.ckeditor-replace.footnote').each(function() {
                CKEDITOR.replace($(this).attr('name'));
            });

            $(document).on('click', '.add-sub_stschedule', function() {
                var icon = $(this).find('i');
                var stschedule = $(this).closest('.form-default').find('.show-sub_stschedule');
                stschedule.slideToggle();
                icon.toggleClass('fa-plus fa-minus');

                // Initialize CKEditor for the new textarea
                CKEDITOR.replace(stschedule.find('.ckeditor-replace.sub_stschedule')[0]);
            });

            $(document).on('click', '.add-footnote', function() {
                var icon = $(this).find('i');
                var stschedule = $(this).closest('.form-default').find('.show-footnote');
                stschedule.slideToggle();
                icon.toggleClass('fa-plus fa-minus');

                // Initialize CKEditor for the new textarea
                CKEDITOR.replace(stschedule.find('.ckeditor-replace.footnote')[0]);
            });



            let stscheduleCounter = 1;
            let sub_stscheduleCounter = 0;
            let subStscheduleIndex = 0;
            let currentIndex;

            $(document).on('click', '.add-multi-addition', function() {
                var lastInput = $('[data-index]:last').data('index');
                // Find the clicked element's index
                var clickedIndex = $(this).closest('.multi-addition').index();

                // Find the maximum sectionCounterIndex among all elements
                var maxStscheduleCounterIndex = 0;

                $('.multi-addition').each(function() {
                    var index = parseInt($(this).find('[data-index]').data('index'));
                    if (!isNaN(index) && index > maxStscheduleCounterIndex) {
                        maxStscheduleCounterIndex = index;
                    }
                });

                // Calculate the new sectionCounterIndex based on the clicked index
                var stscheduleCounterIndex = Math.max(clickedIndex, maxStscheduleCounterIndex) + 1;

                var newStschedule = `
                                <div class="multi-addition">
                                    <div class="border col-md-12 p-3">
                                        <div class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12">
                                            <label class="float-label">
                                            Add Sub-Schedule
                                            <span class="pl-2">
                                                <button type="button" class="btn btn-sm social facebook p-0 add-sub_stschedule">
                                                <i class="fa fa-plus"></i>
                                                </button>
                                            </span>
                                            </label>
                                            <div class="show-sub_stschedule" style="display: none">
                                                <span class="d-flex"><input type="text" name="sub_stschedule_no[${stscheduleCounter}]" class="form-control mb-3" style="width: 20%" placeholder="Enter Sub-schedule No.">
                                                </span>
                                                <textarea type="text" name="sub_stschedule_content[${stscheduleCounter}]" class="form-control ckeditor-replace sub_stschedule"></textarea>
                                            </div>
                                        </div>
                                    
                                        <div class="footnote2-addition-container">
                                                            <div class="col-md-12 px-0 py-3">
                                                                <div class="float-right">
                                                                    <span style="font-size: small;"
                                                                        class="px-2 text-uppercase font-weight-bold">
                                                                        (Add footnote for sub-Schedule)
                                                                    </span>
                                                                    <button type="button"
                                                                        class="btn btn-sm social facebook p-0 add-multi-footnote2">
                                                                        <i class="fa fa-plus"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn-sm social youtube p-0 remove-multi-footnote2">
                                                                        <i class="fa fa-minus"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 px-0 py-3">
                                        <div class="float-right">
                                            <span style="font-size: small;" class="px-2 text-uppercase font-weight-bold">
                                            ( for add and remove Sub-Schedule and Footnote )
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


                  // $('.multi-addition-container').append(newSection);
                  var $clickedElement = $(this).closest('.multi-addition');
                $clickedElement.after(newStschedule);


                CKEDITOR.replace($('.multi-addition:last').find('.ckeditor-replace')[0]);
                CKEDITOR.replace($('.multi-addition:last').find('.ckeditor-replace')[1]);
              
                // Update sub_section_no and sub_section_content names in all elements
                $('.multi-addition').each(function(index) {
                    var newIndex = index + 1;
                    $(this).find(`[name^="sub_stschedule_no["]`).attr('name',
                        `sub_stschedule_no[${newIndex}]`);
                    $(this).find(`[name^="sub_stschedule_content["]`).attr('name',
                        `sub_stschedule_content[${newIndex}]`);
                    $(this).find('[data-index]').attr('data-index', newIndex);
                });

                stscheduleCounter++;
                sub_stscheduleCounter = 0;


            });

            $(document).on('click', '.remove-multi-addition', function() {
                var $clickedElement = $(this).closest('.multi-addition');

                if ($('.multi-addition').length > 0) {
                    $clickedElement.remove();

                    // Find the index of the clicked element
                    var index = $('.multi-addition').index($clickedElement);
                }
            });

            $(document).on('click', '.add-multi-footnote2', function() {
                // Find the closest multi-addition container
                var multiAdditionContainer = $(this).closest('.multi-addition');

                // Find the associated sub_section_no within the multi-addition container
                var associatedSubStscheduleTitle = multiAdditionContainer.find('[name^="sub_stschedule_no["]');

                // Check if the associatedSubSectionTitle is found
                if (associatedSubStscheduleTitle.length > 0) {
                    // Extract the index from the name attribute of the sub_section_no
                    var stscheduleIndexMatch = associatedSubStscheduleTitle.attr('name').match(/\[(\d*)\]/);

                    // Set currentIndex to 0 if the index is empty
                    var currentIndex = stscheduleIndexMatch && stscheduleIndexMatch[1] !== '' ?
                        parseInt(stscheduleIndexMatch[1], 10) : 0;

                    console.log('Current index of sub_stschedule_no:', currentIndex);

                    var newStschedule = `<div class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12 footnote2-addition">
                            <label class="float-label">
                            Add Footnote
                            <span class="pl-2">
                                <button type="button" class="btn btn-sm social facebook p-0 add-footnote">
                                <i class="fa fa-plus"></i>
                                </button>
                            </span>
                            </label>
                            <div class="show-footnote" style="display: none">
                                 <textarea type="text" name="sub_footnote_content[${currentIndex}][${sub_stscheduleCounter}]" class="form-control ckeditor-replace footnote"></textarea>
                            </div>
                        </div>`;

                    // Find the footnote2-addition-container within the multi-addition container
                    var footnote2AdditionContainer = multiAdditionContainer.find(
                        '.footnote2-addition-container');
                    footnote2AdditionContainer.append(newStschedule);

                    CKEDITOR.replace(footnote2AdditionContainer.find('.footnote2-addition:last').find(
                        '.ckeditor-replace')[0]);
                    subStscheduleIndex = sub_stscheduleCounter;
                    sub_stscheduleCounter++;
                } else {
                    console.error('Associated sub_stschedule_no not found.');
                }
            });

            $(document).on('click', '.remove-multi-footnote2', function() {
                if ($('.footnote2-addition').length > 0) {
                    $('.footnote2-addition:last').remove();
                }
            });

            // for section footnote 
            $(document).on('click', '.add-multi-footnote', function() {
                var newStschedule = `<div class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12 footnote-addition">
                                        <label class="float-label">
                                        Add Footnote
                                        <span class="pl-2">
                                            <button type="button" class="btn btn-sm social facebook p-0 add-footnote">
                                            <i class="fa fa-plus"></i>
                                            </button>
                                        </span>
                                        </label>
                                        <div class="show-footnote" style="display: none">
                                            <textarea type="text" name="stschedule_footnote_content[${stscheduleCounter}]" class="form-control ckeditor-replace footnote"></textarea>
                                        </div>
                                   
                                        
                                    </div>
                                    
                                `;

                $('.footnote-addition-container').append(newStschedule);

                CKEDITOR.replace($('.footnote-addition:last').find('.ckeditor-replace')[0]);
                // CKEDITOR.replace($('.footnote-addition:last').find('.ckeditor-replace')[1]);

                stscheduleCounter++; // Increment the counter for the next section
            });

            $(document).on('click', '.remove-multi-footnote', function() {
                if ($('.footnote-addition').length > 0) {
                    $('.footnote-addition:last').remove();
                }
            });


        });
    </script>
@endsection
