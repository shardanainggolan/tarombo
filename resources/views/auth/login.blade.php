<!DOCTYPE html>

<html 
    lang="id-ID" 
    class="light-style layout-wide customizer-hide" 
    dir="ltr" 
    data-theme="theme-semi-dark"
    data-template="vertical-menu-template-semi-dark">

<head>
    <meta charset="utf-8" />
    <meta 
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Admin Login - Yayasan Scriptura Indonesia</title>

    <meta name="description" content="Admin Login - Yayasan Scriptura Indonesia" />

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" 
    />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('admin/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('admin/css/theme-semi-dark.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('admin/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('admin/css/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/css/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/css/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/css/form-validation.css') }}" />
    
    <link rel="stylesheet" href="{{ asset('admin/css/page-auth.css') }}" />

</head>

<body>
    <div class="authentication-wrapper authentication-cover authentication-bg">
        <div class="authentication-inner row">
            <div class="d-none d-lg-flex col-lg-7 p-0">
                <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
                    <img 
                        src="{{ asset('images/logo/logo.png') }}" 
                        alt="auth-login-cover"
                        class="img-fluid my-5 auth-illustration"
                        data-app-light-img="{{ asset('images/logo/logo.png') }}"
                        data-app-dark-img="{{ asset('images/logo/logo.png') }}" 
                    />

                    <img 
                        src="{{ asset('images/bg-shape-image-light.png') }}" 
                        alt="auth-login-cover"
                        class="platform-bg" 
                        data-app-light-img="{{ asset('images/bg-shape-image-light.png') }}"
                        data-app-dark-img="{{ asset('images/bg-shape-image-dark.png') }}" 
                    />
                </div>
            </div>
            
            <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
                <div class="w-px-400 mx-auto">
                    <div class="app-brand mb-4">
                        <a href="{{ route('login') }}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo" style="width: 140px; height: 80px;">
                                <img 
                                    src="{{ asset('images/logo/logo.png') }}"
                                    style="width: 80px; height: 80px;"
                                    alt="Yayasan Scriptura Indonesia"
                                />
                            </span>
                        </a>
                    </div>
                    
                    <h1 class="h3 mb-1">Yayasan Scriptura CMS</h1>

                    <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="email" 
                                name="email"
                                placeholder="Email" 
                                autofocus 
                                value="{{ old('email') }}"
                                autocomplete="username"
                            />
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">Password</label>
                            </div>
                            <div class="input-group input-group-merge">
                                <input 
                                    type="password" 
                                    id="password" 
                                    class="form-control" 
                                    name="password"
                                    autocomplete="current-password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" 
                                />
                                <span class="input-group-text cursor-pointer">
                                    <i class="ti ti-eye-off"></i>
                                </span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary d-grid w-100">
                            Masuk
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('admin/js/jquery.js') }}"></script>
    <script src="{{ asset('admin/js/popper.js') }}"></script>
    <script src="{{ asset('admin/js/bootstrap.js') }}"></script>
    <script src="{{ asset('admin/js/node-waves.js') }}"></script>
    <script src="{{ asset('admin/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('admin/js/hammer.js') }}"></script>
    <script src="{{ asset('admin/js/i18n.js') }}"></script>
    <script src="{{ asset('admin/js/typeahead.js') }}"></script>
    <script src="{{ asset('admin/js/menu.js') }}"></script>

    <!-- Vendors JS -->
    <script src="{{ asset('admin/js/form-validation/popular.js') }}"></script>
    <script src="{{ asset('admin/js/form-validation/bootstrap5.js') }}"></script>
    <script src="{{ asset('admin/js/form-validation/auto-focus.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('admin/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('admin/js/pages-auth.js') }}"></script>

</body>

</html>
