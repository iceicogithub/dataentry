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
                <form id="form" action="/update_all_section/{{ $sections->section_id }}" method="post"
                    enctype="multipart/form-data" class="form form-horizontal">
                    @csrf
                    <!-- Your Blade View -->
                    @if ($errors->has('error'))
                        <div class="alert alert-danger">
                            {{ $errors->first('error') }}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-success">
                            {{ session('error') }}
                        </div>
                    @endif
                    <input type="hidden" name="section_id" value="{{ $sections->section_id }}">
                    <input type="hidden" name="chapter_id" value="{{ $sections->chapter_id }}">
                    <input type="hidden" name="parts_id" value="{{ $sections->parts_id }}">
                    <input type="hidden" name="schedule_id" value="{{ $sections->schedule_id }}">
                    <input type="hidden" name="priliminary_id" value="{{ $sections->priliminary_id }}">
                    <input type="hidden" name="appendix_id" value="{{ $sections->appendix_id }}">
                    <input type="hidden" name="main_order_id" value="{{ $sections->main_order_id }}">
                    <div class="card p-5">
                        <div class="additional-section">
                            <div class="border col-md-12 p-3">
                                <div>
                                    <div class="form-group form-default col-md-12 px-0" id="sectionDiv">

                                        <div class="form-group form-default" style="display: block">
                                            @if ($sections->maintype_id == 1)
                                            <label class="float-label font-weight-bold">Chapter :</label>

                                            <textarea name="chapter_title" class="form-control mb-3 chapter_title" placeholder="Enter Chapter Title" id="c_title">{{ $sections->ChapterModel->chapter_title }}</textarea>
                                        @elseif($sections->maintype_id == 2)
                                            <label class="float-label font-weight-bold">Parts :</label>

                                            <textarea name="parts_title" class="form-control mb-3 parts_title" placeholder="Enter Parts Title" id="p_title">{{ $sections->Partmodel->parts_title }}</textarea>
                                        @elseif($sections->maintype_id == 3)
                                            <label class="float-label font-weight-bold">Priliminary :</label>

                                            <textarea name="parts_title" class="form-control mb-3 parts_title" placeholder="Enter Parts Title" id="pr_title">{{ $sections->Priliminarymodel->priliminary_title }}</textarea>
                                        @elseif($sections->maintype_id == 4)
                                            <label class="float-label font-weight-bold">Schedule :</label>

                                            <textarea name="schedule_title" class="form-control mb-3 schedule_title" placeholder="Enter Schedule Title"
                                                id="s_title">{{ $sections->Schedulemodel->schedule_title }}</textarea>
                                        @elseif($sections->maintype_id == 5)
                                            <label class="float-label font-weight-bold">Appendix :</label>

                                            <textarea name="appendix_title" class="form-control mb-3 appendix_title" placeholder="Enter Appendix Title"
                                                id="a_title">{{ $sections->Appendixmodel->appendix_title }}</textarea>
                                        @elseif($sections->maintype_id == 6)
                                            <label class="float-label font-weight-bold">Order :</label>

                                            <textarea name="main_order_title" class="form-control mb-3 main_order_title" placeholder="Enter Order Title"
                                                id="a_title">{{ $sections->MainOrderModel->main_order_title }}</textarea>
                                        @else
                                            null
                                        @endif
                                        </div>

                                        <div class="form-group form-default" style="display: block">
                                            <label class="float-label font-weight-bold">Section :</label>
                                            
                                                <input type="text" name="section_no" class="form-control my-3"
                                                    style="width: 20%;" placeholder="Enter Section NO."
                                                    value="{{ $sections->section_no }}">
                                                    <textarea type="text" id="section_title" name="section_title"
                                                    class="form-control section-textarea ckeditor-replace section" placeholder="Enter Section Title">{{ $sections->section_title }}</textarea>
                                               
                                            
                                        </div>

                                        <div class="form-group form-default" style="display: block">
                                            <label class="float-label">Section Description<span
                                                    class="text-danger">*</span></label>
                                            <textarea type="text" id="section" name="section_content"
                                                class="form-control section-textarea ckeditor-replace section" placeholder="Enter Section">{{ $sections->section_content }}</textarea>

                                            <div class="footnote-addition-container">
                                                @if ($subsec->isNotEmpty())
                                                    @foreach ($subsec as $s => $section)
                                                        @if ($section->footnoteModel)
                                                            @foreach ($section->footnoteModel as $f => $footnote)
                                                                <div
                                                                    class="form-group form-default mt-3 fa fa-arrow-circle-o-right p-0 col-md-12 footnote-addition">
                                                                    <div class="d-flex justify-content-between">
                                                                        <label class="float-label">
                                                                            Add Footnote
                                                                            <span class="pl-2">
                                                                                <button type="button"
                                                                                    class="btn btn-sm social facebook p-0 add-footnote">
                                                                                    <i class="fa fa-minus"></i>
                                                                                </button>
                                                                            </span>
                                                                        </label>
                                                                        <div>
                                                                            <a href="{{ url('/delete_footnote/' . $footnote->footnote_id) }}"
                                                                                onclick="return confirm('Are you sure ?')"><i
                                                                                    class="bg-danger btn-sm fa fa-trash p-1 text-white"></i></a>
                                                                        </div>
                                                                    </div>

                                                                    <div class="show-footnote" style="display: block">
                                                                        {{-- footnote for section --}}
                                                                        <input type="hidden"
                                                                            name="sec_footnote_id[{{ $s }}][{{ $f }}]"
                                                                            value="{{ $footnote->footnote_id }}">

                                                                        <textarea type="text" name="sec_footnote_content[{{ $s }}][{{ $f }}]"
                                                                            class="form-control ckeditor-replace footnote">{{ $footnote->footnote_content }}</textarea>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif

                                                @if (count($section->footnoteModel) < 1)
                                                    <div class="col-md-12 px-0 py-3">
                                                        <div class="float-right">
                                                            <span style="font-size: small;"
                                                                class="px-2 text-uppercase font-weight-bold">
                                                                (Add footnote for section)
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
                                                @endif

                                            </div>
                                            @if ($sub_section_f->count() > 0 || $count > 0)
                                                <div class="col-md-12 px-0 py-3">
                                                    <div class="float-right">
                                                        <span style="font-size: small;"
                                                            class="px-2 text-uppercase font-weight-bold">
                                                            (for add and remove Sub-Section and
                                                            Footnote)
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
                                            @endif
                                        </div>


                                        @if ($sub_section_f->count() > 0 || $count > 0)
                                            @foreach ($sub_section_f as $k => $subSectionItem)
                                                <div class="multi-addition-container col-md-12 px-0">
                                                    <div class="multi-addition">
                                                        {{-- @foreach ($subSectionItem->footnoteModel as $f => $footnoteItem) --}}
                                                        <input type="hidden" name="sub_section_id[{{ $k }}]"
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
                                                                                class="fa {{ $subSectionItem->sub_section_no ? 'fa-plus' : 'fa-minus' }}"></i>
                                                                        </button>
                                                                    </span>
                                                                </label>
                                                                <div class="show-sub_section">
                                                                    <span class="d-flex">
                                                                        <input type="text"
                                                                            name="sub_section_no[{{ $k }}]"
                                                                            class="form-control mb-3"
                                                                            value="{{ $subSectionItem->sub_section_no ?? '' }}"
                                                                            placeholder="Enter Sub-Section No."
                                                                            style="width: 20%;"
                                                                            data-index="{{ $k }}"">

                                                                    </span>
                                                                    <textarea type="text" name="sub_section_content[{{ $k }}]"
                                                                        class="form-control ckeditor-replace sub_section">{{ $subSectionItem->sub_section_content ?? '' }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if (count($subSectionItem->footnoteModel) > 0)
                                                            @foreach ($subSectionItem->footnoteModel as $a => $footnoteItem)
                                                                <input type="hidden"
                                                                    name="sub_footnote_id[{{ $k }}][{{ $a }}]"
                                                                    value="{{ $footnoteItem->footnote_id }}">
                                                                <div class="border col-md-12 p-3">
                                                                    <div
                                                                        class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12">
                                                                        <div class="d-flex justify-content-between">
                                                                            <label class="float-label">
                                                                                Add Footnote
                                                                                <span class="pl-2">
                                                                                    <button type="button"
                                                                                        class="btn btn-sm social facebook p-0 add-footnote">
                                                                                        <i
                                                                                            class="fa {{ !empty($footnoteItem->footnote_content) ? 'fa-minus' : 'fa-plus' }}"></i>
                                                                                    </button>
                                                                                </span>
                                                                            </label>
                                                                            <div>
                                                                                <a href="{{ url('/delete_footnote/' . $footnoteItem->footnote_id) }}"
                                                                                    onclick="return confirm('Are you sure ?')"><i
                                                                                        class="bg-danger btn-sm fa fa-trash p-1 text-white"></i></a>
                                                                            </div>
                                                                        </div>

                                                                        <div class="show-footnote">
                                                                            <textarea type="text" name="sub_footnote_content[{{ $k }}][{{ $a }}]"
                                                                                class="form-control ckeditor-replace footnote">{{ $footnoteItem->footnote_content ?? '' }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div class="footnote2-addition-container">
                                                                <div class="col-md-12 px-0 py-3">
                                                                    <div class="float-right">
                                                                        <span style="font-size: small;"
                                                                            class="px-2 text-uppercase font-weight-bold">
                                                                            (add Footnote for sub-section)
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
                                                        @endif

                                                        <div class="col-md-12 px-0 py-3">
                                                            <div class="float-right">
                                                                <span style="font-size: small;"
                                                                    class="px-2 text-uppercase font-weight-bold">
                                                                    (for add and remove Sub-Section and
                                                                    Footnote)
                                                                </span>
                                                                <button type="button"
                                                                    class="btn btn-sm social facebook p-0 add-multi-addition">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                {{-- <button type="button"
                                                                    class="btn btn-sm social youtube p-0 remove-multi-addition">
                                                                    <i class="fa fa-minus"></i>
                                                                </button> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <!-- If there are no subsections or footnotes, show the default section -->
                                            <div class="multi-addition-container col-md-12 px-0">
                                                <div class="multi-addition">
                                                    <div class="col-md-12 px-0 py-3">
                                                        <div class="float-right">
                                                            <span style="font-size: small;"
                                                                class="px-2 text-uppercase font-weight-bold">
                                                                (for add and remove Sub-Section and
                                                                Footnote)
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
    <script src="https://cdn.ckeditor.com/4.16.2/full-all/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            CKEDITOR.replace('c_title');
            CKEDITOR.replace('p_title');
            CKEDITOR.replace('m_title');
            CKEDITOR.replace('section');
            CKEDITOR.replace('section_title');
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


            function initializeCKEditor() {
                // Find dynamically added textareas and replace them with CKEditor
                $('.ckeditor-replace').each(function() {
                    CKEDITOR.replace(this);
                });
            }

            $(document).ready(function() {
                initializeCKEditor();
            });

            let sectionCounter = 1;
            let sub_sectionCounter = 0;
            let subSectionIndex = 0;
            let currentIndex;

            // for adding sub section and footnote
            $(document).on('click', '.add-multi-addition', function() {
                var lastInput = $('[data-index]:last').data('index');
                // Find the clicked element's index
                var clickedIndex = $(this).closest('.multi-addition').index();

                // Find the maximum sectionCounterIndex among all elements
                var maxSectionCounterIndex = 0;

                $('.multi-addition').each(function() {
                    var index = parseInt($(this).find('[data-index]').data('index'));
                    if (!isNaN(index) && index > maxSectionCounterIndex) {
                        maxSectionCounterIndex = index;
                    }
                });

                // Calculate the new sectionCounterIndex based on the clicked index
                var sectionCounterIndex = Math.max(clickedIndex, maxSectionCounterIndex) + 1;


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
                                                <span class="d-flex"><input type="text" name="sub_section_no[${sectionCounterIndex}]" class="form-control mb-3" style="width: 20%" placeholder="Enter Sub-Section No." data-index="${sectionCounterIndex}">  </span>
                                                <textarea type="text" name="sub_section_content[${sectionCounterIndex}]" class="form-control ckeditor-replace sub_section" placeholder="Enter Sub-Section Ttile"></textarea>
                                            </div>
                                        </div>
                                    
                                        <div class="footnote2-addition-container">
                                                            <div class="col-md-12 px-0 py-3">
                                                                <div class="float-right">
                                                                    <span style="font-size: small;"
                                                                        class="px-2 text-uppercase font-weight-bold">
                                                                        (Add footnote for sub-section)
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


                // $('.multi-addition-container').append(newSection);
                var $clickedElement = $(this).closest('.multi-addition');
                $clickedElement.after(newSection);



                CKEDITOR.replace($('.multi-addition:last').find('.ckeditor-replace')[0]);
                CKEDITOR.replace($('.multi-addition:last').find('.ckeditor-replace')[1]);

                // Update sub_section_no and sub_section_content names in all elements
                $('.multi-addition').each(function(index) {
                    var newIndex = index + 1;
                    $(this).find(`[name^="sub_section_no["]`).attr('name',
                        `sub_section_no[${newIndex}]`);
                    $(this).find(`[name^="sub_section_content["]`).attr('name',
                        `sub_section_content[${newIndex}]`);
                    $(this).find('[data-index]').attr('data-index', newIndex);
                });

                sectionCounter++;
                sub_sectionCounter = 0;

            });

            $(document).on('click', '.remove-multi-addition', function() {
                var $clickedElement = $(this).closest('.multi-addition');

                if ($('.multi-addition').length > 0) {
                    $clickedElement.remove();

                    // Find the index of the clicked element
                    var index = $('.multi-addition').index($clickedElement);
                }
            });

            //  for sub section footnote 
            $(document).on('click', '.add-multi-footnote2', function() {
                // Find the closest multi-addition container
                var multiAdditionContainer = $(this).closest('.multi-addition');

                // Find the associated sub_section_no within the multi-addition container
                var associatedSubSectionTitle = multiAdditionContainer.find('[name^="sub_section_no["]');

                // Check if the associatedSubSectionTitle is found
                if (associatedSubSectionTitle.length > 0) {
                    // Extract the index from the name attribute of the sub_section_no
                    var sectionIndexMatch = associatedSubSectionTitle.attr('name').match(/\[(\d*)\]/);

                    // Set currentIndex to 0 if the index is empty
                    var currentIndex = sectionIndexMatch && sectionIndexMatch[1] !== '' ?
                        parseInt(sectionIndexMatch[1], 10) : 0;

                    console.log('Current index of sub_section_no:', currentIndex);

                    var newSection = `<div class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12 footnote2-addition">
                            <label class="float-label">
                            Add Footnote
                            <span class="pl-2">
                                <button type="button" class="btn btn-sm social facebook p-0 add-footnote">
                                <i class="fa fa-plus"></i>
                                </button>
                            </span>
                            </label>
                            <div class="show-footnote" style="display: none">
                                <textarea type="text" name="sub_footnote_content[${currentIndex}][${sub_sectionCounter}]" class="form-control ckeditor-replace footnote"></textarea>
                            </div>
                          
                        </div>`;

                    // Find the footnote2-addition-container within the multi-addition container
                    var footnote2AdditionContainer = multiAdditionContainer.find(
                        '.footnote2-addition-container');
                    footnote2AdditionContainer.append(newSection);

                    // CKEDITOR.replace(footnote2AdditionContainer.find('.footnote2-addition:last').find(
                    //     '.ckeditor-replace')[0]);
                    setTimeout(function() {
                        CKEDITOR.replace($('.footnote2-addition:last').find('.ckeditor-replace')[
                            0]);
                    }, 100); // Adjust the delay as needed

                    subSectionIndex = sub_sectionCounter;
                    sub_sectionCounter++;
                } else {
                    console.error('Associated sub_section_no not found.');
                }
            });

            $(document).on('click', '.remove-multi-footnote2', function() {
                if ($('.footnote2-addition').length > 0) {
                    $('.footnote2-addition:last').remove();
                }
            });

            // for section footnote 
            $(document).on('click', '.add-multi-footnote', function() {

                var lastInputFoot = $('[data-footsecindex]:last').data('footsecindex');
                var lastInputSec = $('[data-secindex]:last').data('secindex');
                // console.log(lastInputFoot);
                var footCounterIndex = parseInt(lastInputFoot) + 1;
                // console.log(footCounterIndex);

                var newSection = `<div class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12 footnote-addition">
                                        <label class="float-label">
                                        Add Footnote
                                        <span class="pl-2">
                                            <button type="button" class="btn btn-sm social facebook p-0 add-footnote">
                                            <i class="fa fa-plus"></i>
                                            </button>
                                        </span>
                                        </label>
                                        <div class="show-footnote" style="display: none">
                                            <textarea type="text" name="sec_footnote_content[${lastInputSec}][${footCounterIndex}]" class="form-control ckeditor-replace footnote"></textarea>
                                        </div>
                                   
                                       
                                    </div>
                                    
                                `;

                $('.footnote-addition-container').append(newSection);

                CKEDITOR.replace($('.footnote-addition:last').find('.ckeditor-replace')[0]);
                // CKEDITOR.replace($('.footnote-addition:last').find('.ckeditor-replace')[1]);

                sectionCounter++; // Increment the counter for the next section
            });

            $(document).on('click', '.remove-multi-footnote', function() {
                if ($('.footnote-addition').length > 0) {
                    $('.footnote-addition:last').remove();
                }
            });


        });
    </script>
@endsection
