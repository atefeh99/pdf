<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Interpreter;

class UpdatePriceInGavahi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        $item = Interpreter::where('identifier','gavahi_1')->get();
        $item->update([
            'html' => '<html><head><style>body{direction:rtl;font-family:mitra}.border{border:1px solid black}.table td{text-align:center}.table2 td{text-align:center;width:30px}.barcode{padding:1.5mm;margin:5px;margin-bottom:4px;vertical-align:top;color:black}.barcodecell{vertical-align:middle}</style></head> {{--@php--}} {{--import --}} {{--@endphp--}}<body> {{--{{ var_dump($data) }}--}}@foreach($data as $key=>$item)<table><tr><td style="margin-left:90px;width:200px; height:100px"><img src="var:logo" width="150px" height="100px"></td><td style="width:350px;font-weight: bold;text-align:center;vertical-align: top;font-size:19px;"><span style="margin-right:40px">گواهی کد پستی ده رقمی</span></td></td><td style="width:250px;text-align: center"><div class="barcodecell"> <barcode code={{$item["barcode"]}} type="C128C" class="barcode" size="1" height="1"/></div><div><span style="margin-right:10em;font-size: 18px"> (اصالت گواهی با استعلام این بارکد تعیین می شود) </span></div></td></tr></table><table class="table2" style="border-collapse: collapse"><tr><td><br></td></tr><tr><td><br></td></tr><tr><td style="width:100px;text-align: right;margin-left:0"><span style="width:100px;text-align: right;margin-left:0">کد پستی ده رقمی:</span></td> @for($i=count($item["postalcode"]) - 1;$i>=0;$i--)<td class="border">{{$item["postalcode"][$i]}}</td> @endfor<td></td><td style="width:100px;">تاریخ صدور :</td><td style="width:100px;">{{$date}}</td></tr></table><table><tr><td><br></td></tr><tr><td>استان:</td><td style="width:120px">{{$item["statename"]}}</td><td>شهرستان:</td><td style="width:120px">{{$item["townname"]}}</td><td>بخش:</td><td style="width:160px">{{$item["zonename"]}}</td><td>دهستان:</td><td style="width:160px">{{$item["villagename"]}}</td></tr><tr><td style="width:120px">شهر/روستا/آبادی :</td><td style="width:120px">{{$item["locationname"]}}</td></tr></table><table><tr><td><br></td></tr><tr><td><br></td></tr><tr><td style="width:120px">نشانی پستی :</td></tr><tr><td>محله:</td><td style="width:230px">{{$item["parish"]}}</td><td style="width:180px">معبر ماقبل آخر:</td><td style="width:120px">{{$item["preaven"]}}</td><td>معبرآخر:</td><td style="width:230px">{{$item["avenue"]}}</td></tr><tr><td>پلاک:</td><td style="width:230px">{{$item["plate_no"]}}</td><td style="width:180px">ورودی/ بلوک:</td><td style="width:120px">{{$item["blockno"]}}</td><td>طبقه:</td><td style="width:230px">{{$item["floorno"]}}</td><td>واحد:</td><td style="width:120px">{{$item["unit"]}}</td></tr><tr><td>نوع کاربری :</td><td style="width:120px">{{$item["building_type"]}}</td></tr><tr><td><br></td></tr><tr><td><br></td></tr></table><table><tr><td style="width:100px">نشانی استاندارد ملی:</td></tr><tr><td style="width:100%">{{$item["address"]}}</td></tr><tr><td></td></tr></table> @if($item["image_exists"])<table><tr><td></td></tr><tr><td height="50px"></td></tr><tr><td style="width:100px">تصویر کروکی ملک:</td></tr><tr><td></td></tr><tr><td></td></tr><tr><td><img src="var:{{$item["postalcode"]}}" width="700px" height="400px"></td></tr></table> @endif<div ><table><tr><td style="width:70%;border-top:4px solid black"> قابل توجه درخواست کننده گرامی:<br>۱.کدپستی مندرج در گواهی منحصرا متعلق به نشانی ذکر شده بوده و استفاده از این کد پستی برای سایر مکانها موجب اشتباه در پردازش اطلاعات و عدم خدمت رسانی صحیح به شما خواهد بود<br>۲.مدت اعتبار این گواهی از تاریخ صدور {{$ttl}} روز می باشد.<br>۳.با مراجعه به سامانه GNAF.POST.IR می توانید کد پستی خود را دریافت کنید.<br>۴.هزینه صدور گواهی فوق {{$price}} ریال می باشد.<br>۵.اصالت این گواهی از طریق استعلام بر خط بارکد تعیین می گردد.<br>۶.در صورت مشاهده هر گونه مغایرت در اطلاعات این گواهی با نشانی خود, به نزدیکترین دفتر پستی مراجعه نمایید.</td><td style="width:30%;border-top:4px solid black;border-right:4px solid black;text-align: center"> <barcode code={{$QRCode}} type="QR" class="barcode" size="1.5" error="M" height="2" disableborder="1"/></td></tr></table></div>@if($x !== $length) <pagebreak/> @endif @php $x++; @endphp@endforeach</body></html>'
        ]);


    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */

    public function down()
    {
        $item = Interpreter::where('identifier','gavahi_1')->get();
        $item->delete();
    }
}
