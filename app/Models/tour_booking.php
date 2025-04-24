<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class tour_booking extends Model
{
    protected $table = 'tour_booking';

    protected $fillable = [
        'trip_id',         
        'username',
        'tour_name',
        'check_in',
        'check_out',
        'adults',
        'children',
        'inclusion',
        'exclusion',
        'notes',
        'cost',
        'hotel',
        'flight',
        'tour_image',
        'ftimage',
        'officerimage',
        'officerName',
        
    ];

    public function itinerary() {
        return $this->belongsTo(tour_booking::class);
    }
}
