<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SaleRecord;
use Carbon\Carbon;
use DataTables;

class DataController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function fetchSalesData()
    {
        $query = SaleRecord::select([
            'region',
            'item_type',
            'order_date',
            'order_id',
            'units_sold',
            'unit_price',
            'total_cost',
            'total_profit',
        ]);

        return DataTables::of($query)
            ->addColumn('formatted_order_date', function ($record) {
                // Format 'order_date' as needed
                return Carbon::parse($record->order_date)->format('m/d/Y');
            })
            ->make(true);
    }
}
