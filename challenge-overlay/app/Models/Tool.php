<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tool extends Model
{
    use HasFactory;

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_CHECKED_OUT = 'checked_out';
    public const STATUS_MAINTENANCE = 'maintenance';

    protected $fillable = ['name', 'status'];

    public function checkouts(): HasMany
    {
        return $this->hasMany(ToolCheckout::class);
    }
}
