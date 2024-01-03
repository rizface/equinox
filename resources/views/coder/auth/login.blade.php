@extends('admin.auth.index')

@section('auth')
  <div class="login-box">
    <div class="login-logo">
      <a href="#"><b>POLBAT</b>CODE</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <form action="{{route("coder.login")}}" method="post">
          @csrf
          <div class="input-group mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Kata Sandi">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <button type="submit" class="btn btn-primary btn-block">Masuk</button>
            </div>
          </div>
        </form>
        <!-- /.social-auth-links -->

        {{-- TODO: MAKE RESET PASSWORD WORKS --}}
        {{-- <p class="mb-1">
          <a href="forgot-password.html">Lupa Password ?</a>
        </p> --}}

        <p class="mb-0">
          <a href="{{route('admin.registerPage')}}" class="text-center">Buat Akun</a>
        </p>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
@endsection

