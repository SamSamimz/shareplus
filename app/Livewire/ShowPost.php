<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Post;
use App\Models\SavedPost;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ShowPost extends Component
{
    public $post;
    public $text;
    public $user;

    public function mount($slug) 
    {
        $this->post = Post::with('user','likes','comments.user')->where('slug', $slug)->first();
        $this->user = auth()->user();
    }

    // protected $rules = [
    //     'text' => 'requird','max:50'
    // ];
    public function addComment() 
    {
        $this->post->comments()->create([
            'user_id' => request()->user()->id,
            'text' => $this->text,
        ]);
        $this->reset('text');
    }

    #[Computed]
    public function userSavePost()
    {
        return SavedPost::where('user_id', $this->user->id)->first();

    }
    
    public function savePost(Post $post) 
    {
        if($this->userSavePost) {
            $this->userSavePost->delete();
        }else {
            request()->user()->savedPosts()->create(['post_id' => $post->id]);
        }
        unset($this->userSavePost);
    }

    public function saved(Post $post) :bool{
        if(request()->user()->savedPosts()->where('post_id',$post->id)->exists()) {
            return true;
        }
        return false;
    }

    public function likePost(Post $post) 
    {
        if(!$this->likedBy) {
            $post->likes()->create(['user_id' => request()->user()->id]);
        }else {
            $like = $post->likes()->where('user_id',request()->user()->id)->first();
            $like->delete();
        }
        unset($this->likedBy);
    }

    #[Computed]
    public function likedBy()
    {
        return $this->post->likes()->where('user_id',$this->user->id)->exists();
    }

    public function postAuthor(Post $post) :bool{
        if($post->user->id == auth()->user()->id) {
            return true;
        }else {
            return false;
        }
    }

    public function commentAuthor(Comment $comment) :bool
    {
        if($comment->user->id == auth()->user()->id) {
            return true;
        }else {
            return false;
        }
    }

    public function deleteComment(Comment $comment) 
    {
        $comment->delete();
    }


    public function showFeeling($val) 
    {
        return Post::getFeeling($val);
    }

    public function render()
    {
        $posts = $this->post;
        return view('livewire.show-post',compact('posts'));
    }
}
