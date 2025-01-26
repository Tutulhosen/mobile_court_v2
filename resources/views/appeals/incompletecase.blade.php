@extends('layout.app')

@section('content')
<style>


</style>
<div class="row" style="border: 1px solid rgb(178, 192, 174); box-shadow: 0 0px 2px 0 rgba(85, 185, 45, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
">

        <div class="col-md-12">
            <!--begin::Card-->
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title h2 font-weight-bolder">{{ $page_title}}</h3>
                </div>
 
            <div class="card-body overflow-auto">
            
     
                <table class="table table-hover mb-6 font-size-h5">
                    <thead class="thead-customStyle2 font-size-h6">
                        <tr style="text-align: justify">
                            <th scope="col">ক্রমিক নং</th>
                            <th scope="col">মামলা নম্বর</th>
                            <th scope="col">আবেদনের তারিখ</th>
                            <th scope="col">ঘটনাস্থল</th>
                            <th scope="col">ঘটনাস্থল</th>
                            <th scope="col">জব্দতালিকা</th>
                            <th scope="col">আদেশনামা</th>
                            <th scope="col">পদক্ষেপ</th>
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
                            <td>{{ $key+1 }}</td>
                            <td>{{ $row->case_no }}</td>
                            <td>{{ $row->date }}</td>
                            <td>{{ $row->location }}</td>
                            <td>
                            <?php
                            $arr_length = count($ade);
                            for ($x = 0; $x <$arr_length; $x++){
                            echo $ade[$x];
                            echo "<br/>";
                            }
                            ?>
                            </td>
                            
                             @if(empty($row->is_seizurelist) || $row->is_seizurelist=="")
                             <td> নাই </td> 
                             @else
                             <td>আছে</td>
                             @endif 
                            @if(empty($row->is_orderSheet) || $row->is_orderSheet=="")
                             <td> নাই</td> 
                             @else
                             <td>আছে </td>
                             @endif
                             <td>
                                <div class="btn-group float-right">
                                    <!-- <button class="btn btn-primary font-weight-bold btn-sm dropdown-toggle" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">পদক্ষেপ</button>
                                    <div class="dropdown-menu dropdown-menu-right"> -->
                                          <!-- <a class="dropdown-item"
                                            href="{{ route('proceutions.details',$row->id) }}">বিস্তারিত তথ্য</a> -->

                                            @if($row->is_seizurelist == "")
                                                <a href="{{ url('prosecution/suomotucourt/' . $row->id . '&prosecution_id=' . $row->id . '&case_number=' . $row->case_no . '&step=2') }}" class="btn btn-mideum btn-danger dropdown-item">পরিবর্তন</a>
                                            @else
                                                <a href="{{ url('prosecution/suomotucourt/' . $row->id . '&prosecution_id=' . $row->id . '&case_number=' . $row->case_no . '&step=3') }}" class="btn btn-mideum btn-danger dropdown-item">পরিবর্তন</a>
                                            @endif

                                        
                                    <!-- </div> -->
                                </div>
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
@endsection