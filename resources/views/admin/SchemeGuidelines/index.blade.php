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
                        <a href="{{ Route('new_scheme_guidelines', ['id' => $act_id]) }}"><button class="btn btn-success mr-2">Add Scheme/Guidelines</button></a>


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
                        <strong class="card-title">Scheme/Guidelines Table</strong>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered text-center" id="myTable">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Sr .No</th>
                                    <th scope="col">Scheme/Guidelines</th>
                                    <th scope="col">Last Date Of Edited</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $a=1; @endphp
                                @foreach ($new_scheme_guidelines as $item)
                                    <tr>
                                        <td scope="row">@php echo $a++; @endphp</td>
                                        <td class="text-capitalize">{{ $item['new_scheme_guidelines_title'] }}</td>
                                        <td class="text-capitalize">{{ $item['updated_at'] }}</td>
                                        <td class="text-capitalize d-flex">
                                            <a href="/edit_new_scheme_guidelines/{{$item['new_scheme_guidelines_id']}}?page={{ $currentPage }}" title="Edit" class="px-1"><i
                                                    class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i></a>
                                            <a href="/view_new_scheme_guidelines/{{$item['new_scheme_guidelines_id']}}?page={{ $currentPage }}" title="View" class="px-1"><i
                                                    class="bg-primary btn-sm fa fa-eye p-1 text-white"></i></a>
                                            <a href="/delete_new_scheme_guidelines/{{$item['new_scheme_guidelines_id']}}?page={{ $currentPage }}" onclick="return confirm('Are you sure ?')" title="Delete" class="px-1"><i
                                                    class="bg-danger btn-sm fa fa-trash p-1 text-white"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $new_scheme_guidelines->appends(['page' => $currentPage])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        var table = $('#myTable').DataTable({
            "paging": false, // Disable DataTables paging, use Laravel pagination instead
            "searching": true,
            "ordering": true,
            "info": false,
        });
    
        // Function to update the links with the current page number
        function updateLinks() {
            var currentPage = {{ $currentPage }}; // Use current page from server-side
            $('.edit-link, .view-link, .delete-link, .edit-legislation-link').each(function() {
                var url = new URL($(this).attr('href'), window.location.origin);
                url.searchParams.set('page', currentPage);
                $(this).attr('href', url.toString());
            });
        }
    
        // Update links initially
        updateLinks();
    });

   
    </script>
@endsection

