<?php

declare(strict_types=1);

use App\Enums\CustomerStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table): void {
            $table->id();
            $table->ulid()->unique();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('created_by_user_id')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 30)->nullable();
            $table->string('company_name')->nullable();
            $table->string('status', 20)->default(CustomerStatus::Active->value);
            $table->timestamps();
            $table->softDeletes();
            // NULL for deleted rows, so a deleted customer's email can be used again.
            $table->string('email_active')
                ->nullable()
                ->storedAs('CASE WHEN deleted_at IS NULL THEN email END');

            $table->unique(['tenant_id', 'email_active']);
            $table->index(['tenant_id', 'status', 'created_at']);
            $table->index(['tenant_id', 'created_at']);
            $table->index(['tenant_id', 'name']);
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('created_by_user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
