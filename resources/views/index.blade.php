<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>POLBATCODE</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}"> 
  {{-- Summernote --}}
  <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css')}}">
<link href="https://cdn.jsdelivr.net/npm/ace-builds@1.31.1/css/ace.min.css" rel="stylesheet">
<style type="text/css" media="screen">
    #editor { 
        position: relative;
        top:0;
        right: 0;
        bottom: 0;
        left: 0;
    }
</style>
</head>
<body class="hold-transition sidebar-mini sidebar-closed sidebar-collapse">
@include('sweetalert::alert')
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../../index3.html" class="brand-link">
      <img src="../../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">POLBATCODE</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Alexander Pierce</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="{{route("admin.dashboard")}}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Contest</p>
                </a>
              </li>
            <li class="nav-item">
              <a href="{{route("admin.createContestPage")}}" class="nav-link">
                <i class="fas fa-circle nav-icon"></i>
                <p>Create Contest</p>
              </a>
            </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @yield('dashboard')
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.2.0
    </div>
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/ace-builds@1.31.1/src-min-noconflict/ace.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/ace-builds@1.31.1/src-min-noconflict/ext-language_tools.min.js"></script>
<script>
    var editor = ace.edit("editor", {
        autoScrollEditorIntoView: true,
        maxLines: 50,
        minLines: 50,
        enableBasicAutocompletion: true,
        enableSnippets: true,
        enableLiveAutocompletion: true,
        theme: "ace/theme/dracula"
    });

    function phpInstruction() {
      return `<!--write your solution below this comment using function called solution-->`
    }

    function pythonInstruction() {
      return `# write your solution below this comment using function called solution`
    }

    editor.session.setMode("ace/mode/php");
    editor.session.setValue(phpInstruction())

    function changeLanguage(e) {
      const lang = e.value
      
      editor.session.setMode(`ace/mode/${lang}`);

      if (lang == "php") {
        editor.session.setValue(phpInstruction())
      }

      if (lang == "python") {
        editor.session.setValue(pythonInstruction())
      }
    }
</script>
@stack('datatable')
@stack('summernote')
</body>
</html>
