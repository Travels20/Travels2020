<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'customer_name',
        'customer_address',
        'customer_gst_no',
        'travel_from',
        'travel_to',
        'destination',
        'num_adults',
        'num_children',
        'adults_cost',
        'child_cost',
        'service_cost',
        'service_gst',
        'notes',
        'office_gst_no',
        'office_pan_no',
    ];
}
