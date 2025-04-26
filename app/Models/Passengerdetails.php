<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassengerDetails extends Model
{
    use HasFactory;

    protected $table = 'passengers';
    // PassengerDetails.php
protected $fillable = [
    'customer_id', 'passengers','first_name', 'last_name', 'mobile_number', 'email', 'gender', 'dob', 'anniversary',
    'pan_number', 'passport_number', 'passport_issue_city', 'passport_issue_country',
    'passport_issue_date', 'passport_expiry_date', 'passport_front', 'passport_back', 'pan_card'
];

    
    public function customer()
    {
        return $this->belongsTo(CustomerDetails::class, 'custome_id', 'id');
    }

}
