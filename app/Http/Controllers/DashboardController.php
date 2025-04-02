<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count(); // Conta os usuários no banco
        return view('home', compact('totalUsers')); // Passa para a view correta
    }
}
