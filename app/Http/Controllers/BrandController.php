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
            'names' => 'required|array|min:1',
            'names.*' => 'required|string|max:40|regex:/(^([a-zA-Z ]+)(\d+)?$)/u',
        ]);
        $brand_ids = [];

        foreach ($request->names as $name) {
            $brand = new Brand;
            $brand->name = $name;
            $brand->save();
            array_push($brand_ids, $brand->id);
        }

        return response()->json([
            'status' => 201,
            'message' => 'Brands created successfully',
            'brand_ids' => $brand_ids,
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
        $brand = Brand::find($id);
        if (is_null($brand)) {
            return response()->json([
                'status' => 404,
                'errors' => 'No brand found to be shown!',
            ]);
        }

        return response()->json([
            'status' => 200,
            'brand' => $brand,
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
        $this->validate($request, [
            'name' => 'required|string|max:40|regex:/(^([a-zA-Z ]+)(\d+)?$)/u',
        ]);
        $data = Brand::find($id);
        $data->name = $request->name;
        $data->update();

        return response()->json([
            'status' => 200,
            'message' => 'Brand updated successfully',
            'data' => $data,
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
        $brand = Brand::find($id);
        if (is_null($brand)) {
            return response()->json([
                'status' => 404,
                'errors' => 'Item Not Found!',
            ]);
        }
        $brand->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Brand deleted successfully',
        ]);
    }

    public function search($name)
    {
        $brands = Brand::where('name', 'like', '%'.$name.'%')->get();
        if ($brands->isEmpty()) {
            return response()->json([
                'status' => 404,
                'errors' => 'No Brands found to be shown!',
            ]);
        }

        return response()->json($brands, 200);
    }
}
