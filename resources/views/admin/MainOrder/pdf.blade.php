<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.3/dist/css/bootstrap.min.css"
        integrity="sha384-GLhlTQ8iK7t9LdI8L6FU9tYmVlMGTskTpkEAIaCkIbbVcGpF5eSrhbY6SOMZgT" crossorigin="anonymous">
    <title>{{ $combinedItems[0]->new_rule_title }}</title>

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

    <div style="padding: 50px 50px !important;">
        <div style="text-align: center; text-transform: uppercase !important; font-size: 20px !important;">
            {{ $combinedItems[0]->new_order_title }}
        </div>
        <hr style="width: 10% !important; margin: 10px auto !important;">
        <div style="text-align: center; font-size: 20px !important;">ARRANGEMENT OF SECTIONS</div>
        <hr style="width: 10% !important; margin: 10px auto !important;">
        {{-- Loop through each NewRegulation --}}
        @foreach ($combinedItems as $NewOrder)
            {{-- Check if regulationMain relation exists --}}
            @if (isset($NewOrder->orderMain))
                {{-- Loop through each regulationMain --}}
                @foreach ($NewOrder->orderMain as $orderMain)
                    <div style="text-align: center; margin-bottom: 0.5rem;">
                        <div style="text-transform: uppercase !important; font-size: 16px !important; font-weight: bold !important; margin-top: 0.4rem;">
                            {!! preg_replace('/[0-9\[\]\.]/', '', $orderMain->order_main_title) !!}
                        </div>
                    </div>
                    {{-- Check if regulationtbl relation exists --}}
                    @if (!empty($orderMain->ordertbl))
                        <div style="text-align: start; margin-top: 0.4rem;">
                            {{-- Loop through each regulationtbl --}}
                            @foreach ($orderMain->ordertbl as $ordertblItem)
                                <table style="width: 100%; font-size: 15px !important;">
                                    <tr>
                                        <td class="section-no">
                                            <p>{{ $ordertblItem->orders_no }}</p>
                                        </td>
                                        <td class="section-title">
                                            <p>{!! $ordertblItem->orders_title !!}</p>
                                        </td>
                                    </tr>
                                </table>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            @endif
        @endforeach
    </div>  
    
    <div style=" padding: 50px 50px !important; page-break-before: always;">
        <div style="text-align: center; text-transform: uppercase !important;font-size: 24px !important;">
            {{ $combinedItems[0]->new_order_title }}</div>
        <div style="text-align: center; font-size: 15px !important; margin-top: 0.4rem;">{!! $combinedItems[0]->new_order_no !!}</div>
        <div style="font-size: 13px !important; text-align: right !important;">[{{ $combinedItems[0]->new_order_date }}]</div>
        <p style="font-size: 13px !important;">{!! $combinedItems[0]->new_order_description !!}</p>
    
        <hr style="width: 10% !important;margin: 10px auto !important;">
        

        <em class="footnote"
            style="padding-left: 2rem !important; font-size: 12px !important; margin-top: 0.4rem;">
            {!! $combinedItems[0]->new_order_footnote_description !!}</em>

            <div id="chapterPage">
                @foreach ($combinedItems as $NewOrder)
                    @if (isset($NewOrder->orderMain))
                
                        @foreach ($NewOrder->orderMain as $orderMain)
                            <div style="text-align: center">
                                <div style="text-transform: uppercase !important; font-size: 16px !important; margin-top: 0.4rem;">
                                    {!! $orderMain->order_main_title !!}
                                </div>
                            </div>
                            @if (!empty($orderMain->ordertbl))  
                            <div style="text-align: left">
                                    @foreach ($orderMain->ordertbl as $ordertblItem)
                                        <div style="font-size: 15px !important; margin-top: 25px !important; padding: 0; line-height: 0.9 !important;">
                                            <div style="margin: 0; padding: 0;">
                                                <p style="margin: 0;font-weight: bold;">{{ $ordertblItem->orders_no }}</p>
                                            </div>
                                            <div style="margin: 0; padding: 0;">
                                                <p style="margin: 0;font-weight: bold;">{!! $ordertblItem->orders_title !!}</p>
                                            </div>
                                        </div>
                                        <div style="padding-left: 18px;">
                                            <span style=" margin-bottom: 5px!important;">{!! $ordertblItem->orders_content !!}</span>
                                        </div>
                                
                                        @if (!empty($ordertblItem->orderSub))
                                            @foreach ($ordertblItem->orderSub as $orderSubItem)
                                                <div class="section-container" style="margin-top: 0.4rem; font-size: 15px !important; padding-left:25px!important;">
                                                    <div class="section-item sub-section-no ">{{ $orderSubItem->order_sub_no}}</div>
                                                    <div class="section-item sub-section-content" style="margin-top:-12px!important">{!! $orderSubItem->order_sub_content !!}</div>
                                                </div>
                                            @endforeach
                                        @endif
                                    
                                        @if (!empty($ordertblItem->orderFootnoteModel))
                                            @foreach ($ordertblItem->orderFootnoteModel as $footnoteModel)
                                                <div style="padding-left: 35px!important; font-size: 10px !important">
                                                    <em class="footnote" style="padding: 0 !important; margin: 0 !important; line-height: 1 !important; font-size: smaller;">
                                                        {!! $footnoteModel->footnote_content !!}
                                                    </em>
                                                </div>
                                            @endforeach
                                        @endif
                                
                                        <hr style="width: 10%; margin: 10px auto; border: none; border-top: 1px solid black;">
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    @endif  
                @endforeach 
            </div>    
        
    
