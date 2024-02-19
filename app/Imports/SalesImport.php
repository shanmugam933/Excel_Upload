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

class SalesImport implements ToModel, WithMultipleSheets, SkipsUnknownSheets, WithChunkReading, WithBatchInserts, WithCalculatedFormulas, ShouldQueue
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
            'region' => isset($row[0]) ? $row[0] : null,
            'item_type' => isset($row[1]) ? $row[1] : null,
            'order_date' => isset($row[2]) ? Carbon::createFromFormat('m/d/Y', '1/1/1900')
                            ->addDays((int)$row[2] - 1)
                            ->toDateString() : null,
            'order_id' => isset($row[3]) ? $row[3] : null,
            'units_sold' => isset($row[4]) ? $row[4] : null,
            'unit_price' => isset($row[5]) ? $row[5] : null,
            'total_cost' => isset($row[6]) ? $row[6] : null,
            'total_profit' => isset($row[7]) ? $row[7] : null,
        ]);
    }

    public function chunkSize(): int
    {
        return 2500;
    }

    public function batchSize(): int
    {
        return 2500;
    }
}
