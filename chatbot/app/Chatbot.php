<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chatbot extends Model
{
    public function getSession(){
        return $this->attributes['session'];
    }

    public function setSession($value){
        $this->attributes['session'] = $value;
        return $this;
    }

    public function getToken(){
        return $this->attributes['token'];
    }

    public function setToken($value){
        $this->attributes['token'] = $value;
        return $this;
    }
}
