@extends('coder.dashboard.index')

@section('dashboard')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <a href="{{route('coder.detailCourse', ["id" => request('courseId')])}}">
                            <i class="btn-sm fas fa-arrow-left mr-2"></i>
                          </a>
                        {{$question->title}}
                    </div>
                </div>
                <div class="card-body">
                    {!!$question->description!!}
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">Test Cases</div>
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                @for ($i = 1; $i <= $question->numberOfParams; $i++)
                                    <th>Param {{$i}}</th>
                                @endfor
                                <th>Expected Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0 ?>
                            @foreach ($question->test_cases["params"] as $params)
                                <tr>
                                        <?php $i++; ?>
                                        <td>{{$i}}</td>
                                    @foreach ($params as $param)
                                    @if (is_array($param))
                                        <td>{{json_encode($param)}}</td>
                                    @else
                                        <td>{{$param}}</td>
                                    @endif
                                    @endforeach
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