<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    /**
     * Fillable attributes for mass assignment.
     */
    protected $fillable = [
        'user_name',
        'platform',
        'note',
        'created_at',
        'last_check_date',
        'times_of_check',
        'status',
        'created',
        'image',
        'is_active',
    ];

    /**
     * Accessor for image URL.
     */
    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): ?string
    {
        return $this->attributes['image'] 
            ? asset("storage/{$this->attributes['image']}") 
            : null;
    }

    /**
     * Relationship with logs.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(\App\Models\Log::class, 'by_user_id');
    }
}
