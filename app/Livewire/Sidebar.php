<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\MenuService;

class Sidebar extends Component
{
    public $menus;

    public function mount(MenuService $menuService)
    {
        $this->menus = $menuService->getMenu();
    }

    public function render()
    {
        return view('livewire.sidebar');
    }
}
