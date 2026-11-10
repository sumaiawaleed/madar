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
        Schema::table('users', function (Blueprint $table) {
            $table->string('business_name')->nullable()->after('name');
            $table->string('job_title')->nullable()->after('business_name');
            $table->string('phone', 30)->nullable()->after('email');
            $table->string('vat_number', 30)->nullable();
            $table->string('iban', 34)->nullable();
            $table->string('handle', 40)->nullable()->unique();
            $table->text('bio')->nullable();
            $table->string('logo_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['handle']);
            $table->dropColumn(['business_name', 'job_title', 'phone', 'vat_number', 'iban', 'handle', 'bio', 'logo_path']);
        });
    }
};
