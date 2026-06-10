<?php

namespace App\Models;

use App\Enums\AttendanceLocation;
use App\Enums\AttendanceScanMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'enrollment_id',
        'event_date',
        'scanned_at',
        'scanned_by_user_id',
        'location',
        'scan_method',
        'scanner_ip',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'scanned_at' => 'datetime',
            'location' => AttendanceLocation::class,
            'scan_method' => AttendanceScanMethod::class,
        ];
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function scannedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scanned_by_user_id');
    }
}
