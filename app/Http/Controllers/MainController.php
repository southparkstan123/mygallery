<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gmopx\LaravelOWM\LaravelOWM;
use Illuminate\Support\Facades\Validator;

class MainController extends Controller
{
    public function getWeather(Request $request){

        $validator = Validator::make($request->all(), [
            'location' => 'required'
        ]);

        if($validator->fails()){
            $results = [
                'success' => false,
                'errors' => $validator->errors(),
            ];
            return response()->json($results);
        }else{
            $location = $request->get('location');
            
            $lowm = new LaravelOWM();
            $current_weather = $lowm->getCurrentWeather($location);
    
            $results = [
                'success' => true,
                'weather' => $current_weather->weather,
                'humidity' => ['value' => $current_weather->humidity->getValue(), 'units' => $current_weather->humidity->getUnit()],
                'temperature' => ['value' => $current_weather->temperature->now->getValue(),'units' =>$current_weather->temperature->now->getUnit()],
            ];
            return response()->json($results);
        }
    }
}
