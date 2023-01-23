<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Reserve extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'event_id',
        'email',
        'username',
        'link',
        'duration'
    ];
    protected $dates = ['deleted_at'];

     /**
     * Get the reserved that owns the event.
     */
    public function reserve()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
