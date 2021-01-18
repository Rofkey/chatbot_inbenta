<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ChatbotController;
use Illuminate\Http\Request;
use Session;

class ChatController extends Controller
{
    /**
     * generate token a new token and return it.
     * @param boolean $new
     * @return string 
     */
    public function token(){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.inbenta.io/v1/auth',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "secret": "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJwcm9qZWN0IjoieW9kYV9jaGF0Ym90X2VuIn0.anf_eerFhoNq6J8b36_qbD4VqngX79-yyBKWih_eA1-HyaMe2skiJXkRNpyWxpjmpySYWzPGncwvlwz5ZRE7eg"
        }',
        CURLOPT_HTTPHEADER => array(
            'x-inbenta-key: nyUl7wzXoKtgoHnd2fB0uRrAv0dDyLC+b4Y6xngpJDY=',
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $responseArray = json_decode($response,true);

        if (isset($responseArray['accessToken'])){
            session()->put('accessToken', $responseArray['accessToken']);
        }
    }


     /**
     * newSession() create or update a new session
     * @param boolean $new
     * @return string 
     */
    public function session($token){  
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api-gce3.inbenta.io/prod/chatbot/v1/conversation',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            'x-inbenta-key: nyUl7wzXoKtgoHnd2fB0uRrAv0dDyLC+b4Y6xngpJDY=',
            'Authorization: Bearer '.$token
        ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $responseArray = json_decode($response,true);
        if (isset($responseArray['sessionToken'])){
            session()->put('sessionToken', $responseArray['sessionToken']);
        }
    }

    public function sendMessage(){
        $message = $_GET['message'];

        //Load controller to call functions.
        $controller = new ChatbotController;

        //get token and session to "session"
        $token = session()->get('accessToken');
        $session = session()->get('sessionToken');
      
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api-gce3.inbenta.io/prod/chatbot/v1/conversation/message',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "message": "'.$message.'"
        }',
        CURLOPT_HTTPHEADER => array(
            'x-inbenta-key: nyUl7wzXoKtgoHnd2fB0uRrAv0dDyLC+b4Y6xngpJDY=',
            'x-inbenta-session: Bearer '.$session,
            'Authorization: Bearer '.$token,
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);
        $errors = curl_error($curl);
        $responseInfo = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $responseArray = json_decode($response, true);
        curl_close($curl);

        //User unauthorized -> Create a new Chatbot (Token + Sesion).
        if($responseInfo !== 400 && $responseInfo !== 200){  
            $controller->completeConection();
            return 'false';
        }

        //Session Expired -> Create a new Session conversation
        if($responseInfo === 400){
            $controller->newSession();
            return 'false';
        }
        
        if(isset($responseArray['answers'])){
            $response = $responseArray['answers'][0]['message'];
        }else{
            $response = 'false';
        }

        return $response;
    }

    public function getHistory(){

        //load session and token
        $token = session()->get('accessToken');
        $session = session()->get('sessionToken');

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api-gce3.inbenta.io/prod/chatbot/v1/conversation/history',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'x-inbenta-key: nyUl7wzXoKtgoHnd2fB0uRrAv0dDyLC+b4Y6xngpJDY=',
            'x-inbenta-session: Bearer '.$session,
            'Authorization: Bearer '.$token
          ),
        ));
        
        $response = curl_exec($curl);
        $errors = curl_error($curl);
        $responseInfo = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $responseArray = json_decode($response, true);
        curl_close($curl);

        return $responseArray;
    }
}
