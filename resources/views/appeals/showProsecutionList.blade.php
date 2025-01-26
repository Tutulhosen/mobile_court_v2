@extends('layout.app')

@section('content')
<style>


</style>
    <div class="row">

        <div class="col-md-12">
            <!--begin::Card-->
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title h2 font-weight-bolder">{{ $page_title}}</h3>
                </div>
 
            <div class="card-body overflow-auto">
            
     
               <table class="table  table-bordered table-striped table-info mb30" id="prosecutionList">
                    <thead class="thead-customStyle2 font-size-h6">
                        <tr style="text-align: justify">
                            <th style="width: 5%">ক্রমিক নং</th>
                            <th style="width: 30%">মামলা নম্বর</th>
                            <th style="width: 30%">তারিখ</th>
                            <th style="width: 20%">ঘটনাস্থল</th>
                            <th style="width: 60%">প্রসিকিউটর</th>
                            <th style="width: 60%">অভিযোগ</th>
							<th style="width: 10%">অভিযোগনামা</th>
							<th style="width: 5%">আদেশনামা   </th>
							<th style="width: 10%">জব্দতালিকা   </th>
							<th style="width: 15%">তথ্য পরিবর্তন করুন</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($prosecutions as $key => $row)      
                        <?php 


                        $ade = json_decode($row->subject,true);

                        if (json_last_error() === JSON_ERROR_NONE) {
                        // JSON is valid
                        // everything is OK
                        //echo "everything is OK".$ade[0];
                        }else{
                        $ade[0] = $row->subject;
                        }


                    
                        ?>                
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->case_no }}</td>
                            <td>{{ $row->date }}</td>
                            <td>{{ $row->location }}</td>
                            <td>{{ $row->prosecutor_name }}</td>
                            <td><?php
                                echo "<br/>";

                            $arr_length = count($ade);
                            for ($x = 0; $x <$arr_length; $x++){
                            echo $ade[$x];
                            echo "<br/>";
                            }
                            ?></td>
                            <td>
                            @if($row->is_approved == "0")
                               @if($row->case_status>=3)
                                অভিযোগনামা প্রিন্ট
                                <a href="javascript:void(0);" data-toggle="tooltip" onclick="printProsecutionFromList(<?php echo $row->id ?>, <?php echo $row->hasCriminal?>);" title="Print" class="tooltips">
                                    <i class="fa fa-print"></i>
                                </a>
                                @else
                                নাই      
                                {{-- 
                                অভিযোগনামা প্রিন্ট
                                <a href="javascript:void(0);" data-toggle="tooltip" onclick="printProsecutionFromList(<?php echo $row->id ?>,<?php echo $row->hasCriminal ?>);" title="Print" class="tooltips">
                                    <i class="fa fa-print"></i>
                                </a>
                                --}}
                                @endif
                            @endif 

                            </td>
                            <td>
                            <?php if($row->is_orderSheet==""){?>
                               নাই 
                            <?php }else{?>
                               আছে 
                            <?php }?>
                            </td>
                            <td>
                                @if($row->is_seizurelist == "")
                                    জব্দতালিকা নাই
                                @else
                                 
                                        জব্দতালিকা প্রিন্ট
                                        <br>
                                        <a href="javascript:void(0);" data-toggle="tooltip" onclick="printseizurelist(<?php echo  $row->id?>, 5, 0);" title="Print" class="tooltips">
                                            <i class="fa fa-print"></i>
                                        </a>
                                   
                                @endif
                                </td>

                            
                            <!-- <?php if($row->is_approved==""){?>
                                <td align="center">জব্দতালিকা নাই</td>
                                
                            <?php }else{?>
                                <td align="center">অভিযোগ গঠন  সম্পূর্ণ হয়েছে ।পরিবর্তন করা যাবে না ।</td>
                            <?php }?> -->
                             
                      
                             <td>
                                <?php if ($row->is_approved==0){?>

                                @if($row->status==2)
                                    কোর্টবন্ধ।
                                @else
                                <a href="{{ url('prosecution/newProsecution/' . $row->id . '?prosecution_id=' . $row->id . '&magistrateid=' . $row->magistrateid . '&step=2') }}" class="btn btn-mideum btn-danger">পরিবর্তন</a>

                                {{-- Uncomment the following if needed --}}
                                {{-- <a href="{{ url('prosecution/editProsecutionformMain/' . $it->id) }}" class="btn btn-mideum btn-danger">অভিযোগ</a> --}}
                                {{-- <a href="{{ url('prosecution/editProsecutionformCriminal/' . $it->id) }}" class="btn btn-mideum btn-danger">আসামি</a> --}}

                                        @if ($row->is_seizurelist)
                                            <a href="{{ url('prosecution/editSeizedList/' . $row->id) }}" class="btn btn-mideum btn-danger mt-2">জব্দতালিকা পরিবর্তন</a>
                                        @endif

                                @endif
                                <!-- <div class="btn-group float-right">
                                    <button class="btn btn-primary font-weight-bold btn-sm dropdown-toggle" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">পদক্ষেপ</button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item"
                                            href="{{ route('proceutions.details',$row->id) }}">বিস্তারিত তথ্য</a>
                                        
                                    </div>
                                </div> -->
                                <?php }else{?>
                                    অভিযোগ গঠন  সম্পূর্ণ হয়েছে ।পরিবর্তন করা যাবে না ।
                                <?php }?>
                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
    
                <div class="d-flex justify-content-center">
                {!! $prosecutions->links('pagination::bootstrap-4') !!}

                </div>
            </div>

            </div>
        </div>
  
    </div>





