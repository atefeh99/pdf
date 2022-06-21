<?php

use App\Models\Interpreter;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGavahiHtml12 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $item = Interpreter::where('identifier', 'gavahi_1');
        $item->update([
            'html' => '

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
                    <span>{{$item["post_address"]["part1"]}}
                    {{$item["post_address"]["plate_sign"]}}
                    {{$item["post_address"]["part2"]}}
                    {{$item["post_address"]["part3"]}}
                    {{$item["post_address"]["part4"]}}
                    {{$item["post_address"]["part5"]}}
                    {{$item["post_address"]["part6"]}}
                    {{$item["post_address"]["part7"]}}
                    {{$item["post_address"]["part8"]}}
                    @if($item["post_address"]["floor_is_neg"])
                        {{$item["post_address"]["floor_sign"]}}
                    @endif
                    {{$item["post_address"]["part9"]}}
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
                        {{$item["address"]["part1"]}}     
                        {{$item["address"]["part2"]}} 
                        {{$item["address"]["plate_sign"]}}  
                        {{$item["address"]["part3"]}}                
                        {{$item["address"]["part4"]}}
                        @if($item["address"]["floor_is_neg"])
                            {{$item["address"]["floor_sign"]}} 
                        @endif 
                        {{$item["address"]["part5"]}}
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
                        {{$item["address"]["part1"]}}     
                        {{$item["address"]["part2"]}} 
                        {{$item["address"]["plate_sign"]}}  
                        {{$item["address"]["part3"]}}                
                        {{$item["address"]["part4"]}}
                        @if($item["address"]["floor_is_neg"])
                            {{$item["address"]["floor_sign"]}}  
                        @endif
                        {{$item["address"]["part5"]}}
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
                    ۲.مدت اعتبار این گواهی از تاریخ صدور {{$ttl}} ماه می باشد.
                    <br>
                    ۳.با مراجعه به سامانه GNAF.POST.IR می توانید به صورت آنلاین گواهی کد پستی خود را دریافت کنید.
                    <br>
                                                            ۴.هزینه صدور گواهی برای درخواست های باجه ای (هرکدپستی جدید) با احتساب ارزش افزوده ۱۵۵,۸۷۰ ریال می باشد.
<br>
۵.هزینه صدور گواهی برای درخواست های باجه ای (فقط صدور گواهی) با احتساب ارزش افزوده ۳۱,۱۷۴ ریال می باشد.
<br>
۶.هزینه صدور گواهی برای درخواست های آنلاین (هرکدپستی جدید) با احتساب ارزش افزوده ۱۱۹,۰۲۸ ریال می باشد.
<br>
۷.هزینه صدور گواهی برای درخواست های آنلاین (فقط صدور گواهی) با احتساب ارزش افزوده ۲۲,۶۷۲ ریال می باشد.
                    <br>
                    ۸.اصالت این گواهی از طریق استعلام بر خط بارکد تعیین می گردد.
                    <br>
                    ۹.در صورت مشاهده هر گونه مغایرت در اطلاعات این گواهی با نشانی خود، به نزدیکترین دفتر پستی مراجعه
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
            
']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $item = Interpreter::where('identifier', 'gavahi_1');
        $item->delete();
    }
}
