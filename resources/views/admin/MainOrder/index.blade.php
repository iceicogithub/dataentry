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
                        <a href="{{ Route('new_order', ['id' => $act_id]) }}"><button class="btn btn-success mr-2">Add Order</button></a>


                        <a href="/edit-main-act/{{  $act_id }}"><button class="btn btn-danger">Back</button></a>
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
                        <strong class="card-title">Order Table</strong>
                    </div>
                    <div class="card-body">
                        <div class="pagination-links">
                            <form action="{{ request()->url() }}" method="GET" class="form-inline">
                                <label for="perPage">Show:</label>
                                <select name="perPage" id="perPage" class="form-control mx-2" onchange="this.form.submit()">
                                    <option value="10" {{ request()->get('perPage') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request()->get('perPage') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request()->get('perPage') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request()->get('perPage') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                                <span>entries</span>
                            </form>
                        </div>
                        <table class="table table-bordered text-center" id="">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Sr .No</th>
                                    <th scope="col">Order</th>
                                    <th scope="col">Last Date Of Edited</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $a=1; @endphp
                                @foreach ($paginatedCollection as $item)
                                    <tr>
                                        <td scope="row">@php echo $a++; @endphp</td>
                                        <td class="text-capitalize">{{ $item['new_order_title'] }}</td>
                                        <td class="text-capitalize">{{ $item['updated_at'] }}</td>
                                        <td class="text-capitalize d-flex">
                                            <a href="/edit_new_order/{{$item['new_order_id']}}" title="Edit" class="px-1"><i
                                                    class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i></a>
                                                    <a href="/view_new_order/{{$item['new_order_id']}}?page={{ $paginatedCollection->currentPage() }}" title="View" class="px-1"><i
                                                        class="bg-primary btn-sm fa fa-eye p-1 text-white"></i></a>
                                            <a href="/delete_new_order/{{$item['new_order_id']}}?page={{ $paginatedCollection->currentPage() }}" onclick="return confirm('Are you sure ?')" title="Delete" class="px-1"><i
                                                    class="bg-danger btn-sm fa fa-trash p-1 text-white"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                           
                        </table>
                         {{ $paginatedCollection->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
   
</script>