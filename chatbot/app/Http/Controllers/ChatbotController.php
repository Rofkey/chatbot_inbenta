<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\ChatController;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $apiController = new ChatController;
        //if not exist token or session -> New conection.
        $token = session()->get('accessToken');
        $session = session()->get('sessionToken');
        if(!$token || !$session){
            $this->completeConection();
        }
        $history = $apiController->getHistory();
        return view('chatbot/index',['history' => $history]);
    }

    /**
     * Create a new complete Conection. Token + Session.
     *
     * @return \Illuminate\Http\Response
     */
    public function completeConection($conection = null)
    {
        $apiController = new ChatController;
        $apiController->token();
        $apiController->session($token);
    }

     /**
     * create new session with a token of conection.
     *
     * @return \Illuminate\Http\Response
     */
    public function newSession($conection)
    {
       $apiController->session($conection->getToken());
    }

}
