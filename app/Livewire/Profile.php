<?php

namespace App\Livewire;

use App\Models\Post;
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


    public function mount($username) {
        $this->username = $username;
        $this->user = User::where('username',$this->username)->first();
        if(!$this->user) {
            abort(404);
        }
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
        $this->closePersonalinfoEditModal();
    }

    public function profileUpdate() {
        if($this->newImage) {
            if($this->user->profile->image && file_exists(public_path('storage'.$this->user->profile->image))) {
                unlink(public_path('storage/'.$this->user->profile->image));
            }
            $path = 'img_'.$this->username.'.'.'jpg';
            $this->image = $this->newImage->storeAs('profiles',$path,'public');
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
        $this->closeProfileEditModal();
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



    public function closeProfileEditModal() {
        $this->dispatch('closeProfileEditModal');
    }
    public function openProfileEditModal() {
        $this->dispatch('openProfileEditModal');
    }
    public function closePersonalinfoEditModal() {
        $this->dispatch('closePersonalInfoModal');
    }
    public function openPersonalinfoEditModal() {
        $this->dispatch('openPersonalinfoEditModal');
    }

    public function render()
    {
        $posts = $this->user->posts;
        return view('livewire.profile',compact('posts'))->layout('layouts.app');
    }
}
