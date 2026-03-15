<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('brand');
            $table->string('model');
            $table->integer('year');
            $table->string('vin')->nullable()->unique();
            $table->string('license_plate')->nullable();
            $table->string('color')->nullable();
            $table->integer('initial_mileage')->default(0);
            $table->integer('current_mileage')->default(0);
            $table->string('image')->default('default-car.png');
            $table->timestamps();

            $table->index(['user_id', 'brand']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cars');
    }
}
