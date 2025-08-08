<?php

// Creating a PHP wrapper class around the API calls simplifies interaction and testing.
// CallingAllPapers service class:
// link to video https://laracasts.com/series/lets-build-a-saas-in-laravel/episodes/10
namespace App\Services;

use Illuminate\Support\Facades\Http;

class CallingAllPapers
{
    protected string $baseUrl = 'https://api.callingallpapers.com/v1/';

    public function getCFPs(): array
    {
        return Http::get($this->baseUrl . 'cfp')->json();
    }

    
}

