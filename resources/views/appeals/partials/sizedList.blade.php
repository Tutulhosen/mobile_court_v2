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
        width: 612px;
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
        margin-top: -18px;
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
        font-weight: normal !important;
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

    .list-rotated {
        text-align: justify;
        padding: 0 1em;
    }

    .list-rotated:after {
        content: "";
        width: 100%;
        display: inline-block;
    }

    .list-rotated>li {
        display: inline-block;
        width: 0;
        line-height: 0;
    }

    .list-rotated>li>* {
        display: inline-block;
        white-space: nowrap;
        transform: translate(0, 100%) rotate(-90deg);
        transform-origin: 0 0;
        vertical-align: bottom;
    }

    .list-rotated>li>*:before {
        content: "";
        float: left;
        margin-top: 100%;
    }

    td {
        font-size: 16px;
    }

    td.smallhightspace {
        line-height: 15px;
        ;
    }

    img.thumbnail {
        width: 150px;
        height: 50px;
    }
</style>

<div class="content_form_potrait">
    <h2 style="text-align: center;">জব্দতালিকা</h2>
    <h3 class="top_title_2nd">{মোবাইল কোর্ট আইন এর ১২(২), ১২(৩) এবং ফৌজাদারি কার্যবিধির ১০৩ ধারা অনুযায়ী}</h3>
    <table table border="0" style="width: 100%">
        <tr style="padding: 10px">
            <td align="center">
                বিবিধ মামলা নম্বর-<span id='caseNo'>sz_case_no</span>&nbsp;,&nbsp;তারিখ&nbsp;:&nbsp;<span
                    id='seizureDate'>sz_date</span>।
            </td>
        </tr>
    </table>


    <table width="100%" border="0">
        <tr>
            <td width="5%" align="left" valign="middle">
                ১।
            </td>
            <td width="40%" align="left" valign="middle">
                জব্দ করার তারিখ ও সময়
            </td>
            <td width="2%" align="center" valign="middle">
                :
            </td>
            <td width="53%" align="left" valign="middle">
                &nbsp;<span id="seizureDateAnother">sz_date1</span>&nbsp;
                &nbsp;ও&nbsp;<span id="seizureTime">sz_time1 </span>&nbsp;
                <span>ঘটিকায়</span>
            </td>
        </tr>
        <tr>
            <td align="left" valign="middle">
                ২।
            </td>
            <td align="left" valign="middle">
                জব্দ করার স্থান
            </td>
            <td align="center" valign="middle">
                :
            </td>
            <td align="left" valign="middle">
                &nbsp;<span id="seizurePlace">sz_place</span>&nbsp;
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">
                ৩।
            </td>
            <td align="left" valign="top">
                অপরাধ সংশ্লিষ্ট আইন ও ধারা
            </td>
            <td align="center" valign="top">
                :
            </td>
            <td align="left" valign="top">
                &nbsp;<span id="seizureLawSectionDescription">sz_c_law_section_description</span>&nbsp;
            </td>
        </tr>

        <tr>
            <td align="left" valign="middle">
                ৪।
            </td>
            <td align="left" valign="middle">
                জব্দকৃত মালামাল/ আলামতের বিস্তারিত বিবরণ
            </td>
            <td align="center" valign="left" colspan="2">

            </td>
        </tr>

        <tr>
            <td align="left" valign="middle" colspan="4">
                <table id='seizuretable' border="1" style="border-collapse:collapse;" cellpadding="2px"
                    cellspacing="2px" width="100%">
                    <th style="width: 5%">ক্রম</th>
                    <th style="width: 35%">মালামাল/আলামতের বিবরণ</th>
                    <th style="width: 15%">মালামাল/আলামতের পরিমাণ</th>
                    <th style="width: 15%">আনুমানিক মূল্য</th>
                    <th style="width: 20%">মালামালের ধরন</th>
                </table>
            </td>
        </tr>
    </table>
    <div id="criminalInfoDiv" style="display: none">
        <p>৫। অভিযুক্ত ব্যক্তির বিবরণ :</p>
        <table id='criminalInfo' border="1" style="border-collapse:collapse;" cellpadding="2px" cellspacing="2px"
            width="100%">
            <th style="width: 35px">ক্রম</th>
            <th style="width: 100px">নাম</th>
            <th style="width: 100px">পিতার নাম</th>
            <th style="width: 50px">বয়স</th>
            <th style="width: 100px">পেশা</th>
            <th style="width: 120px">ঠিকানা</th>
            <th style="width: 120px">স্বাক্ষর</th>
        </table>
    </div>


    <p>৬। উপস্থিত সাক্ষীদের স্বাক্ষর, নাম ও ঠিকানাঃ</p>

    <div style="float: left;width: 50%">
        <table width="100%" border="0">
            <tr>
                <td align="left" valign="middle">
                    সাক্ষী-১:
                </td>
            </tr>
            <tr>
                <td align="left" valign="middle">
                    <span id="witness1Fp"></span>
            </tr>
            <tr>
                <td align="left" valign="middle">
                    স্বাক্ষর..................................
                </td>

            </tr>
            <tr>
                <td width="5%" align="left" valign="middle">
                </td>
            </tr>
            <tr>
                <td width="30%" align="left" valign="middle">
                    নাম&nbsp;:&nbsp;<span id="witness1Name">sz_wt1_name</span>&nbsp;
                </td>
            </tr>
            <tr>
                <td width="35%" align="left" valign="middle">
                    পিতার নাম&nbsp;:&nbsp;<span id="witness1FatherName">sz_wt1_fname</span>&nbsp;
                </td>
            </tr>
            <tr>
                <td colspan="3" align="left" valign="middle">
                    ঠিকানা &nbsp;:&nbsp;<span id="witness1Address">sz_wt1_address</span>&nbsp;
                </td>
            </tr>
        </table>
    </div>
    <div style="float: left; width: 50%">
        <table>
            <tr>
                <td align="left" valign="middle">
                    সাক্ষী-২:
                </td>
            </tr>
            <tr>
                <td align="left" valign="middle">
                    <span id="witness2Fp"></span>
            </tr>
            <tr>
                <td align="left" valign="middle">
                    স্বাক্ষর..................................
                </td>
            </tr>
            <tr>
                <td width="30%" align="left" valign="middle">
                    নাম&nbsp;:&nbsp;<span id="witness2Name">sz_wt2_name</span>&nbsp;
                </td>
            </tr>
            <tr>
                <td width="35%" align="left" valign="middle">
                    পিতার নাম&nbsp;:&nbsp;<span id="witness2FatherName">sz_wt2_fname</span>&nbsp;
                </td>
            </tr>
            <tr>
                <td colspan="3" align="left" valign="middle">
                    ঠিকানা &nbsp;:&nbsp;<span id="witness2Address">sz_wt2_address</span>&nbsp;
                </td>
            </tr>
        </table>
    </div>
    <div style="clear: both"></div>

    <div class="smallhightspace">
    </div>


    <div id="prosecutionseizelist" style="display: none">
        <p style="text-align: right;">
            জব্দকারী কর্মকর্তার নাম, পদবী, ঠিকানা ও স্বাক্ষর
        </p>
        <table border="0" style="width: 100%">
            <tr>
                <td width="40%" align="center">&nbsp;<span id='prosecutorName'>s_name_prosecutor</span>&nbsp;
                </td>
                <td width="60%" align="left"></td>
            </tr>
            <tr>

                <td width="40%" align="center">&nbsp;<span
                        id='prosecutorDesignation'>s_prosecutor_designation_bng</span>&nbsp;
                </td>
                <td width="60%" align="left"></td>
            </tr>

            <tr>

                <td width="40%" align="center">&nbsp;<span id='prosecutorOffice'>s_office_prosecutor</span>&nbsp;
                    &nbsp; ।
                </td>
                <td width="60%" align="left"></td>
            </tr>
        </table>
    </div>




    <p class="p_indent">
        আমার নির্দেশে জব্দকৃত মালামালের তালিকা প্রস্তুত করা হয়েছে।
    </p>

    <div class="smallhightspace">
    </div>

    <table border="0" style="width: 100%">
        <tr>
            <td width="40%" align="left"></td>
            <td width="60%" align="center"><span id='prosecutionDate'>sz_date2</span>&nbsp;</td>
        </tr>
        <tr>
            <td width="40%" align="left"></td>
            <td width="60%" align="center">&nbsp;<span id='magistrateSignature'></span></br>&nbsp;</td>
        </tr>
        <tr>
            <td width="40%" align="left"></td>
            <td width="60%" align="center">&nbsp;<span id='magistrateName'>s_m_name</span>&nbsp;</td>
        </tr>
        <tr>
            <td width="40%" align="left"></td>
            <td width="60%" align="center"><span id='magistrateDesignation'>s_m_designation_bng</span>&nbsp;</td>
        </tr>
        <tr>
            <td width="40%" align="left"></td>
            <td width="60%" align="center">
                <span id='MagistrateOfficeAddress'>s_m_office1</span>
                <span id='MagistrateLocation'>s_m_upazilaname1</span></br>
            </td>
        </tr>
    </table>


