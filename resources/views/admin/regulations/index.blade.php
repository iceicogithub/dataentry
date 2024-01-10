@extends('admin.layout.main')
@section('content')
    <div class="breadcrumbs">
        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1>Main Act : {{ $act->act_title }}</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <a href="/add-act/{{ $act_id }}" class="mr-2"><button class="btn btn-success">Add
                                Index</button></a>
                        <a href="/edit-main-act/{{ $act_id }}"><button class="btn btn-danger">Back</button></a>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-sm-12">

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
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

            <div class="card p-5">
                <form id="form" action="/update_main_act/{{ $act->act_id }}" method="post"
                    enctype="multipart/form-data" class="form form-horizontal">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-default">
                                <label class="float-label"> Act<span class="text-danger">*</span></label>
                                <input type="text" name="act_title" value="{{ $act->act_title }}"
                                    class="form-control mb-3" placeholder="Enter Act Title">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-default">
                                <label class="float-label"> Act NO.<span class="text-danger">*</span></label>
                                <input type="text" name="act_no" value="{{ $act->act_no }}" class="form-control mb-3"
                                    placeholder="Enter Act No.">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-default">
                                <label class="float-label">Act Date<span class="text-danger">*</span></label>
                                <input type="text" name="act_date" value="{{ $act->act_date }}" class="form-control mb-3"
                                    placeholder="Enter Act Date">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-default">
                                <label class="float-label"> Act Description<span class="text-danger">*</span></label>

                                <textarea name="act_description" class="form-control mb-3" placeholder="Enter Act Description" id="act_description"
                                    cols="30" rows="3">{{ $act->act_description }}</textarea>
                            </div>
                        </div>
                        <div class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12">
                            <label class="float-label">
                                Footnote
                                <span class="pl-2">
                                    <button type="button" class="btn btn-sm social facebook p-0 add-footnote">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </span>
                            </label>
                            <div class="show-footnote" style="display: none">
                                <input type="text" value="{{ $act->act_footnote_title }}" name="act_footnote_title"
                                    class="form-control mb-3" placeholder="Enter Footnote Title">
                                <textarea type="text" name="act_footnote_description" id="act_footnote"
                                    class="form-control ckeditor-replace footnote">{{ $act->act_footnote_description }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12 text-right">
                            <div class="form-group">
                                <button type="submit" class="btn  btn-success">Update Data</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="content mt-3">
        <div class="row">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Arrangement of Regulations</strong>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered text-center" id="myTable">
                            <thead class="thead-light">
                                @php
                                    use App\Models\Chapter;
                                    use App\Models\Parts;

                                    $chapter = Chapter::with('ChapterType')
                                        ->where('act_id', $act_id)
                                        ->first();
                                    $parts = Parts::with('PartsType')
                                        ->where('act_id', $act_id)
                                        ->first();

                                @endphp

                                <tr>
                                    <th scope="col">Sr.No</th>
                                    <th scope="col">
                                        @if ($chapter && $chapter->maintype_id)
                                            @if ($chapter->ChapterType->maintype_id == '1')
                                                Chapter
                                            @endif
                                        @elseif($parts && $parts->maintype_id)
                                            @if ($parts->PartsType->maintype_id == '2')
                                                Parts
                                            @endif
                                        @elseif($parts && $parts->maintype_id == 2 && ($chapter && $chapter->maintype_id == 1))
                                            @if ($parts && $parts->maintype_id == 2 && ($chapter && $chapter->maintype_id == 1))
                                                Chapter && Parts
                                            @endif
                                        @else
                                            Null
                                        @endif
                                    </th>
                                    <th scope="col">Regulation No.</th>
                                    <th scope="col">Regulation</th>
                                    <th scope="col">Date of changes</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $a = 1;
                                @endphp
                                @foreach ($act_regulation as $item)
                                    <tr>
                                        <td scope="row">@php echo $a++; @endphp</td>
                                        <td class="text-capitalize">
                                            @if ($item->maintype_id == 1)
                                                {!! $item->ChapterModel->chapter_title !!}
                                            @elseif($item->maintype_id == 2)
                                                {!! $item->Partmodel->parts_title !!}
                                            @elseif($item->maintype_id == 3)
                                                Priliminary
                                            @elseif($item->maintype_id == 4)
                                                Schedules
                                            @else
                                                Appendices
                                            @endif
                                        </td>
                                        <td class="text-capitalize">{{ $item->regulation_no }}</td>
                                        <td class="text-capitalize">{{ $item->regulation_title }}</td>
                                        <td class="text-capitalize">{{ $item->updated_at }}</td>
                                        <td class="text-capitalize d-flex justify-content-center">
                                            <a href="/edit-regulation/{{ $item->regulation_id }}" title="Edit"
                                                class="px-1">
                                                <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                            </a>
                                            <a href="#" title="View" class="px-1">
                                                <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                            </a>
                                            <a href="{{ url('/delete_regulation/' . $item->regulation_id) }}"
                                                title="Delete" class="px-1" onclick="return confirm('Are you sure ?')">
                                                <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                            </a>
                                            <a href="{{ url('/add_below_new_regulation', ['act_id' => $item->act_id, 'regulation_no' => $item->regulation_no]) }}"
                                                title="Add Next Section" class="px-1">
                                                <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.ckeditor.com/4.16.2/full-all/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      {{-- <script>
        // Initialize CKEditor
        CKEDITOR.replace('act_description', {
            extraPlugins: 'basicstyles,superscript',
            toolbar: [
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript'] },
            ],
        });
    </script> --}}
    <script>
        CKEDITOR.replace('act_description');
        CKEDITOR.replace('act_footnote');

        $(document).on('click', '.add-footnote', function() {
            var icon = $(this).find('i');
            var section = $(this).closest('.form-default').find('.show-footnote');
            section.slideToggle();
            icon.toggleClass('fa-plus fa-minus');

            // Initialize CKEditor for the new textarea
            CKEDITOR.replace(section.find('.ckeditor-replace.footnote')[0]);
        });
    </script>

@endsection
