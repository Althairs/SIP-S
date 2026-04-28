<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kepakaran extends Model
{
    protected $fillable = [
        'nama_kepakaran',
        'hierarki_level',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrderByHierarki($query)
    {
        return $query->orderBy('hierarki_level', 'asc');
    }
}
