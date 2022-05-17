
<html>
<head>
    <style>



        body {
            direction: rtl;
            font-family: mitra
        }

        .postcode {
            border: 1px solid black;

        }

        .table td {
            text-align: center
        }

        .table2 td {
            text-align: center;
            width: 38px
        }

        .barcode {
            padding: 1.5mm;
            margin: 5px;
            margin-bottom: 4px;
            vertical-align: top;
            color: black;
        }

        .barcodecell {
            vertical-align: middle;
        }</style>
</head> {{--@php--}} {{--import --}} {{--@endphp--}}
<body> {{--{{ var_dump($data) }}--}}
{{--<div style="border:1px solid black ">--}}
@foreach($data as $key=>$item)
    <div style="border:1px solid black;width:667px;">
        <table>
            <tr>
                <td style="margin-left:90px;width:200px; height:100px"><img src="var:logo" width="150px" height="100px">
                </td>
                <td style="width:350px;font-weight: bold;text-align:center;vertical-align: top;font-size:20px;"><span
                        style="margin-right:40px">گواهی کد پستی ده رقمی</span>
                    <div style="font-weight: normal">GNAF.POST.IR</div>
                    <div style="font-size:17px;">اداره کل پست استان {{$item["statename"]}}</div>

                </td>
                <td style="width:250px;text-align: center;padding-right:56px">
                    <div class="barcodecell">
                        <barcode style="margin-bottom:0px;padding-bottom: 0" code={{$item["barcode"]}} type="C128C"
                                 class="barcode" size="0.8" height="1"/>
                        <span style="padding-top: 0;font-size: 20;">{{$item["barcode"]}}</span>
                    </div>
                    {{--                    <div style="width:200px;margin-top: -100px">{{$item["barcode"]}}</div>--}}
                    <div style="margin-right:15px;"><span
                            style="margin-right:10em;"> (اصالت گواهی با استعلام این بارکد تعیین می شود) </span>
                    </div>
                </td>
            </tr>
        </table>
        <table class="table2" style=";margin-bottom:30px;border-collapse: collapse;width:78%">
            {{--            <tr>--}}
            {{--                <td><br></td>--}}
            {{--            </tr>--}}
            <tr>
                <td><br></td>
            </tr>

            <tr>
                <td style="width:125px;text-align: right;height:25px;"><span
                        style="width:85px;text-align: right;margin-left: 30px;font-size: 18;"><b>&#160;&#160;کد پستی ده رقمی:</b></span>
                </td>
                @for($i=count($item["postalcode"]) - 1;$i>=0;$i--)
                    <td class="postcode">
                        <b>
                            {{$item["postalcode"][$i]}}
                        </b>

                    </td> @endfor
                {{--                <td></td>--}}
                <td style="width:160px;height:4px;text-align: center;font-size: 15;">
                    <b>
                        تاریخ صدور :&#160;{{$date}}

                    </b>
                </td>
            </tr>

        </table>
        <table>
            <tr>
                <td><br></td>

            </tr>
            <tr>

                <td style="font-size: 12;">
                    <b style="font-size: 14;">
                        تقسیمات کشوری:
                        &#160;
                        {{$item["country_division"]}}
                    </b>
                </td>
            </tr>
        </table>
        <table style="width:100%">
            <tr>
                <td><br></td>
            </tr>
            {{--            <tr>--}}
            {{--                <td><br></td>--}}
            {{--            </tr>--}}
            <tr>
                <td style="width:13%;vertical-align: text-top;">
                    <b style="font-size: 15">
                        نشانی پستی :
                    </b>
                </td>
                <td style="width:79%;height:30px;min-height:30px;vertical-align: text-top;font-size: 13">
                    <b>
                        <span>
                        {{$item["post_address"]}}
                        </span>
                    </b>
                </td>
                <td style="width:8%"></td>
            </tr>
            <tr>
                <td>
                    <b style="font-size: 15">
                        نوع کاربری :
                    </b>
                </td>
                <td style="width:120px;font-size: 15">
                    <b>
                        {{$item["poi_type_name"]}}
                    </b>
                </td>
            </tr>
        </table>
    </div>

    {{--        <div style="border:1px solid black;border-top:none ">--}}

    @if($item["image_exists"])
        <div style="height:53px;border:1px solid black;border-top:none;width:667px">
            <table style="width:100%;text-align: right;padding-right:10px;font-size: 16;padding-top:10px">
                <tr style="min-height: 34px">
                    <td>
                        <b>
                            نشانی استاندارد ملی:
                        </b>
                        &#171;
                        <b>
                            {{$item["address"]}}
                        </b>

                        &#187;
                    </td>
                </tr>
            </table>

        </div>
    @else
        <div style="height:38px;border:1px solid black;border-top:none;width:667px">
            <table style="width:100%;text-align: right;padding-right:10px;font-size: 16;padding-top:5px">
                <tr style="min-height: 34px">
                    <td>
                        <b>
                            نشانی استاندارد ملی:
                        </b>
                        &#171;
                        <b>
                            {{$item["address"]}}
                        </b>

                        &#187;
                    </td>
                </tr>
            </table>

        </div>
    @endif
    @if($item["image_exists"])
        <div style="border:1px solid black;border-top:none;width:667px;height:345px;max-height:345px">
            <table style="width:98%;height:98%">
                <tr>
                    <td><img  style="height:341px;width:660px" src="var:{{$key}}"></td>
                </tr>
            </table>
        </div>
    @endif
    <div style="border:1px solid black;border-top: none;min-height:10px;width:667px">
        <table style="min-height:10px;">
            <tr>
                <td style="width:76%;font-size: 13;vertical-align: text-top">
                    <b>قابل توجه درخواست کننده گرامی:</b>
                    <br>
                    ۱.کدپستی مندرج بر روی گواهی منحصرا متعلق به نشانی ذکر شده بوده و استفاده از این کد پستی برای سایر
                    مکانها
                    موجب اشتباه در پردازش اطلاعات و عدم خدمت رسانی صحیح به شما خواهد بود.
                    <br>
                    ۲.مدت اعتبار این گواهی از تاریخ صدور {{$ttl}} روز می باشد.
                    <br>
                    ۳.با مراجعه به سامانه GNAF.POST.IR می توانید به صورت آنلاین گواهی کد پستی خود را دریافت کنید.
                    <br>
                                                            ۴.هزینه صدور گواهی فوق {{$price}} ریال و با احتساب ارزش افزوده {{$tax}} ریال می باشد.
                    <br>
                    ۵.اصالت این گواهی از طریق استعلام بر خط بارکد تعیین می گردد.
                    <br>
                    ۶.در صورت مشاهده هر گونه مغایرت در اطلاعات این گواهی با نشانی خود، به نزدیکترین دفتر پستی مراجعه
                    نمایید.
                </td>
                <td style="width:24%;border-right:1px solid black;text-align: center">
                    <barcode code={{$QRCode}} type="QR" class="barcode" size="1.2" error="M" height="2"
                             disableborder="1"/>
                </td>
            </tr>
        </table>
    </div>
    @if($x !== $length)
        <pagebreak/> @endif @php $x++; @endphp
@endforeach
</body>
</html>


            