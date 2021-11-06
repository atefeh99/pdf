<html>
<head>
    <style>body {
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
        }

        .barcode {
            padding: 1.5mm;
            margin: 5px;
            margin-bottom: 4px;
            vertical-align: top;
            color: black
        }

        .barcodecell {
            vertical-align: middle;
        }</style>
</head> {{--@php--}} {{--import --}} {{--@endphp--}}
<body> {{--{{ var_dump($data) }}--}}
<div style="border:1px solid black ">
    @foreach($data as $key=>$item)
        <table>
            <tr>
                <td style="margin-left:90px;width:200px; height:100px"><img src="var:logo" width="150px" height="100px">
                </td>
                <td style="width:350px;font-weight: bold;text-align:center;vertical-align: top;font-size:19px;"><span
                        style="margin-right:40px">گواهی کد پستی ده رقمی</span></td>
                </td>
                <td style="width:250px;text-align: center">
                    <div class="barcodecell">
                        <barcode code={{$item["barcode"]}} type="C128C" class="barcode" size="0.6" height="1"/>
                    </div>
                    <div style="width:200px">{{$item["barcode"]}}</div>
                    <div><span
                            style="margin-right:10em;"> (اصالت گواهی با استعلام این بارکد تعیین می شود) </span>
                    </div>
                </td>
            </tr>
        </table>
        <table class="table2" style="border-collapse: collapse">
            <tr>
                <td><br></td>
            </tr>
            <tr>
                <td><br></td>
            </tr>
            <tr>
                <td style="width:100px;text-align: right;margin-left:0"><span
                        style="width:100px;text-align: right;margin-left:0"><b>کد پستی ده رقمی:</b></span>
                </td>
                @for($i=count($item["postalcode"]) - 1;$i>=0;$i--)
                    <td class="border">
                        <b>
                            {{$item["postalcode"][$i]}}
                        </b>
                    </td> @endfor
                <td></td>
                <td style="width:100px;">
                    <b>
                        تاریخ صدور :
                    </b>
                </td>
                <td style="width:100px;">
                    <b>
                        {{$date}}
                    </b>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td><br></td>
            </tr>
            <tr>

                <td>
                    <b>
                        تقسیمات کشوری:
                        @if($item["statename"])
                            استان
                            {{$item["statename"]}}
                        @endif
                        @if($item["townname"])
                            ،
                            شهرستان
                            {{$item["townname"]}}
                        @endif
                        @if($item["zonename"])
                            ،
                            بخش
                            {{$item["zonename"]}}
                        @endif
                        @if($item["villagename"])
                            ،
                            دهستان
                            {{$item["villagename"]}}
                        @endif
                        @if($item["locationtype"] && $item["locationname"])
                            ،
                            {{$item["locationtype"]}}
                            {{$item["locationname"]}}
                        @endif
                    </b>
                </td>
            </tr>
        </table>
        <table>
            {{--            <tr>--}}
            {{--                <td><br></td>--}}
            {{--            </tr>--}}
            {{--            <tr>--}}
            {{--                <td><br></td>--}}
            {{--            </tr>--}}
            <tr>
                <td>
                    <b>
                        نشانی پستی :
                        @if($item["parish"])
                            محله:
                            {{$item["parish"]}}
                        @endif
                        @if($item["preaven"])
                            ،
                            معبر ماقبل آخر:
                            {{$item["preaven"]}}
                        @endif
                        @if($item["avenue"])
                            ،
                            معبرآخر:
                            {{$item["avenue"]}}
                        @endif
                        @if($item["plate_no"])
                            ،
                            پلاک
                            {{$item["plate_no"]}}
                        @endif
                        @if($item["blockno"])
                            ،
                            ورودی/ بلوک:
                            {{$item["blockno"]}}
                        @endif
                        @if($item["building"])
                            ،

                            {{$item["building"]}}
                        @endif
                        @if($item["floorno"])
                            ،
                            طبقه
                            {{$item["floorno"]}}
                        @endif
                        @if($item["unit"])
                            ،
                            واحد
                            {{$item["unit"]}}
                        @endif
                    </b>
                </td>
            </tr>
            <tr>
                <td>
                    <b>
                        نوع کاربری :
                    </b>
                </td>
                <td style="width:120px">
                    <b>
                        {{$item["building_type"]}}
                    </b>
                </td>
            </tr>
        </table>
</div>
<div style="border:1px solid black;border-top:none ">

    <table>
        <tr>
            <td>
                <b>
                    نشانی استاندارد ملی:
                    {{$item["address"]}}
                </b>
            </td>
        </tr>
    </table>
</div>
@if($item["image_exists"])
    <table>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td height="50px"></td>
        </tr>
        <tr>
            <td style="width:100px">تصویر کروکی ملک:</td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td><img src="var:{{$item["postalcode"]}}" width="700px" height="400px"></td>
        </tr>
    </table> @endif
<div style="border:1px solid black;border-top: none;">
    <table>
        <tr>
            <td style="width:76%">
                <b>قابل توجه درخواست کننده گرامی:</b>
                <br>
                ۱.کدپستی مندرج در گواهی منحصرا متعلق به نشانی ذکر شده بوده و استفاده از این کد پستی برای سایر مکانها موجب اشتباه در پردازش اطلاعات و عدم خدمت رسانی صحیح به شما خواهد بود.
                <br>
                ۲.مدت اعتبار این گواهی از تاریخ صدور {{$ttl}} روز می باشد.
                <br>
                ۳.با مراجعه به سامانه GNAF.POST.IR می توانید کد پستی خود را دریافت کنید.
                <br>
                ۴.هزینه صدور گواهی فوق {{$price}} ریال می باشد.
                <br>
                ۵.اصالت این گواهی از طریق استعلام بر خط بارکد تعیین می گردد.

                ۶.در صورت مشاهده هر گونه مغایرت در اطلاعات این گواهی با نشانی خود، به نزدیکترین دفتر پستی مراجعه نمایید.            </td>
            <td style="width:24%;border-right:1px solid black;text-align: center">
                <barcode code={{$QRCode}} type="QR" class="barcode" size="1.2" error="M" height="2"
                         disableborder="1"/>
            </td>
        </tr>
    </table>
</div>
</div>@if($x !== $length)
    <pagebreak/> @endif @php $x++; @endphp@endforeach</body>
</html>
