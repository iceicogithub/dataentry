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
                        <a href="/edit-main-act/{{ $act_id }}"><button class="btn btn-success">Back</button></a>
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
                        <strong class="card-title">Arrangement of Sections</strong>
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
                                            @else
                                            @endif
                                        @elseif($parts && $parts->maintype_id)
                                            @if ($parts->PartsType->maintype_id == '2')
                                                Parts
                                            @else
                                            @endif
                                        @else
                                            Null
                                        @endif
                                    </th>
                                    <th scope="col">Section No.</th>
                                    <th scope="col">Section</th>
                                    <th scope="col">Date of changes</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $a = 1;
                                @endphp
                                @foreach ($act_section as $item)
                                    <tr>
                                        <td scope="row">@php echo $a++; @endphp</td>
                                        <td class="text-capitalize">
                                            @if ($item->maintype_id == 1)
                                                {{ $item->ChapterModel->chapter_title }}
                                            @elseif($item->maintype_id == 2)
                                                {{ $item->Partmodel->parts_title }}
                                            @elseif($item->maintype_id == 3)
                                                Priliminary
                                            @elseif($item->maintype_id == 4)
                                                Schedules
                                            @else
                                                Appendices
                                            @endif
                                        </td>
                                        <td class="text-capitalize">{{ $item->section_no }}</td>
                                        <td class="text-capitalize">{{ $item->section_title }}</td>
                                        <td class="text-capitalize">{{ $item->updated_at }}</td>
                                        <td class="text-capitalize d-flex justify-content-center">
                                            <a href="/edit-section/{{ $item->section_id }}" title="Edit" class="px-1">
                                                <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                            </a>
                                            <a href="#" title="View" class="px-1">
                                                <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                            </a>
                                            <a href="#" title="Delete" class="px-1">
                                                <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                            </a>
                                            <a href="{{ url('/add_below_new_section', ['act_id' => $item->act_id, 'section_no' => $item->section_no]) }}" title="Add Next Section" class="px-1">
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

    
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        CKEDITOR.replace('act_description');

    </script>
@endsection
