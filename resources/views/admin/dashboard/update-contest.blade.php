@extends('admin.dashboard.index')

@section('dashboard')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Update Course</div>
                </div>
                <div class="card-body">
                    <form action="{{route("admin.updateCourse", ["id" => $course->id])}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="" class="form-label">Title</label>
                            <input value="{{$course->title}}" type="text" name="title" class="form-control">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-small">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection