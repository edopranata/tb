<?php

namespace App\Http\Pages\Dashboard;

use App\Models\Product;
use App\Models\PurchaseDetail;
use App\Models\SellDetail;
use App\Models\ProductStock;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DashboardIndex extends Component
{

    public function render()
    {
        $purchase = SellDetail::query();
        return view('pages.dashboard.dashboard-index', [
            'asset'     => ProductStock::query()->sum(DB::raw('available_stock * buying_price')),
            'sales'     => [
                'year'  => $purchase->whereYear('created_at', Carbon::now()->year)->sum('total'),
                'month' => $purchase->whereMonth('created_at', Carbon::now()->month)->sum('total'),
                'day'   => $purchase->whereDay('created_at', Carbon::now()->day)->sum('total'),
            ]

        ]);
    }
}
