@extends('layout.app')
@section('content')
    <script src="{{ asset('js/prosecution/extOrderSheet.js') }}" type="text/javascript"></script>
    <style>
        .attachment-section {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
        }

        .attachment-section h5 {
            font-weight: bold;
            color: #333;
        }

        body {
            padding: 10px;
            background: #e5f3d4;

        }
        .thumbnail {
            position: relative;
            margin: 5px;
            float: left;
            border-radius: 3px;
            box-shadow: 1px 1px 5px #ccc;
            overflow: hidden;
            border: 1px solid #ccc;
        }

        .thumbnail>.img-label {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
            font-size: 0.8em;
            width: 50%;
            padding: 5px 10px;
            background: rgba(255, 255, 255, 0.75);
        }

        .img-button {
            position: absolute !important;
            z-index: 2 !important;
            right: 10px !important;
            color: #F44336;
            opacity: 1;
        }

        .img-thumbnail {
            /* padding: .25rem; */
            background-color: #fff;
            border: 1px solid #e4e6ef;
            /* border-radius: .42rem; */
            -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, .075);
            box-shadow: 0 1px 2px rgba(0, 0, 0, .075);
            max-width: 25%;
            height: auto;
        }
    </style>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom">

                    <div class="card-header smx">
                        <h4 class="card-title"> ফরমের তালিকা </h4>
                    </div>
                    <div class="card-body p-10 cpv">
                        <div class="clearfix">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <table width="100%">
                                        <tr>
                                            @php $counter = 1; @endphp
                                            @foreach ($prosecution as $it)
                                                <td class="p-right-15">
                                                    @php
                                                        $ade = $it->subject;
                                                        if (strlen($ade) > 600) {
                                                            $ade = substr($it->subject, 0, 600);
                                                        }
                                                    @endphp
                                                    <b class="font-color">মামলা নম্বর</b>:
                                                    {{ $it->case_no }}&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <b class="font-color">তারিখ</b>:
                                                    {{ $it->date }}&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <b class="font-color">ঘটনাস্থল</b>: {{ $it->location }} <br />
                                                    @if ($it->is_suomotu == '0')
                                                        <b class="font-color">প্রসিকিউটর</b>: {{ $it->prosecutor_name }}
                                                        <br />
                                                    @else
                                                        <b class="font-color">আদালতের নাম</b>: {{ $it->prosecutor_name }}
                                                        <br />
                                                    @endif
                                                    <hr>

                                                    <b class="font-color">অভিযোগ</b>:
                                                    <br />
                                                    @foreach ($crime_description as $value)
                                                        {!! $value !!}
                                                        @php $counter++; @endphp
                                                        <br />
                                                    @endforeach
                                                    <br>
                                                    <b class="font-color">ফাইল</b>:
                                                    <br />

                                                    @php $number = 1; @endphp
                                                    @foreach ($uploaded_file as $value)
                                                        <div class="row">
                                                            <div class="form-group col-xs-3 col-sm-3 col-md-3 col-lg-2">
                                                                {{ $number }}- নম্বর :</div>
                                                            <div class="form-group col-xs-3 col-sm-3 col-md-3 col-lg-2">
                                                                <img class="img-responsive img-thumbnail multi-image"
                                                                    src="/mobile_court/images/doc.png">
                                                            </div>
                                                            <div class="form-group col-xs-3 col-sm-3 col-md-3 col-lg-3">

                                                                @if ($value->FileCategory == 'ChargeFame')
                                                                    অভিযোগ গঠন সম্পর্কিত ফাইল
                                                                @elseif ($value->FileCategory == 'CriminalConfession')
                                                                    আসামির জবানবন্দি সম্পর্কিত ফাইল
                                                                @elseif ($value->FileCategory == 'OrderSheet')
                                                                    আদেশ প্রদান সম্পর্কিত ফাইল
                                                                @elseif ($value->FileCategory == 'ExtendedOrder')
                                                                    আদেশ সংযোজন সম্পর্কিত ফাইল
                                                                @endif
                                                            </div>
                                                            <div class="form-group col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                                <a style="color:#428BC9"
                                                                    href="{{ asset($value->FilePath) }}" download>
                                                                    <i class="fa fa-download" style="color:#428BC9"></i>
                                                                    Download
                                                                </a>
                                                            </div>
                                                        </div>
                                                        @php $number++; @endphp
                                                    @endforeach
                                                </td>
                                            @endforeach
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <ul class="list-inline " style="list-style-type: none; padding: 0;">
                                        @if ($is_suomotu != '1')
                                            <li class="m-bottom-10 " style="display: inline; margin-right: 10px;">
                                                <label class="btn btn-default active"
                                                    style="background-color:#21740c;color:#fff">
                                                    <input type="radio" id="initial" name="formtype" value="1"
                                                        onclick="hidecriminalStatus()" /> অভিযোগ দায়ের
                                                </label>
                                            </li>
                                        @endif
                                        <li class="m-bottom-10" style="display: inline; margin-right: 10px;">
                                            <label class="btn btn-default active"
                                                style="background-color:#21740c;color:#fff">
                                                <input type="radio" id="accepted" name="formtype" value="5"
                                                    onclick="hidecriminalStatus()" /> জব্দতালিকা
                                            </label>
                                        </li>
                                        <li class="m-bottom-10" style="display: inline; margin-right: 10px;">
                                            <label class="btn btn-default active"
                                                style="background-color:#21740c;color:#fff">
                                                <input type="radio" id="accepted" name="formtype" value="7"
                                                    onclick="hidecriminalStatus()" /> জিম্মানামা
                                            </label>
                                        </li>
                                        <li class="m-bottom-10" style="display: inline; margin-right: 10px;">
                                            <label class="btn btn-default active"
                                                style="background-color:#21740c;color:#fff">
                                                <input type="radio" id="accepted" name="formtype" value="4"
                                                    checked="checked" onclick="hidecriminalStatus()" /> আদেশনামা
                                            </label>
                                        </li>
                                        @if ($hasCriminal == 1)
                                            <li class="m-bottom-10" style="display: inline; margin-right: 10px;">
                                                <label class="btn btn-default active"
                                                    style="background-color:#21740c;color:#fff">
                                                    <input type="radio" id="accepted" name="formtype" value="2"
                                                        onclick="showcriminalStatus(2)" /> অভিযোগ গঠন
                                                </label>
                                            </li>
                                            <li class="m-bottom-10" style="display: inline; margin-right: 10px;">
                                                <label class="btn btn-default active"
                                                    style="background-color:#21740c;color:#fff">
                                                    <input type="radio" id="accepted" name="formtype" value="3"
                                                        onclick="showcriminalStatus(3)" /> আসামির স্বীকার উক্তি
                                                </label>
                                            </li>
                                            <li class="m-bottom-10" style="display: inline; margin-right: 10px;">
                                                <label class="btn btn-default active"
                                                    style="background-color:#21740c;color:#fff">
                                                    <input type="radio" id="accepted" name="formtype" value="6"
                                                        onclick="showcriminalStatus(6)" /> কয়েদী পরোয়ানা
                                                </label>
                                            </li>
                                            <li class="m-bottom-10" style="display: inline; margin-right: 10px;">
                                                <label class="btn btn-default active"
                                                    style="background-color:#21740c;color:#fff">
                                                    <input type="radio" id="accepted" name="formtype" value="8"
                                                        onclick="showcriminalStatus(8)" /> সোপর্দের পরোয়ানা
                                                </label>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <div class="form-group" id="criminalno" style="display: none">
                                <div class="col-sm-12">
                                    <label class="control-label"
                                        style="background-color:#21740c;color:#fff;width: 100%;padding-left: 5px;">আসামির
                                        তালিকা</label>
                                    <div class="p-left-30" style="">
                                        @foreach ($criminals as $criminal)
                                            {{-- <label class="radio">
                                                <input type="radio" name="criminalname" id="criminalname" value="{{ $criminal->id }}" /> {{ $criminal->name }}
                                            </label> --}}
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="criminalname"
                                                    id="criminalname" value="{{ $criminal->id }}">
                                                <label class="form-check-label" for="criminalname">
                                                    {{ $criminal->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="is_suomotu" name="is_suomotu" value="">

                    <div class="card-footer">
                        <div class="text-left">
                            <button class="btn btn-primary" id="saveComplain"
                                onclick="operationExecute({{ $id }})">প্রিন্ট</button>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <div id="messageModal">
        @include('message.partials.message')
    </div>
@endsection

<div id="complainSubmitReportWithOutCriminal" style="display:none;">
    @include('appeals.partials.complainSubmitReportWithOutCriminal')
</div>

<div id="prosecution" style="display: none;">
    @include('appeals.partials.prosecution')
</div>

<div id="criminalConfession_yes" style="display: none;">
    @include('appeals.partials.criminalConfession_yes')
</div>

<div id="complain_agree" style="display: none;">
    @include('appeals.partials.complain_agree')
</div>

<div id="orderSheet_punishment_table" style="display: none;">
    @include('appeals.partials.orderSheet_punishment_table')
</div>
<div id="jellWarrentall" style="display: none; ">
    @include('appeals.partials.jellWarrentall')
</div>

<div id="sizedList" style="display: none; ">
    @include('appeals.partials.sizedList')
</div>

<div id="jimmader" style="display: none;">
    @include('appeals.partials.jimmader')
</div>
<div id="handoverform" style="display: none; ">
    @include('appeals.partials.handoverform')
</div>



@section('scripts')
    <script src="{{ asset('mobile_court/js/select2.min.js') }}"></script>
    <script src="{{ asset('mobile_court/javascripts/source/utils/convertEngToBangla.js') }}"></script>
    <link href="{{ asset('mobile_court/cssmc/select2.css') }}" rel="stylesheet">
    <script src="{{ asset('mobile_court/javascripts/lib/custom_c.js') }}"></script>

    <script>
        function print_content(type) {
            var html_content = $('#' + type + "_print").html();
            var newwindow = window.open();
            var newdocument = newwindow.document;
            newdocument.write('<div id="' + type + '_print" class="content_form">' + html_content + '</div>');
            newdocument.close();
            newwindow.print();
            return false;
        }

        $(".form_id").select2();

        function showSubject(value) {
            alert(value);
        }
    </script>
@endsection
