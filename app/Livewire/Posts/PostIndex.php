<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use App\Models\SavedPost;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\WithFileUploads;

class PostIndex extends Component
{
    use WithFileUploads;
    public $user;
    public $caption;
    public $image;
    public $feeling;
    public $savedPosts = [];

    public function mount() {
        $this->user = auth()->user();
        $this->loadSavedPosts();

    }

    private function loadSavedPosts()
    {
        $user = request()->user();
        $this->savedPosts = $user->savedPosts()->pluck('post_id')->toArray();
    }


    protected $rules = [
        'caption' => 'required|string|min:3|max:50',
        'image' => 'nullable|image|max:2048',
        'feeling' => 'nullable|in:sad,angry,happy,thankfull,blessed,excited',
    ];

    public function addPost() 
    {
        $this->validate();
        if($this->image) {
            $path = $this->user->username.time().'.'.$this->image->getClientOriginalExtension();
            $this->image  = $this->image->storeAs('posts',$path,'public');
        }
        $this->user->posts()->create([
            'caption' => $this->caption,
            'image' => $this->image,
            'feeling' => $this->feeling,
        ]);
        $this->reset();
        $this->closePostModal();
        $this->mount();
    }

    public function savePost($id)
    {
        if (in_array($id, $this->savedPosts)) {
            $this->user->savedPosts()->where('post_id', $id)->delete();
            $this->savedPosts = array_diff($this->savedPosts, [$id]);
        } else {
            $this->user->savedPosts()->create(['post_id' => $id]);
            $this->savedPosts[] = $id;
        }
    }

    public function saved(Post $post): bool
    {
        $user = request()->user();
        $savedPosts = $user->savedPosts->pluck('post_id')->toArray();
        return in_array($post->id, $savedPosts);
    }
    

    public function likePost(Post $post)
    {
        $existingLike = $post->likes()->where('user_id', $this->user->id)->first();

        if ($existingLike) {
            $existingLike->delete();
        } else {
            $post->likes()->create(['user_id' => $this->user->id]);
        }
    }

    public function likedBy(Post $post) {
        if($post->likes()->where('user_id',request()->user()->id)->exists()) {
            return true;
        }else{
            return false;
        }
    }
    
    public function resetData() 
    {
        $this->reset();
    }

    public function showFeeling($val) 
    {
        return Post::getFeeling($val);
    }

    #[Computed]
    public function posts() {
        return Post::with('likes','comments','user')->latest()->get();
    }

    public function render()
    {
        $posts = $this->posts;
        return view('livewire.posts.post-index',compact('posts'));
    }
}