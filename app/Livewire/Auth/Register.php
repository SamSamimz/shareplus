<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\Profile;
use Livewire\Component;

class Register extends Component
{
    public $username = '';
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';

    protected $rules = [
        'username' => 'required|string|min:3',
        'name' => 'required|string|min:3',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|confirmed',
    ];

    public function registerUser() {
        $this->validate();
        $user = User::create([
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);
        $this->id = $user->id;
        Profile::create(['user_id'=>$user->id]);
        return $this->redirect('/login', navigate:true);
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.guest');
    }
}
