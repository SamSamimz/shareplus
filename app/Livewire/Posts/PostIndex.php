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

    public function mount() {
        $this->user = auth()->user();
    }

    protected $rules = [
        'caption' => 'required|string|min:3|max:50',
        'image' => 'nullable|image|max:2048',
        'feeling' => 'nullable|in:sad,angry,happy,thankfull,blessed,excited',
    ];
    public function addPost() {
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

    public function openPostModal() {
        $this->dispatch('openPostModal');
    }

    public function closePostModal() {
        $this->reset();
        $this->dispatch('closePostModal');
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