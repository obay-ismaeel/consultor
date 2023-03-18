<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Expert;
use Illuminate\Http\Request;
use App\Models\Service;
use Symfony\Component\HttpFoundation\ServerBag;
use WeakMap;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('category','expert')->get();
        
        if(auth()->user()->is_expert){
            $expert_id = Expert::where( 'user_id', auth()->user()->id ) ->id;
            $services->where( 'expert_id', '!=', $expert_id );
        }

        if(!$services)
            return response()->json(['message'=>'there is no data']);

        return response()->json([
            'message' => 'Data retrieved successfully!',
            'data' => $services
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([        // !I SHOULD HANDLE POSSIBLE REPEATED RELATIONS!
            'category_id' => 'required', 
            'expert_id' => 'required',
            'price' => 'required'
        ]);

        if( Service::where('category_id', $request->category_id)->where('expert_id', $request->expert_id)->first() )
            return response()->json(['message', 'Service already exists!'], 400);

        Service::create([
            'category_id' => $request->category_id,
            'expert_id' => $request->expert_id,
            'price' => $request->price
        ]);

        return response()->json([
            'message' => 'Service was created successfully'
        ]);
    }

    public function show($id)
    {
        $record = Service::with('expert')->find($id);
        
        if(!$record)
            return response()->json(['message'=>'Invalid ID!'], 400);
        
        return response()->json([
            'message' => 'Data retrieved successfully',
            'data' => $record
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required',
            'expert_id' => 'required',
            'price' => 'required'
        ]);

        Service::find($id)->update([
            'category_id' => $request->categroy_id,
            'expert_id' => $request->expert_id,
            'price' => $request->price
        ]);

                
        return response()->json([
            'message' => 'Service updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $record = Service::find($id);
        
        if(!$record)
            return response()->json(['message'=>'Invalid ID!'], 404);

        $record->destroy();
        
        return response()->json([
            'message' => 'Data retrieved successfully'

        ]);
    }

    public function servicesByCatId($id){

        $category = Category::find($id);

        if(!$category)
            return response()->json([
                'message'=>'invalid ID!'
            ], 404);
            
        $services = Service::with('category', 'expert')->where('category_id', $category->id)->get();
        
        if( auth()->user()->is_expert ){
            $expert_id = Expert::where( 'user_id', auth()->user()->id ) ->id;
            $services->where('expert_id', '!=', $expert_id);
        }

        return response()->json([
            'message' => 'retrieved successfully',
            'data' => $services
        ]);
    }

    /*
            THE SERVICES UPDATE
    */
    
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

        for( $i=0 ; $i < count($services) ; $i++ ) {
        
            $category_id = $services[$i]->category_id;
            $price = $services[$i]->price;

            $index = 'price' . $category_id;
            $prices[$index] = $price;

        }

        return response()->json([
            'message' => 'data retrieved successfully',
            'price1' => array_key_exists('price1', $prices) ? $prices['price1'] : 0,
            'price2' => array_key_exists('price2', $prices) ? $prices['price2'] : 0,
            'price3' => array_key_exists('price3', $prices) ? $prices['price3'] : 0,
            'price4' => array_key_exists('price4', $prices) ? $prices['price4'] : 0,
            'price5' => array_key_exists('price5', $prices) ? $prices['price5'] : 0
        ]);

    }

}
