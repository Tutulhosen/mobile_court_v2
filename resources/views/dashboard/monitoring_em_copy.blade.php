@extends('layout.app')
@yield('style')
<link href="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />

@section('content')
    {{-- @include('dashboard.inc.icon_card') --}}

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

        .panel-heading-title {
            border: 1px solid #cecece;
            padding: 2px 20px
        }

        .panel-heading {

            position: relative;
            border-top-left-radius: 3px;
            border-top-right-radius: 3px;
            color: #222
        }

        .panel-title {
            .panel-title {
                margin-top: 0;
                margin-bottom: 0;
                font-size: 24px;
                color: inherit;
                line-height: normal;
            }
        }
    </style>


    <div class="panel panel-default">
        <div class="panel-body cpv cpv96">
            <div class="row">
                <div class="col-sm-9">
                    <div class="panel panel-warning-alt">
                        <div class="panel-heading bg-warning panel-heading-title">
                            <h4 class="panel-title ">দায়েরকৃত অভিযোগ</h4>
                        </div>
                        <div class="panel-body panel-dashboard cpv">
                            <div id="dynamic1">
                                <table class="display table-bordered table-striped" id="example" width="100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>নম্বর</th>
                                            <th>তারিখ</th>
                                            <th>ঘটনাস্থল</th>
                                            <th>অভিযোগ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5" class="dataTables_empty">Aoyonnnnnnnnnnnn</td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="divSpace"></div>
                    <div class="panel panel-success-alt">
                        <div class="panel-heading">
                            <h4 class="panel-title">বিভিন্ন দপ্তর হতে প্রাপ্ত আবেদন</h4>
                        </div>
                        <div class="panel-body cpv">
                            <div id="dynamic2">
                                <table class="table table-bordered table-striped" id="requisition" width="100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>নম্বর</th>
                                            <th>তারিখ</th>
                                            <th>ঘটনাস্থল</th>
                                            <th>অভিযোগ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5" class="dataTables_empty">Aoyonnnnnnnnnnn</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="divSpace"></div>
                    <div class="panel panel-danger-alt">
                        <div class="panel-heading">
                            <h4 class="panel-title">অপরাধের তথ্য</h4>
                            <div style="float: right">

                            </div>
                        </div>
                        <div class="panel-body cpv">
                            <div id="dynamic3">
                                <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                    class="display table-bordered table-striped" id="citizen">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>নম্বর</th>
                                            <th>তারিখ</th>
                                            <th>ঘটনাস্থল</th>
                                            <th>অভিযোগ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5" class="dataTables_empty">Aoyonnnnnnnnnnnnnnn</td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>

                    <div id="previewcitizencomplain" style="display: none;">
                        {{-- @include('magistrate.partials.citizencomplain') --}}
                    </div>
                    <div class="divSpace"></div>
                </div>

                <div class="col-sm-3">
                    <div class="panel panel-info-alt">
                        <div class="panel-heading">
                            <h4 class="panel-title">পরিসংখ্যান</h4>
                        </div>
                        <div class="panel-body cpv">
                            <div class="list-group m-0">
                                <div class="list-group-item">
                                    <span class="badge"></span>
                                    মোট পরিচালিত কোর্ট
                                </div>
                                <div class="list-group-item">
                                    <span class="badge"></span>
                                    মোট মামলার সংখ্যা
                                </div>
                                <div class="list-group-item">
                                    <span class="badge"></span>
                                    আদায়কৃত অর্থ
                                </div>
                                <div class="list-group-item">
                                    <span class="badge"></span>
                                    মোট আসামির সংখ্যা
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="divSpace"></div>
                    <div class="panel panel-warning-alt">
                        <div class="panel-heading">
                            <h4 class="panel-title">অপরাধের তথ্য</h4>
                        </div>
                        <div class="panel-body cpv">
                            <div class="list-group m-0">
                                <div class="list-group-item">
                                    <span class="badge"></span>
                                    মোট অভিযোগ
                                </div>
                                <div class="list-group-item">
                                    <span class="badge"></span>
                                    নিষ্পত্তিকৃত অভিযোগ সংখ্যা
                                </div>
                                <div class="list-group-item">
                                    <span class="badge"></span>
                                    অপেক্ষমান অভিযোগ সংখ্যা
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Styles and Scripts -->
    <link href="//cdn.datatables.net/responsive/1.0.1/css/dataTables.responsive.css" rel="stylesheet">
    <link href="{{ asset('css/style.datatables.css') }}" rel="stylesheet">

    <script src="//cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js"></script>
    <script src="//cdn.datatables.net/responsive/1.0.1/js/dataTables.responsive.js"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/magistrate/script.js') }}"></script>

    <div id="printRegister" style="display: none;">
        {{-- @include('magistrate.partials.register') --}}
    </div>



    {{-- <div class="container">
<!-- @if (2 == 2) -->
<div class="row">
    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
        <a href="">
            <div class="card card-custom bg-danger cardCustomBG bg-hover-state-danger card-stretch gutter-b">
                <div class="card-body" style="">
                    <div class="align-items-center justify-content-between card-spacer flex-grow-1">
                        <span class="symbol symbol-50 symbol-light-danger  mr-2">

                            <span class="text-light Count ml-5"></span>
                        </span>
                        <div class="text-left icn-card-label">
                            <span class="text-white  font-size-h3">মোট মামলা</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
        <a href="">
            <div class="card card-custom bg-success cardCustomBG bg-hover-state-success card-stretch gutter-b">
                <div class="card-body" style="">
                    <div class="align-items-center justify-content-between card-spacer flex-grow-1">
                        <span class="symbol symbol-50 symbol-light-success  mr-2">

                        </span>
                        <span class="text-light  Count ml-5"></span>
                        <div class="text-left icn-card-label">
                            <span class="text-white  font-size-h3">স্বপ্রণোদিত মামলা</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
        <a href="">
            <div class="card card-custom bg-primary cardCustomBG bg-hover-state-primary card-stretch gutter-b">
                <div class="card-body" style="">
                    <div class="align-items-center justify-content-between card-spacer flex-grow-1">
                        <span class="symbol symbol-50 symbol-light-primary  mr-2">

                        </span>
                        <span class="text-light  Count ml-5"></span>
                        <div class="text-left icn-card-label">
                            <span class="text-white  font-size-h3">অসম্পূর্ণ মামলা</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
        <a href="">
            <div class="card card-custom bg-primary cardCustomBG bg-hover-state-primary card-stretch gutter-b">
                <div class="card-body" style="">
                    <div class="align-items-center justify-content-between card-spacer flex-grow-1">
                        <span class="symbol symbol-50 symbol-light-primary  mr-2">

                        </span>
                        <span class="text-light  Count ml-5"></span>
                        <div class="text-left icn-card-label">
                            <span class="text-white font-size-h3">নিষ্পত্তিকৃত মামলা</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

</div>
</div> --}}

    {{-- <div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card card-custom">
                    <div class="card-header">
                        <div class="card-title ">
                            <h3 class="card-label font-weight-bolder text-dark h3">পরিসংখ্যান
                            </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item font-weight-bolder h6">
                                <i class="fas fa-gavel icon-lg text-danger mr-3"></i>মোট পরিচালিত কোর্ট
                                <span class="label label-inline label-danger font-weight-bold float-right h6" >44</span>
                            </li>
                            <li class="list-group-item font-weight-bolder h6"><i class="fas fa-gavel icon-lg text-danger mr-3"></i>মোট মামলার সংখ্যা
                                <span class="label label-inline label-danger font-weight-bold float-right h6" >0</span>
                            </li>
                            <li class="list-group-item font-weight-bolder h6"><i class="fas fa-gavel icon-lg text-danger mr-3"></i> আদায়কৃত অর্থ
                                <span class="label label-inline label-danger font-weight-bold float-right h6" >0  টাকা</span>
                            </li>
                            <li class="list-group-item font-weight-bolder h6"><i class="fas fa-gavel icon-lg text-danger mr-3"></i>মোট আসামির সংখ্যা
                                <span class="label label-inline label-danger font-weight-bold float-right h6" >10  জন</span>
                            </li>

                        </ul>
                    </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-custom">
                    <div class="card-header">
                        <div class="card-title ">
                            <h3 class="card-label font-weight-bolder text-dark h3">অপরাধের তথ্য</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item font-weight-bolder h6">
                                <i class="fas fa-gavel icon-lg text-danger mr-3"></i>মোট অভিযোগ
                                <span class="label label-inline label-danger font-weight-bold float-right h6" >4</span>
                            </li>
                            <li class="list-group-item font-weight-bolder h6"><i class="fas fa-gavel icon-lg text-danger mr-3"></i> নিষ্পত্তিকৃত অভিযোগ  সংখ্যা
                                <span class="label label-inline label-danger font-weight-bold float-right h6" >0</span>
                            </li>
                            <li class="list-group-item font-weight-bolder h6"><i class="fas fa-gavel icon-lg text-danger mr-3"></i>অপেক্ষমান অভিযোগ  সংখ্যা
                                <span class="label label-inline label-danger font-weight-bold float-right h6" >0</span>
                            </li>

                        </ul>

                    </div>
            </div>
        </div>
    </div>
</div> --}}
    <!-- <div class="container">
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
                                            <img data-src="holder.js/100x120" class="pull-left m-bottom-0 img-rounded home-img" src="http://training.ecourt.gov.bd/images/info.png" alt="Mobile Court">
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
                    <div class="row" style="display: none;">
                        <div class="col-xl-6">
                            <div class="card card-custom card-stretch gutter-b">
                                {{-- <figure class="highcharts-figure" style="width: 100%">
                    <div id="containerDrilldown"></div>
                </figure> --}}
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="card card-custom card-stretch gutter-b">
                                {{-- <figure class="highcharts-figure" style="width: 100%">
                    <div id="containerSectionStatistics"></div>
                </figure> --}}
                            </div>
                        </div>
                    </div> -->
