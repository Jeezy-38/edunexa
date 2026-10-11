<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('job_opportunities')) {
            Schema::create('job_opportunities', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('company_name');
                $table->string('company_logo')->nullable();
                $table->string('location')->default('Dar es Salaam');
                $table->string('job_type')->default('Full-time'); // Full-time, Internship, Freelance, Part-time
                $table->string('salary_range')->nullable();
                $table->longText('description')->nullable();
                $table->text('requirements')->nullable();
                $table->unsignedBigInteger('related_course_id')->nullable();
                $table->string('apply_type')->default('whatsapp'); // whatsapp, email, link
                $table->string('apply_value'); // phone, email, or url
                $table->date('deadline')->nullable();
                $table->tinyInteger('is_featured')->default(0);
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_opportunities');
    }
};
