@extends('admin.layout.main')
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
   
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
                        <a href="{{ Route('new_manuals', ['id' => $act_id]) }}"><button class="btn btn-success mr-2">Add Manuals</button></a>


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
                        <strong class="card-title">Manual Table</strong>
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
                                    <th scope="col">Manuals</th>
                                    <th scope="col">Last Date Of Edited</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $a=1; @endphp
                                @foreach ($paginatedCollection as $item)
                                    <tr>
                                        <td scope="row">@php echo $a++; @endphp</td>
                                        <td class="text-capitalize">{{ $item['manuals_title'] }}</td>
                                        <td class="text-capitalize">{{ $item['updated_at'] }}</td>
                                        <td class="text-capitalize d-flex">
                                            <div class="row" style="justify-content: center">
                                                <button type="button" class="btn btn-warning" onclick="setActAmendmentId('{{ $item['manuals_id'] }}', '{{ $item['manuals_pdf'] }}')" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                    Upload Pdf
                                                </button>
                                                <a href="/edit_manuals/{{$item['manuals_id']}}" title="Edit" class="px-1"><i
                                                    class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i></a>
                                                <a href="/view_manuals/{{$item['manuals_id']}}?page={{ $paginatedCollection->currentPage() }}" title="View" class="px-1"><i
                                                    class="bg-primary btn-sm fa fa-eye p-1 text-white"></i></a>
                                                <a href="/delete_act_amendment/{{$item['manuals_id']}}?page={{ $paginatedCollection->currentPage() }}" onclick="return confirm('Are you sure ?')" title="Delete" class="px-1"><i
                                                class="bg-danger btn-sm fa fa-trash p-1 text-white"></i></a>
                                               
                                            </div>
                                            
                                                   
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                           
                        </table>
                         {{ $paginatedCollection->links() }}
                    </div>

                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Manual</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="uploadManual" action="" method="post" enctype="multipart/form-data">
                                        @csrf
                                    <input type="hidden" id="manualsIdInput" name="manuals_id">
                                    <input type="hidden" id="currentPage" name="current_page" value="{{ $paginatedCollection->currentPage() }}">
                                    <label class="float-label">Add Manuals<span class="text-danger">*</span></label>
                                    <input type="file" name="manuals_pdf" class="form-control mb-3"
                                         required>
                                    <a id="pdfLink" href="#" target="_blank"></a>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>  
                                    </form>   
                                </div>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  
<script>
       
    function setActAmendmentId(manualsId, manualsPdf) {
        console.log(manualsPdf);
        document.getElementById('manualsIdInput').value = manualsId;
        document.getElementById('uploadManual').action = "/update_manuals_pdf/" + manualsId;
        var pdfLink = document.getElementById('pdfLink');
        if (manualsPdf) {
            console.log('cbnm');
            pdfLink.innerHTML = manualsPdf;
            console.log(pdfLink.innerHTML);
            pdfLink.setAttribute('href', '{{ asset("admin/manuals") }}/' + manualsPdf);
            pdfLink.style.display = 'block';
        } else {
            // If PDF doesn't exist, hide the link
            pdfLink.style.display = 'none';
            pdfLink.innerHTML = ''; // Clear the content
        }
    }
</script>
