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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('product');
            $table->string('status');
            $table->string('auto_type')->nullable();
            $table->string('ride_id')->nullable()->constrained();
            $table->string('auto_id')->nullable();//+
            $table->integer('weigth');//+
            $table->integer('distance')->nullable();//+
            $table->integer('vutratu')->nullable();//+
            $table->bigInteger('driver_id')->nullable()->constrained();
            $table->bigInteger('supplier_id')->nullable()->constrained();//+
            $table->string('point_A');
            $table->string('point_B');
            $table->string('station_id')->nullable()->constrained();//+
            $table->string('date_of_start');
            $table->timestamps();

            $table->foreign('auto_id')->references('id')->on('autos');
            $table->foreign('ride_id')->references('id')->on('rides');
            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('station_id')->references('id')->on('stations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
