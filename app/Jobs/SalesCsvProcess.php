<?php

namespace App\Jobs;

use App\Mail\SalesCsvProcessFailedMail;
use App\Models\Sale;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SalesCsvProcess implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $header;
    protected $chunk_data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($header, $chunk_data)
    {
        $this->header     = $header;
        $this->chunk_data = $chunk_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->chunk_data as $data){
            $sales_data = array_combine($this->header, $data);
            Sale::create($sales_data);
        }
    }

        /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        $details = [
            'title' => 'CSV Milion Records Upload Failed',
            'body' => $exception->getMessage()
        ];
       
        Mail::to('mdrabiulhasan.me@gmail.com')->send(new SalesCsvProcessFailedMail($details));
       
        dd("Email is Sent.");
    }
}
