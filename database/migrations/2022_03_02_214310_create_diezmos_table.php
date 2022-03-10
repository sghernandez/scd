<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiezmosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diezmos', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->integer('user_id');    
            $table->date('fecha');          
            $table->integer('diezmo');
            $table->integer('ofrenda');
            $table->boolean('entregado')->default(0);                                    
            $table->text('observacion')->nullable();
            $table->timestamps();
            /*
            $table->foreign('user_id')
            ->references('id')->on('users')
            ->onDelete('cascade'); */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diezmos');
    }
}
