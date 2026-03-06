<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    protected $fillable = [
        'service_type',
        'sender_name',
        'receiver_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::creating(function ($serviceRequest) {
            $year = now()->year;
            $lastRequest = self::whereYear('created_at', $year)->latest('id')->first();

            $nextNumber = 1;

            if ($lastRequest && $lastRequest->tracking_id) {
                $lastNumber = (int) substr($lastRequest->tracking_id, -5);
                $nextNumber = $lastNumber + 1;
            }

            $serviceRequest->tracking_id = 'KRG-' . $year . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        });
    }
}