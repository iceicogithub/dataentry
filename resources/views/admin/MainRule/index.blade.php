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
                        <a href="{{ Route('new_rule', ['id' => $act_id]) }}"><button class="btn btn-success mr-2">Add Rule</button></a>
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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong class="card-title">Rules Table</strong>
                        <div>
                            <form id="searchForm" method="GET" action="{{ route('act') }}">
                                <input type="hidden" name="perPage" value="{{ $perPage }}">
                                <input type="hidden" name="page" value="{{ $currentPage }}">
                                <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </form>
                            <label for="perPageSelect">Show</label>
                            <select id="perPageSelect" class="form-control d-inline-block" style="width: auto;">
                                <option value="10"{{ $perPage == 10 ? ' selected' : '' }}>10</option>
                                <option value="25"{{ $perPage == 25 ? ' selected' : '' }}>25</option>
                                <option value="50"{{ $perPage == 50 ? ' selected' : '' }}>50</option>
                                <option value="100"{{ $perPage == 100 ? ' selected' : '' }}>100</option>
                            </select>
                            <label for="perPageSelect">entries</label>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered text-center" id="myTable">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Sr. No</th>
                                    <th scope="col">Rule</th>
                                    <th scope="col">Last Date Of Edited</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $a = ($currentPage - 1) * $perPage + 1; @endphp
                                @foreach ($new_rule as $item)
                                    <tr>
                                        <td scope="row">{{ $a++ }}</td>
                                        <td class="text-capitalize">{{ $item['new_rule_title'] }}</td>
                                        <td class="text-capitalize">{{ $item['updated_at'] }}</td>
                                        <td class="text-capitalize d-flex">
                                            <a href="/edit_new_rule/{{$item['new_rule_id']}}?page={{ $currentPage }}&perPage={{ $perPage }}" title="Edit" class="px-1 edit-link">
                                                <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i></a>
                                            <a href="/view_new_rule/{{$item['new_rule_id']}}?page={{ $currentPage }}&perPage={{ $perPage }}" title="View" class="px-1 view-link">
                                                <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i></a>
                                            <a href="/delete_new_rule/{{$item['new_rule_id']}}?page={{ $currentPage }}&perPage={{ $perPage }}" onclick="return confirm('Are you sure?')" title="Delete" class="px-1 delete-link">
                                                <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Pagination Links -->
                        {{ $new_rule->appends(['page' => $currentPage, 'perPage' => $perPage, 'search' => request('search')])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Handle change in length menu
        $('#perPageSelect').change(function() {
            var perPage = $(this).val();
            var newUrl = new URL(window.location.href);
            newUrl.searchParams.set('perPage', perPage);
            window.location.href = newUrl.toString();
        });

        var table = $('#myTable').DataTable({
            "paging": false, // Disable DataTables paging
            "searching": false, // Disable DataTables searching
            "ordering": true,
            "info": false // Disable DataTables info
        });

        // Function to update the links with the current page number and perPage
        function updateLinks() {
            var currentPage = {{ $currentPage }};
            var perPage = {{ $perPage }};
            $('.edit-link, .view-link, .delete-link').each(function() {
                var url = new URL($(this).attr('href'), window.location.origin);
                url.searchParams.set('page', currentPage);
                url.searchParams.set('perPage', perPage);
                $(this).attr('href', url.toString());
            });
        }

        // Update links initially
        updateLinks();
    });
</script>
@endsection
