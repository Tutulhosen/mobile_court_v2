@extends('layout.app')
@section('content')
<select id="dd_seizure" class=" " name="dd_seizure" usedummy="1" style="display:none;">
	<option value="">বাছাই করুন...</option>
    @if (!empty($seizureitem_type))
            @foreach($seizureitem_type as  $seizureitemtype)
            <option value="{{$seizureitemtype->id}}">{{$seizureitemtype->item_group}}</option>
            @endforeach
        @endif
</select>
<style>
    .input-error {
    border-color: red !important;
}   

span.select2-selection.select2-selection--single {
    height: 40px;
}
.nav-primary {
    border-color: #357ebd;
    background-color: #428bca;
}
.nav-primary > 
li.active > a, 
.nav-primary > 
li.active > a:hover, 
.nav-primary > li.active > a:focus, 
.nav-primary > li.active > a:active {
    border-top-color: #357ebd;
    border-left-color: #357ebd;
    border-right-color: #357ebd;
}
.nav > li > a {
    padding: 4px 40px;
}
.nav-primary > li > a, 
.nav-success > li > a, 
.nav-info > li > a, 
.nav-danger > li > a, 
.nav-warning > li > a {
    color: #fff;
    line-height: 30px;
    
}

.nav-primary > li > a:hover, 
.nav-success > li > a:hover, 
.nav-info > li > a:hover, 
.nav-danger > li > a:hover, 
.nav-warning > li > a:hover {
    color: #000;
    background-color: rgb(255 255 255);
   
}
/* .nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {
    color: #555;
    cursor: default;
    background-color: #fff;
    border: 1px solid #ddd;
    border-bottom-color: transparent;
} */
.nav-primary > li > a.active {
    background-color: #fff;
    color: #000;
}
</style>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
          <div class="card card-custom">
                <div class="card-body cpv selfCourt">
                 
                        <div class="panel-heading">
                            <h2 class="panel-title"> আসামি ছাড়া স্বপ্রণোদিত কোর্ট <?php echo !$prosecution_id ? '(নতুন)':'' ?></h2>
                            <input type="hidden" id="txtProsecutionID" value="{{ $prosecution_id }}" />
                        </div>
                        <div class="panel-body cpv selfPanel">
                            <ul class="nav nav-primary nav-tabs">
                                <li class="active" id="tab-2"><a href="#tab2-2" data-toggle="tab"> সাক্ষীর তথ্য</a></li>
                                <li id="tab-3"><a href="#tab3-2" data-toggle="tab"> অভিযোগ গঠন</a></li>
                                <li id="tab-4"><a href="#tab4-2" data-toggle="tab"> জব্দতালিকা</a></li>
                                <li id="tab-5"><a href="#tab5-2" data-toggle="tab"> আদেশ প্রদান</a></li>
                            </ul>

                            <div class="tab-content">

                                <div class="tab-pane active" id="tab2-2">
                                    @include('appeals.partials.tab2-2')
                                </div>

                                <div class="tab-pane" id="tab3-2">
                                    @include('appeals.partials.tab3-2')
                                </div>

                                <div class="tab-pane" id="tab4-2">
                                @include('appeals.partials.tab4-2')
                                </div>

                                <div class="tab-pane" id="tab5-2">
                                    @include('appeals.partials.tab6-2')
                                </div>

                            </div>
                        </div>
                  
                </div>

                <div id="successprosecution" class="modal" style="display: none; ">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <a class="close" data-dismiss="modal">×</a>
                                <h3>ধন্যবাদ</h3>
                            </div>
                            <div class="modal-body" style="height: 200px; overflow: auto;">
                                @include('appeals.partials.message_suo')
                            </div>
                            <div class="modal-footer">
                                <a href="#" class="btn btn-mideum" data-dismiss="modal">সমাপ্ত</a>
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


<script src="{{ asset('js/validation/input-validator.js') }}"></script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script  src="{{ asset('mobile_court/javascripts/source/content/multiFileUpload.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/prosecution/prosecutionInitiate.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/prosecution/magistrateSelectionForm.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/prosecution/witnessInfoForm.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/prosecution/complaintInfoForm.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/prosecution/seizureListInfoForm.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/prosecution/confessionForm.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/prosecution/ordersheetInfoForm.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/law/law.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/utils/ui-utils.js') }}" ></script>
<script  src="{{ asset('mobile_court/javascripts/source/location/location.js') }}" ></script>





@endsection
