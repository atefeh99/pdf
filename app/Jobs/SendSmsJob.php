<?php

namespace App\Jobs;

use App\Http\Services\SendSmsService;
use App\Models\SuccessJobs;
use App\Modules\otp\UsersModule;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendSmsJob implements ShouldQueue
{

    use InteractsWithQueue;

    public $category;
    public $data;
    public $link;
    public $user_id;

    public const QUEUE_NAME = 'gavahi_sms';

    public function __construct($category, $data, $link, $user_id)
    {
        $this->category = $category;
        $this->data = $data;
        $this->link = $link;
        $this->user_id = $user_id;

    }

    public function handle()
    {
        SendSmsService::sendSms($this->category, $this->data, $this->link, $this->user_id);
        Log::info("gavahi:sms:user_id:$this->user_id:send successfully");

        $data = [
            'queue_name' => 'gavahi_sms',
            'data' => [
                'mobile' => UsersModule::getMobile($this->user_id),
            ],
            'job_id' => $this->job->getJobId(),
        ];
        try {
            SuccessJobs::createItem($data);
            Log::info("job " . $data['job_id']." success_record created");

        } catch (\Exception$e) {
            Log::info('err msg: ' . $e->getMessage());
        }
    }
}
