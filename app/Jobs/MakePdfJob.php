<?php

namespace App\Jobs;

use App\Http\Services\PdfMakerService;
use App\Http\Services\SendSmsService;
use App\Models\PdfStatus;
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

       $link = PdfMakerService::getPdf($this->identifier,
            $this->user_id,
            $this->data);
        Log::info('get link successfully');
       if($link == false){
           Log::info('get exception');
           throw new \Exception();
       }
        //success
        $data = [
           'link' => $link,
            'status'=> 'success',
        ];
        Log::info('try changing status');

        PdfStatus::updateRecord($this->job->getJobId(),$data);
        Log::info('status changed to success');
//        SendSmsService::sendSms($this->identifier,$data,$link,$this->user_id);


    }


}

