<?php

use App\Models\Interpreter;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInterpretersTableV3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!schema::hasColumn('interpreters','api_prefix')) {

            Schema::table('interpreters', function (Blueprint $table) {
                $table->text('api_prefix')->nullable();
            });
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('interpreters', function (Blueprint $table) {
            $table->dropColumn('api_prefix');
        });    }
}
