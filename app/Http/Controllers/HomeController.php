<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
        $data['userCount'] = User::doesntHave('roles')->count();

        return view('home', $data);
    }

    /**
     * Show file manager.
     *
     * @return View
     */
    public function fileManager()
    {
        return view('admin.file-manager');
    }
}
