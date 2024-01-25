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
        Schema::create('m_users', function (Blueprint $table) {
            $table->id();
            $table->string('nim_nik', 50)->unique();
            $table->string('name');
            $table->string('password');
            $table->unsignedInteger('id_user_role');
            $table->unsignedInteger('id_prodi');
            $table->string('tahun_id', 20)->nullable();
            $table->string('email');
            $table->string('email2')->nullable();
            $table->string('mentor', 50)->nullable();
            $table->rememberToken();
            $table->enum('na', ['N', 'Y'])->default('N');
            $table->integer('has_password')->default('0');
            $table->timestamps();
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();
            // $table->foreign('id_user_role')->references('id')->on('m_user_role');
            // $table->foreign('id_prodi')->references('id')->on('m_prodi');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
