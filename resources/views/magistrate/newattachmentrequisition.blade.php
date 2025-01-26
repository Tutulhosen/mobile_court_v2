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

        /* Disabled Buttons */
        ul.pagination li.disabled a {
            color: #aaa !important;
            pointer-events: none !important;
            border-color: #ddd !important;
            background-color: #f9f9f9 !important;
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

    <div class="row">
        <div class="col-md-12">

            <div class="card  ">
                <div class="card-header">
                    <h2 class="card-title"> অপরাধের তথ্যের তালিকা</h2>
                </div>
                <div class="card-body   cpv">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="dynamic">
                                <table class="table table-bordered" id="attachmentrequisition">

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card  ">
                <div class="card-header">
                    <h2 class="card-title">গ্রহণকৃত মামলার তালিকা </h2>
                </div>
                <div class="card-body  cpv">
                    <table class="table table-bordered" id="caselist">

                    </table>
                </div>
                <div class="card-footer">
                    <div class="pull-right">
                        <input value="সংযুক্তিকরণ" type="button" id="newsubmit" class="btn btn-success" />
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    <link rel="stylesheet" type="text/css" href="{{ asset('/mobile_court/cssmc/select2.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/mobile_court/cssmc/bootstrap-timepicker.min.css') }}" />
    <script type="text/javascript" src="{{ asset('/mobile_court/js/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/mobile_court/js/bootstrap-timepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/mobile_court/js/jquery-ui-1.10.3.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/mobile_court/js/jquery.validate.min.js') }}"></script>


    <link href="{{ asset('mobile_court/cssmc/style.datatables.css') }}" rel="stylesheet">
    <link href="//cdn.datatables.net/responsive/1.0.1/css/dataTables.responsive.css" rel="stylesheet">

    <script
        src="{{ asset('mobile_court/javascripts/source/register/DataTables-1.10.15/media/js/jquery.dataTables.min.js') }}">
    </script>
    <script src="//cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js"></script>
    <script src="//cdn.datatables.net/responsive/1.0.1/js/dataTables.responsive.js"></script>
    <script src="{{ asset('mobile_court/js/magistrate/script.js') }}"></script>
@endsection
