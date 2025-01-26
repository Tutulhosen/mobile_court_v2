<div class="content_form">

    <div id="newhead"></div>
    <div id="newbody"></div>
    <style>
        .content_form {
            /*min-height: 842px;*/
            /*width: 595px; A4 */
            width: 612px;
            margin-left: auto;
            margin-right: auto;
            border: 1px dotted gray;
            font-family: nikoshBan;
            text-align: justify;
            text-justify: inter-word;



        }

        .para {
            text-indent: 32px;
        }

        .underline {
            text-decoration: underline;
        }

        @media print {
            .content_form {
                border: 0px dotted;
            }
        }

        p.p_indent {
            text-indent: 30px;
        }

        p {
            text-align: justify;
            display: block;
            -webkit-margin-before: 1em;
            -webkit-margin-after: 1em;
            -webkit-margin-start: 0px;
            -webkit-margin-end: 0px;

        }

        h3 {
            text-align: center;
        }

        h3.top_title_2nd {
            margin-top: -18px;
        }

        .clear_div {
            clear: both;
            width: 100%;
            height: 20px;
        }

        br {
            line-height: 5px;
        }
    </style>
    <script>
        function convertAllNumbersToBangla(text) {
            if (text) {
                const banglaNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
                const convertNumber = (number) =>
                    number.split('').map(digit => banglaNumbers[digit] || digit).join('');
                return text.replace(/(?<!["=])\b\d+\b(?![%"])/g, (match) => convertNumber(match));
            }
            return text
        }

        function set_table(data) {

            $('#newhead').html('');
            $('#newbody').html('');
            $.each(data, function(i, item) {
                if (i == 0) {
                    $('#newhead').append(item.order_header);
                }
                $('#newbody').append(convertAllNumbersToBangla(item.order_details));

            });
        }
    </script>
</div>
