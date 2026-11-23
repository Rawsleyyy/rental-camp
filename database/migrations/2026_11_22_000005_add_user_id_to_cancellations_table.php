<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cancellations', function (Blueprint $table) {
            if (! Schema::hasColumn('cancellations', 'user_id')) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('booking_id')
                    ->constrained('users')
                    ->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('cancellations', function (Blueprint $table) {
            if (Schema::hasColumn('cancellations', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
        });
    }
};
