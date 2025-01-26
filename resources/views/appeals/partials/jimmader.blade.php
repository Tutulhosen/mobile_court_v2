
<style>

    table.fixed {
        table-layout: fixed;
    }

    table.fixed td {
        overflow: hidden;
        font-size: 18px !important;
    }

    div.smallhightspace {
        height: 15px;
    }

    .underline {
        text-decoration: underline;
    }

    div.bighightspace {
        height: 40px;
    }

    .content_form {
        /*min-height: 842px;  792px // 596 */
        width: 1200px;
        margin-left: auto;
        margin-right: auto;
        border: 1px dotted gray;
        font-family: nikoshBan;
    }

    .content_form_potrait {
        /*min-height: 842px;  792px // 596 */
        /*width: 792px;*/
        /*margin-left: auto;*/
        /*margin-right: auto;*/
        /*border: 1px dotted gray;*/
        /*font-size: 18px!important;*/
        /*font-family: nikoshBan!important;*/
        width: 595px;
        margin-left: auto;
        margin-right: auto;
        border: 1px dotted gray;
        font-family: nikoshBan;
        text-align: justify;
        text-justify: inter-word;
    }

    .content_form_height {
        /*min-height: 842px;  792px // 596 */
        min-height: 1654px;

    }

    .form_top_title {
        font-size: 24px;
    }

    {
        margin-top: -18px
    ;
    }

    @media print {
        .content_form {
            border: 0px dotted;
        }

        .content_form_potrait {
            border: 0px dotted;
        }
    }

    p {
        text-align: justify !important;
    }

    p.p_indent {
        text-indent: 10px;
    }

    h3 {
        text-align: center;
    }

    h4 {
        text-align: center;
    }

    h5 {
        text-align: center;
    }

    h6 {
        text-align: center;
    }

    h3.top_title_2nd {
        margin-top: -18px;
    }

    h4.bottom_margin {
        margin-bottom: -18px;
    }

    .clear_div {
        clear: both;
        width: 100%;
        height: 20px;
    }

    br {
        line-height: 5px;
    }

    td.centertext {
        text-align: center;
    }
</style>


<div class="content_form_potrait">
    <div class="form_top_title">
        <h3>জিম্মানামা</h3>
        <h3 class="top_title_2nd">জব্দকৃত মালামাল/ আলামত জিম্মায় প্রদান</h3>
    </div>
    <p>
        আমি <span id="osjimmader_jimmader_name"> osjimmader_jimmader_name </span> এই মর্মে অঙ্গীকার করছি যে, নিম্নে বর্ণিত
        মালামাল/ আলামত বিজ্ঞ এক্সিকিউটিভ ম্যাজিস্ট্রেটের আদেশ মোতাবেক নিজ হেফাজতে আইনানুগভাবে সংরক্ষণ করব। অন্যথায় আইন
        অনুযায়ী দায়বদ্ধ থাকব।
    </p>
    <div class="midhightspace"></div>

    <table width="100%" border="0">
        <tr>
            <td align="left" valign="middle">

            </td>
            <td align="left" valign="middle">
                জব্দকৃত মালামাল/ আলামতের বিস্তারিত বিবরণ
            </td>
            <td align="center" valign="left" colspan="2">

            </td>
        </tr>

        <tr>
            <td align="left" valign="middle" colspan="4">
                <table id='osjimmader_seizuretable' border="1" style="border-collapse:collapse;" cellpadding="2px"
                       cellspacing="2px" width="100%">
                    <th style="width: 5%">ক্রম</th>
                    <th style="width: 40%">মালামাল/আলামতের বিবরণ</th>
                    <th style="width: 15%">মালামাল/আলামতের পরিমাণ</th>
                    <th style="width: 15%">আনুমানিক মূল্য</th>
                    <th style="width: 15%">মালামালের ধরন</th>
                </table>
            </td>
        </tr>
    </table>
    <div class="bighightspace"></div>
    <table border="0" style="width: 100%">
        <tr>
            <td width="40%" align="left"> জিম্মাদারের স্বাক্ষর</td>
            <td width="40%" align="center"><span id='osjimmader_date'>osjimmader_date</span>&nbsp;</td>
        </tr>

        <tr>
            <td width="40%" align="left">নামঃ <span
                        id="osjimmader_jimmader_name_sec">osjimmader_jimmader_name_sec</span></td>
            <td width="40%" align="center">এক্সিকিউটিভ ম্যাজিস্ট্রেটের স্বাক্ষর ও তারিখ</td>
        </tr>
        <tr>
            <td width="40%" align="left"> পদবীঃ  <span id="osjimmader_jimmader_custodian_name">osjimmader_jimmader_custodian_name</span>
            </td>
            <td width="60%" align="center"><span id="osjimmader_m_name">osjimmader_m_name</span></td>
        </tr>
        <tr>
            <td width="40%" align="left">ঠিকানাঃ <span
                        id="osjimmader_jimmader_details">osjimmader_jimmader_details</span></td>
            <td width="60%" align="center"><span id='osjimmader_m_office1'>osjimmader_m_office1</span>, <span id='osjimmader_m_zilla'>osjimmader_m_zilla</span>।
            </td>
        </tr>

    </table>
    <div class="bighightspace"></div>
