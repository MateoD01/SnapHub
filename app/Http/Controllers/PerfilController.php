<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PerfilController extends Controller
{
    // Para proteger la URL de los usuarios no autenticados
    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function index()
    {
        return view('perfil.index');
    }

    // Para validar y guardar los cambios del formulario
    public function store(Request $request)
    {
        // Modificar el Request
        $request->request->add(['username' => Str::slug($request->username)]);

        $this->validate($request, [
            'username'=> ['required',
                        'unique:users,username,'.auth()->user()->id,
                        'min:3',
                        'max:20',
                        'not_in:twitter,editar-perfil'
                        ]
        ]);

        if($request->imagen)
        {
            // capturo la imagen en memoria
            $imagen = $request->file('imagen');

            // genero un uuid (identificador único) para tener un registro único de cada imagen
            $nombreImagen = Str::uuid() . "." . $imagen->extension();

            $imagenServidor = Image::make($imagen);
            $imagenServidor->fit(1000, 1000);   // corta la imagen a 1000 pixeles (la hace cuadrada)

            $imagenPath = public_path('perfiles') . '/' . $nombreImagen; // devuelve uploads/314554651198714261 por ejemplo
            $imagenServidor->save($imagenPath);
        }

        // Guardar cambios
        $usuario = User::find(auth()->user()->id);

        $usuario->username = $request->username;
        $usuario->imagen = $nombreImagen ?? auth()->user()->imagen ?? null;

        // guardo en la base de datos
        $usuario->save();

        // Redirecciono al usuario
        return redirect()->route('posts.index', $usuario->username);
    }
}