@endsection

{{-- Includable CSS Related Page --}}
@section('styles')
    <!-- <link href="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" /> -->
    <!--end::Page Vendors Styles-->
@endsection

{{-- Scripts Section Related Page --}}
@section('scripts')
    <!-- <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

                    <script src="https://code.highcharts.com/highcharts.js"></script>
                    <script src="https://code.highcharts.com/modules/data.js"></script>
                    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
                    <script src="https://code.highcharts.com/modules/exporting.js"></script>
                    <script src="https://code.highcharts.com/modules/export-data.js"></script>
                    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
                    <script src="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
                    <script src="{{ asset('js/pages/widgets.js') }}"></script> -->
    <script>
        $('.Count').each(function() {
            var en2bnnumbers = {
                0: '০',
                1: '১',
                2: '২',
                3: '৩',
                4: '৪',
                5: '৫',
                6: '৬',
                7: '৭',
                8: '৮',
                9: '৯'
            };
            var bn2ennumbers = {
                '০': 0,
                '১': 1,
                '২': 2,
                '৩': 3,
                '৪': 4,
                '৫': 5,
                '৬': 6,
                '৭': 7,
                '৮': 8,
                '৯': 9
            };

            function replaceEn2BnNumbers(input) {
                var output = [];
                for (var i = 0; i < input.length; ++i) {
                    if (en2bnnumbers.hasOwnProperty(input[i])) {
                        output.push(en2bnnumbers[input[i]]);
                    } else {
                        output.push(input[i]);
                    }
                }
                return output.join('');
            }

            function replaceBn2EnNumbers(input) {
                var output = [];
                for (var i = 0; i < input.length; ++i) {
                    if (bn2ennumbers.hasOwnProperty(input[i])) {
                        output.push(bn2ennumbers[input[i]]);
                    } else {
                        output.push(input[i]);
                    }
                }
                return output.join('');
            }
            var $this = $(this);
            var nubmer = replaceBn2EnNumbers($this.text());
            jQuery({
                Counter: 0
            }).animate({
                Counter: nubmer
            }, {
                duration: 2000,
                easing: 'swing',
                step: function() {
                    var nn = Math.ceil(this.Counter).toString();
                    // console.log(replaceEn2BnNumbers(nn));
                    $this.text(replaceEn2BnNumbers(nn));
                }
            });
        });
    </script>
@endsection
