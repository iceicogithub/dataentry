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
                        <a href="{{ url('/edit_new_scheme_guidelines/' . $schemeGuidelinesTable->mainschemeGuidelines->new_scheme_guidelines_id. '?perPage=10&page=' . $currentPage) }}"><button class="btn btn-success">Back</button></a>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content mt-3">
        <div class="row">
            <div class="col-lg-12">
                <form id="form" action="/add_new_schemeGuidelinestbl" method="post"
                    enctype="multipart/form-data" class="form form-horizontal">
                    @csrf
                    
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
                    <input type="hidden" name="click_scheme_guidelines_id" value="{{ $schemeGuidelinesTable->scheme_guidelines_id }}">
                    <input type="hidden" name="click_scheme_guidelines_rank" value="{{ $schemeGuidelinesTable->scheme_guidelines_rank }}">
                    <input type="hidden" name="currentPage" value="{{ $currentPage }}">
                    <input type="hidden" name="scheme_guidelines_main_id" value="{{ $schemeGuidelinesTable->scheme_guidelines_main_id }}">
                    <input type="hidden" name="new_scheme_guidelines_id" value="{{ $schemeGuidelinesTable->new_scheme_guidelines_id }}">
                    <input type="hidden" name="scheme_guidelines_subtypes_id" value="{{ $schemeGuidelinesTable->scheme_guidelines_subtypes_id }}">
                    <div class="card p-5">
                        <div class="additional-section">
                            <div class="border col-md-12 p-3">
                                <div>
                                    <div class="form-group form-default col-md-12 px-0" id="sectionDiv">

                                        <div class="form-group form-default" style="display: block">
                                            @if ($schemeGuidelinesTable->mainschemeGuidelines->scheme_guidelines_maintype_id == 1)
                                            <label class="float-label font-weight-bold">Chapter :</label>
                                            @elseif($schemeGuidelinesTable->mainschemeGuidelines->scheme_guidelines_maintype_id == 2)
                                            <label class="float-label font-weight-bold">Schedule :</label>
                                            @elseif($schemeGuidelinesTable->mainschemeGuidelines->scheme_guidelines_maintype_id == 3)
                                            <label class="float-label font-weight-bold">Preliminary :</label>
                                            @elseif($schemeGuidelinesTable->mainschemeGuidelines->scheme_guidelines_maintype_id == 4)
                                            <label class="float-label font-weight-bold">Part :</label>
                                            @elseif($schemeGuidelinesTable->mainschemeGuidelines->scheme_guidelines_maintype_id == 5)
                                            <label class="float-label font-weight-bold">Appendix :</label>
                                            @elseif($schemeGuidelinesTable->mainschemeGuidelines->scheme_guidelines_maintype_id == 6)
                                            <label class="float-label font-weight-bold"> Form :</label>
                                            @else
                                            null
                                            @endif
                                            <textarea name="scheme_guidelines_main_title" class="form-control mb-3 rule_main_title" placeholder="Enter Title" id="scheme_guidelines_main_title">{{ $schemeGuidelinesTable->mainschemeGuidelines->scheme_guidelines_main_title }}</textarea>
                                        </div>

                                        <div class="form-group form-default" style="display: block">
                                            @if ($schemeGuidelinesTable->scheme_guidelines_subtypes_id == 1)
                                            <label class="float-label font-weight-bold">Scheme :</label>
                                            @elseif($schemeGuidelinesTable->scheme_guidelines_subtypes_id == 2)
                                            <label class="float-label font-weight-bold">Guidelines :</label>
                                            @elseif($schemeGuidelinesTable->scheme_guidelines_subtypes_id == 3)
                                            <label class="float-label font-weight-bold">List :</label>
                                            @elseif($schemeGuidelinesTable->scheme_guidelines_subtypes_id == 4)
                                            <label class="float-label font-weight-bold">Part :</label>
                                            @elseif($schemeGuidelinesTable->scheme_guidelines_subtypes_id == 5)
                                            <label class="float-label font-weight-bold">Appendices :</label>
                                            @elseif($schemeGuidelinesTable->scheme_guidelines_subtypes_id == 6)
                                            <label class="float-label font-weight-bold">Order :</label>
                                            @elseif($schemeGuidelinesTable->scheme_guidelines_subtypes_id == 7)
                                            <label class="float-label font-weight-bold">Annexure :</label>
                                            @elseif($schemeGuidelinesTable->scheme_guidelines_subtypes_id == 8)
                                            <label class="float-label font-weight-bold">Schedule :</label>
                                            @elseif($schemeGuidelinesTable->scheme_guidelines_subtypes_id == 9)
                                            <label class="float-label font-weight-bold">Form :</label>
                                            @else
                                            null
                                            @endif
                                            
                                            <input type="text" name="scheme_guidelines_no" class="form-control my-3"
                                                style="width: 20%;" placeholder="Enter NO."
                                                value="">
                                                <textarea type="text" id="scheme_guidelines_title" name="scheme_guidelines_title"
                                                class="form-control section-textarea ckeditor-replace section" placeholder="Enter Title"></textarea>
                                               
                                            
                                        </div>

                                        <div class="form-group form-default" style="display: block">
                                            @if ($schemeGuidelinesTable->scheme_guidelines_subtypes_id == 1)
                                            <label class="float-label font-weight-bold">Scheme Description :</label>
                                            @elseif($schemeGuidelinesTable->scheme_guidelines_subtypes_id == 2)
                                            <label class="float-label font-weight-bold">Guidelines Description:</label>
                                            @elseif($schemeGuidelinesTable->scheme_guidelines_subtypes_id == 3)
                                            <label class="float-label font-weight-bold">List Description:</label>
                                            @elseif($schemeGuidelinesTable->scheme_guidelines_subtypes_id == 4)
                                            <label class="float-label font-weight-bold">Part Description:</label>
                                            @elseif($schemeGuidelinesTable->scheme_guidelines_subtypes_id == 5)
                                            <label class="float-label font-weight-bold">Appendices Description:</label>
                                            @elseif($schemeGuidelinesTable->scheme_guidelines_subtypes_id == 6)
                                            <label class="float-label font-weight-bold">Order Description:</label>
                                            @elseif($schemeGuidelinesTable->scheme_guidelines_subtypes_id == 7)
                                            <label class="float-label font-weight-bold">Annexure Description:</label>
                                            @elseif($schemeGuidelinesTable->scheme_guidelines_subtypes_id == 8)
                                            <label class="float-label font-weight-bold">Schedule Description:</label>
                                            @elseif($schemeGuidelinesTable->scheme_guidelines_subtypes_id == 9)
                                            <label class="float-label font-weight-bold">Form Description:</label>
                                            @else
                                            null
                                            @endif
                                            <textarea type="text" id="scheme_guidelines_content" name="scheme_guidelines_content"
                                                class="form-control section-textarea ckeditor-replace section" placeholder="Enter Section"></textarea>

                                            <div class="footnote-addition-container">
                                                <div class="col-md-12 px-0 py-3">
                                                    <div class="float-right">
                                                        <span style="font-size: small;"
                                                            class="px-2 text-uppercase font-weight-bold">
                                                            (footnote)
                                                        </span>
                                                        <button type="button"
                                                            class="btn btn-sm social facebook p-0 add-multi-footnote">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-sm social youtube p-0 remove-multi-footnote">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                     
                                        <div class="multi-addition-container col-md-12 px-0">
                                            <div class="multi-addition">
                                                <div class="col-md-12 px-0 py-3">
                                                    <div class="float-right sub_section">
                                                        <span style="font-size: small;"
                                                            class="px-2 text-uppercase font-weight-bold">
                                                           (Sub Type)
                                                        </span>
                                                        <button type="button"
                                                            class="btn btn-sm social facebook p-0 add-multi-addition">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

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
            CKEDITOR.replace('scheme_guidelines_main_title');
            CKEDITOR.replace('rules_content');
            CKEDITOR.replace('rules_title');
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
                var schemeGuidelinesSubtypesId = {{ $schemeGuidelinesTable->scheme_guidelines_subtypes_id }};
                console.log(schemeGuidelinesSubtypesId);

                var labelText;

