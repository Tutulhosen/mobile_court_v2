{{ content() }}

<ul class="pager">
    <li class="আগে pull-left">
        {{ link_to("home/index", "&larr; প্রথম পাতা") }}
    </li>
    <li class="pull-right">
        {{ link_to("requisition/new", "Create ", "class": "btn btn-primary") }}
    </li>
</ul>

{% for it in page.items %}
{% if loop.first %}
<table class="table table-bordered table-striped" align="center">
    <thead>
        <tr>
            <th>  ক্রমিক নম্বর</th>
            <th>অভিযোগ </th>

            <th>এক্সিকিউটিভ ম্যাজিস্ট্রেটের  </th>
       <!--      <th>Authority Of Own</th>
             <th>Complain Of Type</th>
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
            <th>Delete Of Status</th>  -->
            <th colspan="2">কার্যক্রম </th>
         </tr>
    </thead>
{% endif %}
    <tbody>
        <tr>
            <td>{{ it.id }} </td>
            <td> {{ it.complain_id }}</td>

            <td>{{ it.magistrate_own_id }}</td>
       <!--     <td>{{ it.authority_own_id }}</td>
             <td>{{ it.complain_type_id }} </td>
            <td>{{ it.status_own }}</td>
            <td>{{ it.authority_1_id }}</td>
            <td>{{ it.magistrate_1_id }}</td>
             <td>{{ it.status_1 }}</td>
            <td>{{ it.authority_2_id }}</td>
            <td>{{ it.magistrate_2_id }}</td>
            <td>{{ it.status_2 }}</td>
            <td>{{ it.authority_3_id }}</td>
            <td>{{ it.magistrate_3_id }}</td>
             <td>{{ it.status_3 }}</td>
            <td>{{ it.authority_4_id }}</td>
            <td>{{ it.magistrate_4_id }}</td>
             <td>{{ it.status_4 }}</td>
            <td>{{ it.status }}</td>
            <td>{{ it.dateofcourt }}</td>
            <td>{{ it.location }}</td>
              <td>{{ it.description }}</td>
            <td>{{ it.created_by }}</td>
            <td>{{ it.created_date }}</td>
            <td>{{ it.update_by }}</td>
            <td>{{ it.update_date }}</td>
            <td>{{ it.delete_status }}</td>  -->


            <td width="12%">{{ link_to("requisition/edit2Requisition/" ~ it.id, '<i class="icon-pencil"></i> Edit', "class": "btn") }}</td>
            <td width="12%">{{ link_to("requisition/delete/" ~ it.id, '<i class="icon-remove"></i> Delete', "class": "btn") }}</td>
        </tr>
    </tbody>

    {% if loop.last %}
        <tbody>
            <tr>
                <td colspan="10" align="right">
                    <div class="btn-group">
                        {{ link_to("requisition/showOwnRequisitionList", '<i class="icon-fast-backward"></i> প্রথম', "class": "btn") }}
                        {{ link_to("requisition/showOwnRequisitionList?page=" ~ page.before, '<i class="icon-step-backward"></i> Previous', "class": "btn ") }}
                        {{ link_to("requisition/showOwnRequisitionList?page=" ~ page.next, '<i class="icon-step-forward"></i> পরে', "class": "btn") }}
                        {{ link_to("requisition/showOwnRequisitionList?page=" ~ page.last, '<i class="icon-fast-forward"></i> শেষ', "class": "btn") }}
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
