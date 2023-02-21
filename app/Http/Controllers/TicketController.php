<?php

namespace App\Http\Controllers;

use App\Dto\ResponseDTO;
use App\Enums\StatusEnum;
use App\Enums\UserRolesEnum;
use App\Http\Requests\CreateTicketRequest;
use App\Http\Resources\TicketPaginatorResouce;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Rules\UserIsAgent;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class TicketController extends Controller
{

    /**
     * TicketController constructor.
     *
     */

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        if($request->user()->tokenCan('role-user')){

            return TicketResource::collection(Ticket::where('author_id',Auth::id())
                ->with(['Comments','Categories','Labels'])->paginate(10));
        }
        if($request->user()->tokenCan('role-agent')){
            return  TicketResource::collection(Ticket::where('agent_id',Auth::id())
                ->with(['Comments','Categories','Labels'])->paginate());
        }
        if($request->user()->tokenCan('role-admin')){
            return TicketResource::collection(Ticket::with(['Comments','Categories','Labels','Logable','Author','Agent'])
                ->paginate(10));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateTicketRequest $request
     * @return void
     */
    public function store(CreateTicketRequest $request)
    {
        $data=$request->validated();
        (new TicketService())->createTicket($data);

        return Response()->json("created",201);
    }

    /**
     * Display the specified resource.
     *
     * @param Ticket $ticket
     * @param Request $request
     * @return TicketResource
     */
    public function show(Ticket $ticket,Request $request)
    {


        if($request->user()->id != $ticket->author_id){
            return Response()->json("Access deny",403);
        }
        return new TicketResource(Ticket::with(['Comments','Categories','Labels','Logable','Author','Agent'])->first());
        //
    }

    /**
     * Update the specified ticket status.
     *
     * @param Request $request
     * @param Ticket $ticket
     * @return Response
     */
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $data=$request->validate([
           'status'=>['string','required',new Enum(StatusEnum::class)]
        ]);
        (new TicketService())->updateTicketStatus($data, $ticket);
        return Response()->json("updated",200);
    }
    /**
     * Assign to the specified ticket user agent.
     *
     * @param Request $request
     * @param Ticket $ticket
     * @return Response
     */
    public function assignAgent(Request $request, Ticket $ticket)
    {
        $data=$request->validate([
            'agent_id'=>['required','exists:\App\Models\User,id', new UserIsAgent()]
        ]);
        (new TicketService())->assignAgentToTicket($data, $ticket);
        return Response()->json("updated",200);
    }


    /**
     * Comment  specified ticket.
     *
     * @param Request $request
     * @param Ticket $ticket
     * @return Response
     */
    public function comment(Request $request, Ticket $ticket)
    {
        $data=$request->validate([
            'content'=>['required','string']
        ]);
        $ticket->commentTicket($data['content']);
        return Response()->json("updated",201);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param Ticket $ticket
     * @param Request $request
     * @return Response
     */
    public function destroy(Ticket $ticket, Request $request)
    {
        if($request->user()->tokenCan('role-admin')){
            $ticket->delete();
            return Response()->json($ticket,200);
        }
        return Response()->json((new ResponseDTO(null,"Access deny",true ))->toArray(),403);

    }
}
