<?php

namespace App\Http\Controllers;

use App\Dto\ResponseDTO;
use App\Models\Labels;
use App\Models\logs;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class LogsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $logs = logs::paginate(10);
        return Response()->json($logs,200);
    }


    /**
     * Display the specified resource.
     *
     * @param logs $logs
     * @return Response
     */
    public function show(logs $logs)
    {
        return Response()->json((new ResponseDTO($logs->with(['User','Log'])->get()->toArray(),"ok",false))->toArray(),
            200);
    }
}
