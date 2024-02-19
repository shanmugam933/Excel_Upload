<?php

namespace App\Jobs;
use Illuminate\Http\Request;
use App\Imports\SalesImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ImportSalesData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function handle()
    {
        try {
            Excel::import(new SalesImport(), $this->path);
        } catch (Exception $e) {
           
            \Log::error('Error importing sales data: ' . $e->getMessage());
            $this->fail($e);
        }

    }
}
