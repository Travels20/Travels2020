<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tour_booking extends Model
{
    protected $fillable = [
        'tripId',         
        'userName',
        'tourName',
        'checkIn',
        'checkOut',
        'numAdults',
        'numChildren',
        'inclusion',
        'exclusion',
        'notes',
        'cost',
        'hotel',
        'flight',
        'timages',
        'flightimages',
        'officerimage',
        'officerName',
        
    ];

    public function itinerary() {
        return $this->belongsTo(tour_booking::class);
    }
}
