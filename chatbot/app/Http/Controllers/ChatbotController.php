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
        $history = $this->formatHistory($apiController->getHistory());
        
        $data = [];
        if($history != 'false'){
            $data = ['history' =>$history];
        }
        return view('chatbot/index', $data);
    }

    /**
     * Create a new complete Conection. Token + Session.
     *
     * @return \Illuminate\Http\Response
     */
    public function completeConection()
    {
        $apiController = new ChatController;
        $apiController->token();
        $apiController->session(session()->get('accessToken'));
    }

     /**
     * create new session with a token of conection.
     *
     * @return \Illuminate\Http\Response
     */
    public function newSession()
    {
        $apiController = new ChatController;
        $apiController->session(session()->get('accessToken'));
    }

    /***
     * get history and format the tip5 and return history
     */
    public function formatHistory($history){
        $apiController = new ChatController;
        //counter of not found messages
        $count = 0;
        $haveForceBool = false;
        foreach($history as $key =>$message){
            //if user say "force" 
            if($message['user'] === 'user'){
                $haveForce = strpos($message['message'], 'force');
                if($haveForce !== false){
                    //$messageApi = $apiController->getMessageFilms();
                    $haveForceBool = true;
                    //$history[$key+1]['messageList'] = $messageApi;
                }
            }else{
                if($haveForceBool){
                    $messageApi = $apiController->getMessageFilms();
                    $history[$key]['messageList'] = $messageApi;
                    $haveForceBool = false;
                }else{
                    //see if is the second "not found"
                    $notFound = strpos($message['message'], "couldn't find");
                    if(!$notFound)
                        $notFound =strpos($message['message'], "Please search again");
                    if(!$notFound){
                        $count=0;
                    }else{
                        $count++;
                    }
                    if($count >1){
                        //call to get heros
                        $messageApi = $apiController->getMessageHeroes();
                        $history[$key]['messageList'] = $messageApi;
                        $count=0;

                    }

                        
                }
            }    
        }
        return $history;
    }

}
