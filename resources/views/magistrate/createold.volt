
<form method="post" autocomplete="off">

<ul class="pager">
    <li class="আগে pull-left">
        {{ link_to("magistrate", "&larr; প্রথম পাতা") }}
    </li>
    <li class="pull-right">
        {{ submit_button("Save", "class": "btn btn-success") }}
    </li>
</ul>

{{ content() }}

<div class="center scaffold">
    <h2>Create a magistrate</h2>
  <div class="span3">

                 <div class="clearfix">
                     <label for="name_eng">name_eng</label>
                     {{ form.render("name_eng") }}
                 </div>

                 <div class="clearfix">
                     <label for="name_bng">name_bng</label>
                     {{ form.render("name_bng") }}
                 </div>

                 <div class="clearfix">
                     <label for="email">E-Mail</label>
                     {{ form.render("email") }}
                 </div>

                 <div class="clearfix">
                     <label for="permanent_address">permanent_address</label>
                     {{ form.render("permanent_address") }}
                 </div>
                 <div class="clearfix">
                     <label for="designation_bng">designation_bng</label>
                     {{ form.render("designation_bng") }}
                 </div>
        </div>


        <div class="span3">

                 <div class="clearfix">
                     <label for="service_id">service_id</label>
                     {{ form.render("service_id") }}
                 </div>

                 <div class="clearfix">
                     <label for="phone">phone</label>
                     {{ form.render("phone") }}
                 </div>

                 <div class="clearfix">
                     <label for="date_of_birth">date_of_birth</label>
                     {{ form.render("date_of_birth") }}
                 </div>

                 <div class="clearfix">
                     <label for="national_id">national_id</label>
                     {{ form.render("national_id") }}
                 </div>
                 <div class="clearfix">
                     <label for="office_name_bng">office_name_bng</label>
                     {{ form.render("office_name_bng") }}
                 </div>

                 <div class="clearfix">
                     <label for="office_id">office_id</label>
                     {{ form.render("office_id") }}
                 </div>
        </div>
  <div class="span3">
               <div class="clearfix">
                     <label for="present_address">present_address</label>
                     {{ form.render("present_address") }}
                 </div>
                 <div class="clearfix">
                     <label for="mobile">mobile</label>
                     {{ form.render("mobile") }}
                 </div>

                 <div class="clearfix">
                     <label for="designation_bng">designation_bng</label>
                     {{ form.render("designation_bng") }}
                 </div>

                 <div class="clearfix">
                     <label for="designation_eng">designation_eng</label>
                     {{ form.render("designation_eng") }}
                 </div>
               <div class="clearfix">
                     <label for="office_name_eng">office_name_eng</label>
                     {{ form.render("office_name_eng") }}
                 </div>



  </div>

</div>

</form>