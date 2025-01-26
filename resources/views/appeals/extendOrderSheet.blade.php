@extends('layout.app')

@section('title', 'আদেশ সংযোজন')

@section('styles')
    <link rel="stylesheet" href="{{ asset('mobile_court/cssmc/select2.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('mobile_court/cssmc/bootstrap-timepicker.min.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('mobile_court/cssmc/bootstrap-wysihtml5.css') }}">
@endsection

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@section('content')

<div class="container">
    <div class="row">
        <div class="col-lg-12">
          <div class="card card-custom">
           <!-- prosecution/saveExtendOrderSheet -->
            <form method="post" id="" enctype="multipart/form-data"  action="{{ route('saveExtendOrderSheet')}}">
                 @csrf
                <div class="mainwrapper">
                    <div class="contentpanel">
                        <!-- <div class="panel panel-default"> -->
                            <div class="card-header smx">
                                <h2 class="card-title">আদেশ সংযোজন</h2>
                            </div>
                            <div class="card-body p-15 cpv">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="date01">তারিখ</label>
                                            <input type="text" name="date01" id="date01" class="input form-control" data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd">
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="receipt_no">রশিদ নম্বর (যদি থাকে)</label>
                                            <input type="text" name="receipt_no" id="receipt_no" class="input form-control" value="{{ $receipt_no }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="content">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                        <label for="tinymce_full">আদেশ</label>
                                        <textarea name="tinymce_full" id="tinymce_full" class="textarea form-control" placeholder="Enter text ..." style="width: 100%; height: 200px; font-size: 14px; line-height: 18px;"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="form-group">
                                    <label for="image">আদেশনামা ফরমের স্ক্যানকৃত ছবি সংযুক্ত করুন (যদি থাকে)</label>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="panel panel-danger-alt">
                                                <div class="panel-body cpv p-5 photoContainer">
                                                    <button type="button" class="btn btn-success multifileupload">
                                                        <span>+</span>
                                                    </button>
                                                    <hr>
                                                    <div class="panel panel-danger-alt">
                                                        <div class="docs-toggles"></div>
                                                        <div class="docs-galley photoView"></div>
                                                        <div class="docs-buttons" role="group"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->

                                <div class="attachment-section">
                                    <h5 class="mb-3" style="font-weight: bold;">সংযুক্তি</h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th style="width: 45%;text-align: center;">আদেশনামা ফরমের স্ক্যানকৃত ছবি সংযুক্ত করুন (যদি থাকে)</th>
                                                    <th style="width: 15%; text-align: center;">
                                                        <button type="button" class="btn btn-sm btn-primary addRowBtn">+</button>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="attachmentTableBody">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="text-right">
                                
                                    <input type="hidden" name="punishment_id" value="{{ $punishment_id }}">
                                    <input type="hidden" name="prosecution_id" value="{{ $prosecution_id }}">

                                    <div class="text-right">
                                        <button type="button" class="btn btn-warning" data-dismiss="modal">বন্ধ করুন</button>
                                        <button type="submit" class="btn btn-primary" id="saveComplain">সংরক্ষণ করুন</button>
                                    </div>
                                </div>
                            </div>
                        <!-- </div> -->
                    </div>
                </div>
            </form>
          </div>
        </div>
    </div>
</div>
    
@endsection
@section('scripts')


<script src="{{ asset('mobile_court/js/select2.min.js') }}"></script>
<script src="{{ asset('mobile_court/js/bootstrap-timepicker.min.js') }}"></script>
<!-- <script src="{{ asset('mobile_court/js/jquery-ui-1.10.3.min.js') }}"></script> -->
<script src="{{ asset('mobile_court/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('mobile_court/js/bootstrap-wizard.min.js') }}"></script>
<script src="{{ asset('mobile_court/js/wysihtml5-0.3.0.min.js') }}"></script>
<script src="{{ asset('mobile_court/js/bootstrap-wysihtml5.js') }}"></script>
<script src="{{ asset('mobile_court/javascripts/source/content/multiFileUpload.js') }}"></script>



    <script>
 
        $(document).ready(function () {
            $('#date01').datepicker({
                format: "yyyy-mm-dd"
            });
            $('.textarea').wysihtml5({});
        });

        function showFine(select){
            var order_str = ["", "", ""];
            var fine_in_word = "#fine_in_word";

            var fine = document.getElementById("fine");
            var finevalue = fine.value;

            if(select == ""){
                order_str[1] = "";
                $(fine_in_word).val("");
                $('#receipt_no').attr('readonly', true);
                return;
            }

            order_str[1] = finevalue + " টাকা অর্থদণ্ড";

            $(fine_in_word).val(order_str[1]);

            $('#receipt_no').removeAttr('readonly');
            document.getElementById('receipt_no').focus();
            document.getElementById('receipt_no').style.color = '#E44666';
        }
    </script>
@endsection
