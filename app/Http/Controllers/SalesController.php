<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function upload(){
        return view('upload');
    }

    public function store(Request $request){
        if( $request->has('mycsv') ){
        
            $data   = array_map('str_getcsv', file($request->file('mycsv')));
            $header = $data[0];
            unset($data[0]);
           
            foreach($data as $item){
                $item_data = array_combine($header, $item);
                Sale::create($item_data);
            }

            dd("Done my job");
    
    
        }
    
        return "Please upload csv file";
    }
}
