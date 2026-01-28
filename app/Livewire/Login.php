<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email;
    public $password;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            return redirect()->intended('/dashboard/families');
        }

        $this->addError('email', 'بيانات الاعتماد غير صحيحة.');
    }

    public function render()
    {
        return view('livewire.login')->layout('layouts.app');
    }
}
