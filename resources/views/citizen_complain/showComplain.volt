{{ content() }}

<ul class="pager">
    <li class="আগে pull-left">
        {{ link_to("home/index", "&larr; প্রথম পাতা") }}
    </li>
    <li class="pull-right">
        {{ link_to("citizen_complain/create", "Create citizen_complain", "class": "btn btn-primary") }}
    </li>
</ul>

{% for it in page.items %}
{% if loop.first %}
<table class="table table-bordered table-striped" align="center">
    <thead>
        <tr>
                   <th>নাম(বাংলা) </th>
                   <th>নাম (ইংরেজি)</th>
                   <th>অভিযোগ</th>
                   <th> অভিযোগের তারিখ ও সময়</th>
                   <th>ঘটনাস্থান</th>
                   <th>ঘটনার তারিখ  </th>
            {#<th>অভিযোগের অবস্থা</th>#}
                   {#<th>ফিডব্যাক</th>#}
                   {#<th>ফিডব্যাক তারিখ</th>#}
             <!--  <th>Magistrate</th>
                   <th>Created Of By</th>
                   <th>Created Of Date</th>
                   <th>Update Of By</th>
                   <th>Update Of Date</th>
                   <th>Delete Of Status</th>
                   <th>Id</th>	-->
            <th>বিভাগ </th>
            <th> জ়েলা </th>
            <th>উপজেলা </th>

       	{#<th>অভিযোগের আইডি</th>#}
       	<th colspan="2">কার্যক্রম </th>
        </tr>
    </thead>
{% endif %}
    <tbody>
        <tr>
            <td>{{ it.name_bng }}</td>
            <td>{{ it.name_eng }}</td>
            <td>{{ it.complain_details }}</td>
            <td>{{ it.created_date }}</td>
            <td>{{ it.location}}</td>
            <td>{{ it.complain_date}}</td>
            {#<td>{{ it.complain_status }}</td>#}
            {#<td>{{ it.feedback}}</td>#}
            {#<td>{{ it.feedback_date}}</td>#}
 <!--              <td>{{ it.magistrate_id}}</td>
            <td>{{ it.created_by}}</td>
            <td>{{ it.created_date}}</td>
            <td>{{ it.update_by}}</td>
            <td>{{ it.update_date}}</td>
            <td>{{ it.delete_status}}</td>
            <td>{{ it.id}}</td>-->
            <td>{{ it.divid}}</td>
            <td>{{ it.zillaId}}</td>
            <td>{{ it.upazilaid}}</td>
            {#<td>{{ it.complain_id}}</td>#}

            <td width="12%">{{ link_to("citizen_complain/edit/" ~ it.id, '<i class="icon-pencil"></i> Edit', "class": "btn") }}</td>
            <td width="12%">{{ link_to("citizen_complain/delete/" ~ it.id, '<i class="icon-remove"></i> Delete', "class": "btn") }}</td>
        </tr>
    </tbody>
{% if loop.last %}
    <tbody>
        <tr>
            <td colspan="10" align="right">
                <div class="btn-group">
                    {{ link_to("citizen_complain/showComplain", '<i class="icon-fast-backward"></i> প্রথম', "class": "btn") }}
                    {{ link_to("citizen_complain/showComplain?page=" ~ page.before, '<i class="icon-step-backward"></i> আগে', "class": "btn ") }}
                    {{ link_to("citizen_complain/showComplain?page=" ~ page.next, '<i class="icon-step-forward"></i> পরে', "class": "btn") }}
                    {{ link_to("citizen_complain/showComplain?page=" ~ page.last, '<i class="icon-fast-forward"></i> শেষ', "class": "btn") }}
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
