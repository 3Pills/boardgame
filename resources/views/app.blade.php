<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>@yield('title') - PVS</title>

        {!! HTML::script('https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js') !!}

        {!! HTML::style('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css') !!}
        {!! HTML::script('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js') !!}

        {!! HTML::style('css/style.css') !!}

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
                <a class="navbar-brand" href="{{ \URL::to('/') }}">
                    <span class="navbar-brand-web">Play Vidya Soon</span>
                    <span class="navbar-brand-mob">PVS</span>
                </a>
                
                <ul class="nav navbar-nav">
                    <li class="nav-item">
                        {!! HTML::link('/game', "Play") !!}
                    </li>
                    <li class="nav-item">
                        {!! HTML::link('/user', "Users") !!}
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    @if(\Auth::user())
                        <li>
                            <a href="{{ \URL::to('/logout') }}">
                                <span class="glyphicon glyphicon-log-out"></span> Logout
                            </a>
                        </li>
                        <li>
                            <a href="{{ \URL::to('/user/'.\Auth::user()->url) }}">
                                <span class="navbar-username">{{\Auth::user()->name}}</span>
                                {!! HTML::image(file_exists('./images/avatars/'.\Auth::user()->url.'.jpg') ? './images/avatars/'.\Auth::user()->url.'.jpg' : './images/default-avatar.png', 'avatar', ['class' => 'img-rounded navbar-profile']) !!}
                            </a>
                        </li>
                        <li>
                        </li>
                    @else
                        <li><a href="{{ \URL::to('/register') }}"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                        <li><a href="{{ \URL::to('/login') }}"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                    @endif
                </ul>
            </nav>
        </header>

        <section class="container">
            @yield('content')
        </section>
        @yield('post-content')

        @yield('footer')
    </body>
</html>