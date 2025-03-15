<?php

namespace App\Http\Controllers;

use App\User;
use App\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LeaveRequest;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        if(Auth::user()->role=='admin') {
            $leaves = Leave::paginate(5);
        }else{
            $leaves =  Auth::user()->leave()->paginate(5);
        }
//        $user = Auth::user();
        return view('admin.leave.index',compact('leaves','user'));
    }

// My Edit
    public function leaveDetails(){
        $user = Auth::user();
        if (Auth::user()->role == 'admin') {
            $leaves = Leave::selectRaw('MONTH(date_from) as month, COUNT(*) as count')
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        
            // Convert numeric months to names
            $months = [];
            $numbers = [];
        
            foreach ($leaves as $leave) {
                $months[] = date('F', mktime(0, 0, 0, $leave->month, 1)); // Convert month number to name
                $numbers[] = $leave->count;
            }
        
            $result = [
                'number' => $numbers,
                'months' => $months
            ];
        
            return response()->json($result);
        }
    }

    public function leaveDetailsByUsername($username){
        $user = Auth::user();
        if (Auth::user()->role == 'admin' || Auth::user()->role == 'employee') {
            $leaves = Leave::selectRaw("MONTH(date_from) as month, COUNT(*) as count")
                ->join("users", "users.id", "=", "leaves.employee_id") 
                ->where("users.username","=",$username)
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        
            // Convert numeric months to names
            $months = [];
            $numbers = [];
        
            foreach ($leaves as $leave) {
                $months[] = date('F', mktime(0, 0, 0, $leave->month, 1)); // Convert month number to name
                $numbers[] = $leave->count;
            }
        
            $result = [
                'number' => $numbers,
                'months' => $months
            ];
        
            return response()->json($result);
        }
    }

//End

/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.leave.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeaveRequest $request)
    {
        Leave::create([
            'employee_id'   => Auth::id(),
            'leave_type'    => $request->leave_type,
            'date_from'     => $request->date_from,
            'date_to'       => $request->date_to,
            'days'          => $request->days,
            'reason'        => $request->reason,
        ]);

        Toastr::success('Leave successfully requested to HR!','Success');

        return redirect()->route('leave');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
//        dd($request->all());
           // $leave = $request -> get('search');
            $leaves =Leave::where('leave_type', 'LIKE',"%{$request->search}%")->paginate();
            return view('admin.leave.index',compact('leaves'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function edit(Leave $leave)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Leave $leave)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */

    public function approve(Request $request,$id)
    {

      //  dd($request->all());
        $leave = Leave::find($id);
//        dd($leave);
       if($leave){
           $leave->is_approved = $request -> approve;
           $leave->save();
           return redirect()->back();
       }
    }

    public function paid(Request $request,$id)
    {
        $leave = Leave::find($id);
        if($leave){
            $leave->leave_type_offer = $request -> paid;
            $leave->save();
            return redirect()->back();
        }
    }
}
