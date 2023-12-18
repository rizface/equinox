@extends('admin.dashboard.index')

@section('dashboard')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Create Course</div>
                </div>
                <div class="card-body">
                    <form action="{{route("admin.createContest")}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="" class="form-label">Title</label>
                            <input type="text" name="title" class="form-control">
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