@extends('layout.app')
@section('content')
<style type="text/css">
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

    .dataTables_info{
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
            
                <div class="card-header smx">
                    <h2 class="card-title">মামলা / অভিযোগ অনুসন্ধান</h2>
                </div>
                <div class="card card-body cpv">
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="form-group">
                                <input class="radio-input " type="radio" name="case_complain" value="is_compID" onclick="ifCaseNumber(this.value)"/>   অপরাধের তথ্য আইডি
                                <input type="text" id="intext" name="complain_no" class="form-control" />
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <input class="radio-input" type="radio" name="case_complain" value="is_case" onclick="ifCaseNumber(this.value)" /> মামলার নম্বর
                                <input type="text" id="intext2" name="case_no" class="form-control" />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <br />
                                <div class="pull-right">
                                    <button class="btn btn-primary" type="button" name="casenothi" onclick="redrawTable();"><i class="glyphicon glyphicon-ok"></i>অনুসন্ধান </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">অভিযোগ  /  মামলার তথ্য</label>
                                <div id="dynamic">
                                    <table class="display table-bordered table-striped" id="magistrate_table" width="100%">
                                        <thead>
                                        <tr>
                                            <th>ম্যাজিস্ট্রেটের নাম  </th>
                                            <th>মামলা  নম্বর</th>
                                            <th>অপরাধের তথ্য নম্বর</th>
                                            <th>মামলার তারিখ </th>
                                            <th>আইন ও ধারা</th>
                                            <th>শাস্তি</th>
                                            <th>সর্বশেষ অবস্থা</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td colspan="7" class="dataTables_empty">Loading data from server</td>
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
<link href="{{ asset('mobile_court/cssmc/select2.css') }}" rel="stylesheet">
<link href="{{ asset('mobile_court/cssmc/bootstrap-timepicker.min.css') }}" rel="stylesheet">
<link href="{{ asset('mobile_court/cssmc/style.datatables.css') }}" rel="stylesheet">
<link href="//cdn.datatables.net/responsive/1.0.1/css/dataTables.responsive.css" rel="stylesheet">

<!-- Scripts -->
<script src="{{ asset('mobile_court/js/select2.min.js') }}"></script>
<script src="{{ asset('mobile_court/js/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('mobile_court/js/jquery-ui-1.10.3.min.js') }}"></script>
<script src="{{ asset('mobile_court/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('mobile_court/javascripts/source/register/DataTables-1.10.15/media/js/jquery.dataTables.js')}}"></script>
<script src="//cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script src="//cdn.datatables.net/responsive/1.0.1/js/dataTables.responsive.js"></script>

<!-- Custom script for magistrate page -->
<script src="{{ asset('mobile_court/js/magistrate/script.js') }}"></script>


<script>
    $(document).ready(function(){
            document.forms[0].elements["intext2"].disabled=true;
            document.forms[0].elements["intext"].disabled=true;
            $('input[type=text]').each(function(){ $(this).val(''); });
    });
</script>
@endsection
