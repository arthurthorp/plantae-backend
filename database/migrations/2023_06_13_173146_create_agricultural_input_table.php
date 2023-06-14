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
        Schema::create('agricultural_inputs', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->enum('type', ["FERTILIZER", "PESTICIDE", "FUNGICIDE", "OTHER"]);
            $table->text("rules");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agricultural_input');
    }
};
