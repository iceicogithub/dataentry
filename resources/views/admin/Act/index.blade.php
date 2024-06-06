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
                        <a href="{{ Route('new_act') }}"><button class="btn btn-success">Add Legislation</button></a>
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
                        <strong class="card-title">Legislation Table</strong>
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
                                    <th scope="col">Legislation</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">State</th>
                                    <th scope="col">Last Date Of Edited</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $a = ($currentPage - 1) * $perPage + 1; @endphp
                                @foreach ($acts as $item)
                                    <tr>
                                        <td scope="row">{{ $a++ }}</td>
                                        <td class="text-capitalize">{{ $item->legislation_name }}</td>
                                        <td class="text-capitalize">{{ $item->CategoryModel->category }}</td>
                                        <td class="text-capitalize">All</td>
                                        <td class="text-capitalize">{{ $item->updated_at }}</td>
                                        <td class="text-capitalize d-flex">
                                            <a href="/edit-main-act/{{ $item->act_id }}?page={{ $currentPage }}&perPage={{ $perPage }}" title="Edit" class="px-1 edit-link">
                                                <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                            </a>
                                            <a href="/view-main-act/{{ $item->act_id }}?page={{ $currentPage }}&perPage={{ $perPage }}" title="View" class="px-1 view-link">
                                                <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                            </a>
                                            <a href="/delete-act/{{ $item->act_id }}?page={{ $currentPage }}&perPage={{ $perPage }}" onclick="return confirm('Are you sure?')" title="Delete" class="px-1 delete-link">
                                                <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                            </a>
                                            <a href="/edit_legislation_name/{{ $item->act_id }}?page={{ $currentPage }}&perPage={{ $perPage }}" title="Edit Legislation" class="px-1 edit-legislation-link">
                                                <i class="bg-success btn-sm fa fa-edit p-1 text-white"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Pagination Links -->
                        {{ $acts->appends(['page' => $currentPage, 'perPage' => $perPage, 'search' => request('search')])->links() }}
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
            "info": false, // Disable DataTables info
            "pageLength": {{ $perPage }},
            "lengthMenu": [10, 25, 50, 100]
        });

        // Function to update the links with the current page number and perPage
        function updateLinks() {
            var currentPage = {{ $currentPage }};
            var perPage = {{ $perPage }};
            $('.edit-link, .view-link, .delete-link, .edit-legislation-link').each(function() {
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
