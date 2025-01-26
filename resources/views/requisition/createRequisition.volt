<?php echo $this->tag->form(array("requisition/create", "method" => "post" ,"id" => "requsitionform")) ?>




<ul class="pager">
    <li class="আগে pull-left">
        {{ link_to("requisition/index", "&larr; প্রথম পাতা") }}
    </li>
    <li class="pull-right">
        {{ link_to("requisition/create", "Save", "class": "btn btn-primary") }}
    </li>
</ul>

<?php echo $this->getContent(); ?>

<div align="center">
    <h1>Create requisition</h1>
</div>

<table width="100%" class="outter">

<tr>
    <td>
            <table class="text" border="0" cellpadding="4" cellspacing="3" width="100%">
                <tr >
                    <td colspan="2" class="formHeading">বিস্তারিত অভিযোগ </td>
                </tr>
                <tr height="10px">
                    <td colspan="2"></td>
                </tr>

                <tr>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>নাম (বাংলা)</td>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>নাম (ইংরেজি) </td>
                </tr>

                <tr>
                    <td align="left">
                        <?php echo $this->tag->textField(array("name_bng",'class' => "input" ,"readonly")) ?>
                    </td>
                    <td align="left">
                        <?php echo $this->tag->textField(array("name_eng",'class' => "input" ,"readonly" )) ?>
                    </td>
                </tr>

                <tr>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>Complain Of Details</td>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>Complain Of Date </td>
                </tr>

                <tr>

                    <td align="left">
                        <?php echo $this->tag->textArea(array("complain_details", "cols" => 50, "rows" => 4 ,'class' => "input")) ?>
                    </td>
                    <td align="left">
                        <?php echo $this->tag->textField(array("complain_date", 'class' => "input")) ?>
                    </td>
                </tr>

                <tr>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>Location</td>
                     <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>District</td>

                </tr>

                <tr>
                    <td align="left">
                        <?php echo $this->tag->textField(array("location",'class' => "input" )) ?>
                    </td>
                                   <td align="left">
                                       <?php echo $this->tag->textField(array("district_id",'class' => "input" )) ?>
                                   </td>

                </tr>

                <tr>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>Feedback</td>
                   <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>Complain Of Status </td>
                </tr>

                <tr>
                    <td align="left">
                         <?php echo $this->tag->textArea(array("feedback", "cols" => 50, "rows" => 4 ,'class' => "input")) ?>
                    </td>

                                   <td align="left">
                                      <!-- <?php echo $this->tag->textField(array("complain_status",'class' => "input" )) ?> -->
                                      <?php  echo Phalcon\Tag::selectStatic("complain_status",
                                      array("1" => "ভবিষ্যতে  আমলে নেয়া হবে",
                                            "2" => "পরিত্যাক্ত",
                                            "3" => "আদালত গঠন করা হউক",
                                            ))
                                      ?>
                                   </td>
                </tr>
 <!--
                <tr>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>Magistrate</td>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>Created Of By </td>
                </tr>

                <tr>
                    <td align="left">
                        <?php echo $this->tag->textField(array("magistrate_id",'class' => "input" )) ?>
                    </td>
                    <td align="left">
                        <?php echo $this->tag->textField(array("created_by",'class' => "input" )) ?>
                    </td>
                </tr>

                <tr>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>Created Of Date</td>
                    <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>Update Of By </td>
                </tr>

                <tr>
                    <td align="left">
                        <?php echo $this->tag->textField(array("created_date",'class' => "input" )) ?>
                    </td>
                    <td align="left">
                        <?php echo $this->tag->textField(array("update_by",'class' => "input" )) ?>
                    </td>
                </tr>


            <tr>
               <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>Update Of Date</td>
               <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>Delete Of Status</td>
            </tr>

            <tr>
               <td align="left">
                   <?php echo $this->tag->textField(array("update_date",'class' => "input" )) ?>
               </td>
               <td align="left">
                   <?php echo $this->tag->textField(array("delete_status",'class' => "input" )) ?>
               </td>
            </tr>


            <tr>

               <td align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000"></span>Complain</td>
            </tr>

            <tr>

               <td align="left">
                   <?php echo $this->tag->textField(array("complain_id",'class' => "input" )) ?>
               </td>
            </tr>
            -->

                                   <tr>
                                       <td><?php echo $this->tag->hiddenField("id") ?></td>
                                  <!--      <td><?php echo $this->tag->submitButton("Search") ?></td> -->
                                   </tr>

            <tr>
                <td  td colspan="2"align="left" bgcolor="#E0F0E8" width="32%"><span style="color:#FF0000">*</span>সংশ্লিষ্ঠ কর্তৃপক্ষ </td>
            </tr>

            <tr>
                <td td colspan="2" align="left">
                   <div id="authority_id"></div>
                       <div>Active node: <span id="echoActive1"></span></div>
                      <!--  <div>Selection: <span id="echoSelection1"></span></div> -->

                </td>

            </tr>
            </table>
    </td>
</tr>
</table>
<tr>
<td>
     <table width="100%">
         <tr>
             <td align="right"><?php echo $this->tag->linkTo(array("home/index", "প্রথম পাতা")) ?></td>
             <td align="right"><?php echo $this->tag->linkTo(array("complain/new", "আদালত গঠন")) ?></td>
             <td align="middle"><?php echo $this->tag->submitButton("সংরক্ষণ") ?></td>
         <tr>
     </table>
</td>
</tr>

</table>
</form>