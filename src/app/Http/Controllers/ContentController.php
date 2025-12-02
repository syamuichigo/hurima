<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function mypage()
    {
        return view('mypage');
    }

    public function sell()
    {
        return view('sell');
    }

    public function profile()
    {
        return view('profile');
    }

    public function purchase()
    {
        return view('purchase');
    }

    public function item()
    {
        return view('item');
    }

    public function address()
    {
        return view('address');
    } 
}
