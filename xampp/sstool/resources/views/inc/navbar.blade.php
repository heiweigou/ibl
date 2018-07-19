<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <div class="container">

        <div class="navbar-header">
                <a class="navbar-brand" href="{{ url('/') }}">
                    SSPortal
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
        </div>

        <div class="collapse navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                &nbsp;
            </ul>

            <ul class="navbar-nav mr-auto">
                @auth
                    <li class="nav-item"><a href="/posts" class="nav-link">Requests</a></li>
                    @if(Auth::user()->type == 'admin')
                        <li class="nav-item"><a href="/users" class="nav-link">Users</a></li>
                    @endif
                @endauth
                <li class="nav-item"><a href="/about" class="nav-link">About</a></li>
                <li class="nav-item"><a href="/services" class="nav-link">Services</a></li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav">
                <!-- Authentication Links -->
                @guest
                    <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                    <!--<li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li> -->
                @else
                    <li class="nav-item">
                        <a href="/posts" class="nav-link">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                   
                @endguest
            </ul>
        </div>
    </div>
</nav>