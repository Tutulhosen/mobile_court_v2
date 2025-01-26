@php
   $user = globalUserInfo();
   $roleID = globalUserInfo()->role_id;
@endphp

@extends('layout.app')

@section('content')

<style type="text/css">
   .tg {border-collapse:collapse;border-spacing:0;width: 100%;}
   .tg td{border-color:black;border-style:solid;border-width:1px;font-size:14px;overflow:hidden;padding:6px 5px;word-break:normal;}
   .tg th{border-color:black;border-style:solid;border-width:1px;font-size:14px;font-weight:normal;overflow:hidden;padding:6px 5px;word-break:normal;}
   .tg .tg-nluh{background-color:#dae8fc;border-color:#cbcefb;text-align:left;vertical-align:top}
   .tg .tg-19u4{background-color:#ecf4ff;border-color:#cbcefb;font-weight:bold;text-align:right;vertical-align:top}
</style>

<!--begin::Card-->
<div class="card card-custom">
   <div class="card-header flex-wrap py-5">

          <div class="container">
              <div class="row">
                  <div class="col-10"><h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3></div>
              
              </div>
          </div>

    
   </div>
   <div class="card-body">

      <div class="row">
        <div class="col-md-12" style="font-weight: bold">
            <h2>সাধারণ তথ্য</h2>
        </div>
      </div>
    <div class="row">
        <div class="col-md-6">
            <table class="table table-striped border">
                <thead>
                    
                </thead>
               <tbody>
                <tr>
                    <th scope="row">মামলা নং</th>
                    <td >{{ en2bn($case_details->case_no) ?? '-'}}</td>
                 </tr>
                <tr>
                    <!-- <th scope="row">মামলার ধারা</th>
                    <td >{{ $case_details->related_act ?? '-'}}</td> -->
                 </tr>
                 <tr>
                    <th scope="row">আবেদনের তারিখ</th>
                    <td >{{ en2bn($case_details->prosecution_date) ?? '-'}}</td>
                 </tr>
                 <tr>
                    <th scope="row">ঘটনাস্থল</th>
                    <td >{{ en2bn($case_details->location) }}</td>
                 </tr>
                 <tr>
                    <th scope="row">আদালতের নাম</th>
                    <td >{{ en2bn($case_details->prosecutor_name)}}</td>
                 </tr>
                 <tr>
                    <th scope="row">অভিযোগ</th>
                    
                    <td > 
                        @if($case_details->hasCriminal == 0)
                          ( আসামি ছাড়া )
                        @endif
                        {{  strip_tags($case_details->subject)  }}
                    </td>
                 </tr>
                 <!-- <tr>
                    <th scope="row">প্রাতিষ্ঠানের নাম</th>
                    <td >{{ $case_details->org_name ?? '-'}}</td>
                 </tr> -->
                 <!-- <tr>
                    <th scope="row">প্রাতিষ্ঠানের ধরণ</th>
                    <td >{{ $org_type ?? '-'  ?? '-' }}</td>
                 </tr> -->
                   <!-- <tr>
                     <th scope="row">দাবিকৃত অর্থের পরিমাণ</th>
                     <td >{{ en2bn($case_details->total_claim_amount) ?? '-'}} টাকা</td>
                  </tr> -->
                  <!-- <tr>
                     <th scope="row">দাবিকৃত অর্থের পরিমাণ (কথায়)</th>
                     <td >{{ $case_details->claim_in_text ?? '-'}} টাকা</td>
                  </tr> -->
                 
                  
                </tbody>
            </table>
            
          
            
        </div>

      <div class="col-md-6">
        <table class="table table-striped border">
            <thead>
                
            </thead>
           <tbody>
            <tr>
                <th scope="row">আদায়কৃত অর্থের পরিমাণ</th>
                <td >{{ en2bn($case_details->total_claim_amount) ?? '-'}} টাকা</td>
             </tr>
             <tr>
                <th scope="row">আদায়কৃত অর্থের পরিমাণ (কথায়)</th>
                <td >{{ $case_details->claim_in_text ?? '-'}} টাকা</td>
             </tr>
            <tr>
                <th scope="row">জেনারেল সার্টিফিকেট আদালত</th>
                <td >@php
                    if (isset($case_details->court_id)) {
                        echo DB::table('court')
                            ->where('id', $case_details->court_id)
                            ->first()->court_name;
                    }
                @endphp</td>
             </tr>
            
             <tr>
                <th scope="row">প্রতিষ্ঠান প্রতিনিধির নাম</th>
                <td >{{ $case_details->org_representative ?? '-'}}</td>
             </tr>
             <tr>
                <th scope="row">সর্বশেষ আদেশ এর তারিখ</th>
                <td >{{ en2bn($case_details->last_order_date) ?? '-'}}</td>
             </tr>
             @if (!empty($case_details->order_attached_file))
                <tr>
                    <th scope="row">আদেশ এর সংযুক্তি</th>
                    <td >
                        <a href="{{asset('/archive_attached_file/'. $case_details->order_attached_file)}}" target="_blank" class="btn btn-sm btn-success font-size-h5 float-left">
                            <i class="fa fas fa-file-pdf"></i>
                            <b>দেখুন</b>
                            
                        </a>
                    </td>
                </tr>

                <tr>
                    <th scope="row">সকল সংযুক্তি</th>
                    <td >
                        <a href="{{route('appeal.generate.pdf', encrypt($case_details->id))}}" target="_blank" class="btn btn-sm btn-success font-size-h5 float-left">
                            <i class="fa fas fa-file-pdf"></i>
                            <b>দেখুন</b>
                            
                        </a>
                    </td>
                </tr>
             @endif
               
              
             
              
            </tbody>
        </table>
           
      </div>
      
      
      {{-- <div class="col-md-12">
        <table class="table table-striped border">
           <thead>
               <th class="h3" scope="col" colspan="2">সংযুক্তি</th>
               
           </thead>
          <tbody>
             <tr>
                <td>
                   @forelse ($attachmentList as $key => $row)
                         <div class="form-group mb-2" id="deleteFile{{ $row->id }}">
                             <div class="input-group">
                                 <div class="input-group-prepend">
                                     <button class="btn bg-success-o-75" type="button">{{ en2bn(++$key) . ' - নম্বর :' }}</button>
                                 </div>
                                 
                                 <input readonly type="text" class="form-control" value="{{ $row->file_category ?? '' }}" />
                                 <div class="input-group-append">
                                     <a href="{{ asset($row->file_path . $row->file_headline) }}" target="_blank" class="btn btn-sm btn-success font-size-h5 float-left">
                                         <i class="fa fas fa-file-pdf"></i>
                                         <b>দেখুন</b>
                                         
                                      </a>
                                    
                                 </div>
                                 
                             </div>
                         </div>
                    @empty
                     <div class="pt-5">
                         <p class="text-center font-weight-normal font-size-lg">কোনো সংযুক্তি খুঁজে পাওয়া যায়নি</p>
                     </div>
                   @endforelse
                </td>
             </tr>
          </tbody>
       </table> 
     </div> --}}

    </div>
    <br>
   
   <br>


  
<!--end::Card-->

@endsection

{{-- Includable CSS Related Page --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<!--end::Page Vendors Styles-->
@endsection

{{-- Scripts Section Related Page--}}
@section('scripts')
<script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('js/pages/crud/datatables/advanced/multiple-controls.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
    integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    function generatePDF() {
        
        var element = document.getElementById('element-to-print');
    
        var opt = {
            margin: 1,
            filename: 'myfile.pdf',
            pagebreak: {
                avoid: ['tr', 'td']
            },
            image: {
                type: 'pdf',
                quality: 0.98
            },
            html2canvas: {
                scale: 2
            },
        };

        // New Promise-based usage:
        html2pdf().set(opt).from(element).save();
    }
</script>
<!--end::Page Scripts-->
@endsection



