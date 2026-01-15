<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AffiliateClient;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Contracts\Service\Attribute\Required;

class AffiliateAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $total = 0;
        $previewApproved = [];
        foreach (Auth::user()->affiliateClient as $preview) {

            if ($preview->status_id == 2 || $preview->status_id == 3) {
                array_push($previewApproved, $preview);
                $total += $preview->commission;
            }
        }

        $affiliateProjects = AffiliateClient::with('user')->latest('id')->get();

        $totalProjects = $affiliateProjects->count();
        $totalApproved = $affiliateProjects->where('status_id', '=', '2')->count();
        $affiliateProjects = AffiliateClient::with('user')->latest('id')->paginate(5);

        $totalUsers = DB::table('users')->where('role_id', '=', '1')->count();
        $image = null;
        if (Auth::user()->profile_photo_path) {
            $image = Storage::get(Auth::user()->profile_photo_path);
        }

        // foreach($affiliateProjects as $af){
        // dd($af);
        // }
        return view('users.pages.admin', compact('total', 'previewApproved', 'affiliateProjects', 'totalUsers', 'totalProjects', 'totalApproved', 'image'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = DB::table('users')->where('role_id', '=', '1')->get();
        return view('users.pages.addProject', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'project_name' => 'required',
            'type' => 'required',
            'user_id' => 'required'
        ]);
        $data = AffiliateClient::create($request->all());
        return redirect()->back()->with('success', 'Project Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return 0;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $users = User::all();
        $project = AffiliateClient::find($id);
        return view('users.pages.editProject', compact('project', 'users'));
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
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required',
            'contact' => 'required|numeric',
            'project_name' => 'required',
            'type' => 'required',
            'user_id' => 'required',
            'status_id' => 'Required',
            'amount' => 'numeric',
            'commission' => 'numeric',
            'payment_status_id' => 'numeric'

        ]);

        $affiliateProjects = AffiliateClient::where('id', $id)->update($request->only([
            'name',
            'type',
            'contact',
            'project_name',
            'user_id',
            'status_id',
            'payment_status_id',
            'amount',
            'commission'
        ]));

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
        //
    }
}
