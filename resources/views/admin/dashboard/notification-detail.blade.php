@extends('admin.dashboard.index')

@section('dashboard')
<div class="row">
    <div class="col-md-6 m-auto">
        <div class="card">
          <div class="card-body">
            <div class="tab-content">
              <div class="active" id="activity">
                @foreach ($notifications as $n)
                    <div class="post">
                        <div class="user-block">
                        <img class="img-circle img-bordered-sm" src="{{asset('dist/img/user1-128x128.jpg')}}" alt="user image">
                        <span class="username">
                            <a href="#" class="text-capitalize">{{$n->title}}.</a>
                        </span>
                        <span class="description">{{$n->created_at->diffForHumans()}}</span>
                        </div>
                        <!-- /.user-block -->
                        @if ($n->question_id)
                            @if ($n->Sender)
                              <p>
                                Your question with title <b>{{$n->Question->title}}</b> from course <b>{{$n->Question->Contest->title}}</b> is reported
                                by <b>{{$n->Sender->name}}</b>
                                with description: {{$n->message}}
                              </p>  
                            @else
                              <p>
                                Your question with title <b>{{$n->Question->title}}</b> from course <b>{{$n->Question->Contest->title}}</b> has been invalidated
                                by <b>System Adminstrator</b>
                              </p>
                            @endif
                              <p>
                                <a href="{{route('admin.questionDetailPage', ["id" => $n->Question->contest_id, "questionId" => $n->question_id])}}">
                                  See Question
                                </a>
                            </p>
                        @else
                            {{$n->message}}
                        @endif
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