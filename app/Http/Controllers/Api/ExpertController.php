<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Consult;
use Illuminate\Http\Request;
use App\Models\Expert;
use App\Models\Favourite;
use App\Models\Openning;
use App\Models\User;
use Illuminate\Support\Facades\File;

class ExpertController extends Controller
{

    public function index()
    {
        $experts = Expert::get();

        if( auth()->user()->is_expert ){
            $expert_id = Expert::where( 'user_id', auth()->user()->id ) ->id;
            $experts->where( 'id', '!=', $expert_id );
        }

        return response()->json([
            'message' => 'Data retrieved successfully!',
            'data' => $experts 
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|unique:experts',
            'address' => 'required',
            'image' => 'image',
            'experience' => 'required'
        ]);

        //STORING IMAGE

        $image_name_to_store = 'default.png';

        if ($request->hasFile('image'))
        {
            $image_name_with_extension = $request->file('image')->getClientOriginalName();  //get full path

            $image_name = pathinfo( $image_name_with_extension, PATHINFO_FILENAME );        //get full path without extension

            $extension = $request->file('image')->getClientOriginalExtension();             //get extesion 

            $image_name_to_store = $image_name . '_' . time() . '.' . $extension;           //the storing name

            $request->file('image')->storeAs( 'public/images/users', $image_name_to_store );    //store the image file
        }

        $expert = Expert::create([
            'user_id' => $request->user_id,
            'address'  =>  $request->address,
            'image_path'   =>  $image_name_to_store,
            'experience'    =>   $request->experience
        ]); //$requrest->all()

        for($i=1; $i<8; $i++)
            Openning::create([
                'expert_id' => $expert->id,
                'day' => $i
            ]);

        if(auth('sanctum')->check()){
            return response()->json([
                'message' => 'Congrats You\'re and expert now!'
            ]);
        }

        return response()->json([
            'message' => 'Expert Registered Successfully!',
            'access_token' => User::find($request->user_id)->createToken('auth_token')->plainTextToken
        ]);
    }

    public function edit($id)
    {
        $record = Expert::find($id);
        
        if(!$record)
            return response()->json(['message'=>'Invalid ID!'], 404);

        return response()->json([
            'message' => 'Data retrieved successfully',
            'data' => $record
        ]);
    }

    public function show($id)
    {
        $record = Expert::find($id);
        
        if(!$record)
            return response()->json(['message'=>'Invalid ID!'], 404);
        
        if(Favourite::where('expert_id', $id)->where('user_id', auth()->user()->id)->get())
            $fav=true;
        else
            $fav=false;

        return response()->json([
            'message' => 'Data retrieved successfully',
            'is_favourite' => $fav,
            'rating' => ExpertController::getRating($id),
            'data' => $record
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        
        $request->validate([
            'address' => 'required',
            'experience' => 'required',
            'image' => 'image'
        ]);
        
        $expert = Expert::find($id);
        $image_name_to_store = 'default.png';

        if ($request->hasFile('image'))
        {
            $image_name_with_extension = $request->file('image')->getClientOriginalName();  //get full path

            $image_name = pathinfo( $image_name_with_extension, PATHINFO_FILENAME );        //get full path without extension

            $extension = $request->file('image')->getClientOriginalExtension();             //get extesion 

            $image_name_to_store = $image_name . '_' . time() . '.' . $extension;           //the storing name

            $request->file('image')->storeAs( 'public/images/users', $image_name_to_store );    //store the image file

            if( $expert->image_path != 'default.png' ){     //delete the old image
                File::delete( storage_path('app/images/users/'. $expert->image_path) );
            }
        }

        $expert->update([
            'address' => $request->address,
            'experience' => $request->experience,
            'image_path' => $request->hasFile('image') ? $image_name_to_store : $expert->image_path
        ]);

                
        return response()->json([
            'message' => 'Data updated successfully'
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $record = Expert::find($id);
        
        if(!$record)
            return response()->json(['message'=>'Invalid ID!'], 400); //REQUIRED!?

        $record->delete();

        return response()->json([
            'message' => 'Expert Deleted Successfully'
        ]);
        
    }

    public static function getRating($id)
    {
        return Consult::where('expert_id', $id)
        ->where('rating', '!=', null)
        ->avg('rating');
    }


}
