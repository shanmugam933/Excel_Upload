<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ImportSalesData;
use App\Imports\SalesImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SalesController extends Controller
{
    public function SalesInvoiceImport(Request $request)
    {
        $file = $request->file('excel_file');

        if ($file && file_exists($file) && is_readable($file)) {
            $filename = 'exceldata_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('Uploaded_Excel_Files', $filename);

            $splitSize = 5000;
            $output = str_ireplace('.csv', '', $filename);
            $inputFilePath = storage_path('app/Uploaded_Excel_Files/' . $filename);
            $outputFilesPath = storage_path('app/Uploaded_Excel_Files/' . $output . '_*.csv');

            $in = fopen($inputFilePath, 'r');
            $rowCount = 0;
            $fileCount = 1;

            while (($data = fgetcsv($in)) !== false && $data) {
                if (($rowCount % $splitSize) == 0) {
                    if ($rowCount > 0) {
                        fclose($out);
                    }
                    $outFilename = storage_path('app/Uploaded_Excel_Files/' . $output . '_' . str_pad($fileCount++, 3, '0', STR_PAD_LEFT) . '.csv');
                    $out = fopen($outFilename, 'w');
                }
                fputcsv($out, $data);
                $rowCount++;
            }

            if (isset($out)) {
                fclose($out);
            }

            fclose($in);

            if(substr(PHP_OS, 0, 3) != 'WIN') {
                $this->changeFolderPermissions(storage_path('app/Uploaded_Excel_Files'), 0777);
            }

            foreach(glob($outputFilesPath) as $file) {
                ImportSalesData::dispatch($file);
            }

            return response()->json(["message" => "Importing In-progress"]);
        } else {
            return response()->json(['error' => 'The file does not exist or is not readable'], 400);
        }
    }

    public function changeFolderPermissions($path, $permissions)
    {
        if (file_exists($path) || is_dir($path)) {
            chmod($path, $permissions);
            $items = scandir($path);

            foreach ($items as $item) {
                if ($item != '.' && $item != '..') {
                    $itemPath = $path . '/' . $item;
                    if (is_dir($itemPath)) {
                        $this->changeFolderPermissions($itemPath, $permissions);
                    } else {
                        chmod($itemPath, $permissions);
                    }
                }
            }
        }
    }
}
