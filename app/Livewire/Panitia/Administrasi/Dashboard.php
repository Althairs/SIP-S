<?php

namespace App\Livewire\Panitia\Administrasi;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.panitia.administrasi.dashboard')->layout('components.layouts.app-auth');
    }
}
