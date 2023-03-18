<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expert;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Openning;

class OpenningController extends Controller
{
    public function index($id)
    {
        $records = Openning::where('expert_id', $id)->get();

        if(!$records)
            return response()->json(['message'=>'Invalid ID!'], 204);

        return response()->json([
            'message' => 'Data retrieved successfully',
            'data' => $records
        ]);
    }

    public function addTime(Request $request)
    {
        $request->validate([
            'day' => 'required',
            'start_hour' => 'required',
            'end_hour' => 'required'        //max and min values
        ]);

        $expert_id = Expert::where('user_id', auth()->user()->id)->first()->id;

        $openning = Openning::where('day', $request->day)->where('expert_id', $expert_id)->first();
        $hours = $openning->hours;

        for ( $i=$request->start_hour; $i < $request->end_hour; $i++ )
            $hours[$i]='1';

        $openning->update([
            'hours' => $hours
        ]);

        return response()->json([
            'message' => 'Time was added successfully'
        ]);
    }

    public function removeTime(Request $request)
    {
        $request->validate([
            'day' => 'required',
            'start_hour' => 'required',
            'end_hour' => 'required'
        ]);

        $expert_id = Expert::where('user_id', auth()->user()->id)->first()->id;

        $openning = Openning::where('day', $request->day)->where('expert_id', $expert_id)->first();
        $hours = $openning->hours;

        for ( $i=$request->start_hour; $i < $request->end_hour; $i++ )
            $hours[$i]='0';

        $openning->update([
            'hours' => $hours
        ]);

        return response()->json([
            'message' => 'Time was removed successfully'
        ]);
    }

    public function timesByDate($id, Request $request){
        $request->validate([
            'appointment' => 'required|date'
        ]);

        $day = strtolower( Carbon::createFromFormat('d/m/Y', $request->appointment)->format('l') );

        $openHours = Openning::where( 'expert_id', $id ) -> where( 'day',$day ) -> first() -> hours;

        return response()->json([
            'message' => 'Open times where retrieved successfully',
            'data' => $openHours
        ]);
    }
}