<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->noActionOnDelete();
            $table->string('folio', 40)->unique();
            $table->string('confirmation_key', 64)->unique();
            $table->decimal('total', 12, 2);
            $table->string('status', 30)->default('confirmado');
            $table->timestamp('ordered_at');
            $table->timestamps();

            $table->index(['user_id', 'ordered_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
