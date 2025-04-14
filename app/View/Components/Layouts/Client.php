<?php

namespace App\View\Components\Layouts;

use Illuminate\View\Component;

class Client extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public ?string $title = null)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('layouts.client');
    }
} 