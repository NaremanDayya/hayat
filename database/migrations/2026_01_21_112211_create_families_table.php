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
        Schema::create('families', function (Blueprint $table) {
            $table->id();
            $table->string('husband_name')->nullable();
            $table->string('husband_id_number')->nullable();
            $table->date('husband_dob')->nullable();
            $table->string('husband_phone')->nullable();
            $table->string('wife_name')->nullable();
            $table->string('wife_id_number')->nullable();
            $table->date('wife_dob')->nullable();
            $table->string('wife_phone')->nullable();
            $table->integer('family_members_count')->default(0);
            $table->text('original_address')->nullable();
            $table->text('current_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('families');
    }
};
