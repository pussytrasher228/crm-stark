<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>STARKUS</title>

    <!-- fontawesome icon -->
    <link rel="stylesheet" href="{{ asset("assets/fonts/fontawesome/css/fontawesome-all.min.css") }}">

    <!-- animation css -->
    <link rel="stylesheet" href="{{ asset("assets/plugins/animation/css/animate.min.css") }}">

    <!-- vendor css -->
    <link rel="stylesheet" href="{{ asset("assets/css/style.css") }}">

    <!-- datepicker css -->
    <link rel="stylesheet" href="{{ asset("assets/plugins/bootstrap-datetimepicker/css/bootstrap-datepicker3.min.css") }}">
    <style>
        .datepicker > .datepicker-days {
            display: block;
        }

        ol.linenums {
            margin: 0 0 0 -8px;
        }

    </style>

    <!-- select2 css -->
    <link rel="stylesheet" href="{{ asset("assets/plugins/select2/css/select2.min.css") }}">
    <!-- multi-select css -->
    <link rel="stylesheet" href="{{ asset("assets/plugins/multi-select/css/multi-select.css") }}">

    <!-- material datetimepicker css -->
    <link rel="stylesheet" href="{{ asset("assets/plugins/material-datetimepicker/css/bootstrap-material-datetimepicker.css") }}">
    <!-- Bootstrap datetimepicker css -->
    <link rel="stylesheet" href="{{ asset("assets/plugins/bootstrap-datetimepicker/css/bootstrap-datepicker3.min.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/fonts/material/css/materialdesignicons.min.css") }}">
    <!-- minicolors css -->
    <link rel="stylesheet" href="{{ asset("assets/plugins/mini-color/css/jquery.minicolors.css") }}">

    <!-- Required Js -->
    <script src="{{ asset("assets/js/vendor-all.min.js") }}"></script>

    {{-- Suggestions --}}
    <link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@19.8.0/dist/css/suggestions.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@19.8.0/dist/js/jquery.suggestions.min.js"></script>
</head>
<body>
<!-- [ Pre-loader ] start -->
<div class="loader-bg">
    <div class="loader-track">
        <div class="loader-fill"></div>
    </div>
</div>
<!-- [ Pre-loader ] End -->

<!-- Модальное окно -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Выбрать дату оплаты</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="tue">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="date">Дата оплаты</label>
                    <input type="text" class="form-control date-picker income-date-picker"
                           route="{{route('income.get_income_date')}}" token="{{csrf_token()}}" name="date"
                           id="date" value="{{ old('date', (new \Carbon\Carbon())->format('d-m-Y')) }}">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success income-data" type="submit">Оплачено</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>

<!-- [ Modal ] Start -->
<div class="modal fade" id="act" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-account_number"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="tue">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <h5 for="date">Клиент</h5>
                    <p class="modal-client"></p>
                </div>
                <hr>
                <div class="form-group">
                    <h5 for="date">Описание</h5>
                    <p class="modal-comment"></p>
                </div>
                <hr>
                <div class="form-group">
                    <h5 for="date">Дата оплаты</h5>
                    <p class="modal-date"></p>
                </div>
                <hr>
                <div class="form-group">
                    <h5 for="date">Планируемая дата оплаты	</h5>
                    <p class="modal-plan-date"></p>
                </div>
                <hr>
                <div class="form-group">
                    <h5 for="date">Услуга</h5>
                    <p class="modal-service"></p>
                </div>
                <hr>
                <div class="form-group">
                    <h5 for="date">Получатель</h5>
                    <p class="modal-pay_service"></p>
                </div>
                <hr>
                <div class="form-group">
                    <h5 for="date">Товар</h5>
                    <div class="modal-product" id="modal-product">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- [ Modal ] End -->

