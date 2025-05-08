<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Tab;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MenuController extends Controller
{
    public function index(Request $request)
    {

        // Set the timezone to Thailand ( UTC : 7)
        Carbon::setLocale('th');
        date_default_timezone_set('Asia/Bangkok');

        // verify the time
        $now = Carbon::now()->format('H:i');

        $tabId = $request->get('tab');

        $tabs = Tab::all();

        $menuItemsQuery = MenuItem::with(['category', 'tab'])
        ->when($tabId, fn($query) => $query->where('tab_id', $tabId));

        $menuItems = $menuItemsQuery->get()->map(function($item) {
            // Ensure consistent time format for available_from and available_until
            if ($item->available_from) {
                $item->available_from = Carbon::parse($item->available_from)->format('H:i');
            }
            
            if ($item->available_until) {
                $item->available_until = Carbon::parse($item->available_until)->format('H:i');
            }
            
            return $item;
        });

        return inertia('Menu/Index', [
            'tabs' => $tabs,
            'menuItems' => $menuItems,
            'currentTime' => $now,
        ]);
    }
}
