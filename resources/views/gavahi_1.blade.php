<html>
<head>
    <style>
        body {
            direction: rtl;
            font-family: mitra
        }

        .border {
            border: 1px solid black
        }

        .table td {
            text-align: center
        }

        .table2 td {
            text-align: center;
            width: 30px
        }</style>
</head>
<body>
<table>
    <tr>
        <td><img src="var:logo" width="150px" height="70px"></td>
        <td style="width:180px"></td>
        <td style="font-weight: bold;text-align: center" colspan="3">گواهی کد پستی ده رقمی</td>
        <td style="width:100px"></td>
        <td><img src="var:barcode" width="120px" height="45px"><br>(اصالت گواهی با این بارکد تعیین می شود)</td>
    </tr>
</table> {{--{{ var_dump($data) }}--}} @foreach($data as $key=>$item)
    <table class="table2" style="border-collapse: collapse">
        <tr>
            <td><br></td>
        </tr>
        <tr>
            <td><br></td>
        </tr>
        <tr>
            <td style="width:130px;">کد پستی ده رقمی:</td>
            @for($i=strlen($item['postalcode']) - 1;$i>=0;$i--)
                <td class="border">{{$item["postalcode"][$i]}}</td>
            @endfor
            <td></td>
            <td style="width:100px;">تاریخ صدور :</td>
            <td style="width:100px;">{{$date}}</td>
        </tr>
    </table>
    <table>
        <tr>
            <td><br></td>
        </tr>
        <tr>
            <td>استان:</td>
            <td style="width:120px">{{$item["statename"]}}</td>
            <td>شهرستان:</td>
            <td style="width:120px">{{$item["townname"]}}</td>
            <td>بخش:</td>
            <td style="width:160px">{{$item["zonename"]}}</td>
            <td>دهستان:</td>
            <td style="width:160px">{{$item["villagename"]}}</td>
        </tr>
        <tr>
            <td style="width:120px">شهر/روستا/آبادی :</td>
            <td style="width:120px">{{$item["locationname"]}}</td>
        </tr>
    </table>
    <table>
        <tr>
            <td><br></td>
        </tr>
        <tr>
            <td><br></td>
        </tr>
        <tr>
            <td style="width:120px">نشانی پستی :</td>
        </tr>
        <tr>
            <td>محله:</td>
            <td style="width:230px">{{$item["parish"]}}</td>
            <td style="width:180px">معبر ماقبل آخر:</td>
            <td style="width:120px">{{$item["preaven"]}}</td>
            <td>معبرآخر:</td>
            <td style="width:230px">{{$item["avenue"]}}</td>
        </tr>
        <tr>
            <td>پلاک:</td>
            <td style="width:230px">{{$item["pelak"]}}</td>
            <td style="width:180px">ورودی/ بلوک:</td>
            <td style="width:120px">{{$item["blockno"]}}</td>
            <td>طبقه:</td>
            <td style="width:230px">{{$item["floorno"]}}</td>
            <td>واحد:</td>
            <td style="width:120px">{{$item["unit"]}}</td>
        </tr>
        <tr>
            <td>نوع کاربری :</td>
            <td style="width:120px">{{$item["building_type"]}}</td>
        </tr>
        <tr>
            <td><br></td>
        </tr>
        <tr>
            <td><br></td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="width:100px">نشانی استاندارد ملی:</td>
        </tr>
        <tr>
            <td style="width:100%">{{$item["address"]}}</td>
        </tr>
        <tr>
            <td></td>
        </tr>
    </table> @endforeach
<div style="position:absolute;bottom:0;left:0;right:0">
    <table>
        <tr>
            <td style="width:70%;border-top:4px solid black"> قابل توجه درخواست کننده گرامی:<br>۱.کدپستی مندرج در گواهی
                منحصرا متعلق به نشانی ذکر شده بوده و استفاده از این کد پستی برای سایر مکانها موجب اشتباه در پردازش
                اطلاعات و عدم خدمت رسانی صحیح به شما خواهد بود<br>۲.مدت اتبار این گواهی از تاریخ صدور 000 روز می
                باشد.<br>۳.با مراجعه به سامانه GNAF.POST.IR می توانید کد پستی خود را دریافت کنید.<br>۴.هزینه صدور گواهی
                فوق 000000 ریال می باشد.<br>۵.اصالت این گواهی از طریق استعلام بر خط بارکد تعیین می گردد.<br>۶.در صورت
                مشاهده هر گونه مغایرت در اطلاعات این گواهی با نشانی خود, به نزدیکترین دفتر پستی مراجعه نمایید.
            </td>
            <td style="width:30%;border-top:4px solid black;border-right:4px solid black"></td>
        </tr>
    </table>
</div>
</body>
</html>