@extends('layout.app')
@section('content')

@section('scripts')
<script src="{{ asset('mobile_court/js/admscript/script.js') }}" type="text/javascript"></script>
<!-- <script type="text/javascript" charset="utf8" src="{{ asset('vendors/datatables/js/jquery.dataTables.js') }}"></script> -->
@endsection

<div class="card panel-default">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="card-header smx">
        <h4 class="card-title">অপরাধের তথ্য</h4>
    </div>
    <!-- panel-heading -->
    <div class="card-body cpv p-15">
        @forelse ($page as $it)
            
                <table class="table table-bordered table-striped table-info m-0" align="center">
                <thead>
                    <tr>
                        <th>অভিযোগের বিবরণ</th>
                        <th colspan="7">কার্যক্রম</th>
                    </tr>
                </thead>
                <tbody>
        

                    <tr>
                        <td>
                            <div class="p-5">
                                @php
                                    $ade = $it->subject;
                                    if (strlen($ade) > 600) {
                                        $ade = substr($it->subject, 0, 600) . '... (<a href="#" onclick="showSubject(\'' . $it->subject . '\'); return false;">বিস্তারিত)</a>';
                                    }
                                @endphp
                                <ul class="list-inline">
                                    <li><b class="font-color">অভিযোগের আইডি</b>: {{ $it->user_idno }}</li>
                                    <li><b class="font-color">তারিখ</b>: {{ $it->complain_date }}</li>
                                    <li><b class="font-color">ঘটনাস্থল</b>: {{ $it->location }}</li>
                                    <li><b class="font-color">অভিযোগকারীর নাম</b>: {{ $it->name_bng }}</li>
                                </ul>
                                <hr />
                                <b class="font-color">অভিযোগ</b>: {!! $ade !!}
                            </div>
                        </td>
                        <td class="text-center">
                        
                            <a href="{{ url('requisition/editRequisition/' . $it->id) }}" class="btn btn-success">কার্যক্রম গ্রহণ</a>
                        </td>
                        <td class="text-center">
                       
                            <a href=" {{ url('citizen_complain/ignore_complain/'.$it->id) }}" class="btn btn-danger">বাতিল</a>
                        </td>
                    </tr>

             
                </tbody>
        
                </table>
                <div class="d-flex justify-content-center">
                    {!! $page->links('pagination::bootstrap-4') !!}
                </div>
        @empty
                অপরাধের তথ্য পাওয়া যায় নি
        @endforelse
    </div>
</div>

<script>
    function showSubject(value) {
        alert(value);
    }
</script>

<div id="admdetailsInfo" class="modal_new hide fade in" style="display: none;">
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
                <tr>
                    <td style="text-align:center;">
                    {{-- url('requisition/editRequisition/' . $it->id) --}}
                        <a href="" class="btn btn-mideum">গ্রহণ</a>
                    </td>
                    {{-- url('citizen_complain/ignore_complain/' . $it->id) --}}
                    <td style="text-align:center;">
                        <a href="" class="btn btn-mideum">বাতিল</a>
                    </td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal">Close</a>
        </div>
    </div>
</div>
@endsection