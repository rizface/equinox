@extends('coder.dashboard.index')

@section('dashboard')
<div class="row">
    <div class="col-md-3">
      <!-- Profile Image -->
      <div class="card card-secondary card-outline">
        <div class="card-body box-profile">
          <div class="text-center">
            <img class="profile-user-img img-fluid img-circle"
                 src="../../dist/img/user4-128x128.jpg"
                 alt="User profile picture">
          </div>

          <h3 class="profile-username text-center text-capitalize">{{$coder->name}}</h3>

          <p class="text-muted text-center">Coder</p>

          <ul class="list-group list-group-unbordered mb-3">
            <li class="list-group-item">
                <b>Total Solved Questions</b> <a class="float-right">{{$totalSolvedQuestions}}</a>
            </li>
            @foreach ($totalSolvedQuestionsPerLevel as $i)
                <li class="text-capitalize list-group-item">
                    <b>{{$i->level}}</b> <a class="float-right">{{$i->count}}</a>
                </li>
            @endforeach
            </li>
          </ul>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

      <!-- About Me Box -->
      <div class="card card-secondary">
        <div class="card-header">
          <h3 class="card-title">About Me</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body text-capitalize">
          <strong><i class="fas fa-book mr-1"></i> Name</strong>

          <p class="text-muted">
            {{$coder->name}}
          </p>

          <hr>

          <strong><i class="fas fa-book mr-1"></i> NIM</strong>

          <p class="text-muted">{{$coder->nim}}</p>

          <hr>

          <strong><i class="fas fa-book mr-1"></i> Username</strong>

          <p class="text-muted">{{$coder->username}}</p>

          {{-- <hr>

          <strong><i class="fas fa-pencil-alt mr-1"></i> Skills</strong>

          <p class="text-muted">
            <span class="tag tag-danger">UI Design</span>
            <span class="tag tag-success">Coding</span>
            <span class="tag tag-info">Javascript</span>
            <span class="tag tag-warning">PHP</span>
            <span class="tag tag-primary">Node.js</span>
          </p>

          <hr>

          <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>

          <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p> --}}
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
    <div class="col-md-9">
      <div class="card">
        <div class="card-header p-2">
          <ul class="nav nav-pills">
            <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Activity</a></li>
            <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
        </ul>
        </div><!-- /.card-header -->
        <div class="card-body">
          <div class="tab-content">
            <div class="active tab-pane" id="activity">
                @foreach ($timeline as $t)
                    <!-- Post -->
                        <div class="post">
                            <div class="user-block">
                            <img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg" alt="user image">
                            <span class="username">
                                <a href="#" class="text-capitalize">{{$coder->name}}.</a>
                                <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                            </span>
                            <span class="description">{{$t->solved_at}}</span>
                            </div>
                            <!-- /.user-block -->
                            <p>
                                Solve question <b>{{$t->title}}</b> 
                            </p>
                        </div>
                    <!-- /.post -->
                @endforeach
            </div>

            <div class="tab-pane" id="settings">
              <form method="post" class="form-horizontal">
                <div class="form-group row">
                  <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                  <div class="col-sm-10">
                    <input type="email" class="form-control" id="inputName" placeholder="Name" value="{{$coder->name}}">
                  </div>
                </div>
                <div class="form-group row">
                    <label for="inputName2" class="col-sm-2 col-form-label">NIM</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputName2" placeholder="NIM" value="{{$coder->nim}}">
                    </div>
                  </div>
                <div class="form-group row">
                  <label for="inputEmail" class="col-sm-2 col-form-label">Username</label>
                  <div class="col-sm-10">
                    <input type="username" class="form-control" id="inputEmail" placeholder="Username" value="{{$coder->username}}">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="offset-sm-2 col-sm-10">
                    <button type="submit" class="btn btn-danger">Submit</button>
                  </div>
                </div>
              </form>
            </div>
            <!-- /.tab-pane -->
          </div>
          <!-- /.tab-content -->
        </div><!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>
@endsection