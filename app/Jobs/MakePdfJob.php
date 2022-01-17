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


class MakePdfJob implements ShouldQueue
{
    use  InteractsWithQueue;

    public $identifier;
    public $user_id;
    public $data;

    public function __construct($identifier, $user_id, $data)
    {
        $this->identifier = $identifier;
        $this->user_id = $user_id;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @param \App\Services\AudioProcessor $processor
     * @return void
     */
    public function handle()
    {
        Log::info('start getting pdf');

        $result = PdfMakerService::getPdf($this->identifier,
            $this->user_id,
            $this->data);
        Log::info('get link successfully');
        if ($result == false) {
            Log::info('get exception');
            throw new \Exception();
        }
        //success
        $data = [
            'link' => $result['link'],
            'status' => 'success',
        ];
        Log::info('try changing status');

        PdfStatus::updateRecord($this->job->getJobId(), $data);
        Log::info('status changed to success');
        if ($this->identifier == 'notebook') {

            if (isset($this->data['block_id'])) {
                $metadata['type'] = 'block';
                $metadata['block_id'] = $this->data['block_id'];
                $metadata['block_name'] = $result['extra_info']['notebook_1']['block_no'];
            } elseif (isset($this->data['tour_id'])) {
                $metadata['type'] = 'tour';
                $metadata['tour_id'] = $this->data['tour_id'];
                $metadata['tour_name'] = $result['extra_info']['notebook_1']['tour_no'];
            }
            $metadata['link'] = $result['link'];
            $success_time = Date::convertCarbonToJalali(carbon::now());
            $app = env('MQTT_APPLICATION');
            $mqtt = new Publisher($metadata, $this->job->getJobId(), $success_time, $this->user_id, $app, $this->identifier,null, null, null,null);
            $mqtt->send();
//        SendSmsService::sendSms($this->identifier,$data,$link,$this->user_id);

        }
    }


}

