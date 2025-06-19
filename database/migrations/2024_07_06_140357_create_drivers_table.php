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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone');
            $table->date('hire_date')->nullable()->constrained();
            $table->Integer('current_station')->nullable()->constrained();
            $table->Integer('user_id')->nullable()->constrained();
            $table->string('status');
            $table->string('accident_type')->nullable();
            $table->Integer('days_in_vacation')->nullable();
            $table->date('date_of_vacation')->nullable();
            $table->string('station_id')->nullable()->constrained();
            $table->timestamps();

            $table->foreign('station_id')->references('id')->on('stations');
            $table->foreign('current_station')->references('id')->on('stations');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
