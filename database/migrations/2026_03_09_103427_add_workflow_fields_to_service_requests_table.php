<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            // Customer-provided request details
            $table->string('sender_country')->nullable()->after('sender_name');
            $table->string('sender_contact')->nullable()->after('sender_country');

            $table->string('receiver_country')->nullable()->after('receiver_name');
            $table->string('receiver_contact')->nullable()->after('receiver_country');

            $table->text('notes')->nullable()->after('receiver_contact');

            // Employee-managed processing details
            $table->string('quantity')->nullable()->after('notes');
            $table->text('product_detail')->nullable()->after('quantity');
            $table->string('weight')->nullable()->after('product_detail');
            $table->string('dimension')->nullable()->after('weight');
            $table->text('employee_note')->nullable()->after('dimension');

            // Workflow tracking
            $table->foreignId('processed_by')
                ->nullable()
                ->after('employee_note')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('processed_at')
                ->nullable()
                ->after('processed_by');
        });
    }

    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('processed_by');

            $table->dropColumn([
                'sender_country',
                'sender_contact',
                'receiver_country',
                'receiver_contact',
                'notes',
                'quantity',
                'product_detail',
                'weight',
                'dimension',
                'employee_note',
                'processed_at',
            ]);
        });
    }
};