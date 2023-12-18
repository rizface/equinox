@extends('admin.dashboard.index')

@section('dashboard')
<div class="row">
    <div class="col-12">
        <!-- Custom Tabs -->
      <div class="card">
        <div class="card-body">
            <table id="example3" class="table table-bordered table-striped">
                <thead>
                    <tr>
                    <th>Title</th>
                    <th>Started At</th>
                    <th>Ended At</th>
                    <th>Participant(s)</th>
                    <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($myContest as $i)
                        <tr>
                            <td>{{$i->title}}</td>
                            <td>{{$i->started_at}}</td>
                            <td>{{$i->ended_at}}</td>
                            <td>{{20}}</td>
                            <td><a href="{{route("admin.contestDetailPage", ["id" => $i->id])}}">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
          <!-- /.tab-content -->
        </div><!-- /.card-body -->
      </div>
      <!-- ./card -->
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