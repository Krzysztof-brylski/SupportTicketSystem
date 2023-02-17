<?php

namespace App\Http\Controllers;

use App\Dto\ResponseDTO;
use App\Models\Labels;
use App\Services\LabelsService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LabelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $label = Labels::paginate(10);
        return Response()->json($label,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data=$request->validate([
            'name'=>'string|required|unique:\App\Models\Labels,name',
        ]);
        try{
            (new LabelsService())->createLabel($data);
            return Response()->json((new ResponseDTO(null,"ok",false))->toArray(),
                201);
        }catch (\Exception $exception){
            return Response()->json((new ResponseDTO(null,$exception->getMessage(),true))->toArray(),
                201);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Labels  $label
     * @return Response
     */
    public function show(Labels $label)
    {
        return Response()->json((new ResponseDTO($label->toArray(),"ok",false))->toArray(),
            200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\Models\Labels  $label
     * @return Response
     */
    public function update(Request $request, Labels $label)
    {
        $data=$request->validate([
            'name'=>'string|required|unique:\App\Models\Labels,name',
        ]);
        try{
            (new LabelsService())->updateLabel($data, $label);
            return Response()->json((new ResponseDTO(null,"ok",false))->toArray(),
                200);
        }catch (\Exception $exception){
            return Response()->json((new ResponseDTO(null,$exception->getMessage(),true))->toArray(),
                500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Labels  $label
     * @return Response
     */
    public function destroy(Labels $label)
    {
        if($label->Ticket()->exists()){
            return Response()->json((new ResponseDTO(null,"This label is in use",true))->toArray(),
                403);
        }
        $label->delete();
        return Response()->json((new ResponseDTO(null,"deleted",false))->toArray(),
            200);
    }
}
