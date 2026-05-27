<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="light" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Dashboard') | Mifty Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="AI Interview Admin Dashboard" name="description" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .input {
            width: 100%;
            border: 1px solid #dbe3f1;
            border-radius: 10px;
            padding: 10px 12px;
            background: #fff;
        }
        .btn.ghost {
            background: transparent;
            border: 1px solid #cbd5e1;
            color: #334155;
        }
        .muted {
            color: #64748b;
        }
        .stat-card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            background: #fff;
            padding: 14px;
        }
        .stat-label {
            font-size: 13px;
            color: #64748b;
        }
        .stat-value {
            font-size: 30px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
        }
    </style>
    @stack('head')
</head>
<body>
    <div class="topbar d-print-none">
        <div class="container-fluid">
            <nav class="topbar-custom d-flex justify-content-between" id="topbar-custom">
                <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
                    <li>
                        <button class="nav-link mobile-menu-btn nav-icon" id="togglemenu">
                            <i class="iconoir-menu"></i>
                        </button>
                    </li>
                    <li class="mx-2 welcome-text">
                        <h5 class="mb-0 fw-semibold text-truncate">AI Interview Admin</h5>
                    </li>
                </ul>

                <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
                    <li class="topbar-item">
                        <a class="nav-link nav-icon" href="javascript:void(0);" id="light-dark-mode">
                            <i class="iconoir-half-moon dark-mode"></i>
                            <i class="iconoir-sun-light light-mode"></i>
                        </a>
                    </li>
                    <li class="dropdown topbar-item">
                        <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="#" role="button"
                            aria-haspopup="false" aria-expanded="false" data-bs-offset="0,19">
                            <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="" class="thumb-md rounded-circle">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end py-0">
                            <div class="d-flex align-items-center dropdown-item py-2 bg-secondary-subtle">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="" class="thumb-md rounded-circle">
                                </div>
                                <div class="flex-grow-1 ms-2 text-truncate align-self-center">
                                    <h6 class="my-0 fw-medium text-dark fs-13">{{ auth()->user()->name ?? 'Admin User' }}</h6>
                                    <small class="text-muted mb-0">{{ auth()->user()->email ?? 'admin@example.com' }}</small>
                                </div>
                            </div>
                            <div class="dropdown-divider mb-0"></div>
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button class="dropdown-item text-danger" type="submit">
                                    <i class="las la-power-off fs-18 me-1 align-text-bottom"></i> Logout
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <div class="startbar d-print-none">
        <div class="brand">
            <a href="{{ route('admin.dashboard') }}" class="logo">
                <span>
                    <img src="{{ asset('assets/images/logo-sm.png') }}" alt="logo-small" class="logo-sm">
                </span>
                <span class="">
                    <img src="{{ asset('assets/images/logo-light.png') }}" alt="logo-large" class="logo-lg logo-light">
                    <img src="{{ asset('assets/images/logo-dark.png') }}" alt="logo-large" class="logo-lg logo-dark">
                </span>
            </a>
        </div>

        <div class="startbar-menu">
            <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
                <div class="d-flex align-items-start flex-column w-100">
                    <ul class="navbar-nav mb-auto w-100">
                        <li class="menu-label mt-2"><span>Navigation</span></li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                               href="{{ route('admin.dashboard') }}">
                                <i class="iconoir-report-columns menu-icon"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}"
                               href="{{ route('admin.students.index') }}">
                                <i class="iconoir-user menu-icon"></i>
                                <span>Students</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                               href="{{ route('admin.categories.index') }}">
                                <i class="iconoir-tag menu-icon"></i>
                                <span>Categories</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.questions.*') ? 'active' : '' }}"
                               href="{{ route('admin.questions.index') }}">
                                <i class="iconoir-question-mark menu-icon"></i>
                                <span>Questions</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.answers.*') ? 'active' : '' }}"
                               href="{{ route('admin.answers.index') }}">
                                <i class="iconoir-chat-bubble menu-icon"></i>
                                <span>Answers</span>
                            </a>
                        </li>
                    </ul>

                    <div class="update-msg text-center">
                        <h5 class="mt-1">AI Interview</h5>
                        <p class="mb-3 text-muted">Manage interview topics, questions, and answers from one place.</p>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="btn bg-black text-white shadow-sm rounded-pill">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="startbar-overlay d-print-none"></div>

    <div class="page-wrapper">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                            <h4 class="page-title">@yield('title', 'Dashboard')</h4>
                            <div>
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Mifty</a></li>
                                    <li class="breadcrumb-item active">@yield('title', 'Dashboard')</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                @yield('content')
            </div>

            <footer class="footer text-center text-sm-start d-print-none">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-0 rounded-bottom-0">
                                <div class="card-body">
                                    <p class="text-muted mb-0">
                                        © <script>document.write(new Date().getFullYear())</script> AI Interview Admin
                                        <span class="text-muted d-none d-sm-inline-block float-end">
                                            Built with <i class="iconoir-heart-solid text-danger align-middle"></i> Mifty theme
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @stack('body')
</body>
</html>

