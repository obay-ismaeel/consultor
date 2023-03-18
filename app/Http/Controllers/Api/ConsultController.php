<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Consult;
use App\Models\Expert;
use App\Models\User;
use App\Models\Service;


class ConsultController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'expert_id' => 'required',
            'category_id' => 'required',
            'appointment' => 'required|date',
            'hour' => 'required'
        ]);


        //check if the expert has another appointment in this time
        if(
            Consult::where( 'expert_id', $request->expert_id )->where('appointment', $request->appointment)
                    ->where('hour',$request->hour)->first()
            )
            return response()->json(['message' => 'Sorry, this appointment has already taken'], 400);

        //check if the user has another appointment in this time
        if(
            Consult::where( 'user_id', auth()->user()->id )->where('appointment', $request->appointment)
                    ->where('hour',$request->hour)->first()
            )
            return response()->json(['message' => 'Sorry, you have another appointment in this time'], 400);

        //check if the user has enough money
        $balance=auth()->user()->balance;
        $price=Service::where('expert_id', $request->expert_id)->where('category_id',$request->category_id)
                ->value('price');

        if($price > $balance)
            return response()->json(['message' => 'Sorry, you dont have enough money'], 400);

        //transfer money from user to the expert
        User::find(auth()->user()->id)->update([
            'balance'=>auth()->user()->balance - $price
        ]);

        $expert=Expert::find($request->expert_id);//->user;
        User::find($expert->user_id)->update([
            'balance'=>$expert->user->balance + $price
        ]);


        Consult::create([
            'user_id' => auth()->user()->id,
            'expert_id'  =>  $request->expert_id,
            'category_id'=>  $request->category_id,
            'appointment'   =>  $request->appointment,
            'hour'      => $request->hour,
            'rating'    =>   $request->rating,
            'is_completed' => $request->is_completed ?? 0
        ]); //$requrest->all()

        return response()->json([
            'message' => 'Consult was created successfully'
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'message' => 'consult retrieved successfully',
            'data' => Consult::with('user','service')->find($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'expert_id' => 'required',
            'category_id' => 'required',
            'appointment' => 'required',
            'hour' => 'required'
        ]);

        Consult::find($id)->update([
            'user_id' => auth()->user()->id,
            'expert_id'  => $request->expert_id ,
            'category_id'=>  $request->category_id,
            'appointment'   =>  $request->appointment,
            'hour'      => $request->hour,
            'rating'    =>   $request->rating,
            'is_completed' => $request->is_completed ?? 0
        ]); //$requrest->all()

        return response()->json([
            'message' => 'Consult was updated successfully',
            
        ]);
    }

    public function destroy($id)
    {
        $record = Consult::find($id);

        if(!$record)
            return response()->json(['message'=>'Invalid ID!'], 400);

        $record->destroy();

        return response()->json([
            'message' => 'Data deleted successfully',
        ]);
    }

    public function getTakenConsults(){

        if( !User::find(auth()->user()->id) )
            return response()->json([ 'message'=>'there is no such user'], 400);

        return response()->json([
            'message' => 'Data retrieved successfully',
            'data' => Consult::with('category', 'expert')->where('user_id', auth()->user()->id)->get()
        ]);
    }

    public function getGivenConsults(){

        $expert = Expert::where('user_id', auth()->user()->id)->first();

        if( !$expert )
            return response()->json([ 'message'=>'The user is not an expert!'], 400);

        $consults = Consult::with('user', 'category')->where('expert_id', $expert->id)->get();

        return response()->json([
            'message' => 'Data retrieved successfully',
            'data' => $consults
        ]);
    }
}
