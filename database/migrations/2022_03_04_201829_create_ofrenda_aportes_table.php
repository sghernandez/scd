<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfrendaAportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ofrenda_aportes', function (Blueprint $table) {
            $table->id()->unsigned();    
            $table->integer('ofrenda_id');         
            $table->integer('aporte');
            $table->boolean('entregado')->default(0);
            $table->date('fecha');
            $table->text('observacion');               
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ofrenda_aportes');
    }
}
