<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Expert;

class UserController extends Controller
{

    public function register(Request $request){

        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required',
            'phone_number'=>'required',
            'is_expert'=>'required'
        ]);

        if($request->is_expert){
            $request->validate([
                'experience' => 'required',
                'address' => 'required',
                'image' => 'image',
            ]);
        }

        //creating the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'balance' => isset($request->balance)? $request->balance : 0,
            'is_expert' => $request->is_expert
        ]);

        if($request->is_expert){
            $request->request->add([ 'user_id' => $user->id ]);
            return (new ExpertController)->store($request);
        }

        return response()->json([
            'message' => 'User Registerd Successfully',
            'access_token' => $user->createToken('auth_token')->plainTextToken
        ]);
    
    }
    
    public function login(Request $request){
        
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        $user = User::where('email', $request->email)->first();
        
        if(!$user){     //user does not exist
            return response()->json(['message' => 'Account Not Found'], 404);
        }
        elseif(!Hash::check($request->password, $user->password)){     //incorrect password
            return response()->json(['message' => 'Invalid Password'], 404);
        }

        return response()->json([
            'message' => 'User logged in successfully!',
            'is_expert' => $user->is_expert,
            'access_token' => $user->createToken('auth_token')->plainTextToken
        ]);

    }

    public function profile(){

        if(auth()->user()->is_expert)
            return response()->json([
                'message' => 'Expert Profile Info',
                'data' => $expert = Expert::where('user_id', auth()->user()->id)->first(),
                'rating' => (new ExpertController)->getRating($expert->id)
            ]);

        return response()->json([
            'message' => 'User Profile Info',
            'data' => auth()->user()
        ]);
    }

    public function update(Request $request){
        
        $request->validate([
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required',
            'phone_number'=>'required',
            'is_expert'=>'required'
        ]);

        
        
        $user_id = auth()->user()->id;
        $expert = Expert::where( 'user_id', $user_id )->first();

        if($request->is_expert){    //validate expert data if is_expert
            
            $request->validate([
                'address' => 'required',
                'experience' => 'required',
                'image' => 'image'
            ]);

        }elseif($expert){           //delete expert data if ! is_expert

            (new ExpertController)->destroy($expert->id);
        
        }

        //update the user info

        User::find($user_id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'balance' => isset($request->balance) ? $request->balance : 0,
            'is_expert' => $request->is_expert
        ]);

        if($expert){                           //if expert exists update data

            return (new ExpertController)->update($request, $expert->id);

        }elseif($request->is_expert){          //if expert does NOT exist then create the expert
            
            $request->request->add([ 'user_id' => $user_id ]);
            return (new ExpertController)->store($request);
        
        }

        return response()->json([
            'message' => 'User Updated Successfully'
        ]);
    }

    public function destroy(){   //ON DELETE CASCADE

        User::find(auth()->user()->id)->delete();

        return response()->json([
            'message' => 'User Was deleted!'    
        ]);

    }

    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'User Logged out successfully'
        ]);
    }

 
}
