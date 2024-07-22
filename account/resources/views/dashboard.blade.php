<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Nextvacay</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.min.css"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js"></script>



        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <h1 id="resp-div"></h1>
                <form action="javascript:;" id="form" style="display: none">
                    <label>Change Email</label>
                    <input type="email" name="email" id="email" placeholder="Enter Email">
                    <input type="submit" value="Send">
                </form>
            </div>
        </div>
    </body>
</html>
<script type="text/javascript">

    var baseurl = window.location.origin;
    var baseurl_api = 'http://127.0.0.1:8001/api/';

    $(document).ready(function () {
        $.ajax({
            type:'POST',
            async:false,
            url:baseurl_api+'check-auth',
            data:{
                token:'{{$token}}',
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}
            },
            success: function(resp){
                var login_resp = resp;
                $('#resp-div').html(resp.message);
                if(resp.status == 'success'){
                    $('#form').show();

                    $('#form').validate({ // initialize the plugin
                        rules: {
                            email: {
                                required: true,
                                email: true
                            }
                        },
                        message:{
                            email:{
                                required: 'Email is required',
                                email: 'Not a valid email'
                            }
                        },
                        submitHandler: function(form){

                            var email = $('#email').val();
                            $.ajax({
                                type:'POST',
                                url:baseurl_api+'edit',
                                data:{
                                    email: email,
                                    token: '{{$token}}',
                                    uid: login_resp.uid,
                                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}
                                },
                                success: function(resp){
                                    $('#resp-div').html(resp.message);
                                    if(resp.status == 'failure'){
                                        $('#form').hide();
                                    }
                                },error:function(){

                                }
                            })
                        }
                    });

                }
            },error:function(){

            }
        });
    });
</script>