<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Interpreter;

class CreatePostalcodesHtml extends Migration
{

    public function up()
    {
        Interpreter::store([
            'identifier' => 'postalcodes_1',
            'description' => 'this is the postalcodes_1 html',
            'api_prefix' =>env('POSTALCODES_HOST').'/pdf/files/postalcodes',
            'html' => '<html>
            <head>
                <style>
                body{direction:rtl;font-family:mitra}
                .table8 td{border:1px solid black;padding-left:10px;padding-right:10px;text-align:center;width:80px;line-height:15px}
                .ta{border-collapse:collapse;width:100%}
                </style>
            </head>
            <body>
                
            <table class="table8 ta">
                <tr><td colspan="3">جدول کد پستی</td></tr>
                <tr>
                    <td>کد پستی</td>
                    <td>طبقه</td>
                    <td>واحد</td>
        
                </tr>
                 @foreach($data as $datum)
                 <tr>
                     <td>{{$datum["postalcode"]}}</td>
                     <td dir="ltr" >
                         @if($datum["floorno"])
                            @if($datum["floorno"] == "۰")
                             همکف
                             @else
                            {{$datum["floorno"]}}
                         @endif
                         @else
                         ندارد
                         @endif
                        </td>
                     <td dir="ltr" >
                         @if($datum["unit"])
                         {{$datum["unit"]}}
                         @else
                         ندارد
                         @endif
                        </td>
                    </tr>
                  @endforeach

                </table>
              
                </body>
                </html>
        
        ',

        ]);
     



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        $items = self::where('identifier','like','%postalcodes%')->get();
        $items->delete();
    }


}
