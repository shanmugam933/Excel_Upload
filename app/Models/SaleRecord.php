<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleRecord extends Model
{
    use HasFactory;


    protected $table = 'sales_records';

    protected $fillable = [
        'region',
        'item_type',
        'order_date',
        'order_id',
        'units_sold',
        'unit_price',
        'total_cost',
        'total_profit',
    ];

}
