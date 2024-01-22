@extends('coder.dashboard.index')

@section('dashboard')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Questions</div>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Action</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1 ?>
                        @foreach ($course->Questions as $q)
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$q->title}}</td>
                            <td>
                                <a href="{{route("coder.detailQuestion", ["courseId"=> $course->id, "questionId" =>
                                    $q->id])}}">View</a>
                            </td>
                            <td>
                                @if ($q->IsSolvedByCurrentUser())
                                <span class="badge badge-success">Solved</span>
                                @else
                                <span class="badge badge-secondary">Unsolved</span>
                                @endif
                            </td>
                            <?php $i += 1 ?>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Leaderboard</div>
            </div>
            <div class="card-body">
                <table id="example2" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 1; ?>
                        <?php $numberOfQuestions = $course->GetNumberOfQuestions() ?>
                        @foreach ($course->GetLeaderboard() as $i)
                            <tr>
                                <td>{{$j++}}</td>
                                <td class="text-capitalize">{{$i->Coder->name}}</td>
                                <td>{{$i->total}} / {{$numberOfQuestions}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
        const commonConfig = {
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        }
        $("#example1").DataTable(commonConfig);
        $("#example2").DataTable(commonConfig);
    });
</script>
@endpush
@endsection