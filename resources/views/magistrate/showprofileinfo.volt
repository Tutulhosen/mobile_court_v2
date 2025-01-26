{{ content() }}
<?php echo $this->getContent(); ?>

<div class="panel panel-success">
    <div class="panel-heading smx">
        <h2 class="panel-title"> ম্যজিস্ট্রেটের তালিকা</h2>
    </div>
    <div class="panel-body cpv">
        <div class="row">
            <div class="col-sm-12">
                <div id="dynamic2">
                    <table style="width: 98%" cellpadding="0" cellspacing="0" border="0" class="display table-striped table-bordered" id="magistratelist">

                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-footer"> </div>
</div>





{{ stylesheet_link('css/select2.css') }}
{{ stylesheet_link('css/bootstrap-timepicker.min.css') }}

{{ javascript_include("js/select2.min.js") }}
{{ javascript_include("js/bootstrap-timepicker.min.js") }}
{{ javascript_include("js/jquery-ui-1.10.3.min.js") }}
{{ javascript_include("js/jquery.validate.min.js") }}



{{ stylesheet_link('css/style.datatables.css') }}
<link href="//cdn.datatables.net/responsive/1.0.1/css/dataTables.responsive.css" rel="stylesheet">

{{ javascript_include("js/jquery.dataTables.min.js") }}
<script src="//cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script src="//cdn.datatables.net/responsive/1.0.1/js/dataTables.responsive.js"></script>


<script>
    var oTablereqcase;
    $(document).ready(function () {
        $('#magistratelist').removeClass('display').addClass('table-striped table-bordered');
        $('.dataTables_filter input[type="search"]').attr('placeholder',
                'সার্ভিস আইডি অথবা ইমেইল লিখুন').css({'width':'250px','display':'inline-block'});

    });



    oTablereqcase = $('#magistratelist').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "bFilter" : true,
        crossDomain: true,
        "sAjaxSource": base_path +"/magistrate/magistrateinfolist",
        "aoColumns": [
            { "sTitle": "id" , "sClass": "left", "sWidth": "300px" , "bVisible" : false  },
            { "sTitle": "নাম" , "sClass": "left", "sWidth": "300px"  },
            { "sTitle": "ইমেইল" , "sClass": "left", "sWidth": "300px"  },
            { "sTitle": "সার্ভিস আইডি" , "sClass": "left", "sWidth": "300px" ,"bVisible" : false },
            { "sTitle": "password", "sClass": "right", "sWidth": "100px"  }
        ],
        "aaSorting": [[1, 'desc']],
        "iDisplayStart":0,
        "iDisplayLength":10,
        "bLengthChange": false,
        "oLanguage": {
            "sSearch":"অনুসন্ধান: "
        }

    } );


</script>