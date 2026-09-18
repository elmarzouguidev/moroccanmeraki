<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('two_factor_trusted_devices', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('authenticatable_type');
            $table->unsignedBigInteger('authenticatable_id');
            $table->char('token_hash', 64)->unique();
            $table->timestamp('expires_at')->index();
            $table->timestamp('last_used_at')->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->ipAddress('ip_address')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['authenticatable_type', 'authenticatable_id', 'expires_at'], 'tftd_auth_expires_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('two_factor_trusted_devices');
    }
};
