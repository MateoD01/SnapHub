<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ImagenController extends Controller
{
    // Método para guardar las imagenes
    public function store(Request $request)
    {
        // capturo la imagen en memoria
        $imagen = $request->file('file');

        // genero un uuid (identificador único) para tener un registro único de cada imagen
        $nombreImagen = Str::uuid() . "." . $imagen->extension();

        $imagenServidor = Image::make($imagen);
        $imagenServidor->fit(1000, 1000);   // corta la imagen a 1000 pixeles (la hace cuadrada)

        $imagenPath = public_path('uploads') . '/' . $nombreImagen; // devuelve uploads/314554651198714261 por ejemplo
        $imagenServidor->save($imagenPath);

        // Uso json como tecnología para comunicar el backend con el front, me crea un token cuando envío la imagen
        return response()->json(['imagen' => $nombreImagen ]);
    }
}
