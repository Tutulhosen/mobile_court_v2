{{ content() }}

<ul class="pager">
    <li class="আগে pull-left">
        {{ link_to("home/index", "&larr; প্রথম পাতা") }}
    </li>
    <!--   <li class="pull-right">
        {{ link_to("requisition/new", "Create ", "class": "btn btn-primary") }}
    </li>
    -->
</ul>

{% for it in page.items %}
    {% if loop.first %}
        <table class="table table-bordered table-striped" align="center">
        <caption class="caption-custom">অভিযোগের  তালিকা</caption>
        <thead>
        <tr>
            <th> অভিযোগের আইডি</th>

            {#<th>তারিখ</th>#}
            <th>  প্রাপ্তির ধরন</th>
            <th>অভিযোগ</th>
            <th>বিভাগ</th>
            <th> জ়েলা</th>
            <th>উপজেলা</th>
            <th>ঘটনাস্থল</th>


            <th colspan="3">কার্যক্রম</th>
        </tr>

        </thead>
    {% endif %}
    <tbody>
    <tr>
            {#<td>{{ it.id }} </td>#}
            <td> {{ it.complain_id }}</td>

            <td>{{ it.complain_type }}</td>
            <td>{{ it.subject }}</td>
            <td>{{ it.divname }}</td>
            <td>{{ it.zillaname }} </td>
            <td>{{ it.upazilaname }} </td>
            <td>{{ it.location }} </td>

            {#<td>{{ it.dateofcourt }}</td>#}
            {#<td>{{ it.magistrate_own_id }}</td>#}
            {#<td>{{ it.status_own }}</td>#}
            {#<td>{{ it.authority_1_id }}</td>#}
            {#<td>{{ it.magistrate_1_id }}</td>#}
            {#<td>{{ it.status_1 }}</td>#}
            {#<td>{{ it.authority_2_id }}</td>#}
            {#<td>{{ it.magistrate_2_id }}</td>#}
            {#<td>{{ it.status_2 }}</td>#}
            {#<td>{{ it.authority_3_id }}</td>#}
            {#<td>{{ it.magistrate_3_id }}</td>#}
            {#<td>{{ it.status_3 }}</td>#}
            {#<td>{{ it.authority_4_id }}</td>#}
            {#<td>{{ it.magistrate_4_id }}</td>#}
            {#<td>{{ it.status_4 }}</td>#}
            {#<td>{{ it.status }}</td>#}
            {#<td>{{ it.dateofcourt }}</td>#}
            {#<td>{{ it.location }}</td>#}
            {#<td>{{ it.description }}</td>#}
            {#<td>{{ it.created_by }}</td>#}
            {#<td>{{ it.created_date }}</td>#}
            {#<td>{{ it.update_by }}</td>#}
            {#<td>{{ it.update_date }}</td>#}
            {#<td>{{ it.delete_status }}</td>#}

        <td width="12%">{{ link_to("court/assignRequisitionToNewCourt/" ~ it.id, '<i class="icon-pencil"></i> নতুন আদালত ', "class": "btn") }}</td>
        <td width="12%">{{ link_to("court/assignRequisitionToExistingCourt/" ~ it.id, '<i class="icon-pencil"></i> চলমান আদালত ', "class": "btn") }}</td>
        <td width="12%">{{ link_to("requisition/editRequisitionForMagistrate/" ~ it.id, '<i class="icon-pencil"></i> খুলুন', "class": "btn") }}</td>
    </tr>
    </tbody>

    {% if loop.last %}
        <tbody>
        <tr>
            <td colspan="10" align="right">
                <div class="btn-group">
                    {{ link_to("requisition/showRequisitionForMagistrate", '<i class="icon-fast-backward"></i> প্রথম', "class": "btn") }}
                    {{ link_to("requisition/showRequisitionForMagistrate?page=" ~ page.before, '<i class="icon-step-backward"></i> Previous', "class": "btn ") }}
                    {{ link_to("requisition/showRequisitionForMagistrate?page=" ~ page.next, '<i class="icon-step-forward"></i> পরে', "class": "btn") }}
                    {{ link_to("requisition/showRequisitionForMagistrate?page=" ~ page.last, '<i class="icon-fast-forward"></i> শেষ', "class": "btn") }}
                    <span class="help-inline">{{ page.current }}/{{ page.total_pages }}</span>
                </div>
            </td>
        </tr>
        <tbody>
        </table>
    {% endif %}
{% else %}
    No citizen_complain are recorded
{% endfor %}
