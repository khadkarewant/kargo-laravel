<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    public const STATUS_REQUEST = 'request';
    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REVISION_REQUIRED = 'revision_required';

    public const TRACKING_WAREHOUSE = 'warehouse';
    public const TRACKING_CUSTOMS = 'customs';
    public const TRACKING_OFFICE = 'office';
    public const TRACKING_ROUTE = 'route';
    public const TRACKING_DESTINATION = 'destination';

    public const SERVICE_CLEARANCE = 'clearance';
    public const SERVICE_IMPORT = 'import';
    public const SERVICE_COURIER = 'courier';
    public const SERVICE_EXPORT = 'export';

    public const ACTION_REQUEST_APPROVED = 'request_approved';
    public const ACTION_REVISION_REQUIRED = 'revision_required';

    public const IN_FLOW_SERVICE_TYPES = [
    self::SERVICE_CLEARANCE,
    self::SERVICE_IMPORT,
    ];

    public const OUT_FLOW_SERVICE_TYPES = [
        self::SERVICE_COURIER,
        self::SERVICE_EXPORT,
    ];

    public function canEmployeeUpdateStatus(): bool
    {
        return in_array($this->status, [
            self::STATUS_REQUEST,
            self::STATUS_PENDING,
        ], true);
    }

    public function nextEmployeeStatus(): ?string
    {
        return match ($this->status) {
            self::STATUS_REQUEST => self::STATUS_PENDING,
            self::STATUS_PENDING => self::STATUS_COMPLETED,
            default => null,
        };
    }

    public function canEmployeeUpdateTrackingStatus(): bool
    {
        return in_array($this->status, [
            self::STATUS_APPROVED,
            self::STATUS_REVISION_REQUIRED,
        ], true);
    }

    public function nextTrackingStatus(): ?string
    {
        if (! $this->canEmployeeUpdateTrackingStatus()) {
            return null;
        }

        if (in_array($this->service_type, self::IN_FLOW_SERVICE_TYPES, true)) {
            return match ($this->tracking_status) {
                null => self::TRACKING_WAREHOUSE,
                self::TRACKING_WAREHOUSE => self::TRACKING_CUSTOMS,
                self::TRACKING_CUSTOMS => self::TRACKING_OFFICE,
                self::TRACKING_OFFICE => self::TRACKING_DESTINATION,
                default => null,
            };
        }

        if (in_array($this->service_type, self::OUT_FLOW_SERVICE_TYPES, true)) {
            return match ($this->tracking_status) {
                null => self::TRACKING_OFFICE,
                self::TRACKING_OFFICE => self::TRACKING_CUSTOMS,
                self::TRACKING_CUSTOMS => self::TRACKING_ROUTE,
                self::TRACKING_ROUTE => self::TRACKING_DESTINATION,
                default => null,
            };
        }
        return null;
    }

    public function canMoveToTrackingStatus(string $newStatus): bool
    {
        return $this->nextTrackingStatus() === $newStatus;
    }

    public function canManagerApprove(): bool
    {
        return in_array($this->status, [
            self::STATUS_COMPLETED,
            self::STATUS_REVISION_REQUIRED,
        ], true); 
    }

    public function canManagerMarkRevisionRequired(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRevisionRequired(): bool
    {
        return $this->status === self::STATUS_REVISION_REQUIRED;
    }



    protected $fillable = [
        'service_type',
        'sender_name',
        'receiver_name',
        'status',
        'tracking_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trackingEvents()
    {
        return $this->hasMany(TrackingEvent::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
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