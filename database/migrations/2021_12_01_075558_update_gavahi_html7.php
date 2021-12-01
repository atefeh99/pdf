<?php

use App\Models\Interpreter;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGavahiHtml7 extends Migration
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
            'html' => '

            ']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $item = Interpreter::where('identifier','gavahi_1');
        $item->delete();
    }
}