<!-- [ Navigation menu ] start -->
<nav class="pcoded-navbar navbar-collapsed menupos-fixed">
    <div class="navbar-wrapper">

        <!-- put Logo is hear -->
        <div class="navbar-brand header-logo">
            <a href="/" class="b-brand">
                <div class="b-bg">
                    <img src="{{ asset("assets/images/mini-logo-transparent.svg") }}" width="22px">
                </div>
                <span class="b-title">STARKUS</span>
            </a>
            <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
        </div>
        <!-- end Logo is hear -->

        <div class="navbar-content scroll-div">
            <ul class="nav pcoded-inner-navbar">
                <li class="nav-item">
                    <a href="{{ route('companies.show') }}">
                        <span class="pcoded-micon">
                            <i class="fas fa-money-bill"></i>
                        </span>
                        <span class="pcoded-mtext">Оплаты</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('project') }}">
                        <span class="pcoded-micon">
                            <i class="fas fa-clipboard-check"></i>
                        </span>
                        <span class="pcoded-mtext">Проекты</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('act.show') }}">
                        <span class="pcoded-micon">
                            <i class="fas fa-file-contract"></i>
                        </span>
                        <span class="pcoded-mtext">Акты</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('regularClients.show') }}">
                        <span class="pcoded-micon">
                            <i class="fas fa-money-bill-wave"></i>
                        </span>
                        <span class="pcoded-mtext">Регулярные оплаты</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('companiesTotal.show') }}" class="">
                        <span class="pcoded-micon">
                            <i class="fas fa-users"></i>
                        </span>
                        <span class="pcoded-mtext">Клиенты</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('registerAct.show') }}">
                        <span class="pcoded-micon">
                            <i class="fas fa-file-archive"></i>
                        </span>
                        <span class="pcoded-mtext">Реестр договоров</span>
                    </a>
                </li>

                @can('admin')
                    <li class="nav-item">
                        <a href="{{ route('companiesExprenses.show') }}">
                        <span class="pcoded-micon">
                            <i class="fas fa-money-check"></i>
                        </span>
                            <span class="pcoded-mtext">Расходы</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('companiesAnalytics.show') }}" class="">
                        <span class="pcoded-micon">
                            <i class="fas fa-globe"></i>
                        </span>
                            <span class="pcoded-mtext">Аналитика</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin') }}">
                        <span class="pcoded-micon">
                            <i class="fas fas fa-cogs"></i>
                        </span>
                            <span class="pcoded-mtext">Настройки</span>
                        </a>
                    </li>
                @endcan
                <li class="nav-item pcoded-menu-caption text-center">
                    <label>Техническая поддержка <br/><a href="mailto:support@stark-media.ru">support@stark-media.ru</a></label>
                </li>

            </ul>
        </div>
    </div>
</nav>
<!-- [ Navigation menu ] end -->

<!-- [ Header ] start -->
<header class="navbar pcoded-header navbar-expand-lg navbar-light headerpos-fixed">
    <div class="m-header">
        <a class="mobile-menu" id="mobile-collapse1" href="#!"><span></span></a>
        <a href="/" class="b-brand">
            <div class="b-bg">
                <img src="{{ asset("assets/images/mini-logo-transparent.svg") }}" width="22px">
            </div>
            <span class="b-title">STARKUS</span>
        </a>
    </div>
    <a class="mobile-menu" id="mobile-header" href="#!">
        <i class="feather icon-more-horizontal"></i>
    </a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
            {{-- Left navbar --}}
            <li>
                # Home
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            {{-- Right navbar --}}
            <li>
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" data-toggle="dropdown"><i class="icon feather icon-bell"></i></a>
                    <div class="dropdown-menu dropdown-menu-right notification">
                        <div class="noti-head">
                            <h6 class="d-inline-block m-b-0">Notifications</h6>
                            <div class="float-right">
                                <a href="#!" class="m-r-10">mark as read</a>
                                <a href="#!">clear all</a>
                            </div>
                        </div>
                        <ul class="noti-body ps">
                            <li class="n-title">
                                <p class="m-b-0">NEW</p>
                            </li>
                            <li class="notification">
                                <div class="media">
                                    <img class="img-radius" src="../assets/images/user/avatar-1.jpg" alt="Generic placeholder image">
                                    <div class="media-body">
                                        <p><strong>John Doe</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>5 min</span></p>
                                        <p>New ticket Added</p>
                                    </div>
                                </div>
                            </li>
                            <li class="n-title">
                                <p class="m-b-0">EARLIER</p>
                            </li>
                            <li class="notification">
                                <div class="media">
                                    <img class="img-radius" src="../assets/images/user/avatar-2.jpg" alt="Generic placeholder image">
                                    <div class="media-body">
                                        <p><strong>Joseph William</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>10 min</span></p>
                                        <p>Prchace New Theme and make payment</p>
                                    </div>
                                </div>
                            </li>
                            <li class="notification">
                                <div class="media">
                                    <img class="img-radius" src="../assets/images/user/avatar-3.jpg" alt="Generic placeholder image">
                                    <div class="media-body">
                                        <p><strong>Sara Soudein</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>12 min</span></p>
                                        <p>currently login</p>
                                    </div>
                                </div>
                            </li>
                            <li class="notification">
                                <div class="media">
                                    <img class="img-radius" src="../assets/images/user/avatar-1.jpg" alt="Generic placeholder image">
                                    <div class="media-body">
                                        <p><strong>Joseph William</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>30 min</span></p>
                                        <p>Prchace New Theme and make payment</p>
                                    </div>
                                </div>
                            </li>
                            <li class="notification">
                                <div class="media">
                                    <img class="img-radius" src="../assets/images/user/avatar-3.jpg" alt="Generic placeholder image">
                                    <div class="media-body">
                                        <p><strong>Sara Soudein</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>1 hour</span></p>
                                        <p>currently login</p>
                                    </div>
                                </div>
                            </li>
                            <li class="notification">
                                <div class="media">
                                    <img class="img-radius" src="../assets/images/user/avatar-1.jpg" alt="Generic placeholder image">
                                    <div class="media-body">
                                        <p><strong>Joseph William</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>2 hour</span></p>
                                        <p>Prchace New Theme and make payment</p>
                                    </div>
                                </div>
                            </li>
                            <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></ul>
                        <div class="noti-footer">
                            <a href="#!">show all</a>
                        </div>
                    </div>
                </div>
            </li>
            @guest
            @else
            <li>
                <div class="dropdown drp-user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i class="icon feather icon-settings"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-notification">
                        <div class="pro-head">
                            <span>{{ Auth::user()->name }}</span>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                               class="dud-logout"
                               title="Logout">
                                <i class="feather icon-log-out"></i>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </li>
            @endguest
        </ul>
    </div>
