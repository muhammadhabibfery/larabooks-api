<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laravel Shop @yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Another Links -->
    @stack('my_styles')

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('polished/polished.min.css') }}">
    <link rel="stylesheet" href="{{ asset('polished/iconic/css/open-iconic-bootstrap.min.css') }}">
    <style>
        .grid-highlight {
            padding-top: 1rem;
            padding-bottom: 1rem;
            background-color: #5c6ac4;
            border: 1px solid #202e78;
            color: #fff;
        }

        hr {
            margin: 6rem 0;
        }

        hr+.display-3,
        hr+.display-2+.display-3 {
            margin-bottom: 2rem;
        }
    </style>

    <!-- Scripts -->
    <script type="text/javascript">
        document.documentElement.className = document.documentElement.className.replace('no-js', 'js') +
            (document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature# BasicStructure", "1.1") ? ' svg' :
                ' no-svg');

    </script>
</head>

<body>
    <nav class="navbar navbar-expand p-0">
        <a href="{{ route('dashboard') }}"
            class="navbar-brand text-center col-xs-12 col-md-3 col-lg-2 mr-0">Larashop</a>
        <button class="btn btn-link d-block d-md-none" datatoggle="collapse" data-target="#sidebar-nav" role="button">
            <span class="oi oi-menu"></span>
        </button>
        <div class="dropdown d-none d-md-block ml-auto mr-4">
            @if (auth()->user())
            <button class="btn btn-link btn-link-primary dropdown-toggle" id="navbar-dropdown" data-toggle="dropdown">
                {{ auth()->user()->name }}
            </button>
            @endif
            <div class="dropdown-menu dropdown-menu-right" id="navbardropdown">
                <a href="{{ route('profiles.edit') }}"
                    class="dropdown-item{{ request()->is('profile/edit*') ? ' active' : '' }}">
                    Profile
                </a>
                <a href="{{ route('profiles.password.edit') }}"
                    class="dropdown-item{{ request()->is('profile/password*') ? ' active' : '' }}">Password</a>
                <div class="dropdown-divider"></div>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="dropdown-item" style="cursor:pointer">Sign Out</button>
                    </form>
                </li>
            </div>
        </div>
    </nav>

    <div class="container-fluid h-100 p-0">
        <div style="min-height: 100%" class="flex-row d-flex align-itemsstretch m-0">
            <div class="polished-sidebar bg-light col-12 col-md-3 col-lg-2 p-0 collapse d-md-inline" id="sidebar-nav">

                <ul class="polished-sidebar-menu ml-0 pt-4 p-0 d-md-block">
                    <input class="border-dark form-control d-block d-md-none mb-4" type="text" placeholder="Search"
                        aria-label="Search" />
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="{{ request()->is('admin/dashboard*') ? 'font-italic fw-bold' : '' }}">
                            <span class="oi oi-home"></span> Dashboard
                        </a>
                    </li>
                    @isAdmin(request()->user()->role)
                    <li>
                        <a href="{{ route('users.index') }}"
                            class="{{ request()->is('admin/users*') ? 'font-italic fw-bold' : '' }}">
                            <span class="oi oi-people"></span> Manage users
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ route('categories.index') }}"
                            class="{{ request()->is('admin/categories*') ? 'font-italic fw-bold' : '' }}">
                            <span class="oi oi-tag"></span> Manage categories
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('books.index') }}"
                            class="{{ request()->is('admin/books*') ? 'font-italic fw-bold' : '' }}">
                            <span class="oi oi-book"></span> Manage books
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('orders.index') }}"
                            class="{{ request()->is('admin/orders*') ? 'font-italic fw-bold' : '' }}">
                            <span class="oi oi-inbox"></span> Manage orders
                        </a>
                    </li>
                    <div class="d-block d-md-none">
                        <div class="dropdown-divider"></div>
                        <li><a href="#"> Profile</a></li>
                        <li><a href="#"> Setting</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item" style="cursor:pointer">Sign Out</button>
                            </form>
                        </li>
                    </div>
                </ul>
                <div class="pl-3 d-none d-md-block position-fixed" style="bottom: 0px">
                    <span class="oi oi-cog"></span> Setting
                </div>
            </div>
            <div class="col-lg-10 col-md-9 p-4">
                <div class="row ">
                    <div class="col-md-12 pl-3 pt-2">
                        <div class="pl-3">
                            <h3>@yield("pageTitle")</h3>
                            <br>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    @yield("content")
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"
        integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"
        integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        @if (session('success'))
            toastr.options.positionClass = "toast-top-center";
            toastr.options.progressBar = true;
            toastr.options.showDuration = 500;
            toastr.success("{{ session('success') }}", 'Success');
        @elseif (session('failed'))
            toastr.options.positionClass = "toast-top-center";
            toastr.options.progressBar = true;
            toastr.options.showDuration = 500;
            toastr.error("{{ session('failed') }}", 'Failed');
        @endif

        document.addEventListener('click', function(e) {
            if (e.target.id == 'btnfr') {
                const form = document.querySelector('#myfr');
                form.addEventListener('submit', function(ev) {
                    const btn = document.querySelector('#btnfr');
                    btn.innerHTML = 'Please Wait ...';
                    btn.style.fontWeight = 'bold';
                    btn.style.color = 'black';
                    btn.setAttribute('disabled', 'disabled');
                    return true;
                })
            }
        })

    </script>
    @stack('my_scripts')
</body>

</html>
