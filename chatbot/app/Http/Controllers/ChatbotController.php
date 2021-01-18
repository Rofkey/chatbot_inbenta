<?php

namespace App\Http\Controllers;

use App\Chatbot;
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
        $history = $apiController->getHistory();
        return view('chatbot/index',['history' => $history]);
    }

    /**
     * Create a new complete Conection. Token + Session.
     *
     * @return \Illuminate\Http\Response
     */
    public function completeConection($conection)
    {
        $apiController = new ChatController;
        $token = $apiController->token();
        $session = $apiController->session($token);
        $conection->setToken($token);
        $conection->setSession($session);
        $conection->save();
    }

     /**
     * create new session with a token of conection.
     *
     * @return \Illuminate\Http\Response
     */
    public function newSession($conection)
    {
        $apiController = new ChatController;
        $session = $apiController->session($conection->getToken());
        $conection->setSession($session);
        $conection->save();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Chatbot  $chatbot
     * @return \Illuminate\Http\Response
     */
    public function show(Chatbot $chatbot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Chatbot  $chatbot
     * @return \Illuminate\Http\Response
     */
    public function edit(Chatbot $chatbot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Chatbot  $chatbot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chatbot $chatbot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Chatbot  $chatbot
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chatbot $chatbot)
    {
        //
    }
}
