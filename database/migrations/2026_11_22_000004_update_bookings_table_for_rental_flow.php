<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'total_days')) {
                $table->unsignedInteger('total_days')->default(1)->after('end_date');
            }
            if (! Schema::hasColumn('bookings', 'contact_name')) {
                $table->string('contact_name')->nullable()->after('total_days');
            }
            if (! Schema::hasColumn('bookings', 'contact_email')) {
                $table->string('contact_email')->nullable()->after('contact_name');
            }
            if (! Schema::hasColumn('bookings', 'contact_phone')) {
                $table->string('contact_phone')->nullable()->after('contact_email');
            }
            $table->string('status', 30)->default('pending')->change();
            if (! Schema::hasColumn('bookings', 'payment_method')) {
                $table->string('payment_method', 50)->nullable()->after('status');
            }
            if (! Schema::hasColumn('bookings', 'payment_proof')) {
                $table->string('payment_proof')->nullable()->after('payment_method');
            }
            if (! Schema::hasColumn('bookings', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_proof');
            }
            if (! Schema::hasColumn('bookings', 'returned_at')) {
                $table->timestamp('returned_at')->nullable()->after('paid_at');
            }
            if (! Schema::hasColumn('bookings', 'late_days')) {
                $table->unsignedInteger('late_days')->default(0)->after('returned_at');
            }
            if (! Schema::hasColumn('bookings', 'late_fee')) {
                $table->decimal('late_fee', 12, 2)->default(0)->after('late_days');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $columns = [
                'total_days',
                'contact_name',
                'contact_email',
                'contact_phone',
                'payment_method',
                'payment_proof',
                'paid_at',
                'returned_at',
                'late_days',
                'late_fee',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
