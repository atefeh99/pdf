<html>
<head>
    <style>body {
            direction: rtl;
            font-family: mitra
        }

        .div1 {
            border: 1px solid black;
            padding: 6px 6px 15px 6px;
            width: 100%
        }

        .tab {
            border-collapse: collapse
        }

        .table3 td {
            border: 1px solid black;
            padding-left: 10px;
            padding-right: 10px;
            text-align: center;
            width: 80px;
            line-height: 30px
        }

        .table4 td {
            border-bottom: 1px solid black;
            border-left: 1px solid black;
            padding-left: 10px;
            padding-right: 10px;
            text-align: center;
            width: 72px;
            line-height: 20px
        }

        .table5 td {
            border-bottom: 1px solid black;
            border-right: 1px solid black;
            border-top: 1px solid black;
            padding-left: 10px;
            padding-right: 10px;
            text-align: right;
            width: 10px;
            line-height: 15px
        }

        .table6 td {
            padding-left: 10px;
            padding-right: 10px;
            text-align: center;
            width: 72px;
            line-height: 20px
        }</style>
</head>
<body>
<div class="div1">
    <table class="table3 tab">
        <tr>
            <td style="width:120px"> کد گشت نامه رسانی:</td>
            <td>{{ $tour_no }}</td>
            <td>کد جزء:</td>
            <td>{{ $code_joze }}</td>
            <td style="border: none;width:10px"></td>
            <td style="width:370px;font-size:15px">شرکت ملی پست جمهوری اسلامی ایران-اداره کل جغرافیایی و اطلاعات مکانی
                کشور
            </td>
            <td style="border: none;width:10px"></td>
            <td>تاریخ:</td>
            <td style="width:100px">{{ $date }}</td>
            <td>صفحه:</td>
            <td>{PAGENO}</td>
        </tr>
    </table>
</div>
<table class="table4 tab">
    <tr>
        <td style="border-right: 1px solid black">ردیف در رکورد</td>
        <td>نوع پلاک</td>
        <td>تکمیلی</td>
        <td>پلاک</td>
        <td>طبقه</td>
        <td>سمت</td>
        <td style="border:none;border-bottom:1px solid black;width: 150px">نام خانوادگی/نوع فعالیت</td>
        <td style="border:none;border-bottom:1px solid black"></td>
        <td style="width: 150px">نام/نام کارگاه</td>
        <td>کدفعالیت</td>
        <td>نوع مکان</td>
        <td>کد شناسایی</td>
    </tr>
</table> @for($i = 0; $i <count ($data["parts"]); $i++) @for($j = 0; $j < count($data["parts"][$i]["blocks"]); $j++) @for($k = 0; $k < count($data["parts"][$i]["blocks"][$j]["buildings"]); $k++) @for($l = 0; $l < count($data["parts"][$i]["blocks"][$j]["buildings"][$k]["addresses"]); $l++)
    <table class="table5 tab" style="width:100%">
        <tr>
            <td style="border-right: 1px solid black">بلوک</td>
            <td style="border-right:none">{{ $data["parts"][$i]["blocks"][$j]["id"]}}</td>
            <td>ساختمان</td>
            <td style="border-right:none">{{ $data["parts"][$i]["blocks"][$j]["buildings"][$k]["building_no"] }}</td>
            <td>{{ $data["parts"][$i]["blocks"][$j]["buildings"][$k]["flour_count"] }}</td>
            <td style="border-right:none">طبقه</td>
            <td>آدرس</td>
            <td>محله</td>
            <td style="width: 150px;border-right:none">{{ $data["parts"][$i]["blocks"][$j]["buildings"][$k]["neighbourhood"] }}</td>
            <td style="width: 150px;border-right:none">{{ $data["parts"][$i]["blocks"][$j]["buildings"][$k]["addresses"][$l]["s_way_name"] }}</td>
            <td style="width: 150px;border-right:none">{{ $data["parts"][$i]["blocks"][$j]["buildings"][$k]["addresses"][$l]["way_name"] }}</td>
            <td style="width: 40px"></td>
            <td style="border-left: 1px solid black;width: 40px"></td>
        </tr>
    </table> @for($m = 0; $m < count($data["parts"][$i]["blocks"][$j]["buildings"][$k]["addresses"][$l]["entrances"]); $m++) @for($n = 0; $n < count($data["parts"][$i]["blocks"][$j]["buildings"][$k]["addresses"][$l]["entrances"][$m]["units"]); $n++)
        <table class="table6 tab">
            <tr>
                <td>{{ $data["parts"][$i]["blocks"][$j]["buildings"][$k]["addresses"][$l]["entrances"][$m]["units"][$n]["row_no"] }}</td>
                <td>نوع پلاک</td>
                <td>تکمیلی</td>
                <td>{{ $data["parts"][$i]["blocks"][$j]["buildings"][$k]["addresses"][$l]["entrances"][$m]["plate_no"]}}</td>
                <td>{{ $data["parts"][$i]["blocks"][$j]["buildings"][$k]["addresses"][$l]["entrances"][$m]["units"][$n]["floor_no"] }}</td>
                <td style="width: 100px">{{ $data["parts"][$i]["blocks"][$j]["buildings"][$k]["addresses"][$l]["entrances"][$m]["units"][$n]["unit_no"] }}</td>
                <td style="width: 150px"></td>
                <td style="width: 150px"> {{$data["parts"][$i]["blocks"][$j]["buildings"][$k]["addresses"][$l]["entrances"][$m]["units"][$n]["name"] }}</td>
                <td>{{ $data["parts"][$i]["blocks"][$j]["buildings"][$k]["addresses"][$l]["entrances"][$m]["units"][$n]["isic_id"] }}</td>
                <td>{{ $data["parts"][$i]["blocks"][$j]["buildings"][$k]["addresses"][$l]["entrances"][$m]["units"][$n]["location_type_id"] }}</td>
                <td>{{ $data["parts"][$i]["blocks"][$j]["buildings"][$k]["addresses"][$l]["entrances"][$m]["units"][$n]["recog_code"] }}</td>
            </tr>
        </table> @endfor @endfor @endfor @endfor @endfor @endfor</body>
</html>
