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
                        <a href="/get_act_section/{{ $rule->act_id }}"><button class="btn btn-success">Back</button></a>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content mt-3">
        <div class="row">
            <div class="col-lg-12">
                <form id="form" action="/add_new_rule" method="post"
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
                    <input type="hidden" name="click_section_rank" value="{{ $rule->rule_rank }}">
                    <input type="hidden" name="serial_no" value="{{ $rule->serial_no }}">
                    <input type="hidden" name="maintype_id" value="{{ $rule->maintype_id }}">
                    <input type="hidden" name="act_id" value="{{ $rule->act_id }}">
                    {{-- <input type="hidden" name="rule_rank" value="{{ $rule_rank }}"> --}}
                    <input type="hidden" name="rule_id" value="{{ $rule->rule_id }}">
                     



                    @if ($rule->chapter_id)
                    <input type="hidden" name="chapter_id" value="{{ $rule->chapter_id }}">
                @endif
                @if ($rule->parts_id)
                    <input type="hidden" name="parts_id" value="{{ $rule->parts_id }}">
                @endif
                @if ($rule->priliminary_id)
                    <input type="hidden" name="priliminary_id" value="{{ $rule->priliminary_id }}">
                @endif
                @if ($rule->schedule_id)
                    <input type="hidden" name="schedule_id" value="{{ $rule->schedule_id }}">
                @endif
                @if ($rule->appendix_id)
                    <input type="hidden" name="appendix_id" value="{{ $rule->appendix_id }}">
                @endif
                @if ($rule->main_order_id)
                    <input type="hidden" name="main_order_id" value="{{ $rule->main_order_id }}">
                @endif
                    <div class="card p-5">
                        <div class="additional-section">
                            <div class="border col-md-12 p-3">
                                <div class="form-group form-default col-md-12 px-0" id="sectionDiv">

                                    <div class="form-group form-default" style="display: block">
                                        @if ($rule->maintype_id == 1)
                                                <label class="float-label font-weight-bold">Chapter :</label>

                                                <textarea name="chapter_title" class="form-control mb-3 chapter_title" placeholder="Enter Chapter Title" id="c_title">{{ $rule->ChapterModel->chapter_title }}</textarea>
                                            @elseif($rule->maintype_id == 2)
                                                <label class="float-label font-weight-bold">Parts :</label>

                                                <textarea name="parts_title" class="form-control mb-3 parts_title" placeholder="Enter Parts Title" id="part_title">{{ $rule->Partmodel->parts_title }}</textarea>
                                            @elseif($rule->maintype_id == 3)
                                                <label class="float-label font-weight-bold">Priliminary :</label>

                                                <textarea name="priliminary_title" class="form-control mb-3 priliminary_title" placeholder="Enter Priliminary Title" id="p_title">{{ $rule->Priliminarymodel->priliminary_title }}</textarea>
                                            @elseif($rule->maintype_id == 4)
                                                <label class="float-label font-weight-bold">Schedule :</label>

                                                <textarea name="schedule_title" class="form-control mb-3 schedule_title" placeholder="Enter Schedule Title" id="s_title">{{ $rule->Schedulemodel->schedule_title }}</textarea>
                                            @elseif($rule->maintype_id == 5)
                                                <label class="float-label font-weight-bold">Appendix :</label>

                                                <textarea name="appendix_title" class="form-control mb-3 appendix_title" placeholder="Enter Appendix Title" id="a_title">{{ $rule->Appendixmodel->appendix_title }}</textarea>
                                            @elseif($rule->maintype_id == 6)
                                                <label class="float-label font-weight-bold">Order :</label>

                                                <textarea name="main_order_title" class="form-control mb-3 main_order_title" placeholder="Enter Order Title" id="m_title">{{ $rule->MainOrderModel->main_order_title }}</textarea>
                                            @else
                                                null
                                            @endif
                                    </div>

                                    <div class="form-group form-default" style="display: block">
                                        <label class="float-label font-weight-bold">Rules :</label>
                                        <span class="d-flex">
                                            <input type="text" name="rule_no" class="form-control" style="width: 20%;"
                                                placeholder="Enter Rule NO.">
                                            <input type="text" name="rule_title" placeholder="Enter Rule Title." class="form-control mb-3">
                                        </span>
                                    </div>

                                    <div class="form-group form-default" style="display: block">
                                        <label class="float-label">Rule Description<span
                                                class="text-danger">*</span></label>
                                        <textarea type="text" id="rule" name="rule_content" class="form-control rule-textarea ckeditor-replace rule"
                                            placeholder="Enter Rule"></textarea>

                                        <div class="footnote-addition-container">


                                            <div class="col-md-12 px-0 py-3">
                                                <div class="float-right">
                                                    <span style="font-size: small;"
                                                        class="px-2 text-uppercase font-weight-bold">
                                                        (Add footnote for rule)
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

                                    <div class="multi-addition-container col-md-12 px-0">
                                        <div class="multi-addition">
                                            <div class="col-md-12 px-0 py-3">
                                                <div class="float-right">
                                                    <span style="font-size: small;"
                                                        class="px-2 text-uppercase font-weight-bold">
                                                        (for add and remove Sub-Rule and
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

                                </div>

                                <div class="form-group form-default" id="articleDiv" style="display: none">
                                    <input type="text" class="form-control mb-3" placeholder="Enter Article Title">
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
                </form>
            </div>
        </div>
    </div>

@section('script')
    <script src="https://cdn.ckeditor.com/4.16.2/full-all/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            CKEDITOR.replace('s_title');
            CKEDITOR.replace('rule');
            CKEDITOR.replace('p_title');
            CKEDITOR.replace('s_title');
            CKEDITOR.replace('a_title');
            CKEDITOR.replace('m_title');
            CKEDITOR.replace('state_amendment');

            // Initialize CKEditor for existing sections
            $('.ckeditor-replace.sub_rule').each(function() {
                CKEDITOR.replace($(this).attr('name'));
            });

            // Initialize CKEditor for existing footnotes
            $('.ckeditor-replace.footnote').each(function() {
                CKEDITOR.replace($(this).attr('name'));
            });

            $(document).on('click', '.add-sub_rule', function() {
                var icon = $(this).find('i');
                var rule = $(this).closest('.form-default').find('.show-sub_rule');
                rule.slideToggle();
                icon.toggleClass('fa-plus fa-minus');

                // Initialize CKEditor for the new textarea
                CKEDITOR.replace(rule.find('.ckeditor-replace.sub_rule')[0]);
            });

            $(document).on('click', '.add-footnote', function() {
                var icon = $(this).find('i');
                var rule = $(this).closest('.form-default').find('.show-footnote');
                rule.slideToggle();
                icon.toggleClass('fa-plus fa-minus');

                // Initialize CKEditor for the new textarea
                CKEDITOR.replace(rule.find('.ckeditor-replace.footnote')[0]);
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

            let ruleCounter = 1;
            let sub_ruleCounter = 0;
            let subRuleIndex = 0;
            let currentIndex;

            $(document).on('click', '.add-multi-addition', function() {
                var lastInput = $('[data-index]:last').data('index');
                // Find the clicked element's index
                var clickedIndex = $(this).closest('.multi-addition').index();

                // Find the maximum sectionCounterIndex among all elements
                var maxRuleCounterIndex = 0;

                $('.multi-addition').each(function() {
                    var index = parseInt($(this).find('[data-index]').data('index'));
                    if (!isNaN(index) && index > maxRuleCounterIndex) {
                        maxRuleCounterIndex = index;
                    }
                });

                // Calculate the new sectionCounterIndex based on the clicked index
                var ruleCounterIndex = Math.max(clickedIndex, maxRuleCounterIndex) + 1;


                var newRule = `
                                    <div class="multi-addition">
                                        <div class="border col-md-12 p-3">
                                            <div class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12">
                                                <label class="float-label">
                                                Add Sub-Rule
                                                <span class="pl-2">
                                                    <button type="button" class="btn btn-sm social facebook p-0 add-sub_rule">
                                                    <i class="fa fa-plus"></i>
                                                    </button>
                                                </span>
                                                </label>
                                                <div class="show-sub_rule" style="display: none">
                                                    <span class="d-flex"><input type="text" name="sub_rule_no[${ruleCounterIndex}]" class="form-control mb-3" style="width: 20%" placeholder="Enter Sub-Rule No." data-index="${ruleCounterIndex}">  </span>
                                                    <textarea type="text" name="sub_rule_content[${ruleCounterIndex}]" class="form-control ckeditor-replace sub_rule"></textarea>
                                                </div>
                                            </div>
                                        
                                            <div class="footnote2-addition-container">
                                                                <div class="col-md-12 px-0 py-3">
                                                                    <div class="float-right">
                                                                        <span style="font-size: small;"
                                                                            class="px-2 text-uppercase font-weight-bold">
                                                                            (Add footnote for sub-rule)
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
                                                ( for add and remove Sub-Rule and Footnote )
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
                $clickedElement.after(newRule);

                CKEDITOR.replace($('.multi-addition:last').find('.ckeditor-replace')[0]);
                CKEDITOR.replace($('.multi-addition:last').find('.ckeditor-replace')[1]);
               
                // Update sub_section_no and sub_section_content names in all elements
                $('.multi-addition').each(function(index) {
                    var newIndex = index + 1;
                    $(this).find(`[name^="sub_rule_no["]`).attr('name',
                        `sub_rule_no[${newIndex}]`);
                    $(this).find(`[name^="sub_rule_content["]`).attr('name',
                        `sub_rule_content[${newIndex}]`);
                    $(this).find('[data-index]').attr('data-index', newIndex);
                });

                ruleCounter++;
                sub_ruleCounter = 0;

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
                var associatedSubRuleTitle = multiAdditionContainer.find('[name^="sub_rule_no["]');

                // Check if the associatedSubSectionTitle is found
                if (associatedSubRuleTitle.length > 0) {
                    // Extract the index from the name attribute of the sub_section_no
                    var ruleIndexMatch = associatedSubRuleTitle.attr('name').match(/\[(\d*)\]/);

                    // Set currentIndex to 0 if the index is empty
                    var currentIndex = ruleIndexMatch && ruleIndexMatch[1] !== '' ?
                        parseInt(ruleIndexMatch[1], 10) : 0;

                    console.log('Current index of sub_rule_no:', currentIndex);

                    var newRule = `<div class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12 footnote2-addition">
                                <label class="float-label">
                                Add Footnote
                                <span class="pl-2">
                                    <button type="button" class="btn btn-sm social facebook p-0 add-footnote">
                                    <i class="fa fa-plus"></i>
                                    </button>
                                </span>
                                </label>
                                <div class="show-footnote" style="display: none">
                                    <textarea type="text" name="sub_footnote_content[${currentIndex}][${sub_ruleCounter}]" class="form-control ckeditor-replace footnote"></textarea>
                                </div>
                            </div>`;

                    // Find the footnote2-addition-container within the multi-addition container
                    var footnote2AdditionContainer = multiAdditionContainer.find(
                        '.footnote2-addition-container');
                    footnote2AdditionContainer.append(newRule);

                    // CKEDITOR.replace(footnote2AdditionContainer.find('.footnote2-addition:last').find(
                    //     '.ckeditor-replace')[0]);
                    setTimeout(function() {
                        CKEDITOR.replace($('.footnote2-addition:last').find('.ckeditor-replace')[
                            0]);
                    }, 100); // Adjust the delay as needed

                    subRuleIndex = sub_ruleCounter;
                    sub_ruleCounter++;
                } else {
                    console.error('Associated sub_rule_no not found.');
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

                var newRule = `<div class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12 footnote-addition">
                                            <label class="float-label">
                                            Add Footnote
                                            <span class="pl-2">
                                                <button type="button" class="btn btn-sm social facebook p-0 add-footnote">
                                                <i class="fa fa-plus"></i>
                                                </button>
                                            </span>
                                            </label>
                                            <div class="show-footnote" style="display: none">
                                                <textarea type="text" name="rule_footnote_content[${lastInputSec}][${footCounterIndex}]" class="form-control ckeditor-replace footnote"></textarea>
                                            </div>
                                        </div>
                                        
                                    `;

                $('.footnote-addition-container').append(newRule);

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
@endsection