</div>

<script>

    function osjimmader_setParamsForPunishment(data) {
//        alert(JSON.stringify(data));
        document.getElementById("osjimmader_jimmader_details").innerHTML = data.jimmader_address;
        document.getElementById("osjimmader_jimmader_name").innerHTML = data.jimmader_name;
        document.getElementById("osjimmader_jimmader_name_sec").innerHTML = data.jimmader_name;
        document.getElementById("osjimmader_jimmader_custodian_name").innerHTML = data.jimmader_designation;
//        $.each(data, function (i, item) {
//
//            document.getElementById("osjimmader_jimmader_details").innerHTML = item.jimmader_address;
//            document.getElementById("osjimmader_jimmader_name").innerHTML = item.jimmader_name;
//            document.getElementById("osjimmader_jimmader_name_sec").innerHTML = item.jimmader_name;
//            document.getElementById("osjimmader_jimmader_custodian_name").innerHTML = item.jimmader_designation;
//        });
    }


    function osjimmader_setParamsFormagistraten(data) {
        document.getElementById("osjimmader_m_name").innerHTML = data.name_eng;
//        $.each(data, function (i, item) {
//
////            document.getElementById("osjimmader_m_designation").innerHTML= item.designation_bng;
//
//        });
    }
    function osjimmader_setParamsForProsecution(data) {
        var dateq = data.date;
        var date_arr = dateq.split("-");
        document.getElementById("osjimmader_date").innerHTML = convertEngNumberToBangla(date_arr[2]) + "-" + convertEngNumberToBangla(date_arr[1]) + "-" + convertEngNumberToBangla(date_arr[0]);
//        $.each(data, function (i, item) {
//            var dateq = item.date;
//            var date_arr = dateq.split("-");
//            document.getElementById("osjimmader_date").innerHTML = date_arr[2] + "-" + date_arr[1] + "-" + date_arr[0];
//        });
    }

    function osjimmader_setParamsForjobdescription(data) {
        document.getElementById("osjimmader_m_office1").innerHTML = data.location_str;
        document.getElementById("osjimmader_m_zilla").innerHTML = data.location_details;
//        $.each(data, function (i, item) {
//            document.getElementById("osjimmader_m_office1").innerHTML = item.location_str;
//            document.getElementById("osjimmader_m_zilla").innerHTML = item.location_details;
//        });
    }

    function osjimmader_setParamsForSizedList(data) {
        var j = 0;
        $("#osjimmader_seizuretable").find("tr:not(:first)").remove();
        $.each(data, function (i, item) {

            j = i + 1;
            $('#osjimmader_seizuretable').append(
                '<tr><td> ' + convertEngNumberToBangla(j) + '</td>' +
                '<td> ' + item['stuff_description'] + '</td>' +
                '<td> ' + item['stuff_weight'] + '</td>' +
                '<td> ' + item['apx_value'] + '</td>' +
                '<td> ' + item['seizureitem_type_group'] + '</td>' +
                '</tr>');
        });
    }
</script>


