@extends('layout.app')

@section('content')
<style type="text/css">
        fieldset {
            border: 1px solid #ddd !important;
            margin: 0;
            xmin-width: 0;
            padding: 10px;
            position: relative;
            border-radius: 4px;
            background-color: #d5f7d5;
            padding-left: 10px !important;
        }

        fieldset .form-label {
            color: black;
        }

        legend {
            font-size: 14px;
            font-weight: bold;
            width: 45%;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px 5px 5px 10px;
            background-color: #5cb85c;
        }

        .list-group-flush>.list-group-item {
            padding-left: 0;
        }

        /*highchart css*/

        .highcharts-figure,
        .highcharts-data-table table {
            /*min-width: 310px; */
            /*max-width: 1000px;*/
            /*margin: 1em auto;*/
        }

        #container {
            /*height: 400px;*/
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #EBEBEB;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            /*max-width: 500px;*/
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }

        /*Pie chart*/
        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 320px;
            max-width: 1030px;
            margin: 1em auto;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #EBEBEB;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }

        input[type="number"] {
            min-width: 50px;
        }

        .highcharts-credits {
            display: none;
        }
    </style>
        <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                    <div class="card card-custom">
                            <div class="card-header">
                                <div class="card-title ">
                                    <h3 class="card-label font-weight-bolder text-dark h3">মোবাইল কোর্টের উদ্দেশ্য
                                    </h3>
                                </div>
                                <div class="card-toolbar">

                                </div>
                            </div>
                            <div class="card-body">
                            <p class="font-weight-boldest">মোবাইল কোর্ট আইন, ২০০৯-এর লক্ষ্য এবং এই আইনের ৪ ধারা বিশ্লেষণ করলে মোবাইল কোর্টের উদ্দেশ্যসমূহ নিম্নরূপ প্রতিভাত হয়:</p>
                            <div class="media">
                                    <img data-src="holder.js/100x120" class="pull-left m-bottom-0 img-rounded home-img" src="{{ url('/images/info.png')}}" alt="Mobile Court">
                                    <div class="media-body">
                                        <ul class="newpadding">
                                            <li>ঘটনাস্থলে তাৎক্ষণিকভাবে অপরাধ আমলে নিয়ে ও দণ্ড আরোপ করে জননিরাপত্তা বিধান ও আইনশৃঙ্খলা
                                                রক্ষা;
                                            </li>
                                            <li>সামাজিক অপরাধ নিয়ন্ত্রণ;</li>
                                            <li>কার্যকরভাবে ও দক্ষতার সঙ্গে অপরাধ প্রতিরোধ করা;</li>
                                            <li>জনশৃঙ্খলা প্রতিষ্ঠা করা এবং</li>
                                            <li>জনসমক্ষে দণ্ড আরোপের মাধ্যমে অপরাধ প্রবণতা হ্রাস ও আইনের শাসন প্রতিষ্ঠা।</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                    </div>
                    </div>
                </div>
        </div>
          
</div> 


@endsection