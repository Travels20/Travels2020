<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VacationSummary extends Model
{
    protected $table = 'vacation_summary';
    
    protected $fillable = [
        'fk_tour_booking',
        'stay',
        'date',
        'itinerary',
        'image',
    ];

    public function days() {
        return $this->hasMany(vacation_summary::class);
    }
}