</header>
<!-- [ Header ] end -->


<!-- [ Main Content ] start -->
<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="pcoded-inner-content">
                <div class="main-body">
                    <div class="page-wrapper">
                        <!-- [ Main Content ] start -->
                        <div class="container-fluid">
                            @yield("content")
                        </div>
                        <!-- [ Main Content ] end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->

<!-- Warning Section Starts -->
<!-- Older IE warning message -->
<!--[if lt IE 11]>
<div class="ie-warning">
    <h1>Warning!!</h1>
    <p>You are using an outdated version of Internet Explorer, please upgrade
        <br/>to any of the following web browsers to access this website.
    </p>
    <div class="iew-container">
        <ul class="iew-download">
            <li>
                <a href="http://www.google.com/chrome/">
                    <img src="/assets/images/browser/chrome.png" alt="Chrome">
                    <div>Chrome</div>
                </a>
            </li>
            <li>
                <a href="https://www.mozilla.org/en-US/firefox/new/">
                    <img src="/assets/images/browser/firefox.png" alt="Firefox">
                    <div>Firefox</div>
                </a>
            </li>
            <li>
                <a href="http://www.opera.com">
                    <img src="/assets/images/browser/opera.png" alt="Opera">
                    <div>Opera</div>
                </a>
            </li>
            <li>
                <a href="https://www.apple.com/safari/">
                    <img src="/assets/images/browser/safari.png" alt="Safari">
                    <div>Safari</div>
                </a>
            </li>
            <li>
                <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                    <img src="/assets/images/browser/ie.png" alt="">
                    <div>IE (11 & above)</div>
                </a>
            </li>
        </ul>
    </div>
    <p>Sorry for the inconvenience!</p>
</div>
<![endif]-->
<!-- Warning Section Ends -->

<!-- Required Js -->
<script src="{{ asset("assets/plugins/bootstrap/js/bootstrap.min.js") }}"></script>
<script src="{{ asset("assets/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js") }}"></script>
<script src="{{ asset("assets/js/pcoded.min.js") }}"></script>
<script src="{{ asset("assets/js/custom.js") }}"></script>
<script src="{{ asset("assets/plugins/select2/js/select2.full.min.js") }}"></script>
<script src="{{ asset("assets/plugins/multi-select/js/jquery.quicksearch.js") }}"></script>
<script src="{{ asset("assets/plugins/multi-select/js/jquery.multi-select.js") }}"></script>
{{--<script src="{{ asset("assets/js/pages/ac-datepicker.js") }}"></script>--}}
<script src="http://momentjs.com/downloads/moment-with-locales.min.js"></script>
<script src="{{ asset("assets/plugins/material-datetimepicker/js/bootstrap-material-datetimepicker.js") }}"></script>
<script src="{{ asset("assets/plugins/mini-color/js/jquery.minicolors.min.js") }}"></script>
<script src="{{ asset("assets/js/pages/form-picker-custom.js") }}"></script>
<script src="{{ asset("assets/js/pages/form-select-custom.js") }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>

</body>
</html>
