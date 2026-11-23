<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('status', 30)->default('pending')->change();

            if (! Schema::hasColumn('bookings', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('status');
            }
            if (! Schema::hasColumn('bookings', 'unique_code')) {
                $table->unsignedSmallInteger('unique_code')->nullable()->after('payment_method');
            }
            if (! Schema::hasColumn('bookings', 'payment_sender_name')) {
                $table->string('payment_sender_name')->nullable()->after('payment_proof');
            }
            if (! Schema::hasColumn('bookings', 'payment_rejection_reason')) {
                $table->text('payment_rejection_reason')->nullable()->after('payment_sender_name');
            }
            if (! Schema::hasColumn('bookings', 'picked_up_at')) {
                $table->timestamp('picked_up_at')->nullable()->after('paid_at');
            }
            if (! Schema::hasColumn('bookings', 'return_condition')) {
                $table->string('return_condition', 50)->nullable()->after('returned_at');
            }
            if (! Schema::hasColumn('bookings', 'is_late')) {
                $table->boolean('is_late')->default(false)->after('return_condition');
            }
            if (! Schema::hasColumn('bookings', 'damage_fee')) {
                $table->decimal('damage_fee', 12, 2)->default(0)->after('late_fee');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $columns = [
                'rejection_reason',
                'unique_code',
                'payment_sender_name',
                'payment_rejection_reason',
                'picked_up_at',
                'return_condition',
                'is_late',
                'damage_fee',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
