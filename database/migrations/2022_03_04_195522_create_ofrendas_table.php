<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfrendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ofrendas', function (Blueprint $table) {
            $table->id()->unsigned();    
            $table->integer('user_id');         
            $table->boolean('periocidad');
            $table->integer('ofrenda');
            $table->date('fecha');
            $table->text('detalle');   
        });       

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ofrendas');
    }
}
