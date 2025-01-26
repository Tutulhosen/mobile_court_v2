@extends('layout.app')
@yield('style')

@section('content')

<style>
    #calendar {
    width: 100%; /* Adjust width as needed */
    max-width: 700px; /* Limit the maximum width */
    height: 100vh; /* Adjust height as needed */
    overflow: hidden; /* Prevent scrollbars */
    margin: 0 auto; /* Center the calendar */
}
</style>

@if (session()->has('message'))
<div class="alert alert-success alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>{{ session('message') }}</strong>
  </div>
@endif
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <h3 class="card-label">
			সবুজ রং :  কোর্ট খোলা । হলুদ রং : কোর্ট বন্ধ  । নীল রং : কোর্ট কর্মসূচি প্রণয়ন করা হয়েছে । 
            </h3>
        </div>
        {{-- <div class="card-toolbar">
            <a href="#" class="btn btn-light-primary font-weight-bold">
                <i class="ki ki-plus "></i> Add Event
            </a>
        </div> --}}
    </div>
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>

<div id="createEventModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header" style="background:#1b8f1b;border-top-left-radius: 5px;border-top-right-radius: 5px;color: #fff;">
				<!--button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button-->
				
				<h3 id="myModalLabel1 text-left">নতুন কর্মসূচি  প্রণয়ন</h3>
				<a class="close" data-dismiss="modal" >×</a>
			</div>
			<div class="card-body p-5 cpv">
				<form id="createForm" name="createForm"  class="contact">
					<div class="form-group">
						<label class="control-label" for="court_heading1" style="background-color: #4c81db;width: 100%;padding-left: 5px;color:#fff">কোর্টের শিরোনাম <span style="color:#FF0000">*</span></label>
						<div class="controls">
							<input type="text" name="court_heading" class="form-control" id="court_heading1" data-provide="typeahead" data-items="4" data-source="[&quot;Value 1&quot;,&quot;Value 2&quot;,&quot;Value 3&quot;]">
							<input type="hidden" id="apptStartTime1"/>
							<input type="hidden" id="apptEndTime1"/>
							<input type="hidden" id="apptAllDay1" />
							<input type="hidden" id="id1" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label" style="background-color: #4c81db;width: 100%;padding-left: 5px;color:#fff" for="when1">তারিখঃ</label>
						<div class="controls " id="when1" style="margin-top:5px;">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label" style="background-color: #4c81db;width: 100%;
    padding-left: 5px;color:#fff">স্ট্যাটাসঃ</label>
						<div class="controls radio">
							<label for="status11"><input type="radio" name="status" value="1" id="status11" /> কোর্ট খোলা</label>
						</div>
						<div class="controls radio">
							<label for="status12"><input type="radio" name="status" value="2" id="status12" /> কোর্ট বন্ধ</label>
						</div>
						<div class="controls radio">
							<label for="status10"><input type="radio" name="status" value="0" id="status10" required="true" /> কোর্ট কর্মসূচি  প্রণয়ন</label>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">বাতিল</button>
				<button class="btn btn-success" type="submit" id="submitButton">সংরক্ষণ করুন</button>
			</div>
		</div>
	</div>
</div>


