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
                        <a href="{{Route('add-act')}}"><button class="btn btn-success">Add Act</button></a>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    @if(session('success'))
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
                        <table class="table table-bordered text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Sr .No</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Act</th>
                                    <th scope="col">Chapter</th>
                                    <th scope="col">Section</th>
                                    <th scope="col">Sub Section</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $a=1; @endphp
                                {{-- @foreach ($category as $item) --}}
                                    <tr>
                                        <td scope="row">@php echo $a++; @endphp</td>
                                        {{-- <td class="text-capitalize">{{ $item->category }}</td> --}}
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
