@extends('admin.dashboard.index')

@section('dashboard')
<form action="{{route("admin.createQuestion",["id" => request('id')])}}" method="post">
<div class="row">
    @csrf
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Question Description</div>
            </div>
            
            <div class="card-body card-outline">
                <div class="form-group">
                    <label for="" class="form-label">Title</label>
                    <input type="text" name="title" class="form-control">
                </div>
                <div class="form-group">
                    <label for="" class="form-label">
                        Question Description
                    </label>
                    <textarea name="question-description" id="compose-textarea" class="form-control">
                    </textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Params and Expected Return</div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-2">
                            <input class="form-control" type="number" id="num_of_params">
                        </div>                   
                        <div class="col-2">
                            <button id="add_params" class="btn btn-success">Add Params</button>    
                        </div> 
                    </div>
                </div>
                <div class="form-group ml-2">
                    <div class="row" id="params_container">

                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

@push('summernote')
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<script>
    $(function () {
      //Add text editor
      $('#compose-textarea').summernote()
    })
</script>
<script src="{{asset('scripts/contest.js')}}"></script>
@endpush
@endsection