<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowerController extends Controller
{
    // Almacena el usuario que da click en "Seguir"
    public function store(User $user, Request $request)
    {
        $user->followers()->attach( auth()->user()->id );
        return back();
    }

    // Elimina a la persona, la deja de seguir.
    public function destroy(User $user, Request $request)
    {
        $user->followers()->detach( auth()->user()->id );
        return back();
    }
}
