<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * token() return a token in session or create a new token and save it in session.
     * @param boolean $new
     * @return string 
     */
    public function token(Request $request, $new = false){
        
        if(Session::get('access_token') != null || $new){
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
            if($responseArray['accessToken']){

                $request->session()->put('access_token',$responseArray['accessToken']);
            }
        }
        return $request->session()->get('access_token');
    }


     /**
     * newSession() return the actual session or create a new session
     * @param boolean $new
     * @return string 
     */
    public function session(Request $request, $new = false){
        //get the token witch we need to call session conversation
        $token = $this->token($request, true);
        if(Session::get('session_token') == null || $new){
            //generate new session        
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
            if($responseArray['sessionToken']){
                Session::put(['session_token'=>$responseArray['sessionToken']]);
            }
        }

        return Session::get('session_token');
    }

    public function sendMessage(){
        //get token
        $token = $this->token(true);
        //get session
        $session = $this->session(true);

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
            "message": "How can I repair my TV?",
            "option": "10",
            "directCall": "ESCALATE_FORM"
        }',
        CURLOPT_HTTPHEADER => array(
            'x-inbenta-key: nyUl7wzXoKtgoHnd2fB0uRrAv0dDyLC+b4Y6xngpJDY=',
            'x-inbenta-session: '.$session,
            'Authorization: Bearer '.$token,
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        dump($response);die();
        echo $response;
    }

    public function getHistory(Request $request){
        //get token
        $token = $this->token($request, true);
        //get session
        $session = $this->session($request, true);

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
            'x-inbenta-session: '.$session,
            'Authorization: Bearer '.$token
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $request->session()->put('access_token',"prueba");
        dump($request->session()->all());die();
    }
}
