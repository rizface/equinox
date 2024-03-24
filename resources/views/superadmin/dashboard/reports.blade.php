@extends('superadmin.dashboard.index')

@section('dashboard')
<div class="row">
    <div class="col-md-6 m-auto">
        <div class="card">
          <div class="card-body">
            <div class="tab-content">
              <div class="active" id="activity">
                @foreach ($reports as $r)
                    <div class="post">
                        <div class="user-block">
                        <img class="img-circle img-bordered-sm" src="{{asset('dist/img/user1-128x128.jpg')}}" alt="user image">
                        <span class="username">
                            <a href="#" class="text-capitalize">{{$r->report_title}}.</a>
                        </span>
                        <span class="description">{{\Carbon\Carbon::parse($r->report_time)->diffForHumans()}}</span>
                        </div>
                        <!-- /.user-block -->
                        <p>
                            {{$r->description}}
                        </p>
                        <p>
                            <a href="{{route('superadmin.questionDetail', ["questionId" => $r->question_id])}}">
                              See Question
                            </a>
                        </p>
                    </div>
                @endforeach
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div><!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
</div>
@endsection