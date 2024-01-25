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
                    <input type="hidden" name="maintype_id" value="{{ $rule->maintype_id }}">
                    <input type="hidden" name="act_id" value="{{ $rule->act_id }}">
                    <input type="hidden" name="rule_rank" value="{{ $rule_rank }}">
                    <input type="hidden" name="rule_id" value="{{ $rule->rule_id }}">
                    <input type="hidden" name="schedule_id" value="{{ $rule->schedule_id }}">
                    <div class="card p-5">
                        <div class="additional-section">
                            <div class="border col-md-12 p-3">
                                <div class="form-group form-default col-md-12 px-0" id="sectionDiv">

                                    <div class="form-group form-default" style="display: block">
                                        @if ($rule->maintype_id == 4)
                                            <label class="float-label font-weight-bold">Schedule :</label>

                                            <textarea name="schedule_title" class="form-control mb-3 schedule_title" placeholder="Enter Schedule Title"
                                                id="s_title">{{ $rule->ScheduleModel->schedule_title }}</textarea>
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
                                            <div class="border col-md-12 p-3">
                                                <div
                                                    class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12">
                                                    <label class="float-label">
                                                        Add Sub-Rule
                                                        <span class="pl-2">
                                                            <button type="button"
                                                                class="btn btn-sm social facebook p-0 add-sub_rule">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </span>
                                                    </label>
                                                    <div class="show-sub_rule" style="display: none">
                                                        <span class="d-flex">
                                                            <input type="text" name="sub_rule_no[]"
                                                                class="form-control mb-3" placeholder="Enter Sub-Rule No."
                                                                style="width: 20%;" data-index="0">

                                                        </span>
                                                        <textarea type="text" name="sub_rule_content[]" class="form-control ckeditor-replace sub_rule"></textarea>
                                                    </div>
                                                </div>
                                                <div class="footnote2-addition-container">

                                                    <div class="col-md-12 px-0 py-3">
                                                        <div class="float-right">
                                                            <span style="font-size: small;"
                                                                class="px-2 text-uppercase font-weight-bold">
                                                                (add Footnote for sub-rule)
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
                var ruleCounterIndex = lastInput + 1;

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


                $('.multi-addition-container').append(newRule);

                CKEDITOR.replace($('.multi-addition:last').find('.ckeditor-replace')[0]);
                CKEDITOR.replace($('.multi-addition:last').find('.ckeditor-replace')[1]);
                ruleCounter++;
                sub_ruleCounter = 0;

            });

            $(document).on('click', '.remove-multi-addition', function() {
                if ($('.multi-addition').length > 1) {
                    $('.multi-addition:last').remove();
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
                                <div class="col-md-12 px-0 py-3">
                                    <div class="float-right">
                                        <span style="font-size: small;" class="px-2 text-uppercase font-weight-bold">
                                        ( Add Footnote sub-rule )
                                        </span>
                                        <button type="button" class="btn btn-sm social facebook p-0 add-multi-footnote2">
                                        <i class="fa fa-plus"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm social youtube p-0 remove-multi-footnote2">
                                        <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
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
                if ($('.footnote2-addition').length > 1) {
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
                                    
                                            <div class="col-md-12 px-0 py-3">
                                                <div class="float-right">
                                                    <span style="font-size: small;" class="px-2 text-uppercase font-weight-bold">
                                                    ( Add footnote for Rule)
                                                    </span>
                                                    <button type="button" class="btn btn-sm social facebook p-0 add-multi-footnote">
                                                    <i class="fa fa-plus"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm social youtube p-0 remove-multi-footnote">
                                                    <i class="fa fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    `;

                $('.footnote-addition-container').append(newRule);

                CKEDITOR.replace($('.footnote-addition:last').find('.ckeditor-replace')[0]);
                // CKEDITOR.replace($('.footnote-addition:last').find('.ckeditor-replace')[1]);

                sectionCounter++; // Increment the counter for the next section
            });

            $(document).on('click', '.remove-multi-footnote', function() {
                if ($('.footnote-addition').length > 1) {
                    $('.footnote-addition:last').remove();
                }
            });


        });
    </script>
@endsection
@endsection
