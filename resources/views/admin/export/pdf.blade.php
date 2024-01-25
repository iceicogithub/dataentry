<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.3/dist/css/bootstrap.min.css"
        integrity="sha384-GLhlTQ8iK7t9LdI8L6FU9tYmVlMGTskTpkEAIaCkIbbVcGpF5eSrhbY6SOMZgT" crossorigin="anonymous">
    <title>{{ $act->act_title }}</title>


</head>

<body>

    <div style=" padding: 50px 50px !important;">
        <div style="text-align: center; text-transform: uppercase !important;font-size: 20px !important;">
            {{ $act->act_title }}</div>
        <hr style="width: 10% !important;margin: 10px auto !important;">
        <div style="text-align: center; font-size: 20px !important;">ARRANGEMENT OF SECTIONS</div>
        <hr style="width: 10% !important;margin: 10px auto !important;">

        {{-- for chapter  --}}
        @if ($type->contains('maintype_id', 1))
            @php
                $sectionCounter1 = 1;
            @endphp
            @foreach ($chapter as $key => $chapterItem)
                <div style="text-align: center; margin-bottom: 0.5rem;">
                    <div style="text-transform: uppercase !important; font-size: 15px !important;">
                        {!! $chapterItem->chapter_title !!}</div>
                </div>
                @if (!empty($section) && count($section) > 0)
                    @if ($key === 0)
                        <div style="text-align: start; margin-top: 0.2rem;">Sections</div>
                    @endif
                    <div style="text-align: start; margin-top: 0.2rem;">
                        @foreach ($section->where('chapter_id', $chapterItem->chapter_id) as $sectionItem)
                            <span style="font-size: 15px !important;">{{ $sectionItem->section_no }}.
                                {{ $sectionItem->section_title }}</span><br><br>
                        @endforeach
                    </div>
                @endif
                {{-- for regulation  --}}
                @if (!empty($regulation) && count($regulation) > 0)
                    @if ($key === 0)
                        <div style="text-align: start; margin-top: 0.2rem;">Regulation</div>
                    @endif
                    <div style="text-align: start; margin-top: 0.2rem;">
                        @foreach ($regulation->where('chapter_id', $chapterItem->chapter_id) as $regulationItem)
                            <span
                                style="text-align: start; font-size: 15px !important;">{{ $regulationItem->regulation_no }}.
                                {{ $regulationItem->regulation_title }}</span><br><br>
                        @endforeach
                    </div>
                @endif
            @endforeach

        @endif

        {{-- for priliminary  --}}
        @if ($type->contains('maintype_id', 3))
            @php $displayedSections = []; @endphp
            @foreach ($priliminary as $key => $priliminarys)
                @php
                    $sectionKey = $priliminarys->act_id . '' . $priliminarys->maintype_id . '_' . $priliminarys->priliminary_title;
                @endphp
                <div style="text-align: center">
                    @if (!in_array($sectionKey, $displayedSections))
                        <div
                            style="text-align: center; text-transform: uppercase !important; font-size: 15px !important;">
                            {!! $priliminarys->priliminary_title !!}</div>
                        @php $displayedSections[] = $sectionKey; @endphp
                    @endif
                </div>
                @if ($key === 0)
                    <div style="text-align: start">Sections</div>
                @endif
                <div style="text-align: start; margin-top: 0.2rem;">
                    @foreach ($section->where('priliminary_id', $priliminarys->priliminary_id) as $priliminaryItem)
                        <span
                            style="text-align: start; margin-top: 0.5rem; font-size: 15px !important;">{{ $priliminaryItem->section_no }}.
                            {{ $priliminaryItem->section_title }}</span><br><br>
                    @endforeach
                </div>
            @endforeach
        @endif

        {{-- for parts  --}}
        @if ($type->contains('maintype_id', 2))
            @php $displayedSections = []; @endphp
            @foreach ($parts as $key => $part)
                @php
                    $sectionKey = $part->act_id . '' . $part->maintype_id . '_' . $part->parts_title;
                @endphp
                <div style="text-align: center">
                    @if (!in_array($sectionKey, $displayedSections))
                        <div
                            style="text-align: center; text-transform: uppercase !important; font-size: 15px !important;">
                            {!! $part->parts_title !!}</div>
                        @php $displayedSections[] = $sectionKey; @endphp
                    @endif
                </div>
                @if ($key === 0)
                    <div style="text-align: start">Sections</div>
                @endif
                <div style="text-align: start; margin-top: 0.2rem;">
                    @foreach ($section->where('parts_id', $part->parts_id) as $partsItem)
                        <span
                            style="text-align: start; margin-top: 0.5rem; font-size: 15px !important;">{{ $partsItem->section_no }}.
                            {{ $partsItem->section_title }}</span><br><br>
                    @endforeach
                </div>
            @endforeach
        @endif

        {{-- for schedule  --}}
        @if ($type->contains('maintype_id', 4))
        <div style="text-align: center; margin-top: 0.2rem;">
            THE FIRST SCHEDULE
        </div>
        <hr style="width: 10% !important;margin: 10px auto !important;">
        
            @php $displayedschedules = []; @endphp
            @foreach ($schedule as $key => $schedules)
                @php
                    $scheduleKey = $schedules->act_id . '' . $schedules->maintype_id . '_' . $schedules->schedule_title;
                @endphp
               
                <div style="text-align: center">
                    @if (!in_array($scheduleKey, $displayedschedules))
                        <div
                            style="text-align: center; text-transform: uppercase !important; font-size: 15px !important;">
                            {!! $schedules->schedule_title !!}</div>
                        @php $displayedschedules[] = $sectionKey; @endphp
                    @endif
                </div>
                @if ($key === 0)
                    <div style="text-align: start">Rules</div>
                @endif
                <div style="text-align: start; margin-top: 0.2rem;">
                    @foreach ($rule->where('schedule_id', $schedules->schedule_id) as $ruleItem)
                        <span
                            style="text-align: start; margin-top: 0.5rem; font-size: 15px !important;">{{ $ruleItem->rule_no }}.
                            {{ $ruleItem->rule_title }}</span><br><br>
                    @endforeach
                </div>
            @endforeach
        @endif

    </div>

    {{-- index part end here  --}}

    <div style=" padding: 50px 50px !important; page-break-before: always;">
        <div style="text-align: center; text-transform: uppercase !important;font-size: 20px !important;">
            {{ $act->act_title }}</div>
        <div style="text-align: center; font-size: 15px !important;">{!! $act->act_no !!}</div>
        <div style="font-size: 13px !important; text-align: right !important;">[{{ $act->act_date }}]</div>
        <p style="font-size: 13px !important;">{!! $act->act_description !!}</p>

        @foreach ($act_footnotes as $act_footnote)
            @php
                $footnote_description_array = json_decode($act_footnote->act_footnote_description, true);
            @endphp

            @if ($footnote_description_array && count($footnote_description_array) > 0)
                <hr style="width: 10% !important;margin: 10px auto !important;">
            @endif

            @if ($footnote_description_array)
                @foreach ($footnote_description_array as $footnote)
                    <p class="footnote" style="padding-left: 2rem !important; font-size: 15px !important;">
                        {!! $footnote !!}</p>
                @endforeach
            @endif
        @endforeach



        {{-- for chapter  --}}
        <div>
            @if ($type->contains('maintype_id', 1))
                @php $sectionCounter2 = 1; @endphp
                @foreach ($chapter as $key => $chapterItem)
                    <div style="text-align: center">
                        <div style="text-transform: uppercase !important; font-size: 15px !important;">
                            {!! $chapterItem->chapter_title !!}</div>
                    </div>

                    <div style="text-align: start">
                        @foreach ($section->where('chapter_id', $chapterItem->chapter_id) as $item)
                            <strong><span style="font-size: 15px !important;">{{ $item->section_no }} 
                                    {{ $item->section_title }} </span></strong><span>{!! $item->section_content !!}</span><br>
                            @foreach ($item->subsectionModel as $subSection)
                                <table style="margin-left: 2%">
                                    <tr>
                                        <td style="vertical-align: middle;">{{ $subSection->sub_section_no }}</td>
                                        <td style="">{!! $subSection->sub_section_content !!}</td>
                                    </tr>

                                </table>
                            @endforeach

                            @if ($item->footnoteModel->count() > 0)
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                            @endif
                            @foreach ($item->footnoteModel as $footnoteModel)
                                <span class="footnote" @style('padding-left: 2rem !important; font-size: 15px !important;')>{!! $footnoteModel->footnote_content !!}</span>
                            @endforeach
                        @endforeach
                    </div>
                @endforeach
            @endif
        </div>

        {{-- for priliminary  --}}
        <div>

            @if ($type->contains('maintype_id', 3))
                @php $sectionCounter2 = 1; @endphp

                @foreach ($priliminary as $key => $priliminaryItem)
                    <div style="text-align: center">
                        <div style="text-transform: uppercase !important; font-size: 15px !important;">
                            {!! $priliminaryItem->priliminary_title !!}</div>
                    </div>

                    <div style="text-align: start">
                        @foreach ($section->where('priliminary_id', $priliminaryItem->priliminary_id) as $item)
                            <strong><span style="font-size: 15px !important;">{{ $item->section_no }} 
                                    {{ $item->section_title }} </span></strong><span>{!! $item->section_content !!}</span><br>
                            @foreach ($item->subsectionModel as $subSection)
                                <table style="margin-left: 2%">
                                    <tr>
                                        <td style="vertical-align: middle;">{{ $subSection->sub_section_no }}</td>
                                        <td style="">{!! $subSection->sub_section_content !!}</td>
                                    </tr>
                                </table>
                            @endforeach

                            @if ($item->footnoteModel->count() > 0)
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                            @endif
                            @foreach ($item->footnoteModel as $footnoteModel)
                                <span class="footnote" @style('padding-left: 2rem !important; font-size: 15px !important;')>{!! $footnoteModel->footnote_content !!}</span>
                            @endforeach
                        @endforeach
                    </div>
                @endforeach

            @endif
        </div>

        {{-- for parts  --}}
        <div>
            @if ($type->contains('maintype_id', 2))
                @php $displayedSections = []; @endphp
                @foreach ($parts as $part)
                    @php
                        $sectionKey = $part->act_id . '' . $part->maintype_id . '_' . $part->parts_title;
                    @endphp

                    <div style="text-align: center">
                        @if (!in_array($sectionKey, $displayedSections))
                            <div style="text-transform: uppercase !important; font-size: 15px !important;">
                                {!! $part->parts_title !!}</div>
                            @php $displayedSections[] = $sectionKey; @endphp
                        @endif
                    </div>

                    <div style="text-align: start">
                        @foreach ($section->where('parts_id', $part->parts_id) as $item)
                            <strong><span style="font-size: 15px !important;">{{ $item->section_no }} 
                                    {{ $item->section_title }} </span></strong><span>{!! $item->section_content !!}</span><br>
                            @foreach ($item->subsectionModel as $subSection)
                                <span style="font-size: 15px !important;">{{ $subSection->sub_section_no }}.
                                    {!! $subSection->sub_section_content !!}</span>
                            @endforeach

                            @if ($item->footnoteModel->count() > 0)
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                            @endif
                            @foreach ($item->footnoteModel as $footnoteModel)
                                <span style="font-size: 15px !important;">{!! $footnoteModel->footnote_content !!}</span>
                            @endforeach
                        @endforeach

                    </div>
                @endforeach
            @endif
        </div>

        {{-- for Schedule  --}}
        <div>

            @if ($type->contains('maintype_id', 4))
                @php $ruleCounter2 = 1; @endphp

                @foreach ($schedule as $key => $scheduleItem)
                    <div style="text-align: center">
                        <div style="text-transform: uppercase !important; font-size: 15px !important;">
                            {!! $scheduleItem->schedule_title !!}</div>
                    </div>

                    <div style="text-align: start">
                        @foreach ($rule->where('schedule_id', $scheduleItem->schedule_id) as $item)
                            <strong><span style="font-size: 15px !important;">{{ $item->rule_no }}
                                    {{ $item->rule_title }} </span></strong><span>{!! $item->rule_content !!}</span><br>
                            @foreach ($item->subruleModel as $subRule)
                                <table style="margin-left: 2%">
                                    <tr>
                                        <td style="vertical-align: middle;">{{ $subRule->sub_rule_no }}</td>
                                        <td style="">{!! $subRule->sub_rule_content !!}</td>
                                    </tr>
                                </table>
                            @endforeach

                            @if ($item->footnoteModel->count() > 0)
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                            @endif
                            @foreach ($item->footnoteModel as $footnoteModel)
                                <span class="footnote" @style('padding-left: 2rem !important; font-size: 15px !important;')>{!! $footnoteModel->footnote_content !!}</span>
                            @endforeach
                        @endforeach
                    </div>
                @endforeach

            @endif
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofNlq+M5q9dOB6yS/MbGCD8Fk8MIdjT7+q" crossorigin="anonymous">
    </script>


</body>

</html>
