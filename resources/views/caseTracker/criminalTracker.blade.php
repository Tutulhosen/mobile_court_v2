@extends('layout.app')
@section('content')
    <style type="text/css">
        body {
            padding: 10px;
            background: #e5f3d4;

        }

        /* Ensure the row uses flexbox for alignment */
        .row {
            display: flex !important;
            justify-content: space-between !important;
            /* Spread content to edges */
            width: 100% !important;
            align-items: center !important;
            /* margin-left: 50px */
        }


        /* Pagination Container */
        ul.pagination {
            display: flex !important;
            justify-content: flex-start !important;
            /* Align pagination items within their container */
            align-items: center !important;
            padding: 0 !important;
            list-style: none !important;
            margin: 20px 0 !important;
        }

        .dataTables_info {
            margin-left: 50px
        }

        /* Pagination Items */
        ul.pagination li {
            margin: 0 5px !important;
        }

        ul.pagination li a {
            display: block !important;
            padding: 10px 15px !important;
            font-size: 14px !important;
            color: #007bff !important;
            text-decoration: none !important;
            border: 1px solid #ddd !important;
            border-radius: 4px !important;
            transition: all 0.3s ease !important;
        }



        /* Hover Effect */
        ul.pagination li a:hover {
            background-color: #007bff !important;
            color: #fff !important;
        }

        /* Active Page */
        ul.pagination li.active a {
            background-color: #007bff !important;
            color: #fff !important;
            border-color: #007bff !important;
            pointer-events: none !important;
        }
    </style>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom">

                    <div class="card-header smx" style="padding: 10px;">
                        <h2 class="card-title">আসামি অনুসন্ধান</h2>
                        <p>আসামির নাম এবং বিভাগ, জেলা, উপজেলা অথবা মোবাইল নম্বর লিখে অনুসন্ধান করুন</p>
                    </div>
                    <div class="card card-body p-15 cpv">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">নাম</label>
                                    <input name="name_bng" id="name_bng" value="" class="form-control"
                                        type="text" />
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">বিভাগ</label>
                                    <select id="division" name="division" onchange="showZilla(this.value,'zilla')"
                                        tabindex="-1" title="" class="">
                                        <option value="">বাছাই করুন...</option>
                                        <?php foreach($divisions as $dv){?>
                                        <option value="<?php echo $dv->id; ?>"><?php echo $dv->division_name_bn; ?></option>
                                        <?php }?>
                                    </select>

                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">জেলা / সিটি কর্পোরেশন</label>
                                    <select id="zilla" name="zilla" onchange="showUpazila(this.value,'upazila')"
                                        tabindex="-1" title="" class="select2-offscreen">
                                        <option value="">বাছাই করুন...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">উপজেলা /ওয়ার্ড</label>
                                    <select id="upazila" name="upazila" class="input select2-offscreen" tabindex="-1"
                                        title="">
                                        <option value="">বাছাই করুন...</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">মোবাইল</label>
                                    <input name="mobile" class="form-control" id="mobile" value=""
                                        type="text" />
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <div class="form-group">
                                    <label class="control-label"> </label><br /><br />
                                    <div class="pull-right">
                                        <button class="btn btn-primary" type="button" name="casenothi"
                                            onclick="redrawCriminalTable();"><i class="fa fa-check"></i> অনুসন্ধান </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">আসামি তথ্য</label>
                                    <div id="dynamic">
                                        <table width="100%" class="display table table-bordered table-striped"
                                            id="criminal_table">
                                            <thead>
                                                <tr>
                                                    <th>আসামি নাম </th>
                                                    <th>মামলা নম্বর</th>
                                                    <th>মামলার তারিখ </th>
                                                    <th>আইন ও ধারা</th>
                                                    <th>অপরাধের বর্ণনা </th>
                                                    <th>শাস্তি - জরিমানা</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="6" class="dataTables_empty">Loading data from server
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div><!-- panel-body -->
                    <div class="card-footer">

                    </div><!-- panel-footer -->


                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <!-- Stylesheets -->
    <script src="{{ asset('mobile_court/javascripts/lib/custom_c.js') }}"></script>
    <link href="{{ asset('mobile_court/cssmc/select2.css') }}" rel="stylesheet">
    <link href="{{ asset('mobile_court/cssmc/bootstrap-timepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('mobile_court/cssmc/style.datatables.css') }}" rel="stylesheet">
    <link href="//cdn.datatables.net/responsive/1.0.1/css/dataTables.responsive.css" rel="stylesheet">

    <!-- Scripts -->
    <script src="{{ asset('mobile_court/js/select2.min.js') }}"></script>
    <script src="{{ asset('mobile_court/js/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('mobile_court/js/jquery-ui-1.10.3.min.js') }}"></script>
    <script src="{{ asset('mobile_court/js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript"
        src="{{ asset('mobile_court/javascripts/source/register/DataTables-1.10.15/media/js/jquery.dataTables.js') }}">
    </script>
    <script src="//cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js"></script>
    <script src="//cdn.datatables.net/responsive/1.0.1/js/dataTables.responsive.js"></script>

    <!-- Custom script for magistrate page -->
    <script src="{{ asset('mobile_court/js/magistrate/script.js') }}"></script>


    <script>
        $(document).ready(function() {
            jQuery("#division, #zilla, #upazila").select2();
        });
    </script>
@endsection
