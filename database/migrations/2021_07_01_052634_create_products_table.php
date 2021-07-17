<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
          $table->id();
          $table->string('name');
          $table->string('cost');
          $table->string('description');
          // $table->string('color');
          // $table->string('productivity');
          // $table->string('num_of_speed');
          // $table->string('controll_type');
          // $table->string('height');
          // $table->string('width');
          // $table->string('depth');
          // $table->string('montage');
          // $table->string('range_hood_type');
          // $table->string('lighting_type');
          // $table->string('glass');
          // $table->string('producing_country');
          $table->foreignId('categories_id')->constrained('categories');
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
