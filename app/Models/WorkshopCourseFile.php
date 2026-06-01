<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkshopCourseFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'workshop_id',
        'uploaded_by',
        'title',
        'description',
        'file_path',
        'original_filename',
        'mime_type',
        'file_size_bytes',
        'is_published',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'file_size_bytes' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function workshop(): BelongsTo
    {
        return $this->belongsTo(Workshop::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size_bytes;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2).' Go';
        }

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' Mo';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2).' Ko';
        }

        return $bytes.' octets';
    }
}
