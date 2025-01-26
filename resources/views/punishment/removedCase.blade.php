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
                        <th width='100'> মামলার নম্বর </th>
                        <th width='100'> মামলার তারিখ </th>
                        <th width='100'> ঘটনাস্থল </th>
                        <th width='100'> প্রসিকিউটরের নাম ও পদবি </th>
                        <th width='100'> প্রথম সাক্ষীর নাম </th>
                        <th width='100'> দ্বিতীয় সাক্ষীর নাম </th>
                        <th width='100'> আইন ও ধারা </th>
                        <th width='100'> অপরাধীর নাম </th>
                        <th width='100'> অপরাধের ধরন </th>
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
    <link rel="stylesheet" type="text/css" href="{{ asset('/mobile_court/cssmc/registerList/registerCheckBox.css')}}" />

    <script type="text/javascript"
        src="{{ asset('/mobile_court/javascripts/source/register/DataTables-1.10.15/extensions/Responsive/js/dataTables.responsive.js') }}">
    </script>
    <script type="text/javascript"
        src="{{ asset('/mobile_court/javascripts/source/register/DataTables-1.10.15/extensions/RowGroup/js/dataTables.rowGroup.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('/mobile_court/javascripts/source/admin/showDeletedCaseBySystem.js') }}">
    </script>
@endsection