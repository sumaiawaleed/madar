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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('number');
            $table->string('status', 20)->default('sent');
            $table->date('issued_on');
            $table->date('due_on')->nullable();
            $table->date('paid_on')->nullable();
            $table->boolean('has_vat')->default(true);
            $table->json('items');
            $table->timestamps();

            $table->unique(['user_id', 'number']);
            $table->index(['user_id', 'status', 'paid_on']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
