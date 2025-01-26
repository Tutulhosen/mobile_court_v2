@extends('layout.app')

@section('content')

 

<?php //echo $this->tag->form(array("requisition/createRequisition", "method" => "post" ,"id" => "requsitionform")) ?>
@push('head')
<style>
    .input-select {
        font-family: inherit;
        font-size: 16px;
        line-height: inherit;
        width: 150px !important;
    }
</style>
<link rel="stylesheet" type="text/css" href="{{ asset('/mobile_court/cssmc/select2.css')}}" />
<link rel="stylesheet" type="text/css" href="{{ asset('/mobile_court/cssmc/bootstrap-timepicker.min.css')}}" />
<link rel="stylesheet" type="text/css" href="{{ asset('/mobile_court/assets/js/dropzone/dropzone.css')}}" />

@endpush



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
<form action="{{ url('requisition/createRequisition') }}" method="post" id="requsitionform">
    @csrf
    <div class="card-header smx">
        <div class="panel-btns" style="display: none;">
            <a title="" data-toggle="tooltip" class="panel-minimize tooltips" href="" data-original-title="Minimize Panel"><i class="fa fa-minus"></i></a>
        </div>
        <!-- panel-btns -->
        <h4 class="card-title"> <strong>বিস্তারিত অভিযোগ </strong></h4>
    </div>
    <div class="card-body p-15 cpv">
        <div class="row">
            <div class="col-sm-3 " >
                <div class="form-group">
                    <label class="control-label"><strong>অভিযোগকারীর নাম :</strong></label>
                    <?php echo $name_bng ?>
                </div>
            </div>
            <div class="col-sm-3 ">
                <div class="form-group">
                    <label class="control-label"><strong>মোবাইল :</strong></label>
                    <?php echo $mobile ?>
                </div>
            </div>
            <div class="col-sm-3 ">
                <div class="form-group">
                    <label class="control-label"><strong>অভিযোগ আইডি :</strong></label>
                    <?php echo $user_idno ?>
                </div>
            </div>
            <div class="col-sm-3 ">
                <div class="form-group">
                    <label class="control-label"><strong>অভিযোগের তারিখ ও সময় :</strong></label>
                    <?php echo $created_date ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label"><strong>বিভাগ :</strong></label>
                    <?php echo $divname ?>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label"><strong>জেলা :</strong></label>
                    <?php echo $zillaname ?>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label"><strong>উপজেলা : </strong></label>
                    <?php echo $upazilaname ?>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label"><strong>ঘটনাস্থল : </strong></label>
                    <?php echo $cmp_location ?>
                </div>
            </div>
            <?php //echo $this->tag->hiddenField("divname") ?>
            <?php //echo $this->tag->hiddenField("zillaname") ?>
            <?php //echo $this->tag->hiddenField("upazilaname") ?>
            <input type="hidden" name="divname" id="divname" value="{{ $divname }}">
            <input type="hidden" name="zillaname" id="zillaname" value="{{ $zillaname }}">
            <input type="hidden" name="upazilaname" id="upazilaname" value="{{ $upazilaname  }}">
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label"><strong>অভিযোগের বর্ণনা</strong></label>
                    <textarea name="complain_details" cols="50" rows="4" class="input form-control" readonly>{{ $complain_details }}</textarea>
                     <input type="hidden" name="complain_id" id="complain_id" value="{{ $complain_id }}">
                </div>
            </div>
        </div>

        <!-- <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label"> নাগরিক  অভিযোগ  সম্পর্কিত  ফাইল </label> -->
              
                    @php
                        $number = 1; 
                    @endphp

                    {{-- @foreach ($uploaded_file as $value)
                        <!-- <div class="row">
                            <div class="form-group col-xs-3 col-sm-3 col-md-3 col-lg-1">{{ $number }}- নম্বর :</div>
                            <div class="form-group col-xs-3 col-sm-3 col-md-3 col-lg-2">
                                <img class="img-responsive img-thumbnail multi-image" src="{{ $value->FileType == 'IMAGE' ? '/ecourt/' . $value->FilePath . $value->FileName : '/doc.png' }}">
                            </div>
                            <div class="form-group col-xs-3 col-sm-3 col-md-3 col-lg-1">
                                <a style="color:#428BC9" href="/ecourt/{{ $value->FilePath . $value->FileName }}" download>
                                    <i class="glyphicon glyphicon-download-alt" style="font-size:20px;"></i>
                                </a>
                            </div>
                        </div> -->
                        @php
                            <!-- $number++; -->
                        @endphp
                    @endforeach --}}


                <!-- </div>
            </div>
        </div> -->

        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label"><strong>এক্সিকিউটিভ ম্যাজিস্ট্রেট</strong></label>
                    <select name="magistrate_id" class="input form-control" id="magistrate_id" require>
                        <option value="">{{ 'বাছাই করুন...' }}</option>
                        @foreach($magistrates as  $val)
                            <option value="{{ $val->id }}">{{ $val->name_eng }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label"> <strong>সিদ্ধান্ত</strong></label>
                    <?php //echo $this->tag->textField(array("req_comment", 'class' => "input form-control" )) ?>
                    <input type="text" name="req_comment" class="input form-control">
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label"><strong>সিদ্ধান্ত</strong></label>
                    <input class="input form-control " data-date-format="mm/dd/yyyy" name="estimated_date" id="" value="" type ="date">
                </div>
            </div>
        </div>
        {{-- <label><img style="cursor:pointer; " alt=" প্রিন্ট" title=" প্রিন্ট" src="{{ url.getBaseUri() }}images/print.png" onclick="printComplain();"> </label> --}}
    </div>

    <div class="card-footer">
        <div class="row">
            <div class="col-xs-8 col-sm-6 col-md-6 col-lg-6 text-left">
                <input name="accept" value="অভিযোগ প্রেরণ " type="submit" class="btn btn-success"/>
                <input name="delete" value="বাতিল" type="submit" class="btn btn-danger"/>
                <!-- <input value="প্রথম পাতা" type="button" class="btn btn-success hidden-xs"/> -->
            </div>
            <div class="col-xs-4 col-sm-6 col-md-6 col-lg-6 text-right">
                <div class="btn btn-info" onclick="printComplain();"><i class="fa fa-print"></i> <span class="hidden-xs">অভিযোগ প্রিন্ট করুন </span></div>
            </div>
        </div>
    </div>
</div>
<!-- main panel body -->


<div id="printComplain" style="display: none;">
    {{-- @include('requisition.partials.citizen_complain') --}}
      @include('citizen_complain.partials.citizen_complain') 

</div>
@endsection
@section('scripts')

<script type="text/javascript" src="{{ asset('/mobile_court/js/jquery-ui-1.10.3.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/mobile_court/js/select2.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/mobile_court/js/requisition/requisitionscript.js')}}"></script>
<script type="text/javascript" src="{{ asset('/mobile_court/js/bootstrap-timepicker.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/mobile_court/assets/js/dropzone/dropzone.min.js')}}"></script>

@endsection