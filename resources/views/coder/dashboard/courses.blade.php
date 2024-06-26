@extends('coder.dashboard.index')

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
            <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#all-courses"
              role="tab" aria-controls="custom-content-below-home" aria-selected="true">All Courses</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-content-below-home-tab" data-toggle="pill" href="#my-courses" role="tab"
              aria-controls="custom-content-below-home" aria-selected="true">My Courses</a>
          </li>
        </ul>
        <div class="tab-content" id="custom-content-below-tabContent">
          <div class="tab-pane fade show active" id="all-courses" role="tabpanel"
            aria-labelledby="custom-content-below-home-tab">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Title</th>
                  <th>Number Of Question(s)</th>
                  <th>Participant(s)</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($allCourses as $i)
                <tr>
                  <td>{{$i->title}}</td>
                  <td>{{$i->GetNumberOfValidQuestions()}}</td>
                  <td>{{$i->GetNumberOfParticipants()}}</td>
                  <td>
                    @if ($i->IsCompleteByCurrentUser())
                    <span class="badge badge-success">Completed</span>
                    @else
                    <span class="badge badge-secondary">Not Completed</span>
                    @endif
                  </td>
                  <td>
                    <a href="{{route("coder.detailCourse", ["id"=> $i->id])}}">View</a>
                    @if (!$i->Joined())
                      <a class="ml-3" href="{{route("coder.joinCourse", [ "courseId"=> $i->id,
                        "coderId" => Auth::guard("coder")->user()->id
                        ])}}">Join</a>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="tab-pane fade" id="my-courses" role="tabpanel"
            aria-labelledby="custom-content-below-home-tab">
            <table id="example2" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Title</th>
                  <th>Number Of Question(s)</th>
                  <th>Participant(s)</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($coderCourses as $i)
                <tr>
                  <td>{{$i->Course->title}}</td>
                  <td>{{$i->Course->GetNumberOfQuestions()}}</td>
                  <td>{{$i->Course->GetNumberOfParticipants()}}</td>
                  <td>
                    @if ($i->Course->IsCompleteByCurrentUser())
                    <span class="badge badge-success">Completed</span>
                    @else
                    <span class="badge badge-secondary">Not Completed</span>
                    @endif
                  </td>
                  <td>
                    <a href="{{route("coder.detailCourse", ["id"=> $i->Course->id])}}">View</a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.card -->
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