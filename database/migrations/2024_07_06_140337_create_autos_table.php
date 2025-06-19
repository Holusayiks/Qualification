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
        Schema::create('autos', function (Blueprint $table) {
            $table->id();
            $table->string('nomer');
            $table->string('type');
            $table->bigInteger('lifting_weight');
            $table->bigInteger('driver_id')->nullable()->constrained();
            $table->string('station_id')->nullable()->constrained();
            $table->timestamps();

            $table->foreign('station_id')->references('id')->on('stations');
            $table->foreign('driver_id')->references('id')->on('drivers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autos');
    }
};
