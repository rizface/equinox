@extends('admin.dashboard.index')

@section('dashboard')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{$question->title}}</div>
                </div>
                <div class="card-body">
                    <form action="{{route('admin.updateQuestion', ['id' => request('id'), 'questionId' => request('questionId')])}}" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col">   
                                    <div class="form-group">
                                        <label for="" class="form-label">Title</label>
                                        <input value="{{$question->title}}" type="text" name="title" class="form-control">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="" class="form-label">Level</label>
                                        <select name="level" class="form-control">
                                            <option {{$question->LevelIs("easy") ? "selected" : ""}} value="easy">Easy</option>    
                                            <option {{$question->LevelIs("medium") ? "selected" : ""}} value="medium">Medium</option>    
                                            <option {{$question->LevelIs("hard") ? "selected" : ""}} value="hard">Hard</option>    
                                        </select>                    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="form-label">
                                Question Description
                            </label>
                            <textarea name="description" id="compose-textarea" class="form-control">
                                {!! $question->description !!}
                            </textarea>
                        </div>
                        <div class="form-group">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        @for ($i = 1; $i <= $question->numberOfParams; $i++)
                                            <th>Param {{$i}}</th>
                                        @endfor
                                        <th>Expected Result</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0 ?>
                                    @foreach ($question->test_cases["params"] as $params)
                                        <tr id="row{{$i}}">
                                            @foreach ($params as $key => $param)
                                                <td>
                                                    <input 
                                                    type="text"
                                                    id="input{{$i}}"
                                                    value="{{$param}}"
                                                    type="text" name="{{$key}}[]" id="" class="form form-control">
                                                </td>
                                            @endforeach
                                            <td>
                                                <a onclick="deleteParams(this)" data-row="{{$i}}" href="#">Delete</a>
                                            </td>
                                        </tr>
                                        <?php $i++; ?>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-secondary btn-sm">Save</button>
                        </div>
                    </form>
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
    <script src="{{asset('scripts/update-question.js')}}"></script>
    
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": false,
                "info": false,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
    @endpush

@push('summernote')
    <script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
    <script>
        $(function () {
            //Add text editor
            $('#compose-textarea').summernote({
                height: 250
            })
        })
    </script>
@endpush
@endsection