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
        Schema::create('work_times', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
             $table->date('date');
           $table->double('hours');
    $table->foreignId('emp_id')->constrained('employees')->onDelete('cascade')->onUpdate('cascade');
    $table->foreignId('project_id')->constrained('projects')->onDelete('cascade')->onUpdate('cascade');
    $table->foreignId('modul_id')->constrained('moduls')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_times');
    }
};
