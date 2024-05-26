<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Post;
use Livewire\Component;

class ShowPost extends Component
{
    public $post;
    public $text;

    public function mount($slug) {
        $this->post = Post::where('slug', $slug)->first();
    }

    // protected $rules = [
    //     'text' => 'requird','max:50'
    // ];
    public function addComment() {
        $this->post->comments()->create([
            'user_id' => request()->user()->id,
            'text' => $this->text,
        ]);
        $this->reset('text');
    }



    public function allLikers() {
        $this->dispatch('openModal');
    }

    public function closeModal() {
        $this->dispatch('closeModal');
    }

    
    public function savePost(Post $post) {
        $userSavePost = request()->user()->savedPosts()->where('post_id',$post->id)->first();
        if($userSavePost) {
            $userSavePost->delete();
        }else {
            request()->user()->savedPosts()->create(['post_id' => $post->id]);
        }
    }

    public function saved(Post $post) :bool{
        if(request()->user()->savedPosts()->where('post_id',$post->id)->exists()) {
            return true;
        }
        return false;
    }

    public function likePost(Post $post) {
        if(!$this->likedBy($post)) {
            $post->likes()->create(['user_id' => request()->user()->id]);
        }else {
            $like = $post->likes()->where('user_id',request()->user()->id)->first();
            $like->delete();
        }
    }

    public function likedBy(Post $post) {
        if($post->likes()->where('user_id',request()->user()->id)->exists()) {
            return true;
        }else{
            return false;
        }
    }

    public function postAuthor(Post $post) :bool{
        if($post->user->id == auth()->user()->id) {
            return true;
        }else {
            return false;
        }
    }

    public function commentAuthor(Comment $comment) :bool{
        if($comment->user->id == auth()->user()->id) {
            return true;
        }else {
            return false;
        }
    }

    public function deleteComment(Comment $comment) {
        $comment->delete();
    }


    public function getFeeling($val) {
        switch ($val) {
            case 'happy':
                return "is feeling happy😀";
                break;
            
            case 'sad':
                return "is feeling sad😥";
                break;
            
            case 'angry':
                return "is feeling angry😡";
                break;
            
            case 'thankfull':
                return "is feeling thankfull🙏";
                break;
            case 'blessed':
                return "is feeling blessed😊";
                break;
            case 'excited':
                return "is feeling excited😉";
                break;
            
            default:
                return null;
                break;
        }
    }

    public function render()
    {
        $posts = $this->post;
        return view('livewire.show-post',compact('posts'));
    }
}
