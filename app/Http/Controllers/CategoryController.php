<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages() 
            ]);
        }
        else
        {
            $category = new Category;
            $category->name = $request->name;
            $category->save();
            return response()->json([
                'status' => 201,
                'message' => 'Category created successfully'
            ]);
        }
    }

}