<div id="printProsecution" style="display: none; ">
    @include('appeals.partials.prosecution')
</div>
<div id="complainSubmitReportWithOutCriminal" style="display: none; ">
  
    @include('appeals.partials.complainSubmitReportWithOutCriminal')
</div>

<div id="sizedList" style="display: none; ">
 
    @include('appeals.partials.sizedList')
</div>

<div id="messageModal">
    @include('message/partials/message')
</div>
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

@endsection
@section('scripts')
<script  src="{{ asset('mobile_court/javascripts/source/prosecution/seizureListInfoForm.js') }}" ></script>

<script>
    function showNotAllowMessage(){
        $("#NotAllowMessageModal").modal('show');
    }
    function printseizurelist(prosecutionId,a,b) {
        var url = "/prosecution/showFormByProsecution?prosecutionId=" + prosecutionId;
        $.ajax({
            url: url,
            success: function (data) {
                if (data) {
                  
                    if (data.seizurelist.length > 0) {
               
                        prepareReport(data);
                        var reportContent = $('#sizedList').html();
                        newwindow = window.open();
                        newdocument = newwindow.document;
                        newdocument.write(reportContent);
                        newdocument.close();

                        newwindow.print();
                        return false;
                    }
                }
            },
            error: function () {
            },
            complete: function () {
            }
        });
    }
    
    function printProsecutionFromList(id,hasCriminal) {

        var url =  "/prosecution/showFormByProsecution?form_id=" + 1 + "&id=" + id + "&suomotu=" + 0;
        $.ajax({url:url,type: 'POST',

                success:function (data) {
                      
                    if (data) {
                        var html_content='';
                        if(hasCriminal==1){
                     
                            if(data.caseInfo.prosecutorInfo[0].designation_bng !="প্রসিকিউটর"){
                                p_setcrimieDescriptionWC(data.caseInfo.prosecution,data.caseInfo.criminalDetails,data.caseInfo.prosecutionLocationName,data.caseInfo.lawsBrokenList,data.caseInfo.lawsBrokenList);
                                p_setParamsForcriminalWC(data.caseInfo.criminalDetails,data.caseInfo.lawsBrokenList);
                            }else{
                                p_setcrimieDescriptionWC(data.caseInfo.prosecution,data.caseInfo.criminalDetails,data.caseInfo.prosecutionLocationName,data.caseInfo.lawsBrokenListWithProsecutor,data.caseInfo.lawsBrokenListWithProsecutor);
                                p_setParamsForcriminalWC(data.caseInfo.criminalDetails,data.caseInfo.lawsBrokenListWithProsecutor);
                            }
                          
                          
                            p_setParamsForProsecution(data.caseInfo.prosecution);
                          
                            p_setParamsForSizedList(data.caseInfo.seizurelist);
                            p_setParamsForjobdescription(data.caseInfo.prosecutorInfo);
                            p_setParamsFormagistraten(data.caseInfo.magistrateInfo);
                            p_setParamsForLocation(data.caseInfo.prosecutionLocationName);
                       
                            p_setParamsForProsecutor(data.caseInfo.prosecutorInfo);
                            html_content = $('#printProsecution').html();
                        }else {
                           
                            if(data.caseInfo.prosecutorInfo[0].designation_bng !="প্রসিকিউটর"){
                                p_setcrimieDescriptionWOC(data.caseInfo.prosecution, data.caseInfo.criminalDetails, data.caseInfo.prosecutionLocationName, data.caseInfo.lawsBrokenList, data.caseInfo.lawsBrokenList);
                                p_setParamsForcriminalWOC(data.caseInfo.criminalDetails, data.caseInfo.lawsBrokenList);
                            }else{
                                p_setcrimieDescriptionWOC(data.caseInfo.prosecution,data.caseInfo.criminalDetails,data.caseInfo.prosecutionLocationName,data.caseInfo.lawsBrokenListWithProsecutor,data.caseInfo.lawsBrokenListWithProsecutor);
                                p_setParamsForcriminalWOC(data.caseInfo.criminalDetails,data.caseInfo.lawsBrokenListWithProsecutor);
                            }


                            p_setParamsForProsecutionWOC(data.caseInfo.prosecution);
                            p_setParamsForSizedListWOC(data.caseInfo.seizurelist);
                            p_setParamsForjobdescriptionWOC(data.caseInfo.prosecutorInfo);
                            p_setParamsFormagistratenWOC(data.caseInfo.magistrateInfo);
                            p_setParamsForLocationWOC(data.caseInfo.prosecutionLocationName);

                            p_setParamsForProsecutorWOC(data.caseInfo.prosecutorInfo);
                            html_content = $('#complainSubmitReportWithOutCriminal').html();
                        }


                        newwindow=window.open();
                        newdocument=newwindow.document;
                        newdocument.write(html_content);
                        newdocument.close();

                        newwindow.print();
                        return false;

                    }
                },
                error:function () {
                },
                complete:function () {
                }
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
@endsection
 
 
