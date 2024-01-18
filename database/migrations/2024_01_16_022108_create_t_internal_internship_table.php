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
        Schema::create('t_internal_internship', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_activity_category');            
            $table->unsignedInteger('id_activity_type');
            $table->date('initial_period');
            $table->date('final_period');
            $table->string('activity_name');
            $table->string('activity_purpose');
            $table->string('organizer_name')->nullable();
            $table->string('organizer_location')->nullable();
            $table->string('id_pic', 20)->nullable();
            $table->string('id_supervisor', 20)->nullable();
            $table->enum('final', ['N', 'Y'])->default('N');
            $table->enum('approve', ['A', 'H', 'R'])->default('H');
            $table->mediumText('reject_text')->nullable();
            $table->string('file')->nullable();
            $table->string('file_type')->nullable();
            $table->timestamps();
            $table->string('created_by', 20)->nullable();
            $table->string('updated_by', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_internal_internship');
    }
};
