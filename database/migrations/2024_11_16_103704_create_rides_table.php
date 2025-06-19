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
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->json('points')->nullable();
            $table->Integer('station_start_id')->nullable()->constrained();
            $table->Integer('station_end_id')->nullable()->constrained();
            $table->Integer('driver_id')->nullable()->constrained();
            $table->Integer('current_point')->nullable();//пройденна
            $table->date('date')->nullable();
            $table->bigInteger('distance')->nullable();
            $table->bigInteger('weigth')->nullable();
            $table->bigInteger('vutratu')->nullable();
            $table->bigInteger('vuruchka')->nullable();
            $table->bigInteger('driver_zarplata')->nullable();
            $table->enum('status', ['Призначено','Перенаправлено','Відмовлено водієм','Прийнято', 'В дорозі','Виконано','Аварійна ситуація'])->nullable();
            $table->json('accident_data')->nullable();
            $table->timestamps();

            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->foreign('station_start_id')->references('id')->on('stations');
            $table->foreign('station_end_id')->references('id')->on('stations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};
