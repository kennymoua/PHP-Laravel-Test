<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tool_checkouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_id')->constrained('tools')->cascadeOnDelete();
            $table->timestampTz('checked_out_at');
            $table->timestampTz('checked_in_at')->nullable();
            $table->timestamps();

            $table->index(['tool_id', 'checked_in_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tool_checkouts');
    }
};