<div id="operationalEventModal"  class="modal fade" >
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header"  style="background:#1b8f1b;border-top-left-radius: 5px;border-top-right-radius: 5px;color: #fff;">
              
                <h3 id="modal-title text-left">কর্মসূচি আপডেট অথবা বাতিল করুন</h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="panel panel-info-head">
                <!-- {#<div class="panel-heading newhead">#}
                {#</div>#} -->
                    <div class="modal-body cpv">
                        <form id="deleteForm" name="deleteForm" class="contact">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label midtext" for="court_heading2" style="background-color: #4c81db;width: 100%;padding-left: 5px;color:#fff">কোর্টের শিরোনাম <span
                                                    style="color:#FF0000">*</span></label>
                                        <div class="controls">
                                            <input type="text" name="court_heading" id="court_heading2"
                                                   class="form-control" data-provide="typeahead" data-items="4"
                                                   data-source="[&quot;Value 1&quot;,&quot;Value 2&quot;,&quot;Value 3&quot;]">
                                            <input type="hidden" id="apptStartTime2"/>
                                            <input type="hidden" id="apptEndTime2"/>
                                            <input type="hidden" id="apptAllDay2"/>
                                            <input type="hidden" id="id2"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" style="background-color: #4c81db;width: 100%;padding-left: 5px;color:#fff" for="when2">তারিখঃ</label>
                                        <div class="controls controls-row" id="when2" style="margin-top:5px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">স্ট্যাটাসঃ</label>
                                        <div class="rdio rdio-primary">
                                            <input type="radio" name="status" value="1" id="status21"/>
                                            <label for="status21">কোর্ট খোলা</label>
                                        </div>
                                        <div class="rdio rdio-primary">
                                            <input type="radio" name="status" value="2" id="status22"/>
                                            <label for="status22">কোর্ট বন্ধ</label>
                                        </div>
                                        <div class="rdio rdio-primary">
                                            <input type="radio" name="status" value="0" id="status20" checked
                                                   required="true"/>
                                            <label for="status20">কোর্ট কর্মসূচি প্রণয়ন</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn-warning btn btn-mideum" data-dismiss="modal" aria-hidden="true"> না</button>
                <button type="submit" class="btn btn-success  btn-modal" id="updateButton">আপডেট</button>
                <button type="submit" class="btn btn-danger  btn-modal" id="deleteButton">বাতিল</button>
            </div>
        </div>
    </div>
</div>

<div id="messageModal" class="modal fade in" data-backdrop="static" data-keyboard="false" style="display: none;">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h3 id="myModalLabel3" class="float-left">ধন্যবাদ</h3>
				<a class="close" data-dismiss="modal" >×</a>
			</div>
			<div class="modal-body">
				<input style="text-align:center;  color: red;width:100%" type="text" name="message" id="message" value="" style="width:320px;" readonly />
			</div>
			<div class="modal-footer">
				<button id="closeButton" class="btn btn-mideum" data-dismiss="modal" aria-hidden="true"> বন্ধ করুন</button>
			</div>
		</div>
	</div>
</div>
@endsection

{{-- Includable CSS Related Page --}}
@section('styles')
 
    <!--end::Page Vendors Styles-->
@endsection
@section('scripts')

<!-- <link href="{{asset('plugins/fullcalendar/fullcalendar.min.css')}}" rel="stylesheet" type="text/css" />

<link href="{{ asset('plugins/fullcalendar/fullcalendar.print.min.css')}}" rel="stylesheet" media="print" type="text/css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js" integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('plugins/fullcalendar/lib/moment.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('plugins/fullcalendar/fullcalendar.min.js')}}>" type="text/javascript"></script> -->


<!-- <link href="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" /> -->

<!-- <script src='http://fullcalendar.io/js/fullcalendar-2.1.1/lib/moment.min.js'></script> -->
<!-- <script src='http://fullcalendar.io/js/fullcalendar-2.1.1/lib/jquery.min.js'></script> -->
<!-- <script src="http://fullcalendar.io/js/fullcalendar-2.1.1/lib/jquery-ui.custom.min.js"></script> -->


<!-- <script src='http://fullcalendar.io/js/fullcalendar-2.1.1/fullcalendar.min.js'></script> -->


<link href="{{asset('plugins/ttt/jquery-ui-1.10.3.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/ttt/fullcalendar-1.6.4.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/ttt/fullcalendar-1.6.4.print.css')}}" rel="stylesheet" type="text/css" />

<script src="{{ asset('plugins/ttt/jquery-ui-1.10.3.min.js') }}"></script>
<script src="{{ asset('plugins/ttt/fullcalendar-1.6.4.js') }}"></script>

<script type="text/javascript">
 
 $(document).ready(function () {
      // full calender
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    var event = '';
    var calendar = $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
       
        selectable: true,
        selectHelper: true,
        navLinks: true, // can click day/week names to navigate views
        businessHours: true, // display business hours
        editable: true,
        select: function(start, end, allDay) {
            endtime = $.fullCalendar.formatDate(end,'h:mm tt');
            starttime = $.fullCalendar.formatDate(start,'ddd, MMM d');
            var mywhen = starttime ;
            $('#createEventModal #apptStartTime1').val(start);
            $('#createEventModal #apptEndTime1').val(end);
            $('#createEventModal #apptAllDay1').val(allDay);
            $('#createEventModal #when1').text(mywhen);
            $('#createEventModal').modal('show');
        },
        //openModal("' + event.title + '","' + event.description + '","' + event.url + '","' + event.start + '","' + event.end + '"
       
        eventDrop: function(event, delta) {
            
            var start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
            var end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
            var url = "{{ url('/court/update_events') }}/";
            alert(url);
            $.ajax({
                url: url,
                data: 'title='+ event.title+'&start='+ $.fullCalendar.formatDate(start, "yyyy/MM/dd") +'&end='+ end +'&id='+ event.id ,
                type: "POST",
                success: function(json) {
                    alert("Updated Successfully");
                }
            });
           
        },
        eventResize: function(event) {
            var start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
            var end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
          
            var url = "{{ url('/court/update_events') }}/";
       
            $.ajax({
                url: url,
                data: 'title='+ event.title+'&start='+ $.fullCalendar.formatDate(start, "yyyy/MM/dd") +'&end='+ end +'&id='+ event.id ,
                type: "POST",
                success: function(json) {
                    alert("Updated Successfully");
                }
            });
        },
        eventClick: function(event) {
            $('#operationalEventModal #court_heading2').val(event.title);
            $('#operationalEventModal #apptStartTime2').val(event.start);
            if(event.status == '1'){
                document.forms["deleteForm"]["status21"].checked=true;
            }
            else if(event.status == '2'){
                document.forms["deleteForm"]["status22"].checked=true;
            }
            else{ // 0
                document.forms["deleteForm"]["status20"].checked=true;
            }

            $('#operationalEventModal #id2').val(event.id);

            var mywhen = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd");
            $('#operationalEventModal #when2').text(mywhen);

            $('#operationalEventModal').modal('show');
        },

        events: function (start, end, callback) {
            var url = "{{ url('/court/getcourtdataAll') }}/";
            $.ajax({
                url:url,
                dataType: 'json',

                success: function (doc) {
                    var events = [];
                    // console.log(doc);
                    $.each(doc, function (key, val) {
                        events.push({
                            id: val.id,
                            title : val.title,
                            start: val.start,
                            editable: false,
                            status: val.status,
                            className: val.className
                        });
                    });

                    callback(events);
                }
            });
            // /
        }
        
        /* ,
        eventColor: '#378006' */
    });
    });

    $('#submitButton').on('click', function(e){
        var  title =   $('#createEventModal #court_heading1').val();
        var  apptStartTime =   new Date($('#createEventModal #apptStartTime1').val());
        var  status =   '';
        var len = document.createForm.status.length;
        for (i = 0; i < len; i++) {
            if ( document.createForm.status[i].checked ) {
                status = document.createForm.status[i].value;
                break;
            }

        }
        var  id =   $('#operationalEventModal #id2').val();

        var start = $.fullCalendar.formatDate(apptStartTime, "yyyy-MM-dd");
        if (title) {
            var url = "{{ url('/court/create_events') }}/" + id;

            $.ajax({
                url:url,
                data: "&title=" + title +"&status=" + status +"&start=" + start,
                type: "POST",
                success: function(json) {
                    $('#createEventModal #id1').val();
                    $('#createEventModal #title1').val();
                   
                   if(json.success==false){
                      toastr.error(json.msg,"সর্তকীকরণ  ম্যাসেজ");
                      window.location.reload();
                   }else{
                       toastr.success(json.msg," অবহিত করন ম্যাসেজ");
                       window.location.reload();
                   }
                   $('#message').val(json);
                    // $("#messageModal").modal('show');
                    $("#createEventModal").modal('hide');
                   
                }
            });
            $('#calendar').fullCalendar( 'refetchEvents' );

        }else{
            toastr.error("কোর্টের শিরোনাম লিখুন ।" ,"সর্তকীকরণ  ম্যাসেজ");
            
        }
        
    });
    $('#updateButton').on('click', function(e){
       
        // We don't want this to act as a link so cancel the link action
        e.preventDefault();

        $("#operationalEventModal").modal('hide');

        var  title =   $('#operationalEventModal #court_heading2').val();
        var  apptStartTime =   new Date($('#operationalEventModal #apptStartTime2').val());
        var  status =   '';
        var len = document.deleteForm.status.length;
        for (i = 0; i < len; i++) {

            if ( document.deleteForm.status[i].checked ) {

                status = document.deleteForm.status[i].value;
                break;

            }

        }
        
        var  id =   $('#operationalEventModal #id2').val();

        var start = $.fullCalendar.formatDate(apptStartTime, "yyyy-MM-dd");
        var url = "{{ url('/court/update_events') }}/";
        $.ajax({
            url: url,
            data: "&id=" + id +"&title=" + title +"&status=" + status +"&start=" + start,
            type: "POST",
            success: function(json) {

                if(json.success==false){
                      toastr.error(json.msg,"সর্তকীকরণ  ম্যাসেজ");
                      setTimeout(function(){
                        window.location.reload();
                    }, 2000);
                }else{
                    toastr.success(json.msg," অবহিত করন ম্যাসেজ");
                    setTimeout(function(){
                        window.location.reload();
                    }, 2000);
                }
                //  console.log(json);
                //  return false;
                // $('#message').val(json);
                // $("#messageModal").modal('show');
                // toastr.success(json," ম্যাসেজ");
            }
        });
        $('#calendar').fullCalendar( 'refetchEvents' );

    });
    $('#deleteButton').on('click', function(e){
        // We don't want this to act as a link so cancel the link action
        e.preventDefault();
        $("#operationalEventModal").modal('hide');
        var  id =   $('#operationalEventModal #id2').val();
//        jAlert(id);
        var url = "{{ url('/court/delete_events') }}/";
        $.ajax({
            type: "POST",
            url: url,
            data: "&id=" + id,
            success: function(json) {

                if(json.success==false){
                      toastr.error(json.msg,"সর্তকীকরণ  ম্যাসেজ");
                      setTimeout(function(){
                        window.location.reload();
                      }, 2000);
                   }else{
                       toastr.success(json.msg,"অবহিত করন ম্যাসেজ");
                      
                       setTimeout(function(){
                        window.location.reload();
                       }, 2000);
                   }
                //  console.log(json);
                // $('#message').val(json.msg);
                // // $("#messageModal").modal('show');
                // toastr.success(json.msg," ম্যাসেজ");
            }
        });
        $('#calendar').fullCalendar('removeEvents', id);
    });

    $('#closeButton').on('click', function(e){
        window.location.reload();
    });
</script>
<script>
    jQuery("th.fc-agenda-axis").hide();
  </script>
  <script>
    $('.dropdown-option li').on('click', function () {
      var getValue = $(this).text();
      $('.dropdown-select').text(getValue);
    });
  </script>
@endsection