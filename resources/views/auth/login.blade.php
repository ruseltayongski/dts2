<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DOHRO7 DTS | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{ asset('resources/assets/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('resources/assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/assets/css/AdminLTE.min.css') }}">
    <link rel="icon" href="{{ asset('resources/img/favicon.png') }}">
    <script src="{{ asset('resources/assets/js/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="{{ asset('resources/assets/js/bootstrap.min.js') }}"></script>
  </head>
  <body class="hold-transition login-page">
    @if(Session::has('ok'))
        <div class="row">
            <div class="alert alert-success text-center">
                <strong class="text-center">{{ Session::get('ok') }}</strong>
            </div>
        </div>
    @endif
    <div class="login-box">
      <div class="login-logo">
          <img src="{{ asset('public/img/doh.png') }}" style="width: 30%" />
          <br />
          <a href="#" style="font-weight:bolder;"><label style="font-size: 17pt;">DOH-CVCHD DTS 5.0</label></a>
      </div><!-- /.login-logo -->
      <form role="form" method="POST" action="{{ url('/login') }}">
          {{ csrf_field() }}
          <div class="login-box-body">
                <!--
                <div class="alert alert-success">For new user: your account is<br>
                    Username: (ID NUMBER)<br>
                    Password: 123
                </div>
                -->
              <div class="form-group has-feedback {{ $errors->has('username') ? ' has-error' : '' }}">
                <input id="username" type="text" placeholder="Login ID" class="form-control" name="username" value="{{ old('username') }}">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                @if ($errors->has('username'))
                    <span class="help-block">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif
              </div>
              <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                <input id="password" type="password" class="form-control" name="password" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
              </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="form-group">
                            <label style="cursor:pointer;">
                                <input type="checkbox" name="remember"> Remember Me
                            </label>
                        </div>
                    </div><!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                    </div><!-- /.col -->
                </div> 
            </div><!-- /.login-box-body -->
            
      </form>

    @if(session()->has('successFeedback'))
        <div class="alert alert-success">
            <i class="fa fa-check"></i> {{ session()->get('successFeedback') }}
        </div>
    @else
        <form action="{{ asset('sendFeedback') }}" method="POST">
            {{ csrf_field() }}
            <div class="login-box-body">
                <div class="alert alert-info">
                    <blockquote style="text-align: justify;text-justify: inter-word; font-size: 100%">
                        If you encountered an error in document tracking system,
                        please send us feedback and suggestion. Thank you!
                    </blockquote>
                </div>
                <textarea class="form-control" name="feedback" required></textarea><br>
                <button class="btn btn-success" type="submit">Send Feedback</button>
            </div>
        </form>
    @endif
    {{--@include('modal.announcement')--}}
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    
    <!-- iCheck -->
  </body>
</html>
