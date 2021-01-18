<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
    <title>YodaBot</title>
 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
   </head>
<body>
    <div class="container">
        <div class="row">
            <h1 class="text-center">YodaBot</h1>
        </div>
        <div class="row">
            <div class ="col" style="">
                <ul id="chatBody">
                @isset($history)
                    @foreach($history as $key => $messages)
                        @foreach($messages['messageList'] as $key=> $message)
                                @if($messages['user'] === 'user')
                                    <li>
                                    <span class='fw-bold'>Me: </span>{{$message}}
                                    </li>
                                
                                @else
                                    @if($key === 0)
                                        <li>
                                        <span class='fw-bold'>YodaBot: </span> {{$message}}
                                        </li>
                                    @else
                                        <ul><li>{{$message}}</li></ul>
                                    @endif    
                                @endif    
                            </li>
                        @endforeach
                    @endforeach
                @endisset
                </ul>
            </div>
         </div>
        <div class="row">
        <div class="col-12">
            <span id="writting" style="display: none;">Yodabot is writting...</span>
        </div>
            <form id="yodabotForm">
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-10 m-0 p-0">
                            <input required type="text" name="message" class="form-control" id="message">
                        </div>
                        <div class="col-12 col-sm-2 m-0 p-0">
                            <button style="width:100%; height:100%" class="btn btn-success" id="submit">Send!</button>    
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
</body>
</html>

<script>
    $('#yodabotForm').on('submit',function(event){
        
        event.preventDefault();

        message = $('#message').val();
        if(message !== ''){
            $('#message').val('');
            $('#chatBody').append('<li> <span class="fw-bold">Me: </span>'+message+'</li>');
            $('#writting').show();
            //send message to chatbot
            $.ajax({
                url: 'send_message',
                type:"GET",
                data: {'message': message},
                success: function(data){
                    $('#writting').hide();
                    if(data !== 'false'){
                        $.each(data, function(key, message){
                            if(key == 0){
                                $('#chatBody').append('<li> <span class="fw-bold">YodaBot: </span>'+message+'</li>');
                            }else{
                                $('#chatBody').append('<ul><li>'+message+'</li></ul>');
                            }
                        });
                    }
                    else{
                        $('#chatBody').html('');
                        alert("Session expired (Creating a new session...)");
                    }
                }
            });
        }
    });
</script>