<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>DTS</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css"> -->

    <!-- Fontawesome -->
    <!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous"> -->

    <!-- Datatables -->
    <!-- <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css"> -->
    <link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css')}} ">

    <!-- <script src="//code.jquery.com/jquery.js"></script> -->
    <script src="{{asset('js/jquery.js')}}"></script>

    <!-- <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script> -->
    <script src="{{ asset('js/jquery.dataTables.min.js')}}"></script>

    <!-- <script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script> -->
    <script src="{{ asset('js/bootstrap.min.js')}}"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- icon -->
    <link rel="shortcut icon" href="{{ asset('img/csflu.ico') }}">

    <!-- datetimepicker -->
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet"> -->
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> -->

</head>
<body>
    <div id="app">
        <!-- <nav class="navbar navbar-expand-md navbar-light navbar-laravel"> #1565c0;-->
        <nav class="navbar navbar-expand-md navbar-dark sidenav" style="background-color: #1565c0;">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/home') }}">
                    @guest
                        Document Tracking System
                    @else
                        DTS
                    @endguest
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                @guest
                @else
                    @if(Auth::user()->access=="A")
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav mr-auto">
                            
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('new_document') }}">{{ __('New Document') }}</a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('transaction') }}">{{ __('Transaction') }}</a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('archive') }}">{{ __('Archive') }}</a>
                            </li>
                            
                            <li class="nav-item dropdown">
                                <!-- <a class="nav-link" href="{{ route('login') }}">{{ __('Report') }}</a> -->
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Report <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('report_custom') }}">
                                        {{ __('Custom Report') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('office_performance') }}">
                                        {{ __('Office Performance') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('summary') }}">
                                        {{ __('Summary') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('office_transaction_summary') }}">
                                        {{ __('Office Transaction Summary') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('unended_transaction') }}">
                                        {{ __('Unended Transactions') }}
                                    </a>
                                </div>
                            </li>
                            
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Settings <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('office_entry') }}">
                                        {{ __('Office Entry') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('performance_standards') }}">
                                        {{ __('Office Performance Standards') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('document_type_entry') }}">
                                        {{ __('Document Type') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('delivery_method_entry') }}">
                                        {{ __('Delivery Method') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('transaction_type_entry') }}">
                                        {{ __('Transaction Type') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('holidays') }}">
                                        {{ __('Holidays') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('reactivate_ended_transaction') }}">
                                        {{ __('Reactivate Ended Transactions') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('correct_release_route') }}">
                                        {{ __('Correct Release Route') }}
                                    </a>
                                </div>
                            </li>
                            
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        User Management <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('create_user') }}">
                                        {{ __('Create Users') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('change_password') }}">
                                        {{ __('Change Password') }}
                                    </a>
                                </div>
                            </li>
            
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                                @if (Route::has('register'))
                                    <!-- <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li> -->
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ ucwords(Auth::user()->name) }} <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                    @elseif(Auth::user()->access=="R")
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav mr-auto">
                            
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('new_document') }}">{{ __('New Document') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('transaction') }}">{{ __('Transaction') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('archive') }}">{{ __('Archive') }}</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Previous Archive') }}</a>
                            </li> -->
                            <li class="nav-item dropdown">
                                <!-- <a class="nav-link" href="{{ route('login') }}">{{ __('Report') }}</a> -->
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Report <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('report_custom') }}">
                                        {{ __('Custom Report') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('office_performance') }}">
                                        {{ __('Office Performance') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('summary') }}">
                                        {{ __('Summary') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('office_transaction_summary') }}">
                                        {{ __('Office Transaction Summary') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('unended_transaction') }}">
                                        {{ __('Unended Transactions') }}
                                    </a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Settings <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    
                                    <a class="dropdown-item" href="{{ route('reactivate_ended_transaction') }}">
                                        {{ __('Reactivate Ended Transactions') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('correct_release_route') }}">
                                        {{ __('Correct Release Route') }}
                                    </a>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('change_password') }}">{{ __('Change Password') }}</a>
                            </li>
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                                @if (Route::has('register'))
                                    <!-- <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li> -->
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ ucwords(Auth::user()->name) }} <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                    @else
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('transaction') }}">{{ __('Transaction') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('archive') }}">{{ __('Archive') }}</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Previous Archive') }}</a>
                            </li> -->
                            <li class="nav-item dropdown">
                                <!-- <a class="nav-link" href="{{ route('login') }}">{{ __('Report') }}</a> -->
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Report <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('report_custom') }}">
                                        {{ __('Custom Report') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('office_performance') }}">
                                        {{ __('Office Performance') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('summary') }}">
                                        {{ __('Summary') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('office_transaction_summary') }}">
                                        {{ __('Office Transaction Summary') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('unended_transaction') }}">
                                        {{ __('Unended Transactions') }}
                                    </a>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('change_password') }}">{{ __('Change Password') }}</a>
                            </li>
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                                @if (Route::has('register'))
                                    <!-- <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li> -->
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ ucwords(Auth::user()->name) }} <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>

                    @endif

                @endguest
            </div>

        </nav>
        @if(Session::has('flash_message_success'))
            <div class="alert alert-success alert-dismissible flash_alert" style="width: 400px; left: 60%;
  top: 55px; position: absolute; z-index: 100;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Success!</strong>
                {{Session::get('flash_message_success')}}
                
            </div>
        @endif
        @if(Session::has('flash_message_error'))
            <div class="alert alert-danger alert-dismissible flash_alert" style="width: 400px; left: 60%;
  top: 55px; position: absolute; z-index: 100;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Error!</strong>
                {{Session::get('flash_message_error')}}
                
            </div>
        @endif

        

        <main class="py-4">
            @yield('content')

            @yield('myScript')
        </main>
    </div>


</body>

</html>




