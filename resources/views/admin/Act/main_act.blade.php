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
                            
                                @if ($relatedActSummaries->isNotEmpty())
                                    @foreach ($relatedActSummaries as $item)
                                        <tr>
                                            <td scope="row">{{ $a++ }}</td>
                                            <td class="text-capitalize text-justify">
                                                @php
                                                    $urls = [
                                                        1 => "/get_act_section/{$act_id}",
                                                        2 => "/get_state_amendments/{$act_id}",
                                                        3 => "/get_amendment_act/{$act_id}",
                                                        4 => "/get_timeline/{$act_id}",
                                                        5 => "/get_rule/{$act_id}",
                                                        6 => "/get_regulation/{$act_id}",
                                                        7 => "/get_orders/{$act_id}",
                                                        8 => "/get_schemes_guidelines/{$act_id}",
                                                        9 => "/get_other_main_acts/{$act_id}",
                                                        10 => "/get_notification/{$act_id}",
                                                        11 => "/get_manuals/{$act_id}"
                                                    ];
                                                @endphp
                            
                                                @if (isset($urls[$item->act_summary_id]))
                                                    <a href="{{ $urls[$item->act_summary_id] }}">{{ $item->actSummary->title }}</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
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
                                                        <a href="/get_rule/{{ $act_id }}">{{ $item->title }}</a>
                                                    @elseif ($item->id === 6)
                                                        <a href="/get_regulation/{{ $act_id }}">{{ $item->title }}</a>
                                                    @elseif ($item->id === 7)
                                                        <a href="/get_orders/{{ $act_id }}">{{ $item->title }}</a>
                                                    @elseif ($item->id === 8)
                                                        <a href="/get_schemes_guidelines/{{ $act_id }}">{{ $item->title }}</a>
                                                    @elseif ($item->id === 9)
                                                        <a href="/get_other_main_acts/{{ $act_id }}">{{ $item->title }}</a>
                                                    @elseif ($item->id === 10)
                                                    <a href="/get_notification/{{ $act_id }}">{{ $item->title }}</a>
                                                    @elseif ($item->id === 11)
                                                        <a href="/get_manuals/{{ $act_id }}">{{ $item->title }}</a>    
                                                    @else
                                                        <a href="/desired_path_for_existing_id/{{ $act_id }}">{{ $item->title }}</a>
                                                    @endif
                                                @else
                                                    <a href="/desired_path_for_existing_id/{{ $act_id }}">{{ $item->title }}</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>    
                         </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
