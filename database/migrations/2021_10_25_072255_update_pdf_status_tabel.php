<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePdfStatusTabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pdf_status', function (Blueprint $table) {
            $table->text('identifier')->nullable();
            $table->string('link')->nullable()->change();

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pdf_status', function (Blueprint $table) {
            $table->dropColumn('identifier');
            $table->dropColumn('link');
        });

    }
}
