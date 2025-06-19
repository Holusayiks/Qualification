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
        Schema::create('stations', function (Blueprint $table) {
            $table->id();
            $table->string("nomer");
            $table->string("address");
            $table->integer("menedger_id")->nullable()->constrained();
            $table->integer("dispetcher_id")->nullable()->constrained();
            $table->timestamps();

            $table->foreign('menedger_id')->references('id')->on('menedgers');
            $table->foreign('dispetcher_id')->references('id')->on('dispetchers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stations');
    }
};
