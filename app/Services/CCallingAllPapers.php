<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CCallingAllPapers
{
    protected $baseUrl = 'https://api.callingallpapers.com/v1/';

    public function getCFPs(): array
    {
        return Http::get($this->baseUrl . 'cfp')->json();
    }

    
}

