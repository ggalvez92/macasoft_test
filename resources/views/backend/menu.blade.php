<nav class="navbar navbar-expand-lg navbar-light {{ isset($home) ? 'menu_home' : '' }}" id="menu">
	<div class="container-fluid">
		<a class="navbar-brand" href="{{ route('dashboard.index') }}"><img src="{{ asset('imgs/gianpierre.png?'.time()) }}"></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item dropdown active">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}">Cerrar sesión</a>
                    </div>
                </li>
            </ul>
        </div>
	</div>
</nav>