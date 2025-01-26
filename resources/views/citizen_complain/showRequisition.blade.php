@extends('layout.app')
@section('content')
@push('head')
{{-- stylesheet_link('css/select2.css') --}}
{{-- javascript_include("js/select2.min.js") --}}
<link rel="stylesheet" href="{{ asset('mobile_court/cssmc/select2.css') }}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">

@endpush
@section('scripts')
<script src="{{ asset('mobile_court/js/select2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('mobile_court/js/admscript/script.js') }}" type="text/javascript"></script>
<script src="{{ asset('mobile_court/js/requisition/requisitionscript.js') }}" type="text/javascript"></script>

<!-- <script type="text/javascript" charset="utf8" src="{{ asset('vendors/datatables/js/jquery.dataTables.js') }}"></script> -->
<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<!-- <script type="text/javascript" charset="utf8" src="/vendors/datatables/js/jquery.dataTables.js"></script> -->
<!-- <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script> -->

@endsection
<style>
    .input-select {
        font-family: inherit;
        font-size: 16px;
        line-height: inherit;
        width: 150px !important;
    }
</style>

<div class="card panel-default">
    <div class="card-header smx" >
        <h4 class="card-title" style="margin: 0%">অপরাধের তথ্য</h4>
    </div>
    <!-- panel-heading -->
    <div class="card-body cpv">
        @foreach ($requisition as $it)
           
                <table class="table table-bordered table-striped table-info" align="center">
                <thead>
                    <tr>
                        <th>অভিযোগের বিবরণ</th>
                        <th>এক্সিকিউটিভ ম্যাজিস্ট্রেট</th>
                        <th colspan="7">কার্যক্রম</th>
                    </tr>
                </thead>
                <tbody>
            

            @if ($it->complain_status == 're-send')
            <!-- class="tooltip-show" data-toggle="tooltip" -->
                <tr style="background-color: #eaaeea;"  title="ম্যাজিস্ট্রেট কর্তৃক পুনর্বিবেচনার জন্য প্রেরিত">
            @else
                <tr>
            @endif
                <td>
                    @php
               
                        $ade = $it->subject;
                        if (strlen($ade) > 600) {
                            $ade = substr($it->subject, 0, 600) . " ...";
                          
                        }
                    @endphp
                    <!-- /* <a href='#' onclick='showSubject($it->subject); return false;'>বিস্তারিত</a> */ -->

                    <b class="font-color">অভিযোগ আইডি</b>: {{ $it->user_idno }}&nbsp;&nbsp;&nbsp;&nbsp;
                    <b class="font-color">তারিখ</b>: {{ $it->cdate }}&nbsp;&nbsp;&nbsp;&nbsp;<br/>
                    <b class="font-color">কার্যক্রম গ্রহণের সময়সীমা</b>: {{ $it->esdate }}&nbsp;&nbsp;&nbsp;&nbsp;
                    <b class="font-color">ঘটনাস্থল</b>: {{ $it->location }} <br/>
                    <b class="font-color">অভিযোগকারীর নাম</b>: {{ $it->name_bng }} <br/>
                    <hr>
                    <b class="font-color">অভিযোগ</b>: {!! $ade !!}
                </td>
                <td>
                    <select name="magistrate_id[{{ $it->id }}]" class="input input-select form-control" onchange="editRequisition(this.value,<?php echo $it->id;?>)">
                        <option value="">বাছাই করুন...</option>
                        @foreach($magistrate_id as $magistrate)
                            <option value="{{ $magistrate->id }}"  @if($magistrate->id ==$it->magistrate_id) selected @endif>{{ $magistrate->name_eng }}</option>
                        @endforeach
                    </select>
                </td>
                <td style="text-align: center">
                    <a href="#">
                        <img src="{{ asset('mobile_court/images/print.png') }}" onclick="printComplainFromList({{ $it->complain_id }}, '{{ $it->divname }}', '{{ $it->zillaname }}', '{{ $it->upazilaname }}');">
                    </a>
                </td>
            </tr>

          
                </tbody>
                <tbody>
                <tr>
                    {{--<td colspan="12" align="center">
                        <div class="btn-group">
                            <a href="{{ url('citizen_complain/showRequisition') }}" class="btn btn-primary"><i class="fa fa-angle-double-left"></i> প্রথম</a>
                            <a href="{{ url('citizen_complain/showRequisition?page=' . $page->before) }}" class="btn btn-primary"><i class="fa fa-angle-left"></i> আগে</a>
                            <a href="{{ url('citizen_complain/showRequisition?page=' . $page->next) }}" class="btn btn-primary"><i class="fa fa-angle-right"></i> পরে</a>
                            <a href="{{ url('citizen_complain/showRequisition?page=' . $page->last) }}" class="btn btn-primary"><i class="fa fa-angle-double-right"></i> শেষ</a>
                            <span class="help-inline">{{ $page->current }}/{{ $page->total_pages }}</span>
                        </div>
                    </td>--}}
                </tr>
                </tbody>
                </table>
             
        @endforeach

        @if ($requisition->isEmpty())
            <p>No citizen complaints are recorded</p>
        @endif
    </div>
