<?php

namespace App\Http\Controllers;

use App\Mail\complaintMail;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Pages.Complaint');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Pages.Complaint');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $validated = $req->validate([
            'co_name'=>'required',
            'cust_name'=>'required',
            'contact'=>'required|required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'email'=>'required',
            'subject'=>'required',
            'details'=>'required'

        ]);
        $slug = Str::slug($req->subject,'-'.Str::random(3).'-');
        $gen=Str::random(6);
        $key =bcrypt($gen);

        $data=$req->all();
        $data+= ['slug'=>$slug];
        $data+=['key'=>$key];

        $complaint= Complaint::create($data);
$mailData=[
    'co_name'=>$req->co_name,
    'subject'=>$req->subject,
    'cust_name'=>$req->cust_name,
    'details'=>$req->details,
    'slug'=> $slug,
    'key'=> $gen


];
        Mail::to($req->email,'info@datrixtecsolutions.com')->send(new complaintMail($mailData));

        return redirect()->back()->with('success', 'Complaint sent');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $complaint = Complaint::where('slug',$id ,)->first();

        return view('Pages.SingleComp')->with('complaint',$complaint);
    }

    public function showAdmin($id)
    {
        $complaint = Complaint::where('slug',$id ,)->first();

        return view('users.pages.singleCompliantAdmin')->with('complaint',$complaint);
    }

    public function complaintAdmin(){
        $complaint = Complaint::all();
        return view('users.pages.complaintAdmin')->with('complaint',$complaint);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

        $validated = $request->validate([
            'status'=>'required',
            'progress'=>'required|between:0,100|numeric'
        ]);

        $complaint = Complaint::where('id',$id)->update(['status'=>$request->status,'progress'=>$request->progress]);

        return redirect()->back()->with('success', 'Updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $complaint= Complaint::find($id);
        $complaint->delete();
        return redirect()->to(route('complaintAdmin'))->with('success', 'Deleted');
    }
}
