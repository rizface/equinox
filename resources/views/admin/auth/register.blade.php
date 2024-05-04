@extends('admin.auth.index')

@section('auth')
<div class="register-box">
    <div class="register-logo">
      <a href="#"><b>POLBAT</b>CODE</a>
    </div>
  
    <div class="card">
      <div class="card-body register-card-body">
        <p class="login-box-msg">Create New Account</p>
  
        <form action="{{route("admin.register")}}" method="post">
            @csrf
          <div class="input-group mb-3">
            <input name="username" type="text" class="form-control" placeholder="Username">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input name="email" type="email" class="form-control" placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input name="password" type="password" class="form-control" placeholder="Kata Sandi">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input name="confirm-password" type="password" class="form-control" placeholder="Ketik Ulang Kata Sandi">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input name="name" type="text" class="form-control" placeholder="Nama Lengkap">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <!-- /.col -->
            <div class="col">
              <button type="submit" class="btn btn-primary btn-block">Create Account</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
  
        <a href="{{route('admin.loginPage')}}" class="text-center">Already Have an Account</a>
      </div>
      <!-- /.form-box -->
    </div><!-- /.card -->
  </div>
@endsection