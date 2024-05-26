<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;

class SavedPostIndex extends Component
{
    public $posts;

    public function mount() {
        $this->posts = auth()->user()->savedPosts;
    }

    public function deleteSavedPost(Post $post) {
        $userSavePost = request()->user()->savedPosts()->where('post_id',$post->id)->first();
        if($userSavePost) {
            $userSavePost->delete();
            $this->mount();
        }else {
            // Somethin wen't wrong
            dd('Somethin wen\'t wrong');
        }
    }

    public function render()
    {
        return view('livewire.saved-post-index');
    }
}
