<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $template = 'backend.user.index';
        return view('backend.dashboard.layout', compact('template'));
    }
}
