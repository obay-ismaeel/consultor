<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Expert;
use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'price1' => 'required',
            'price2' => 'required',
            'price3' => 'required',
            'price4' => 'required',
            'price5' => 'required',
        ]);

        $expert = Expert::where( 'user_id', auth()->user()->id )->first();
        
        for( $i=1 ; $i<=5 ; $i++ ) {
            $index = 'price' . $i;
            $price = $request[$index];
            $cat = Category::find($i);
            
            if( $price==0  ){       //NOT checked
                
                $service = Service::where('category_id', $cat->id)->where('expert_id', $expert->id)->first();

                if($service)
                    $service->delete();

            }else{                              //CHECKED

                $service = Service::where('category_id', $cat->id)->where('expert_id', $expert->id)->first();

                if($service){                   //if service found UPDATE

                    $service->update(['price' => $price]);

                }else{                          //NOT found then CREATE

                    Service::create([
                        'expert_id' => $expert->id,
                        'category_id' => $cat->id,
                        'price' => $price,
                    ]);

                }

            }
        }

        return response()->json([
            'message' => 'Services were updated successfully'
        ]);
    }

    public function getServices()
    {
        $expert = Expert::where( 'user_id', auth()->user()->id )->first();
        $services = Service::where( 'expert_id' , $expert->id )->get();
        $prices = array();

        for( $i=1 ; $i <= count($services) ; $i++ ) {
        
            $category_id = $services[$i]->category_id;
            $price = $services[$i]->price;

            $index = 'price' . $category_id;
            $prices[$index] = $price;

        }

        return response()->json([
            'message' => 'data retrieved successfully',
            'price1' => $prices['price1'] ?? 0,
            'price2' => $prices['price2'] ?? 0,
            'price3' => $prices['price3'] ?? 0,
            'price4' => $prices['price4'] ?? 0,
            'price5' => $prices['price5'] ?? 0
        ]);
    }
}
