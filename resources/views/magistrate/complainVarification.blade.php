@extends('layout.app')
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading mb-3">
            <h2 class="panel-title"> অপরাধের তথ্য</h2>
        </div>
        <div class="panel-body cpv">

            <table class="table table-bordered table-striped table-info" id="">
                <thead style="background-color: green; color:white">
                    <tr>
                        <th>অভিযোগের আইডি</th>
                        <th>অভিযোগ</th>
                        <th> অভিযোগের তারিখ</th>
                        <th>ঘটনার তারিখ</th>
                        <th>অভিযোগকারীর নাম</th>
                        <th>ঘটনাস্থল</th>
                        <th colspan="3">কার্যক্রম</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($citizen_complain as $citizen_complains){?>
                    <tr>
                        <td>{{ $citizen_complains->user_idno }}</td>
                        <td>{{ $citizen_complains->subject }}</td>
                        <td>{{ $citizen_complains->created_date }}</td>
                        <td>{{ $citizen_complains->complain_date }}</td>
                        <td>{{ $citizen_complains->name_bng }}</td>
                        <td>{{ $citizen_complains->location }}</td>
                        <td style="text-align:center;">
                            <a class="btn btn-primary" onclick="showComplain(<?php echo $citizen_complains->complain_id; ?>);"> কার্যক্রম গ্রহণ </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>

            </table>
            <div class="d-flex justify-content-center">
                {!! $citizen_complain->links('pagination::bootstrap-4') !!}
            </div>


        </div>
        <!-- panel-body -->

    </div><!-- panel -->


    <!-- Modal -->
    <div class="modal fade" id="complainInfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span><span class="sr-only"></span></button>
                    <h4 class="modal-title" id="myModalLabel"> অপরাধের তথ্য প্রতিপাদন </h4>
                </div>

                <div class="modal-body">
                    <form method="post" id="saveComplainForm" action="">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">মন্তব্য</label>
                                <label for="fruits" class="error"></label>
                            </div>
                            <!-- form-group -->

                            <div class="form-group">
                                <div class="col-sm-4">
                                    <textarea id="feedback" name="feedback" class="input
                    " rows="4" cols="50"></textarea>
                                </div>
                            </div>
                            <!-- form-group -->
                            <div class="form-group">
                                <label class="col-sm-12 control-label">সিদ্ধান্ত</label>
                                <label for="fruits" class="error"></label>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-4">

                                    <table align="center" width="50%" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td style="padding:  0px 15px 0px 0px;">
                                                <input class="radio-input" type="radio" name="feedback_action"
                                                    value="1" />
                                                নিষ্পন্ন
                                            </td>
                                            <td style="padding: 0px 15px 0px 0px;">
                                                <input class="radio-input" type="radio" name="feedback_action"
                                                    value="2" checked />
                                                পুনর্বিবেচনা
                                            </td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                            <!-- form-group -->
                            <input name="id" id="id" value="" type="hidden" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">বন্ধ করুন</button>
                            <button type="submit" class="btn btn-primary" id="saveComplain">সংরক্ষণ করুন</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- {{ asset('mobile_court/js/magistrate/submitmodal.js') }} -->
    <script src="{{ asset('mobile_court/js/magistrate/submitmodal.js') }}" type="text/javascript"></script>
    <script>
        // function showComplain(select){



        //     if(select == "" ){

        //         return
        //     }

        //     var url ="/citizen_complain/getCitizen_complainByReqId?reqid="+select;
        //     $.ajax({
        //         url: url,
        //         type: 'POST',
        //         success:function(data) {

        //             if(data.length>0)
        //             {
        //                 var name = "#name" ;
        //                 var cmp_mobile = "#cmp_mobile" ;
        //                 var email = "#email" ;

        //                 var cmp_subject = "#cmp_subject" ;
        //                 var cmp_details = "#cmp_details" ;

        //                 $(name).val(data[0].name);
        //                 $(cmp_mobile).val(data[0].mobile);
        //                 $(cmp_subject).val(data[0].subject);
        //                 $(cmp_details).val(data[0].complain_details);

        //             }
        //         },
        //         error:function() {
        //         },
        //         complete:function() {
        //         }
        //     });
        // }
    </script>
@endsection
