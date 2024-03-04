@extends('admin.layout.main')
@section('content')
    <div class="breadcrumbs">
        <div class="col-sm-8">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1>Main Act : {{ $act->act_title }}</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <a href="/add-act/{{ $act_id }}" class="mr-2"><button class="btn btn-success">Add
                                Index</button></a>
                        <a href="/edit-main-act/{{ $act_id }}"><button class="btn btn-danger">Back</button></a>
                    </ol>

                </div>
            </div>
        </div>

        <div class="col-sm-12">

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->has('error'))
                <div class="alert alert-danger">
                    {{ $errors->first('error') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-success">
                    {{ session('error') }}
                </div>
            @endif

            <div class="card p-5">
                <form id="form" action="/update_main_act/{{ $act->act_id }}" method="post"
                    enctype="multipart/form-data" class="form form-horizontal">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-default">
                                <label class="float-label"> Act<span class="text-danger">*</span></label>
                                <input type="text" name="act_title" value="{{ $act->act_title }}"
                                    class="form-control mb-3" placeholder="Enter Act Title">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-default">
                                <label class="float-label"> Act NO.<span class="text-danger">*</span></label>
                                <textarea name="act_no" class="form-control mb-3" placeholder="Enter Act No" id="act_no" cols="30"
                                    rows="3">{{ $act->act_no }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-default">
                                <label class="float-label">Enactment Date<span class="text-danger">*</span></label>
                                <input type="date" name="enactment_date" value="{{ $act->enactment_date }}"
                                    class="form-control mb-3">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-default">
                                <label class="float-label">Enforcement Date<span class="text-danger">*</span></label>
                                <input type="date" name="enforcement_date" value="{{ $act->enforcement_date }}"
                                    class="form-control mb-3">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-default">
                                <label class="float-label">Act Date<span class="text-danger">*</span></label>
                                <input type="text" name="act_date" value="{{ $act->act_date }}"
                                    class="form-control mb-3" placeholder="Enter Act Date">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-default">
                                <label class="float-label">Ministry<span class="text-danger">*</span></label>
                                <input type="text" name="ministry" value="{{ $act->ministry }}"
                                    class="form-control mb-3" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-default">
                                <label class="float-label"> PREAMBLE <span class="text-danger">*</span></label>

                                <textarea name="act_description" class="form-control mb-3" placeholder="Enter Act Description" id="act_description"
                                    cols="30" rows="3">{{ $act->act_description }}</textarea>
                            </div>
                        </div>
                        @if ($act_footnote_descriptions)
                            @foreach ($act_footnote_descriptions as $key => $act_footnote_description)
                                <div class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12">
                                    <label class="float-label">
                                        Footnote
                                        <span class="pl-2">
                                            <button type="button" class="btn btn-sm social facebook p-0 add-footnote">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </span>
                                    </label>
                                    <div class="show-footnote" style="">
                                        <div class="footnote-entry">
                                            <textarea type="text" name="act_footnote_description[]" id="ck[{{ $key }}]"
                                                class="form-control ckeditor-replace footnote" placeholder="Enter Footnote Description">
                                                {{ $act_footnote_description }}</textarea>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        @endIf

                        <div class="footnote-addition-container float-right col-md-12 ">

                            <div class="px-0 py-3">
                                <div class="float-right">
                                    <span style="font-size: small;" class="px-2 text-uppercase font-weight-bold">
                                        (add Footnote)
                                    </span>
                                    <button type="button" class="btn btn-sm social facebook p-0 add-multi-footnote">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm social youtube p-0 remove-multi-footnote">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-12 text-right mt-2">
                            <div class="form-group">
                                <button type="submit" class="btn  btn-success">Update Data</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="content mt-3">
        <div class="row">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Arrangement of Sections</strong>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered text-center" id="myTable">
                            <thead class="thead-light">
                                @php
                                    use App\Models\Chapter;
                                    use App\Models\Parts;
                                    use App\Models\Priliminary;
                                    use App\Models\Schedule;
                                    use App\Models\Appendices;

                                    $chapter = Chapter::with('ChapterType')->where('act_id', $act_id)->first();
                                    $parts = Parts::with('PartsType')->where('act_id', $act_id)->first();
                                    $priliminary = Priliminary::with('PriliminaryType')->where('act_id', $act_id)->first();
                                    $schedule = Schedule::with('ScheduleType')->where('act_id', $act_id)->first();
                                    $appendices = Appendices::with('AppendicesType')->where('act_id', $act_id)->first();

                                @endphp

                                <tr>
                                    <th scope="col" class="text-center">Sr.No</th>
                                    <th scope="col" class="text-center">
                                        @if ($chapter && $chapter->maintype_id)
                                            @if ($chapter->ChapterType->maintype_id == '1')
                                                Chapter,
                                            @endif
                                        @endif
                                        @if ($parts && $parts->maintype_id)
                                            @if ($parts->PartsType->maintype_id == '2')
                                                Parts,
                                            @endif
                                        @endif
                                        @if ($priliminary && $priliminary->maintype_id)
                                            @if ($priliminary->PriliminaryType->maintype_id == '3')
                                                Priliminary,
                                            @endif
                                        @endif
                                        @if ($schedule && $schedule->maintype_id)
                                            @if ($schedule->ScheduleType->maintype_id == '4')
                                                Schedule,
                                            @endif
                                        @endif
                                        @if ($appendices && $appendices->maintype_id)
                                            @if ($appendices->AppendicesType->maintype_id == '5')
                                                Appendices,
                                            @endif
                                        @endif

                                    </th>
                                    <th scope="col" class="text-center">
                                        @if ($chapter && $chapter->maintype_id)
                                            @if ($chapter->ChapterType->maintype_id == '1')
                                                Section No. ,
                                            @endif
                                        @endif
                                        @if ($schedule && $schedule->maintype_id)
                                            @if ($schedule->ScheduleType->maintype_id == '4')
                                                Rule No. ,
                                            @endif
                                        @endif
                                        @if ($appendices && $appendices->maintype_id)
                                            @if ($appendices->AppendicesType->maintype_id == '5')
                                                Article No. ,
                                            @endif
                                        @endif

                                    </th>
                                    <th scope="col" class="text-center">
                                        @if ($chapter && $chapter->maintype_id)
                                            @if ($chapter->ChapterType->maintype_id == '1')
                                                Section ,
                                            @endif
                                        @endif
                                        @if ($schedule && $schedule->maintype_id)
                                            @if ($schedule->ScheduleType->maintype_id == '4')
                                                Rule ,
                                            @endif
                                        @endif
                                        @if ($appendices && $appendices->maintype_id)
                                            @if ($appendices->AppendicesType->maintype_id == '5')
                                                Article ,
                                            @endif
                                        @endif
                                    </th>
                                    <th scope="col" class="text-center">Date of changes</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @php
                                    $a = 1;
                                @endphp
                                @if ($act_section)
                                    @foreach ($act_section as $item)
                                        <tr>
                                            <td scope="row">@php echo $a++; @endphp</td>
                                            <td class="text-capitalize">
                                                @if ($item->maintype_id == 1)
                                                    {!! $item->ChapterModel->chapter_title !!}
                                                @elseif($item->maintype_id == 2)
                                                    {!! $item->Partmodel->parts_title !!}
                                                @elseif($item->maintype_id == 3)
                                                    {!! $item->PriliminaryModel->priliminary_title !!}
                                                @elseif($item->maintype_id == 4)
                                                    {!! $item->Schedulemodel->schedule_title !!}
                                                @elseif($item->maintype_id == 5)
                                                    {!! $item->Appendicesmodel->appendices_title !!}
                                                @else
                                                    null
                                                @endif
                                            </td>
                                            <td class="text-capitalize">{{ $item->section_no }}</td>
                                            <td class="text-capitalize">{!! preg_replace('/[0-9\[\]\.]/', '', $item->section_title) !!}</td>
                                            <td class="text-capitalize">{{ $item->updated_at }}</td>
                                            <td class="text-capitalize d-flex justify-content-center">
                                                <a href="/edit-section/{{ $item->section_id }}" title="Edit"
                                                    class="px-1">
                                                    <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                </a>
                                                <a href="/view-sub-section/{{ $item->section_id }}" title="View"
                                                    class="px-1">
                                                    <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                </a>
                                                <a href="{{ url('/delete_section/' . $item->section_id) }}"
                                                    title="Delete" class="px-1"
                                                    onclick="return confirm('Are you sure ?')">
                                                    <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                </a>
                                                <a href="{{ url('/add_below_new_section', ['act_id' => $item->act_id, 'section_id' => $item->section_id, 'section_rank' => $item->section_rank]) }}"
                                                    title="Add Next Section" class="px-1">
                                                    <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                @if ($act_article)
                                    @foreach ($act_article as $item)
                                        <tr>
                                            <td scope="row">@php echo $a++; @endphp</td>
                                            <td class="text-capitalize">
                                                @if ($item->maintype_id == 1)
                                                    {!! $item->ChapterModel->chapter_title !!}
                                                @elseif($item->maintype_id == 2)
                                                    {!! $item->Partmodel->parts_title !!}
                                                @elseif($item->maintype_id == 3)
                                                    {!! $item->PriliminaryModel->priliminary_title !!}
                                                @elseif($item->maintype_id == 4)
                                                    {!! $item->Schedulemodel->schedule_title !!}
                                                @elseif($item->maintype_id == 5)
                                                    {!! $item->Appendicesmodel->appendices_title !!}
                                                @else
                                                    null
                                                @endif
                                            </td>
                                            <td class="text-capitalize">{{ $item->article_no }}</td>
                                            <td class="text-capitalize">{!! preg_replace('/[0-9\[\]\.]/', '', $item->article_title) !!}</td>
                                            <td class="text-capitalize">{{ $item->updated_at }}</td>
                                            <td class="text-capitalize d-flex justify-content-center">
                                                <a href="/edit-article/{{ $item->article_id }}" title="Edit"
                                                    class="px-1">
                                                    <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                </a>
                                                <a href="/view-sub-article/{{ $item->article_id }}" title="View"
                                                    class="px-1">
                                                    <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                </a>
                                                <a href="{{ url('/delete_article/' . $item->article_id) }}"
                                                    title="Delete" class="px-1"
                                                    onclick="return confirm('Are you sure ?')">
                                                    <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                </a>
                                                <a href="{{ url('/add_below_new_article', ['act_id' => $item->act_id, 'article_id' => $item->article_id, 'article_rank' => $item->article_rank]) }}"
                                                    title="Add Next Article" class="px-1">
                                                    <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                @if ($act_rule)
                                    @foreach ($act_rule as $item)
                                        <tr>
                                            <td scope="row">@php echo $a++; @endphp</td>
                                            <td class="text-capitalize">
                                                @if ($item->maintype_id == 1)
                                                    {!! $item->ChapterModel->chapter_title !!}
                                                @elseif($item->maintype_id == 2)
                                                    {!! $item->Partmodel->parts_title !!}
                                                @elseif($item->maintype_id == 3)
                                                    {!! $item->PriliminaryModel->priliminary_title !!}
                                                @elseif($item->maintype_id == 4)
                                                    {!! $item->Schedulemodel->schedule_title !!}
                                                @elseif($item->maintype_id == 5)
                                                    {!! $item->Appendicesmodel->appendices_title !!}
                                                @else
                                                    null
                                                @endif
                                            </td>
                                            <td class="text-capitalize">{{ $item->rule_no }}</td>
                                            <td class="text-capitalize">{!! preg_replace('/[0-9\[\]\.]/', '', $item->rule_title) !!}</td>
                                            <td class="text-capitalize">{{ $item->updated_at }}</td>
                                            <td class="text-capitalize d-flex justify-content-center">
                                                <a href="/edit-rule/{{ $item->rule_id }}" title="Edit" class="px-1">
                                                    <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                </a>
                                                <a href="/view-sub-rule/{{ $item->rule_id }}" title="View"
                                                    class="px-1">
                                                    <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                </a>
                                                <a href="{{ url('/delete_rule/' . $item->rule_id) }}" title="Delete"
                                                    class="px-1" onclick="return confirm('Are you sure ?')">
                                                    <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                </a>
                                                <a href="{{ url('/add_below_new_rule', ['act_id' => $item->act_id, 'rule_id' => $item->rule_id, 'rule_rank' => $item->rule_rank]) }}"
                                                    title="Add Next Rule" class="px-1">
                                                    <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                @if ($act_regulation)
                                    @foreach ($act_regulation as $item)
                                        <tr>
                                            <td scope="row">@php echo $a++; @endphp</td>
                                            <td class="text-capitalize">
                                                @if ($item->maintype_id == 1)
                                                    {!! $item->ChapterModel->chapter_title !!}
                                                @elseif($item->maintype_id == 2)
                                                    {!! $item->Partmodel->parts_title !!}
                                                @elseif($item->maintype_id == 3)
                                                    {!! $item->PriliminaryModel->priliminary_title !!}
                                                @elseif($item->maintype_id == 4)
                                                    {!! $item->Schedulemodel->schedule_title !!}
                                                @elseif($item->maintype_id == 5)
                                                    {!! $item->Appendicesmodel->appendices_title !!}
                                                @else
                                                    null
                                                @endif
                                            </td>
                                            <td class="text-capitalize">{{ $item->regulation_no }}</td>
                                            <td class="text-capitalize">{!! preg_replace('/[0-9\[\]\.]/', '', $item->regulation_title) !!}</td>
                                            <td class="text-capitalize">{{ $item->updated_at }}</td>
                                            <td class="text-capitalize d-flex justify-content-center">
                                                <a href="/edit-regulation/{{ $item->regulation_id }}" title="Edit"
                                                    class="px-1">
                                                    <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                </a>
                                                <a href="/view-sub-regulation/{{ $item->regulation_id }}" title="View"
                                                    class="px-1">
                                                    <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                </a>
                                                <a href="{{ url('/delete_regulation/' . $item->regulation_id) }}"
                                                    title="Delete" class="px-1"
                                                    onclick="return confirm('Are you sure ?')">
                                                    <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                </a>
                                                <a href="{{ url('/add_below_new_regulation', ['act_id' => $item->act_id, 'regulation_id' => $item->regulation_id, 'regulation_rank' => $item->regulation_rank]) }}"
                                                    title="Add Next Regulation" class="px-1">
                                                    <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                @if ($act_list)
                                    @foreach ($act_list as $item)
                                        <tr>
                                            <td scope="row">@php echo $a++; @endphp</td>
                                            <td class="text-capitalize">
                                                @if ($item->maintype_id == 1)
                                                    {!! $item->ChapterModel->chapter_title !!}
                                                @elseif($item->maintype_id == 2)
                                                    {!! $item->Partmodel->parts_title !!}
                                                @elseif($item->maintype_id == 3)
                                                    {!! $item->PriliminaryModel->priliminary_title !!}
                                                @elseif($item->maintype_id == 4)
                                                    {!! $item->Schedulemodel->schedule_title !!}
                                                @elseif($item->maintype_id == 5)
                                                    {!! $item->Appendicesmodel->appendices_title !!}
                                                @else
                                                    null
                                                @endif
                                            </td>
                                            <td class="text-capitalize">{{ $item->list_no }}</td>
                                            <td class="text-capitalize">{!! preg_replace('/[0-9\[\]\.]/', '', $item->list_title) !!}</td>
                                            <td class="text-capitalize">{{ $item->updated_at }}</td>
                                            <td class="text-capitalize d-flex justify-content-center">
                                                <a href="/edit-list/{{ $item->list_id }}" title="Edit" class="px-1">
                                                    <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                </a>
                                                <a href="/view-sub-list/{{ $item->list_id }}" title="View"
                                                    class="px-1">
                                                    <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                </a>
                                                <a href="{{ url('/delete_list/' . $item->list_id) }}" title="Delete"
                                                    class="px-1" onclick="return confirm('Are you sure ?')">
                                                    <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                </a>
                                                <a href="{{ url('/add_below_new_list', ['act_id' => $item->act_id, 'list_id' => $item->list_id, 'list_rank' => $item->list_rank]) }}"
                                                    title="Add Next List" class="px-1">
                                                    <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                @if ($act_part)
                                    @foreach ($act_part as $item)
                                        <tr>
                                            <td scope="row">@php echo $a++; @endphp</td>
                                            <td class="text-capitalize">
                                                @if ($item->maintype_id == 1)
                                                    {!! $item->ChapterModel->chapter_title !!}
                                                @elseif($item->maintype_id == 2)
                                                    {!! $item->Partmodel->parts_title !!}
                                                @elseif($item->maintype_id == 3)
                                                    {!! $item->PriliminaryModel->priliminary_title !!}
                                                @elseif($item->maintype_id == 4)
                                                    {!! $item->Schedulemodel->schedule_title !!}
                                                @elseif($item->maintype_id == 5)
                                                    {!! $item->Appendicesmodel->appendices_title !!}
                                                @else
                                                    null
                                                @endif
                                            </td>
                                            <td class="text-capitalize">{{ $item->part_no }}</td>
                                            <td class="text-capitalize">{!! preg_replace('/[0-9\[\]\.]/', '', $item->part_title) !!}</td>
                                            <td class="text-capitalize">{{ $item->updated_at }}</td>
                                            <td class="text-capitalize d-flex justify-content-center">
                                                <a href="/edit-part/{{ $item->part_id }}" title="Edit" class="px-1">
                                                    <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                </a>
                                                <a href="/view-sub-part/{{ $item->part_id }}" title="View"
                                                    class="px-1">
                                                    <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                </a>
                                                <a href="{{ url('/delete_part/' . $item->part_id) }}" title="Delete"
                                                    class="px-1" onclick="return confirm('Are you sure ?')">
                                                    <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                </a>
                                                <a href="{{ url('/add_below_new_part', ['act_id' => $item->act_id, 'part_id' => $item->part_id, 'list_rank' => $item->part_rank]) }}"
                                                    title="Add Next Part" class="px-1">
                                                    <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                @if ($act_appendix)
                                    @foreach ($act_appendix as $item)
                                        <tr>
                                            <td scope="row">@php echo $a++; @endphp</td>
                                            <td class="text-capitalize">
                                                @if ($item->maintype_id == 1)
                                                    {!! $item->ChapterModel->chapter_title !!}
                                                @elseif($item->maintype_id == 2)
                                                    {!! $item->Partmodel->parts_title !!}
                                                @elseif($item->maintype_id == 3)
                                                    {!! $item->PriliminaryModel->priliminary_title !!}
                                                @elseif($item->maintype_id == 4)
                                                    {!! $item->Schedulemodel->schedule_title !!}
                                                @elseif($item->maintype_id == 5)
                                                    {!! $item->Appendicesmodel->appendices_title !!}
                                                @else
                                                    null
                                                @endif
                                            </td>
                                            <td class="text-capitalize">{{ $item->appendix_no }}</td>
                                            <td class="text-capitalize">{!! preg_replace('/[0-9\[\]\.]/', '', $item->appendix_title) !!}</td>
                                            <td class="text-capitalize">{{ $item->updated_at }}</td>
                                            <td class="text-capitalize d-flex justify-content-center">
                                                <a href="/edit-appendix/{{ $item->appendix_id }}" title="Edit"
                                                    class="px-1">
                                                    <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                </a>
                                                <a href="/view-sub-appendix/{{ $item->appendix_id }}" title="View"
                                                    class="px-1">
                                                    <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                </a>
                                                <a href="{{ url('/delete_appendix/' . $item->appendix_id) }}"
                                                    title="Delete" class="px-1"
                                                    onclick="return confirm('Are you sure ?')">
                                                    <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                </a>
                                                <a href="{{ url('/add_below_new_appendix', ['act_id' => $item->act_id, 'appendix_id' => $item->appendix_id, 'appendix_rank' => $item->appendix_rank]) }}"
                                                    title="Add Next Appendix" class="px-1">
                                                    <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                @if ($act_order)
                                    @foreach ($act_order as $item)
                                        <tr>
                                            <td scope="row">@php echo $a++; @endphp</td>
                                            <td class="text-capitalize">
                                                @if ($item->maintype_id == 1)
                                                    {!! $item->ChapterModel->chapter_title !!}
                                                @elseif($item->maintype_id == 2)
                                                    {!! $item->Partmodel->parts_title !!}
                                                @elseif($item->maintype_id == 3)
                                                    {!! $item->PriliminaryModel->priliminary_title !!}
                                                @elseif($item->maintype_id == 4)
                                                    {!! $item->Schedulemodel->schedule_title !!}
                                                @elseif($item->maintype_id == 5)
                                                    {!! $item->Appendicesmodel->appendices_title !!}
                                                @else
                                                    null
                                                @endif
                                            </td>
                                            <td class="text-capitalize">{{ $item->order_no }}</td>
                                            <td class="text-capitalize">{!! preg_replace('/[0-9\[\]\.]/', '', $item->order_title) !!}</td>
                                            <td class="text-capitalize">{{ $item->updated_at }}</td>
                                            <td class="text-capitalize d-flex justify-content-center">
                                                <a href="/edit-order/{{ $item->order_id }}" title="Edit"
                                                    class="px-1">
                                                    <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                </a>
                                                <a href="/view-sub-order/{{ $item->order_id }}" title="View"
                                                    class="px-1">
                                                    <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                </a>
                                                <a href="{{ url('/delete_order/' . $item->order_id) }}" title="Delete"
                                                    class="px-1" onclick="return confirm('Are you sure ?')">
                                                    <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                </a>
                                                <a href="{{ url('/add_below_new_order', ['act_id' => $item->act_id, 'order_id' => $item->order_id, 'order_rank' => $item->order_rank]) }}"
                                                    title="Add Next Order" class="px-1">
                                                    <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                @if ($act_annexture)
                                    @foreach ($act_annexture as $item)
                                        <tr>
                                            <td scope="row">@php echo $a++; @endphp</td>
                                            <td class="text-capitalize">
                                                @if ($item->maintype_id == 1)
                                                    {!! $item->ChapterModel->chapter_title !!}
                                                @elseif($item->maintype_id == 2)
                                                    {!! $item->Partmodel->parts_title !!}
                                                @elseif($item->maintype_id == 3)
                                                    {!! $item->PriliminaryModel->priliminary_title !!}
                                                @elseif($item->maintype_id == 4)
                                                    {!! $item->Schedulemodel->schedule_title !!}
                                                @elseif($item->maintype_id == 5)
                                                    {!! $item->Appendicesmodel->appendices_title !!}
                                                @else
                                                    null
                                                @endif
                                            </td>
                                            <td class="text-capitalize">{{ $item->annexture_no }}</td>
                                            <td class="text-capitalize">{!! preg_replace('/[0-9\[\]\.]/', '', $item->annexture_title) !!}</td>
                                            <td class="text-capitalize">{{ $item->updated_at }}</td>
                                            <td class="text-capitalize d-flex justify-content-center">
                                                <a href="/edit-annexture/{{ $item->annexture_id }}" title="Edit"
                                                    class="px-1">
                                                    <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                </a>
                                                <a href="/view-sub-annexture/{{ $item->annexture_id }}" title="View"
                                                    class="px-1">
                                                    <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                </a>
                                                <a href="{{ url('/delete_annexture/' . $item->annexture_id) }}"
                                                    title="Delete" class="px-1"
                                                    onclick="return confirm('Are you sure ?')">
                                                    <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                </a>
                                                <a href="{{ url('/add_below_new_annexture', ['act_id' => $item->act_id, 'annexture_id' => $item->annexture_id, 'annexture_rank' => $item->annexture_rank]) }}"
                                                    title="Add Next Annexture" class="px-1">
                                                    <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($act_stschedule)
                                    @foreach ($act_stschedule as $item)
                                        <tr>
                                            <td scope="row">@php echo $a++; @endphp</td>
                                            <td class="text-capitalize">
                                                @if ($item->maintype_id == 1)
                                                    {!! $item->ChapterModel->chapter_title !!}
                                                @elseif($item->maintype_id == 2)
                                                    {!! $item->Partmodel->parts_title !!}
                                                @elseif($item->maintype_id == 3)
                                                    {!! $item->PriliminaryModel->priliminary_title !!}
                                                @elseif($item->maintype_id == 4)
                                                    {!! $item->Schedulemodel->schedule_title !!}
                                                @elseif($item->maintype_id == 5)
                                                    {!! $item->Appendicesmodel->appendices_title !!}
                                                @else
                                                    null
                                                @endif
                                            </td>
                                            <td class="text-capitalize">{{ $item->stschedule_no }}</td>
                                            <td class="text-capitalize">{!! preg_replace('/[0-9\[\]\.]/', '', $item->stschedule_title) !!}</td>
                                            <td class="text-capitalize">{{ $item->updated_at }}</td>
                                            <td class="text-capitalize d-flex justify-content-center">
                                                <a href="/edit-stschedule/{{ $item->stschedule_id }}" title="Edit"
                                                    class="px-1">
                                                    <i class="bg-secondary btn-sm fa fa-edit p-1 text-white"></i>
                                                </a>
                                                <a href="/view-sub-stschedule/{{ $item->stschedule_id }}" title="View"
                                                    class="px-1">
                                                    <i class="bg-primary btn-sm fa fa-eye p-1 text-white"></i>
                                                </a>
                                                <a href="{{ url('/delete_stschedule/' . $item->stschedule_id) }}"
                                                    title="Delete" class="px-1"
                                                    onclick="return confirm('Are you sure ?')">
                                                    <i class="bg-danger btn-sm fa fa-trash p-1 text-white"></i>
                                                </a>
                                                <a href="{{ url('add_below_new_stschedule', ['act_id' => $item->act_id, 'stschedule_id' => $item->stschedule_id, 'stschedule_rank' => $item->stschedule_rank]) }}"
                                                    title="Add Next Schedule" class="px-1">
                                                    <i class="bg-success btn-sm fa fa-plus p-1 text-white"></i>
                                                </a>
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


    <script src="https://cdn.ckeditor.com/4.16.2/full-all/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @if ($act_footnote_descriptions)
        <script>
            $(document).ready(function() {
                CKEDITOR.replaceAll('ckeditor-replace', {
                    // Additional configuration options if needed
                    // ...
                });
            });
        </script>
    @endif
    <script>
        CKEDITOR.replace('act_no');
        CKEDITOR.replace('act_description');
        CKEDITOR.replace('act_footnote');


        $(document).on('click', '.add-footnote', function() {
            var icon = $(this).find('i');
            var section = $(this).closest('.form-default').find('.show-footnote');
            section.slideToggle();
            icon.toggleClass('fa-plus fa-minus');

            // Initialize CKEditor for the new textarea
            CKEDITOR.replace(section.find('.ckeditor-replace.footnote')[0]);
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.add-multi-footnote', function() {


                var newSection = `<div class="form-group form-default fa fa-arrow-circle-o-right p-0 col-md-12 footnote-addition">
                                        <label class="float-label">
                                        Add Footnote
                                        <span class="pl-2">
                                            <button type="button" class="btn btn-sm social facebook p-0 add-footnote">
                                            <i class="fa fa-plus"></i>
                                            </button>
                                        </span>
                                        </label>
                                        <div class="show-footnote" style="display: none">
                                            <textarea type="text" name="act_footnote_description[]" class="form-control ckeditor-replace footnote"></textarea>
                                        </div>
                                   
                                       
                                    </div>
                                    
                                `;

                $('.footnote-addition-container').append(newSection);

                CKEDITOR.replace($('.footnote-addition:last').find('.ckeditor-replace')[0]);
            });

            $(document).on('click', '.remove-multi-footnote', function() {
                if ($('.footnote-addition').length > 0) {
                    $('.footnote-addition:last').remove();
                }
            });

        });
    </script>
    
@endsection
