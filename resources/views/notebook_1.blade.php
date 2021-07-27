<html>
<head>
    <style>body {
            direction: rtl;
            font-family: mitra
        }

        .header {
            text-align: center
        }

        .table1 {
            border-collapse: collapse;
            display: inline-block
        }

        .tabled1 {
            border: 1px solid black;
            padding-left: 10px;
            padding-right: 10px;
            text-align: center;
            width: 10%
        }

        .tabled2 {
            border: 1px solid black;
            padding-left: 10px;
            padding-right: 10px;
            text-align: center;
            width: 140px
        }

        .info td {
            width: 150px
        }</style>
</head>
<body>
<div><h3 class="header"><b>شرکت ملی پست جمهوری اسلامی ایران</b></h3><h4 class="header"><b>اداره کل جغرافیایی و اطلاعات
            مکانی کشور</b></h4>
    <p class="header">فهرست مکان های مسکونی و کسب</P></div>
<center style="margin:0 auto;display: block;width: 50%;">
    <table class="table1">
        <tr>
            <td class="tabled1">شماره گشت نامه رسانی</td>
            <td class="tabled1">{{ $tour_no }}</td>
            <td class="tabled1" style="border:none"></td>
            <td class="tabled1">کد جزء</td>
            <td class="tabled1">{{ $code_joze }}</td>
        </tr>
    </table>
</center>
<table class="info">
    <tr>
        <td>استان:</td>
        <td colspan="2" style="text-align: right;">{{ $province }}-{{ $region }}</td>
        <td>شهرستان:</td>
        <td>{{ $county }}</td>
        <td> بخش:</td>
        <td> {{ $district }}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>محدوه پستی:</td>
        <td>{{ $postal_region }}</td>
    </tr>
</table>
<table style="float:right">
    <tr>
        <td>تعداد بلوک</td>
        <td class="tabled2">{{ $blocks_count }}</td>
    </tr>
    <tr>
        <td>تعداد ساختمان</td>
        <td class="tabled2">{{ $buildings }}</td>
    </tr>
    <tr>
        <td>تعداد کد شناسایی</td>
        <td class="tabled2">{{ $recog_count }}</td>
    </tr>
    <tr>
        <td>تعداد رکورد</td>
        <td class="tabled2">{{ $records_counts }}</td>
    </tr>
</table>
<table style="margin-right: 60%">
    <tr>
        <td>تاریخ چاپ گزارش:</td>
        <td>{{ $date }}</td>
    </tr>
    <tr></tr>
    <tr>
        <td>تعداد صفحات گزارش:</td>
        <td> {nb}  </td>
    </tr>
</table>
</body>
</html>
