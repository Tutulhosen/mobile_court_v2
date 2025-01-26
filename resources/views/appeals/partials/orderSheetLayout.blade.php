<!-- <script src="{{ asset('mobile_court/javascripts/lib/jquery/jquery-3.2.1.min.js') }}"></script> -->

@extends('layout.app')
@yield('style')

@section('content')
    <section class="magistrate">
        <div class="mainwrapper">
            <div class="mainpanel">
                <div class="contentpanel">
                    <style>
                        .noneditable {
                            background-color: yellow;
                        }

                        .editable {
                            background-color: lightgreen;
                        }

                        .para {
                            text-indent: 32px;
                        }

                        .content_form {
                            /*min-height: 842px;*/
                            /*width: 595px; A4 */
                            width: 612px;
                            margin-left: auto;
                            margin-right: auto;
                            border: 1px dotted gray;
                            font-family: nikoshBan;
                            text-align: justify;
                            text-justify: inter-word;

                        }

                        .underline {
                            text-decoration: underline;
                        }

                        @media print {
                            .content_form {
                                border: 0px dotted;
                            }
                        }

                        p.p_indent {
                            text-indent: 30px;
                        }

                        p {
                            text-align: justify;
                            display: block;
                            /* -webkit-margin-before: 1em; */
                            /* -webkit-margin-after: 1em; */
                            -webkit-margin-start: 0px;
                            -webkit-margin-end: 0px;

                        }

                        h3 {
                            text-align: center;
                        }

                        h3.top_title_2nd {
                            margin-top: -18px;
                        }

                        .clear_div {
                            clear: both;
                            width: 100%;
                            height: 20px;
                        }

                        br {
                            line-height: 20px;
                        }

                        .btn-save {
                            background-color: #4CAF50;
                            border: none;
                            color: white;
                            padding: 5px 15px;
                            text-align: center;
                            text-decoration: none;
                            display: inline-block;
                            font-size: 16px;
                            margin: 4px 2px;
                            cursor: pointer;
                            border-radius: 4px;
                        }

                        .btn-cancel {
                            background-color: #d43f3a;
                            border: none;
                            color: white;
                            padding: 5px 15px;
                            text-align: center;
                            text-decoration: none;
                            display: inline-block;
                            font-size: 16px;
                            margin: 4px 2px;
                            cursor: pointer;
                            border-radius: 4px;
                        }
                    </style>
                    <script>
                        var prosecutionId = JSON.parse('<?php echo JSON_encode($prosecutionId); ?>');
                    </script>


                    <div class="content_form">
                        <input type="hidden" id="ProsecutionID" value="{{ $prosecutionId }}" />
                        <div id="newhead"></div>
                        <div id="newbody"></div>


                    </div>

                    <div style="margin-left: 58%">
                        <button class="btn-cancel" type="button" onclick="window.history.back();"> বাতিল </button>
                        <button class="btn-save" type="button" onclick="orderTemplate.saveOrdersheet();">সংরক্ষণ
                            করুন</button>
                    </div>

                </div>
            </div>
        </div>
        </div>

        <script src="{{ asset('plugins/global/plugins.bundle.js') }}"></script>
        <script src="{{ asset('mobile_court/javascripts/source/utils/convertEngToBangla.js') }}"></script>
        <script src="{{ asset('mobile_court/javascripts/source/prosecution/orderSheetTemplate.js') }}"></script>
    @endsection
