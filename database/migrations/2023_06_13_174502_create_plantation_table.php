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
        Schema::create('plantations', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("description");
            $table->string("cultivation");
            $table->date("planting_date");
            $table->date("estimate_harvest_date");
            $table->float("plantation_size", 8,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plantation');
    }
};