</div>


<div id="admdetailsInfo" class="modal fade in" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">×</a>
        </div>
        <div class="modal-body">
            <table class="table table-bordered table-striped" align="center">
                <thead>
                    <tr>
                        <th colspan="4">অভিযোগকারীর বিবরণ</th>
                    </tr>
                </thead>
                <tr>
                    <td>অভিযোগকারীর নাম</td>
                    <td>মোবাইল</td>
                    <td colspan="2">অভিযোগ আইডি</td>
                </tr>
                <tr>
                    <td><input type="text" name="name" id="name" value="" readonly/></td>
                    <td><input type="text" name="cmp_mobile" id="cmp_mobile" value="" readonly/></td>
                    <td colspan="2"><input type="text" name="cmp_user_idno" id="cmp_user_idno" value="" readonly/></td>
                </tr>
                {{-- The following row is commented out, you can uncomment if needed --}}
                {{-- 
                <tr>
                    <td>ঘটনাস্থল</td>
                    <td colspan="3"> </td>
                </tr>
                --}}
                <tr>
                    <td>বিভাগ</td>
                    <td>জেলা</td>
                    <td>উপজেলা</td>
                    <td>স্থান</td>
                </tr>
                <tr>
                    <td><input type="text" name="cmp_divname" id="cmp_divname" value="" readonly/></td>
                    <td><input type="text" name="cmp_zillaname" id="cmp_zillaname" value="" readonly/></td>
                    <td><input type="text" name="cmp_upazilaname" id="cmp_upazilaname" value="" readonly/></td>
                    <td><input type="text" name="cmp_location" id="cmp_location" value="" readonly/></td>
                </tr>
            </table>
            <table class="table table-bordered table-striped" align="center">
                <thead>
                    <tr>
                        <th colspan="4">অভিযোগের বিবরণ</th>
                    </tr>
                </thead>
                <tr>
                    <td>বিষয়</td>
                    <td colspan="3"><input type="text" name="cmp_subject" id="cmp_subject" value="" readonly/></td>
                </tr>
                <tr>
                    <td>বিস্তারিত</td>
                    <td colspan="3"><input type="text" name="cmp_details" id="cmp_details" value="" readonly/></td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal">Close</a>
        </div>
    </div>
</div>



<div id="printComplain" style="display: none;">
    @include('citizen_complain.partials.citizen_complain')
</div>


<!-- <div id="dialog-confirm" style="display: none">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;">
    </span>আপনি কি এক্সিকিউটিভ ম্যাজিস্ট্রেটের নাম পরিবর্তন করতে চান ?</p>
</div> -->

<!-- <div id="dialog-message" title="Download complete" style="display: none">
    <p>
        <span id="mydialog" class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
    </p>

</div> -->

<script>
    // $(function () {
    //     $(function () {
    //         $('.tooltip-show').tooltip('ম্যজিস্ট্রেট কর্তৃক পুঃনবিবেচনার জন্য প্রেরিত ');
    //     });

    // });

    // function showSubject(value) {
    //     alert(value);
    // }

</script>

@endsection