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
                        <a href="{{ Route('act') }}"><button class="btn btn-success"> Back</button></a>
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
                        <strong class="card-title">Main Act</strong>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Sr .No</th>
                                    <th scope="col">Title</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $a = 1; @endphp
                                @foreach ($mainact as $item)
                                    <tr>
                                        <td scope="row">@php echo $a++; @endphp</td>
                                        <td class="text-capitalize text-justify">
                                            @php
                                                $actSummaryArray = json_decode($act->act_summary, true);
                                            @endphp

                                            @if ($actSummaryArray && in_array($item->id, $actSummaryArray))
                                                @if ($item->id === 1)
                                                    <a href="/get_act_section/{{ $act_id }}">{{ $item->title }}</a>
                                                @elseif ($item->id === 2)
                                                    <a href="/get_state_amendments/{{ $act_id }}">{{ $item->title }}</a>
                                                @elseif ($item->id === 3)
                                                    <a href="/get_amendment_act/{{ $act_id }}">{{ $item->title }}</a>
                                                @elseif ($item->id === 4)
                                                    <a href="/get_timeline/{{ $act_id }}">{{ $item->title }}</a>
                                                @elseif ($item->id === 5)
                                                    <a href="/get_act_rule/{{ $act_id }}">{{ $item->title }}</a>
                                                @elseif ($item->id === 6)
                                                    <a href="/get_act_regulation/{{ $act_id }}">{{ $item->title }}</a>
                                                @elseif ($item->id === 7)
                                                    <a href="/get_orders/{{ $act_id }}">{{ $item->title }}</a>
                                                @elseif ($item->id === 8)
                                                    <a href="/get_schemes_guidelines/{{ $act_id }}">{{ $item->title }}</a>
                                                @elseif ($item->id === 9)
                                                    <a href="/get_other_main_acts/{{ $act_id }}">{{ $item->title }}</a>
                                                @else
                                                    <a href="/desired_path_for_existing_id/{{ $act_id }}">{{ $item->title }}</a>
                                                @endif
                                            @else
                                                <a href="/desired_path_for_existing_id/{{ $act_id }}">{{ $item->title }}</a>
                                            @endif
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
