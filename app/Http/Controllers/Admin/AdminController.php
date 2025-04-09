<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AdminController extends Controller
{
     /**
     * Write code on Method
     *
     * @return response()
     */
    public function index(): View
    {
        return view('admin.dashboard');
    }  
}
