<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomersDetails extends Model
{
    use HasFactory;

    protected $table = 'customers_details'; 

    // CustomerDetails.php
protected $fillable = [
    'travel_from', 'travel_to', 'destination', 'relationship', 'adults', 'children'
];
   

    public function passengers()
    {
        return $this->hasMany(PassengerDetails::class, 'custome_id', 'id');
    }
    
}
