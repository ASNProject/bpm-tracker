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
        Schema::create('bpms', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('record_id');
            $table->string('age');
            $table->string('gender');
            $table->string('status');
            $table->string('bpm');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bpms');
    }
};
