<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class TrackingEvent extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'service_request_id',
        'updated_by',
        'tracking_status',
        'note',
    ];

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
