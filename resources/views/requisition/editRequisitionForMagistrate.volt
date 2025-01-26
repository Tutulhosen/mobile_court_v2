<table width="100%" class="outter">

    <tr>
        <td>
            <table class="text" border="0" cellpadding="4" cellspacing="3" width="100%">
                <tr >
                    <td colspan="2" class="formHeading">বিস্তারিত</td>
                </tr>
                <tr height="10px">
                    <td colspan="2"></td>
                </tr>

                <tr>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span> Complain</td>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span> Complain Of Type </td>
                </tr>

                <tr>
                    <td align="left">
                        <?php echo $this->tag->textField(array("complain_type_id", "type" => "number")) ?>
                    </td>
                    <td align="left">
                        <?php echo $this->tag->textField(array("complain_id", "type" => "number")) ?>
                    </td>
                </tr>

                <tr>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span> Location</td>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span> Description </td>
                </tr>
                <tr>

                    <td align="left">
                        <?php echo $this->tag->textField(array("location", "size" => 30)) ?>
                    </td>
                    <td align="left">
                        <?php echo $this->tag->textField(array("description", "size" => 30)) ?>
                    </td>
                </tr>
                <tr>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span> name_bng</td>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span> complain_details </td>
                </tr>
                <tr>

                    <td align="left">
                        <?php echo $this->tag->textField(array("name_bng", "size" => 30)) ?>
                    </td>
                    <td align="left">
                        <?php echo $this->tag->textField(array("complain_details", "size" => 30)) ?>
                    </td>
                </tr>


                <tr>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span> complain_date</td>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span> location </td>
                </tr>
                <tr>

                    <td align="left">
                        <?php echo $this->tag->textField(array("complain_date", "size" => 30)) ?>
                    </td>
                    <td align="left">
                        <?php echo $this->tag->textField(array("location", "size" => 30)) ?>
                    </td>
                </tr>

                <tr>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span> divid</td>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span> zillaId </td>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span> upazilaid </td>
                </tr>
                <tr>

                    <td align="left">
                        <?php echo $this->tag->textField(array("divid", "size" => 30)) ?>
                    </td>
                    <td align="left">
                        <?php echo $this->tag->textField(array("zillaId", "size" => 30)) ?>
                    </td>
                    <td align="left">
                        <?php echo $this->tag->textField(array("upazilaid", "size" => 30)) ?>
                    </td>
                </tr>

                <tr>
                    <td><?php echo $this->tag->hiddenField("id") ?></td>
                    <!--  <td><?php echo $this->tag->submitButton("Search") ?></td> -->
                </tr>

            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%">
                <tr>
                    <td align="right"><?php echo $this->tag->linkTo(array("home/index", "প্রথম পাতা")) ?></td>
                <tr>
            </table>
        </td>
    </tr>

</table>


