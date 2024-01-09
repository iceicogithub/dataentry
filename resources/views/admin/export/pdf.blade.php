<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.3/dist/css/bootstrap.min.css"
        integrity="sha384-GLhlTQ8iK7t9LdI8L6FU9tYmVlMGTskTpkEAIaCkIbbVcGpF5eSrhbY6SOMZgT" crossorigin="anonymous">
    <title>{{ $act->act_title }}</title>
    <style>
        .container {
            padding: 50px 50px !important;
        }

        hr {
            width: 10% !important;
            margin: 10px auto !important;
        }

        .text-uppercase {
            text-transform: uppercase !important;
        }

        .text-capitalize {
            text-transform: capitalize !important;
        }

        .fs-1 {
            font-size: 20px !important;
        }

        .fs-2 {
            font-size: 15px !important;
        }

        .fs-3 {
            font-size: 13px !important;
        }

        div>span {
            padding: 3px 3px 3px 0px !important;
        }

        .text-end {
            text-align: right !important;
        }

        .fw-bold {
            font-weight: bold !important;
        }

        .section-padding {
            padding: 2px 0px !important;
        }

        .d-flex {
            display: flex !important;
        }
    </style>

</head>

<body>
    <div class="container">
        <div class="text-uppercase fs-1" style="text-align: center">{{ $act->act_title }}</div>
        <hr>
        <div class="fs-1" style="text-align: center">ARRANGEMENT OF SECTIONS</div>
        <hr>

        {{-- for chapter  --}}
        @if ($type->contains('maintype_id', 1))
            @php
                $sectionCounter1 = 1;
            @endphp
            @foreach ($chapter as $key => $chapterItem)
                <div style="text-align: center; margin-bottom: 0.5rem;">
                    <div class="text-uppercase fs-2">{!! $chapterItem->chapter_title !!}</div>
                </div>
                @if (!empty($section) && count($section) > 0)
                    @if ($key === 0)
                        <div style="text-align: start; margin-top: 0.2rem;">Sections</div>
                    @endif
                    <div style="text-align: start; margin-top: 0.2rem;">
                        @foreach ($section->where('chapter_id', $chapterItem->chapter_id) as $sectionItem)
                            <span class="text-capitalize fs-2">{{ $sectionCounter1++ }}.
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
                            <span class="text-capitalize fs-2" style="text-align: start;">{{ $sectionCounter1++ }}.
                                {{ $regulationItem->regulation_title }}</span><br><br>
                        @endforeach
                    </div>
                @endif
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
                    <div id="output{{ $part->parts_id }}">PART 1</div>
                    @if (!in_array($sectionKey, $displayedSections))
                        <div class="text-uppercase fs-2" style="text-align: center;">{!! $part->parts_title !!}</div>
                        @php $displayedSections[] = $sectionKey; @endphp
                    @endif
                </div>
                @if ($key === 0)
                    <div style="text-align: start">Sections</div>
                @endif
                <div style="text-align: start; margin-top: 0.2rem;">
                    @foreach ($section->where('parts_id', $part->parts_id) as $partsItem)
                        <span class="text-capitalize fs-2"
                            style="text-align: start; margin-top: 0.5rem;">{{ $partsItem->section_no }}.
                            {{ $partsItem->section_title }}</span><br><br>
                    @endforeach
                </div>
            @endforeach
        @endif
    </div>

    {{-- index part end here  --}}


    <div class="container">
        <div class="text-uppercase fs-1" style="text-align: center">{{ $act->act_title }}</div>
        <div class="text-uppercase fs-2" style="text-align: center">{{ $act->act_no }}</div>
        <div class="fs-3 text-end">[{{ $act->act_date }}]</div>
        <p class="fs-3">{!! $act->act_description !!}</p>
        <div>
            {{-- for chapter  --}}
            @if ($type->contains('maintype_id', 1))
                @php $sectionCounter2 = 1; @endphp
                @foreach ($chapter as $key => $chapterItem)
                    <div style="text-align: center">
                        <div class="text-uppercase fs-2">{!! $chapterItem->chapter_title !!}</div>
                    </div>

                    <div style="text-align: start">
                        @foreach ($section->where('chapter_id', $chapterItem->chapter_id) as $item)
                            <strong><span class="text-capitalize fs-2">{{ $item->section_no }}.
                                    {{ $item->section_title }}:-</span></strong><span>{!! $item->section_content !!}</span><br>
                            @foreach ($item->subsectionModel as $subSection)
                                <span class="text-capitalize fs-2">{{ $subSection->sub_section_no }}. {!! $subSection->sub_section_content !!}</span>
                            @endforeach
                        @endforeach
                    </div>

                    @foreach ($section->where('chapter_id', $chapterItem->chapter_id) as $item)
                        @if ($item->footnoteModel->count() > 0)
                            <hr>
                        @endif
                        @foreach ($item->footnoteModel as $footnoteModel)
                            <span class="text-capitalize fs-2">{{ $footnoteModel->footnote_no }}.
                                {!! $footnoteModel->footnote_content !!}</span>
                        @endforeach
                    @endforeach

                    {{-- for regulation  --}}
                    @if (!empty($regulation) && count($regulation) > 0)
                        <div style="text-align: start">
                            @foreach ($regulation->where('chapter_id', $chapterItem->chapter_id) as $regulationItem)
                                <div class="section-padding">
                                    <span class="text-capitalize fs-2 fw-bold">{{ $sectionCounter2++ }}.
                                        {{ $regulationItem->regulation_title }}:-</span><span>{!! $regulationItem->regulation_content !!}</span>
                                </div><br>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
        <div>
            {{-- for parts  --}}
            @if ($type->contains('maintype_id', 2))
                @php $displayedSections = []; @endphp
                @foreach ($parts as $part)
                    @php
                        $sectionKey = $part->act_id . '' . $part->maintype_id . '_' . $part->parts_title;
                    @endphp
                    <div style="text-align: center">
                        <div id="output{{ $part->parts_id }}">PART 1</div>
                        @if (!in_array($sectionKey, $displayedSections))
                            <div class="text-uppercase fs-2">{!! $part->parts_title !!}</div>
                            @php $displayedSections[] = $sectionKey; @endphp
                        @endif
                    </div>

                    <div style="text-align: start">
                        @foreach ($section->where('parts_id', $part->parts_id) as $item)
                            <strong><span class="text-capitalize fs-2">{{ $item->section_no }}.
                                    {{ $item->section_title }}:-</span></strong><span>{!! $item->section_content !!}</span><br>
                            @foreach ($item->subsectionModel as $subSection)
                                <span class="text-capitalize fs-2">{{ $subSection->sub_section_no }}.
                                    {!! $subSection->sub_section_content !!}</span>
                            @endforeach
                        @endforeach
                    </div>

                    @foreach ($section->where('parts_id', $part->parts_id) as $item)
                        @if ($item->footnoteModel->count() > 0)
                            <hr>
                        @endif
                        @foreach ($item->footnoteModel as $footnoteModel)
                            <span class="text-capitalize fs-2">{{ $footnoteModel->footnote_no }}.
                                {!! $footnoteModel->footnote_content !!}</span>
                        @endforeach
                    @endforeach
                @endforeach
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofNlq+M5q9dOB6yS/MbGCD8Fk8MIdjT7+q" crossorigin="anonymous">
    </script>


</body>

</html>
