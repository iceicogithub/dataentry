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
        body, html {
            margin: 0;
            padding: 0;
        }

        /* Ensure content fits within page boundaries */
        @page {
            size: A4;
            margin: 0;
        }

        /* Define styles for content */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
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
        
        table{
            page-break-inside: avoid;
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
                <div style="text-transform: uppercase !important; font-size: 15px !important; margin-top: 0.4rem;">
                    {!! preg_replace('/[0-9\[\]\.]/', '', $item['parts_title']) !!}</div>
            </div>
            @if (!empty($item['sections']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['sections'] as $sectionItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td style="vertical-align: baseline;">
                                    <p>{{ $sectionItem['section_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $ruleItem['rule_no'] }}</p>
                                </td>
                                <td>
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
                                    <td style="vertical-align: baseline;">
                                        <p>{{ $regulationItem['regulation_no'] }}</p>
                                    </td>
                                    <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $listItem['list_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $partItem['part_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $appendicesItem['appendices_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $orderItem['order_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $annexureItem['annexure_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $stscheduleItem['stschedule_no'] }}</p>
                                </td>
                                <td>
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
                <div style="text-transform: uppercase !important; font-size: 15px !important; margin-top: 0.4rem;">
                    {!! preg_replace('/[0-9\[\]\.]/', '', $item['chapter_title']) !!}</div>
            </div>
            @if (!empty($item['sections']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['sections'] as $sectionItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td style="vertical-align: baseline;">
                                    <p>{{ $sectionItem['section_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $ruleItem['rule_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $regulationItem['regulation_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $listItem['list_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $partItem['part_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $appendicesItem['appendices_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $orderItem['order_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $annexureItem['annexure_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $stscheduleItem['stschedule_no'] }}</p>
                                </td>
                                <td>
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
                <div style="text-transform: uppercase !important; font-size: 15px !important; margin-top: 0.4rem;">
                    {!! preg_replace('/[0-9\[\]\.]/', '', $item['priliminary_title']) !!}</div>
            </div>
            @if (!empty($item['sections']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['sections'] as $sectionItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td style="vertical-align: baseline;">
                                    <p>{{ $sectionItem['section_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $ruleItem['rule_no'] }}</p>
                                </td>
                                <td>
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
                                    <td style="vertical-align: baseline;">
                                        <p>{{ $regulationItem['regulation_no'] }}</p>
                                    </td>
                                    <td>
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
                                    <td style="vertical-align: baseline;">
                                        <p>{{ $listItem['list_no'] }}</p>
                                    </td>
                                    <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $partItem['part_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $appendicesItem['appendices_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $orderItem['order_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $annexureItem['annexure_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $stscheduleItem['stschedule_no'] }}</p>
                                </td>
                                <td>
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
                <div style="text-transform: uppercase !important; font-size: 15px !important; margin-top: 0.4rem;">
                    {!! preg_replace('/[0-9\[\]\.]/', '', $item['schedule_title']) !!}</div>
            </div>
            @if (!empty($item['sections']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['sections'] as $sectionItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td style="vertical-align: baseline;">
                                    <p>{{ $sectionItem['section_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $ruleItem['rule_no'] }}</p>
                                </td>
                                <td>
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
                                    <td style="vertical-align: baseline;">
                                        <p>{{ $regulationItem['regulation_no'] }}</p>
                                    </td>
                                    <td>
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
                                    <td style="vertical-align: baseline;">
                                        <p>{{ $listItem['list_no'] }}</p>
                                    </td>
                                    <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $partItem['part_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $appendicesItem['appendices_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $orderItem['order_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $annexureItem['annexure_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $stscheduleItem['stschedule_no'] }}</p>
                                </td>
                                <td>
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
                <div style="text-transform: uppercase !important; font-size: 15px !important; margin-top: 0.4rem;">
                    {!! preg_replace('/[0-9\[\]\.]/', '', $item['appendix_title']) !!}</div>
            </div>
            @if (!empty($item['sections']))
                <div style="text-align: start; margin-top: 0.4rem;">
                    @foreach ($item['sections'] as $sectionItem)
                        <table style="font-size: 15px !important;">
                            <tr>
                                <td style="vertical-align: baseline;">
                                    <p>{{ $sectionItem['section_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $ruleItem['rule_no'] }}</p>
                                </td>
                                <td>
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
                                    <td style="vertical-align: baseline;">
                                        <p>{{ $regulationItem['regulation_no'] }}</p>
                                    </td>
                                    <td>
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
                                    <td style="vertical-align: baseline;">
                                        <p>{{ $listItem['list_no'] }}</p>
                                    </td>
                                    <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $partItem['part_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $appendicesItem['appendices_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $orderItem['order_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $annexureItem['annexure_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $stscheduleItem['stschedule_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $sectionItem['section_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $ruleItem['rule_no'] }}</p>
                                </td>
                                <td>
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
                                    <td style="vertical-align: baseline;">
                                        <p>{{ $regulationItem['regulation_no'] }}</p>
                                    </td>
                                    <td>
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
                                    <td style="vertical-align: baseline;">
                                        <p>{{ $listItem['list_no'] }}</p>
                                    </td>
                                    <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $partItem['part_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $appendicesItem['appendices_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $orderItem['order_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $annexureItem['annexure_no'] }}</p>
                                </td>
                                <td>
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
                                <td style="vertical-align: baseline;">
                                    <p>{{ $stscheduleItem['stschedule_no'] }}</p>
                                </td>
                                <td>
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
    <div style="text-align: center; text-transform: uppercase !important;font-size: 20px !important;">
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
            @if (isset($item['parts_id']))
                <div style="text-align: center">
                    <div style="text-transform: uppercase !important; font-size: 15px !important; margin-top: 0.4rem;">
                        {!! $item['parts_title'] !!}
                    </div>
                </div>
                @if (!empty($item['sections']))   
                    <div style="text-align: start">
                        @foreach ($item['sections'] as $section)
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $section['section_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $section['section_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $section['section_content'] !!}</span>
                            
                            @if (!empty($section['subsection_model']))
                                @foreach ($section['subsection_model'] as $subSection)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subSection['sub_section_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $article['article_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $article['article_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $article['article_content'] !!}</span>
                            
                            @if (!empty($article['sub_article_model']))
                                @foreach ($article['sub_article_model'] as $subArticle)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subArticle['sub_article_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $rule['rule_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $rule['rule_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $rule['rule_content'] !!}</span>
                            
                            @if (!empty($rule['subrule_model']))
                                @foreach ($rule['subrule_model'] as $subRule)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subRule['sub_rule_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $regulation['regulation_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $regulation['regulation_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $regulation['regulation_content'] !!}</span>
                            
                            @if (!empty($regulation['sub_regulation_model']))
                                @foreach ($regulation['sub_regulation_model'] as $subRegulation)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subRegulation['sub_regulation_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $list['list_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $list['list_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $part['part_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $part['part_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $part['part_content'] !!}</span>
                            
                            @if (!empty($part['sub_part_model']))
                                @foreach ($part['sub_part_model'] as $subPart)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subPart['sub_part_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $appendices['appendices_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $appendices['appendices_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $appendices['appendices_content'] !!}</span>
                            
                            @if (!empty($appendices['sub_appendices_model']))
                                @foreach ($appendices['sub_appendices_model'] as $subAppendices)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subAppendices['sub_appendices_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $order['order_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $order['order_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $order['order_content'] !!}</span>
                            
                            @if (!empty($order['sub_order_model']))
                                @foreach ($order['sub_order_model'] as $subOrder)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subOrder['sub_order_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $annexure['annexure_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $annexure['annexure_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $annexure['annexure_content'] !!}</span>
                            
                            @if (!empty($annexure['sub_annexure_model']))
                                @foreach ($annexure['sub_annexure_model'] as $subAnnexure)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subAnnexure['sub_annexure_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $stschedule['stschedule_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $stschedule['stschedule_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $stschedule['stschedule_content'] !!}</span>
                            
                            @if (!empty($stschedule['sub_stschedule_model']))
                                @foreach ($stschedule['sub_stschedule_model'] as $subStschedule)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subStschedule['sub_stschedule_no'] }}</p>
                                            </td>
                                            <td>
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
            @if (isset($item['chapter_id']))
                <div style="text-align: center">
                    <div style="text-transform: uppercase !important; font-size: 15px !important; margin-top: 0.4rem;">
                        {!! $item['chapter_title'] !!}
                    </div>
                </div>
                @if (!empty($item['sections']))   
                    <div style="text-align: start">
                        @foreach ($item['sections'] as $section)
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $section['section_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $section['section_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $section['section_content'] !!}</span>
                            
                            @if (!empty($section['subsection_model']))
                                @foreach ($section['subsection_model'] as $subSection)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subSection['sub_section_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $article['article_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $article['article_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $article['article_content'] !!}</span>
                            
                            @if (!empty($article['sub_article_model']))
                                @foreach ($article['sub_article_model'] as $subArticle)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subArticle['sub_article_no'] }}</p>
                                            </td>
                                            <td>
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
                                <strong>
                                    <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $rule['rule_no'] }}</p>
                                            </td>
                                            <td>
                                                <p>{!! $rule['rule_title'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                </strong>
                                <span>{!! $rule['rule_content'] !!}</span>
                                
                                @if (!empty($rule['subrule_model']))
                                    @foreach ($rule['subrule_model'] as $subRule)
                                        <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                            <tr>
                                                <td style="vertical-align: baseline;">
                                                    <p>{{ $subRule['sub_rule_no'] }}</p>
                                                </td>
                                                <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $regulation['regulation_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $regulation['regulation_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $regulation['regulation_content'] !!}</span>
                            
                            @if (!empty($regulation['sub_regulation_model']))
                                @foreach ($regulation['sub_regulation_model'] as $subRegulation)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subRegulation['sub_regulation_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $list['list_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $list['list_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
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
                                <strong>
                                    <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $part['part_no'] }}</p>
                                            </td>
                                            <td>
                                                <p>{!! $part['part_title'] !!}</p>
                                            </td>
                                        </tr>
                                    </table>
                                </strong>
                                <span>{!! $part['part_content'] !!}</span>
                                
                                @if (!empty($part['sub_part_model']))
                                    @foreach ($part['sub_part_model'] as $subPart)
                                        <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                            <tr>
                                                <td style="vertical-align: baseline;">
                                                    <p>{{ $subPart['sub_part_no'] }}</p>
                                                </td>
                                                <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $appendices['appendices_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $appendices['appendices_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $appendices['appendices_content'] !!}</span>
                            
                            @if (!empty($appendices['sub_appendices_model']))
                                @foreach ($appendices['sub_appendices_model'] as $subAppendices)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subAppendices['sub_appendices_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $order['order_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $order['order_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $order['order_content'] !!}</span>
                            
                            @if (!empty($order['sub_order_model']))
                                @foreach ($order['sub_order_model'] as $subOrder)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subOrder['sub_order_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $annexure['annexure_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $annexure['annexure_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $annexure['annexure_content'] !!}</span>
                            
                            @if (!empty($annexure['sub_annexure_model']))
                                @foreach ($annexure['sub_annexure_model'] as $subAnnexure)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subAnnexure['sub_annexure_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $stschedule['stschedule_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $stschedule['stschedule_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $stschedule['stschedule_content'] !!}</span>
                            
                            @if (!empty($stschedule['sub_stschedule_model']))
                                @foreach ($stschedule['sub_stschedule_model'] as $subStschedule)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subStschedule['sub_stschedule_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $section['section_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $section['section_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $section['section_content'] !!}</span>
                            
                            @if (!empty($section['subsection_model']))
                                @foreach ($section['subsection_model'] as $subSection)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subSection['sub_section_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $article['article_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $article['article_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $article['article_content'] !!}</span>
                            
                            @if (!empty($article['sub_article_model']))
                                @foreach ($article['sub_article_model'] as $subArticle)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subArticle['sub_article_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $rule['rule_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $rule['rule_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $rule['rule_content'] !!}</span>
                            
                            @if (!empty($rule['subrule_model']))
                                @foreach ($rule['subrule_model'] as $subRule)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subRule['sub_rule_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $regulation['regulation_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $regulation['regulation_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $regulation['regulation_content'] !!}</span>
                            
                            @if (!empty($regulation['sub_regulation_model']))
                                @foreach ($regulation['sub_regulation_model'] as $subRegulation)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subRegulation['sub_regulation_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $list['list_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $list['list_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $part['part_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $part['part_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $part['part_content'] !!}</span>
                            
                            @if (!empty($part['sub_part_model']))
                                @foreach ($part['sub_part_model'] as $subPart)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subPart['sub_part_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $appendices['appendices_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $appendices['appendices_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $appendices['appendices_content'] !!}</span>
                            
                            @if (!empty($appendices['sub_appendices_model']))
                                @foreach ($appendices['sub_appendices_model'] as $subAppendices)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subAppendices['sub_appendices_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $order['order_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $order['order_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $order['order_content'] !!}</span>
                            
                            @if (!empty($order['sub_order_model']))
                                @foreach ($order['sub_order_model'] as $subOrder)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subOrder['sub_order_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $annexure['annexure_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $annexure['annexure_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $annexure['annexure_content'] !!}</span>
                            
                            @if (!empty($annexure['sub_annexure_model']))
                                @foreach ($annexure['sub_annexure_model'] as $subAnnexure)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subAnnexure['sub_annexure_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $stschedule['stschedule_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $stschedule['stschedule_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $stschedule['stschedule_content'] !!}</span>
                            
                            @if (!empty($stschedule['sub_stschedule_model']))
                                @foreach ($stschedule['sub_stschedule_model'] as $subStschedule)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subStschedule['sub_stschedule_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $section['section_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $section['section_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $section['section_content'] !!}</span>
                            
                            @if (!empty($section['subsection_model']))
                                @foreach ($section['subsection_model'] as $subSection)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subSection['sub_section_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $article['article_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $article['article_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $article['article_content'] !!}</span>
                            
                            @if (!empty($article['sub_article_model']))
                                @foreach ($article['sub_article_model'] as $subArticle)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subArticle['sub_article_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $rule['rule_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $rule['rule_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $rule['rule_content'] !!}</span>
                            
                            @if (!empty($rule['subrule_model']))
                                @foreach ($rule['subrule_model'] as $subRule)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subRule['sub_rule_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $regulation['regulation_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $regulation['regulation_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $regulation['regulation_content'] !!}</span>
                            
                            @if (!empty($regulation['sub_regulation_model']))
                                @foreach ($regulation['sub_regulation_model'] as $subRegulation)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subRegulation['sub_regulation_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $list['list_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $list['list_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $part['part_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $part['part_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $part['part_content'] !!}</span>
                            
                            @if (!empty($part['sub_part_model']))
                                @foreach ($part['sub_part_model'] as $subPart)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subPart['sub_part_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $appendices['appendices_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $appendices['appendices_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $appendices['appendices_content'] !!}</span>
                            
                            @if (!empty($appendices['sub_appendices_model']))
                                @foreach ($appendices['sub_appendices_model'] as $subAppendices)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subAppendices['sub_appendices_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $order['order_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $order['order_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $order['order_content'] !!}</span>
                            
                            @if (!empty($order['sub_order_model']))
                                @foreach ($order['sub_order_model'] as $subOrder)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subOrder['sub_order_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $annexure['annexure_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $annexure['annexure_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $annexure['annexure_content'] !!}</span>
                            
                            @if (!empty($annexure['sub_annexure_model']))
                                @foreach ($annexure['sub_annexure_model'] as $subAnnexure)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subAnnexure['sub_annexure_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $stschedule['stschedule_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $stschedule['stschedule_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $stschedule['stschedule_content'] !!}</span>
                            
                            @if (!empty($stschedule['sub_stschedule_model']))
                                @foreach ($stschedule['sub_stschedule_model'] as $subStschedule)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subStschedule['sub_stschedule_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $section['section_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $section['section_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $section['section_content'] !!}</span>
                            
                            @if (!empty($section['subsection_model']))
                                @foreach ($section['subsection_model'] as $subSection)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subSection['sub_section_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $article['article_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $article['article_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $article['article_content'] !!}</span>
                            
                            @if (!empty($article['sub_article_model']))
                                @foreach ($article['sub_article_model'] as $subArticle)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subArticle['sub_article_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $rule['rule_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $rule['rule_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $rule['rule_content'] !!}</span>
                            
                            @if (!empty($rule['subrule_model']))
                                @foreach ($rule['subrule_model'] as $subRule)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subRule['sub_rule_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $regulation['regulation_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $regulation['regulation_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $regulation['regulation_content'] !!}</span>
                            
                            @if (!empty($regulation['sub_regulation_model']))
                                @foreach ($regulation['sub_regulation_model'] as $subRegulation)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subRegulation['sub_regulation_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $list['list_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $list['list_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $part['part_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $part['part_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $part['part_content'] !!}</span>
                            
                            @if (!empty($part['sub_part_model']))
                                @foreach ($part['sub_part_model'] as $subPart)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subPart['sub_part_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $appendices['appendices_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $appendices['appendices_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $appendices['appendices_content'] !!}</span>
                            
                            @if (!empty($appendices['sub_appendices_model']))
                                @foreach ($appendices['sub_appendices_model'] as $subAppendices)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subAppendices['sub_appendices_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $order['order_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $order['order_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $order['order_content'] !!}</span>
                            
                            @if (!empty($order['sub_order_model']))
                                @foreach ($order['sub_order_model'] as $subOrder)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subOrder['sub_order_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $annexure['annexure_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $annexure['annexure_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $annexure['annexure_content'] !!}</span>
                            
                            @if (!empty($annexure['sub_annexure_model']))
                                @foreach ($annexure['sub_annexure_model'] as $subAnnexure)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subAnnexure['sub_annexure_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $stschedule['stschedule_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $stschedule['stschedule_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $stschedule['stschedule_content'] !!}</span>
                            
                            @if (!empty($stschedule['sub_stschedule_model']))
                                @foreach ($stschedule['sub_stschedule_model'] as $subStschedule)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subStschedule['sub_stschedule_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $section['section_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $section['section_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $section['section_content'] !!}</span>
                            
                            @if (!empty($section['subsection_model']))
                                @foreach ($section['subsection_model'] as $subSection)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subSection['sub_section_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $article['article_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $article['article_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $article['article_content'] !!}</span>
                            
                            @if (!empty($article['sub_article_model']))
                                @foreach ($article['sub_article_model'] as $subArticle)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subArticle['sub_article_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $rule['rule_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $rule['rule_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $rule['rule_content'] !!}</span>
                            
                            @if (!empty($rule['subrule_model']))
                                @foreach ($rule['subrule_model'] as $subRule)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subRule['sub_rule_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $regulation['regulation_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $regulation['regulation_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $regulation['regulation_content'] !!}</span>
                            
                            @if (!empty($regulation['sub_regulation_model']))
                                @foreach ($regulation['sub_regulation_model'] as $subRegulation)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subRegulation['sub_regulation_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $list['list_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $list['list_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $part['part_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $part['part_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $part['part_content'] !!}</span>
                            
                            @if (!empty($part['sub_part_model']))
                                @foreach ($part['sub_part_model'] as $subPart)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subPart['sub_part_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $appendices['appendices_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $appendices['appendices_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $appendices['appendices_content'] !!}</span>
                            
                            @if (!empty($appendices['sub_appendices_model']))
                                @foreach ($appendices['sub_appendices_model'] as $subAppendices)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subAppendices['sub_appendices_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $order['order_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $order['order_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $order['order_content'] !!}</span>
                            
                            @if (!empty($order['sub_order_model']))
                                @foreach ($order['sub_order_model'] as $subOrder)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subOrder['sub_order_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $annexure['annexure_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $annexure['annexure_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $annexure['annexure_content'] !!}</span>
                            
                            @if (!empty($annexure['sub_annexure_model']))
                                @foreach ($annexure['sub_annexure_model'] as $subAnnexure)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subAnnexure['sub_annexure_no'] }}</p>
                                            </td>
                                            <td>
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
                            <strong>
                                <table style="font-size: 15px !important; margin-top: 0.4rem; page-break-inside: avoid;">
                                    <tr>
                                        <td style="vertical-align: baseline;">
                                            <p>{{ $stschedule['stschedule_no'] }}</p>
                                        </td>
                                        <td>
                                            <p>{!! $stschedule['stschedule_title'] !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            </strong>
                            <span>{!! $stschedule['stschedule_content'] !!}</span>
                            
                            @if (!empty($stschedule['sub_stschedule_model']))
                                @foreach ($stschedule['sub_stschedule_model'] as $subStschedule)
                                    <table style="margin-left: 2%; text-align: justify; margin-top: 0.4rem; page-break-inside: avoid;">
                                        <tr>
                                            <td style="vertical-align: baseline;">
                                                <p>{{ $subStschedule['sub_stschedule_no'] }}</p>
                                            </td>
                                            <td>
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
