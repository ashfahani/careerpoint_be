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
        Schema::create('t_cp_competition', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_activity_type');
            $table->unsignedInteger('id_activity_category');
            $table->string('id_user', 50);
            $table->string('activity_name');
            $table->date('initial_period');
            $table->date('final_period');
            $table->string('period', 50)->nullable();
            $table->string('organizer_name')->nullable();
            $table->string('organizer_location')->nullable();
            $table->unsignedInteger('id_level');            
            $table->unsignedInteger('id_role');
            $table->float('score', 8, 2);
            $table->enum('approve', ['A', 'H', 'R'])->default('H');
            $table->enum('na', ['N', 'Y'])->default('N');
            $table->string('file')->nullable();
            $table->string('file_type')->nullable();
            $table->mediumText('reject_text')->nullable();
            $table->unsignedInteger('id_internal_committee')->nullable();
            $table->enum('send_email', ['N', 'Y'])->default('N');
            $table->timestamps();
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_cp_competition');
    }
};
