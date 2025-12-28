<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Select extends Component
{
    public $variant;
    public $block;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($variant = '', $block = false)
    {
        $this->variant = $variant;
        $this->block = $block;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form.select');
    }

    public function selectClasses()
    {
        $classes = 'w-full py-2 px-4 text-md rounded-md border outline-none ';
        if ($this->variant == '') {
            $classes .= 'border-gray-light bg-white text-black';
        } else {
            $classes .= 'text-white border-' . $this->variant . ' bg-' . $this->variant;
        }

        return $classes;
    }
}
