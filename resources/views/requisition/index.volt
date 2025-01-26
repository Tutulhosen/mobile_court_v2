
<?php echo $this->getContent() ?>

<div align="right">
    <?php echo $this->tag->linkTo(array("requisition/new", "Create requisition")) ?>
</div>

<?php echo $this->tag->form(array("requisition/search", "autocomplete" => "off")) ?>

<div align="center">
    <h1>Search requisition</h1>
</div>

<table>
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
            <label for="complain_id">Complain</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("complain_id", "type" => "number")) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="complain_type_id">Complain Of Type</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("complain_type_id", "type" => "number")) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="authority_own_id">Authority Of Own</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("authority_own_id", "type" => "number")) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="magistrate_own_id">Magistrate Of Own</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("magistrate_own_id", "type" => "number")) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="status_own">Status Of Own</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("status_own", "type" => "number")) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="authority_1_id">Authority Of 1</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("authority_1_id", "type" => "number")) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="magistrate_1_id">Magistrate Of 1</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("magistrate_1_id", "type" => "number")) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="status_1">Status Of 1</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("status_1", "type" => "number")) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="authority_2_id">Authority Of 2</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("authority_2_id", "type" => "number")) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="magistrate_2_id">Magistrate Of 2</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("magistrate_2_id", "type" => "number")) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="status_2">Status Of 2</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("status_2", "type" => "number")) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="authority_3_id">Authority Of 3</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("authority_3_id", "type" => "number")) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="magistrate_3_id">Magistrate Of 3</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("magistrate_3_id", "type" => "number")) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="status_3">Status Of 3</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("status_3", "type" => "number")) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="authority_4_id">Authority Of 4</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("authority_4_id", "type" => "number")) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="magistrate_4_id">Magistrate Of 4</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("magistrate_4_id", "type" => "number")) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="status_4">Status Of 4</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("status_4", "type" => "number")) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="status">Status</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("status", "type" => "number")) ?>
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="dateofcourt">Dateofcourt</label>
        </td>
        <td align="left">
                <?php echo $this->tag->textField(array("dateofcourt", "type" => "date")) ?>
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
            <label for="description">Description</label>
        </td>
        <td align="left">
            <?php echo $this->tag->textField(array("description", "size" => 30)) ?>
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
                <?php echo $this->tag->textField(array("created_date", "type" => "date")) ?>
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
                <?php echo $this->tag->textField(array("update_date", "type" => "date")) ?>
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
        <td></td>
        <td><?php echo $this->tag->submitButton("Search") ?></td>
    </tr>
</table>

</form>