switch (schemeGuidelinesSubtypesId) {
    case 1:
        labelText = "Add Sub Scheme :";
        break;
    case 2:
        labelText = "Add Sub Guidelines :";
        break;
    case 3:
        labelText = "Add Sub List :";
        break;
    case 4:
        labelText = "Add Sub Part :";
        break;
    case 5:
        labelText = "Add Sub Appendices :";
        break;
    case 6:
        labelText = "Add Sub Order :";
        break;
    case 7:
        labelText = "Add Sub Annexure :";
        break;
    case 8:
        labelText = "Add Sub Schedule :";
        break;
    case 9:
        labelText = "Add Sub Form :";
        break;
    default:
        labelText = "null";
}
                var lastInput = $('[data-index]:last').data('index');
                // Find the clicked element's index
                var clickedIndex = $(this).closest('.multi-addition').index();
                console.log(clickedIndex);

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
                                    <div class="border col-md-12 p-3 sub_section_box">
                                        <button type="button"
                                            class="btn btn-sm social youtube p-0 remove-multi-addition">
                                                 <i class="fa fa-trash"></i>
                                         </button>

                                        <div>
                                            <div class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12">
                                                <label class="float-label">
                                                    ${labelText}
                                                <span class="pl-2">
                                                    <button type="button" class="btn btn-sm social facebook p-0 add-sub_section">
                                                    <i class="fa fa-plus"></i>
                                                    </button>
                                                </span>
                                                </label>
                                                <div class="show-sub_section" style="display: none">
                                                    <span class="d-flex"><input type="text" name="scheme_guidelines_sub_no[${sectionCounterIndex}]" class="form-control mb-3" style="width: 20%" placeholder="Enter No." data-index="${sectionCounterIndex}">  </span>
                                                    <textarea type="text" name="scheme_guidelines_sub_content[${sectionCounterIndex}]" class="form-control ckeditor-replace sub_section" placeholder="Enter Ttile"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    
                                        <div class="footnote2-addition-container">
                                            <div class="col-md-12 px-0 py-3">
                                                <div class="float-right">
                                                    <span style="font-size: small;"
                                                        class="px-2 text-uppercase font-weight-bold">
                                                        (footnote)
                                                    </span>
                                                    <button type="button"
                                                        class="btn btn-sm social facebook p-0 add-multi-footnote2">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                    <button type="button"
                                                        class="btn btn-sm social youtube p-0 remove-multi-footnote2">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                   
                                </div>
                                `;

                              

                // $('.multi-addition-container').append(newSection);
                // var $clickedElement = $(this).closest('.multi-addition');
                $('.multi-addition').last().after(newSection);



                CKEDITOR.replace($('.multi-addition:last').find('.ckeditor-replace')[0]);
                CKEDITOR.replace($('.multi-addition:last').find('.ckeditor-replace')[1]);

                // Update sub_section_no and sub_section_content names in all elements
                $('.multi-addition').each(function(index) {
                    var newIndex = index;
                    $(this).find(`[name^="scheme_guidelines_sub_no["]`).attr('name',
                        `scheme_guidelines_sub_no[${newIndex}]`);
                    $(this).find(`[name^="scheme_guidelines_sub_content["]`).attr('name',
                        `scheme_guidelines_sub_content[${newIndex}]`);
                    $(this).find('[data-index]').attr('data-index', newIndex);
                });

                sectionCounter++;
                sub_sectionCounter = 0;

            });
            $(document).on('click', '.remove-multi-addition', function() {
                $(this).closest('.multi-addition').remove();
            
            });
            //  for sub section footnote 
            $(document).on('click', '.add-multi-footnote2', function() {
                // Find the closest multi-addition container
                var multiAdditionContainer = $(this).closest('.multi-addition');

                // Find the associated sub_section_no within the multi-addition container
                var associatedSubSectionTitle = multiAdditionContainer.find('[name^="scheme_guidelines_sub_no["]');

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
                    $(this).hide();

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
                // Find the container for the current footnote2 addition
                var footnote2AdditionContainer = $(this).closest('.footnote2-addition-container');
                
                // Find the last footnote2 addition within the current container
                var lastFootnote2Addition = footnote2AdditionContainer.find('.footnote2-addition:last');

                if (lastFootnote2Addition.length > 0) {
                    // Remove the last footnote2 addition
                    lastFootnote2Addition.remove();
                    
                    // Check if there are any remaining footnotes in this sub-section
                    if (footnote2AdditionContainer.find('.footnote2-addition').length === 0) {
                        // Show the corresponding "+ Add Footnote" button for this sub-section
                        footnote2AdditionContainer.find('.add-multi-footnote2').show();
                    }
                }
            });

            // for section footnote 
            $(document).on('click', '.add-multi-footnote', function() {
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
                                            <textarea type="text" name="sec_footnote_content" class="form-control ckeditor-replace footnote"></textarea>
                                        </div>
                                    </div>`;

                        $('.footnote-addition-container').html(newSection); // Replace existing content with new section
                        $(this).hide();

                        CKEDITOR.replace($('.footnote-addition:last').find('.ckeditor-replace')[0]);

                        sectionCounter++; // Increment the counter for the next section
            });

            $(document).on('click', '.remove-multi-footnote', function() {
                if ($('.footnote-addition').length > 0) {
                    $('.footnote-addition:last').remove();
                    $('.add-multi-footnote').show();
                }
            });


        });
    </script>
@endsection
