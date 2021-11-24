<?php

use App\Models\Interpreter;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateApiPrefixForPdf extends Migration
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
            $notebook->update(['api_prefix'=>env('NOTEBOOK_HOST').'/pdf/files/notebook']);
        }
        $gavahi_ha = Interpreter::where('identifier','like','%gavahi%')->get();
        foreach ($gavahi_ha as $gavahi){
            $gavahi->update(['api_prefix'=>env('GAVAHI_HOST').'/pdf/files/gavahi']);
        }
        $direct_mails = Interpreter::where('identifier','like','direct_mail%')->get();
        foreach ($direct_mails as $direct_mail){
            $direct_mail->update(['api_prefix'=>env('DIRECT_MAIL_HOST').'/pdf/files/direct_mail']);
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
