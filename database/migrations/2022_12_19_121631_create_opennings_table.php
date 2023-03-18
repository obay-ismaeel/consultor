<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opennings', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('expert_id')->constrained()->onUpdate('cascade')->onDelete('cascade');

            $table->enum('day', ['sunday','monday','tuesday','wednesday','thursday','friday','saturday']);
            
            $table->string('hours')->default('000000000000000000000000');
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('opennings');
    }
};
