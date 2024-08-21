<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\SavedPost;
use Livewire\Component;

class SavedPostIndex extends Component
{
    public $posts;
    public $user;

    public function mount() {
        $this->user = auth()->user();
        $this->posts = SavedPost::where('user_id',$this->user->id)->get();
        
    }

    public function deleteSavedPost(Post $post) {
        $userSavePost = $this->user->savedPosts()->where('post_id',$post->id)->first();
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
