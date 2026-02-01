<?php

namespace App\Livewire\Employee;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Illuminate\Validation\Rules;

class CreateAccount extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    public function create()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', Rules\Password::defaults(), 'confirmed'],
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $user->assignRole('employee');

        session()->flash('message', 'Employee account created successfully.');

        return redirect()->route('profile.show');
    }

    public function render()
    {
        return view('livewire.employee.create-account')->layout('layouts.guest'); 
    }
}
