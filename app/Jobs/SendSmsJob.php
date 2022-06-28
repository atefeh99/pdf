<?php

namespace App\Jobs;

use App\Database\Entity\SuccessJobs;
use App\Http\Services\SendSmsService;
use App\Modules\otp\UsersModule;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendSmsJob implements ShouldQueue
{

    use InteractsWithQueue;

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
        $data = [
            'queue_name' => 'gavahi',
            'data' => [
                'mobile' => UsersModule::getMobile($this->user_id),
            ],
            'job_id' => $this->job->getJobId(),
        ];
        
        SuccessJobs::createItem($data);
        }
}
