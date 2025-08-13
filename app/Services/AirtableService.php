<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class AirtableService
{
    private $baseUrl = 'https://api.airtable.com/v0';
    private $baseId;
    private $headers;


    public function __construct()
    {
        $this->baseId = config('services.airtable.base_id');
        $this->headers = [
            'Authorization' => 'Bearer '.config('services.airtable.api_key'),
        ];
    }

    protected function makeRequest($tableName = '', $query = [])
    {
        $url = "{$this->baseUrl}/{$this->baseId}/{$tableName}/";
        if (isset($query['recordId'])) {
            $url = "{$this->baseUrl}/{$this->baseId}/{$tableName}/{$query['recordId']}";
            unset($query['recordId']);
        }
        $response = Http::withHeaders($this->headers)->get($url, $query);

        // dd($query);
        // dd($url);

        dd($response->json());


        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Airtable API request failed: '.$response->body());
    }

    public function getUser($airtableUserId)
    {
        $cacheKey = 'airtable_user_'.md5($airtableUserId);

        return Cache::remember($cacheKey, 300, function () use ($airtableUserId) {
            $response = $this->makeRequest('customers', [
                'recordId' => $airtableUserId,
            ]);

            return $response['records'][0] ?? null;
        });
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

    public function getBikes($bikeIds)
    {
        $cacheKey = 'airtable_bikes_'.md5(implode(',', $bikeIds));

        return Cache::remember($cacheKey, 300, function () use ($bikeIds) {
            $response = $this->makeRequest('bikes', [
                'filterByFormula' => "({id} IN ('".implode("','", $bikeIds)."'))",
            ]);
        });
    }

    // public function getUserBikes($customerId)
    // {
    //     $cacheKey = 'airtable_user_bikes_'.md5($customerId);

    //     return Cache::remember($cacheKey, 300, function () use ($customerId) {
    //         $response = $this->makeRequest('bikes', [
    //             'filterByFormula' => "({customerId} = '{$customerId}')",
    //         ]);

    //         return $response['records'] ?? [];
    //     });
    // }
}
