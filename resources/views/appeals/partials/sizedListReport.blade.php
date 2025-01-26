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
        font-weight: normal!important;
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

    .list-rotated > li {
        display: inline-block;
        width: 0;
        line-height: 0;
    }

    .list-rotated > li > * {
        display: inline-block;
        white-space: nowrap;
        transform: translate(0,100%) rotate(-90deg);
        transform-origin: 0 0;
        vertical-align: bottom;
    }

    .list-rotated > li > *:before {
        content: "";
        float: left;
        margin-top: 100%;
    }

    td  {
        font-size: 16px;
    }
    td.smallhightspace {
        line-height: 15px;;
    }
    img.thumbnail {
        width: 150px;
        height:50px;
    }
</style>

<div class="content_form_potrait">
    <h2 style="text-align: center;">জব্দতালিকা</h2>
    <h3 class="top_title_2nd">{মোবাইল কোর্ট আইন এর ১২(২), ১২(৩) এবং ফৌজাদারি কার্যবিধির ১০৩ ধারা অনুযায়ী}</h3>
    <table table border="0" style="width: 100%">
        <tr style="padding: 10px">
            <td align="center">
                বিবিধ মামলা নম্বর-<span id='caseNo'>sz_case_no</span>&nbsp;,&nbsp;তারিখ&nbsp;:&nbsp;<span id='seizureDate'>sz_date</span>।
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
                &nbsp;ও&nbsp;<span id="seizureTime">sz_time1</span>&nbsp;
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
                <table id='seizuretable' border="1" style="border-collapse:collapse;" cellpadding="2px" cellspacing="2px"
                       width="100%">
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
        <table id='criminalInfo' border="1" style="border-collapse:collapse;" cellpadding="2px" cellspacing="2px" width="100%">
            <th style="width: 50px">ক্রম</th>
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


    <div id="prosecutionseizelist" >
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

                <td width="40%" align="center">&nbsp;<span id='prosecutorDesignation'>s_prosecutor_designation_bng</span>&nbsp;
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