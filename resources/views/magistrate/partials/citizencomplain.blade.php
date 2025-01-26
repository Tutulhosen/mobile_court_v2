<div id="previewcitizencomplain" class="content_form">
    <div class="form_top_title">
        <h3>   অপরাধের তথ্যের তালিকা </h3>
        <h3 class="top_title_2nd"></h3>
    </div>

    <table id='scores'  border="1" style="border-collapse:collapse;" cellpadding="2px" cellspacing="2px" width="100%">
        <th style="width: 120px">অভিযোগের আইডি</th>
        <th style="width: 100px">অভিযোগকারীর নাম</th>
        <th style="width: 100px">মোবাইল </th>
        <th style="width: 100px">অভিযোগ</th>
        <th style="width: 100px">অভিযোগের তারিখ</th>
        <th style="width: 100px">ঘটনাস্থল</th>

        </table>


        <style>
   .content_form
   {
       /*min-height: 842px;*/
       width: 595px;
       margin-left: auto;
       margin-right: auto;
       border: 1px dotted gray;
       font-family: nikoshBan;
   }
  .form_top_title
   {
      font-size: 24px;
   }
   {
       margin-top: -18px;
   }

   @media print {
       .content_form {
       border: 0px dotted;
       }
   }

   p.p_indent
   {
     text-indent: 30px;
   }

   h3
   {
    text-align: center;
   }

   h3.top_title_2nd
   {
    margin-top: -18px;
   }

   .clear_div
   {
    clear: both;
    width: 100%;
    height: 20px;
   }
   br
   {
    line-height:5px;
   }
</style>

</div>
<script>
    function setParamsforComplainList(data){
        var myTable = document.getElementById("scores");
        var rowCount = myTable.rows.length;
        for (var x=rowCount-1; x>0; x--) {
            myTable.deleteRow(x);
        }

        $(data.citizenComplain).each(function(index, element){
         $('#scores').append(
                 '<tr><td> '+element['user_idno']+ '</td>' +
                     '<td> '+element['name_bng']+ '</td>'  +
                     '<td> '+element['mobile']+ '</td>'  +
                     '<td> '+element['subject']+ '</td>'  +
                     '<td> '+element['cdate']+ '</td>'  +
                     '<td> '+element['location'] +'</td>'  +
                 '</tr>');
        })

    }


</script>
