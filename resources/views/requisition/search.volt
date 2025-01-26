<?php use Phalcon\Tag; ?>

<?php echo $this->getContent(); ?>

<table width="100%">
    <tr>
        <td align="left">
            <?php echo $this->tag->linkTo(array("requisition/index", "প্রথম পাতা")); ?>
        </td>
        <td align="right">
            <?php echo $this->tag->linkTo(array("requisition/new", "Create ")); ?>
        </td>
    <tr>
</table>

<table class="browse" align="center">
    <thead>
        <tr>
            <th>Id</th>
            <th>Complain</th>
            <th>Complain Of Type</th>
            <th>Authority Of Own</th>
            <th>Magistrate Of Own</th>
            <th>Status Of Own</th>
            <th>Authority Of 1</th>
            <th>Magistrate Of 1</th>
            <th>Status Of 1</th>
            <th>Authority Of 2</th>
            <th>Magistrate Of 2</th>
            <th>Status Of 2</th>
            <th>Authority Of 3</th>
            <th>Magistrate Of 3</th>
            <th>Status Of 3</th>
            <th>Authority Of 4</th>
            <th>Magistrate Of 4</th>
            <th>Status Of 4</th>
            <th>Status</th>
            <th>Dateofcourt</th>
            <th>Location</th>
            <th>Description</th>
            <th>Created Of By</th>
            <th>Created Of Date</th>
            <th>Update Of By</th>
            <th>Update Of Date</th>
            <th>Delete Of Status</th>
         </tr>
    </thead>
    <tbody>
    <?php foreach ($page->items as $requisition) { ?>
        <tr>
            <td><?php echo $requisition->id ?></td>
            <td><?php echo $requisition->complain_id ?></td>
            <td><?php echo $requisition->complain_type_id ?></td>
            <td><?php echo $requisition->authority_own_id ?></td>
            <td><?php echo $requisition->magistrate_own_id ?></td>
            <td><?php echo $requisition->status_own ?></td>
            <td><?php echo $requisition->authority_1_id ?></td>
            <td><?php echo $requisition->magistrate_1_id ?></td>
            <td><?php echo $requisition->status_1 ?></td>
            <td><?php echo $requisition->authority_2_id ?></td>
            <td><?php echo $requisition->magistrate_2_id ?></td>
            <td><?php echo $requisition->status_2 ?></td>
            <td><?php echo $requisition->authority_3_id ?></td>
            <td><?php echo $requisition->magistrate_3_id ?></td>
            <td><?php echo $requisition->status_3 ?></td>
            <td><?php echo $requisition->authority_4_id ?></td>
            <td><?php echo $requisition->magistrate_4_id ?></td>
            <td><?php echo $requisition->status_4 ?></td>
            <td><?php echo $requisition->status ?></td>
            <td><?php echo $requisition->dateofcourt ?></td>
            <td><?php echo $requisition->location ?></td>
            <td><?php echo $requisition->description ?></td>
            <td><?php echo $requisition->created_by ?></td>
            <td><?php echo $requisition->created_date ?></td>
            <td><?php echo $requisition->update_by ?></td>
            <td><?php echo $requisition->update_date ?></td>
            <td><?php echo $requisition->delete_status ?></td>
            <td><?php echo $this->tag->linkTo(array("requisition/edit/" . $requisition->id, "Edit")); ?></td>
            <td><?php echo $this->tag->linkTo(array("requisition/delete/" . $requisition->id, "Delete")); ?></td>
        </tr>
    <?php } ?>
    </tbody>
    <tbody>
        <tr>
            <td colspan="2" align="right">
                <table align="center">
                    <tr>
                        <td><?php echo $this->tag->linkTo("requisition/search", "প্রথম") ?></td>
                        <td><?php echo $this->tag->linkTo("requisition/search?page=" . $page->before, "আগে") ?></td>
                        <td><?php echo $this->tag->linkTo("requisition/search?page=" . $page->next, "পরে") ?></td>
                        <td><?php echo $this->tag->linkTo("requisition/search?page=" . $page->last, "শেষ") ?></td>
                        <td><?php echo $page->current, "/", $page->total_pages ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    <tbody>
</table>
