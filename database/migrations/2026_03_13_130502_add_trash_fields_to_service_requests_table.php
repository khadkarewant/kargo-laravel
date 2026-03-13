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
        Schema::table('service_requests', function (Blueprint $table) {
            $table->boolean('is_trashed')->default(false)->after('tracking_status');
            $table->timestamp('trashed_at')->nullable()->after('is_trashed');
            $table->foreignId('trashed_by')
                ->nullable()
                ->after('trashed_at')
                ->constrained('users')
                ->nullOnDelete();
            $table->text('trash_reason')->nullable()->after('trashed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('trashed_by');
            $table->dropColumn([
                'is_trashed',
                'trashed_at',
                'trash_reason',
            ]);
        });
    }
};