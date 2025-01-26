
@extends('layout.app')
@section('content')
<script  src="{{ asset('mobile_court/js/prosecution/extOrderSheet.js') }}" ></script>
<script>

function createConfession(){
    $("#confirmModal_conf").modal('show');
}
</script>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-custom">
                <div class="card-header">
                    <h2 class="card-title">মামলার তালিকা</h2>
                </div>
                <div class="card-body p-5 cpv">

                    <table class="table table-bordered table-striped table-info m-0" align="center">
                
                        <thead style="background-color: #008841; color: #fff;">

                            <tr>
                                <th>নং</th>
                                <th> মামলাসমূহ </th>
                                <th colspan="6" style="text-align: center">কার্যক্রম  </th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($prosecutions as $key => $row) 
                            <tr>
                               <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="p-2">
                                    <?php
                                        $ade = json_decode($row['subject'],true);

                                        if (json_last_error() === JSON_ERROR_NONE) {
                                            // JSON is valid
                                            // everything is OK
                                            //echo "everything is OK".$ade[0];
                                        }else{
                                        $ade[0] = $row['subject'];
                                        }
                                    ?>

                                    <ul class="list-inline">
                                        <li><b class="font-color">মামলা নম্বর</b>: {{ $row->case_no }}</li>
                                        <li><b class="font-color">তারিখ</b>: {{ $row->date }}</li>
                                        <li><b class="font-color">ঘটনাস্থল</b>: {{ $row->location }}</li>
                                        @if ($row->is_suomotu == "0")
                                            <li><b class="font-color">প্রসিকিউটর</b>: {{ $row->prosecutor_name }}</li>
                                        @elseif ($row->is_suomotu == "1")
                                            <li><b class="font-color">আদালতের নাম</b>: {{ $row->prosecutor_name }}</li>
                                        @endif
                                    </ul>
                                    <hr>

                                    <b class="font-color; font-face:NikoshBan">অভিযোগ</b>: @if ($row->hasCriminal == 0) <b>( আসামি ছাড়া )</b>@endif
                                    <?php
                                        echo "<br/>";

                                    $arr_length = count($ade);
                                    for ($x = 0; $x <$arr_length; $x++){
                                    echo $ade[$x];
                                    echo "<br/>";
                                    }
                                    ?>
                                    </div>
                                </td>
                                @if ($row->orderSheet_id != "")
                                    <td width="" style="text-align: center">
                                        <a href="{{ url('prosecution/extendOrderSheet/' . $row->id) }}" class="btn btn-success">
                                            আদেশ সংযোজন
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    
                    </table>
                    <div class="d-flex justify-content-center">
                      {{ $prosecutions->links('pagination::bootstrap-4') }}
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')

<script>
	function showSubject(value) {
		alert(value);
	}
</script>

@endsection