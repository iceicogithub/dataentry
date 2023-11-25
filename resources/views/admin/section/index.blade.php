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
                        <a href="{{Route('act')}}"><button class="btn btn-success">Back</button></a>
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
                        <strong class="card-title">Section List</strong>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Sr .No</th>
                                    <th scope="col">Chapter</th>
                                    <th scope="col">Section</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $a=1; @endphp
                                {{-- @foreach ($category as $item) --}}
                                <tr>
                                    <td scope="row">@php echo $a++; @endphp</td>
                                    <td class="text-capitalize">Chapter Title</td>
                                    <td class="text-capitalize">Short title, extent and commencement.</td>
                                    <td class="text-capitalize">
                                        <a href="{{Route('edit-section')}}" title="Edit" class="px-1"><i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i></a>
                                        <a href="#" title="View" class="px-1"><i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i></a>
                                        <a href="#" title="Delete" class="px-1"><i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td scope="row">@php echo $a++; @endphp</td>
                                    <td class="text-capitalize">Chapter Title</td>
                                    <td class="text-capitalize">Definitions.</td>
                                    <td class="text-capitalize">
                                        <a href="{{Route('edit-section')}}" title="Edit" class="px-1"><i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i></a>
                                        <a href="#" title="View" class="px-1"><i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i></a>
                                        <a href="#" title="Delete" class="px-1"><i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i></a>
                                    </td>
                                </tr>
                                {{-- @endforeach --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
