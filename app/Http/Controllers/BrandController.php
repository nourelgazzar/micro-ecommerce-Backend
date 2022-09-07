<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brand = Brand::all();

        return response()->json($brand, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
        ]);

        $brand = new Brand;
        $brand->name = $request->name;
        $brand->save();

        return response()->json([
            'status' => 201,
            'message' => 'Brand created successfully',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $this->validate($request, [
            'name' => 'required|string',
        ]);
        $data = Brand::find($id);
        $data->name = $request->name;
        $data->update();

        return response()->json([
            'status' => 200,
            'message' => 'Brand updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
////////////////////////////////
    public function destroy($id)
    {
        $data = Brand::find($id);
        if (is_null($data)) {
            return response()->json([
                'status' => 404,
                'errors' => 'Item Not Found!',
            ]);
        }
        $data->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Brand deleted successfully',
        ]);
    }

    public function search($name)
    {
        $brands = Brand::where('name', 'like', '%'.$name.'%')->get();
        if (! count($brands)) {
            return response()->json([
                'status' => 404,
                'errors' => 'No Brands found to be shown!',
            ]);
        }

        return response()->json($brands, 200);
    }
}
