
@extends('layout.app')
@section('content')
<script>
function createConfession() {
    $("#confirmModal_conf").modal('show');
}
</script>
<script src="{{ url('/js/prosecution/extOrderSheet.js') }}" type="text/javascript"></script>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-custom">
                <div class="card-header">
                        <h2 class="card-title">মামলার তালিকা</h2>
                </div>

                <div class="card-body cpv">
                <table class="table table-bordered table-striped table-info mb30" align="center">
                    <thead style="background-color: #008841;color: #fff;">
                    <tr>
                        <th> মামলাসমূহ </th>
                        <th colspan="7">কার্যক্রম  </th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($prosecutions as $it)
                        <tr>
                        <td>
                            <?php
                                $ade = json_decode($it['subject'],true);
                                if (json_last_error() === JSON_ERROR_NONE) {
                                    // JSON is valid
                                    // everything is OK
                                    //echo "everything is OK".$ade[0];
                                }else{
                                $ade[0] = $it['subject'];
                                }
                            ?>
                            <b class="font-color">মামলা নম্বর</b>: {{ $it->case_no }}&nbsp;&nbsp;&nbsp;&nbsp;
                            <b class="font-color">তারিখ</b>: {{ $it->date }}&nbsp;&nbsp;&nbsp;&nbsp;
                            <b class="font-color">ঘটনাস্থল</b>: {{ $it->location }} <br />
                                @if ($it->is_suomotu == "0")
                                    <b class="font-color">প্রসিকিউটর</b>: {{ $it->prosecutor_name }} <br />
                                @else
                                    <b class="font-color">আদালতের নাম</b>: {{ $it->prosecutor_name }} <br />
                                @endif
                            <hr>

                            <b class="font-color; font-face:NikoshBan">অভিযোগ</b>: @if ($it->hasCriminal == 0)<b>( আসামি ছাড়া )</b> @endif<?php
                                echo "<br/>";

                            $arr_length = count($ade);
                            for ($x = 0; $x <$arr_length; $x++){
                            echo $ade[$x];
                            echo "<br/>";
                            }
                            ?>
                        </td>
                        @if ($it->is_criminal_confession != null)
                            @if ($it->orderSheet_id != "")
                                <td width="12%">
                                    <a href="{{ url('prosecution/extendOrderSheet/' . $it->id) }}" class="btn btn-mideum btn-warning">আদেশ সংযোজন</a>
                                </td>
                            @else
                                <td width="12%">
                                    <a href="{{ url('prosecution/editOrderSheet/' . $it->id) }}" class="btn btn-mideum btn-warning">আদেশ প্রদান</a>
                                </td>
                            @endif
                        @else
                            @if ($it->hasCriminal == 0)
                                <td width="12%">
                                    <a href="{{ url('prosecution/editOrderSheet/' . $it->id) }}" class="btn btn-mideum btn-warning">আদেশ প্রদান</a>
                                </td>
                            @else
                                <td width="18%">
                                    <a href="{{ url('prosecution/editCriminalConfession/' . $it->id) }}" class="btn btn-mideum btn-warning">জবানবন্দি</a>
                                </td>
                            @endif
                        @endif

                       </tr>
                       @endforeach
                    </tbody>

                </table>
                <div class="d-flex justify-content-center">
                    {{ $prosecutions->links('pagination::bootstrap-4') }}

                </div>

                </div>

            </div>
        </div>
    </div>
</div>
<div class="modal fade " id="confirmModal_conf" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
                <div class="modal-body">
                    <h3>আসামির জবানবন্দি  নাই । </h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">বন্ধ করুন</button>
                </div>
        </div>
    </div>
</div>
<script>
    function showSubject(value) {
        alert(value);
    }
</script>
@endsection