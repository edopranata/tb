<?php

namespace App\Http\Pages\Reporting\Income;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Purchase;
use App\Models\Sell;
use Barryvdh\Debugbar\Facades\Debugbar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class IncomeReport extends Component
{
    public $report_type = 'daily';
    public $report_format = 'dd/mm/yyyy';
    public $report_day;
    public $report_month = 1;
    public $report_year = 2022;

    public $sells;
    public $inventories;
    public $assets;

    public $reports;


    public function render()
    {
        return view('pages.reporting.income.income-report');
    }

    public function updatedReportType()
    {
        $this->dispatchBrowserEvent('reportChanged');
    }

    public function viewReport()
    {
        switch ($this->report_type){
            case 'daily':
                $this->dailyReport();
                break;
            case 'monthly':
                $this->monthlyReport();
                break;
            case 'yearly':
                $this->yearlyReport();
                break;
            default:
                break;
        }
    }

    public function dailyReport()
    {

      $sells = Sell::query()
          ->select('sells.invoice_date', DB::raw('SUM(sell_details.total) As sell_price '), DB::raw('SUM(sell_details.buying_price * sell_details.product_price_quantity) As buying_price'))
          ->leftJoin('sell_details', 'sells.id', '=', 'sell_details.sell_id')
          ->where('sells.invoice_date', $this->report_day)
          ->groupBy('sells.invoice_date')
          ->get();

      $inventories = Purchase::query()
          ->select('purchases.invoice_date', DB::raw('SUM(purchase_details.total) As total '))
          ->leftJoin('purchase_details', 'purchases.id', '=', 'purchase_details.purchase_id')
          ->where('purchases.invoice_date', $this->report_day)
          ->groupBy('purchases.invoice_date')
          ->get();

      $assets = ProductStock::query()
          ->select(DB::raw('SUM(buying_price * available_stock) as total'))
          ->where('available_stock', '>' , 0)
          ->first();

      $reports = Sell::query()
          ->select('sells.invoice_number', 'sells.id', 'sells.invoice_date', DB::raw('SUM(sell_details.total) As sell_price '), DB::raw('SUM(sell_details.buying_price * sell_details.product_price_quantity) As buying_price'))
          ->leftJoin('sell_details', 'sells.id', '=', 'sell_details.sell_id')
          ->where('sells.invoice_date', $this->report_day)
          ->groupBy('sells.id')
          ->get();

      $this->reports = $reports;
      $this->sells = $sells;
      $this->inventories = $inventories;
      $this->assets = $assets;

    }

    public function monthlyReport()
    {
        $sells = Sell::query()
            ->select(DB::raw("DATE_FORMAT(sells.invoice_date,'%m %Y') as months"), DB::raw('SUM(sell_details.total) As sell_price '), DB::raw('SUM(sell_details.buying_price * sell_details.product_price_quantity) As buying_price'))
            ->leftJoin('sell_details', 'sells.id', '=', 'sell_details.sell_id')
            ->whereYear('sells.invoice_date', $this->report_year)
            ->whereMonth('sells.invoice_date', $this->report_month)
            ->groupBy('months')
            ->get();


        $inventories = Purchase::query()
            ->select(DB::raw("DATE_FORMAT(purchases.invoice_date,'%m %Y') as months"), DB::raw('SUM(purchase_details.total) As total '))
            ->leftJoin('purchase_details', 'purchases.id', '=', 'purchase_details.purchase_id')
            ->whereYear('purchases.invoice_date', $this->report_year)
            ->whereMonth('purchases.invoice_date', $this->report_month)
            ->groupBy('months')
            ->get();

        $assets = ProductStock::query()
            ->select(DB::raw('SUM(buying_price * available_stock) as total'))
            ->where('available_stock', '>' , 0)
            ->first();

        $reports = Sell::query()
            ->select('sells.invoice_number', 'sells.id', 'sells.invoice_date', DB::raw("DATE_FORMAT(sells.invoice_date,'%m %Y') as months"), DB::raw('SUM(sell_details.total) As sell_price '), DB::raw('SUM(sell_details.buying_price * sell_details.product_price_quantity) As buying_price'))
            ->leftJoin('sell_details', 'sells.id', '=', 'sell_details.sell_id')
            ->whereYear('sells.invoice_date', $this->report_year)
            ->whereMonth('sells.invoice_date', $this->report_month)
            ->groupBy('sells.id')
            ->get();

        $this->reports = $reports;
        $this->sells = $sells;
        $this->inventories = $inventories;
        $this->assets = $assets;
    }

    public function yearlyReport()
    {
        $sells = Sell::query()
            ->select(DB::raw("DATE_FORMAT(sells.invoice_date, '%Y') as years"), DB::raw('SUM(sell_details.total) As sell_price '), DB::raw('SUM(sell_details.buying_price * sell_details.product_price_quantity) As buying_price'))
            ->leftJoin('sell_details', 'sells.id', '=', 'sell_details.sell_id')
            ->whereYear('sells.invoice_date', $this->report_year)
            ->groupBy('years')
            ->get();


        $inventories = Purchase::query()
            ->select(DB::raw("DATE_FORMAT(purchases.invoice_date, '%Y') as years"), DB::raw('SUM(purchase_details.total) As total '))
            ->leftJoin('purchase_details', 'purchases.id', '=', 'purchase_details.purchase_id')
            ->whereYear('purchases.invoice_date', $this->report_year)
            ->groupBy('years')
            ->get();


        $assets = ProductStock::query()
            ->select(DB::raw('SUM(buying_price * available_stock) as total'))
            ->where('available_stock', '>' , 0)
            ->first();

        $reports = Sell::query()
            ->select( DB::raw("DATE_FORMAT(sells.invoice_date, '%m %Y') as months"), DB::raw('SUM(sell_details.total) As sell_price '), DB::raw('SUM(sell_details.buying_price * sell_details.product_price_quantity) As buying_price'))
            ->leftJoin('sell_details', 'sells.id', '=', 'sell_details.sell_id')
            ->whereYear('sells.invoice_date', $this->report_year)
            ->groupBy('months')
            ->get();

        $this->reports = $reports;
        $this->sells = $sells;
        $this->inventories = $inventories;
        $this->assets = $assets;
    }
}
