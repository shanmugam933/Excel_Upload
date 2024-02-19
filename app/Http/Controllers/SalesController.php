<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Jobs\ImportSalesData;
use App\Imports\SalesImport;
use Maatwebsite\Excel\Facades\Excel;

class SalesController extends Controller
{
    public function SalesInvoiceImport(Request $request)
    {
        if ($request->hasFile('excel_file')) {
            $path = $request->file('excel_file')->store('temp');
            ImportSalesData::dispatch($path);
            return response()->json(['message' => 'Import process has been dispatched']);
        } else {
            return response()->json(['error' => 'No file uploaded'], 400);
        }
    }
}
