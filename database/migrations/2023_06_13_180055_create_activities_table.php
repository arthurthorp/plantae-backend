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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->text("description");
            $table->enum('type', ['HARVEST', 'IRRIGATION', 'PARING', 'AGRICULTURAL_INPUT', 'OTHER']);
            $table->enum('status', ['PENDING', 'FORBIDDEN', 'FINISHED']);
            $table->date("estimate_date");
            $table->date("execution_date");
            $table->unsignedBigInteger('charge_in')->nullable();
            $table->unsignedBigInteger('plantation_id')->onDelete('cascade');

            //AGRICULTURAL_INPUT
            $table->unsignedBigInteger('agricultural_input_id')->nullable();
            $table->float('estimate_produtivity', 8, 2)->nullable();
            $table->float('real_produtivity', 8, 2)->nullable();

            //colheita
            $table->float('quantity_used', 8, 2)->nullable();


            $table->text("report_message")->nullable();



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
