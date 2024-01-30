<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // Método para mostrar el formulario de registro
    public function index ()
    {
        return view('auth.register'); //se posiciona en la carpeta views, le digo que entre a auth y despues en register.blade
    }

    // Método para guardar los datos del formulario
    public function store(Request $request)
    {
        // dd($request->get('username')); // para asegurarme de que el valor que ingreso en el input se está enviando (el form usa el campo "name")

        // Modificar el Request (hacerlo lo menos posible)
        $request->request->add(['username' => Str::slug( $request->username )]);
        //slug elimina los acentos y hace que se almacene "sin espacios (reemplaza con guion) y en minusculas" el username

        // Validacion
        $this->validate($request, [
            'name' => ['required', 'max:30'],
            'username' => ['required', 'unique:users', 'min:3', 'max:20'],
            'email' => ['required', 'unique:users', 'email', 'max:60'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        // Creo un registro de usuario y lo almaceno en la BD
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make( $request->password )  //uso Hash para almacenar la contraseña de forma segura
        ]);

        // Autenticar a un usuario
        auth()->attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        // Redireccionar al usuario al Hub
        return redirect()->route('posts.index', auth()->user()->username);
    }
}
