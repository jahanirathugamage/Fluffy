<?php

namespace App\Livewire\Fluffy;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class HamburgerMenu extends Component
{
    public $isOpen = false;
    public $currentLevel = 'main'; // main, otherPets, cats, dogs, rabbit, hamster
    public $animalContext = null; // Current animal name for level 3

    protected $listeners = ['openHamburgerMenu' => 'open'];

    public function open()
    {
        $this->isOpen = true;
        $this->currentLevel = 'main'; // Reset to main when opening
    }

    public function close()
    {
        $this->isOpen = false;
        $this->currentLevel = 'main'; // Reset to main when closing
        $this->animalContext = null;
    }

    public function navigateTo($level, $animal = null)
    {
        $this->currentLevel = $level;
        $this->animalContext = $animal;
    }

    public function goBack()
    {
        // Navigate back based on current level
        if (in_array($this->currentLevel, ['cats', 'dogs'])) {
            $this->currentLevel = 'main';
            $this->animalContext = null;
        } elseif (in_array($this->currentLevel, ['rabbit', 'hamster'])) {
            $this->currentLevel = 'otherPets';
            $this->animalContext = null;
        } elseif ($this->currentLevel === 'otherPets') {
            $this->currentLevel = 'main';
            $this->animalContext = null;
        }
    }

    public function goToProducts($animal, $category = null)
    {
        $params = ['animal' => $animal];
        if ($category) {
            $params['category'] = $category;
        }
        $this->close();
        return redirect()->route('products.index', $params);
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.fluffy.hamburger-menu');
    }
}
