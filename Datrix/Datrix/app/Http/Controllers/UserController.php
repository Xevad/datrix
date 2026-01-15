<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Status;

use Illuminate\Http\Request;
use App\Models\AffiliateClient;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {

        // dump(Auth::user()->role->id);
        // $user=Auth::user();

        $user = Auth::user()->with('affiliateClient.paymentStatus')->get();
        $image = Storage::url(Auth::user()->profile_photo_path);

        foreach ($user as $user) {

            if ($user->id == Auth::user()->id) {

                $userselected = $user;
            }
        }


        $total = 0;
        $previewApproved = [];
        foreach ($userselected->affiliateClient as $preview) {

            if ($preview->status_id == 2 || $preview->status_id == 3) {
                array_push($previewApproved, $preview);
                $total += $preview->commission;
            }
        }






        return view('users.pages.index', compact('user', 'total', 'previewApproved', 'userselected', 'image'));
    }

    public function profilePhoto(User $user, Request $request)
    {

        $validated = $request->validate([
            'photo' => 'image',
        ]);
        $id = Auth::user()->id;
        $photoName =  Auth::user()->name . '_' . time() . '_' . $request->file('photo')->getClientOriginalName();
        $path = $request->file('photo')->storeAs('public/profile', $photoName);
        User::where('id', $id)->update([
            'profile_photo_path' => $path
        ]);
        return back();
    }
}
