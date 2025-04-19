<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassengerDetails extends Model
{
    use HasFactory;

    protected $table = 'passengers';

    protected $primaryKey = 'id';

    // Disable auto-increment if you're handling it manually (not needed in this case)
    public $incrementing = true;

    protected $fillable = [
        'customer_id',
        'passengers',
        'first_name',
        'last_name',
        'mobile_number',
        'email',
        'gender',
        'dob',
        'anniversary',
        'pan_number',
        'passport_number',
        'passport_issue_city',
        'passport_issue_country',
        'passport_issue_date',
        'passport_expiry_date',
        'passport_front',
        'passport_back',
        'pan_card'
    ];

    public $timestamps = true;

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
