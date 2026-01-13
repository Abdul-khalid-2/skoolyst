<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LanguageSwitcher extends Component
{
    public $currentLocale;
    public $locales;

    public function __construct()
    {
        $this->currentLocale = app()->getLocale();
        $this->locales = config('laravellocalization.supportedLocales');
    }

    public function render()
    {
        return view('components.language-switcher');
    }
}
