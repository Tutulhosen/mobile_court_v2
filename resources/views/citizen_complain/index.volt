
<?php echo $this->getContent() ?>

<div align="right">
    <?php echo $this->tag->linkTo(array("citizen_complain/new", "Create citizen_complain")) ?>
</div>

<?php echo $this->tag->form(array("citizen_complain/search", "autocomplete" => "off")) ?>

<div align="center">
    <h1>Search citizen_complain</h1>
</div>

<table>
    <tr>
        <td align="right">
            <label for="name_bng">Name Of Bng</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("name_bng", "size" => 30)) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="name_eng">Name Of Eng</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("name_eng", "size" => 30)) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="complain_details">Complain Of Details</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("complain_details", "size" => 30)) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="complain_date">Complain Of Date</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("complain_date", "size" => 30)) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="location">Location</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("location", "size" => 30)) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="complain_status">Complain Of Status</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("complain_status", "size" => 30)) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="feedback">Feedback</label>
        </td>
        <td align="left">
                <?php echo $this->tag->textField(array("feedback", "type" => "date")) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="feedback_date">Feedback Of Date</label>
        </td>
        <td align="left">
                <?php echo $this->tag->textField(array("feedback_date", "type" => "date")) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="magistrate_id">Magistrate</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("magistrate_id", "type" => "number")) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="created_by">Created Of By</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("created_by", "size" => 30)) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="created_date">Created Of Date</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("created_date", "size" => 30)) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="update_by">Update Of By</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("update_by", "size" => 30)) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="update_date">Update Of Date</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("update_date", "size" => 30)) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="delete_status">Delete Of Status</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("delete_status", "type" => "number")) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="id">Id</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("id", "type" => "number")) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="district_id">District</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("district_id", "type" => "number")) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="complain_id">Complain</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("complain_id", "type" => "number")) ?>
        </td>
    </tr>

    <tr>
        <td></td>
      <!--  <td><?php echo $this->tag->submitButton("Search") ?></td> -->
    </tr>
</table>

</form>
