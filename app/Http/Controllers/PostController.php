<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    // Ejecuta el middleware de autenticación (otra persona NO PUEDE acceder al muro sin estar autenticado)
    public function __construct()
    {
        $this->middleware('auth')->except(['show', 'index']);
    }

    // Retorna la vista del muro
    public function index(User $user)
    {
        $posts = Post::where('user_id', $user->id)->latest()->paginate(5);

        return view('dashboard', [
            'user' => $user,
            'posts' => $posts
        ]);
    }

    // Nos permite tener el formulario de tipo GET para visualizarlo en la vista
    public function create()
    {
        return view('posts.create');
    }

    // Metodo que almacena la info en la base de datos
    public function store(Request $request)
    {
        $this->validate($request, [
            'titulo' => 'required|max:255',
            'descripcion' => 'required',
            'imagen' => 'required'
        ]);

        // Guardo los registros al momento de "crear una publicación", usando la relación de User y Post
        $request->user()->posts()->create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'imagen' => $request->imagen,
            'user_id' => auth()->user()->id
        ]);

        return redirect()->route('posts.index', auth()->user()->username);
    }

    // Método para mostrar una publicación en específico (el index muestra todas)
    public function show(User $user, Post $post)
    {
        return view('posts.show', [
            'post' => $post,
            'user' => $user
        ]);
    }

    // Para borrar una publicacion
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();

        // Eliminar la imagen
        $imagen_path = public_path('uploads/' . $post->imagen);

        if(File::exists($imagen_path))
        {
            unlink($imagen_path);
        }

        return redirect()->route('posts.index', auth()->user()->username);
    }
}
