<?php

use App\Models\Interpreter;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDirectMailHtml3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $item = Interpreter::where('identifier', 'direct_mail_1');
        $item->update([
            'html' => '<html>
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
            <td style="margin-right:0;"><img src="var:logo" width="40px" height="35px"
                                             style="margin-right: 0px;margin-top: 25px"></td>
            <td style="width:45px;"></td>
            <td style="text-align: right;font-size: 24;padding-top: 30px">
                کدپستی&#160;&#160;
            </td>
            <td style="font-family:traffic;font-size: 20;padding-top:30px">
                {{$item["postalcode"]}}
            </td>
            <td style="width:90px;"></td>
        </tr>
    </table>
    <table class="table2">
        <tr>
            <td style="height: 17px;"></td>
        </tr>
        <tr>
            <td style="padding-top:2px;">

                <p style="border-bottom: 1px dotted black;text-decoration-color: black;font-size:{{$item["font_size1"]}}"  >
                    {{$item["country_division"]}}
                </p>

            </td>
        </tr>
        <tr>
            <td style="padding-top:5px;font-size:{{$item["font_size2"]}}">
                {{$item["parish_and_way"]}}
            </td>
        </tr>
        <tr>
            <td style="padding-top:3px">
            {{$item["pelak_and_entrance"]}}
        </tr>
    </table>
    <table class="table3">
        <tr>
            <td style="height: 30px;"></td>
        </tr>

        <tr>
            <td style="height: 30px;">
                @if($item["activity"])
                    {{$item["activity"]}}
                @endif
            </td>

        </tr>
        <tr>
            <td>
                اصلاحات .....................................................................
            </td>
        </tr>
    </table>
    @if($x !== $length)
        <pagebreak/> @endif @php $x++; @endphp@endforeach
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
        $item = Interpreter::where('identifier','direct_mail_1');
        $item->delete();
    }
}
