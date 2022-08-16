<?php

namespace App\Http\Controllers;

use App\Jobs\SalesCsvProcess;
use App\Models\Sale;
use Illuminate\Http\Request;

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

            foreach($chunks as $key => $chunk){
                if($key == 5){
                    $header = [];
                }
                SalesCsvProcess::dispatch($header, $chunk);
            }

            return "Sending Chunk File in Queue";
    
    
        }
    
        return "Please upload csv file";
    }




}
