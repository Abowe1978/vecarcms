<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        Log::info('Switching language to: ' . $locale);
        
        if (array_key_exists($locale, config('app.available_locales'))) {
            Log::info('Locale exists in config, setting session and app locale');
            session()->put('locale', $locale);
            app()->setLocale($locale);
        }
        
        return redirect()->back();
    }
} 