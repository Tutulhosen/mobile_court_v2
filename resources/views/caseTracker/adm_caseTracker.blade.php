@extends('layout.app')
@section('content')
    <style>
        body {
            padding: 10px;
            background: #e5f3d4;

        }
    </style>
    <form method="post" name="casedeleteform" id="casetrackerform" action="profile_adm/searchcasedetailsfroadm"
        name="casetrackerform">
        <div class="card panel-default">
            <div class="card-header smx">
                <h2 class="panel-title">অনুসন্ধান</h2>
            </div>
            <div class="card-body cpv p-7">
                <div class="row">
                    <!-- Executive Magistrate Selection -->
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <div class="radio-inline d-flex align-items-center my-2">
                                <input class="radio-input" type="radio" name="case_complain" value="is_magistrate"
                                    onclick="ifCaseNumber(this.value)" checked />
                                <span class="ml-2">এক্সিকিউটিভ ম্যাজিস্ট্রেট অনুযায়ী</span>
                            </div>
                            <select id="magistrate" name="magistrate" class="form-control select2-offscreen" tabindex="-1"
                                title="">
                                <option value="">{{ __('বাছাই করুন...') }}</option>
                                @foreach ($magistrate as $mag)
                                    <option value="{{ $mag->id }}">{{ $mag->name_eng }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Complain ID Input -->
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <div class="radio-inline d-flex align-items-center my-2">
                                <input class="radio-input" type="radio" name="case_complain" value="is_compID"
                                    onclick="ifCaseNumber(this.value)" />
                                <span class="ml-2">অপরাধের তথ্য আইডি</span>
                            </div>
                            <input type="text" id="intext" class="form-control" name="complain_no"
                                placeholder="Enter Complain ID" />
                        </div>
                    </div>

                    <!-- Case Number Input -->
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <div class="radio-inline d-flex align-items-center my-2">
                                <input class="radio-input" type="radio" name="case_complain" value="is_case"
                                    onclick="ifCaseNumber(this.value)" />
                                <span class="ml-2">মামলার নম্বর</span>
                            </div>
                            <input type="text" id="intext2" class="form-control" name="case_no"
                                placeholder="Enter Case Number" />
                        </div>
                    </div>

                    <!-- Date Selection -->
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 mt-2">
                        <label class="d-block">তারিখ</label>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 form-group">
                                <input class="form-control" name="start_date" id="start_date" type="text"
                                    placeholder="প্রথম তারিখ" />
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 form-group">
                                <input class="form-control" name="end_date" id="end_date" type="text"
                                    placeholder="শেষ তারিখ" />
                            </div>
                        </div>
                    </div>
                </div>


            </div><!-- panel-body -->
            <div class="card-footer">
                <div class="pull-right">
                    <button class="btn btn-primary" type="button" name="casenothi" onclick="redrawTable();"><i
                            class="glyphicon glyphicon-ok"></i> অনুসন্ধান </button>
                </div>
            </div><!-- panel-footer -->
        </div><!-- panel -->
    </form>
    <div class="card panel-primary">
        <div class="card-header smx">
            <h2 class="panel-title">অভিযোগ অথবা মামলার তথ্য</h2>
        </div>
        <div class="card-body cpv">
            <div class="row">
                <div class="col-md-12">
                    <div id="dynamic">
                        <table cellpadding="0" cellspacing="0" border="0"
                            class="dispaly table-bordered table-striped table-info mb30" id="my_tabletrac" width="100%">
                            <thead>
                                <tr>
                                    <th>ম্যাজিস্ট্রেটের নাম </th>
                                    <th>মামলা নম্বর</th>
                                    <th>অপরাধের তথ্য নম্বর</th>
                                    <th>মামলার তারিখ </th>
                                    <th>আইন ও ধারা</th>
                                    <th>শাস্তি</th>
                                    <th>সর্বশেষ অবস্থা</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="dataTables_empty">Loading data from server</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <link rel="stylesheet" type="text/css" href="{{ asset('/mobile_court/cssmc/style.datatables.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/mobile_court/cssmc/select2.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/mobile_court/cssmc/bootstrap-timepicker.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/mobile_court/cssmc/jquery-ui-1.10.3.css') }}" />

    <link href="//cdn.datatables.net/responsive/1.0.1/css/dataTables.responsive.css" rel="stylesheet">
    <link type="text/css" href="{{ asset('/mobile_court/cssmc/jquery-ui-1.11.0.min.css') }}" rel="stylesheet">

    <script type="text/javascript"
        src="{{ asset('/mobile_court/javascripts/source/register/DataTables-1.10.15/media/js/jquery.dataTables.min.js') }}">
    </script>
    <script src="//cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js"></script>
    <script src="//cdn.datatables.net/responsive/1.0.1/js/dataTables.responsive.js"></script>

    <script src="{{ asset('mobile_court/js/jquery-ui-1.11.0.min.js') }}"></script>
    <script src="{{ asset('mobile_court/js/select2.min.js') }}"></script>
    <script src="{{ asset('mobile_court/js/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('mobile_court/js/jquery-ui-1.10.3.min.js') }}"></script>

    <script src="{{ asset('mobile_court/js/admscript/casetrackerscript.js') }}"></script>





    {{-- javascript_include("js/jquery.validate.min.js") --}}
@endsection
