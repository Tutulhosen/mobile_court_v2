<style>

    table.fixed {
        table-layout: auto;
    }


    table.newfont td {
        font-size: 14px !important;
    }

    table.fixed td {
        overflow: hidden;
        font-size: 14px !important;
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
        width: 792px;
        margin-left: auto;
        margin-right: auto;
        border: 1px dotted gray;
        font-size: 14px !important;
        font-family: nikoshBan !important;
    }

    .content_form_height {
        /*min-height: 842px;  792px // 596 */
        min-height: 1654px;

    }

    .form_top_title {
        font-size: 18px;
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
        font-size: 18px;
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

    div.bighightspace {
        height: 40px;
    }

    h1 {
        page-break-before: always;
    }


    @media print
    {
        table {page-break-inside:auto}
        /*div   {page-break-inside:avoid; page-break-after:always}*/
        /*thead { display:table-header-group; page-break-after:always}*/
        tfoot { display:table-footer-group }
    }

</style>
<div id="printdivappealcasereportWithComment" class="content_form_potrait">
    {#{{ stylesheet_link('css/protibedon.css') }}#}
    <div class="form_top_title">
        <table style="width: 100%">
            <tr>
                <td colspan="3" class="centertext"><span id="cmt_div_appeal_allmonthreg_name">allmonthreg_name</span></td>
            </tr>
            <tr>
                <td colspan="3" class="centertext"><span id="cmt_div_appeal_month_year">month_year</span></td>
            </tr>
        </table>
    </div>


    <table id='cmt_div_appeal_monthlyregister_table' border="1" style="border-collapse:collapse;" cellpadding="2px"
           cellspacing="2px" width="100%">
        <tr>
            <td class="centertext" style="width: 10%" ROWSPAN=2>বিভাগের নাম</td>
            <td class="centertext" style="width: 36%;" colspan="3">পূর্বের মাস (<span id="cmt_div_appeal_pre_month_year2">pre_month_year</span>)
            </td>
            <td class="centertext" style="width: 36%;" colspan="3">বিবেচ্য মাস (<span id="cmt_div_appeal_month_year2">month_year</span>)
            </td>
            <td class="centertext" style="width: 6%" ROWSPAN=2>নিষ্পত্তিকৃত মামলার তুলনা</td>
            <td class="centertext" style="width: 6%" ROWSPAN=2>মন্তব্য</td>
        </tr>
        <tr>
            <td class="centertext" style="width: 9%">মোট মামলার সংখ্যা</td>
            <td class="centertext" style="width: 9%">নিষ্পত্তিকৃত মামলার সংখ্যা</td>
            <td class="centertext" style="width: 9%">অনিষ্পন্ন মামলার সংখ্যা</td>
            <td class="centertext" style="width: 9%">মোট মামলার সংখ্যা</td>
            <td class="centertext" style="width: 9%">নিষ্পত্তিকৃত মামলার সংখ্যা</td>
            <td class="centertext" style="width: 9%">অনিষ্পন্ন মামলার সংখ্যা</td>
        </tr>
    </table>
</div>


<script>
    function pdivappealc_setParamsWithComment(data) {

        document.getElementById("cmt_div_appeal_allmonthreg_name").innerHTML = data.name;
        document.getElementById("cmt_div_appeal_month_year").innerHTML = data.month_year;
        document.getElementById("cmt_div_appeal_month_year2").innerHTML = data.month_year;
        document.getElementById("cmt_div_appeal_pre_month_year2").innerHTML = data.pre_month_year;


        var myTable = document.getElementById("cmt_div_appeal_monthlyregister_table");
        var rowCount = myTable.rows.length;
        for (var x = rowCount - 1; x > 1; x--) {
            myTable.deleteRow(x);
        }
        var slno = 0;
        var rowcount = 0;
        var divname = "";
        var zillaname = "";
        var grandtotal = 0;
        var totaltext = 'মোট';
        var increasecasevalue = 0;
        var noofRow = data.result.length;


        $(data.result).each(function (index, element) {
            rowcount++;
            increasecasevalue = element['case_complete2'] - element['case_complete1'];
//            alert(increasecasevalue);
            if (increasecasevalue <= 0) {
                increasecasevalue = increasecasevalue + ' টি';
            } else {
                increasecasevalue = "+" + increasecasevalue + ' টি';
            }
            if (divname == element['divname']) {
                $('#cmt_div_appeal_monthlyregister_table').append(
                        '<tr>' +
                                '<td> ' + totaltext + '</td>' +
                                '<td class="centertext"> ' + element['case_total1'] + '</td>' +
                                '<td class="centertext"> ' + element['case_complete1'] + '</td>' +
                                '<td class="centertext"> ' + element['case_incomplete1'] + '</td>' +
                                '<td class="centertext"> ' + element['case_total2'] + '</td>' +
                                '<td class="centertext"> ' + element['case_complete2'] + '</td>' +
                                '<td class="centertext"> ' + element['case_incomplete2'] + '</td>' +
                                '<td class="centertext"> ' + increasecasevalue + '</td>' +
                                '<td class="centertext"> ' + "-"  + '</td>' +
                                '</tr>');
            } else {
                slno++;
                $('#cmt_div_appeal_monthlyregister_table').append(
                        '<tr>' +
                                '<td > ' + element['divname'] + '</td>' +
                                '<td class="centertext"> ' + element['case_total1'] + '</td>' +
                                '<td class="centertext"> ' + element['case_complete1'] + '</td>' +
                                '<td class="centertext"> ' + element['case_incomplete1'] + '</td>' +
                                '<td class="centertext"> ' + element['case_total2'] + '</td>' +
                                '<td class="centertext"> ' + element['case_complete2'] + '</td>' +
                                '<td class="centertext"> ' + element['case_incomplete2'] + '</td>' +
                                '<td class="centertext"> ' + increasecasevalue + '</td>' +
                                '<td class="centertext" > ' + element['comment2'] + '</td>' +
                                '</tr>');
                divname = element['divname'];
            }
        })
    }
</script>
