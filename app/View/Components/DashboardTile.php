<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DashboardTile extends Component
{
    public function __construct(
        public string $title,
        public string $id,
        public string $icon,
        public string $iconBg = 'bg-blue-100',
        public string $iconColor = 'text-blue-600',
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.dashboard-tile');
    }
}