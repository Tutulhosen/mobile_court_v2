
@extends('layout.app')

@section('content')

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
                    <th style="width: 10%">মামলা নম্বর</th>
                    <th style="width: 10%">তারিখ</th>
                    <th style="width: 10%">ঘটনাস্থল</th>
                    <th style="width: 10%">অভিযোগ</th>
                   
                    <th style="width: 10%">জব্দতালিকা   </th>
                    <th style="width: 10%">তথ্য পরিবর্তন করুন</th>

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
                  
                    <td><?php
    
                    $arr_length = count($ade);
                    for ($x = 0; $x <$arr_length; $x++){
                    echo $ade[$x];
                    echo "<br/>";
                    }
                    ?></td>
                   

                   <?php if($row->is_seizurelist==""){?>
                        <td align="center">জব্দতালিকা নাই</td>
                        
                    <?php }else{?>
                        <td align="center">জব্দতালিকা আছে</td>
                    <?php }?>
                    
                    @if ($row->status == 2)
                        <td>কোর্টবন্ধ।</td>
                    @else
                        <td>
                            <a href="{{ url('prosecution/newProsecution/' . $row->id . '?prosecution_id=' . $row->id . '&magistrateid=' . $row->magistrateid . '&step=2') }}" class="btn btn-medium btn-danger">
                                পরিবর্তন
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
    </div>

    </div>
</div>

</div>


@endsection