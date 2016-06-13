<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('metadata')

    <title>@yield('title') - PVS</title>

    <link media="all" type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css"/>
    <link media="all" type="text/css" rel="stylesheet" href="{{url('/assets/css/app.css')}}"/>
    @yield('css')
</head>
<body>
    <nav class="navbar navbar-default">
        <div class="navbar-container">                
            <ul class="nav navbar-nav">
                <li>
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <span class="navbar-brand-web">Play Vidya Soon</span>
                        <span class="navbar-brand-mob">PVS</span>
                    </a>
                </li>
                <li class="nav-item"><a href="{{ url('/game') }}">Play</a></li>
                <li class="nav-item"><a href="{{ url('/user') }}">Users</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if(\Auth::check())
                <li class="navbar-profile">
                    <a class="navbar-profile-link" id="profile-link" type="button" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="{{ \URL::to('/user/'.\Auth::user()->url) }}">
                        <span class="navbar-profile-username">{{\Auth::user()->name}}</span>
                        <img src="{{url('/assets/images/').(file_exists('./assets/images/avatars/'.\Auth::user()->url.'.jpg') ? '/avatars/'.\Auth::user()->url.'.jpg' : '/default-avatar.png')}}" alt="avatar", class = "img-rounded navbar-profile-avatar")/>
                    </a>
                    <div class="dropdown-menu dropdown-profile" aria-labelledby="profile-link">
                        <div class="profile-field"> <a href="{{ url('user/'.\Auth::user()->url) }}" class="btn-block">Profile</a></div>
                        <div class="profile-field"> <a href="{{ url('user/'.\Auth::user()->url.'/settings') }}" class="btn-block">Settings</a></div>
                        <div class="profile-field divider" role="separator"></div>               
                        <div class="profile-field"> <a href="{{ url('logout') }}" class="btn-block">Logout</a></div>
                    </div>
                @else
                <li>
                    <!--<li><a href="{{ \URL::to('/login') }}"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>-->
                    <a class="navbar-login" id="login-link" type="button" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        Login <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu dropdown-login" aria-labelledby="login-link">
                        <form method="POST" action="{{ url('/login') }}" role="form" class="form-horizontal" accept-charset="UTF-8" >
                            <input name="_token" type="hidden" value="{{ csrf_token() }}">
                            <div class="login-field"> <input type="email" name="email" placeholder="Email" class='form-control'> </div>
                            <div class="login-field"> <input type="password" name="password" placeholder="Password" class='form-control' > </div>
                            <div class="login-field"> <input type="checkbox" name="remember" value="1"> <label for="remember">Remember Me</label></div>
                            <div class="login-field"> <input type="submit" value="Login" class="btn btn-primary btn-block"></div>                 
                        </form>
                        <div class="login-field divider" role="separator"></div>               
                        <div class="login-field"> <a href="{{url('register')}}" class="btn btn-default btn-block">Don't have an account?</a> </div>
                    </div>
                @endif
                </li>
            </ul>
        </div>
    </nav>

    <section class="container">
        @if(\Auth::check() && \Auth::user()->role == 0) 
            <div class="alert alert-warning">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Alert!</strong> Your email has yet to be validated. You will not be able to create game lobbies or chat. Click <a>here</a> to resend verification email.
            </div>
        @endif

        @yield('content')
    </section>
    @yield('post-content')

    <!--
    <footer class="footer">

    </footer>

    <script src="http://js.pusher.com/3.0/pusher.min.js"></script>
    <script>
        var pusher = new Pusher("{{env("PUSHER_KEY")}}")
        var channel = pusher.subscribe('test-channel');
        channel.bind('test-event', function(data) {
          alert(data.text);
        });
    </script>
    -->
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
    @yield('scripts-deferred')
</body>
</html>