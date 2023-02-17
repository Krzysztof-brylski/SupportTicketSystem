<?php

namespace App\Http\Controllers;

use App\Dto\ResponseDTO;
use App\Models\Categories;
use App\Services\CategoriesService;
use http\Env\Response;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Categories::paginate(10);
        return Response()->json((new ResponseDTO($categories,"ok",false)),200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data=$request->validate([
           'name'=>"string|required|unique:\App\Models\Categories,name"
        ]);
        try{
            (new CategoriesService())->createCategory($data);
            return Response()->json("created",201);
        }catch (\Exception $exception){
            return Response()->json($exception->getMessage(),500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param Categories $categories
     * @return \Illuminate\Http\Response
     */
    public function show(Categories $categories)
    {
        return Response()->json($categories->toArray(),200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Categories $categories
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categories $categories)
    {
        $data=$request->validate([
            'name'=>"string|required|unique:\App\Models\Categories,name"
        ]);
        try{
            (new CategoriesService())->updateCategory($categories,$data);
            return Response()->json((new ResponseDTO(null,"created",false))->toArray(),
                201);
        }catch (\Exception $exception){
            return Response()->json((new ResponseDTO(null,$exception->getMessage(),true))->toArray(),
                500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Categories $categories
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categories $categories)
    {
        if($categories->Ticket()->exists()){
            return Response()->json((new ResponseDTO(null,"This category is in use",true))->toArray(),
                500);
        }
        $categories->delete();
        return Response()->json((new ResponseDTO(null,"deleted",false))->toArray(),
            200);
    }
}
