<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function upload(){
        return view('upload');
    }

    public function chunkFile(Request $request){
        if( $request->has('mycsv') ){
        
            $data = file( $request->file('mycsv') );
            $header = $data[0];
            unset($data[0]);
            
            $chunks = array_chunk($data, 100);

            foreach($chunks as $key => $chunk){
                $file_name = "temp_{$key}.csv";
                $folder_path = resource_path("temp");
                $file_location = "{$folder_path}/{$file_name}";

                file_put_contents($file_location, $header);
                file_put_contents($file_location, $chunk, FILE_APPEND);
            }
    
    
        }
    
        return "Please upload csv file";
    }


    public function store(){
        $folder_path = resource_path("temp");
        $files = glob("{$folder_path}/*.csv");
        
        foreach($files as $file){
            $data = array_map("str_getcsv", file($file) );
            $header = $data[0];
            unset($data[0]);

            foreach($data as $value){
                $sale_data = array_combine($header, $value);
                Sale::create($sale_data);
            }
        }
    }


}
