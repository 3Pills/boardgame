<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('metadata')

    <title>@yield('title') - PVS</title>

    <link media="all" type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css"/>
    <link media="all" type="text/css" rel="stylesheet" href="{{url('/')}}/css/app.css"/>
    @yield('css')
</head>
<body>
    <header class="navbar navbar-default">
        <nav class="navbar-container">                
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
                @if(\Auth::user())
                    <li role="presentation" class="dropdown">
                        <a id="profile-link" type="button" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="{{ \URL::to('/user/'.\Auth::user()->url) }}">
                            <span class="navbar-username">{{\Auth::user()->name}}</span>
                            <img src="{{ url(file_exists('./images/avatars/'.\Auth::user()->url.'.jpg') ? './images/avatars/'.\Auth::user()->url.'.jpg' : './images/default-avatar.png') }}" alt="avatar", class = "img-rounded navbar-profile")/>
                        </a>
                        <div class="dropdown-menu dropdown-profile" aria-labelledby="profile-link">
                            <div class="profile-field"> <a href="{{ url('user/'.\Auth::user()->url) }}" class="btn-block">Profile</a></div>
                            <div class="profile-field"> <a href="{{ url('user/'.\Auth::user()->url.'/settings') }}" class="btn-block">Settings</a></div>
                            <div class="profile-field divider" role="separator"></div>               
                            <div class="profile-field"> <a href="{{ url('logout') }}" class="btn-block">Logout</a></div>
                        </div>
                    </li>
                @else
                    <!--<li><a href="{{ \URL::to('/login') }}"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>-->
                    <li role="presentation" class="dropdown">
                        <a id="login-link" type="button" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
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
                    </li>
                @endif
            </ul>
        </nav>
    </header>

    <section class="container">
        @yield('content')
    </section>
    @yield('post-content')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var base_url = '<?php echo url('/') ?>/';
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
    @yield('scripts-deferred')

    <footer class="footer">

    </footer>
</body>
</html>