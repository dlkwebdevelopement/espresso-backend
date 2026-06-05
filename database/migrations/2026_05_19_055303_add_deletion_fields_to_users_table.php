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
            $table->boolean('deletion_requested')->default(false)->after('otp_expires_at');
            $table->text('deletion_reason')->nullable()->after('deletion_requested');
            $table->timestamp('deletion_requested_at')->nullable()->after('deletion_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['deletion_requested', 'deletion_reason', 'deletion_requested_at']);
        });
    }
};
