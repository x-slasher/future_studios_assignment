<?php

declare(strict_types=1);

use App\Enums\TenantStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Named to run before the users migration: users.tenant_id references tenants.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table): void {
            $table->id();
            $table->ulid()->unique();
            $table->string('name');
            $table->string('status', 20)->default(TenantStatus::Active->value);
            $table->timestamp('suspended_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
