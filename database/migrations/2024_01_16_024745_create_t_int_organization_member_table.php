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
        Schema::create('t_int_organization_member', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_internal_organization');
            $table->string('id_user', 20);
            $table->date('initial_period')->nullable();
            $table->date('final_period')->nullable();
            $table->unsignedInteger('id_level');
            $table->unsignedInteger('id_role');
            $table->float('score', 8, 2);
            $table->string('role_description')->nullable();
            $table->enum('na', ['N', 'Y'])->default('N');
            $table->enum('approve', ['A', 'H', 'R'])->default('H');
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
        Schema::dropIfExists('t_int_organization_member');
    }
};
