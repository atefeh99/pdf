<?php

use App\Models\Interpreter;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDirectMailHtml extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $item = Interpreter::findOrFail(5);
        $item->update([
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


        /*.border {*/
        /*    border: 1px solid black*/
        /*}*/

        /*.table td {*/
        /*    text-align: center*/
        /*}*/

        {{--.table2 td {--}}
        {{--    text-align: center;--}}
        {{--    width: 30px--}}
        {{--}--}}

        {{--.barcode {--}}
        {{--    padding: 1.5mm;--}}
        {{--    margin: 5px;--}}
        {{--    margin-bottom: 4px;--}}
        {{--    vertical-align: top;--}}
        {{--    color: black--}}
        {{--}--}}

        {{--.barcodecell {--}}
        {{--    vertical-align: middle--}}


        table td {
            text-align: center;
        }
        .table2{
            background-color: blue;
            min-width: 100%;
        }
        .table2 td {
            background-color: white;
            padding: 0;
            /*width:150px;*/
        }
    </style>
</head> {{--@php--}} {{--import --}} {{--@endphp--}}
<body > {{--{{ var_dump($data) }}--}}
{{--<div style="height:100%;width:100%;background-color: blue">--}}
    @foreach($data as $key=>$item)
        <table >
            <tr>
                <td style="float: right"><img src="var:logo" width="50px" height="50px"
                                                         style="margin-right: 0px;margin-top: 0px"></td>
                <td width="50px"></td>
                <td style="text-align: right;font-size: 15">
                    کد پستی
                    {{$item["postalcode"]}}

                </td>
                <td style="width:90px"></td>
            </tr>
        </table>
        <table class="table2">

            <tr>
                <td style="border-bottom: 1px dotted black">
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
                        {{$item["floorno"]}}
                    @endif
                    @if($item["unit"])
                        ،
                        واحد
                        {{$item["unit"]}}
                    @endif
                </td>
            </tr>
            <tr>
                <td>
                    @if($item["activity_type"])
                        {{$item["activity_type"]}}
                    @endif
                    @if($item["activity_name"])
                        ،
                        {{$item["activity_name"]}}
                    @endif
                </td>

            </tr>
            <tr>
                <td>
                    اصلاحات ..............................
                </td>
            </tr>
        </table>
        @if($x !== $length)
            <pagebreak/> @endif @php $x++; @endphp@endforeach
{{--</div>--}}
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
        $item = Interpreter::findOrFail(5);
        $item->delete();
    }
}
