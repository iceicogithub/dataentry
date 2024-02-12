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
                        <a href="/get_act_section/{{ $article->act_id }}"><button class="btn btn-success">Back</button></a>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content mt-3">
        <div class="row">
            <div class="col-lg-12">
                <form id="form" action="/update_all_article/{{ $article->article_id }}" method="post"
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
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <input type="hidden" name="article_id" value="{{ $article->article_id }}">
                    <input type="hidden" name="chapter_id" value="{{ $article->chapter_id }}">
                    <input type="hidden" name="parts_id" value="{{ $article->parts_id }}">
                    <input type="hidden" name="priliminary_id" value="{{ $article->priliminary_id }}">
                    <input type="hidden" name="schedule_id" value="{{ $article->schedule_id }}">
                    <input type="hidden" name="appendices_id" value="{{ $article->appendices_id }}">
                    <div class="card p-5">
                        <div class="additional-section">
                            <div class="border col-md-12 p-3">
                                <div>
                                    <div class="form-group form-default col-md-12 px-0" id="sectionDiv">

                                        <div class="form-group form-default" style="display: block">
                                            @if ($article->maintype_id == 1)
                                                <label class="float-label font-weight-bold">Chapter :</label>

                                                <textarea name="chapter_title" class="form-control mb-3 chapter_title" placeholder="Enter Chapter Title" id="c_title">{{ $article->ChapterModel->chapter_title }}</textarea>
                                            @elseif($article->maintype_id == 2)
                                                <label class="float-label font-weight-bold">Parts :</label>

                                                <textarea name="parts_title" class="form-control mb-3 parts_title" placeholder="Enter Parts Title" id="p_title">{{ $article->Partmodel->parts_title }}</textarea>
                                            @elseif($article->maintype_id == 3)
                                                <label class="float-label font-weight-bold">Priliminary :</label>

                                                <textarea name="parts_title" class="form-control mb-3 parts_title" placeholder="Enter Parts Title" id="pr_title">{{ $article->Priliminarymodel->priliminary_title }}</textarea>
                                            @elseif($article->maintype_id == 4)
                                                <label class="float-label font-weight-bold">Schedule :</label>

                                                <textarea name="schedule_title" class="form-control mb-3 schedule_title" placeholder="Enter Schedule Title"
                                                    id="s_title">{{ $article->Schedulemodel->schedule_title }}</textarea>
                                            @elseif($article->maintype_id == 5)
                                                <label class="float-label font-weight-bold">Appendices :</label>

                                                <textarea name="appendices_title" class="form-control mb-3 appendices_title" placeholder="Enter Appendices Title"
                                                    id="a_title">{{ $article->Appendicesmodel->appendices_title }}</textarea>
                                            @else
                                                null
                                            @endif
                                        </div>

                                        <div class="form-group form-default" style="display: block">
                                            <label class="float-label font-weight-bold">Article :</label>

                                            <input type="text" name="article_no" class="form-control my-3" style="width: 20%;"
                                                placeholder="Enter Article NO." value="{{ $article->article_no }}">

                                            <textarea type="text" id="article_title" name="article_title"
                                                class="form-control section-textarea ckeditor-replace section" placeholder="Enter Article Title">{{ $article->article_title }}</textarea>

                                        </div>

                                        <div class="form-group form-default" style="display: block">
                                            <label class="float-label">Article Description<span
                                                    class="text-danger">*</span></label>
                                            <textarea type="text" id="article" name="article_content"
                                                class="form-control article-textarea ckeditor-replace article" placeholder="Enter Article">{{ $article->article_content }}</textarea>

                                            <div class="footnote-addition-container">
                                                @if ($subarticle->isNotEmpty())
                                                    @foreach ($subarticle as $s => $art)
                                                        @if ($art->footnoteModel)
                                                            @foreach ($art->footnoteModel as $f => $footnote)
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
                                                                            name="article_footnote_id[{{ $s }}][{{ $f }}]"
                                                                            value="{{ $footnote->footnote_id }}">

                                                                        <textarea type="text" name="article_footnote_content[{{ $s }}][{{ $f }}]"
                                                                            class="form-control ckeditor-replace footnote">{{ $footnote->footnote_content }}</textarea>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif

                                                @if (count($art->footnoteModel) < 1)
                                                    <div class="col-md-12 px-0 py-3">
                                                        <div class="float-right">
                                                            <span style="font-size: small;"
                                                                class="px-2 text-uppercase font-weight-bold">
                                                                (Add footnote for article)
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
                                            @if ($sub_article_f->count() > 0 || $count > 0)
                                                <div class="col-md-12 px-0 py-3">
                                                    <div class="float-right">
                                                        <span style="font-size: small;"
                                                            class="px-2 text-uppercase font-weight-bold">
                                                            (for add and remove Sub-Article and
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


                                        @if ($sub_article_f->count() > 0 || $count > 0)
                                            @foreach ($sub_article_f as $k => $subArticleItem)
                                                <div class="multi-addition-container col-md-12 px-0">
                                                    <div class="multi-addition">
                                                        {{-- @foreach ($subSectionItem->footnoteModel as $f => $footnoteItem) --}}
                                                        <input type="hidden" name="sub_article_id[{{ $k }}]"
                                                            value="{{ $subArticleItem->sub_article_id }}">
                                                        <div class="border col-md-12 p-3">
                                                            <div
                                                                class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12">
                                                                <label class="float-label">
                                                                    Add Sub-Article
                                                                    <span class="pl-2">
                                                                        <button type="button"
                                                                            class="btn btn-sm social facebook p-0 add-sub_article">
                                                                            <i
                                                                                class="fa {{ $subArticleItem->sub_article_no ? 'fa-plus' : 'fa-minus' }}"></i>
                                                                        </button>
                                                                    </span>
                                                                </label>
                                                                <div class="show-sub_article">
                                                                    <span class="d-flex">
                                                                        <input type="text"
                                                                            name="sub_article_no[{{ $k }}]"
                                                                            class="form-control mb-3"
                                                                            value="{{ $subArticleItem->sub_article_no ?? '' }}"
                                                                            placeholder="Enter Sub-Article No."
                                                                            style="width: 20%;"
                                                                            data-index="{{ $k }}"">

                                                                    </span>
                                                                    <textarea type="text" name="sub_article_content[{{ $k }}]"
                                                                        class="form-control ckeditor-replace sub_section">{{ $subArticleItem->sub_article_content ?? '' }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if (count($subArticleItem->footnoteModel) > 0)
                                                            @foreach ($subArticleItem->footnoteModel as $a => $footnoteItem)
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
                                                                            (add Footnote for sub-article)
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
                                                                    (for add and remove Sub-Article and
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
                                                                (for add and remove Sub-Article and
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
            CKEDITOR.replace('pr_title');
            CKEDITOR.replace('s_title');
            CKEDITOR.replace('a_title');
            CKEDITOR.replace('section');
            CKEDITOR.replace('state_amendment');

            // Initialize CKEditor for existing sections
            $('.ckeditor-replace.sub_article').each(function() {
                CKEDITOR.replace($(this).attr('name'));
            });

            // Initialize CKEditor for existing footnotes
            $('.ckeditor-replace.footnote').each(function() {
                CKEDITOR.replace($(this).attr('name'));
            });

            $(document).on('click', '.add-sub_article', function() {
                var icon = $(this).find('i');
                var article = $(this).closest('.form-default').find('.show-sub_article');
                article.slideToggle();
                icon.toggleClass('fa-plus fa-minus');

                // Initialize CKEditor for the new textarea
                CKEDITOR.replace(article.find('.ckeditor-replace.sub_article')[0]);
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

            let articleCounter = 1;
            let sub_articleCounter = 0;
            let subArticleIndex = 0;
            let currentIndex;

            // for adding sub section and footnote
            $(document).on('click', '.add-multi-addition', function() {
                var lastInput = $('[data-index]:last').data('index');
                // Find the clicked element's index
                var clickedIndex = $(this).closest('.multi-addition').index();

                // Find the maximum sectionCounterIndex among all elements
                var maxArticleCounterIndex = 0;

                $('.multi-addition').each(function() {
                    var index = parseInt($(this).find('[data-index]').data('index'));
                    if (!isNaN(index) && index > maxArticleCounterIndex) {
                        maxArticleCounterIndex = index;
                    }
                });

                // Calculate the new sectionCounterIndex based on the clicked index
                var ArticleCounterIndex = Math.max(clickedIndex, maxArticleCounterIndex) + 1;


                var newArticle = `
                                <div class="multi-addition">
                                    <div class="border col-md-12 p-3">
                                        <div class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12">
                                            <label class="float-label">
                                            Add Sub-Article
                                            <span class="pl-2">
                                                <button type="button" class="btn btn-sm social facebook p-0 add-sub_article">
                                                <i class="fa fa-plus"></i>
                                                </button>
                                            </span>
                                            </label>
                                            <div class="show-sub_article" style="display: none">
                                                <span class="d-flex"><input type="text" name="sub_article_no[${ArticleCounterIndex}]" class="form-control mb-3" style="width: 20%" placeholder="Enter Sub-Article No." data-index="${ArticleCounterIndex}">  </span>
                                                <textarea type="text" name="sub_article_content[${ArticleCounterIndex}]" class="form-control ckeditor-replace sub_article" placeholder="Enter Sub-Article Ttile"></textarea>
                                            </div>
                                        </div>
                                    
                                        <div class="footnote2-addition-container">
                                                            <div class="col-md-12 px-0 py-3">
                                                                <div class="float-right">
                                                                    <span style="font-size: small;"
                                                                        class="px-2 text-uppercase font-weight-bold">
                                                                        (Add footnote for sub-article)
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
                                            ( for add and remove Sub-Article and Footnote )
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
                $clickedElement.after(newArticle);



                CKEDITOR.replace($('.multi-addition:last').find('.ckeditor-replace')[0]);
                CKEDITOR.replace($('.multi-addition:last').find('.ckeditor-replace')[1]);

                // Update sub_section_no and sub_section_content names in all elements
                $('.multi-addition').each(function(index) {
                    var newIndex = index + 1;
                    $(this).find(`[name^="sub_article_no["]`).attr('name',
                        `sub_article_no[${newIndex}]`);
                    $(this).find(`[name^="sub_article_content["]`).attr('name',
                        `sub_article_content[${newIndex}]`);
                    $(this).find('[data-index]').attr('data-index', newIndex);
                });

                articleCounter++;
                sub_articleCounter = 0;

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
                var associatedSubArticleTitle = multiAdditionContainer.find('[name^="sub_article_no["]');

                // Check if the associatedSubSectionTitle is found
                if (associatedSubArticleTitle.length > 0) {
                    // Extract the index from the name attribute of the sub_section_no
                    var articleIndexMatch = associatedSubArticleTitle.attr('name').match(/\[(\d*)\]/);

                    // Set currentIndex to 0 if the index is empty
                    var currentIndex = articleIndexMatch && articleIndexMatch[1] !== '' ?
                        parseInt(articleIndexMatch[1], 10) : 0;

                    console.log('Current index of sub_article_no:', currentIndex);

                    var newArticle = `<div class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12 footnote2-addition">
                            <label class="float-label">
                            Add Footnote
                            <span class="pl-2">
                                <button type="button" class="btn btn-sm social facebook p-0 add-footnote">
                                <i class="fa fa-plus"></i>
                                </button>
                            </span>
                            </label>
                            <div class="show-footnote" style="display: none">
                                <textarea type="text" name="sub_footnote_content[${currentIndex}][${sub_articleCounter}]" class="form-control ckeditor-replace footnote"></textarea>
                            </div>
                          
                        </div>`;

                    // Find the footnote2-addition-container within the multi-addition container
                    var footnote2AdditionContainer = multiAdditionContainer.find(
                        '.footnote2-addition-container');
                    footnote2AdditionContainer.append(newArticle);

                    // CKEDITOR.replace(footnote2AdditionContainer.find('.footnote2-addition:last').find(
                    //     '.ckeditor-replace')[0]);
                    setTimeout(function() {
                        CKEDITOR.replace($('.footnote2-addition:last').find('.ckeditor-replace')[
                            0]);
                    }, 100); // Adjust the delay as needed

                    subArticleIndex = sub_ArticleCounter;
                    sub_ArticleCounter++;
                } else {
                    console.error('Associated sub_article_no not found.');
                }
            });

            $(document).on('click', '.remove-multi-footnote2', function() {
                if ($('.footnote2-addition').length > 0) {
                    $('.footnote2-addition:last').remove();
                }
            });

            // for section footnote 
            $(document).on('click', '.add-multi-footnote', function() {

                var lastInputFoot = $('[data-footarticleindex]:last').data('footarticleindex');
                var lastInputArticle = $('[data-articleindex]:last').data('articleindex');
                // console.log(lastInputFoot);
                var footCounterIndex = parseInt(lastInputFoot) + 1;
                // console.log(footCounterIndex);

                var newArticle = `<div class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12 footnote-addition">
                                        <label class="float-label">
                                        Add Footnote
                                        <span class="pl-2">
                                            <button type="button" class="btn btn-sm social facebook p-0 add-footnote">
                                            <i class="fa fa-plus"></i>
                                            </button>
                                        </span>
                                        </label>
                                        <div class="show-footnote" style="display: none">
                                            <textarea type="text" name="article_footnote_content[${lastInputArticle}][${footCounterIndex}]" class="form-control ckeditor-replace footnote"></textarea>
                                        </div>
                                   
                                       
                                    </div>
                                    
                                `;

                $('.footnote-addition-container').append(newArticle);

                CKEDITOR.replace($('.footnote-addition:last').find('.ckeditor-replace')[0]);
                // CKEDITOR.replace($('.footnote-addition:last').find('.ckeditor-replace')[1]);

                articleCounter++; // Increment the counter for the next section
            });

            $(document).on('click', '.remove-multi-footnote', function() {
                if ($('.footnote-addition').length > 0) {
                    $('.footnote-addition:last').remove();
                }
            });


        });
    </script>
@endsection
