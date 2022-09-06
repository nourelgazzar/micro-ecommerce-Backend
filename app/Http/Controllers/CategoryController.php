<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::all();
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:40', 'regex:/(^([a-zA-Z]+)(\d+)?$)/u', 'unique:categories'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ]);
        } else {
            $category = new Category;
            $category->name = $request->name;
            $category->save();

            return response()->json([
                'status' => 201,
                'message' => 'Category created successfully',
            ]);
        }
    }

    public function show($id)
    {
        $category = Category::find($id);
        if (is_null($category))
        {
            return response()->json([
                'status' => 404,
                'errors' => 'No category found to be shown!',
            ]);
        }
        
        return response()->json([
            'status' => 200,
            'category' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:40', 'regex:/(^([a-zA-Z]+)(\d+)?$)/u', 'unique:categories'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ]);
        } else {
            $category = Category::find($id);
            if (is_null($category) || empty($category))
            {
                return response()->json([
                    'status' => 404,
                    'errors' => 'No category found to be updated!',
                ]);
            }
            else
            {
                $category->name = $request->name;    
                $category->save();
                return response()->json([
                    'status' => 200,
                    'category' => $category,
                ]);
            }
        }
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if (is_null($category) || empty($category))
       {
            return response()->json([
                'status' => 404,
                'errors' => 'No category found to be deleted!',
            ]);
       }
       else
       {
           $category->destroy($id);
           return response()->json([
            'status' => 200,
            'message' => 'The category has been deleted!',
        ]);
       }
    }

    /**
     * Search for a name
     *
     * @param  int  $name
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        $category = Category::where('name', 'like', '%'.$name.'%')->get();
        
        if (!count($category))
       {
            return response()->json([
                'status' => 404,
                'errors' => 'No category found to be shown!',
            ]);
       }
        return response()->json([
            'status' => 200,
            'category' => $category,
        ]);
    }
}
