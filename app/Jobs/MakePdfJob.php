<?php

namespace App\Jobs;

use App\Http\Controllers\PdfMakerController;
use App\Http\Services\PdfMakerService;
use App\Models\PdfStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;


class MakePdfJob implements ShouldQueue
{
    use  InteractsWithQueue;

    public $identifier;
    public $link;
    public $uuid;
    public $user_id;
    public $data;

    public function __construct($identifier, $link, $uuid, $user_id, $data)
    {
        $this->identifier = $identifier;
        $this->link = $link;
        $this->uuid = $uuid;
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

       $info = PdfMakerService::getPdf($this->identifier,
            $this->link,
            $this->uuid,
            $this->user_id,
            $this->data);

        //success
        PdfStatus::changeStatus($this->job->getJobId(),'success');
        PdfStatus::updateInfo($this->job->getJobId(),$info);
        Log::info('status changed');
//        return "job_id:". $this->job->getJobId();
//        Log::info('job_id:  '.$this->job->getJobId());
    }


}

