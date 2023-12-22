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
        hr {
            width: 10% !important;
            margin: 10px auto !important;
        }

        .text-uppercase {
            text-transform: uppercase !important;
        }

        .fs-1 {
            font-size: 20px !important;
        }

        .fs-2 {
            font-size: 15px !important;
        }

        .fs-2 {
            font-size: 13px !important;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="text-uppercase fs-1" style="text-align: center">{{ $act->act_title }}</div>
        <hr>
        <div class="fs-1" style="text-align: center">ARRANGEMENT OF SECTIONS</div>
        <hr>
        @foreach ($chapter as $chapterItem)
            <div style="text-align: center">
                <div class="text-uppercase fs-2">{{ $chapterItem->chapter_title }}</div>
            </div>
            <ol style="text-align: start">
                @foreach ($section->where('chapter_id', $chapterItem->chapter_id) as $sectionItem)
                    <li>{{ $sectionItem->section_title }}</li>
                @endforeach
            </ol>
        @endforeach
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofNlq+M5q9dOB6yS/MbGCD8Fk8MIdjT7+q" crossorigin="anonymous">
    </script>

</body>

</html>
