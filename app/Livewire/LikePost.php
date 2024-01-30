<?php

namespace App\Livewire;

use Livewire\Component;

class LikePost extends Component
{
    // Atributos
    public $post;
    public $isLiked;
    public $likes;

    // Función constructora, se ejecuta automáticamente cuando se presiona el botón Like
    public function mount($post)
    {
        $this->isLiked = $post->checkLike(auth()->user());
        $this->likes = $post->likes->count();
    }

    // Función para manejar la lógica de los likes
    public function like()
    {
        if( $this->post->checkLike( auth()->user() ))
        {
            $this->post->likes()->where('post_id', $this->post->id)->delete();
            $this->isLiked = false; // se coloca en falso porque el usuario no dió me gusta
            $this->likes--;
        } else {
            $this->post->likes()->create([
                'user_id' => auth()->user()->id
            ]);
            $this->isLiked = true; // se coloca en verdadero porque el usuario dió me gusta
            $this->likes++;
        }
    }

    public function render()
    {
        return view('livewire.like-post');
    }
}
