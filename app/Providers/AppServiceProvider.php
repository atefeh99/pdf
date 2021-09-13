<?php

namespace App\Providers;

use App\Models\PdfStatus;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

//use Queue;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function boot()
    {
//        Queue::failing(function ($connection, $job, $data) {
//            Log::info('job:::::::::::: '.$job);
//        });

        Queue::failing(function (JobFailed $event) {

            $data = [
                'job_id' => $event->job->getJobId(),
                'exception' => $event->exception,
                'code' => $event->exception->getCode(),
            ];
            //failed
            PdfStatus::changeStatus($event->job->getJobId(),'failed');

            Log::error('data: ' . json_encode($data));

        });
    }

    public function register()
    {
        //
    }

}


