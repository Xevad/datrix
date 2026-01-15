<?php

namespace App\Http\Controllers;
use App\Mail\requestFormMail;
use App\Models\RequestForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RequestFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Pages.RequestForm');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Pages.RequestForm');
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

        $requestForm= RequestForm::create($data);
$mailData=[
    'co_name'=>$req->co_name,
    'subject'=>$req->subject,
    'cust_name'=>$req->cust_name,
    'details'=>$req->details,
    'slug'=> $slug,
    'key'=> $gen


];
        Mail::to($req->email,'info@datrixtecsolutions.com')->send(new requestFormMail($mailData));

        return redirect()->back()->with('success', 'Request Form sent');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $requestForm = RequestForm::where('slug',$id ,)->first();

        return view('Pages.SingleReq')->with('requestForm',$requestForm);
    }

    public function showAdmin($id)
    {
        $requestForm = RequestForm::where('slug',$id ,)->first();

        return view('users.pages.singleRequestFormAdmin')->with('requestForm',$requestForm);
    }

    public function requestFormAdmin(){
        $requestForm = RequestForm::all();
        return view('users.pages.requestFormAdmin')->with('requestForm',$requestForm);
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

        $requestForm = RequestForm::where('id',$id)->update(['status'=>$request->status,'progress'=>$request->progress]);

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
        $requestForm= RequestForm::find($id);
        $requestForm->delete();
        return redirect()->to(route('requestFormAdmin'))->with('success', 'Deleted');
    }
}
