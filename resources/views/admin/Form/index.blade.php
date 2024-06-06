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
                        <a href="{{ Route('new_forms', ['id' => $act_id]) }}"><button class="btn btn-success mr-2">Add Form</button></a>


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
                        <strong class="card-title">Form Table</strong>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered text-center" id="myTable">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Sr .No</th>
                                    <th scope="col">Form</th>
                                    <th scope="col">Last Date Of Edited</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $a=1; @endphp
                                @foreach ($forms as $item)
                                    <tr>
                                        <td scope="row">@php echo $a++; @endphp</td>
                                        <td class="text-capitalize">{{ $item['forms_title'] }}</td>
                                        <td class="text-capitalize">{{ $item['updated_at'] }}</td>
                                        <td class="text-capitalize d-flex">
                                            <div class="row" style="justify-content: center">
                                                <button type="button" class="btn btn-warning" onclick="setActAmendmentId('{{ $item['forms_id'] }}', '{{ $item['forms_pdf'] }}')" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                    Upload Pdf
                                                </button>
                                                <a href="/edit_form/{{$item['forms_id']}}" title="Edit" class="px-1 edit-link"><i
                                                    class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i></a>
                                                <a href="/view_form/{{$item['forms_id']}}?page={{ $currentPage }}" title="View" class="px-1"><i
                                                    class="bg-primary btn-sm fa fa-eye p-1 text-white view-link"></i></a>
                                                <a href="/delete_form/{{$item['forms_id']}}?page={{ $currentPage }}" onclick="return confirm('Are you sure ?')" title="Delete" class="px-1"><i
                                                class="bg-danger btn-sm fa fa-trash p-1 text-white delete-link"></i></a>
                                            </div>           
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                           
                        </table>
                        {{ $forms->appends(['page' => $currentPage])->links() }}
                    </div>

                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Form</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="uploadform" action="" method="post" enctype="multipart/form-data">
                                        @csrf
                                    <input type="hidden" id="formIdInput" name="forms_id">
                                    <input type="hidden" id="currentPage" name="current_page" value="{{ $currentPage }}">
                                    <label class="float-label">Add Form<span class="text-danger">*</span></label>
                                    <input type="file" name="forms_pdf" class="form-control mb-3"
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
  
@section('script')
<script>
     function setActAmendmentId(formId, formPdf) {
        console.log(formPdf);
        document.getElementById('formIdInput').value = formId;
        document.getElementById('uploadform').action = "/update_form_pdf/" + formId;
        var pdfLink = document.getElementById('pdfLink');
        if (formPdf) {
            console.log('cbnm');
            pdfLink.innerHTML = formPdf;
            console.log(formPdf.innerHTML);
            pdfLink.setAttribute('href', '{{ asset("admin/form") }}/' + formPdf);
            pdfLink.style.display = 'block';
        } else {
            
            pdfLink.style.display = 'none';
            pdfLink.innerHTML = ''; 
        }
    }
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
