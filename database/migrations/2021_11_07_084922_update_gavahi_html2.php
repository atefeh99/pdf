<?php

use App\Models\Interpreter;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGavahiHtml2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $item = Interpreter::where('identifier','gavahi_1');
        $item->update([
            'html' => '<html>
<head>
    <style>body {
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
            color: black
        }

        .barcodecell {
            vertical-align: middle;
        }</style>
</head> {{--@php--}} {{--import --}} {{--@endphp--}}
<body> {{--{{ var_dump($data) }}--}}
{{--<div style="border:1px solid black ">--}}
    @foreach($data as $key=>$item)
        <div style="border:1px solid black ">
        <table>
            <tr>
                <td style="margin-left:90px;width:200px; height:100px"><img src="var:logo" width="150px" height="100px">
                </td>
                <td style="width:350px;font-weight: bold;text-align:center;vertical-align: top;font-size:19px;"><span
                        style="margin-right:40px">گواهی کد پستی ده رقمی</span>
                    <div style="font-weight: normal">GNAF.POST.IR</div>
                    <div>اداره کل پست استان تهران-منطقه ۱۵ پستی</div>

                </td>
                </td>
                <td style="width:250px;text-align: center">
                    <div class="barcodecell">
                        <barcode style="margin-bottom:0px;padding-bottom: 0" code={{$item["barcode"]}} type="C128C" class="barcode" size="0.8" height="1"/>
                        <span style="padding-top: 0;font-size: 20">{{$item["barcode"]}}</span>
                    </div>
{{--                    <div style="width:200px;margin-top: -100px">{{$item["barcode"]}}</div>--}}
                    <div><span
                            style="margin-right:10em"> (اصالت گواهی با استعلام این بارکد تعیین می شود) </span>
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
                <td style="width:125px;text-align: right;height:30px"><span
                        style="width:85px;text-align: right;margin-left: 30px"><b>کد پستی ده رقمی:</b></span>
                </td>
                @for($i=count($item["postalcode"]) - 1;$i>=0;$i--)
                    <td class="postcode">
                        <b>
                            {{$item["postalcode"][$i]}}
                        </b>
                    </td> @endfor
                <td></td>
                <td style="width:120px;height:4px;font-size: 12px">
                    <b >
                         تاریخ صدور :{{$date}}

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
                <td style="width:13%;vertical-align: text-top;">
                    <b>
                        نشانی پستی :
                    </b>
                </td>
                <td style="width:77%">
                    <b>
                        <span>
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
                            ورودی/ بلوک
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
                        </span>
                    </b>
                </td>
                <td style="width:8%"></td>
            </tr>
            <tr>
                <td>
                    <b>
                        نوع کاربری :
                    </b>
                </td>
                <td style="width:120px">
                    <b>
                        {{$item["activity_type"]}}
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
                </b>
                &#171;
               <b>
                    {{$item["address"]}}
                </b>
                &#187;
            </td>
        </tr>
        @if($item["image_exists"])
            <tr>
                <td><img src="var:{{$item["postalcode"]}}" width="691px" height="377px"></td>
            </tr>
        @endif
    </table>
</div>

<div style="border:1px solid black;border-top: none;">
    <table>
        <tr>
            <td style="width:76%">
                <b>قابل توجه درخواست کننده گرامی:</b>
                <br>
                ۱.کدپستی مندرج بر روی گواهی منحصرا متعلق به نشانی ذکر شده بوده و استفاده از این کد پستی برای سایر مکانها
                موجب اشتباه در پردازش اطلاعات و عدم خدمت رسانی صحیح به شما خواهد بود.
                <br>
                ۲.مدت اعتبار این گواهی از تاریخ صدور {{$ttl}} روز می باشد.
                <br>
                ۳.با مراجعه به سامانه GNAF.POST.IR می توانید به صورت آنلاین گواهی کد پستی خود را دریافت کنید.
                <br>
                ۴.هزینه صدور گواهی فوق {{$price}} ریال و با احتساب ارزش افزوده ........ ریال می باشد.
                <br>
                ۵.اصالت این گواهی از طریق استعلام بر خط بارکد تعیین می گردد.
<br>
                ۶.در صورت مشاهده هر گونه مغایرت در اطلاعات این گواهی با نشانی خود، به نزدیکترین دفتر پستی مراجعه نمایید.
            </td>
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

'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $item =Interpreter::where('identifier','gavahi_1');
        $item->delete();
    }
}
