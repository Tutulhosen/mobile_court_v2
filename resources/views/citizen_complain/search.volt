<?php use Phalcon\Tag; ?>

<?php echo $this->getContent(); ?>

<table width="100%">
    <tr>
        <td align="left">
            <?php echo $this->tag->linkTo(array("home/index", "প্রথম পাতা")); ?>
        </td>
        <td align="right">
            <?php echo $this->tag->linkTo(array("citizen_complain/new", "Create ")); ?>
        </td>
    <tr>
</table>



<p>
    <h2>অভিযোগের তালিকা </h2>
</p>

<table class="table table-bordered table-striped">
    <thead>
               <tr>
                   <th>নাম(বাংলা) </th>
                   <th>নাম (ইংরেজি)</th>
                   <th>অভিযোগ</th>
                   <th> অভিযোগের তারিখ</th>
                   <th>ঘটনাস্থান</th>
                   <th>অভিযোগের অবস্থা</th>
                   <th>ফিডব্যাক</th>
                   <th>ফিডব্যাক তারিখ</th>
             <!--      <th>Magistrate</th>
                   <th>Created Of By</th>
                   <th>Created Of Date</th>
                   <th>Update Of By</th>
                   <th>Update Of Date</th>
                   <th>Delete Of Status</th>
                   <th>Id</th>	-->
                   <th> জ়েলা </th>
       	<th>অভিযোগের আইডি</th>

         </tr>
    </thead>
    <tbody>
       <tbody>
           <?php foreach ($page->items as $citizen_complain) { ?>
               <tr>
                   <td><?php echo $citizen_complain->name_bng ?></td>
                   <td><?php echo $citizen_complain->name_eng ?></td>
                   <td><pre><?php echo $citizen_complain->complain_details ?></pre></td>
                   <td><?php echo $citizen_complain->complain_date ?></td>
                   <td><?php echo $citizen_complain->location ?></td>
                   <td><?php echo $citizen_complain->complain_status ?></td>
                   <td><?php echo $citizen_complain->feedback ?></td>
                   <td><?php echo $citizen_complain->feedback_date ?></td>
                  <!--  <td><?php echo $citizen_complain->magistrate_id ?></td>
                   <td><?php echo $citizen_complain->created_by ?></td>
                   <td><?php echo $citizen_complain->created_date ?></td>
                   <td><?php echo $citizen_complain->update_by ?></td>
                   <td><?php echo $citizen_complain->update_date ?></td>
                   <td><?php echo $citizen_complain->delete_status ?></td>
                   <td><?php echo $citizen_complain->id ?></td> -->
                   <td><?php echo $citizen_complain->district_id ?></td>
                   <td><?php echo $citizen_complain->complain_id ?></td>
                   <td><?php echo $this->tag->linkTo(array("citizen_complain/edit/" . $citizen_complain->name_bng, "Edit")); ?></td>
                   <td><?php echo $this->tag->linkTo(array("citizen_complain/delete/" . $citizen_complain->name_bng, "Delete")); ?></td>
               </tr>
           <?php } ?>
           </tbody>

   <tbody>
           <tr>
                                  <td colspan="12" align="right">
                                      <table align="center">
                                          <tr>

                                          </tr>
                                      </table>
                                  </td>
                              </tr>
   </tbody>



            <tbody>
                   <tr>
                       <td colspan="2" align="right">
                           <table align="center">
                               <tr>
                                   <td><?php echo $this->tag->linkTo("citizen_complain/search", "প্রথম") ?></td>
                                   <td><?php echo $this->tag->linkTo("citizen_complain/search?page=" . $page->before, "আগে") ?></td>
                                   <td><?php echo $this->tag->linkTo("citizen_complain/search?page=" . $page->next, "পরে") ?></td>
                                   <td><?php echo $this->tag->linkTo("citizen_complain/search?page=" . $page->last, "শেষ") ?></td>
                                   <td><?php echo $page->current, "/", $page->total_pages ?></td>
                               </tr>
                           </table>
                       </td>
                   </tr>
               </tbody>
</table>
