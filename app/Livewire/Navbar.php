<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Navbar extends Component
{
    public function mount()
    {
        Log::info('Navbar component mounted.');
    }

    public function render()
    {
        return view('livewire.navbar');
    }
}
