<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ (getUserSetting('project_name') ?? config('app.name')) }} - Login</title>

    @if (getUserSetting('favicon'))
        <link rel="icon" href="{{ getSettingAssetUrl(getUserSetting('favicon')) }}">
    @endif

    <link href="{{ asset('backend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/style.css') }}" rel="stylesheet">

    <style>
        body.gray-bg {
            background-color: #f3f3f4;
        }
        .login-logo-box {
            background: #1ab394;
            border-radius: 8px 8px 0 0;
            padding: 22px 20px 18px;
            text-align: center;
        }
        .login-logo-box img {
            max-height: 60px;
            max-width: 220px;
            width: auto;
            height: auto;
            border-radius: 4px;
            object-fit: contain;
        }
        .login-logo-text {
            color: #fff;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin: 0;
            line-height: 1.2;
        }
        .login-logo-tagline {
            color: rgba(255,255,255,0.75);
            font-size: 12px;
            margin-top: 3px;
        }
        .login-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border-radius: 8px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.12);
            overflow: hidden;
            background: #fff;
        }
        .login-card-body {
            padding: 30px 30px 25px;
        }
        .login-card-body h4 {
            margin-bottom: 20px;
            color: #676a6c;
            font-weight: 600;
        }
        .login-card-body .form-control {
            height: 40px;
            border-radius: 4px;
        }
        .login-card-body .btn-primary {
            background: #1ab394;
            border-color: #1ab394;
            width: 100%;
            height: 40px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 4px;
            margin-top: 8px;
        }
        .login-card-body .btn-primary:hover {
            background: #18a689;
            border-color: #18a689;
        }
        .form-group label {
            font-weight: 600;
            color: #676a6c;
            font-size: 13px;
        }
        .alert-danger {
            border-radius: 4px;
            font-size: 13px;
        }
        .login-footer {
            background: #f7f7f7;
            border-top: 1px solid #e7eaec;
            padding: 12px 20px;
            text-align: center;
            color: #999;
            font-size: 12px;
        }
    </style>
</head>
<body class="gray-bg">

    <div class="login-wrapper">
        <div class="login-card animated fadeInDown">

            {{-- Logo / Project Name --}}
            <div class="login-logo-box">
                @if(getUserSetting('logo'))
                    <img src="{{ getSettingAssetUrl(getUserSetting('logo')) }}"
                         alt="{{ getUserSetting('project_name') }}">
                @else
                    <img src="{{ asset('backend/img/default_logo.png') }}"
                         alt="{{ getUserSetting('project_name') }}">
                @endif

                @if(getUserSetting('project_name'))
                    <p class="login-logo-text" style="margin-top: 10px;">
                        {{ getUserSetting('project_name') }}
                    </p>
                @endif

                @if(getUserSetting('project_tagline'))
                    <p class="login-logo-tagline">
                        {{ getUserSetting('project_tagline') }}
                    </p>
                @endif
            </div>

            {{-- Login Form --}}
            <div class="login-card-body">
                <h4>Welcome Back
                    @if(getUserSetting('project_name'))
                        <small class="text-muted" style="font-size: 13px; font-weight: 400; display: block; margin-top: 3px;">
                            {{ getUserSetting('project_name') }}
                        </small>
                    @endif
                </h4>

                {{-- Session Status --}}
                @if(session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                {{-- Validation Errors --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input id="email"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="form-control"
                               placeholder="Enter your email"
                               required
                               autofocus
                               autocomplete="username">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password"
                               type="password"
                               name="password"
                               class="form-control"
                               placeholder="Enter your password"
                               required
                               autocomplete="current-password">
                    </div>

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="font-weight: 400; cursor: pointer;">
                            <input type="checkbox" name="remember" id="remember_me">
                            &nbsp;Remember me
                        </label>

                        {{-- @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               style="float: right; font-size: 12px; color: #1ab394; margin-top: 2px;">
                                Forgot password?
                            </a>
                        @endif --}}
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fa fa-sign-in"></i> &nbsp;Log In
                    </button>
                </form>
            </div>

            <div class="login-footer">
                &copy; {{ date('Y') }} {{ getUserSetting('project_name') ?? config('app.name') }}. All rights reserved.
            </div>
        </div>
    </div>

    <script src="{{ asset('backend/js/jquery/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('backend/js/bootstrap/bootstrap.min.js') }}"></script>
</body>
</html>
