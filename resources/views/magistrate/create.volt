<form method="post" autocomplete="off">

    <ul class="pager">
        <li class="আগে pull-left">
            {{ link_to("magistrate", "&larr; প্রথম পাতা") }}
        </li>
        {#<li class="pull-right">#}
            {#{{ submit_button("Save", "class": "btn btn-success") }}#}
        {#</li>#}
    </ul>

    {{ content() }}

    <div class="center scaffold">
        {#<h2>Create a magistrate</h2>#}

        <div class="clearfix">
            <table width="100%" class="outter">

                <tr>
                    <td>
                        <table class="text" border="0" cellpadding="4" cellspacing="3" width="100%">
                            <tr >
                                <td align="left" bgcolor="#E0F0E8"><span style="color:#FF0000">*</span>নাম (ইংরেজি)</td>
                                <td align="left" bgcolor="#E0F0E8"><span style="color:#FF0000">*</span>নাম(বাংলা)</td>

                            </tr>
                            <tr>

                                <td align="left"> {{ form.render("name_eng") }} </td>
                                <td align="left"> {{ form.render("name_bng") }} </td>

                            </tr>

                            <tr >
                                <td align="left" bgcolor="#E0F0E8"><span style="color:#FF0000">*</span>স্থায়ী ঠিকানা</td>
                                {#<td align="left" bgcolor="#E0F0E8"><label for="office_id">অফিস আইডি</label></td>#}
                                <td align="left" bgcolor="#E0F0E8"><span style="color:#FF0000">*</span>বর্তমান ঠিকানা  </td>

                            </tr>
                            <tr>

                                <td align="left"> {{ form.render("permanent_address") }}</td>
                                {#<td align="left"> {{ form.render("office_id") }} </td>#}
                                <td align="left"> {{ form.render("present_address") }} </td>

                            </tr>

                            <tr >
                                <td align="left" bgcolor="#E0F0E8"><span style="color:#FF0000">*</span>র্সাভিস আইডি </td>

                                <td align="left" bgcolor="#E0F0E8"><span style="color:#FF0000">*</span>জাতীয় পরিচয় পত্র নম্বর </td>
                            </tr>
                            <tr>

                                <td align="left"> {{ form.render("service_id") }} </td>

                                <td align="left"> {{ form.render("national_id") }} </td>
                            </tr>

                            <tr >
                                {#<td align="left" bgcolor="#E0F0E8"><label for="office_name_bng">অফিস  (ইংরেজি)</label></td>#}
                                {##}
                                {#<td align="left" bgcolor="#E0F0E8"><label for="designation_bng">অফিস (বাংলা)</label></td>#}
                                <td align="left" bgcolor="#E0F0E8"><span style="color:#FF0000">*</span>ই-মেইল</td>
                                <td align="left" bgcolor="#E0F0E8"><span style="color:#FF0000">*</span>জন্ম তারিখ</td>
                                </td>
                            </tr>
                            <tr>
                                <td align="left"> {{ form.render("email") }} </td>
                                {#<td align="left"> {{ form.render("office_name_bng") }} </td>#}
                                {#<td align="left">{{ form.render("office_name_eng") }}</td>#}
                                {#<td align="left"> {{ form.render("date_of_birth") }} </td>#}
                                <td align="left"> <input class="input" name="date_of_birth" id="date" value="" type="text" /> </td>


                            </tr>

                            <tr >
                                <td align="left" bgcolor="#E0F0E8"><span style="color:#FF0000">*</span>মোবাইল  </td>
                                <td align="left" bgcolor="#E0F0E8"><span style="color:#FF0000"></span>ফোন নম্বর</td>
                                {#<td align="left" bgcolor="#E0F0E8"><label for="designation_bng">পদবী (বাংলা)</label>#}
                                {#</td>#}
                                {#<td align="left" bgcolor="#E0F0E8"><label for="designation_eng">পদবী  (ইংরেজি)</label>#}
                                {#</td>#}
                            </tr>
                            <tr>

                                <td align="left"> {{ form.render("mobile") }} </td>
                                <td align="left"> {{ form.render("phone") }} </td>
                                {#<td align="left"> {{ form.render("designation_bng") }} </td>#}
                                {#<td align="left"> {{ form.render("designation_eng") }} </td>#}
                            </tr>
                            <tr>
                                <td align="left"
                                    colspan="3"> {{ submit_button("Save", "class": "btn-large btn-primary") }} </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    </div>
</form>