</div>

<script>
    function convertEnglishDateToBangla(englishDate) {
        const banglaNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

        const englishToBanglaNumber = (number) =>
            number.toString().split('').map(digit => banglaNumbers[digit]).join('');

        const date = new Date(englishDate);

        if (isNaN(date)) {
            return 'Invalid date';
        }

        const day = englishToBanglaNumber(date.getDate());
        const month = englishToBanglaNumber(date.getMonth() + 1); 
        const year = englishToBanglaNumber(date.getFullYear());

        return `${day}-${month}-${year}`;
    }

    function convertEngTimeToBangla(englishTime) {
        const banglaNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

        const englishToBanglaNumber = (number) =>
            number.toString().split('').map(digit => banglaNumbers[digit] || digit).join('');

        try {
            const [hours, minutes, seconds] = englishTime.split(':');

            const banglaHours = englishToBanglaNumber(hours);
            const banglaMinutes = englishToBanglaNumber(minutes);
            // const banglaSeconds = seconds ? englishToBanglaNumber(seconds) : null;

            return `${banglaHours}:${banglaMinutes}`;

        } catch (error) {
            return 'Invalid time format';
        }
    }

    function convertEngNumberToBangla(number) {
        const banglaNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

        if (isNaN(number)) {
            return 'Invalid number';
        }

        return number.toString().split('').map(digit => banglaNumbers[digit] || digit).join('');
    }

    function prepareReport(caseInfo) {

        $('#caseNo').text(caseInfo.prosecution.case_no);
        $('#seizureDate').text(convertEnglishDateToBangla(caseInfo.prosecution.date));
        // $('#seizureDate').text(caseInfo.prosecution.date);
        $('#seizureDateAnother').text(convertEnglishDateToBangla(caseInfo.prosecution.date));
        $('#seizureTime').text(convertEngTimeToBangla(caseInfo.prosecution.time));
        $('#seizurePlace').text(caseInfo.prosecution.location);
        // if((caseInfo.prosecutorInfo[0].designation_bng !="") && (caseInfo.prosecutorInfo[0].designation_bng !="প্রসিকিউটর")){
        //     $('#seizureLawSectionDescription').text(lawSectionConcatenation(caseInfo.lawsBrokenList));
        // }else{
        //     $('#seizureLawSectionDescription').text(lawSectionConcatenation(caseInfo.lawsBrokenListWithProsecutor));
        // }

        if (Array.isArray(caseInfo.prosecutorInfo) && caseInfo.prosecutorInfo.length === 0) {
            $('#seizureLawSectionDescription').text(lawSectionConcatenation(caseInfo.lawsBrokenList));
        } else {
            $('#seizureLawSectionDescription').text(lawSectionConcatenation(caseInfo.lawsBrokenListWithProsecutor));
        }

        //witness data populate
        $('#witness1Name').text(caseInfo.prosecution.witness1_name);
        $('#witness1FatherName').text(caseInfo.prosecution.witness1_custodian_name);
        $('#witness1Address').text(caseInfo.prosecution.witness1_address);

        $('#witness2Name').text(caseInfo.prosecution.witness2_name);
        $('#witness2FatherName').text(caseInfo.prosecution.witness2_custodian_name);
        $('#witness2Address').text(caseInfo.prosecution.witness2_address);
        if (caseInfo.prosecutorInfo != null) {
            if (caseInfo.prosecutorInfo[0]) {
                document.getElementById('prosecutionseizelist').style.display = 'block';
                //prosecutor data population
                $('#prosecutorName').text(caseInfo.prosecutorInfo[0].name_eng);
                $('#prosecutorDesignation').text(caseInfo.prosecutorInfo[0].designation_bng);
                $('#prosecutorOffice').text(caseInfo.prosecutorInfo[0].office_address);
            }
        }


        $('#prosecutionDate').text(convertEnglishDateToBangla(caseInfo.prosecution.date));
        $('#magistrateName').text(caseInfo.magistrateInfo.name_eng);
        $('#magistrateDesignation').text(caseInfo.magistrateInfo.designation_bng);

        //set seizurelist info
        $("#seizuretable").find("tr:not(:first)").remove();
        $.each(caseInfo.seizurelist, function(i, item) {
            j = i + 1;
            $('#seizuretable').append(
                '<tr><td> ' + convertEngNumberToBangla(j) + '</td>' +
                '<td> ' + item.stuff_description + '</td>' +
                '<td> ' + item.stuff_weight + '</td>' +
                '<td> ' + item.apx_value + '</td>' +
                '<td> ' + item.seizureitem_type_group + '</td>' +
                '</tr>');
        });

        if (caseInfo.prosecution.hasCriminal == 1) {
            $("#criminalInfoDiv").css("display", "block");
            //set criminal info
            //$('#criminalInfo').empty();
            $("#criminalInfo").find("tr:not(:first)").remove();
            $.each(caseInfo.criminalDetails, function(i, criminal) {
                //criminal fingureprint/signature
                var criminalFingurePrint = "";
                var imprint1 = criminal.imprint1 ? criminal.imprint1 : "";
                if (imprint1) {
                    criminalFingurePrint += '<img id=criminalImprint1 style="width:32%;"' +
                        'src=data:image/jpeg;base64,' + imprint1 +
                        '>';
                }
                j = i + 1;
                $('#criminalInfo').append(
                    '<tr><td> ' + convertEngNumberToBangla(j)  + '</td>' +
                    '<td> ' + criminal.name_bng + '</td>' +
                    '<td> ' + criminal.custodian_name + '</td>' +
                    '<td class="centertext"> ' + convertEngNumberToBangla(criminal.age) + '</td>' +
                    '<td> ' + criminal.occupation + '</td>' +
                    '<td> ' + criminal.permanent_address + '</td>' +
                    '<td class="centertext"> ' + criminalFingurePrint + '</td>' +
                    '</tr>'
                );
            });
        }



        //Set Fingureprint/signature
        //witness fingureprint
        var witness1Fp = caseInfo.prosecution.witness1_imprint1 ? caseInfo.prosecution.witness1_imprint1 : "";
        var witness2Fp = caseInfo.prosecution.witness2_imprint1 ? caseInfo.prosecution.witness2_imprint1 : "";
        if (witness1Fp) {
            var html_witness1_imprrint1 = "";
            html_witness1_imprrint1 +=
                '<img id=sz_wt1_witness1_imprint1 width="12%" height="12%" style="padding-left: 15px"' +
                'src=data:image/jpeg;base64,' + witness1Fp +
                '>';

            $("#witness1Fp").append(html_witness1_imprrint1);
        }
        if (witness2Fp) {
            var html_witness2_imprrint1 = "";
            html_witness2_imprrint1 +=
                '<img id=sz_wt2_witness2_imprint1 width="12%" height="12%" style="padding-left: 15px"' +
                'src=data:image/jpeg;base64,' + witness2Fp +
                '>';

            $("#witness2Fp").append(html_witness2_imprrint1);
        }
        //magistrate signature
        if (caseInfo.magistrateInfo.signature) {

            var path = "";
            var magistrateSignature = "";
            if (base_path != "") {
                path = base_path + '/public/ecourt/Mobile/Signature/IMAGE/';
            } else {
                path = '/ecourt/Mobile/Signature/IMAGE/';
            }
            magistrateSignature += '<img class="thumbnail" id=s_m_imprint1 ' +
                'src=' + path + caseInfo.magistrateInfo.signature +
                '>';
        }
        $("#magistrateSignature ").append(magistrateSignature);
        $("#MagistrateOfficeAddress ").text(caseInfo.magistrateInfo.location_str);
        $("#MagistrateLocation ").text("," + caseInfo.magistrateInfo.location_details);
    }

    function convertTimeToBangla(time_in) {

        // Check correct time format and split into components
        var time = time_in.toString().match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time_in];
        if (time.length > 1) { // If time format correct
            time = time.slice(1); // Remove full string match value
            time[5] = +time[0] < 12 ? 'পূর্বাহ্ন  ' : 'মধ্যহ্ন'; // Set AM/PM
            time[0] = +time[0] % 12 || 12; // Adjust hours
        }
        return time[5] + "  " + time[0] + ":" + time[2] + " ঘটিকায়";
    }

    function lawSectionConcatenation(lawsBrokenList) {
        var seizureLawSectionDescription = "";
        var additionalText = " এবং ";
        var arrayLength = lawsBrokenList.length;

        for (var i = 0; i < arrayLength; i++) {
            if (arrayLength == (i + 1)) {
                additionalText = "";
            }
            seizureLawSectionDescription += lawsBrokenList[i].sec_description + " যা " + lawsBrokenList[i].sec_title +
                " -এর " + convertEngNumberToBangla(lawsBrokenList[i].sec_number) + " ধারার লঙ্ঘন ও " + convertEngNumberToBangla(lawsBrokenList[i].sec_number) +
                " ধারায় দণ্ডনীয় অপরাধ" + additionalText;

        }
        return seizureLawSectionDescription;
    }
</script>
