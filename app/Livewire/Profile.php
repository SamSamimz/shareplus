<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\SavedPost;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;
    // user profile information
    public $image;
    public $newImage;
    public $bio = '';
    public $address = '';
    public $work_at = '';
    public $birthdate = '';
    public $facebook = '';
    public $github = '';
    public $linkedin = '';
    public $username;
    public $name;
    public $email;
    public $user;
    public $savedPosts = [];

    public function mount($username) 
    {
        $this->username = $username;
        $this->user = User::with('profile','posts.likes','posts.comments')->where('username', $this->username)->firstOrFail();
        
        $this->image = $this->user->profile->image;
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->bio = $this->user->profile->bio;
        $this->address = $this->user->profile->address;
        $this->work_at = $this->user->profile->work_at;
        $this->birthdate = $this->user->profile->birthdate;
        $this->facebook = $this->user->profile->facebook;
        $this->github = $this->user->profile->github;
        $this->linkedin = $this->user->profile->linkedin;
        $this->loadSavedPosts();
    }

    
    private function loadSavedPosts()
    {
        $user = auth()->user();
        $this->savedPosts = $user->savedPosts()->pluck('post_id')->toArray();
    }

    protected $rules = [
        'username' => 'required',
        'name' => 'required',
        'email' => 'required|email',
    ];
    public function personalnfoUpdate() {
        $this->validate();
        $this->user->update([
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
        ]);
        $this->dispatch('closeProfileEditModal');
        $this->dispatch('closePersonalinfoEditModal');
    }

    public function profileUpdate()
    {
        $this->validate([
            'bio' => 'nullable|string',
            'address' => 'nullable|string',
            'work_at' => 'nullable|string',
            'birthdate' => 'nullable|date',
            'facebook' => 'nullable|url',
            'github' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'newImage' => 'nullable|image|max:1024',
        ]);
    
        if ($this->newImage) {
            if ($this->user->profile->image && file_exists(public_path('storage/' . $this->user->profile->image))) {
                unlink(public_path('storage/' . $this->user->profile->image));
            }
    
            $path = 'profiles/img_' . $this->username . '.jpg';
            $this->image = $this->newImage->storeAs('profiles', $path, 'public');
        }
    
        $this->user->profile->update([
            'image' => $this->image,
            'bio' => $this->bio,
            'address' => $this->address,
            'work_at' => $this->work_at,
            'birthdate' => $this->birthdate,
            'facebook' => $this->facebook,
            'github' => $this->github,
            'linkedin' => $this->linkedin,
        ]);
    
        $this->dispatch('closeProfileEditModal');
        $this->dispatch('closePersonalinfoEditModal');
        
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
        return SavedPost::with('user','post')->where('user_id',$this->user->id)->where('post_id', $post->id)->exists();
    }
    
    public function likePost(Post $post) 
    {
        $like = $post->likes()->where('user_id', $this->user->id)->first();

        if ($like) {
            $like->delete();
        } else {
            $post->likes()->create(['user_id' => $this->user->id]);
        }
    }


    public function likedBy(Post $post): bool
    {
        return $post->likes()->where('user_id', $this->user->id)->exists();
    }


    public function showFeeling($val) 
    {
        return Post::getFeeling($val);
    }
    

    public function render()
    {
        $posts = $this->user->posts;
        return view('livewire.profile',compact('posts'))->layout('layouts.app');
    }
}