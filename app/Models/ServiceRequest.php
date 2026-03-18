<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\TrackingEvent;

class ServiceRequest extends Model
{
    use HasFactory;
    
    public const STATUS_REQUEST = 'request';
    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REVISION_REQUIRED = 'revision_required';

    public const STATUSES = [
        self::STATUS_REQUEST,
        self::STATUS_PENDING,
        self::STATUS_COMPLETED,
        self::STATUS_APPROVED,
        self::STATUS_REVISION_REQUIRED,
    ];

    public const TRACKING_WAREHOUSE = 'warehouse';
    public const TRACKING_CUSTOMS = 'customs';
    public const TRACKING_OFFICE = 'office';
    public const TRACKING_ROUTE = 'route';
    public const TRACKING_DESTINATION = 'destination';

    public const TRACKING_STATUSES = [
        self::TRACKING_WAREHOUSE,
        self::TRACKING_CUSTOMS,
        self::TRACKING_OFFICE,
        self::TRACKING_ROUTE,
        self::TRACKING_DESTINATION,
    ];

    public const SERVICE_CLEARANCE = 'clearance';
    public const SERVICE_IMPORT = 'import';
    public const SERVICE_COURIER = 'courier';
    public const SERVICE_EXPORT = 'export';

    public const SERVICE_TYPES = [
        self::SERVICE_CLEARANCE,
        self::SERVICE_IMPORT,
        self::SERVICE_COURIER,
        self::SERVICE_EXPORT,
    ];

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

    protected $fillable = [
        'user_id',
        'service_type',
        'sender_name',
        'sender_country',
        'sender_contact',
        'receiver_name',
        'receiver_country',
        'receiver_contact',
        'notes',
        'quantity',
        'product_detail',
        'weight',
        'dimension',
        'employee_note',
        'manager_note',
        'tracking_id',
        'status',
        'tracking_status',
        'processed_by',
        'processed_at',
        'is_trashed',
        'trashed_at',
        'trashed_by',
        'trash_reason',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
        'trashed_at' => 'datetime',
        'is_trashed' => 'boolean',
    ];

    public function trashedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trashed_by');
    }

    public function isTrashed(): bool
    {
        return (bool) $this->is_trashed;
    }

    public function canBeWorkedOn(): bool
    {
        return ! $this->isTrashed();
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function trackingEvents(): HasMany
    {
        return $this->hasMany(TrackingEvent::class)->orderBy('created_at', 'asc');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function canEmployeeUpdateStatus(): bool
    {
        return in_array($this->status, [
            self::STATUS_REQUEST,
            self::STATUS_PENDING,
            self::STATUS_REVISION_REQUIRED,
        ], true);
    }

    public function canEmployeeUpdateDetails(): bool
    {
        return in_array($this->status, [
            self::STATUS_REQUEST,
            self::STATUS_PENDING,
            self::STATUS_REVISION_REQUIRED,
        ], true);
    }
    public function nextEmployeeStatus(): ?string
    {
        return match ($this->status) {
            self::STATUS_REQUEST => self::STATUS_PENDING,
            self::STATUS_PENDING, self::STATUS_REVISION_REQUIRED => self::STATUS_COMPLETED,
            default => null,
        };
    }

    public function canEmployeeUpdateTrackingStatus(): bool
    {
        return $this->status === self::STATUS_APPROVED;
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
        return $this->status === self::STATUS_COMPLETED;
    }

    public function canManagerMarkRevisionRequired(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isRevisionRequired(): bool
    {
        return $this->status === self::STATUS_REVISION_REQUIRED;
    }

    public function getStatusLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->status));
    }

    public function getTrackingStatusLabelAttribute(): string
    {
        return $this->tracking_status
            ? ucwords(str_replace('_', ' ', $this->tracking_status))
            : 'Not started';
    }

    public function getServiceTypeLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->service_type));
    }

    protected static function booted(): void
    {
        static::creating(function (ServiceRequest $serviceRequest) {
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