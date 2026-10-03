<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_exports', function (Blueprint $table): void {
            $table->id();
            $table->ulid()->unique();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('requested_by_user_id')->nullable();
            $table->string('status', 20);
            $table->json('filters')->nullable();
            $table->string('file_path')->nullable();
            $table->unsignedInteger('row_count')->nullable();
            $table->string('error_message')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'created_at']);
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('requested_by_user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_exports');
    }
};
