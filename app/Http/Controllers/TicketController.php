<?php

namespace App\Http\Controllers;

use App\Dto\ResponseDTO;
use App\Enums\UserRolesEnum;
use App\Http\Requests\CreateTicketRequest;
use App\Models\Ticket;
use App\Rules\UserIsAgent;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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
     * @return Response
     */
    public function index(Request $request)
    {
        if($request->user()->tokenCan('role-user')){
            $tickets=Ticket::where('user_id',Auth::id())->paginate(10);
            return Response()->json($tickets,200);
        }
        if($request->user()->tokenCan('role-agent')){
            $tickets=Ticket::where('agent_id',Auth::id())->paginate(10);
            return Response()->json($tickets,200);
        }
        if($request->user()->tokenCan('role-admin')){
            $tickets=Ticket::paginate(10);
            return Response()->json($tickets,200);
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
     * @return Response
     */
    public function show(Ticket $ticket,Request $request)
    {
        if($request->user()->tokenCan('role-admin')){
            return Response()->json($ticket,200);
        }
        if($request->user()->id == $ticket->author_id or $request->user()->id == $ticket->agent_id ){
            return Response()->json($ticket,200);
        }
        return Response()->json((new ResponseDTO(null,"Access deny",true ))->toArray(),403);
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
           'status'=>'string|required'
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
        if($request->user()->id == $ticket->author_id){
            $ticket->delete();
            return Response()->json($ticket,200);
        }
        return Response()->json((new ResponseDTO(null,"Access deny",true ))->toArray(),403);

    }
}
