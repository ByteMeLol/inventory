<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class LanguageController extends Controller
{
    /**
     * Switch active application language (English or Kiswahili).
     */
    public function switch(string $locale): RedirectResponse
    {
        if (in_array($locale, ['en', 'sw'], true)) {
            session(['locale' => $locale]);
        }

        return redirect()->back(fallback: '/');
    }
}
