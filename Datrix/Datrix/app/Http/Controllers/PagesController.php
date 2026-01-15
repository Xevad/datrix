<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index()
    {
        return view('Home');
    }

    public function home()
    {
        return view('Pages.Home');
    }

    public function about()
    {
        return view('Pages.About');
    }

    public function services()
    {
        return view('Pages.Services');
    }

    public function complaint()
    {
        return view('Pages.Complaint');
    }

    public function requestForm()
    {
        return view('Pages.RequestForm');
    }

    public function iso()
    {
        return view('Pages.ISO');
    }

    public function AML()
    {
        return view('Pages.AML');
    }
}
