<?php

namespace App\Http\Controllers;

use App\Jobs\SalesCsvProcess;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function upload(){
        return view('upload');
    }

    public function chunkFile(Request $request){
        if( $request->has('mycsv') ){
        
            $data = array_map("str_getcsv", file( $request->file('mycsv') ));
            $header = $data[0];
            unset($data[0]);
            
            $chunks = array_chunk($data, 50);

            $batch = Bus::batch([])->dispatch();

            foreach($chunks as $key => $chunk){

                $batch->add(new SalesCsvProcess($header, $chunk));

            }

            return  response()->json($batch);
    
    
        }
    
        return "Please upload csv file";
    }


    public function batch($batchId){
        $details = Bus::findBatch($batchId);
		return response()->json($details);
    }
	
	public function inProgress(){
		$batch_jobs = DB::table('job_batches')->where('pending_jobs', '>', 0)->get();
		if( count($batch_jobs) > 0 ){
			$batch_id = $batch_jobs[0]->id;
			$details = Bus::findBatch($batch_id);
			return response()->json($details);
		}
		return response()->json([]);
	}



}
