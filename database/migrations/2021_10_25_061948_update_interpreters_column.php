<?php

use App\Models\Interpreter;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInterpretersColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $notebooks= Interpreter::where('identifier','like','%notebook%')->get();
        foreach ($notebooks as $notebook){
            $notebook->update(['api_prefix'=>'https://gnaf-dev.map.ir/pdf']);
        }
        $gavahi_ha = Interpreter::where('identifier','like','%gavahi%')->get();
        foreach ($gavahi_ha as $gavahi){
            $gavahi->update(['api_prefix'=>'https://sina-dev.map.ir/pdf']);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
