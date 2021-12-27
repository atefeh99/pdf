<?php

use App\Models\Interpreter;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StoreDirectMailHtml extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Interpreter::store([
            'identifier'=>'direct_mail_1',
            'description' => 'this is the direct_mail_1 html',
            'api_prefix'=> 'https://sina-dev.map.ir/pdf/files/direct_mail',
            'html' => '
            <html>
<head>
    <style>
        html, body {
            direction: rtl;
            font-family: mitra;
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
        }

        table td {
            text-align: center;
        }

        .table2 {
            min-width: 100%;
            width: 100%;
            margin-right: 0;
            margin-left: 0;

        }

        .table2 tr {
            text-align: center;
            padding: 0;
        }

        .table2 td {
            padding: 0;
            /*width:150px;*/
            /*text-align: center;*/
        }

        .table3 {
            min-width: 100%;
            width: 100%;
            margin-right: 0;
            margin-left: 0;
            bottom: 0;
        }

        .table3 tr {
            padding: 0;
        }

        .table3 td {
            padding: 0;

        }
    </style>
</head>
<body>
@foreach($data as $key=>$item)
    <table>
        <tr>
            <td style="margin-right:0;"><img src="var:logo" width="45px" height="40px"
                                             style="margin-right: 0px;margin-top: 0px"></td>
            <td style="width:45px;"></td>
            <td style="text-align: right;font-size: 14;width:100px;padding-top: 0">
                کد پستی
                {{$item["postalcode"]}}

            </td>
            <td style="width:90px;"></td>
        </tr>
    </table>
    <table class="table2">
        <tr>
            <td style="border-bottom: 1px dotted black;padding-top:2px">
                @if($item["statename"])
                    @if($item["locationtype"] != "روستا")
                        استان
                    @endif
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
                    {{$item["locationtype"]}}:
                    {{$item["locationname"]}}
                @endif
            </td>
        </tr>
        <tr>
            <td style="border-bottom: 1px dotted black">
                @if($item["parish"])
                    {{$item["parish"]}}
                @endif
                @if($item["mainavenue"])
                    ،
                    {{$item["mainavenue"]}}
                @endif
                @if($item["preaventypename"] && $item["preaven"])
                    ،
                    {{$item["preaventypename"]}}
                    {{$item["preaven"]}}
                @endif
                @if($item["avenuetypename"] && $item["avenue"])
                    ،
                    {{$item["avenuetypename"]}}
                    {{$item["avenue"]}}
                @endif

            </td>
        </tr>
        <tr>
            <td>
                @if($item["plate_no"])
                    پلاک
                    {{$item["plate_no"]}}
                @endif
                @if($item["building"])
                    ،
                    {{$item["building"]}}
                @endif
                @if($item["blockno"])
                    ،
                    بلوک
                    {{$item["blockno"]}}
                @endif
                @if($item["floorno"])
                    ،
                    طبقه
                    @if($item["floorno"] != "۰")
                        {{$item["floorno"]}}
                    @else
                        همکف
                    @endif
                @endif
                @if($item["unit"])
                    ،
                    واحد
                    {{$item["unit"]}}
                @endif
            </td>
        </tr>
    </table>
    <table class="table3">
        <tr>
            <td style="height: 12px;"></td>
        </tr>
        <tr>
            <td>
               @if($class_name)
                    {{$class_name}}
                @endif
            </td>

        </tr>
        <tr>
            <td>
                اصلاحات ....................................................................................
    </table>
    @if($x !== $length)
        <pagebreak/> @endif @php $x++; @endphp@endforeach
</body>
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
        $item = Interpreter::where('identifier','direct_mail_1');
        $item->delete();
    }
}
