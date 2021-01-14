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
        Schema::create('chatbot', function(Blueprint $table){
            $table->increments('id'); //Predefined ID
            $table->string('message'); // Message
            $table->set('emitter',['YodaBot', 'Me']); // Set for emitter
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
        Schema::dropIfExists('chatbot');
    }
}
