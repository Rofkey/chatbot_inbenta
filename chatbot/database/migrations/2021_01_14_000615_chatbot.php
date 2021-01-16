<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Chatbot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chatbots', function(Blueprint $table){
            $table->increments('id'); //Predefined ID
            $table->string('token'); // Conection Token
            $table->string('session'); // Conection Session
            $table->timestamps(); //Predefined Timestams
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chatbots');
    }
}
