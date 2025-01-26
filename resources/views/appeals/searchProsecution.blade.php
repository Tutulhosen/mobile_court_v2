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
                    <th style="width: 10%">প্রসিকিউটর</th>
                    <th style="width: 10%">অভিযোগ</th>
                     <th style="width: 10%">আদালতের তারিখ</th>
                     <th style="width: 10%">ঘটনার তারিখ</th>
                    <th style="width: 10%">ঘটনাস্থল</th>
                    <th style="width: 10%">কার্যক্রম</th>

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
                    <td>{{ $row->prosecutor_name }}</td>

                    <td>
                    <?php
                    $arr_length = count($ade);
                    for ($x = 0; $x <$arr_length; $x++){
                    echo $ade[$x];
                    echo "<br/>";
                    }
                    ?></td>
                    <td>{{ $row->courtdate }}</td>
                    <td>{{ $row->date }}</td>
                    <td>{{ $row->location }}</td>
                  
                    @if($row->status == "2")
                     <td width="12%">কোর্ট বন্ধ।</td>
                    @else
                        <td width="12%">
                            <a href="{{ url('prosecution/newComplain/' . $row->id) }}" class="btn btn-mideum btn-warning">অভিযোগ গঠন</a>
                        </td>
                        {{-- Uncomment if needed
                        <td width="12%">
                            <a href="{{ url('prosecution/delete/' . $row->id) }}" class="btn">জব্দতালিকা</a>
                        </td>
                        --}}
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