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
                        <a href="/add-act/{{ $act_id }}"><button class="btn btn-success">Add Index</button></a>
                    </ol>
                </div>
            </div>
        </div>
    </div>
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
    <div class="content mt-3">
        <div class="row">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Section List</strong>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered text-center">
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
                                @php $a=1; $b=1; @endphp
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
                                        <td class="text-capitalize">@php echo $b++; @endphp</td>
                                        <td class="text-capitalize">{{ $item->section_title }}</td>
                                        <td class="text-capitalize">{{ $item->updated_at }}</td>
                                        <td class="text-capitalize d-flex justify-content-center">
                                            <a href="/edit-section/{{ $item->section_id }}" title="Edit" class="px-1"><i
                                                    class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i></a>
                                            <a href="#" title="View" class="px-1"><i
                                                    class="bg-primary btn-sm fa fa-eye p-1 text-white"></i></a>
                                            <a href="#" title="Delete" class="px-1"><i
                                                    class="bg-danger btn-sm fa fa-trash p-1 text-white"></i></a>
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
@endsection
