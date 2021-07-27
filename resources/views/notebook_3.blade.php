<html>
<head>
    <style>body {
            direction: rtl;
            font-family: mitra
        }

        .div2 {
            padding: 6px 6px 15px 6px;
            width: 100%
        }

        .table7 td {
            border: 1px solid black;
            padding-left: 10px;
            padding-right: 10px;
            text-align: right;
            width: 80px;
            line-height: 15px
        }

        .table8 td {
            border: 1px solid black;
            padding-left: 10px;
            padding-right: 10px;
            text-align: center;
            width: 80px;
            line-height: 15px
        }

        .ta {
            border-collapse: collapse;
            width: 100%
        }

        .table9 td {
            border: 1px solid black;
            padding-left: 10px;
            padding-right: 10px;
            text-align: center;
            width: 80px;
            line-height: 15px
        }</style>
</head>
<body>
<div class="div2">
    <table class="table7 ta">
        <tr>
            <td style="width: 110px;"> کد گشت نامه رسانی:</td>
            <td>{{ $tour_no }}</td>
            <td colspan="3" rowspan="2" style="border:none;font-size: 20px;font-weight: bold;text-align: center;">شرکت
                ملی پست جمهوری اسلامی ایران
            </td>
            <td>تاریخ:</td>
            <td>{{ $date }}</td>
        </tr>
        <tr>
            <td>کد جزء:</td>
            <td>{{ $code_joze }}</td>
            <td>صفحه:</td>
            <td>{PAGENO}</td>
        </tr>
    </table>
</div>
<table class="table8 ta">
    <tr>
        <td colspan="2">معبر</td>
    </tr>
    <tr>
        <td>نوع معبر</td>
        <td>نام معبر</td>
    </tr>
    <tr>
        <td>نوع معبر</td>
        <td>نام معبر</td>
    </tr>
</table>
<table class="table9 ta">
    <tr>
        <td colspan="2">محله</td>
    </tr>
    <tr>
        <td colspan="2">نام محله</td>
    </tr>
    <tr>
        <td colspan="2">نام محله</td>
    </tr>
</table>
</body>
</html>
