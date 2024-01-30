<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogoutController extends Controller
{
    // Método para cerrar la sesión del usuario
    public function store()
    {
        auth()->logout();

        return redirect()->route('login');
    }
}
