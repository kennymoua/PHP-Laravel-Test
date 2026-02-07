<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ToolCheckoutResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tool' => [
                'id' => $this->tool->id,
                'name' => $this->tool->name,
                'status' => $this->tool->status,
            ],
            'checked_out_at' => optional($this->checked_out_at)->toIso8601String(),
            'checked_in_at' => optional($this->checked_in_at)->toIso8601String(),
        ];
    }
}
