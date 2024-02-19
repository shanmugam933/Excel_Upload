<?php

namespace App\Imports;


use DB;
use Carbon\Carbon;
use App\Models\SaleRecord;

use Illuminate\Support\Facades\Session;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;


HeadingRowFormatter::default('none');

class SalesImport implements ToModel, WithMultipleSheets, SkipsUnknownSheets, WithHeadingRow, WithChunkReading, WithBatchInserts, WithCalculatedFormulas, ShouldQueue
{

    private $totalRows;
    private $processedRows;

    public function __construct()
    {
        $this->totalRows = 0;
        $this->processedRows = 0;
    }

    public function sheets(): array
    {
        return [
            0 => $this
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        // E.g. you can log that a sheet was not found.
        info("Sheet {$sheetName} was skipped");
    }

    public function model(array $row)
    {





            return new SaleRecord([
                'region' => isset($row['region']) ? $row['region'] : null,
                'item_type' => isset($row['item_type']) ? $row['item_type'] : null,
                'order_date' => isset($row['order_date']) ? Carbon::createFromFormat('m/d/Y', '1/1/1900')
                                ->addDays((int)$row['order_date'] - 1)
                                ->toDateString() : null,
                'order_id' => isset($row['order_id']) ? $row['order_id'] : null,
                'units_sold' => isset($row['units_sold']) ? $row['units_sold'] : null,
                'unit_price' => isset($row['unit_price']) ? $row['unit_price'] : null,
                'total_cost' => isset($row['total_cost']) ? $row['total_cost'] : null,
                'total_profit' => isset($row['total_profit']) ? $row['total_profit'] : null,
            ]);



    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }


}
