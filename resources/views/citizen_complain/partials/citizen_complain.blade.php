
<div id="register_print" class="content_form">
    <div class="form_top_title">
        <h3>   অপরাধের তথ্য  </h3>
    </div>
    <h4>অভিযোগকারীর বিবরণ </h4>
    <p>নাম :&nbsp;<span id="citreq_name">name</span></p>
    <p>ঠিকানা :&nbsp;<span id="citreq_citizen_address">name</span></p>
    <p>মোবাইল  নম্বর : &nbsp;<span id="citreq_mobile">mobile</span></p>
    <p>অভিযোগের তারিখ ও সময় : &nbsp;<span id="citreq_complain_date">complain_date</span></p>
    <p>অভিযোগ আইডি : &nbsp;<span id="citreq_user_idno">user_idno</span></p>
    <p>অভিযোগ : &nbsp;<span id="citreq_complain_details">complain_details</span></p>
    <p><table>
        <tr>
            <td>
                বিভাগ :&nbsp;   <span id="citreq_div">location_str</span>
            </td>
            <td>
                জেলা :&nbsp; <span id="citreq_zilla">location_str</span>
            </td>
        </tr>

        <tr>
            <td>
                উপজেলা  :&nbsp;  <span id="citreq_upazilla">location_str</span>
            </td>
            <td>
                ঘটনাস্থল :&nbsp;  <span id="citreq_location_str">location_str</span>
            </td>
        </tr>

    </table>
    </p>

    <h4>দায়িত্বপ্রাপ্ত এক্সিকিউটিভ ম্যাজিস্ট্রেট</h4>
    <div><p> নাম : &nbsp;<span id="citreq_magistrate">magistrate</span></p></div>
    <div><p>কার্যক্রম গ্রহণের সময়সীমা : &nbsp;<span id="citreq_estimated_date">estimated_date</span></p> </div>
    <div><p>সিদ্ধান্ত: &nbsp;<span id="citreq_requisition_comment">citreq_requisition_comment</span></p> </div>

    <style>
   .content_form
   {
       /*min-height: 842px;  // 596 */
       width: 792px;
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
     text-indent: 10px;
   }

   h3
   {
    text-align: center;
   }
   h4
   {
       text-align: left;
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
    function setParams(data,div,zilla,upazilla){
        document.getElementById("citreq_div").innerHTML= div;
        document.getElementById("citreq_zilla").innerHTML= zilla;
        document.getElementById("citreq_upazilla").innerHTML= upazilla;

        $(data.result).each(function(index, item){

            //alert(JSON.stringify(data.result));
            document.getElementById("citreq_name").innerHTML= item.name;
            document.getElementById("citreq_mobile").innerHTML= item.mobile;
            document.getElementById("citreq_complain_details").innerHTML= item.complain_details;
            document.getElementById("citreq_complain_date").innerHTML= item.complain_date;
            document.getElementById("citreq_user_idno").innerHTML= item.user_idno;
            document.getElementById("citreq_location_str").innerHTML= item.location;
            document.getElementById("citreq_estimated_date").innerHTML= item.estimated_date?item.estimated_date:"";
            document.getElementById("citreq_magistrate").innerHTML= item.magistrate?item.magistrate:"";
            document.getElementById("citreq_citizen_address").innerHTML= item.citizen_address?item.citizen_address:"";
            document.getElementById("citreq_requisition_comment").innerHTML= item.req_comment?item.req_comment:"";
        })

    }

</script>
