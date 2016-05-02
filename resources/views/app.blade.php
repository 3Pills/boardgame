<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title') - PVS</title>

    {!! HTML::script('https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js') !!}

    {!! HTML::style('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css') !!}
    {!! HTML::script('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js') !!}

    {!! HTML::style('css/app.css') !!}

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var base_url = '<?php echo \URL::to('/') ?>/';
    </script>
    @yield('includes')
</head>
<body>
    <header class="navbar navbar-default">
        <nav class="navbar-container">                
            <ul class="nav navbar-nav">
                <li>
                    <a class="navbar-brand" href="{{ \URL::to('/') }}">
                        <span class="navbar-brand-web">Play Vidya Soon</span>
                        <span class="navbar-brand-mob">PVS</span>
                    </a>
                </li>
                <li class="nav-item"> {!! HTML::link('/game', "Play") !!} </li>
                <li class="nav-item"> {!! HTML::link('/user', "Users") !!} </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if(\Auth::user())
                    <li role="presentation" class="dropdown">
                        <a id="profile-link" type="button" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="{{ \URL::to('/user/'.\Auth::user()->url) }}">
                            <span class="navbar-username">{{\Auth::user()->name}}</span>
                            {!! HTML::image(file_exists('./images/avatars/'.\Auth::user()->url.'.jpg') ? './images/avatars/'.\Auth::user()->url.'.jpg' : './images/default-avatar.png', 'avatar', ['class' => 'img-rounded navbar-profile']) !!}
                        </a>
                        <div class="dropdown-menu dropdown-profile" aria-labelledby="profile-link">
                            <div class="profile-field"> {!! HTML::link(\URL::to('user/'.\Auth::user()->url), 'Profile', ['class' => 'btn-block']) !!}</div>
                            <div class="profile-field"> {!! HTML::link(\URL::to('user/'.\Auth::user()->url.'/settings'), 'Settings', ['class' => 'btn-block']) !!}</div>
                            <div class="profile-field divider" role="separator"></div>               
                            <div class="profile-field"> {!! HTML::link(\URL::to('logout'), "Logout", ['class' => 'btn-block']) !!}</div>
                        </div>
                    </li>
                @else
                    <!--<li><a href="{{ \URL::to('/login') }}"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>-->
                    <li role="presentation" class="dropdown">
                        <a id="login-link" type="button" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            Login <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu dropdown-login" aria-labelledby="login-link">
                            {!! Form::open(['url' => \URL::to('login'), 'role' => 'form', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="login-field"> {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) !!} </div>
                                <div class="login-field"> {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) !!} </div>
                                <div class="login-field"> {!! Form::checkbox('remember') !!} {!! Form::label('remember', 'Remember Me') !!} </div>
                                <div class="login-field"> {!! Form::submit('Login', ['class' => 'btn btn-primary btn-block']) !!} </div>                 
                            {!! Form::close() !!}
                            <div class="login-field divider" role="separator"></div>               
                            <div class="login-field"> {!! HTML::link(\URL::to('register'), "Don't have an account?", ['class' => 'btn btn-default btn-block']) !!}</div>
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

    <footer class="footer">

    </footer>
</body>
</html>