<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    // Método que muestra la vista de Inicio de sesión
    public function index()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if( !auth()->attempt( $request->only('email', 'password'), $request->remember ))    //con remember puedo guardar un token en la bd para mantener la sesión abierta del usuario
        {
            return back()->with('mensaje', 'Datos Incorrectos');
        }

        return redirect()->route('posts.index', auth()->user()->username );
    }
}
