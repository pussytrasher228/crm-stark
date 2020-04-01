<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery-ui.theme.min.css') }}" rel="stylesheet">
    <script src="{{ asset('css/jquery-ui.structure.min.css') }}"></script>
    <link href="{{ asset('css/cust.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <script src="{{ asset('js/modernizr.custom.js') }}"></script>
{{--    <link href="{{ asset('css/demo.css') }}" rel="stylesheet">--}}
    <link href="{{ asset('css/component.css') }}" rel="stylesheet">
    <link href="{{ asset('css/normalize.css') }}" rel="stylesheet">



</head>
<body>
<div class="container">
    <ul id="gn-menu" class="gn-menu-main">
        <li class="gn-trigger">
            <a class="menu-bars">
                <i class="fa fa-bars"></i>
                <span>Меню</span>
            </a>
            <nav class="gn-menu-wrapper">
                <div class="gn-scroller">
                    <ul class="gn-menu">
                        <li class="gn-search-item">
                            <input placeholder="Поиск" type="search" class="gn-search">
                            <a href="#">
                                <i class="fa fa-search"></i>
                                <span>Поиск</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa fa-download"></i> Неразобранное
                            </a>
                            <ul class="gn-submenu">
                                <li>
                                    <a href="#">
                                        <i class="fa fa-file-pdf-o"></i> В работе
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-file-word-o"></i> Продано
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa fa-cog"></i> В работе
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa fa-question-circle"></i> Продано
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa fa-archive"></i> Архив
                            </a>
{{--                            <ul class="gn-submenu">--}}
{{--                                <li>--}}
{{--                                    <a href="#">--}}
{{--                                        <i class="fa fa-file-text-o"></i> Статьи--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li>--}}
{{--                                    <a href="#">--}}
{{--                                        <i class="fa fa-file-image-o"></i> Картинки--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li>--}}
{{--                                    <a href="#">--}}
{{--                                        <i class="fa fa-file-video-o"></i> Видео--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
                        </li>
                    </ul>
                </div>
                <!-- /gn-scroller -->
            </nav>
        </li>
        <li><a href="#">АНПРОЕКТ</a></li>
    </ul>

    <header>
        @yield('content')
    </header>

</div>

<script src="{{ asset('js/classie.js') }}"></script>
<script src="{{ asset('js/gnmenu.js') }}"></script>

<script>
    new gnMenu(document.getElementById('gn-menu'));
</script>
</body>
</html>