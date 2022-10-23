<html>
    <head>
        <meta charset="utf-8">
        <title>Анализатор страниц</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

    <body class="min-vh-100 d-flex flex-column">
        <header class="flex-shrink-0">
            <nav class="navbar navbar-expand-md navbar-dark bg-dark">
                <a class="navbar-brand" href="{{ route('welcome') }}">Анализатор страниц</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link 
                            {{ request()->routeIs('welcome') ? 'active' : '' }}
                            " href="{{ route('welcome') }}">Главная</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link
                            {{ request()->routeIs('urls.show', 'urls.index') ? 'active' : '' }}
                            "href="{{ route('urls.index') }}">Сайты</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        @include('flash::message')
        @yield('content')

    </body>
</html>