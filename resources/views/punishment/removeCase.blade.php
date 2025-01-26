@extends('layout.app')

@section('content')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/mobile_court/javascripts/source/register/DataTables-1.10.15/media/css/jquery.dataTables.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/mobile_court/javascripts/source/register/DataTables-1.10.15/extensions/RowGroup/css/rowGroup.dataTables.css') }}" />
    <div class="panel panel-default">
        <div class="panel-heading smx">
            <h2 class="panel-title-dashboard"> বাতিলকৃত মামলা </h2>
        </div>

        <div class="panel-body cpv">
            <table border="1" style="border-collapse:collapse;" cellpadding="2px" cellspacing="2px" width="100%"
                id ="dataTable">
                <thead>
                    <tr>
                        <th style="min-width: 200px"> মামলার নম্বর </th>
                        <th style="min-width: 100px"> মামলার তারিখ </th>
                        <th style="min-width: 120px"> ঘটনাস্থল </th>
                        <th style="min-width: 120px"> প্রসিকিউটরের নাম ও পদবি </th>
                        <th style="min-width: 120px"> প্রথম সাক্ষীর নাম </th>
                        <th style="min-width: 120px"> দ্বিতীয় সাক্ষীর নাম </th>
                        <th style="min-width: 120px"> আইন ও ধারা </th>
                        <th style="min-width: 120px"> অপরাধীর নাম </th>
                        <th style="min-width: 120px"> অপরাধের ধরন </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript"
        src="{{ asset('/mobile_court/javascripts/source/register/DataTables-1.10.15/media/js/jquery.dataTables.js') }}">
    </script>
    <script type="text/javascript"
        src="{{ asset('/mobile_court/javascripts/source/register/DataTables-1.10.15/extensions/Responsive/js/dataTables.responsive.js') }}">
    </script>
    <script type="text/javascript"
        src="{{ asset('/mobile_court/javascripts/source/register/DataTables-1.10.15/extensions/RowGroup/js/dataTables.rowGroup.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('/mobile_court/javascripts/source/admin/showDeletedCaseBySystem.js') }}">
    </script>
@endsection
