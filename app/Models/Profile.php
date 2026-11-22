<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'profession', 'biography', 'biography_id', 'biography_en', 'photo', 'email', 'whatsapp', 'location',
    ];

    protected $appends = ['photo_url'];

    public function getPhotoUrlAttribute(): string
    {
        if (! $this->photo) {
            return asset('images/placeholders/profile.svg');
        }

        return str_starts_with($this->photo, 'images/')
            ? asset($this->photo)
            : Storage::disk('public')->url($this->photo);
    }
}
