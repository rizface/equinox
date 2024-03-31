@extends('admin.dashboard.index')

@section('dashboard')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Questions</div>
            </div>
            <div class="card-body">
                <table id="example2" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            @if ($contest->ThisIsMyContest())
                                <th>Valid</th>
                            @endif
                            <th>Level</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1 ?>
                        @foreach ($contest->Questions as $q)
                        @if ($contest->ThisIsMyContest())
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$q->title}}</td>
                                <td>
                                    @if ($q->is_valid)
                                        Yes
                                    @else
                                        No
                                    @endif
                                </td>
                                <td>
                                    @switch($q->level)
                                        @case("easy")
                                            <span class="text-capitalize badge badge-success">{{$q->level}}</span>
                                            @break
                                        @case("medium")
                                            <span class="text-capitalize badge badge-warning">{{$q->level}}</span>
                                            @break
                                        @default
                                            <span class="text-capitalize badge badge-danger">{{$q->level}}</span>
                                    @endswitch
                                </td>
                                <td>
                                    <a href="{{route('admin.questionDetailPage', ["id"=> request('id'), "questionId" =>
                                        $q->id])}}">View</a>
                                    <a class="m-4" href="{{route('admin.deleteQuestion', ["id"=> request('id'),
                                        "questionId" => $q->id])}}">Delete</a>
                                    <a class="m-2" href="{{route('admin.updateQuestionPage', ["id"=> request('id'),
                                        "questionId" => $q->id])}}">Update</a>
                                    @if (!$q->is_valid)
                                    <a class="m-2" href="{{route('admin.validateQuestionPage', ["id"=> request('id'),
                                        "questionId" => $q->id])}}">Validate</a>
                                    @endif
                                </td>
                                <?php $i += 1 ?>
                            </tr>
                        @elseif(!$contest->ThisIsMyContest() && $q->is_valid)
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$q->title}}</td>
                                <td>
                                    @switch($q->level)
                                        @case("easy")
                                            <span class="text-capitalize badge badge-success">{{$q->level}}</span>
                                            @break
                                        @case("medium")
                                            <span class="text-capitalize badge badge-warning">{{$q->level}}</span>
                                            @break
                                        @default
                                            <span class="text-capitalize badge badge-danger">{{$q->level}}</span>
                                    @endswitch
                                </td>
                                <td>
                                    <a href="{{route('admin.questionDetailPage', ["id"=> request('id'), "questionId" =>
                                        $q->id])}}">View</a>
                                </td>
                                <?php $i += 1 ?>
                            </tr>
                        @endif
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
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 1 ?>
                        <?php $numberOfQuestions = $contest->GetNumberOfQuestions() ?>
                        @foreach ($contest->GetLeaderboard() as $i)
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
    const thisIsMyContest = `{{$contest->ThisIsMyContest()}}`
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

            let table2Config = {
                ...commonConfig
            }

            if(thisIsMyContest) {
                table2Config = {
                    ...table2Config,
                    buttons: [
                        {
                            text: 'New Question',
                            action: function ( e, dt, node, config ) {
                                window.location.href = "{{route('admin.createQuestionPage', ['id' => request('id')])}}"
                            },
                            className: "btn-sm"
                        }
                    ]
                }
            }

            const table2 = $("#example2").DataTable(table2Config);
            
            if(thisIsMyContest) {
                table2.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)')
            }
        });
</script>
@endpush
@endsection