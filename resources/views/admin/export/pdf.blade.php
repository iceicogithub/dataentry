<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.3/dist/css/bootstrap.min.css"
        integrity="sha384-GLhlTQ8iK7t9LdI8L6FU9tYmVlMGTskTpkEAIaCkIbbVcGpF5eSrhbY6SOMZgT" crossorigin="anonymous">
    <title>Export PDF</title>
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
    </style>
</head>

<body>
    <div class="container">
        <div class="text-uppercase fs-1" style="text-align: center">{{ $act->act_title }}</div>
        <hr>
        @if (isset($section) && count($section) > 0)
            <div class="fs-1" style="text-align: center">ARRANGEMENT OF SECTIONS</div>
        @elseif (isset($regulation) && count($regulation) > 0)
            <div class="fs-1" style="text-align: center">ARRANGEMENT OF REGULATIONS</div>
        @else
            <div class="fs-1" style="text-align: center">ARRANGEMENT OF OTHERS</div>
        @endif

        <hr>
        @if ($type->contains('maintype_id', 1))
            @php $sectionCounter1 = 1; @endphp
            @foreach ($chapter as $key => $chapterItem)
                <div style="text-align: center">
                    <div class="text-uppercase fs-2">{{ $chapterItem->chapter_title }}</div>
                </div>
                @if (!empty($section) && count($section) > 0)
                    @if ($key === 0)
                        <div style="text-align: start">Sections</div>
                    @endif
                    <div style="text-align: start">
                        @foreach ($section->where('chapter_id', $chapterItem->chapter_id) as $sectionItem)
                            <span class="text-capitalize fs-2">{{ $sectionCounter1++ }}.
                                {{ $sectionItem->section_title }}</span><br>
                        @endforeach
                    </div>
                @elseif (!empty($regulation) && count($regulation) > 0)
                    @if ($key === 0)
                        <div style="text-align: start">Regulation</div>
                    @endif
                    <div style="text-align: start">
                        @foreach ($regulation->where('chapter_id', $chapterItem->chapter_id) as $regulationItem)
                            <span class="text-capitalize fs-2">{{ $sectionCounter1++ }}.
                                {{ $regulationItem->regulation_title }}</span><br>
                        @endforeach
                    </div>
                @endif
            @endforeach
        @endif
        @if ($type->contains('maintype_id', 2))
            @php $displayedSections = []; @endphp
            @foreach ($parts as $part)
                @php
                    $sectionKey = $part->act_id . '' . $part->maintype_id . '' . $part->partstype_id . '_' . $part->parts_title;
                @endphp
                <div style="text-align: center">
                    @if (!in_array($sectionKey, $displayedSections))
                        <div class="text-uppercase fs-3 section-padding">{{ $part->partsTypepdf->parts }}</div>
                        <div class="text-uppercase fs-2">{{ $part->parts_title }}</div>
                        @php $displayedSections[] = $sectionKey; @endphp
                    @endif
                </div>
                <div style="text-align: start">
                    @foreach ($forparts->where('parts_id', $part->parts_id) as $partsItem)
                        <span class="text-capitalize fs-2">{{ $partsItem->section_no }}.
                            {{ $partsItem->section_title }}</span><br>
                    @endforeach
                </div>
            @endforeach
        @endif
    </div>

    <div class="container">
        <div class="text-uppercase fs-1" style="text-align: center">{{ $act->act_title }}</div>
        <div class="text-uppercase fs-2" style="text-align: center">{{ $act->act_no }}</div>
        <div class="fs-3 text-end">[{{ $act->act_date }}]</div>
        <p class="fs-3">{!! $act->act_description ! !}</p>
        @if ($type->contains('maintype_id', 1))
            @php $sectionCounter2 = 1; @endphp
            @foreach ($chapter as $key => $chapterItem)
                <div style="text-align: center">
                    <div class="text-uppercase fs-2">{{ $chapterItem->chapter_title }}</div>
                </div>
                @if (!empty($section) && count($section) > 0)
                    <div style="text-align: start">
                        @foreach ($section->where('chapter_id', $chapterItem->chapter_id) as $sectionItem)
                            <div class="section-padding">
                                <span class="text-capitalize fs-2 fw-bold">{{ $sectionCounter2++ }}.
                                    {{ $sectionItem->section_title }}:-</span><span>{!! $sectionItem->section_content !!}</span>
                            </div><br>
                        @endforeach
                    </div>
                @elseif(!empty($regulation) && count($regulation) > 0)
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
        @if ($type->contains('maintype_id', 2))
            @php $displayedSections = []; @endphp
            @foreach ($parts as $part)
                @php
                    $sectionKey = $part->act_id . '' . $part->maintype_id . '' . $part->partstype_id . '_' . $part->parts_title;
                @endphp
                <div style="text-align: center">
                    @if (!in_array($sectionKey, $displayedSections))
                        <div class="text-uppercase fs-3 section-padding">{{ $part->partsTypepdf->parts }}</div>
                        <div class="text-uppercase fs-2">{{ $part->parts_title }}</div>
                        @php $displayedSections[] = $sectionKey; @endphp
                    @endif
                </div>

                <div style="text-align: start">
                    @foreach ($forparts->where('parts_id', $part->parts_id) as $partsItem)
                        <div class="section-padding">
                            <span class="text-capitalize fs-2">{{ $partsItem->section_no }}.
                                {{ $partsItem->section_title }}:-</span><span>{!! $partsItem->section_content !!}</span>
                        </div><br>
                    @endforeach
                </div>
            @endforeach
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofNlq+M5q9dOB6yS/MbGCD8Fk8MIdjT7+q" crossorigin="anonymous">
    </script>

</body>

</html>
