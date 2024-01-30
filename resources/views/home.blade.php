@extends('layouts.app')

@section('titulo')
    Página Principal
@endsection

@section('contenido')
    <x-listar-post :posts="$posts" /> {{-- Paso la variable de posts del controlador al componente --}}
@endsection
