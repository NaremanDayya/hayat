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
        Schema::table('family_members', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->enum('gender', ['male', 'female'])->nullable()->change();
        });

        Schema::table('health_conditions', function (Blueprint $table) {
            $table->string('person_name')->nullable()->change();
            $table->text('condition_details')->nullable()->change();
        });
        
        Schema::table('families', function (Blueprint $table) {
            $table->string('husband_name')->nullable()->change();
            $table->string('wife_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('family_members', function (Blueprint $table) {
            $table->string('name')->change();
            $table->enum('gender', ['male', 'female'])->change();
        });

        Schema::table('health_conditions', function (Blueprint $table) {
            $table->string('person_name')->change();
            $table->text('condition_details')->change();
        });
        
        Schema::table('families', function (Blueprint $table) {
            $table->string('husband_name')->nullable(false)->change();
            $table->string('wife_name')->nullable(false)->change();
        });
    }
};
