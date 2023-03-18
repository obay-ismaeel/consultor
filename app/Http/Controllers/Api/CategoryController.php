<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    
    public function index()
    {
        return response()->json([
            'message' => 'Data retrieved successfully!',
            'data' => Category::all()
        ]);
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories',
            'description' => 'required'
        ]);

        Category::create([
            'name'  =>  $request->name,
            'description'   =>  $request->description
        ]);

        return response()->json([
            'message' => $request->name . ' Category was added successfully'
        ]);
    }

    public function show($id)
    {
        $record = Category::find($id);
        
        if(!$record)
            return response()->json(['message'=>'Invalid ID!'], 404);
        
        return response()->json([
            'message' => 'Data retrieved successfully',
            'data' => $record
        ]);
    }

    
    public function update(Request $request, $id)
    {
        
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        Category::find($id)->update([
            'name'=>$request->name,
            'description'=>$request->description
        ]);
                
        return response()->json([
            'message' => 'Data updated successfully'
        ]);

    }

    public function destroy($id)
    {
        $record = Category::find($id);
        
        if(!$record)
            return response()->json(['message'=>'Invalid ID!']); //REQUIRED!?

        $record->delete();

        return response()->json([
            'message' => 'Category Deleted Successfully'
        ]);
        
    }
}
