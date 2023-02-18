<?php


namespace App\Services;

use App\Enums\StatusEnum;
use App\Models\Categories;
use App\Models\Labels;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketService
{

    public function assignAgentToTicket(array $data,Ticket $ticket)
    {
        $agent=User::where('id',$data['agent_id'])->first();
        $ticket->assignAgent($agent);

    }

    public function updateTicketStatus(array $data,Ticket $ticket)
    {
        $ticket->updateStatus($data['status']);
    }

    public function createTicket($data)
    {
        $category = Categories::where('id',$data['category_id'])->first();
        $label = Labels::where('id',$data['label_id'])->first();
        $ticket=new Ticket();
        $ticket->title = $data['title'];
        $ticket->description = $data['description'];
        $ticket->priority=$data['priority'];
        //$ticket->files = $data['files'];

        $ticket->Author()->associate(Auth::user());
        $ticket->Labels()->associate($label);
        $ticket->Categories()->associate($category);

        $ticket->save();
    }
}
