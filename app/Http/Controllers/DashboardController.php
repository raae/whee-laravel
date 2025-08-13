<?php

namespace App\Http\Controllers;

use App\Services\AirtableService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $airtableService;

    public function __construct(AirtableService $airtableService)
    {
        $this->airtableService = $airtableService;
    }

    public function index(Request $request)
    {
        $airtableUserId = $request->user()->airtable_id;
        $airtableUser = $this->airtableService->getUser($airtableUserId);


        $profile = [];
        $subscriptions = [];

        if ($airtableUser) {
            $profile = [
                "name" => $airtableUser["fields"]["name"][0],
                "email" => $airtableUser["fields"]["email"][0],
                "phone" => $airtableUser["fields"]["phone"][0],
            ];

           $subscriptions[0] = [
            "name" => $airtableUser["fields"]["bikeSubscriptionName"][0],
            "startDate" => $airtableUser["fields"]["latestHandoutDate"],
            "price" => $airtableUser["fields"]["bikeSubscriptionPrice"][0],
            "bikeName" => $airtableUser["fields"]["bikeTypeShort"][0],
           ];
        }

        return view('dashboard', compact('subscriptions', 'profile'));
    }
}