@extends('admin.dashboard.index')

@section('dashboard')
<div class="row">
  <div class="col-12">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-edit"></i>
          Courses
        </h3>
      </div>
      <div class="card-body">
        <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill"
              href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home"
              aria-selected="true">All Course</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill"
              href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile"
              aria-selected="false">My Course</a>
          </li>
        </ul>
        <div class="tab-content" id="custom-content-below-tabContent">
          <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel"
            aria-labelledby="custom-content-below-home-tab">
            <table id="example2" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Title</th>
                  <th>Number Of Question(s)</th>
                  <th>Participant(s)</th>
                  <th>View</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($allCourses as $i)
                <tr>
                  <td>{{$i->title}}</td>
                  <td>{{$i->GetNumberOfQuestions()}}</td>
                  <td>{{$i->GetNumberOfParticipants()}}</td>
                  <td><a href="{{route("admin.contestDetailPage", ["id"=> $i->id])}}">View</a></td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel"
            aria-labelledby="custom-content-below-profile-tab">
            <table id="example3" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Title</th>
                  <th>Number Of Question(s)</th>
                  <th>Participant(s)</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($myCourses as $i)
                <tr>
                  <td>{{$i->title}}</td>
                  <td>{{$i->GetNumberOfQuestions()}}</td>
                  <td>{{20}}</td>
                  <td>
                    <a href="{{route("admin.contestDetailPage", ["id"=> $i->id])}}">View</a>
                    <a class="m-4" href="{{route("admin.deleteCourse", ["id"=> $i->id])}}">Delete</a>
                    <a class="m-2" href="{{route("admin.updateCoursePage", ["id"=> $i->id])}}">Update</a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- /.card -->
    </div>
  </div>
  <!-- /.col -->
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
        ["#example1", "#example2", "#example3"].forEach(e => {
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