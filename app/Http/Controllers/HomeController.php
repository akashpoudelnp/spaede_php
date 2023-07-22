<?php

namespace App\Http\Controllers;

use Spaede\Contracts\Response;
use Spaede\Support\BaseController;

class HomeController extends BaseController
{
    public function index(): Response
    {
        $data = [1, 2, 3];
        return view('home.index', compact('data'));
    }

    public function save()
    {
        $name = request()->input('yay');
        $email = request()->input('email', 'akash@email.com');

        return response()->redirect('/');
    }
}