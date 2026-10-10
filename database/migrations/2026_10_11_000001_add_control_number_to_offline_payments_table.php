<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('offline_payments', 'control_number')) {
            Schema::table('offline_payments', function (Blueprint $table) {
                $table->string('control_number', 20)->nullable()->unique();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('offline_payments', 'control_number')) {
            Schema::table('offline_payments', function (Blueprint $table) {
                $table->dropUnique(['control_number']);
                $table->dropColumn('control_number');
            });
        }
    }
};
