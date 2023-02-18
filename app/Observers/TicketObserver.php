<?php

namespace App\Observers;

use App\Http\Controllers\LogsController;
use App\Models\logs;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TicketObserver
{
    /**
     * Handle the Ticket "created" event.
     *
     * @param Ticket $ticket
     * @return void
     */
    public function created(Ticket $ticket)
    {
        $logs = new Logs();
        $logs->actionName = "ticket created";
        $logs->actionTime = Carbon::now();
        $logs->User()->associate(Auth::user());
        $logs->logable()->associate($ticket);
        $logs->save();
    }

    /**
     * Handle the Ticket "assignAgent" event.
     *
     * @param Ticket $ticket
     * @return void
     */
    public function assignAgent(Ticket $ticket)
    {
        $logs = new Logs();
        $logs->actionName = "agent assigned";
        $logs->actionTime = Carbon::now();
        $logs->User()->associate(Auth::user());
        $logs->logable()->associate($ticket);
        $logs->save();
    }

    /**
     * Handle the Ticket "updateStatus" event.
     *
     * @param Ticket $ticket
     * @return void
     */
    public function updateStatus(Ticket $ticket)
    {
        $logs = new Logs();
        $logs->actionName = "status updated";
        $logs->actionTime = Carbon::now();
        $logs->User()->associate(Auth::user());
        $logs->logable()->associate($ticket);
        $logs->save();
    }

    /**
     * Handle the Ticket "commentTicket" event.
     *
     * @param Ticket $ticket
     * @return void
     */
    public function commentTicket(Ticket $ticket)
    {
        $logs = new Logs();
        $logs->actionName = "ticket commented";
        $logs->actionTime = Carbon::now();
        $logs->User()->associate(Auth::user());
        $logs->logable()->associate($ticket);
        $logs->save();
    }

}
