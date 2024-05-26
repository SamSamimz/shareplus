<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Login extends Component
{
    public $username = '';
    public $password = '';
    protected $rules = ['username' => 'required', 'password' => 'required'];
    public function loginUser() {
        $this->validate();

        $user = User::where('email',$this->username)
                ->orwhere('username',$this->username)
                ->first();
        if(!$user || !Hash::check($this->password,$user->password)) {
            session()->flash('invalid','Invalid username or password');
            return back();
        }
        Auth::login($user);
        $this->redirect('/',navigate:true);
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.guest');
    }
}
