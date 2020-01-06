<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Assigned_ticket;
use App\Ticket;
use App\User;
use App\Status;
use App\Priority;
use Auth;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(isset($_GET['id']))
        {   
            Ticket::where('ticket_id',$_GET['id'])
                        ->update(['assign_to'=>$_GET['eid']]);
        }

        $ticket = Ticket::orderBy('status_id')->get();
        $status = Status::all();
        $priority = Priority::all();
        $employees = User::where('user_role', 2)->get();
        // $signedTicket = DB::table('tickets')
        //                 ->join('users','tickets.assign_to','users.id')
        //                 ->join('status','tickets.status_id','status.id')
        //                 ->get();
        
        
       
        
        
        return view('admin',compact('employees','ticket','status','priority'));
      
    }
    public function store()
    {
      dd(request('ticket_id')); 
    }

    public function updateCreds(Request $request)
    {
        // dd($request->all());
        $priorityID = Priority::where('priority',$request['priority'])->first();
        // dd($priorityID->priority_id);
        $statusID = Status::where('status_name',$request['status'])->first();
        // dd($statusID->id);
        if($request['assignTo']==null)
        {
            $assignToID = 0;
        }
        else
        {
            $assignTo = User::where('name',$request['assignTo'])->first();
            $assignToID = $assignTo->id;
        }
        // dd($priorityID->priority_id,$statusID->id,$assignToID);
        Ticket::Where('ticket_id',$request['ticket_id'])
                ->update(['priority_id'=>$priorityID->priority_id,
                        'status_id'=>$statusID->id,
                        'assign_to'=>$assignToID]);
        
        return redirect(url('admin'));

    }
}
