@extends('layout.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
          <div class="card card-custom">
                <div class="card-header">
                        <h2 class="card-title">দায়েরকৃত অভিযোগ</h2>
                </div>
   
                <div class="card-body cpv">
                    @if($prosecutions->count())
                        <table class="table table-bordered table-striped table-info mb30" id="prosecutionList">
                            <thead style="background-color: #008841;color: #fff;">
                                <tr>
                                    <th>দায়েরকৃত অভিযোগ</th>
                                    <th>জব্দতালিকা</th>
                                    <th>আদেশনামা</th>
                                    <th>মামলার তথ্য পরিবর্তন করুন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($prosecutions as $prosecution)
                                    <tr>
                                        <td>
                                            @php
                                                $ade = json_decode($prosecution->subject, true);
                                                if (json_last_error() !== JSON_ERROR_NONE) {
                                                    $ade = [$prosecution->subject];
                                                }
                                            @endphp

                                            <b class="font-color">মামলা নম্বরঃ</b>{{ $prosecution->case_no }} &nbsp;&nbsp;
                                            <b class="font-color">তারিখঃ</b>{{ $prosecution->date }}</br>
                                            <b class="font-color">ঘটনাস্থলঃ</b>{{ $prosecution->location }} &nbsp;&nbsp;&nbsp;&nbsp;</br>
                                            <b class="font-color">বিষয়ঃ </b><b class="font-color; font-face:NikoshBan">অভিযোগ</b>:
                                            
                                            @foreach($ade as $index => $item)
                                                {{ $index + 1 }} নম্বর - {{ $item }} 
                                            @endforeach
                                        </td>

                                        <td>{{ $prosecution->is_seizurelist ? 'আছে' : 'নাই' }}</td>
                                        <td>{{ $prosecution->is_orderSheet ? 'আছে' : 'নাই' }}</td>

                                        <td>
                                            <a href="{{ url('prosecution/suomotucourtWithoutCriminal/'.$prosecution->id.'?prosecution_id='.$prosecution->id.'&case_number='.$prosecution->case_no.'&step='.($prosecution->is_seizurelist ? 3 : 2)) }}" class="btn btn-mideum btn-danger">
                                                পরিবর্তন
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center">
                          {!! $prosecutions->links('pagination::bootstrap-4') !!}
                        </div>
                    @else
                        <p>No   recorded</p>
                    @endif
                </div><!-- panel-body -->

           </div>
        </div>
    </div>   
</div>

@endsection


<script>
    function showNotAllowMessage(){
        $("#NotAllowMessageModal").modal('show');
    }


    function printProsecutionFromList(id) {

        var url =  base_path + "/prosecution/getProsecutionforPrint?id="+id;
        $.post(url, function (data) {
        })
            .success(function (data) {
                if (data) {
//                        setParams(data);
//
//                        var html_content = $('#printProsecution').html();
//
//
//                        newwindow = window.open();
//                        newdocument = newwindow.document;
//                        newwindow.document.write('<title>অভিযোগ </title>');
//                        newdocument.write(html_content);
//                        newdocument.close();
//
//                        newwindow.print();
//                        return false;

                    pro_setParamsForProsecution(data.prosecution);
                    pro_setParamsForcriminal(data.criminal);
                    pro_setParamsForSizedList(data.seizurelist);
                    pro_setParamsForjobdescription(data.jobdescription);
                    pro_setParamsFormagistraten(data.magistrate);
                    pro_setParamsForLocation(data.pro_location);
                    pro_setParamsForLaw(data.lawList);
                    pro_setParamsForSection(data.sectionList);
                    pro_setParamsForProsecutor(data.prosecutor);

                    var html_content = $('#printProsecution').html();

                    newwindow=window.open();
                    newdocument=newwindow.document;
                    newdocument.write(html_content);
                    newdocument.close();

                    newwindow.print();
                    return false;

                }
            })
            .error(function () {
            })
            .complete(function () {
            });

    }
    jQuery(document).ready(function() {
        /*jQuery(".scrollbox1").scrollbar({
            height: 355,
            axis: 'y'
        });*/

    });
</script>

<div id="NotAllowMessageModal" class="modal hide fade in" style="display: none; width: 300px;!important">
    <div class="modal-header">
        <a class="close" data-dismiss="modal" >×</a>
    </div>
    <div class="modal-body">
        <p> অভিযোগ গঠন  সম্পূর্ণ হয়েছে । অভিযোগটি পরিবর্তন করা যাবে না ।</p>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn btn-mideum" data-dismiss="modal">বন্ধ করুন</a>
    </div>
</div>