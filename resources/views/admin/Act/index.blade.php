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
                        <a href="{{ Route('new_act') }}"><button class="btn btn-success">Add Act</button></a>
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

    <div class="content mt-3">
        <div class="row">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Act Table</strong>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered text-center" id="myTable">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Sr .No</th>
                                    <th scope="col">Act</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">State</th>
                                    <th scope="col">Last Date Of Edited</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @php $a=1; @endphp
                                @foreach ($act as $item)
                                    <tr>
                                        <td scope="row">@php echo $a++; @endphp</td>
                                        <td class="text-capitalize">{{ $item->act_title }}</td>
                                        <td class="text-capitalize">{{ $item->CategoryModel->category }}</td>
                                        <td class="text-capitalize">All</td>
                                        <td class="text-capitalize">{{ $item->updated_at }}</td>
                                        {{-- <td class="text-capitalize">
                                            <span>
                                                <a href="/get_act_section/{{ $item->act_id }}" title="View Section" class="btn btn-primary rounded px-3 btn-sm">View</a>
                                            </span>
                                        </td>
                                         --}}
                                        <td class="text-capitalize d-flex">
                                            <a href="/edit-main-act/{{$item->act_id}}" title="Edit" class="px-1"><i
                                                    class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i></a>
                                            <a href="/view-main-act/{{$item->act_id}}" title="View" class="px-1"><i
                                                    class="bg-primary btn-sm fa fa-eye p-1 text-white"></i></a>
                                            <a href="/delete-act/{{$item->act_id}}" onclick="return confirm('Are you sure ?')" title="Delete" class="px-1"><i
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
