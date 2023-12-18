@extends('admin.dashboard.index')

@section('dashboard')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Description</div>
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
                                @for ($i = 1; $i <= $numberOfParams; $i++)
                                    <th>Param {{$i}}</th>
                                @endfor
                                <th>Expected Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0 ?>
                            @foreach ($testCases as $t)         
                                @if ($t != "")                                    
                                    <tr>
                                        <td>{{$i+1}}</td>
                                        @foreach (explode("*", $t) as $it)
                                            <td>{{$it}}</td>
                                        @endforeach
                                        <td>{{$result[$i]}}</td>
                                    </tr>
                                    <?php $i++ ?>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Solvers</div>
                </div>
                <div class="card-body">
                    <table id="example2" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>View Solution</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Rain Water</td>
                                <td><a href="">View</a></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Stack</td>
                                <td><a href="">View</a></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>BFS (Breadth First Search)</td>
                                <td><a href="">View</a></td>
                            </tr>
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