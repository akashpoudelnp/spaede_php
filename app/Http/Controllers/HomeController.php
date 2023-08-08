<?php

namespace App\Http\Controllers;

use Spaede\Contracts\Response;
use Spaede\Support\BaseController;
use Spaede\Support\Database\DB;

class HomeController extends BaseController
{

    public function index(): Response
    {
        $data = [1, 2, 3];

        $query = DB::table('users')
            ->where('id', '>=', '114')
            ->where('id', '<', '120')
            ->whereIn('name', ['Akash'])
            ->get(['id', 'name']);

        return view('home.index', compact('data'));
    }

    public function save()
    {
        $name = request()->input('yay');
        $email = request()->input('email', 'akash@email.com');

        return response()->redirect('/');
    }
}