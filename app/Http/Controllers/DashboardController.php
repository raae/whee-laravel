<?php

namespace App\Http\Controllers;

use App\Services\AirtableService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $airtableService;

    public function __construct(AirtableService $airtableService)
    {
        $this->airtableService = $airtableService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $userBikes = [];

        // Get user's bikes from Airtable if user has an Airtable ID
        if ($user && $user->airtable_id) {
            $userBikes = $this->airtableService->getUserBikes($user->airtable_id);
        }

        return view('dashboard', compact('userBikes'));
    }
}