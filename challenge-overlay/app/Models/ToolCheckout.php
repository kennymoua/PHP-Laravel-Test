<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToolCheckout extends Model
{
    use HasFactory;

    protected $fillable = ['tool_id', 'checked_out_at', 'checked_in_at'];

    protected $casts = [
        'checked_out_at' => 'datetime',
        'checked_in_at' => 'datetime',
    ];

    public function tool(): BelongsTo
    {
        return $this->belongsTo(Tool::class);
    }

    /**
     * TODO: Implement this scope.
     * A checkout is currently checked out when checked_in_at IS NULL.
     */
    public function scopeCurrentlyCheckedOut($query)
    {
        // Intentionally wrong to make tests fail until implemented.
        return $query->whereNotNull('checked_in_at');
    }

    public function isActive(): bool
    {
        return $this->checked_in_at === null;
    }
}
