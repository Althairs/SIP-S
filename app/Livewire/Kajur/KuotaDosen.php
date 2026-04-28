<?php

namespace App\Livewire\Kajur;

use Livewire\Component;

class KuotaDosen extends Component
{
    public function render()
    {
        return view('livewire.kajur.kuota-dosen')->layout('components.layouts.app-auth');
    }
}
