<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class AirtableService
{
    protected $baseId;

    protected $apiKey;

    public function __construct()
    {
        $this->baseId = config('services.airtable.base_id');
        $this->apiKey = config('services.airtable.api_key');
    }

    protected function makeRequest($tableName = '', $params = [])
    {
        $url = "https://api.airtable.com/v0/{$this->baseId}/{$tableName}/";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->apiKey,
        ])->get($url, $params);

        dd($response->json());

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Airtable API request failed: '.$response->body());
    }

    public function getUserByPhone($phone)
    {
        $cacheKey = 'airtable_user_phone_'.md5($phone);

        return Cache::remember($cacheKey, 300, function () use ($phone) {
            $response = $this->makeRequest('customers', [
                'filterByFormula' => "({phoneNumber} = '{$phone}')",
                'maxRecords' => 1,
            ]);

            return $response['records'][0] ?? null;
        });
    }

    public function getUserBikes($customerId)
    {
        $cacheKey = 'airtable_user_bikes_'.md5($customerId);

        return Cache::remember($cacheKey, 300, function () use ($customerId) {
            $response = $this->makeRequest('bikes', [
                'filterByFormula' => "FIND('{$customerId}', ARRAYJOIN({customer}, ','))",
            ]);

            return $response['records'] ?? [];
        });
    }
}
