{{ content() }}

<form method="post" action="{{ url("magistrate/search") }}" autocomplete="off">

    <div class="center scaffold">

        <h2>এক্সিকিউটিভ ম্যাজিস্ট্রেটের   অনুসন্ধান করুন</h2>
            <div class="clearfix">
                <table width="100%" class="outter">

                    <tr>
                        <td>
                            <table class="text" border="0" cellpadding="4" cellspacing="3" width="100%">
                                <tr >
                                    <td align="left" bgcolor="#E0F0E8" ><label for="name_bng">নাম</label></td>
                                    <td align="left" bgcolor="#E0F0E8"><label for="mobile">মোবাইল নন্বর </label></td>
                                    <td align="left" bgcolor="#E0F0E8"><label for="service_id">র্সাভিস আইডি  </label></td>
                                    <td align="left" bgcolor="#E0F0E8"><label for="national_id">জাতীয় পরিচয় পত্র নন্বর</label></td>
                                </tr>
                                <tr>
                                    <td  align="left">
                                        <?php echo $this->tag->textField(array("name_eng",'class' => "input" ,"readonly")) ?>
                                    </td>
                                    <td  align="left">
                                        <?php echo $this->tag->textField(array("mobile",'class' => "input" ,"readonly")) ?>
                                    </td>
                                    <td  align="left">
                                        <?php echo $this->tag->textField(array("service_id",'class' => "input" ,"readonly")) ?>
                                    </td>
                                    <td  align="left">
                                        <?php echo $this->tag->textField(array("national_id",'class' => "input" ,"readonly")) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" colspan="4"> {{ submit_button("অনুসন্ধান", "class": "btn-large btn-primary") }} </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>

        <h2>
            এক্সিকিউটিভ ম্যাজিস্ট্রেটের ের  প্রোফাইল গঠন
        </h2>
        <div class="clearfix">
            <table width="100%" class="outter">

                <tr>
                    <td>
                        <table class="text" border="0" cellpadding="4" cellspacing="3" width="100%">
                            <tr height="80px">
                                <td align="left" colspan="4"> {{ link_to("magistrate/create", "<i class='icon-plus-sign'></i> এক্সিকিউটিভ ম্যাজিস্ট্রেটের ের  প্রোফাইল গঠন ", "class": "btn-large btn-primary") }} </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</form>