@extends('admin.layout.main')
@section('content')
    <div class="breadcrumbs">
        <div class="col-sm-8">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1>Section : {{ $section->section_title }}</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <a href="/get_act_section/{{$section->act_id}}"><button class="btn btn-danger">Back</button></a>
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


        </div>
    </div>

    <div class="content mt-3">
        <div class="row">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Arrangement of Sub-Sections</strong>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered text-center" id="myTable">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Sr.No</th>
                                    <th scope="col">Sub-Section No.</th>
                                    <th scope="col">Sub-Section</th>
                                    <th scope="col">Date of changes</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $a = 1;
                                @endphp
                                @foreach ($sub_section as $item)
                                    <tr>
                                        <td scope="row">@php echo $a++; @endphp</td>
                                        <td class="text-capitalize">{{ $item->sub_section_no }}</td>
                                        <td class="text-capitalize">{!! $item->sub_section_content !!}</td>
                                        <td class="text-capitalize">{{ $item->updated_at }}</td>
                                        <td class="text-capitalize d-flex justify-content-center">
                                            <a href="{{ url('/delete_sub_section/' . $item->sub_section_id) }}" title="Delete"
                                                class="px-1" onclick="return confirm('Are you sure ?')">
                                                <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
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
  
    <script>
        CKEDITOR.replace('act_no');
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
