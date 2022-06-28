<?php

namespace App\Jobs;

use App\Http\Services\PdfMakerService;
use App\Http\Services\SendSmsService;
use App\Helpers\Date;
use App\Models\PdfStatus;
use MqttNotification\Publisher;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;


class SendSmsJob implements ShouldQueue
{

    use  InteractsWithQueue;

    public $identifier;
    public $data;
    public $link;
    public $user_id;

    public function __construct($category, $data, $link, $user_id)
    {
        $this->category = $category;
        $this->data = $data;
        $this->link = $link;
        $this->user_id = $user_id;

    }


    public function handle()
    {
        SendSmsService::sendSms($this->identifier, $this->data, $this->link, $this->user_id);
        Log::info("gavahi:sms:$this->user_id:send successfully");
    }
}

