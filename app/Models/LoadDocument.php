<?php

namespace App\Models;

use App\Enums\DocumentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class LoadDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'load_id',
        'type',
        'path',
        'original_name',
        'mime_type',
        'size',
        'uploaded_by',
    ];

    protected $casts = [
        'type' => DocumentType::class,
    ];

    public function load(): BelongsTo
    {
        return $this->belongsTo(Load::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('documents')->url($this->path);
    }

    public function getSizeInMbAttribute(): string
    {
        return number_format($this->size / 1024 / 1024, 2);
    }
}
