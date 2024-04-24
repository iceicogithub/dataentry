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
    
     @page {
        size: A4; /* Set the size of the page to A4 */
        margin: 2cm; /* Set the margin for all sides to 2cm */
        margin-top: 1cm !important; /* Set a top margin of 3cm for all pages */
    }
/* Reset default styles */
body, html {
    margin: 0;
    padding: 0;
}

/* Define global styles */
 /* Ensure content fits within page boundaries */
       

        /* Define styles for content */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.3;
        }
        table p {
            margin: 0;
        }

        .container {
            padding: 50px;
        }

        .section {
            margin-top: 20px;
        }
        
        table {
            page-break-inside: avoid;
            font-size: 15px !important;
            width: 100%; /* Ensure table takes full width */
            border-collapse: collapse; /* Optional: collapse borders between cells */
        }
        
        td {
            vertical-align: top; /* Align content at the top of the cell */
        }
        
        td.section-no,
        .article-no,
        .stschedule-no,
        .list-no,
        .regulation-no,
        .rule-no,
        .annexure-no,
        .order-no,
        .appendices-no,
        .part-no {
            white-space: nowrap;
            width: 2%; /* Set width for section-no column */
            page-break-inside: avoid !important;
           
        }
        
        td.section-title,
        .article-title,
        .stschedule-title,
        .list-title,
        .regulation-title,
        .rule-title,
        .annexure-title,
        .order-title,
        .appendices-title,
        .part-title {
            
            width: 98%; /* Set width for section-title column */
            padding-left: 8px; /* Remove default padding to start content from the beginning */
            page-break-inside: avoid !important;
        }
        
        .footnote {
    display: block; /* Ensures each footnote appears on a new line */
    margin-bottom: 5px; /* Adjust the bottom margin between footnotes */
    line-height: 1.2; /* Adjust the line height of the footnotes */
}
   
   
   
   
   
        
 .section-container:after {
        content: "";
        display: table;
        clear: both;
    }
  .section-container{
      padding-left:25px!important;
  }
    .section-item {
        font-size: 12px;
        margin-right: 20px;
        float:left;    /* Float elements to the left */
    }

    .sub-section-no {
        font-size: 13px; /* Example size; adjust as needed */
        font-weight: bold; /* Example style; adjust as needed */
         

    .sub-section-content {
        overflow-wrap: break-word; /* Allow content to break into new lines if needed */
          text-align: justify; 
    }
</style>


            

    </style>

</head>

<body>

    <div style=" padding: 50px 50px !important;">
        <div style="text-align: center; text-transform: uppercase !important;font-size: 20px !important;">
            {{ $act->act_title }}</div>
        <hr style="width: 10% !important;margin: 10px auto !important;">
        <div style="text-align: center; font-size: 20px !important;">ARRANGEMENT OF SECTIONS</div>
        <hr style="width: 10% !important;margin: 10px auto !important;">

        <!--<div style="text-align: start; margin-top: 0.2rem;">PREAMBLE</div>-->
        {{-- for chapter  --}}
        @foreach($combinedItems as $item)
        @if(isset($item['parts_id']))
            <div style="text-align: center; margin-bottom: 0.5rem;">
                <div style="text-transform: uppercase !important; font-size: 16px !important;font-weight: bold!important; margin-top: 0.4rem;">
                    {!! preg_replace('/[0-9\[\]\.]/', '', $item['parts_title']) !!}</div>
            </div>
            @if (!empty($item['sections']))
                <div style="text-align:start; margin-top: 0.4rem;">
                    @foreach ($item['sections'] as $sectionItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="section-no">
                                    <p>{{ $sectionItem['section_no'] }}</p>
                                </td>
                                <td class="section-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $sectionItem['section_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['articles']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['articles'] as $articleItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="article-no">
                                    <p>{{ $articleItem['article_no'] }}</p>
                                </td>
                                <td class="article-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $articleItem['article_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['rules']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['rules'] as $ruleItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="rule-no">
                                    <p>{{ $ruleItem['rule_no'] }}</p>
                                </td>
                                <td class="rule-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $ruleItem['rule_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['regulation']))
                    <div style="text-align: start; margin-top: 0.4rem;">
                        @foreach ($item['regulation'] as $regulationItem)
                            <table style="font-size: 15px !important;">
                                <tr>
                                    <td class="regulation-no">
                                        <p>{{ $regulationItem['regulation_no'] }}</p>
                                    </td>
                                    <td class="regulation-title">
                                        <p>{!! preg_replace('/[0-9\[\]\.]/', '', $regulationItem['regulation_title']) !!}</p>
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </div>
            @endif
            @if (!empty($item['lists']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['lists'] as $listItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="list-no">
                                    <p>{{ $listItem['list_no'] }}</p>
                                </td>
                                <td class="list-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $listItem['list_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['part']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['part'] as $partItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="part-no">
                                    <p>{{ $partItem['part_no'] }}</p>
                                </td>
                                <td class="part-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $partItem['part_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['appendices']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['appendices'] as $appendicesItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="appendices-no">
                                    <p>{{ $appendicesItem['appendices_no'] }}</p>
                                </td>
                                <td class="appendices-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $appendicesItem['appendices_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['order']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['order'] as $orderItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="order-no">
                                    <p>{{ $orderItem['order_no'] }}</p>
                                </td>
                                <td class="order-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $orderItem['order_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['annexure']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['annexure'] as $annexureItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="annexure-no">
                                    <p>{{ $annexureItem['annexure_no'] }}</p>
                                </td>
                                <td class="annexure-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $annexureItem['annexure_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['stschedule']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['stschedule'] as $stscheduleItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="stschedule-no">
                                    <p>{{ $stscheduleItem['stschedule_no'] }}</p>
                                </td>
                                <td class="stschedule-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $stscheduleItem['stschedule_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
        @endif
        @if(isset($item['chapter_id']))
            <div style="text-align: center; margin-bottom: 0.5rem;">
                <div style="text-transform: uppercase !important; font-size: 15px !important;font-weight: bold!important; margin-top: 0.4rem;">
                    {!! preg_replace('/[0-9\[\]\.]/', '', $item['chapter_title']) !!}</div>
            </div>
            @if (!empty($item['sections']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['sections'] as $sectionItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="section-no">
                                    <p>{{ $sectionItem['section_no'] }}</p>
                                </td>
                                <td class="section-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $sectionItem['section_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['articles']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['articles'] as $articleItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="article-no">
                                    <p>{{ $articleItem['article_no'] }}</p>
                                </td>
                                <td class="article-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $articleItem['article_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['rules']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['rules'] as $ruleItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="rule-no">
                                    <p>{{ $ruleItem['rule_no'] }}</p>
                                </td>
                                <td class="rule-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $ruleItem['rule_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if(!empty($item['regulation']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['regulation'] as $regulationItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="regulation-no">
                                    <p>{{ $regulationItem['regulation_no'] }}</p>
                                </td>
                                <td class="regulation-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $regulationItem['regulation_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['lists']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['lists'] as $listItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="list-no">
                                    <p>{{ $listItem['list_no'] }}</p>
                                </td>
                                <td class="list-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $listItem['list_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['part']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['part'] as $partItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="part-no">
                                    <p>{{ $partItem['part_no'] }}</p>
                                </td>
                                <td class="part-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $partItem['part_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['appendices']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['appendices'] as $appendicesItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="appendices-no">
                                    <p>{{ $appendicesItem['appendices_no'] }}</p>
                                </td>
                                <td class="appendices-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $appendicesItem['appendices_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['order']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['order'] as $orderItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="order-no">
                                    <p>{{ $orderItem['order_no'] }}</p>
                                </td>
                                <td class="order-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $orderItem['order_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['annexure']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['annexure'] as $annexureItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="annexure-no">
                                    <p>{{ $annexureItem['annexure_no'] }}</p>
                                </td>
                                <td class="annexure-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $annexureItem['annexure_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['stschedule']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['stschedule'] as $stscheduleItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="stschedule-no">
                                    <p>{{ $stscheduleItem['stschedule_no'] }}</p>
                                </td>
                                <td class="stschedule-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $stscheduleItem['stschedule_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
        @endif
        @if(isset($item['priliminary_id']))
            <div style="text-align: center; margin-bottom: 0.5rem;">
                <div style="text-transform: uppercase !important; font-size: 15px !important;font-weight: bold!important; margin-top: 0.4rem;">
                    {!! preg_replace('/[0-9\[\]\.]/', '', $item['priliminary_title']) !!}</div>
            </div>
            @if (!empty($item['sections']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['sections'] as $sectionItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="section-no">
                                    <p>{{ $sectionItem['section_no'] }}</p>
                                </td>
                                <td class="section-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $sectionItem['section_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['articles']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['articles'] as $articleItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="article-no">
                                    <p>{{ $articleItem['article_no'] }}</p>
                                </td>
                                <td class="article-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $articleItem['article_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['rules']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['rules'] as $ruleItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="rule-no">
                                    <p>{{ $ruleItem['rule_no'] }}</p>
                                </td>
                                <td class="rule-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $ruleItem['rule_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['regulation']))
                    <div style="text-align: start; margin-top: 0.4rem;">
                        @foreach ($item['regulation'] as $regulationItem)
                            <table style="font-size: 15px !important;">
                                <tr>
                                    <td class="regulation-no">
                                        <p>{{ $regulationItem['regulation_no'] }}</p>
                                    </td>
                                    <td class="regulation-title">
                                        <p>{!! preg_replace('/[0-9\[\]\.]/', '', $regulationItem['regulation_title']) !!}</p>
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </div>
            @endif
            @if (!empty($item['lists']))
                    <div style="text-align: start; margin-top: 0.4rem;">
                        @foreach ($item['lists'] as $listItem)
                            <table style="font-size: 15px !important;">
                                <tr>
                                    <td class="list-no">
                                        <p>{{ $listItem['list_no'] }}</p>
                                    </td>
                                    <td class="list-title">
                                        <p>{!! preg_replace('/[0-9\[\]\.]/', '', $listItem['list_title']) !!}</p>
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </div>
            @endif
            @if (!empty($item['part']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['part'] as $partItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="part-no">
                                    <p>{{ $partItem['part_no'] }}</p>
                                </td>
                                <td class="part-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $partItem['part_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['appendices']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['appendices'] as $appendicesItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="appendices-no">
                                    <p>{{ $appendicesItem['appendices_no'] }}</p>
                                </td>
                                <td class="appendices-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $appendicesItem['appendices_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['order']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['order'] as $orderItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="order-no">
                                    <p>{{ $orderItem['order_no'] }}</p>
                                </td>
                                <td class="order-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $orderItem['order_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['annexure']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['annexure'] as $annexureItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="annexure-no">
                                    <p>{{ $annexureItem['annexure_no'] }}</p>
                                </td>
                                <td class="annexure-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $annexureItem['annexure_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['stschedule']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['stschedule'] as $stscheduleItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="stschedule-no">
                                    <p>{{ $stscheduleItem['stschedule_no'] }}</p>
                                </td>
                                <td class="stschedule-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $stscheduleItem['stschedule_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
        @endif
        @if(isset($item['schedule_id']))
            <div style="text-align: center; margin-bottom: 0.5rem;">
                <div style="text-transform: uppercase !important; font-size: 15px !important;font-weight: bold!important; margin-top: 0.4rem;">
                    {!! preg_replace('/[0-9\[\]\.]/', '', $item['schedule_title']) !!}</div>
            </div>
            @if (!empty($item['sections']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['sections'] as $sectionItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="section-no">
                                    <p>{{ $sectionItem['section_no'] }}</p>
                                </td>
                                <td class="section-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $sectionItem['section_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['articles']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['articles'] as $articleItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="article-no">
                                    <p>{{ $articleItem['article_no'] }}</p>
                                </td>
                                <td class="article-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $articleItem['article_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['rules']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['rules'] as $ruleItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="rule-no">
                                    <p>{{ $ruleItem['rule_no'] }}</p>
                                </td>
                                <td class="rule-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $ruleItem['rule_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['regulation']))
                    <div style="text-align: start; margin-top: 0.4rem;">
                        @foreach ($item['regulation'] as $regulationItem)
                            <table style="font-size: 15px !important;">
                                <tr>
                                    <td class="regulation-no">
                                        <p>{{ $regulationItem['regulation_no'] }}</p>
                                    </td>
                                    <td class="regulation-title">
                                        <p>{!! preg_replace('/[0-9\[\]\.]/', '', $regulationItem['regulation_title']) !!}</p>
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </div>
            @endif
            @if (!empty($item['lists']))
                    <div style="text-align: start; margin-top: 0.4rem;">
                        @foreach ($item['lists'] as $listItem)
                            <table style="font-size: 15px !important;">
                                <tr>
                                    <td class="list-no">
                                        <p>{{ $listItem['list_no'] }}</p>
                                    </td>
                                    <td class="list-title">
                                        <p>{!! preg_replace('/[0-9\[\]\.]/', '', $listItem['list_title']) !!}</p>
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </div>
            @endif
            @if (!empty($item['part']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['part'] as $partItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="part-no">
                                    <p>{{ $partItem['part_no'] }}</p>
                                </td>
                                <td class="part-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $partItem['part_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['appendices']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['appendices'] as $appendicesItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="appendices-no">
                                    <p>{{ $appendicesItem['appendices_no'] }}</p>
                                </td>
                                <td class="appendices-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $appendicesItem['appendices_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['order']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['order'] as $orderItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="order-no">
                                    <p>{{ $orderItem['order_no'] }}</p>
                                </td>
                                <td class="order-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $orderItem['order_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['annexure']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['annexure'] as $annexureItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="annexure-no">
                                    <p>{{ $annexureItem['annexure_no'] }}</p>
                                </td>
                                <td class="annexure-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $annexureItem['annexure_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['stschedule']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['stschedule'] as $stscheduleItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="stschedule-no">
                                    <p>{{ $stscheduleItem['stschedule_no'] }}</p>
                                </td>
                                <td class="stschedule-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $stscheduleItem['stschedule_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
        @endif
        @if(isset($item['appendix_id']))
            <div style="text-align: center; margin-bottom: 0.5rem;">
                <div style="text-transform: uppercase !important; font-size: 15px !important;font-weight: bold!important; margin-top: 0.4rem;">
                    {!! preg_replace('/[0-9\[\]\.]/', '', $item['appendix_title']) !!}</div>
            </div>
            @if (!empty($item['sections']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['sections'] as $sectionItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="section-no">
                                    <p>{{ $sectionItem['section_no'] }}</p>
                                </td>
                                <td class="section-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $sectionItem['section_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['articles']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['articles'] as $articleItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="article-no">
                                    <p>{{ $articleItem['article_no'] }}</p>
                                </td>
                                <td class="article-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $articleItem['article_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['rules']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['rules'] as $ruleItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="rule-no">
                                    <p>{{ $ruleItem['rule_no'] }}</p>
                                </td>
                                <td class="rule-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $ruleItem['rule_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['regulation']))
                    <div style="text-align: start; margin-top: 0.4rem;">
                        @foreach ($item['regulation'] as $regulationItem)
                            <table style="font-size: 15px !important;">
                                <tr>
                                    <td class="regulation-no">
                                        <p>{{ $regulationItem['regulation_no'] }}</p>
                                    </td>
                                    <td class="regulation-title">
                                        <p>{!! preg_replace('/[0-9\[\]\.]/', '', $regulationItem['regulation_title']) !!}</p>
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </div>
            @endif
            @if (!empty($item['lists']))
                    <div style="text-align: start; margin-top: 0.4rem;">
                        @foreach ($item['lists'] as $listItem)
                            <table style="font-size: 15px !important;">
                                <tr>
                                    <td class="list-no">
                                        <p>{{ $listItem['list_no'] }}</p>
                                    </td>
                                    <td class="list-title">
                                        <p>{!! preg_replace('/[0-9\[\]\.]/', '', $listItem['list_title']) !!}</p>
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </div>
            @endif
            @if (!empty($item['part']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['part'] as $partItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="part-no">
                                    <p>{{ $partItem['part_no'] }}</p>
                                </td>
                                <td class="part-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $partItem['part_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['appendices']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['appendices'] as $appendicesItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="appendices-no">
                                    <p>{{ $appendicesItem['appendices_no'] }}</p>
                                </td>
                                <td class="appendices-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $appendicesItem['appendices_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['order']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['order'] as $orderItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="order-no">
                                    <p>{{ $orderItem['order_no'] }}</p>
                                </td>
                                <td class="order-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $orderItem['order_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['annexure']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['annexure'] as $annexureItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="annexure-no">
                                    <p>{{ $annexureItem['annexure_no'] }}</p>
                                </td>
                                <td class="annexure-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $annexureItem['annexure_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['stschedule']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['stschedule'] as $stscheduleItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="stschedule-no">
                                    <p>{{ $stscheduleItem['stschedule_no'] }}</p>
                                </td>
                                <td class="stschedule-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $stscheduleItem['stschedule_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
       @endif
       @if(isset($item['main_order_id']))
            <div style="text-align: center; margin-bottom: 0.5rem;">
                <div style="text-transform: uppercase !important; font-size: 15px !important; margin-top: 0.4rem;">
                    {!! preg_replace('/[0-9\[\]\.]/', '', $item['main_order_title']) !!}</div>
            </div>
            @if (!empty($item['sections']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['sections'] as $sectionItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="section-no">
                                    <p>{{ $sectionItem['section_no'] }}</p>
                                </td>
                                <td class="section-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $sectionItem['section_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['articles']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['articles'] as $articleItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td style="vertical-align: baseline;">
                                    <p>{{ $articleItem['article_no'] }}</p>
                                </td>
                                <td>
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $articleItem['article_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['rules']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['rules'] as $ruleItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="rule-no">
                                    <p>{{ $ruleItem['rule_no'] }}</p>
                                </td>
                                <td class="rule-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $ruleItem['rule_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['regulation']))
                    <div style="text-align: start; margin-top: 0.4rem;">
                        @foreach ($item['regulation'] as $regulationItem)
                            <table style="font-size: 15px !important;">
                                <tr>
                                    <td class="regulation-no">
                                        <p>{{ $regulationItem['regulation_no'] }}</p>
                                    </td>
                                    <td class="regulation-title">
                                        <p>{!! preg_replace('/[0-9\[\]\.]/', '', $regulationItem['regulation_title']) !!}</p>
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </div>
            @endif
            @if (!empty($item['lists']))
                    <div style="text-align: start; margin-top: 0.4rem;">
                        @foreach ($item['lists'] as $listItem)
                            <table style="font-size: 15px !important;">
                                <tr>
                                    <td class="list-no">
                                        <p>{{ $listItem['list_no'] }}</p>
                                    </td>
                                    <td class="list-title">
                                        <p>{!! preg_replace('/[0-9\[\]\.]/', '', $listItem['list_title']) !!}</p>
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </div>
            @endif
            @if (!empty($item['part']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['part'] as $partItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="part-no">
                                    <p>{{ $partItem['part_no'] }}</p>
                                </td>
                                <td class="part-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $partItem['part_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['appendices']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['appendices'] as $appendicesItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="appendices-no">
                                    <p>{{ $appendicesItem['appendices_no'] }}</p>
                                </td>
                                <td class="appendices-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $appendicesItem['appendices_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['order']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['order'] as $orderItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="order-no">
                                    <p>{{ $orderItem['order_no'] }}</p>
                                </td>
                                <td class="order-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $orderItem['order_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['annexure']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['annexure'] as $annexureItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="annexure-no">
                                    <p>{{ $annexureItem['annexure_no'] }}</p>
                                </td>
                                <td class="annexure-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $annexureItem['annexure_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['stschedule']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['stschedule'] as $stscheduleItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td class="stschedule-no">
                                    <p>{{ $stscheduleItem['stschedule_no'] }}</p>
                                </td>
                                <td class="stschedule-title">
                                    <p>{!! preg_replace('/[0-9\[\]\.]/', '', $stscheduleItem['stschedule_title']) !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
       @endif
    @endforeach
</div>
<div style=" padding: 50px 50px !important; page-break-before: always;">
    <div style="text-align: center; text-transform: uppercase !important;font-size: 24px !important;">
        {{ $act->act_title }}</div>
    <div style="text-align: center; font-size: 15px !important; margin-top: 0.4rem;">{!! $act->act_no !!}</div>
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
                <em class="footnote"
                    style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">
                    {!! $footnote !!}</em>
            @endforeach
        @endif
    @endforeach

    <div>
        @foreach($combinedItems as $item)
            @if (isset($item['chapter_id']))
                <div style="text-align: center">
                    <div style="text-transform: uppercase !important; font-size: 15px !important; margin-top: 0.4rem;">
                        {!! $item['chapter_title'] !!}
                    </div>
                </div>
                @if (!empty($item['sections']))   
                    <div style="text-align: start">
                        @foreach ($item['sections'] as $section)
                            <table style="font-size: 15px !important; margin-bottom:5px!important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="section-no" style=" font-weight:bold;">
                                            <p>{{ $section['section_no'] }}</p>
                                        </td>
                                        <td class="section-title">
                                            <p>{!! $section['section_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            <span  style="margin-bottom:5px!important;">{!! $section['section_content'] !!}</span>
                                
                                @if (!empty($section['subsection_model']))
                                    @foreach ($section['subsection_model'] as $subSection)
                                        <!-- <div class="section-container">-->
                                        <!--    <div class="section-item">-->
                                        <!--        <span class="section-number">{{ $subSection['sub_section_no'] }}</span>-->
                                        <!--        <span class="section-content">{!! $subSection['sub_section_content'] !!}</span>-->
                                        <!--    </div>-->
                                        <!--</div>-->
                                        <div class="section-container" style="margin-top: 0.4rem;">
                                            <div class="section-item sub-section-no ">{{ $subSection['sub_section_no'] }}</div>
                                            <div class="section-item sub-section-content" style="margin-top:-12px!important">{!! $subSection['sub_section_content'] !!}</div>
                                        </div>
                                        <!--<div class="section-container">-->
                                        <!--    <div class="section-item no-wrap">{{ $subSection['sub_section_no'] }}</div>-->
                                        <!--    <div class="section-item" style="margin-top:-12px!important">{!! $subSection['sub_section_content'] !!}</div>-->
                                        <!--</div>-->
                                        <!-- <table style="width: 100%;page-break-inside: avoid; page-break-before: avoid; page-break-after: avoid;">-->
                                        <!--    <tr>-->
                                        <!--        <td style="white-space: nowrap; page-break-inside: avoid;page-break-before: avoid; page-break-after: avoid;">{{ $subSection['sub_section_no'] }}</td>-->
                                        <!--        <td style="page-break-inside: avoid;page-break-before: avoid; page-break-after: avoid;" >{!! $subSection['sub_section_content'] !!}</td>-->
                                        <!--    </tr>-->
                                        <!--</table>-->
                                       
                                    @endforeach
                                @endif
                                
                                @if (!empty($section['footnote_model']))
                                    @foreach ($section['footnote_model'] as $footnoteModel)
                                        <em class="footnote">{!! $footnoteModel['footnote_content'] !!}</em>
                                    @endforeach
                                @endif
                    
                                <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                            
                        @endforeach
                        
                    </div>
                @endif  
                @if (!empty($item['articles']))   
                    <div style="text-align: start">
                        @foreach ($item['articles'] as $article)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="article-no">
                                            <p>{{ $article['article_no'] }}</p>
                                        </td>
                                        <td class="article-title">
                                            <p>{!! $article['article_title'] !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $article['article_content'] !!}</span>
                            
                            @if (!empty($article['sub_article_model']))
                                @foreach ($article['sub_article_model'] as $subArticle)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="article-no">
                                                <p>{{ $subArticle['sub_article_no'] }}</p>
                                            </td>
                                            <td class="article-title">
                                                <p>{!! $subArticle['sub_article_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($article['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($article['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['rules']))   
                    <div style="text-align: start">
                        @foreach ($item['rules'] as $rule)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="rule-no">
                                            <p>{{ $rule['rule_no'] }}</p>
                                        </td>
                                        <td class="rule-title">
                                            <p>{!! $rule['rule_title'] !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $rule['rule_content'] !!}</span>
                            
                            @if (!empty($rule['subrule_model']))
                                @foreach ($rule['subrule_model'] as $subRule)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="rule-no">
                                                <p>{{ $subRule['sub_rule_no'] }}</p>
                                            </td>
                                            <td class="rule-title">
                                                <p>{!! $subRule['sub_rule_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($rule['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($rule['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['regulation']))   
                    <div style="text-align: start">
                        @foreach ($item['regulation'] as $regulation)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="regulation-no">
                                            <p>{{ $regulation['regulation_no'] }}</p>
                                        </td>
                                        <td class="regulation-title">
                                            <p>{!! $regulation['regulation_title'] !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $regulation['regulation_content'] !!}</span>
                            
                            @if (!empty($regulation['sub_regulation_model']))
                                @foreach ($regulation['sub_regulation_model'] as $subRegulation)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="regulation-no">
                                                <p>{{ $subRegulation['sub_regulation_no'] }}</p>
                                            </td>
                                            <td class="regulation-title">
                                                <p>{!! $subRegulation['sub_regulation_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($regulation['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($regulation['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['lists']))   
                    <div style="text-align: start">
                        @foreach ($item['lists'] as $list)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="list-no">
                                            <p>{{ $list['list_no'] }}</p>
                                        </td>
                                        <td class="list-title">
                                            <p>{!! $list['list_title'] !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $list['list_content'] !!}</span>
                            
                            @if (!empty($list['sub_list_model']))
                                @foreach ($list['sub_list_model'] as $subList)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="list-no">
                                                <p>{{ $subList['sub_list_no'] }}</p>
                                            </td>
                                            <td class="list-title">
                                                <p>{!! $subList['sub_list_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($list['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($list['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['part']))   
                    <div style="text-align: start">
                        @foreach ($item['part'] as $part)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="part-no">
                                            <p>{{ $partItem['part_no'] }}</p>
                                        </td>
                                        <td class="part-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $partItem['part_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $part['part_content'] !!}</span>
                            
                            @if (!empty($part['sub_part_model']))
                                @foreach ($part['sub_part_model'] as $subPart)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="appendices-no">
                                                <p>{{ $subPart['sub_part_no'] }}</p>
                                            </td>
                                            <td class="appendices-title">
                                                <p>{!! $subPart['sub_part_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($part['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($part['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['appendices']))   
                    <div style="text-align: start">
                        @foreach ($item['appendices'] as $appendices)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="appendices-no">
                                            <p>{{ $appendicesItem['appendices_no'] }}</p>
                                        </td>
                                        <td class="appendices-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $appendicesItem['appendices_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $appendices['appendices_content'] !!}</span>
                            
                            @if (!empty($appendices['sub_appendices_model']))
                                @foreach ($appendices['sub_appendices_model'] as $subAppendices)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="appendices-no">
                                                <p>{{ $subAppendices['sub_appendices_no'] }}</p>
                                            </td>
                                            <td class="appendices-title">
                                                <p>{!! $subAppendices['sub_appendices_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($appendices['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($appendices['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['order']))   
                    <div style="text-align: start">
                        @foreach ($item['order'] as $order)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="order-no">
                                            <p>{{ $orderItem['order_no'] }}</p>
                                        </td>
                                        <td class="order-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $orderItem['order_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $order['order_content'] !!}</span>
                            
                            @if (!empty($order['sub_order_model']))
                                @foreach ($order['sub_order_model'] as $subOrder)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="order-no">
                                                <p>{{ $subOrder['sub_order_no'] }}</p>
                                            </td>
                                            <td class="order-title">
                                                <p>{!! $subOrder['sub_order_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($order['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($order['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['annexure']))   
                    <div style="text-align: start">
                        @foreach ($item['annexure'] as $annexure)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="annexure-no">
                                            <p>{{ $annexureItem['annexure_no'] }}</p>
                                        </td>
                                        <td class="annexure-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $annexureItem['annexure_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $annexure['annexure_content'] !!}</span>
                            
                            @if (!empty($annexure['sub_annexure_model']))
                                @foreach ($annexure['sub_annexure_model'] as $subAnnexure)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="annexure-no">
                                                <p>{{ $subAnnexure['sub_annexure_no'] }}</p>
                                            </td>
                                            <td class="annexure-title">
                                                <p>{!! $subAnnexure['sub_annexure_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($annexure['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($annexure['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['stschedule']))   
                    <div style="text-align: start">
                        @foreach ($item['stschedule'] as $stschedule)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="stschedule-no">
                                            <p>{{ $stscheduleItem['stschedule_no'] }}</p>
                                        </td>
                                        <td class="stschedule-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $stscheduleItem['stschedule_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $stschedule['stschedule_content'] !!}</span>
                            
                            @if (!empty($stschedule['sub_stschedule_model']))
                                @foreach ($stschedule['sub_stschedule_model'] as $subStschedule)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="stschedule-no">
                                                <p>{{ $subStschedule['sub_stschedule_no'] }}</p>
                                            </td>
                                            <td class="stschedule-title">
                                                <p>{!! $subStschedule['sub_stschedule_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($stschedule['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($stschedule['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
            @endif
            @if (isset($item['parts_id']))
                <div style="text-align: center">
                    <div style="text-transform: uppercase !important; font-size: 15px !important; margin-top: 0.4rem;">
                        {!! $item['parts_title'] !!}
                    </div>
                </div>
                @if (!empty($item['sections']))   
                    <div style="text-align: start">
                        @foreach ($item['sections'] as $section)
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="section-no">
                                            <p>{{ $section['section_no'] }}</p>
                                        </td>
                                        <td class="section-title">
                                            <p>{!! $section['section_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            <span>{!! $section['section_content'] !!}</span>
                            
                            @if (!empty($section['subsection_model']))
                                @foreach ($section['subsection_model'] as $subSection)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="section-no">
                                                <p>{{ $subSection['sub_section_no'] }}</p>
                                            </td>
                                            <td class="section-title">
                                                <p>{!! $subSection['sub_section_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                             
                            @if (!empty($section['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($section['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">

                        @endforeach
                    </div>
                   
                @endif  
                @if (!empty($item['articles']))   
                    <div style="text-align: start">
                        @foreach ($item['articles'] as $article)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="article-no">
                                            <p>{{ $article['article_no'] }}</p>
                                        </td>
                                        <td class="article-title">
                                            <p>{!! $article['article_title'] !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $article['article_content'] !!}</span>
                            
                            @if (!empty($article['sub_article_model']))
                                @foreach ($article['sub_article_model'] as $subArticle)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="article-no">
                                                <p>{{ $subArticle['sub_article_no'] }}</p>
                                            </td>
                                            <td class="article-title">
                                                <p>{!! $subArticle['sub_article_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($article['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($article['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['rules']))   
                    <div style="text-align: start">
                        @foreach ($item['rules'] as $rule)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="rule-no">
                                            <p>{{ $rule['rule_no'] }}</p>
                                        </td>
                                        <td class="rule-title">
                                            <p>{!! $rule['rule_title'] !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $rule['rule_content'] !!}</span>
                            
                            @if (!empty($rule['subrule_model']))
                                @foreach ($rule['subrule_model'] as $subRule)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="rule-no">
                                                <p>{{ $subRule['sub_rule_no'] }}</p>
                                            </td>
                                            <td class="rule-title">
                                                <p>{!! $subRule['sub_rule_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($rule['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($rule['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['regulation']))   
                    <div style="text-align: start">
                        @foreach ($item['regulation'] as $regulation)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="regulation-no">
                                            <p>{{ $regulation['regulation_no'] }}</p>
                                        </td>
                                        <td class="regulation-title">
                                            <p>{!! $regulation['regulation_title'] !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $regulation['regulation_content'] !!}</span>
                            
                            @if (!empty($regulation['sub_regulation_model']))
                                @foreach ($regulation['sub_regulation_model'] as $subRegulation)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="regulation-no">
                                                <p>{{ $subRegulation['sub_regulation_no'] }}</p>
                                            </td>
                                            <td class="regulation-title">
                                                <p>{!! $subRegulation['sub_regulation_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($regulation['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($regulation['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['lists']))   
                    <div style="text-align: start">
                        @foreach ($item['lists'] as $list)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="list-no">
                                            <p>{{ $list['list_no'] }}</p>
                                        </td>
                                        <td class="list-title">
                                            <p>{!! $list['list_title'] !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $list['list_content'] !!}</span>
                            
                            @if (!empty($list['sub_list_model']))
                                @foreach ($list['sub_list_model'] as $subList)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="list-no">
                                                <p>{{ $subList['sub_list_no'] }}</p>
                                            </td>
                                            <td class="list-title">
                                                <p>{!! $subList['sub_list_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($list['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($list['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['part']))   
                    <div style="text-align: start">
                        @foreach ($item['part'] as $part)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="part-no">
                                            <p>{{ $partItem['part_no'] }}</p>
                                        </td>
                                        <td class="part-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $partItem['part_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $part['part_content'] !!}</span>
                            
                            @if (!empty($part['sub_part_model']))
                                @foreach ($part['sub_part_model'] as $subPart)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="appendices-no">
                                                <p>{{ $subPart['sub_part_no'] }}</p>
                                            </td>
                                            <td class="appendices-title">
                                                <p>{!! $subPart['sub_part_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($part['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($part['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['appendices']))   
                    <div style="text-align: start">
                        @foreach ($item['appendices'] as $appendices)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="appendices-no">
                                            <p>{{ $appendicesItem['appendices_no'] }}</p>
                                        </td>
                                        <td class="appendices-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $appendicesItem['appendices_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $appendices['appendices_content'] !!}</span>
                            
                            @if (!empty($appendices['sub_appendices_model']))
                                @foreach ($appendices['sub_appendices_model'] as $subAppendices)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="appendices-no">
                                                <p>{{ $subAppendices['sub_appendices_no'] }}</p>
                                            </td>
                                            <td class="appendices-title">
                                                <p>{!! $subAppendices['sub_appendices_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($appendices['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($appendices['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['order']))   
                    <div style="text-align: start">
                        @foreach ($item['order'] as $order)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                         <td class="order-no">
                                            <p>{{ $orderItem['order_no'] }}</p>
                                        </td>
                                        <td class="order-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $orderItem['order_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $order['order_content'] !!}</span>
                            
                            @if (!empty($order['sub_order_model']))
                                @foreach ($order['sub_order_model'] as $subOrder)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="order-no">
                                                <p>{{ $subOrder['sub_order_no'] }}</p>
                                            </td>
                                            <td class="order-title">
                                                <p>{!! $subOrder['sub_order_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($order['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($order['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['annexure']))   
                    <div style="text-align: start">
                        @foreach ($item['annexure'] as $annexure)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="annexure-no">
                                            <p>{{ $annexureItem['annexure_no'] }}</p>
                                        </td>
                                        <td class="annexure-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $annexureItem['annexure_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $annexure['annexure_content'] !!}</span>
                            
                            @if (!empty($annexure['sub_annexure_model']))
                                @foreach ($annexure['sub_annexure_model'] as $subAnnexure)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="annexure-no">
                                                <p>{{ $subAnnexure['sub_annexure_no'] }}</p>
                                            </td>
                                            <td class="annexure-title">
                                                <p>{!! $subAnnexure['sub_annexure_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($annexure['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($annexure['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['stschedule']))   
                    <div style="text-align: start">
                        @foreach ($item['stschedule'] as $stschedule)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="stschedule-no">
                                            <p>{{ $stscheduleItem['stschedule_no'] }}</p>
                                        </td>
                                        <td class="stschedule-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $stscheduleItem['stschedule_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $stschedule['stschedule_content'] !!}</span>
                            
                            @if (!empty($stschedule['sub_stschedule_model']))
                                @foreach ($stschedule['sub_stschedule_model'] as $subStschedule)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="stschedule-no">
                                                <p>{{ $subStschedule['sub_stschedule_no'] }}</p>
                                            </td>
                                            <td class="stschedule-title">
                                                <p>{!! $subStschedule['sub_stschedule_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($stschedule['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($stschedule['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
            @endif
            @if (isset($item['priliminary_id']))
                <div style="text-align: center">
                    <div style="text-transform: uppercase !important; font-size: 15px !important; margin-top: 0.4rem;">
                        {!! $item['priliminary_title'] !!}
                    </div>
                </div>
                @if (!empty($item['sections']))   
                    <div style="text-align: start">
                        @foreach ($item['sections'] as $section)
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="section-no">
                                            <p>{{ $section['section_no'] }}</p>
                                        </td>
                                        <td class="section-title">
                                            <p>{!! $section['section_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            <span>{!! $section['section_content'] !!}</span>
                            
                            @if (!empty($section['subsection_model']))
                                @foreach ($section['subsection_model'] as $subSection)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="section-no">
                                                <p>{{ $subSection['sub_section_no'] }}</p>
                                            </td>
                                            <td class="section-title">
                                                <p>{!! $subSection['sub_section_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            
                            @if (!empty($section['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($section['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">

                        @endforeach
                    </div>   
                @endif  
                @if (!empty($item['articles']))   
                    <div style="text-align: start">
                        @foreach ($item['articles'] as $article)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="article-no">
                                            <p>{{ $article['article_no'] }}</p>
                                        </td>
                                        <td class="article-title">
                                            <p>{!! $article['article_title'] !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $article['article_content'] !!}</span>
                            
                            @if (!empty($article['sub_article_model']))
                                @foreach ($article['sub_article_model'] as $subArticle)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="article-no">
                                                <p>{{ $subArticle['sub_article_no'] }}</p>
                                            </td>
                                            <td class="article-title">
                                                <p>{!! $subArticle['sub_article_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($article['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($article['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['rules']))   
                    <div style="text-align: start">
                        @foreach ($item['rules'] as $rule)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="rule-no">
                                            <p>{{ $rule['rule_no'] }}</p>
                                        </td>
                                        <td class="rule-title">
                                            <p>{!! $rule['rule_title'] !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $rule['rule_content'] !!}</span>
                            
                            @if (!empty($rule['subrule_model']))
                                @foreach ($rule['subrule_model'] as $subRule)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="rule-no">
                                                <p>{{ $subRule['sub_rule_no'] }}</p>
                                            </td>
                                            <td class="rule-title">
                                                <p>{!! $subRule['sub_rule_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($rule['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($rule['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['regulation']))   
                    <div style="text-align: start">
                        @foreach ($item['regulation'] as $regulation)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="regulation-no">
                                            <p>{{ $regulation['regulation_no'] }}</p>
                                        </td>
                                        <td class="regulation-title">
                                            <p>{!! $regulation['regulation_title'] !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $regulation['regulation_content'] !!}</span>
                            
                            @if (!empty($regulation['sub_regulation_model']))
                                @foreach ($regulation['sub_regulation_model'] as $subRegulation)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="regulation-no">
                                                <p>{{ $subRegulation['sub_regulation_no'] }}</p>
                                            </td>
                                            <td class="regulation-title">
                                                <p>{!! $subRegulation['sub_regulation_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($regulation['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($regulation['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['lists']))   
                    <div style="text-align: start">
                        @foreach ($item['lists'] as $list)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="list-no">
                                            <p>{{ $list['list_no'] }}</p>
                                        </td>
                                        <td class="list-title">
                                            <p>{!! $list['list_title'] !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $list['list_content'] !!}</span>
                            
                            @if (!empty($list['sub_list_model']))
                                @foreach ($list['sub_list_model'] as $subList)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="list-no">
                                                <p>{{ $subList['sub_list_no'] }}</p>
                                            </td>
                                            <td class="list-title">
                                                <p>{!! $subList['sub_list_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($list['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($list['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['part']))   
                    <div style="text-align: start">
                        @foreach ($item['part'] as $part)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="part-no">
                                            <p>{{ $partItem['part_no'] }}</p>
                                        </td>
                                        <td class="part-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $partItem['part_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $part['part_content'] !!}</span>
                            
                            @if (!empty($part['sub_part_model']))
                                @foreach ($part['sub_part_model'] as $subPart)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="appendices-no">
                                                <p>{{ $subPart['sub_part_no'] }}</p>
                                            </td>
                                            <td class="appendices-title">
                                                <p>{!! $subPart['sub_part_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($part['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($part['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['appendices']))   
                    <div style="text-align: start">
                        @foreach ($item['appendices'] as $appendices)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="appendices-no">
                                            <p>{{ $appendicesItem['appendices_no'] }}</p>
                                        </td>
                                        <td class="appendices-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $appendicesItem['appendices_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $appendices['appendices_content'] !!}</span>
                            
                            @if (!empty($appendices['sub_appendices_model']))
                                @foreach ($appendices['sub_appendices_model'] as $subAppendices)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="appendices-no">
                                                <p>{{ $subAppendices['sub_appendices_no'] }}</p>
                                            </td>
                                            <td class="appendices-title">
                                                <p>{!! $subAppendices['sub_appendices_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($appendices['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($appendices['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['order']))   
                    <div style="text-align: start">
                        @foreach ($item['order'] as $order)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="order-no">
                                            <p>{{ $orderItem['order_no'] }}</p>
                                        </td>
                                        <td class="order-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $orderItem['order_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $order['order_content'] !!}</span>
                            
                            @if (!empty($order['sub_order_model']))
                                @foreach ($order['sub_order_model'] as $subOrder)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="order-no">
                                                <p>{{ $subOrder['sub_order_no'] }}</p>
                                            </td>
                                            <td class="order-title">
                                                <p>{!! $subOrder['sub_order_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($order['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($order['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['annexure']))   
                    <div style="text-align: start">
                        @foreach ($item['annexure'] as $annexure)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="annexure-no">
                                            <p>{{ $annexureItem['annexure_no'] }}</p>
                                        </td>
                                        <td class="annexure-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $annexureItem['annexure_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $annexure['annexure_content'] !!}</span>
                            
                            @if (!empty($annexure['sub_annexure_model']))
                                @foreach ($annexure['sub_annexure_model'] as $subAnnexure)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="annexure-no">
                                                <p>{{ $subAnnexure['sub_annexure_no'] }}</p>
                                            </td>
                                            <td class="annexure-title">
                                                <p>{!! $subAnnexure['sub_annexure_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($annexure['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($annexure['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['stschedule']))   
                    <div style="text-align: start">
                        @foreach ($item['stschedule'] as $stschedule)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="stschedule-no">
                                            <p>{{ $stscheduleItem['stschedule_no'] }}</p>
                                        </td>
                                        <td class="stschedule-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $stscheduleItem['stschedule_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $stschedule['stschedule_content'] !!}</span>
                            
                            @if (!empty($stschedule['sub_stschedule_model']))
                                @foreach ($stschedule['sub_stschedule_model'] as $subStschedule)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="stschedule-no">
                                                <p>{{ $subStschedule['sub_stschedule_no'] }}</p>
                                            </td>
                                            <td class="stschedule-title">
                                                <p>{!! $subStschedule['sub_stschedule_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($stschedule['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($stschedule['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif          
            @endif
            @if (isset($item['schedule_id']))
                <div style="text-align: center">
                    <div style="text-transform: uppercase !important; font-size: 15px !important; margin-top: 0.4rem;">
                        {!! $item['schedule_title'] !!}
                    </div>
                </div>
                @if (!empty($item['sections']))   
                    <div style="text-align: start">
                        @foreach ($item['sections'] as $section)
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="section-no">
                                            <p>{{ $section['section_no'] }}</p>
                                        </td>
                                        <td class="section-title">
                                            <p>{!! $section['section_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            <span>{!! $section['section_content'] !!}</span>
                            
                            @if (!empty($section['subsection_model']))
                                @foreach ($section['subsection_model'] as $subSection)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="article-no">
                                                <p>{{ $subSection['sub_section_no'] }}</p>
                                            </td>
                                            <td class="article-title">
                                                <p>{!! $subSection['sub_section_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                             
                            @if (!empty($section['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($section['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">

                        @endforeach
                    </div>
                   
                @endif  
                @if (!empty($item['articles']))   
                    <div style="text-align: start">
                        @foreach ($item['articles'] as $article)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="article-no">
                                            <p>{{ $article['article_no'] }}</p>
                                        </td>
                                        <td class="article-title">
                                            <p>{!! $article['article_title'] !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $article['article_content'] !!}</span>
                            
                            @if (!empty($article['sub_article_model']))
                                @foreach ($article['sub_article_model'] as $subArticle)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="article-no">
                                                <p>{{ $subArticle['sub_article_no'] }}</p>
                                            </td>
                                            <td class="article-title">
                                                <p>{!! $subArticle['sub_article_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($article['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($article['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['rules']))   
                    <div style="text-align: start">
                        @foreach ($item['rules'] as $rule)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="rule-no">
                                            <p>{{ $rule['rule_no'] }}</p>
                                        </td>
                                        <td class="rule-title">
                                            <p>{!! $rule['rule_title'] !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $rule['rule_content'] !!}</span>
                            
                            @if (!empty($rule['subrule_model']))
                                @foreach ($rule['subrule_model'] as $subRule)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="rule-no">
                                                <p>{{ $subRule['sub_rule_no'] }}</p>
                                            </td>
                                            <td class="rule-title">
                                                <p>{!! $subRule['sub_rule_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($rule['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($rule['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['regulation']))   
                    <div style="text-align: start">
                        @foreach ($item['regulation'] as $regulation)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="regulation-no">
                                            <p>{{ $regulation['regulation_no'] }}</p>
                                        </td>
                                        <td class="regulation-title">
                                            <p>{!! $regulation['regulation_title'] !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $regulation['regulation_content'] !!}</span>
                            
                            @if (!empty($regulation['sub_regulation_model']))
                                @foreach ($regulation['sub_regulation_model'] as $subRegulation)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="regulation-no">
                                                <p>{{ $subRegulation['sub_regulation_no'] }}</p>
                                            </td>
                                            <td class="regulation-title">
                                                <p>{!! $subRegulation['sub_regulation_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($regulation['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($regulation['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['lists']))   
                    <div style="text-align: start">
                        @foreach ($item['lists'] as $list)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="list-no">
                                            <p>{{ $list['list_no'] }}</p>
                                        </td>
                                        <td class="list-title">
                                            <p>{!! $list['list_title'] !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $list['list_content'] !!}</span>
                            
                            @if (!empty($list['sub_list_model']))
                                @foreach ($list['sub_list_model'] as $subList)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="list-no">
                                                <p>{{ $subList['sub_list_no'] }}</p>
                                            </td>
                                            <td class="list-title">
                                                <p>{!! $subList['sub_list_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($list['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($list['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['part']))   
                    <div style="text-align: start">
                        @foreach ($item['part'] as $part)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="part-no">
                                            <p>{{ $partItem['part_no'] }}</p>
                                        </td>
                                        <td class="part-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $partItem['part_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $part['part_content'] !!}</span>
                            
                            @if (!empty($part['sub_part_model']))
                                @foreach ($part['sub_part_model'] as $subPart)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="appendices-no">
                                                <p>{{ $subPart['sub_part_no'] }}</p>
                                            </td>
                                            <td class="appendices-title">
                                                <p>{!! $subPart['sub_part_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($part['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($part['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['appendices']))   
                    <div style="text-align: start">
                        @foreach ($item['appendices'] as $appendices)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="appendices-no">
                                            <p>{{ $appendicesItem['appendices_no'] }}</p>
                                        </td>
                                        <td class="appendices-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $appendicesItem['appendices_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $appendices['appendices_content'] !!}</span>
                            
                            @if (!empty($appendices['sub_appendices_model']))
                                @foreach ($appendices['sub_appendices_model'] as $subAppendices)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="appendices-no">
                                                <p>{{ $subAppendices['sub_appendices_no'] }}</p>
                                            </td>
                                            <td class="appendices-title">
                                                <p>{!! $subAppendices['sub_appendices_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($appendices['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($appendices['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['order']))   
                    <div style="text-align: start">
                        @foreach ($item['order'] as $order)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="order-no">
                                            <p>{{ $orderItem['order_no'] }}</p>
                                        </td>
                                        <td class="order-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $orderItem['order_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $order['order_content'] !!}</span>
                            
                            @if (!empty($order['sub_order_model']))
                                @foreach ($order['sub_order_model'] as $subOrder)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="order-no">
                                                <p>{{ $subOrder['sub_order_no'] }}</p>
                                            </td>
                                            <td class="order-title">
                                                <p>{!! $subOrder['sub_order_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($order['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($order['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['annexure']))   
                    <div style="text-align: start">
                        @foreach ($item['annexure'] as $annexure)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="annexure-no">
                                            <p>{{ $annexureItem['annexure_no'] }}</p>
                                        </td>
                                        <td class="annexure-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $annexureItem['annexure_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $annexure['annexure_content'] !!}</span>
                            
                            @if (!empty($annexure['sub_annexure_model']))
                                @foreach ($annexure['sub_annexure_model'] as $subAnnexure)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="annexure-no">
                                                <p>{{ $subAnnexure['sub_annexure_no'] }}</p>
                                            </td>
                                            <td class="annexure-title">
                                                <p>{!! $subAnnexure['sub_annexure_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($annexure['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($annexure['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['stschedule']))   
                    <div style="text-align: start">
                        @foreach ($item['stschedule'] as $stschedule)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="stschedule-no">
                                            <p>{{ $stscheduleItem['stschedule_no'] }}</p>
                                        </td>
                                        <td class="stschedule-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $stscheduleItem['stschedule_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $stschedule['stschedule_content'] !!}</span>
                            
                            @if (!empty($stschedule['sub_stschedule_model']))
                                @foreach ($stschedule['sub_stschedule_model'] as $subStschedule)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="stschedule-no">
                                                <p>{{ $subStschedule['sub_stschedule_no'] }}</p>
                                            </td>
                                            <td class="stschedule-title">
                                                <p>{!! $subStschedule['sub_stschedule_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($stschedule['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($stschedule['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
            @endif
            @if (isset($item['appendix_id']))
                <div style="text-align: center">
                    <div style="text-transform: uppercase !important; font-size: 15px !important; margin-top: 0.4rem;">
                        {!! $item['appendix_title'] !!}
                    </div>
                </div>
                @if (!empty($item['sections']))   
                    <div style="text-align: start">
                        @foreach ($item['sections'] as $section)
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="section-no">
                                            <p>{{ $section['section_no'] }}</p>
                                        </td>
                                        <td class="section-title">
                                            <p>{!! $section['section_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            <span>{!! $section['section_content'] !!}</span>
                            
                            @if (!empty($section['subsection_model']))
                                @foreach ($section['subsection_model'] as $subSection)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="article-no">
                                                <p>{{ $subSection['sub_section_no'] }}</p>
                                            </td>
                                            <td class="article-title">
                                                <p>{!! $subSection['sub_section_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                             
                            @if (!empty($section['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($section['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">

                        @endforeach
                    </div>
                   
                @endif  
                @if (!empty($item['articles']))   
                    <div style="text-align: start">
                        @foreach ($item['articles'] as $article)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="article-no">
                                            <p>{{ $article['article_no'] }}</p>
                                        </td>
                                        <td class="article-title">
                                            <p>{!! $article['article_title'] !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $article['article_content'] !!}</span>
                            
                            @if (!empty($article['sub_article_model']))
                                @foreach ($article['sub_article_model'] as $subArticle)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="article-no">
                                                <p>{{ $subArticle['sub_article_no'] }}</p>
                                            </td>
                                            <td class="article-title">
                                                <p>{!! $subArticle['sub_article_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($article['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($article['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['rules']))   
                    <div style="text-align: start">
                        @foreach ($item['rules'] as $rule)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="rule-no">
                                            <p>{{ $rule['rule_no'] }}</p>
                                        </td>
                                        <td class="rule-title">
                                            <p>{!! $rule['rule_title'] !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $rule['rule_content'] !!}</span>
                            
                            @if (!empty($rule['subrule_model']))
                                @foreach ($rule['subrule_model'] as $subRule)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="rule-no">
                                                <p>{{ $subRule['sub_rule_no'] }}</p>
                                            </td>
                                            <td class="rule-title">
                                                <p>{!! $subRule['sub_rule_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($rule['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($rule['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['regulation']))   
                    <div style="text-align: start">
                        @foreach ($item['regulation'] as $regulation)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="regulation-no">
                                            <p>{{ $regulation['regulation_no'] }}</p>
                                        </td>
                                        <td class="regulation-title">
                                            <p>{!! $regulation['regulation_title'] !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $regulation['regulation_content'] !!}</span>
                            
                            @if (!empty($regulation['sub_regulation_model']))
                                @foreach ($regulation['sub_regulation_model'] as $subRegulation)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="regulation-no">
                                                <p>{{ $subRegulation['sub_regulation_no'] }}</p>
                                            </td>
                                            <td class="regulation-title">
                                                <p>{!! $subRegulation['sub_regulation_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($regulation['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($regulation['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['lists']))   
                    <div style="text-align: start">
                        @foreach ($item['lists'] as $list)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="list-no">
                                            <p>{{ $list['list_no'] }}</p>
                                        </td>
                                        <td class="list-title">
                                            <p>{!! $list['list_title'] !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $list['list_content'] !!}</span>
                            
                            @if (!empty($list['sub_list_model']))
                                @foreach ($list['sub_list_model'] as $subList)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subList['sub_list_no'] }}</p>
                                            </td>
                                            <td>
                                                <p>{!! $subList['sub_list_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($list['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($list['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['part']))   
                    <div style="text-align: start">
                        @foreach ($item['part'] as $part)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="part-no">
                                            <p>{{ $partItem['part_no'] }}</p>
                                        </td>
                                        <td class="part-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $partItem['part_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $part['part_content'] !!}</span>
                            
                            @if (!empty($part['sub_part_model']))
                                @foreach ($part['sub_part_model'] as $subPart)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="appendices-no">
                                                <p>{{ $subPart['sub_part_no'] }}</p>
                                            </td>
                                            <td class="appendices-title">
                                                <p>{!! $subPart['sub_part_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($part['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($part['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['appendices']))   
                    <div style="text-align: start">
                        @foreach ($item['appendices'] as $appendices)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="appendices-no">
                                            <p>{{ $appendicesItem['appendices_no'] }}</p>
                                        </td>
                                        <td class="appendices-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $appendicesItem['appendices_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $appendices['appendices_content'] !!}</span>
                            
                            @if (!empty($appendices['sub_appendices_model']))
                                @foreach ($appendices['sub_appendices_model'] as $subAppendices)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="appendices-no">
                                                <p>{{ $subAppendices['sub_appendices_no'] }}</p>
                                            </td>
                                            <td class="appendices-title">
                                                <p>{!! $subAppendices['sub_appendices_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($appendices['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($appendices['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['order']))   
                    <div style="text-align: start">
                        @foreach ($item['order'] as $order)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="order-no">
                                            <p>{{ $orderItem['order_no'] }}</p>
                                        </td>
                                        <td class="order-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $orderItem['order_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $order['order_content'] !!}</span>
                            
                            @if (!empty($order['sub_order_model']))
                                @foreach ($order['sub_order_model'] as $subOrder)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="order-no">
                                                <p>{{ $subOrder['sub_order_no'] }}</p>
                                            </td>
                                            <td class="order-title">
                                                <p>{!! $subOrder['sub_order_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($order['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($order['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['annexure']))   
                    <div style="text-align: start">
                        @foreach ($item['annexure'] as $annexure)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="annexure-no">
                                            <p>{{ $annexureItem['annexure_no'] }}</p>
                                        </td>
                                        <td class="annexure-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $annexureItem['annexure_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $annexure['annexure_content'] !!}</span>
                            
                            @if (!empty($annexure['sub_annexure_model']))
                                @foreach ($annexure['sub_annexure_model'] as $subAnnexure)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="annexure-no">
                                                <p>{{ $subAnnexure['sub_annexure_no'] }}</p>
                                            </td>
                                            <td class="annexure-title">
                                                <p>{!! $subAnnexure['sub_annexure_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($annexure['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($annexure['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['stschedule']))   
                    <div style="text-align: start">
                        @foreach ($item['stschedule'] as $stschedule)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="stschedule-no">
                                            <p>{{ $stscheduleItem['stschedule_no'] }}</p>
                                        </td>
                                        <td class="stschedule-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $stscheduleItem['stschedule_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $stschedule['stschedule_content'] !!}</span>
                            
                            @if (!empty($stschedule['sub_stschedule_model']))
                                @foreach ($stschedule['sub_stschedule_model'] as $subStschedule)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="stschedule-no">
                                                <p>{{ $subStschedule['sub_stschedule_no'] }}</p>
                                            </td>
                                            <td class="stschedule-title">
                                                <p>{!! $subStschedule['sub_stschedule_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($stschedule['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($stschedule['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
            @endif
           @if (isset($item['main_order_id']))
                <div style="text-align: center">
                    <div style="text-transform: uppercase !important; font-size: 15px !important; margin-top: 0.4rem;">
                        {!! $item['main_order_title'] !!}
                    </div>
                </div>
                @if (!empty($item['sections']))   
                    <div style="text-align: start">
                        @foreach ($item['sections'] as $section)
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="section-no">
                                            <p>{{ $section['section_no'] }}</p>
                                        </td>
                                        <td class="section-title">
                                            <p>{!! $section['section_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            <span>{!! $section['section_content'] !!}</span>
                            
                            @if (!empty($section['subsection_model']))
                                @foreach ($section['subsection_model'] as $subSection)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="article-no">
                                                <p>{{ $subSection['sub_section_no'] }}</p>
                                            </td>
                                            <td class="article-title">
                                                <p>{!! $subSection['sub_section_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            
                            @if (!empty($section['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($section['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">

                        @endforeach
                    </div>   
                @endif  
                @if (!empty($item['articles']))   
                    <div style="text-align: start">
                        @foreach ($item['articles'] as $article)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="article-no">
                                            <p>{{ $article['article_no'] }}</p>
                                        </td>
                                        <td class="article-title">
                                            <p>{!! $article['article_title'] !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $article['article_content'] !!}</span>
                            
                            @if (!empty($article['sub_article_model']))
                                @foreach ($article['sub_article_model'] as $subArticle)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="article-no">
                                                <p>{{ $subArticle['sub_article_no'] }}</p>
                                            </td>
                                            <td class="article-title">
                                                <p>{!! $subArticle['sub_article_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($article['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($article['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['rules']))   
                    <div style="text-align: start">
                        @foreach ($item['rules'] as $rule)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="rule-no">
                                            <p>{{ $rule['rule_no'] }}</p>
                                        </td>
                                        <td class="rule-title">
                                            <p>{!! $rule['rule_title'] !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $rule['rule_content'] !!}</span>
                            
                            @if (!empty($rule['subrule_model']))
                                @foreach ($rule['subrule_model'] as $subRule)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="rule-no">
                                                <p>{{ $subRule['sub_rule_no'] }}</p>
                                            </td>
                                            <td class="rule-title">
                                                <p>{!! $subRule['sub_rule_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($rule['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($rule['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['regulation']))   
                    <div style="text-align: start">
                        @foreach ($item['regulation'] as $regulation)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="regulation-no">
                                            <p>{{ $regulation['regulation_no'] }}</p>
                                        </td>
                                        <td class="regulation-title">
                                            <p>{!! $regulation['regulation_title'] !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $regulation['regulation_content'] !!}</span>
                            
                            @if (!empty($regulation['sub_regulation_model']))
                                @foreach ($regulation['sub_regulation_model'] as $subRegulation)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="regulation-no">
                                                <p>{{ $subRegulation['sub_regulation_no'] }}</p>
                                            </td>
                                            <td class="regulation-title">
                                                <p>{!! $subRegulation['sub_regulation_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($regulation['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($regulation['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['lists']))   
                    <div style="text-align: start">
                        @foreach ($item['lists'] as $list)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="list-no">
                                            <p>{{ $list['list_no'] }}</p>
                                        </td>
                                        <td class="list-title">
                                            <p>{!! $list['list_title'] !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $list['list_content'] !!}</span>
                            
                            @if (!empty($list['sub_list_model']))
                                @foreach ($list['sub_list_model'] as $subList)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="list-no">
                                                <p>{{ $subList['sub_list_no'] }}</p>
                                            </td>
                                            <td class="list-title">
                                                <p>{!! $subList['sub_list_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($list['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($list['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['part']))   
                    <div style="text-align: start">
                        @foreach ($item['part'] as $part)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="part-no">
                                            <p>{{ $partItem['part_no'] }}</p>
                                        </td>
                                        <td class="part-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $partItem['part_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $part['part_content'] !!}</span>
                            
                            @if (!empty($part['sub_part_model']))
                                @foreach ($part['sub_part_model'] as $subPart)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="appendices-no">
                                                <p>{{ $subPart['sub_part_no'] }}</p>
                                            </td>
                                            <td class="appendices-title">
                                                <p>{!! $subPart['sub_part_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($part['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($part['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['appendices']))   
                    <div style="text-align: start">
                        @foreach ($item['appendices'] as $appendices)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="appendices-no">
                                            <p>{{ $appendicesItem['appendices_no'] }}</p>
                                        </td>
                                        <td class="appendices-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $appendicesItem['appendices_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $appendices['appendices_content'] !!}</span>
                            
                            @if (!empty($appendices['sub_appendices_model']))
                                @foreach ($appendices['sub_appendices_model'] as $subAppendices)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="appendices-no">
                                                <p>{{ $subAppendices['sub_appendices_no'] }}</p>
                                            </td>
                                            <td class="appendices-title">
                                                <p>{!! $subAppendices['sub_appendices_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($appendices['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($appendices['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['order']))   
                    <div style="text-align: start">
                        @foreach ($item['order'] as $order)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="order-no">
                                            <p>{{ $orderItem['order_no'] }}</p>
                                        </td>
                                        <td class="order-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $orderItem['order_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $order['order_content'] !!}</span>
                            
                            @if (!empty($order['sub_order_model']))
                                @foreach ($order['sub_order_model'] as $subOrder)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="order-no">
                                                <p>{{ $subOrder['sub_order_no'] }}</p>
                                            </td>
                                            <td class="order-title">
                                                <p>{!! $subOrder['sub_order_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($order['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($order['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['annexure']))   
                    <div style="text-align: start">
                        @foreach ($item['annexure'] as $annexure)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="annexure-no">
                                            <p>{{ $annexureItem['annexure_no'] }}</p>
                                        </td>
                                        <td class="annexure-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $annexureItem['annexure_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $annexure['annexure_content'] !!}</span>
                            
                            @if (!empty($annexure['sub_annexure_model']))
                                @foreach ($annexure['sub_annexure_model'] as $subAnnexure)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="annexure-no">
                                                <p>{{ $subAnnexure['sub_annexure_no'] }}</p>
                                            </td>
                                            <td class="annexure-title">
                                                <p>{!! $subAnnexure['sub_annexure_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($annexure['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($annexure['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['stschedule']))   
                    <div style="text-align: start">
                        @foreach ($item['stschedule'] as $stschedule)
                            <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td class="stschedule-no">
                                            <p>{{ $stscheduleItem['stschedule_no'] }}</p>
                                        </td>
                                        <td class="stschedule-title">
                                            <p>{!! preg_replace('/[0-9\[\]\.]/', '', $stscheduleItem['stschedule_title']) !!}</p>
                                        </td>
                                    </tr>
                            </table>
                            <span>{!! $stschedule['stschedule_content'] !!}</span>
                            
                            @if (!empty($stschedule['sub_stschedule_model']))
                                @foreach ($stschedule['sub_stschedule_model'] as $subStschedule)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td class="stschedule-no">
                                                <p>{{ $subStschedule['sub_stschedule_no'] }}</p>
                                            </td>
                                            <td class="stschedule-title">
                                                <p>{!! $subStschedule['sub_stschedule_content'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                            @if (!empty($stschedule['footnote_model']))
                                <hr style="width: 10% !important;margin: 10px auto !important;">
                                @foreach ($stschedule['footnote_model'] as $footnoteModel)
                                    <em class="footnote" style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">{!! $footnoteModel['footnote_content'] !!}</em>
                                @endforeach
                            @endif
                            <hr style="width: 10% !important;margin: 10px auto !important;">
                        @endforeach
                    </div>
                @endif
           @endif
    @endforeach 
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofNlq+M5q9dOB6yS/MbGCD8Fk8MIdjT7+q" crossorigin="anonymous">
    </script>


</body>

</html>
