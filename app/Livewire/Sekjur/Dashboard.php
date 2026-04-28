<?php

namespace App\Livewire\Sekjur;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.sekjur.dashboard')->layout('components.layouts.app-auth');
    }
}
