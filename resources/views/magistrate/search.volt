{{ content() }}

{#<ul class="pager">#}
    {#<li class="আগে pull-left">#}
        {#{{ link_to("magistrate/index", "&larr; প্রথম পাতা") }}#}
    {#</li>#}
{#</ul>#}

<div class="panel panel-default">
    <div class="panel-heading smx">
        <h2 class="panel-title">জেলা অ্যাডমিন এর তালিকা</h2>
    </div>
    <div class="panel-body cpv">


{% for it in page.items %}
{% if loop.first %}
<table class="table table-bordered table-striped" align="center">
    <thead>
        <tr>

            <th>নাম  </th>
            <th>র্সাভিস আইডি  </th>
            <th>জাতীয় পরিচয় পত্র নম্বর</th>
            <th>ঠিকানা</th>
            <th>ই-মেইল</th>
            <th>মোবাইল</th>
            <th colspan="2">কার্যক্রম </th>
        </tr>
    </thead>
{% endif %}
    <tbody>
        <tr>

            <td>{{ it.name_eng }}</td>

            <td>{{ it.service_id }}</td>
            <td>{{ it.national_id }}</td>

            <td>{{ it.present_address }}</td>

            <td>{{ it.email }}</td>
            <td>{{ it.mobile }}</td>

            <td width="12%">{{ link_to("magistrate/edit/" ~ it.id, '<i class="icon-pencil"></i> দেখুন', "class": "btn") }}</td>
        </tr>
    </tbody>
{% if loop.last %}
    <tbody>
        <tr>
            <td colspan="10" align="right">
                <div class="btn-group">
                    {{ link_to("magistrate/search", '<i class="icon-fast-backward"></i> প্রথম', "class": "btn") }}
                    {{ link_to("magistrate/search?page=" ~ page.before, '<i class="icon-step-backward"></i> আগে', "class": "btn ") }}
                    {{ link_to("magistrate/search?page=" ~ page.next, '<i class="icon-step-forward"></i> পরে', "class": "btn") }}
                    {{ link_to("magistrate/search?page=" ~ page.last, '<i class="icon-fast-forward"></i> শেষ', "class": "btn") }}
                    <span class="help-inline">{{ page.current }}/{{ page.total_pages }}</span>
                </div>
            </td>
        </tr>
    <tbody>
</table>
{% endif %}
{% else %}
    No users are recorded
{% endfor %}

