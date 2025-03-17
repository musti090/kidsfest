<!DOCTYPE html>
<html>
<head>
    <title>Giriş səhifəsi | İdarə Paneli</title>
    <!--Made with love by Mutiullah Samim -->

    <!--Bootsrap 4 CDN-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!--Fontawesome CDN-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/toastr/toastr.min.css') }}">
    <!--Custom styles-->
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/assets/login/css/style.css') }}">
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-center h-100">
        <div class="card">
            <div class="card-header">
                <h3>Giriş edin</h3>
                @if( session()->has('error') )
                    <div style="background-color: #BD3730;color: white;padding:15px 15px 3px">
                        <p><strong> {{ session('error') }}</strong></p>
                    </div>
                @endif
            </div>
            <div class="card-body">
                <form id="accesspanel" action="{{ route('backend.login.submit') }}" method="post">
                    @csrf
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input type="text" class="form-control" name="email_username" id="email" value="{{ old('email_username') }}"  placeholder="E-poçt və ya İstifadəçi adı">
                    </div>
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                        </div>
                        <input class="form-control"  type="password" name="password" id="password" placeholder="Parol">
                    </div>
                  {{--  <div class="row align-items-center remember">
                        <input name="remember" type="checkbox" id="remember">Məni xatırla
                    </div>--}}
                    <div class="form-group">
                        <input type="submit" value="Daxil ol" class="btn float-right login_btn">
                    </div>
                </form>
            </div>
            {{--         <div class="card-footer">
                         <div class="d-flex justify-content-center links">
                             Don't have an account?<a href="#">Sign Up</a>
                         </div>
                         <div class="d-flex justify-content-center">
                             <a href="#">Forgot your password?</a>
                         </div>
                     </div>--}}
        </div>
    </div>
</div>
<script src="{{ asset('backend/assets/plugins/toastr/toastr.min.js') }}"></script>

@if( session()->has('error') )
    <script> toastr.error('{{ session('error') }}')</script>
@endif
@if( session()->has('success') )
    <script> toastr.success('{{ session('success') }}')</script>
@endif

@if ( $errors->any() )
    @foreach ( $errors->all() as $error)
        <script> toastr.error('{{ $error }}')</script>
    @endforeach
@endif
</body>
</html>
