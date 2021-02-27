<?php

namespace App\Http\Controllers;

use Config\information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index(){
        $establishmentInfos = information::establishmentInfos();
        $pageTitle = 'Accueil';
        $token = '';
        return view('welcome', compact('establishmentInfos', 'pageTitle', 'token'));
    }
}
