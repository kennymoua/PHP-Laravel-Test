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
     * 
     * This scope acts as a method to be resued anywhere in the codebase - ken  
     * Example usage: ToolCheckout::currentlyCheckedOut()->get(); 
     */
    public function scopeCurrentlyCheckedOut($query)
    {
        // Intentionally wrong to make tests fail until implemented.
        // Fix: Changed WhereNotNull to WhereNull to return checkouts that are still active (not yet checked in) - ken
        return $query->whereNull('checked_in_at');
    }

    // Helper method to check if the checkout is still active (not yet checked in)
    // If the checeked_in_at field is null, it means the tool is still checked out and not yet returned - ken
    public function isActive(): bool
    {
        return $this->checked_in_at === null;
    }
}
