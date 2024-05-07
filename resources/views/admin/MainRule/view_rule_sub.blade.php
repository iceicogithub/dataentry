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
                        <a href="{{ Route('edit_new_rule', ['id' => $ruleSub[0]->new_rule_id]) }}"><button class="btn btn-success">Back</button></a>
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
                        <strong class="card-title"> Table</strong>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered text-center" id="myTable">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Sr .No</th>
                                    @if ($ruleSub[0]->rule_subtypes_id == 1)
                                    <th scope="col">Sub Section :</th>
                                    @elseif($ruleSub[0]->rule_subtypes_id == 2)
                                    <th scope="col">Sub Article :</th>
                                    @elseif($ruleSub[0]->rule_subtypes_id == 3)
                                    <th scope="col">Sub Rule :</th>
                                    @elseif($ruleSub[0]->rule_subtypes_id == 4)
                                    <th scope="col">Sub Regulation :</th>
                                    @elseif($ruleSub[0]->rule_subtypes_id == 5)
                                    <th scope="col">Sub List :</th>
                                    @elseif($ruleSub[0]->rule_subtypes_id == 6)
                                    <th scope="col">Sub Part :</th>
                                    @elseif($ruleSub[0]->rule_subtypes_id == 7)
                                    <th scope="col">Sub Appendices :</th>
                                    @elseif($ruleSub[0]->rule_subtypes_id == 8)
                                    <th scope="col">Sub Order :</th>
                                    @elseif($ruleSub[0]->rule_subtypes_id == 9)
                                    <th scope="col">Sub Annexure :</th>
                                    @elseif($ruleSub[0]->rule_subtypes_id == 10)
                                    <th scope="col">Sub Schedule :</th>
                                    @elseif($ruleSub[0]->rule_subtypes_id == 11)
                                    <th scope="col">Sub Form :</th>
                                    @else
                                    null
                                    @endif
                                    <th scope="col">Last Date Of Edited</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $a=1; @endphp
                                @foreach ($ruleSub as $item)
                                    <tr>
                                        <td scope="row">@php echo $a++; @endphp</td>
                                        <td class="text-capitalize">{!!$item->rule_sub_content!!}</td>
                                        <td class="text-capitalize">{{ $item->updated_at }}</td>
                                        <td class="text-capitalize">
                                            <a href="{{ url('/delete_rule_sub/' . $item->rule_sub_id) }}"
                                            title="Delete" class="px-1"
                                            onclick="return confirm('Are you sure ?')">
                                            <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                        </a>
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
