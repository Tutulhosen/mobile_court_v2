{{ content() }}


{{ javascript_include("js/admscript/script.js") }}
<ul class="pager">
    <li class="আগে pull-left">
        {{ link_to("magistrate/dashboard", "&larr; প্রথম পাতা") }}
    </li>
</ul>

{% for it in page.items %}
    {% if loop.first %}
        <table class="table table-bordered table-striped" align="center">
        <caption class="caption-custom">নিষ্পত্তিকৃত মামলার   তালিকা</caption>
        <thead>
        <tr>
            {#<th>Id</th>#}
            <th>নম্বর </th>
            {#<th>কোর্ট </th>#}
            <th>প্রসিকিউটর </th>
            <th>বিষয়</th>
            <th>তারিখ </th>
            <th>ঘটনাস্থল  </th>
            {#<th >কার্যক্রম </th>#}

        </tr>
        </thead>
    {% endif %}
    <tbody>
    <tr>

        <td> {{ it.case_no }}</td>
        <td>{{ it.prosecutor_name }} </td>
        <td> {{ it.subject }}</td>
        <td>{{ it.date }} </td>
        <td>{{ it.location }} </td>
        {#<td width="12%"><a class="btn" href="#" onclick="showComplainInformation({{ it.id  }}); return false"> দেখুন  </a></td>#}
    </tr>
    </tbody>
    {% if loop.last %}
        <tbody>
        <tr>
            <td colspan="12" align="center">
                <div class="btn-group">
                    {{ link_to("magistrate/showMagistrateCompleteCase", '<i class="icon-fast-backward"></i> প্রথম', "class": "btn") }}
                    {{ link_to("magistrate/showMagistrateCompleteCase?page=" ~ page.before, '<i class="icon-step-backward"></i> আগে', "class": "btn ") }}
                    {{ link_to("magistrate/showMagistrateCompleteCase?page=" ~ page.next, '<i class="icon-step-forward"></i> পরে', "class": "btn") }}
                    {{ link_to("magistrate/showMagistrateCompleteCase?page=" ~ page.last, '<i class="icon-fast-forward"></i> শেষ', "class": "btn") }}
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


<div id="admdetailsInfo" class="modal hide fade in" style="display: none; ">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
    </div>
    <div class="modal-body">
        <table class="table table-bordered table-striped" align="center">
            <thead>
            <tr>
                <th colspan="4" > অভিযোগকারীর বিবরণ</th>
            </tr>
            </thead>
            <tr>
                <td> অভিযোগকারীর নাম</td>
                <td>মোবাইল</td>
                <td  colspan="2"> অভিযোগ আইডি </td>
            </tr>
            <tr>
                <td> <input type="text" name="name" id="name" value="" readonly/>  </td>
                <td> <input type="text" name="cmp_mobile" id="cmp_mobile" value="" readonly/> </td>
                <td colspan="2"><input type="text" name="cmp_user_idno" id="cmp_user_idno" value="" readonly/>  </td>
            </tr>
            {#<tr>#}
            {#<td> ঘটনাস্থল</td>#}
            {#<td colspan="3"> </td>#}
            {#</tr>#}
            <tr>
                <td> বিভাগ </td>
                <td> জেলা</td>
                <td>  উপজেলা </td>
                <td>স্থান </td>
            </tr>
            <tr>
                <td> <input type="text" name="cmp_divname" id="cmp_divname" value="" readonly/>  </td>
                <td> <input type="text" name="cmp_zillaname" id="cmp_zillaname" value="" readonly/> </td>
                <td>  <input type="text" name="cmp_upazilaname" id="cmp_upazilaname" value="" readonly/>  </td>
                <td> <input type="text" name="cmp_location" id="cmp_location" value="" readonly/>  </td>
            </tr>
        </table>
        <table class="table table-bordered table-striped" align="center">
            <thead>
            <tr>
                <th colspan="4" >অভিযোগের বিবরণ </th>
            </tr>
            </thead>
            <tr>
                <td>বিষয়</td>
                <td colspan="3"><input type="text" name="cmp_subject" id="cmp_subject" value="" readonly/> </td>
            </tr>
            <tr>
                <td>বিস্তারিত</td>
                <td colspan="3"> <input type="text" name="cmp_details" id="cmp_details" value="" readonly/> </td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Close</a>
    </div>
</div>