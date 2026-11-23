<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_items', function (Blueprint $table) {
            if (! Schema::hasColumn('booking_items', 'is_handover_checked')) {
                $table->boolean('is_handover_checked')->default(false)->after('price_at_booking');
            }
            if (! Schema::hasColumn('booking_items', 'handover_notes')) {
                $table->string('handover_notes')->nullable()->after('is_handover_checked');
            }
            if (! Schema::hasColumn('booking_items', 'handover_checked_at')) {
                $table->timestamp('handover_checked_at')->nullable()->after('handover_notes');
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'handover_staff_id')) {
                $table->foreignId('handover_staff_id')
                    ->nullable()
                    ->after('picked_up_at')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'handover_staff_id')) {
                $table->dropConstrainedForeignId('handover_staff_id');
            }
        });

        Schema::table('booking_items', function (Blueprint $table) {
            $columns = ['is_handover_checked', 'handover_notes', 'handover_checked_at'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('booking_items', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
