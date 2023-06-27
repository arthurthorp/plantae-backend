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

            //HARVEST
            $table->float('estimate_produtivity', 8, 2)->nullable();
            $table->float('real_produtivity', 8, 2)->nullable();

            //AGRICULTURAL_INPUT
            $table->unsignedBigInteger('agricultural_input_id')->nullable();
            $table->float('quantity_used', 8, 2)->nullable();


            $table->text("report_message")->nullable();



            $table->timestamps();
            $table->foreign('charge_in')->references('id')->on('users');
            $table->foreign('plantation_id')->references('id')->on('plantations');
            $table->foreign('agricultural_input_id')->references('id')->on('agricultural_inputs');
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
