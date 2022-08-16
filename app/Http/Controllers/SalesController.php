<?php

namespace App\Http\Controllers;

use App\Jobs\SalesCsvProcess;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

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
            
            $chunks = array_chunk($data, 100);

            $batch = Bus::batch([])->dispatch();

            foreach($chunks as $key => $chunk){

                $batch->add(new SalesCsvProcess($header, $chunk));

            }

            return  $batch->id;
    
    
        }
    
        return "Please upload csv file";
    }


    public function batch($batchId){
        return Bus::findBatch($batchId);
    }



}
