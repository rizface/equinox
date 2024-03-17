@extends('superadmin.dashboard.index')

@section('dashboard')
<div class="row">
    <div class="col-md">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Validate & Invalidate Admins</div>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Validate</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Invalidate</a>
                  </li>
                </ul>
                <div class="tab-content" id="custom-content-below-tabContent">
                  <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                    <div class="col-md">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Invalid Admin</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Name</th>
                                                <th>Username</th>
                                                <td>Register At</td>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($invalidAdmins as $a)
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>{{$a->name}}</td>
                                                    <td>{{$a->username}}</td>
                                                    <td>{{\Carbon\Carbon::parse($a->created_at)->isoFormat("DD-MM-YYYY")}}</td>
                                                    <td>
                                                        <div class="col-md-4">
                                                            <a href="{{route("superadmin.validateAdmin", ["adminId" => $a->id])}}" onclick="return confirm('Are you sure want to validate this admin ?')">Validate</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                    <div class="col-md">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Invalid Admin</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example2" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Name</th>
                                                <th>Username</th>
                                                <td>Validate At</td>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($validAdmins as $a)
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>{{$a->name}}</td>
                                                    <td>{{$a->username}}</td>
                                                    <td>{{\Carbon\Carbon::parse($a->validate_at)->isoFormat("DD-MM-YYYY")}}</td>
                                                    <td>
                                                        <div class="col-md-4">
                                                            <a href="{{route("superadmin.invalidateAdmin", ["adminId" => $a->id])}}" onclick="return confirm('Are you sure want to invalidate this admin ?')">Invalidate</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
        </div>
    </div>
</div>

    @push('datatable')
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>

    <script>
      $(function () {
        ["#example1", "#example2"].forEach(e => {
            $(e).DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        })
    });
    </script>
    @endpush
@endsection