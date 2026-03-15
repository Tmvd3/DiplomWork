<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartsTable extends Migration
{
    public function up()
    {
        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('category');
            $table->integer('replacement_interval');
            $table->integer('last_replacement_mileage')->default(0);
            $table->integer('next_replacement_mileage')->default(0);
            $table->string('status')->default('good');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['car_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('parts');
    }
}
