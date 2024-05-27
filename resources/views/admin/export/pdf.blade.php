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
            size: A4; 
            margin: 4cm; 
            margin-top: 1cm !important; 
            margin-left: 1cm !important; 
            margin-right: 1cm !important; 
            
        }
        body, html {
            margin: 0;
            padding: 0;
            line-height: 1.2;
        }


        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table p {
            margin: 0;
        }

        .container {
            padding: 50px !important;
        }

        .section {
            margin-top: 20px;
        }
        
     .section-no,
        .article-no,
        .stschedule-no,
        .list-no,
        .regulation-no,
        .rule-no,
        .annexure-no,
        .order-no,
        .appendices-no,
        .part-no, .section-title,
        .article-title,
        .stschedule-title,
        .list-title,
        .regulation-title,
        .rule-title,
        .annexure-title,
        .order-title,
        .appendices-title,
        .part-title {
        width: 50%; 
        box-sizing: border-box; 
        padding: 5px; 
    }

    .section-title,
        .article-title,
        .stschedule-title,
        .list-title,
        .regulation-title,
        .rule-title,
        .annexure-title,
        .order-title,
        .appendices-title,
        .part-title {
        overflow-wrap: break-word; 
    }
   
 .section-container:after ,
 .article-container:after,
 .stschedule-container:after,
 .list-container:after,
 .regulation-container:after,
 .rule-container:after,
 .annexure-container:after,
 .order-container:after,
 .appendices-container:after,
 .part-container:after
 {
        content: "";
        display: table;
        clear: both;
    }
  
    .section-item,
        .article-item,
        .stschedule-item,
        .list-item,
        .regulation-item,
        .rule-item,
        .annexure-item,
        .order-item,
        .appendices-item,
        .part-item {
        font-size: 12px;
        margin-right: 20px;
        float:left;    
    }

    .sub-section-no , .sub-article-no,
        .sub-stschedule-no,
        .sub-list-no,
        .sub-regulation-no,
        .sub-rule-no,
        .sub-annexure-no,
        .sub-order-no,
        .sub-appendices-no,
        .sub-part-no {
        font-size: 13px; 
        font-weight: bold; 
        width: 3%;
         
    }
    .sub-section-content , , .sub-article-content,
        .sub-stschedule-content,
        .sub-list-content,
        .sub-regulation-content,
        .sub-rule-content,
        .sub-annexure-content,
        .sub-order-content,
        .sub-appendices-content,
        .sub-part-content {
        width: 97%; 
        overflow-wrap: break-word; 
        text-align: justify; 
        page-break-after: auto;
    }
    
    .page-break {
        page-break-after: always;
    }
 
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
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="section-no">
                                    <p>{{ $sectionItem['section_no'] }}</p>
                                </td>
                                <td class="section-title">
                                    <p>{!! $sectionItem['section_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['articles']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['articles'] as $articleItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="article-no">
                                    <p>{{ $articleItem['article_no'] }}</p>
                                </td>
                                <td class="article-title">
                                    <p>{!! $articleItem['article_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['rules']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['rules'] as $ruleItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="rule-no">
                                    <p>{{ $ruleItem['rule_no'] }}</p>
                                </td>
                                <td class="rule-title">
                                    <p>{!! $ruleItem['rule_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                        
                    @endforeach
                </div>
            @endif
            @if (!empty($item['regulation']))
                    <div style="text-align: start; margin-top: 0.4rem;">
                        @foreach ($item['regulation'] as $regulationItem)
                            <table style="width: 100%;font-size: 15px !important;">
                                <tr>
                                    <td class="regulation-no">
                                        <p>{{ $regulationItem['regulation_no'] }}</p>
                                    </td>
                                    <td class="regulation-title">
                                        <p>{!! $regulationItem['regulation_title'] !!}</p>
                                    </td>
                                </tr>
                            </table>
                            
                        @endforeach
                    </div>
            @endif
            @if (!empty($item['lists']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['lists'] as $listItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="list-no">
                                    <p>{{ $listItem['list_no'] }}</p>
                                </td>
                                <td class="list-title">
                                    <p>{!! $listItem['list_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['part']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['part'] as $partItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="part-no">
                                    <p>{{ $partItem['part_no'] }}</p>
                                </td>
                                <td class="part-title">
                                    <p>{!!  $partItem['part_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                        
                    @endforeach
                </div>
            @endif
            @if (!empty($item['appendices']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['appendices'] as $appendicesItem)
                        <table style="font-size: 15px !important;width: 100%;">
                            <tr>
                                <td class="appendices-no">
                                    <p>{{ $appendicesItem['appendices_no'] }}</p>
                                </td>
                                <td class="appendices-title">
                                    <p>{!! $appendicesItem['appendices_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                        
                    @endforeach
                </div>
            @endif
            @if (!empty($item['order']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['order'] as $orderItem)
                        <table style="font-size: 15px !important;width: 100%;">
                            <tr>
                                <td class="order-no">
                                    <p>{{ $orderItem['order_no'] }}</p>
                                </td>
                                <td class="order-title">
                                    <p>{!! $orderItem['order_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['annexure']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['annexure'] as $annexureItem)
                        <table style="font-size: 15px !important;width: 100%;">
                            <tr>
                                <td class="annexure-no">
                                    <p>{{ $annexureItem['annexure_no'] }}</p>
                                </td>
                                <td class="annexure-title">
                                    <p>{!! $annexureItem['annexure_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['stschedule']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['stschedule'] as $stscheduleItem)
                        <table style="font-size: 15px !important;width: 100%;">
                            <tr>
                                <td class="stschedule-no">
                                    <p>{{ $stscheduleItem['stschedule_no'] }}</p>
                                </td>
                                <td class="stschedule-title">
                                    <p>{!!  $stscheduleItem['stschedule_title'] !!}</p>
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
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="section-no">
                                    <p>{{ $sectionItem['section_no'] }}</p>
                                </td>
                                <td class="section-title">
                                    <p>{!! $sectionItem['section_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['articles']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['articles'] as $articleItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="article-no">
                                    <p>{{ $articleItem['article_no'] }}</p>
                                </td>
                                <td class="article-title">
                                    <p>{!! $articleItem['article_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['rules']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['rules'] as $ruleItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="rule-no">
                                    <p>{{ $ruleItem['rule_no'] }}</p>
                                </td>
                                <td class="rule-title">
                                    <p>{!! $ruleItem['rule_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if(!empty($item['regulation']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['regulation'] as $regulationItem)
                        <table style="font-size: 15px !important;width: 100%;">
                            <tr>
                                <td class="regulation-no">
                                    <p>{{ $regulationItem['regulation_no'] }}</p>
                                </td>
                                <td class="regulation-title">
                                    <p>{!! $regulationItem['regulation_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['lists']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['lists'] as $listItem)
                        <table style="font-size: 15px !important;width: 100%;">
                            <tr>
                                <td class="list-no">
                                    <p>{{ $listItem['list_no'] }}</p>
                                </td>
                                <td class="list-title">
                                    <p>{!! $listItem['list_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['part']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['part'] as $partItem)
                        <table style="font-size: 15px !important;width: 100%;">
                            <tr>
                                <td class="part-no">
                                    <p>{{ $partItem['part_no'] }}</p>
                                </td>
                                <td class="part-title">
                                    <p>{!!  $partItem['part_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['appendices']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['appendices'] as $appendicesItem)
                        <table style="font-size: 15px !important;width: 100%;">
                            <tr>
                                <td class="appendices-no">
                                    <p>{{ $appendicesItem['appendices_no'] }}</p>
                                </td>
                                <td class="appendices-title">
                                    <p>{!! $appendicesItem['appendices_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['order']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['order'] as $orderItem)
                        <table style="font-size: 15px !important;width: 100%;">
                            <tr>
                                <td class="order-no">
                                    <p>{{ $orderItem['order_no'] }}</p>
                                </td>
                                <td class="order-title">
                                    <p>{!! $orderItem['order_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['annexure']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['annexure'] as $annexureItem)
                        <table style="font-size: 15px !important;width: 100%;">
                            <tr>
                                <td class="annexure-no">
                                    <p>{{ $annexureItem['annexure_no'] }}</p>
                                </td>
                                <td class="annexure-title">
                                    <p>{!! $annexureItem['annexure_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['stschedule']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['stschedule'] as $stscheduleItem)
                        <table style="font-size: 15px !important;width: 100%;">
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
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="section-no">
                                    <p>{{ $sectionItem['section_no'] }}</p>
                                </td>
                                <td class="section-title">
                                    <p>{!! $sectionItem['section_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['articles']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['articles'] as $articleItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="article-no">
                                    <p>{{ $articleItem['article_no'] }}</p>
                                </td>
                                <td class="article-title">
                                    <p>{!! $articleItem['article_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['rules']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['rules'] as $ruleItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="rule-no">
                                    <p>{{ $ruleItem['rule_no'] }}</p>
                                </td>
                                <td class="rule-title">
                                    <p>{!! $ruleItem['rule_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['regulation']))
                    <div style="text-align: start; margin-top: 0.4rem;">
                        @foreach ($item['regulation'] as $regulationItem)
                            <table style="width: 100%;font-size: 15px !important;">
                                <tr>
                                    <td class="regulation-no">
                                        <p>{{ $regulationItem['regulation_no'] }}</p>
                                    </td>
                                    <td class="regulation-title">
                                        <p>{!! $regulationItem['regulation_title'] !!}</p>
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </div>
            @endif
            @if (!empty($item['lists']))
                    <div style="text-align: start; margin-top: 0.4rem;">
                        @foreach ($item['lists'] as $listItem)
                            <table style="width: 100%;font-size: 15px !important;">
                                <tr>
                                    <td class="list-no">
                                        <p>{{ $listItem['list_no'] }}</p>
                                    </td>
                                    <td class="list-title">
                                        <p>{!! $listItem['list_title'] !!}</p>
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </div>
            @endif
            @if (!empty($item['part']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['part'] as $partItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="part-no">
                                    <p>{{ $partItem['part_no'] }}</p>
                                </td>
                                <td class="part-title">
                                    <p>{!!  $partItem['part_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['appendices']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['appendices'] as $appendicesItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="appendices-no">
                                    <p>{{ $appendicesItem['appendices_no'] }}</p>
                                </td>
                                <td class="appendices-title">
                                    <p>{!! $appendicesItem['appendices_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['order']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['order'] as $orderItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="order-no">
                                    <p>{{ $orderItem['order_no'] }}</p>
                                </td>
                                <td class="order-title">
                                    <p>{!! $orderItem['order_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['annexure']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['annexure'] as $annexureItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="annexure-no">
                                    <p>{{ $annexureItem['annexure_no'] }}</p>
                                </td>
                                <td class="annexure-title">
                                    <p>{!! $annexureItem['annexure_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['stschedule']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['stschedule'] as $stscheduleItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="stschedule-no">
                                    <p>{{ $stscheduleItem['stschedule_no'] }}</p>
                                </td>
                                <td class="stschedule-title">
                                    <p>{!!  $stscheduleItem['stschedule_title'] !!}</p>
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
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="section-no">
                                    <p>{{ $sectionItem['section_no'] }}</p>
                                </td>
                                <td class="section-title">
                                    <p>{!! $sectionItem['section_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['articles']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['articles'] as $articleItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="article-no">
                                    <p>{{ $articleItem['article_no'] }}</p>
                                </td>
                                <td class="article-title">
                                    <p>{!! $articleItem['article_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['rules']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['rules'] as $ruleItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="rule-no">
                                    <p>{{ $ruleItem['rule_no'] }}</p>
                                </td>
                                <td class="rule-title">
                                    <p>{!! $ruleItem['rule_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['regulation']))
                    <div style="text-align: start; margin-top: 0.4rem;">
                        @foreach ($item['regulation'] as $regulationItem)
                            <table style="width: 100%;font-size: 15px !important;">
                                <tr>
                                    <td class="regulation-no">
                                        <p>{{ $regulationItem['regulation_no'] }}</p>
                                    </td>
                                    <td class="regulation-title">
                                        <p>{!! $regulationItem['regulation_title'] !!}</p>
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </div>
            @endif
            @if (!empty($item['lists']))
                    <div style="text-align: start; margin-top: 0.4rem;">
                        @foreach ($item['lists'] as $listItem)
                            <table style="width: 100%;font-size: 15px !important;">
                                <tr>
                                    <td class="list-no">
                                        <p>{{ $listItem['list_no'] }}</p>
                                    </td>
                                    <td class="list-title">
                                        <p>{!! $listItem['list_title'] !!}</p>
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </div>
            @endif
            @if (!empty($item['part']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['part'] as $partItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="part-no">
                                    <p>{{ $partItem['part_no'] }}</p>
                                </td>
                                <td class="part-title">
                                    <p>{!!  $partItem['part_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['appendices']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['appendices'] as $appendicesItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="appendices-no">
                                    <p>{{ $appendicesItem['appendices_no'] }}</p>
                                </td>
                                <td class="appendices-title">
                                    <p>{!! $appendicesItem['appendices_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['order']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['order'] as $orderItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="order-no">
                                    <p>{{ $orderItem['order_no'] }}</p>
                                </td>
                                <td class="order-title">
                                    <p>{!! $orderItem['order_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['annexure']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['annexure'] as $annexureItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="annexure-no">
                                    <p>{{ $annexureItem['annexure_no'] }}</p>
                                </td>
                                <td class="annexure-title">
                                    <p>{!! $annexureItem['annexure_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['stschedule']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['stschedule'] as $stscheduleItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="stschedule-no">
                                    <p>{{ $stscheduleItem['stschedule_no'] }}</p>
                                </td>
                                <td class="stschedule-title">
                                    <p>{!!  $stscheduleItem['stschedule_title'] !!}</p>
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
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="section-no">
                                    <p>{{ $sectionItem['section_no'] }}</p>
                                </td>
                                <td class="section-title">
                                    <p>{!! $sectionItem['section_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['articles']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['articles'] as $articleItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="article-no">
                                    <p>{{ $articleItem['article_no'] }}</p>
                                </td>
                                <td class="article-title">
                                    <p>{!! $articleItem['article_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['rules']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['rules'] as $ruleItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="rule-no">
                                    <p>{{ $ruleItem['rule_no'] }}</p>
                                </td>
                                <td class="rule-title">
                                    <p>{!! $ruleItem['rule_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['regulation']))
                    <div style="text-align: start; margin-top: 0.4rem;">
                        @foreach ($item['regulation'] as $regulationItem)
                            <table style="width: 100%;font-size: 15px !important;">
                                <tr>
                                    <td class="regulation-no">
                                        <p>{{ $regulationItem['regulation_no'] }}</p>
                                    </td>
                                    <td class="regulation-title">
                                        <p>{!! $regulationItem['regulation_title'] !!}</p>
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </div>
            @endif
            @if (!empty($item['lists']))
                    <div style="text-align: start; margin-top: 0.4rem;">
                        @foreach ($item['lists'] as $listItem)
                            <table style="width: 100%;font-size: 15px !important;">
                                <tr>
                                    <td class="list-no">
                                        <p>{{ $listItem['list_no'] }}</p>
                                    </td>
                                    <td class="list-title">
                                        <p>{!! $listItem['list_title'] !!}</p>
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </div>
            @endif
            @if (!empty($item['part']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['part'] as $partItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="part-no">
                                    <p>{{ $partItem['part_no'] }}</p>
                                </td>
                                <td class="part-title">
                                    <p>{!!  $partItem['part_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['appendices']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['appendices'] as $appendicesItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="appendices-no">
                                    <p>{{ $appendicesItem['appendices_no'] }}</p>
                                </td>
                                <td class="appendices-title">
                                    <p>{!! $appendicesItem['appendices_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['order']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['order'] as $orderItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="order-no">
                                    <p>{{ $orderItem['order_no'] }}</p>
                                </td>
                                <td class="order-title">
                                    <p>{!! $orderItem['order_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['annexure']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['annexure'] as $annexureItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="annexure-no">
                                    <p>{{ $annexureItem['annexure_no'] }}</p>
                                </td>
                                <td class="annexure-title">
                                    <p>{!! $annexureItem['annexure_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['stschedule']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['stschedule'] as $stscheduleItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="stschedule-no">
                                    <p>{{ $stscheduleItem['stschedule_no'] }}</p>
                                </td>
                                <td class="stschedule-title">
                                    <p>{!!  $stscheduleItem['stschedule_title'] !!}</p>
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
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="section-no">
                                    <p>{{ $sectionItem['section_no'] }}</p>
                                </td>
                                <td class="section-title">
                                    <p>{!! $sectionItem['section_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['articles']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['articles'] as $articleItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td style="vertical-align: baseline;">
                                    <p>{{ $articleItem['article_no'] }}</p>
                                </td>
                                <td>
                                    <p>{!! $articleItem['article_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['rules']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['rules'] as $ruleItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="rule-no">
                                    <p>{{ $ruleItem['rule_no'] }}</p>
                                </td>
                                <td class="rule-title">
                                    <p>{!! $ruleItem['rule_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['regulation']))
                    <div style="text-align: start; margin-top: 0.4rem;">
                        @foreach ($item['regulation'] as $regulationItem)
                            <table style="width: 100%;font-size: 15px !important;">
                                <tr>
                                    <td class="regulation-no">
                                        <p>{{ $regulationItem['regulation_no'] }}</p>
                                    </td>
                                    <td class="regulation-title">
                                        <p>{!! $regulationItem['regulation_title'] !!}</p>
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </div>
            @endif
            @if (!empty($item['lists']))
                    <div style="text-align: start; margin-top: 0.4rem;">
                        @foreach ($item['lists'] as $listItem)
                            <table style="width: 100%;font-size: 15px !important;">
                                <tr>
                                    <td class="list-no">
                                        <p>{{ $listItem['list_no'] }}</p>
                                    </td>
                                    <td class="list-title">
                                        <p>{!! $listItem['list_title'] !!}</p>
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </div>
            @endif
            @if (!empty($item['part']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['part'] as $partItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="part-no">
                                    <p>{{ $partItem['part_no'] }}</p>
                                </td>
                                <td class="part-title">
                                    <p>{!!  $partItem['part_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['appendices']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['appendices'] as $appendicesItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="appendices-no">
                                    <p>{{ $appendicesItem['appendices_no'] }}</p>
                                </td>
                                <td class="appendices-title">
                                    <p>{!! $appendicesItem['appendices_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['order']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['order'] as $orderItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="order-no">
                                    <p>{{ $orderItem['order_no'] }}</p>
                                </td>
                                <td class="order-title">
                                    <p>{!! $orderItem['order_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['annexure']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['annexure'] as $annexureItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="annexure-no">
                                    <p>{{ $annexureItem['annexure_no'] }}</p>
                                </td>
                                <td class="annexure-title">
                                    <p>{!! $annexureItem['annexure_title'] !!}</p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </div>
            @endif
            @if (!empty($item['stschedule']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['stschedule'] as $stscheduleItem)
                        <table style="width: 100%;font-size: 15px !important;">
                            <tr>
                                <td class="stschedule-no">
                                    <p>{{ $stscheduleItem['stschedule_no'] }}</p>
                                </td>
                                <td class="stschedule-title">
                                    <p>{!!  $stscheduleItem['stschedule_title'] !!}</p>
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
    <div style="font-size: 14px!important;">{!! $act->act_description !!}</div>

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

    <div id="chapterPage">
        @foreach($combinedItems as $item)
            @if (isset($item['chapter_id']))
                <div style="text-align: center">
                    <div style="text-transform: uppercase !important; font-size: 16px !important; margin-top: 0.4rem;">
                        {!! $item['chapter_title'] !!}
                    </div>
                </div>
               
                @if (!empty($item['sections']))   
                   <div style="text-align: left">
                        @foreach ($item['sections'] as $section)
                             <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{{ $section['section_no'] }}</p>
                                    </div>
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{!! $section['section_title'] !!}</p>
                                    </div>
                                </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important; font-size: 14px !important;">{!! $section['section_content'] !!}</span>
                            </div>
                    
                        @if (!empty($section['subsection_model']))
                            @foreach ($section['subsection_model'] as $subSection)
                                <div class="section-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                    <div class="section-item sub-section-no" style="font-size: 14px !important;">{{ $subSection['sub_section_no'] }}</div>
                                    <div class="section-item sub-section-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subSection['sub_section_content'] !!}</div>
                                </div>
                               
                            @endforeach
                        @endif
                    
                        @if (!empty($section['footnote_model']))
                        
                            @foreach ($section['footnote_model'] as $footnoteModel)
                                <div style="padding-left: 35px!important; font-size: 10px !important">
                                    <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                        {!! $footnoteModel['footnote_content'] !!}
                                    </em>
                                </div>
                            @endforeach
                        @endif
                    
                        <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif

                @if (!empty($item['articles']))   
                    <div style="text-align: left">
                        @foreach ($item['articles'] as $article)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{{ $article['article_no'] }}</p>
                                    </div>
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{!! $article['article_title'] !!}</p>
                                    </div>
                                </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $article['article_content'] !!}</span>
                            </div>
                            @if (!empty($article['sub_article_model']))
                                @foreach ($article['sub_article_model'] as $subArticle)
                                    
                                <div class="article-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                    <div class="article-item sub-article-no " style="font-size: 14px !important;" >{{ $subArticle['sub_article_no'] }}</div>
                                    <div class="article-item sub-article-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subArticle['sub_article_content'] !!}</div>
                                </div>
                                @endforeach
                            @endif
                            @if (!empty($article['footnote_model']))
                                @foreach ($article['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                        <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['rules']))   
                    <div style="text-align: left">
                        @foreach ($item['rules'] as $rule)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{{ $rule['rule_no'] }}</p>
                                    </div>
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{!! $rule['rule_title'] !!}</p>
                                    </div>
                                </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $rule['rule_content'] !!}</span>
                            </div>
                            
                            @if (!empty($rule['subrule_model']))
                                @foreach ($rule['subrule_model'] as $subRule)
                                    <div class="rule-container" style="margin-top: 0.4rem; padding-left:25px!important;">
                                        <div class="rule-item sub-rule-no " style="font-size: 14px !important;">{{ $subRule['sub_rule_no'] }}</div>
                                        <div class="rule-item sub-rule-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subRule['sub_rule_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($rule['footnote_model']))
                                @foreach ($rule['footnote_model'] as $footnoteModel)
                                     <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                        <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['regulation']))   
                    <div style="text-align: left">
                        @foreach ($item['regulation'] as $regulation)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{{ $regulation['regulation_no'] }}</p>
                                    </div>
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{!! $regulation['regulation_title'] !!}</p>
                                    </div>
                                </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $regulation['regulation_content'] !!}</span>
                            </div>
                            
                            @if (!empty($regulation['sub_regulation_model']))
                                @foreach ($regulation['sub_regulation_model'] as $subRegulation)
                                    <div class="regulation-container" style="margin-top: 0.4rem; padding-left:25px!important;">
                                        <div class="regulation-item sub-regulation-no " style="font-size: 14px !important;">{{ $subRegulation['sub_regulation_no'] }}</div>
                                        <div class="regulation-item sub-regulation-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subRegulation['sub_regulation_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            
                            @if (!empty($regulation['footnote_model']))
                                @foreach ($regulation['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>                              
                                @endforeach
                            @endif
                        <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['lists']))   
                    <div style="text-align: left">
                        @foreach ($item['lists'] as $list)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $list['list_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!! $list['list_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $list['list_content'] !!}</span>
                            </div>
                            
                            @if (!empty($list['sub_list_model']))
                                @foreach ($list['sub_list_model'] as $subList)
                                    <div class="list-container" style="margin-top: 0.4rem; padding-left:25px!important;">
                                        <div class="list-item sub-list-no " style="font-size: 14px !important;">{{ $subList['sub_list_no'] }}</div>
                                        <div class="list-item sub-list-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subList['sub_list_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($list['footnote_model']))
                                @foreach ($list['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['part']))   
                    <div style="text-align: left">
                        @foreach ($item['part'] as $part)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $part['part_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!!  $part['part_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $part['part_content'] !!}</span>
                            </div>
                            @if (!empty($part['sub_part_model']))
                                @foreach ($part['sub_part_model'] as $subPart)
                                    <div class="part-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="part-item sub-part-no " style="font-size: 14px !important;">{{ $subPart['sub_part_no'] }}</div>
                                        <div class="part-item sub-part-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subPart['sub_part_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($part['footnote_model']))
                                @foreach ($part['footnote_model'] as $footnoteModel)
                                     <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['appendices']))   
                    <div style="text-align: left">
                        @foreach ($item['appendices'] as $appendices)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $appendicesItem['appendices_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!! $appendicesItem['appendices_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $appendices['appendices_content'] !!}</span>
                            </div>
                            @if (!empty($appendices['sub_appendices_model']))
                                @foreach ($appendices['sub_appendices_model'] as $subAppendices)
                                    <div class="appendices-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="appendices-item sub-appendices-no " style="font-size: 14px !important;">{{ $subAppendices['sub_appendices_no'] }}</div>
                                        <div class="appendices-item sub-appendices-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subAppendices['sub_appendices_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($appendices['footnote_model']))
                                @foreach ($appendices['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['order']))   
                    <div style="text-align: left">
                        @foreach ($item['order'] as $order)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $order['order_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!! $order['order_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $order['order_content'] !!}</span>
                            </div>
                            @if (!empty($order['sub_order_model']))
                                @foreach ($order['sub_order_model'] as $subOrder)
                                    <div class="order-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="order-item sub-order-no " style="font-size: 14px !important;">{{ $subOrder['sub_order_no'] }}</div>
                                        <div class="order-item sub-order-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subOrder['sub_order_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($order['footnote_model']))
                                @foreach ($order['footnote_model'] as $footnoteModel)
                                     <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['annexure']))   
                    <div style="text-align: left">
                        @foreach ($item['annexure'] as $annexure)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $annexure['annexure_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!! $annexure['annexure_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $annexure['annexure_content'] !!}</span>
                            </div>
                            @if (!empty($annexure['sub_annexure_model']))
                                @foreach ($annexure['sub_annexure_model'] as $subAnnexure)
                                    <div class="annexure-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="annexure-item sub-annexure-no " style="font-size: 14px !important;">{{ $subAnnexure['sub_annexure_no'] }}</div>
                                        <div class="annexure-item sub-annexure-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subAnnexure['sub_annexure_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($annexure['footnote_model']))
                                @foreach ($annexure['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['stschedule']))   
                    <div style="text-align: left">
                        @foreach ($item['stschedule'] as $stschedule)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $stschedule['stschedule_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!!  $stschedule['stschedule_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $stschedule['stschedule_content'] !!}</span>
                            </div>
                            @if (!empty($stschedule['sub_stschedule_model']))
                                @foreach ($stschedule['sub_stschedule_model'] as $subStschedule)
                                    <div class="stschedule-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="stschedule-item sub-stschedule-no " style="font-size: 14px !important;">{{ $subStschedule['sub_stschedule_no'] }}</div>
                                        <div class="stschedule-item sub-stschedule-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subStschedule['sub_stschedule_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($stschedule['footnote_model']))
                                @foreach ($stschedule['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
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
                   <div style="text-align: left">
                        @foreach ($item['sections'] as $section)
                             <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{{ $section['section_no'] }}</p>
                                    </div>
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{!! $section['section_title'] !!}</p>
                                    </div>
                                </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $section['section_content'] !!}</span>
                            </div>
                    
                        @if (!empty($section['subsection_model']))
                            @foreach ($section['subsection_model'] as $subSection)
                                <div class="section-container" style="margin-top: 0.4rem; padding-left:25px!important;">
                                    <div class="section-item sub-section-no " style="font-size: 14px !important;">{{ $subSection['sub_section_no'] }}</div>
                                    <div class="section-item sub-section-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subSection['sub_section_content'] !!}</div>
                                </div>
                            @endforeach
                        @endif
                    
                        @if (!empty($section['footnote_model']))
                        
                            @foreach ($section['footnote_model'] as $footnoteModel)
                                <div style="padding-left: 35px!important; font-size: 10px !important">
                                    <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                        {!! $footnoteModel['footnote_content'] !!}
                                    </em>
                                </div>
                            @endforeach
                        @endif
                    
                        <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif  
                @if (!empty($item['articles']))   
                    <div style="text-align: left">
                        @foreach ($item['articles'] as $article)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{{ $article['article_no'] }}</p>
                                    </div>
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{!! $article['article_title'] !!}</p>
                                    </div>
                                </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $article['article_content'] !!}</span>
                            </div>
                            @if (!empty($article['sub_article_model']))
                                @foreach ($article['sub_article_model'] as $subArticle)
                                    
                                    <div class="article-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                    <div class="article-item sub-article-no " style="font-size: 14px !important;">{{ $subArticle['sub_article_no'] }}</div>
                                    <div class="article-item sub-article-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subArticle['sub_article_content'] !!}</div>
                                </div>
                                @endforeach
                            @endif
                            @if (!empty($article['footnote_model']))
                                @foreach ($article['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                        <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['rules']))   
                    <div style="text-align: left">
                        @foreach ($item['rules'] as $rule)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{{ $rule['rule_no'] }}</p>
                                    </div>
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{!! $rule['rule_title'] !!}</p>
                                    </div>
                                </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $rule['rule_content'] !!}</span>
                            </div>
                            
                            @if (!empty($rule['subrule_model']))
                                @foreach ($rule['subrule_model'] as $subRule)
                                    <div class="rule-container" style="margin-top: 0.4rem; padding-left:25px!important;">
                                        <div class="rule-item sub-rule-no " style="font-size: 14px !important;">{{ $subRule['sub_rule_no'] }}</div>
                                        <div class="rule-item sub-rule-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subRule['sub_rule_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($rule['footnote_model']))
                                @foreach ($rule['footnote_model'] as $footnoteModel)
                                     <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                        <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['regulation']))   
                    <div style="text-align: left">
                        @foreach ($item['regulation'] as $regulation)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{{ $regulation['regulation_no'] }}</p>
                                    </div>
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{!! $regulation['regulation_title'] !!}</p>
                                    </div>
                                </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $regulation['regulation_content'] !!}</span>
                            </div>
                            
                            @if (!empty($regulation['sub_regulation_model']))
                                @foreach ($regulation['sub_regulation_model'] as $subRegulation)
                                    <div class="regulation-container" style="margin-top: 0.4rem; padding-left:25px!important;">
                                        <div class="regulation-item sub-regulation-no " style="font-size: 14px !important;">{{ $subRegulation['sub_regulation_no'] }}</div>
                                        <div class="regulation-item sub-regulation-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subRegulation['sub_regulation_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            
                            @if (!empty($regulation['footnote_model']))
                                @foreach ($regulation['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>                              
                                @endforeach
                            @endif
                        <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['lists']))   
                    <div style="text-align: left">
                        @foreach ($item['lists'] as $list)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $list['list_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!! $list['list_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $list['list_content'] !!}</span>
                            </div>
                            
                            @if (!empty($list['sub_list_model']))
                                @foreach ($list['sub_list_model'] as $subList)
                                    <div class="list-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="list-item sub-list-no " style="font-size: 14px !important;">{{ $subList['sub_list_no'] }}</div>
                                        <div class="list-item sub-list-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subList['sub_list_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($list['footnote_model']))
                                @foreach ($list['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['part']))   
                    <div style="text-align: left">
                        @foreach ($item['part'] as $part)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $part['part_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!!  $part['part_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $part['part_content'] !!}</span>
                            </div>
                            @if (!empty($part['sub_part_model']))
                                @foreach ($part['sub_part_model'] as $subPart)
                                    <div class="part-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="part-item sub-part-no " style="font-size: 14px !important;">{{ $subPart['sub_part_no'] }}</div>
                                        <div class="part-item sub-part-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subPart['sub_part_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($part['footnote_model']))
                                @foreach ($part['footnote_model'] as $footnoteModel)
                                     <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['appendices']))   
                    <div style="text-align: left">
                        @foreach ($item['appendices'] as $appendices)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $appendicesItem['appendices_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!! $appendicesItem['appendices_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $appendices['appendices_content'] !!}</span>
                            </div>
                            @if (!empty($appendices['sub_appendices_model']))
                                @foreach ($appendices['sub_appendices_model'] as $subAppendices)
                                    <div class="appendices-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="appendices-item sub-appendices-no " style="font-size: 14px !important;">{{ $subAppendices['sub_appendices_no'] }}</div>
                                        <div class="appendices-item sub-appendices-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subAppendices['sub_appendices_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($appendices['footnote_model']))
                                @foreach ($appendices['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['order']))   
                    <div style="text-align: left">
                        @foreach ($item['order'] as $order)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $order['order_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!! $order['order_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $order['order_content'] !!}</span>
                            </div>
                            @if (!empty($order['sub_order_model']))
                                @foreach ($order['sub_order_model'] as $subOrder)
                                    <div class="order-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="order-item sub-order-no "tyle="font-size: 14px !important;" >{{ $subOrder['sub_order_no'] }}</div>
                                        <div class="order-item sub-order-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subOrder['sub_order_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($order['footnote_model']))
                                @foreach ($order['footnote_model'] as $footnoteModel)
                                     <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['annexure']))   
                    <div style="text-align: left">
                        @foreach ($item['annexure'] as $annexure)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $annexure['annexure_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!! $annexure['annexure_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $annexure['annexure_content'] !!}</span>
                            </div>
                            @if (!empty($annexure['sub_annexure_model']))
                                @foreach ($annexure['sub_annexure_model'] as $subAnnexure)
                                    <div class="annexure-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="annexure-item sub-annexure-no " style="font-size: 14px !important;">{{ $subAnnexure['sub_annexure_no'] }}</div>
                                        <div class="annexure-item sub-annexure-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subAnnexure['sub_annexure_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($annexure['footnote_model']))
                                @foreach ($annexure['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['stschedule']))   
                    <div style="text-align: left">
                        @foreach ($item['stschedule'] as $stschedule)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $stschedule['stschedule_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!!  $stschedule['stschedule_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $stschedule['stschedule_content'] !!}</span>
                            </div>
                            @if (!empty($stschedule['sub_stschedule_model']))
                                @foreach ($stschedule['sub_stschedule_model'] as $subStschedule)
                                    <div class="stschedule-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="stschedule-item sub-stschedule-no " style="font-size: 14px !important;">{{ $subStschedule['sub_stschedule_no'] }}</div>
                                        <div class="stschedule-item sub-stschedule-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subStschedule['sub_stschedule_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($stschedule['footnote_model']))
                                @foreach ($stschedule['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
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
                    <div style="text-align: left">
                        @foreach ($item['sections'] as $section)
                             <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{{ $section['section_no'] }}</p>
                                    </div>
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{!! $section['section_title'] !!}</p>
                                    </div>
                                </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $section['section_content'] !!}</span>
                            </div>
                    
                        @if (!empty($section['subsection_model']))
                            @foreach ($section['subsection_model'] as $subSection)
                                <div class="section-container" style="margin-top: 0.4rem; padding-left:25px!important;">
                                    <div class="section-item sub-section-no " style="font-size: 14px !important;">{{ $subSection['sub_section_no'] }}</div>
                                    <div class="section-item sub-section-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subSection['sub_section_content'] !!}</div>
                                </div>
                            @endforeach
                        @endif
                    
                        @if (!empty($section['footnote_model']))
                        
                            @foreach ($section['footnote_model'] as $footnoteModel)
                                <div style="padding-left: 35px!important; font-size: 10px !important">
                                    <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                        {!! $footnoteModel['footnote_content'] !!}
                                    </em>
                                </div>
                            @endforeach
                        @endif
                    
                        <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div> 
                @endif  
                @if (!empty($item['articles']))   
                    <div style="text-align: left">
                        @foreach ($item['articles'] as $article)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{{ $article['article_no'] }}</p>
                                    </div>
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{!! $article['article_title'] !!}</p>
                                    </div>
                                </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $article['article_content'] !!}</span>
                            </div>
                            @if (!empty($article['sub_article_model']))
                                @foreach ($article['sub_article_model'] as $subArticle)
                                    
                                    <div class="article-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                    <div class="article-item sub-article-no " style="font-size: 14px !important;">{{ $subArticle['sub_article_no'] }}</div>
                                    <div class="article-item sub-article-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subArticle['sub_article_content'] !!}</div>
                                </div>
                                @endforeach
                            @endif
                            @if (!empty($article['footnote_model']))
                                @foreach ($article['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                        <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['rules']))   
                    <div style="text-align: left">
                        @foreach ($item['rules'] as $rule)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{{ $rule['rule_no'] }}</p>
                                    </div>
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{!! $rule['rule_title'] !!}</p>
                                    </div>
                                </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $rule['rule_content'] !!}</span>
                            </div>
                            
                            @if (!empty($rule['subrule_model']))
                                @foreach ($rule['subrule_model'] as $subRule)
                                    <div class="rule-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="rule-item sub-rule-no " style="font-size: 14px !important;">{{ $subRule['sub_rule_no'] }}</div>
                                        <div class="rule-item sub-rule-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subRule['sub_rule_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($rule['footnote_model']))
                                @foreach ($rule['footnote_model'] as $footnoteModel)
                                     <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                        <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['regulation']))   
                    <div style="text-align: left">
                        @foreach ($item['regulation'] as $regulation)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{{ $regulation['regulation_no'] }}</p>
                                    </div>
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{!! $regulation['regulation_title'] !!}</p>
                                    </div>
                                </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $regulation['regulation_content'] !!}</span>
                            </div>
                            
                            @if (!empty($regulation['sub_regulation_model']))
                                @foreach ($regulation['sub_regulation_model'] as $subRegulation)
                                    <div class="regulation-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="regulation-item sub-regulation-no " style="font-size: 14px !important;">{{ $subRegulation['sub_regulation_no'] }}</div>
                                        <div class="regulation-item sub-regulation-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subRegulation['sub_regulation_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            
                            @if (!empty($regulation['footnote_model']))
                                @foreach ($regulation['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>                              
                                @endforeach
                            @endif
                        <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['lists']))   
                    <div style="text-align: left">
                        @foreach ($item['lists'] as $list)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $list['list_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!! $list['list_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $list['list_content'] !!}</span>
                            </div>
                            
                            @if (!empty($list['sub_list_model']))
                                @foreach ($list['sub_list_model'] as $subList)
                                    <div class="list-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="list-item sub-list-no " style="font-size: 14px !important;">{{ $subList['sub_list_no'] }}</div>
                                        <div class="list-item sub-list-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subList['sub_list_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($list['footnote_model']))
                                @foreach ($list['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['part']))   
                    <div style="text-align: left">
                        @foreach ($item['part'] as $part)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $part['part_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!!  $part['part_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $part['part_content'] !!}</span>
                            </div>
                            @if (!empty($part['sub_part_model']))
                                @foreach ($part['sub_part_model'] as $subPart)
                                    <div class="part-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="part-item sub-part-no " style="font-size: 14px !important;">{{ $subPart['sub_part_no'] }}</div>
                                        <div class="part-item sub-part-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subPart['sub_part_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($part['footnote_model']))
                                @foreach ($part['footnote_model'] as $footnoteModel)
                                     <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['appendices']))   
                    <div style="text-align: left">
                        @foreach ($item['appendices'] as $appendices)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $appendicesItem['appendices_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!! $appendicesItem['appendices_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $appendices['appendices_content'] !!}</span>
                            </div>
                            @if (!empty($appendices['sub_appendices_model']))
                                @foreach ($appendices['sub_appendices_model'] as $subAppendices)
                                    <div class="appendices-container" style="margin-top: 0.4rem; padding-left:25px!important;">
                                        <div class="appendices-item sub-appendices-no " style="font-size: 14px !important;">{{ $subAppendices['sub_appendices_no'] }}</div>
                                        <div class="appendices-item sub-appendices-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subAppendices['sub_appendices_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($appendices['footnote_model']))
                                @foreach ($appendices['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['order']))   
                    <div style="text-align: left">
                        @foreach ($item['order'] as $order)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $order['order_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!! $order['order_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $order['order_content'] !!}</span>
                            </div>
                            @if (!empty($order['sub_order_model']))
                                @foreach ($order['sub_order_model'] as $subOrder)
                                    <div class="order-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="order-item sub-order-no " style="font-size: 14px !important;">{{ $subOrder['sub_order_no'] }}</div>
                                        <div class="order-item sub-order-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subOrder['sub_order_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($order['footnote_model']))
                                @foreach ($order['footnote_model'] as $footnoteModel)
                                     <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['annexure']))   
                    <div style="text-align: left">
                        @foreach ($item['annexure'] as $annexure)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $annexure['annexure_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!! $annexure['annexure_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $annexure['annexure_content'] !!}</span>
                            </div>
                            @if (!empty($annexure['sub_annexure_model']))
                                @foreach ($annexure['sub_annexure_model'] as $subAnnexure)
                                    <div class="annexure-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="annexure-item sub-annexure-no " style="font-size: 14px !important;">{{ $subAnnexure['sub_annexure_no'] }}</div>
                                        <div class="annexure-item sub-annexure-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subAnnexure['sub_annexure_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($annexure['footnote_model']))
                                @foreach ($annexure['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['stschedule']))   
                    <div style="text-align: left">
                        @foreach ($item['stschedule'] as $stschedule)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $stschedule['stschedule_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!!  $stschedule['stschedule_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $stschedule['stschedule_content'] !!}</span>
                            </div>
                            @if (!empty($stschedule['sub_stschedule_model']))
                                @foreach ($stschedule['sub_stschedule_model'] as $subStschedule)
                                    <div class="stschedule-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="stschedule-item sub-stschedule-no " style="font-size: 14px !important;">{{ $subStschedule['sub_stschedule_no'] }}</div>
                                        <div class="stschedule-item sub-stschedule-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subStschedule['sub_stschedule_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($stschedule['footnote_model']))
                                @foreach ($stschedule['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
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
                    <div style="text-align: left">
                        @foreach ($item['sections'] as $section)
                             <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{{ $section['section_no'] }}</p>
                                    </div>
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{!! $section['section_title'] !!}</p>
                                    </div>
                                </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $section['section_content'] !!}</span>
                            </div>
                    
                        @if (!empty($section['subsection_model']))
                            @foreach ($section['subsection_model'] as $subSection)
                                <div class="section-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                    <div class="section-item sub-section-no " style="font-size: 14px !important;">{{ $subSection['sub_section_no'] }}</div>
                                    <div class="section-item sub-section-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subSection['sub_section_content'] !!}</div>
                                </div>
                            @endforeach
                        @endif
                    
                        @if (!empty($section['footnote_model']))
                        
                            @foreach ($section['footnote_model'] as $footnoteModel)
                                <div style="padding-left: 35px!important; font-size: 10px !important">
                                    <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                        {!! $footnoteModel['footnote_content'] !!}
                                    </em>
                                </div>
                            @endforeach
                        @endif
                    
                        <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                   
                @endif  
                @if (!empty($item['articles']))   
                    <div style="text-align: left">
                        @foreach ($item['articles'] as $article)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{{ $article['article_no'] }}</p>
                                    </div>
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{!! $article['article_title'] !!}</p>
                                    </div>
                                </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $article['article_content'] !!}</span>
                            </div>
                            @if (!empty($article['sub_article_model']))
                                @foreach ($article['sub_article_model'] as $subArticle)
                                    
                                    <div class="article-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                    <div class="article-item sub-article-no " style="font-size: 14px !important;">{{ $subArticle['sub_article_no'] }}</div>
                                    <div class="article-item sub-article-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subArticle['sub_article_content'] !!}</div>
                                </div>
                                @endforeach
                            @endif
                            @if (!empty($article['footnote_model']))
                                @foreach ($article['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                        <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['rules']))   
                    <div style="text-align: left">
                        @foreach ($item['rules'] as $rule)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{{ $rule['rule_no'] }}</p>
                                    </div>
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{!! $rule['rule_title'] !!}</p>
                                    </div>
                                </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $rule['rule_content'] !!}</span>
                            </div>
                            
                            @if (!empty($rule['subrule_model']))
                                @foreach ($rule['subrule_model'] as $subRule)
                                    <div class="rule-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="rule-item sub-rule-no " style="font-size: 14px !important;">{{ $subRule['sub_rule_no'] }}</div>
                                        <div class="rule-item sub-rule-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subRule['sub_rule_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($rule['footnote_model']))
                                @foreach ($rule['footnote_model'] as $footnoteModel)
                                     <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                        <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['regulation']))   
                    <div style="text-align: left">
                        @foreach ($item['regulation'] as $regulation)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{{ $regulation['regulation_no'] }}</p>
                                    </div>
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{!! $regulation['regulation_title'] !!}</p>
                                    </div>
                                </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $regulation['regulation_content'] !!}</span>
                            </div>
                            
                            @if (!empty($regulation['sub_regulation_model']))
                                @foreach ($regulation['sub_regulation_model'] as $subRegulation)
                                    <div class="regulation-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="regulation-item sub-regulation-no " style="font-size: 14px !important;">{{ $subRegulation['sub_regulation_no'] }}</div>
                                        <div class="regulation-item sub-regulation-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subRegulation['sub_regulation_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            
                            @if (!empty($regulation['footnote_model']))
                                @foreach ($regulation['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>                              
                                @endforeach
                            @endif
                        <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['lists']))   
                    <div style="text-align: left">
                        @foreach ($item['lists'] as $list)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $list['list_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!! $list['list_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $list['list_content'] !!}</span>
                            </div>
                            
                            @if (!empty($list['sub_list_model']))
                                @foreach ($list['sub_list_model'] as $subList)
                                    <div class="list-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="list-item sub-list-no " style="font-size: 14px !important;">{{ $subList['sub_list_no'] }}</div>
                                        <div class="list-item sub-list-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subList['sub_list_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($list['footnote_model']))
                                @foreach ($list['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['part']))   
                    <div style="text-align: left">
                        @foreach ($item['part'] as $part)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $part['part_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!!  $part['part_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $part['part_content'] !!}</span>
                            </div>
                            @if (!empty($part['sub_part_model']))
                                @foreach ($part['sub_part_model'] as $subPart)
                                    <div class="part-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="part-item sub-part-no " style="font-size: 14px !important;">{{ $subPart['sub_part_no'] }}</div>
                                        <div class="part-item sub-part-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subPart['sub_part_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($part['footnote_model']))
                                @foreach ($part['footnote_model'] as $footnoteModel)
                                     <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['appendices']))   
                    <div style="text-align: left">
                        @foreach ($item['appendices'] as $appendices)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $appendicesItem['appendices_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!! $appendicesItem['appendices_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $appendices['appendices_content'] !!}</span>
                            </div>
                            @if (!empty($appendices['sub_appendices_model']))
                                @foreach ($appendices['sub_appendices_model'] as $subAppendices)
                                    <div class="appendices-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="appendices-item sub-appendices-no " style="font-size: 14px !important;">{{ $subAppendices['sub_appendices_no'] }}</div>
                                        <div class="appendices-item sub-appendices-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subAppendices['sub_appendices_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($appendices['footnote_model']))
                                @foreach ($appendices['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['order']))   
                    <div style="text-align: left">
                        @foreach ($item['order'] as $order)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $order['order_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!! $order['order_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $order['order_content'] !!}</span>
                            </div>
                            @if (!empty($order['sub_order_model']))
                                @foreach ($order['sub_order_model'] as $subOrder)
                                    <div class="order-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="order-item sub-order-no " style="font-size: 14px !important;">{{ $subOrder['sub_order_no'] }}</div>
                                        <div class="order-item sub-order-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subOrder['sub_order_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($order['footnote_model']))
                                @foreach ($order['footnote_model'] as $footnoteModel)
                                     <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['annexure']))   
                    <div style="text-align: left">
                        @foreach ($item['annexure'] as $annexure)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $annexure['annexure_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!! $annexure['annexure_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $annexure['annexure_content'] !!}</span>
                            </div>
                            @if (!empty($annexure['sub_annexure_model']))
                                @foreach ($annexure['sub_annexure_model'] as $subAnnexure)
                                    <div class="annexure-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="annexure-item sub-annexure-no " style="font-size: 14px !important;">{{ $subAnnexure['sub_annexure_no'] }}</div>
                                        <div class="annexure-item sub-annexure-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subAnnexure['sub_annexure_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($annexure['footnote_model']))
                                @foreach ($annexure['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['stschedule']))   
                    <div style="text-align: left">
                        @foreach ($item['stschedule'] as $stschedule)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $stschedule['stschedule_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!!  $stschedule['stschedule_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $stschedule['stschedule_content'] !!}</span>
                            </div>
                            @if (!empty($stschedule['sub_stschedule_model']))
                                @foreach ($stschedule['sub_stschedule_model'] as $subStschedule)
                                    <div class="stschedule-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="stschedule-item sub-stschedule-no " style="font-size: 14px !important;">{{ $subStschedule['sub_stschedule_no'] }}</div>
                                        <div class="stschedule-item sub-stschedule-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subStschedule['sub_stschedule_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($stschedule['footnote_model']))
                                @foreach ($stschedule['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
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
                    <div style="text-align: left">
                        @foreach ($item['sections'] as $section)
                             <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{{ $section['section_no'] }}</p>
                                    </div>
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{!! $section['section_title'] !!}</p>
                                    </div>
                                </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $section['section_content'] !!}</span>
                            </div>
                    
                        @if (!empty($section['subsection_model']))
                            @foreach ($section['subsection_model'] as $subSection)
                                <div class="section-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                    <div class="section-item sub-section-no " style="font-size: 14px !important;">{{ $subSection['sub_section_no'] }}</div>
                                    <div class="section-item sub-section-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subSection['sub_section_content'] !!}</div>
                                </div>
                            @endforeach
                        @endif
                    
                        @if (!empty($section['footnote_model']))
                        
                            @foreach ($section['footnote_model'] as $footnoteModel)
                                <div style="padding-left: 35px!important; font-size: 10px !important">
                                    <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                        {!! $footnoteModel['footnote_content'] !!}
                                    </em>
                                </div>
                            @endforeach
                        @endif
                    
                        <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                   
                @endif  
                @if (!empty($item['articles']))   
                    <div style="text-align: left">
                        @foreach ($item['articles'] as $article)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{{ $article['article_no'] }}</p>
                                    </div>
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{!! $article['article_title'] !!}</p>
                                    </div>
                                </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $article['article_content'] !!}</span>
                            </div>
                            @if (!empty($article['sub_article_model']))
                                @foreach ($article['sub_article_model'] as $subArticle)
                                    
                                    <div class="article-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                    <div class="article-item sub-article-no " style="font-size: 14px !important;">{{ $subArticle['sub_article_no'] }}</div>
                                    <div class="article-item sub-article-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subArticle['sub_article_content'] !!}</div>
                                </div>
                                @endforeach
                            @endif
                            @if (!empty($article['footnote_model']))
                                @foreach ($article['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                        <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['rules']))   
                    <div style="text-align: left">
                        @foreach ($item['rules'] as $rule)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{{ $rule['rule_no'] }}</p>
                                    </div>
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{!! $rule['rule_title'] !!}</p>
                                    </div>
                                </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $rule['rule_content'] !!}</span>
                            </div>
                            
                            @if (!empty($rule['subrule_model']))
                                @foreach ($rule['subrule_model'] as $subRule)
                                    <div class="rule-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="rule-item sub-rule-no " style="font-size: 14px !important;">{{ $subRule['sub_rule_no'] }}</div>
                                        <div class="rule-item sub-rule-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subRule['sub_rule_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($rule['footnote_model']))
                                @foreach ($rule['footnote_model'] as $footnoteModel)
                                     <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                        <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['regulation']))   
                    <div style="text-align: left">
                        @foreach ($item['regulation'] as $regulation)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{{ $regulation['regulation_no'] }}</p>
                                    </div>
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{!! $regulation['regulation_title'] !!}</p>
                                    </div>
                                </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $regulation['regulation_content'] !!}</span>
                            </div>
                            
                            @if (!empty($regulation['sub_regulation_model']))
                                @foreach ($regulation['sub_regulation_model'] as $subRegulation)
                                    <div class="regulation-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="regulation-item sub-regulation-no " style="font-size: 14px !important;">{{ $subRegulation['sub_regulation_no'] }}</div>
                                        <div class="regulation-item sub-regulation-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subRegulation['sub_regulation_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            
                            @if (!empty($regulation['footnote_model']))
                                @foreach ($regulation['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>                              
                                @endforeach
                            @endif
                        <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['lists']))   
                   <div style="text-align: left">
                        @foreach ($item['lists'] as $list)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $list['list_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!! $list['list_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $list['list_content'] !!}</span>
                            </div>
                            
                            @if (!empty($list['sub_list_model']))
                                @foreach ($list['sub_list_model'] as $subList)
                                    <div class="list-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="list-item sub-list-no " style="font-size: 14px !important;">{{ $subList['sub_list_no'] }}</div>
                                        <div class="list-item sub-list-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subList['sub_list_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($list['footnote_model']))
                                @foreach ($list['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['part']))   
                    <div style="text-align: left">
                        @foreach ($item['part'] as $part)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $part['part_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!!  $part['part_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $part['part_content'] !!}</span>
                            </div>
                            @if (!empty($part['sub_part_model']))
                                @foreach ($part['sub_part_model'] as $subPart)
                                    <div class="part-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="part-item sub-part-no " style="font-size: 14px !important;">{{ $subPart['sub_part_no'] }}</div>
                                        <div class="part-item sub-part-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subPart['sub_part_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($part['footnote_model']))
                                @foreach ($part['footnote_model'] as $footnoteModel)
                                     <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['appendices']))   
                    <div style="text-align: left">
                        @foreach ($item['appendices'] as $appendices)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $appendicesItem['appendices_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!! $appendicesItem['appendices_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $appendices['appendices_content'] !!}</span>
                            </div>
                            @if (!empty($appendices['sub_appendices_model']))
                                @foreach ($appendices['sub_appendices_model'] as $subAppendices)
                                    <div class="appendices-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="appendices-item sub-appendices-no " style="font-size: 14px !important;">{{ $subAppendices['sub_appendices_no'] }}</div>
                                        <div class="appendices-item sub-appendices-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subAppendices['sub_appendices_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($appendices['footnote_model']))
                                @foreach ($appendices['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['order']))   
                    <div style="text-align: left">
                        @foreach ($item['order'] as $order)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $order['order_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!! $order['order_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $order['order_content'] !!}</span>
                            </div>
                            @if (!empty($order['sub_order_model']))
                                @foreach ($order['sub_order_model'] as $subOrder)
                                    <div class="order-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="order-item sub-order-no " style="font-size: 14px !important;">{{ $subOrder['sub_order_no'] }}</div>
                                        <div class="order-item sub-order-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subOrder['sub_order_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($order['footnote_model']))
                                @foreach ($order['footnote_model'] as $footnoteModel)
                                     <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['annexure']))   
                    <div style="text-align: left">
                        @foreach ($item['annexure'] as $annexure)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $annexure['annexure_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!! $annexure['annexure_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $annexure['annexure_content'] !!}</span>
                            </div>
                            @if (!empty($annexure['sub_annexure_model']))
                                @foreach ($annexure['sub_annexure_model'] as $subAnnexure)
                                    <div class="annexure-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="annexure-item sub-annexure-no " style="font-size: 14px !important;">{{ $subAnnexure['sub_annexure_no'] }}</div>
                                        <div class="annexure-item sub-annexure-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subAnnexure['sub_annexure_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($annexure['footnote_model']))
                                @foreach ($annexure['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['stschedule']))   
                    <div style="text-align: left">
                        @foreach ($item['stschedule'] as $stschedule)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $stschedule['stschedule_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!!  $stschedule['stschedule_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $stschedule['stschedule_content'] !!}</span>
                            </div>
                            @if (!empty($stschedule['sub_stschedule_model']))
                                @foreach ($stschedule['sub_stschedule_model'] as $subStschedule)
                                    <div class="stschedule-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="stschedule-item sub-stschedule-no " style="font-size: 14px !important;">{{ $subStschedule['sub_stschedule_no'] }}</div>
                                        <div class="stschedule-item sub-stschedule-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subStschedule['sub_stschedule_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($stschedule['footnote_model']))
                                @foreach ($stschedule['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
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
                    <div style="text-align: left">
                        @foreach ($item['sections'] as $section)
                             <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{{ $section['section_no'] }}</p>
                                    </div>
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{!! $section['section_title'] !!}</p>
                                    </div>
                                </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $section['section_content'] !!}</span>
                            </div>
                    
                        @if (!empty($section['subsection_model']))
                            @foreach ($section['subsection_model'] as $subSection)
                                <div class="section-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                    <div class="section-item sub-section-no " style="font-size: 14px !important;">{{ $subSection['sub_section_no'] }}</div>
                                    <div class="section-item sub-section-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subSection['sub_section_content'] !!}</div>
                                </div>
                            @endforeach
                        @endif
                    
                        @if (!empty($section['footnote_model']))
                        
                            @foreach ($section['footnote_model'] as $footnoteModel)
                                <div style="padding-left: 35px!important; font-size: 10px !important">
                                    <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                        {!! $footnoteModel['footnote_content'] !!}
                                    </em>
                                </div>
                            @endforeach
                        @endif
                    
                        <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>  
                @endif  
                @if (!empty($item['articles']))   
                    <div style="text-align: left">
                        @foreach ($item['articles'] as $article)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{{ $article['article_no'] }}</p>
                                    </div>
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{!! $article['article_title'] !!}</p>
                                    </div>
                                </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $article['article_content'] !!}</span>
                            </div>
                            @if (!empty($article['sub_article_model']))
                                @foreach ($article['sub_article_model'] as $subArticle)
                                    
                                    <div class="article-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                    <div class="article-item sub-article-no " style="font-size: 14px !important;">{{ $subArticle['sub_article_no'] }}</div>
                                    <div class="article-item sub-article-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subArticle['sub_article_content'] !!}</div>
                                </div>
                                @endforeach
                            @endif
                            @if (!empty($article['footnote_model']))
                                @foreach ($article['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                        <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['rules']))   
                    <div style="text-align: left">
                        @foreach ($item['rules'] as $rule)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{{ $rule['rule_no'] }}</p>
                                    </div>
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{!! $rule['rule_title'] !!}</p>
                                    </div>
                                </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $rule['rule_content'] !!}</span>
                            </div>
                            
                            @if (!empty($rule['subrule_model']))
                                @foreach ($rule['subrule_model'] as $subRule)
                                    <div class="rule-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="rule-item sub-rule-no " style="font-size: 14px !important;">{{ $subRule['sub_rule_no'] }}</div>
                                        <div class="rule-item sub-rule-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subRule['sub_rule_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($rule['footnote_model']))
                                @foreach ($rule['footnote_model'] as $footnoteModel)
                                     <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                        <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['regulation']))   
                    <div style="text-align: left">
                        @foreach ($item['regulation'] as $regulation)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{{ $regulation['regulation_no'] }}</p>
                                    </div>
                                    <div style="margin: 0; padding: 0;">
                                        <p style="margin: 0;font-weight: bold;">{!! $regulation['regulation_title'] !!}</p>
                                    </div>
                                </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $regulation['regulation_content'] !!}</span>
                            </div>
                            
                            @if (!empty($regulation['sub_regulation_model']))
                                @foreach ($regulation['sub_regulation_model'] as $subRegulation)
                                    <div class="regulation-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="regulation-item sub-regulation-no " style="font-size: 14px !important;">{{ $subRegulation['sub_regulation_no'] }}</div>
                                        <div class="regulation-item sub-regulation-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subRegulation['sub_regulation_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            
                            @if (!empty($regulation['footnote_model']))
                                @foreach ($regulation['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>                              
                                @endforeach
                            @endif
                        <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['lists']))   
                    <div style="text-align: left">
                        @foreach ($item['lists'] as $list)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $list['list_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!! $list['list_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $list['list_content'] !!}</span>
                            </div>
                            
                            @if (!empty($list['sub_list_model']))
                                @foreach ($list['sub_list_model'] as $subList)
                                    <div class="list-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="list-item sub-list-no " style="font-size: 14px !important;">{{ $subList['sub_list_no'] }}</div>
                                        <div class="list-item sub-list-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subList['sub_list_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($list['footnote_model']))
                                @foreach ($list['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['part']))   
                    <div style="text-align: left">
                        @foreach ($item['part'] as $part)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $part['part_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!!  $part['part_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $part['part_content'] !!}</span>
                            </div>
                            @if (!empty($part['sub_part_model']))
                                @foreach ($part['sub_part_model'] as $subPart)
                                    <div class="part-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="part-item sub-part-no " style="font-size: 14px !important;">{{ $subPart['sub_part_no'] }}</div>
                                        <div class="part-item sub-part-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subPart['sub_part_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($part['footnote_model']))
                                @foreach ($part['footnote_model'] as $footnoteModel)
                                     <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['appendices']))   
                    <div style="text-align: left">
                        @foreach ($item['appendices'] as $appendices)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $appendicesItem['appendices_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!! $appendicesItem['appendices_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $appendices['appendices_content'] !!}</span>
                            </div>
                            @if (!empty($appendices['sub_appendices_model']))
                                @foreach ($appendices['sub_appendices_model'] as $subAppendices)
                                    <div class="appendices-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="appendices-item sub-appendices-no " style="font-size: 14px !important;">{{ $subAppendices['sub_appendices_no'] }}</div>
                                        <div class="appendices-item sub-appendices-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subAppendices['sub_appendices_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($appendices['footnote_model']))
                                @foreach ($appendices['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['order']))   
                    <div style="text-align: left">
                        @foreach ($item['order'] as $order)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $order['order_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!! $order['order_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $order['order_content'] !!}</span>
                            </div>
                            @if (!empty($order['sub_order_model']))
                                @foreach ($order['sub_order_model'] as $subOrder)
                                    <div class="order-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="order-item sub-order-no " style="font-size: 14px !important;">{{ $subOrder['sub_order_no'] }}</div>
                                        <div class="order-item sub-order-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subOrder['sub_order_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($order['footnote_model']))
                                @foreach ($order['footnote_model'] as $footnoteModel)
                                     <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['annexure']))   
                    <div style="text-align: left">
                        @foreach ($item['annexure'] as $annexure)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $annexure['annexure_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!! $annexure['annexure_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $annexure['annexure_content'] !!}</span>
                            </div>
                            @if (!empty($annexure['sub_annexure_model']))
                                @foreach ($annexure['sub_annexure_model'] as $subAnnexure)
                                    <div class="annexure-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="annexure-item sub-annexure-no " style="font-size: 14px !important;">{{ $subAnnexure['sub_annexure_no'] }}</div>
                                        <div class="annexure-item sub-annexure-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subAnnexure['sub_annexure_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($annexure['footnote_model']))
                                @foreach ($annexure['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                        @endforeach
                    </div>
                @endif
                @if (!empty($item['stschedule']))   
                    <div style="text-align: left">
                        @foreach ($item['stschedule'] as $stschedule)
                            <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{{ $stschedule['stschedule_no'] }}</p>
                                </div>
                                <div style="margin: 0; padding: 0;">
                                    <p style="margin: 0;font-weight: bold;">{!!  $stschedule['stschedule_title'] !!}</p>
                                </div>
                            </div>
                            <div style="padding-left: 18px;">
                                <span style=" margin-bottom: 5px!important;font-size: 14px !important;">{!! $stschedule['stschedule_content'] !!}</span>
                            </div>
                            @if (!empty($stschedule['sub_stschedule_model']))
                                @foreach ($stschedule['sub_stschedule_model'] as $subStschedule)
                                    <div class="stschedule-container" style="margin-top: 0.4rem;  padding-left:25px!important;">
                                        <div class="stschedule-item sub-stschedule-no " style="font-size: 14px !important;">{{ $subStschedule['sub_stschedule_no'] }}</div>
                                        <div class="stschedule-item sub-stschedule-content" style="margin-top:-12px!important; font-size: 14px !important;">{!! $subStschedule['sub_stschedule_content'] !!}</div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($stschedule['footnote_model']))
                                @foreach ($stschedule['footnote_model'] as $footnoteModel)
                                    <div style="padding-left: 35px!important; font-size: 10px !important">
                                        <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                            {!! $footnoteModel['footnote_content'] !!}
                                        </em>
                                    </div>
                                @endforeach
                            @endif
                          <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
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
