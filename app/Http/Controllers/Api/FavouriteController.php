<?php

namespace App\Http\Controllers\Api;

use App\Models\Favourite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FavouriteController extends Controller
{

    public function index()
    {
        $favs = Favourite::where( 'user_id', auth()->user()->id )->with('expert')->get();

        return response()->json([
            'message'=>'data retrived successfully',
            'data'=>$favs
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            "expert_id"=>'required'
        ]);

        if(Favourite::where('user_id', auth()->user()->id)->where('expert_id',$request->expert_id)->first())
            return response()->json([
                'message' => 'Favourite relation already exists'
            ]);

        Favourite::create([
            'user_id'=>auth()->user()->id,
            'expert_id'=>$request->expert_id
        ]);

        return response()->json([
            'message'=>'added to favourite successfully'
        ]);
    }

    public function destroy($id)
    {
        $fav = Favourite::where('user_id',auth()->user()->id)->where('expert_id',$id)->first()->delete();

        if(!$fav)
        {
            return response()->json([
                'message'=>'invalid id !',
            ],404);
        }

        return response()->json([
            'message'=>'removed from favourite successfully'
        ]);
    }
}
