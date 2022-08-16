<?php

namespace App\Jobs;

use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SalesCsvProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
}
