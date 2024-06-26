@extends('coder.dashboard.index')

@section('dashboard')
<div class="row">
    <div class="col-md-5">
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
                <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Description</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Test Cases</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#submissions" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Submission(s)</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link text-red" id="custom-content-below-profile-tab" data-toggle="pill" href="#report" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Report</a>
                  </li>
                </ul>
                <div class="tab-content" id="custom-content-below-tabContent">
                  <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                    <div class="mt-4">
                        <b>Note: ⚠️ Please write your code in function with name solution</b>
                        {!! $question->description !!}  
                    </div>
                  </div>
                  <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
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
                          @foreach ($question->test_cases["params"]  as $params)
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
                  <div class="tab-pane fade" id="submissions" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                    <div class="accordion mt-2" id="accordionExample">
                      <?php $submissionAt = sizeof($submissions) ?>
                      @foreach ($submissions as $key => $submission)
                      <div class="card">
                        <div class="card-header" id="headingOne">
                          <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#submission{{$submissionAt}}" aria-expanded="false" aria-controls="collapseOne">
                              Submission {{$submissionAt}}
                            </button>
                          </h2>
                        </div>
                    
                        <div id="submission{{$submissionAt}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                          <div class="ml-4 mt-3">
                            <a href="?solution={{$submission[0]->batch_token}}">View Solution</a>
                          </div>
                          <div class="card-body table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                              <thead>
                                  <tr>
                                      <th>#</th>
                                      @for ($i = 1; $i <= $question->numberOfParams; $i++)
                                          <th>Param {{$i}}</th>
                                      @endfor
                                      <th>Expected Result</th>
                                      <th>Result</th>
                                      <th>Status</th>
                                      <th>Message</th>
                                  </tr>
                              </thead>
                              <tbody>
                                <?php $j=0; ?>
                                  @foreach ($submission  as $s)
                                      <tr>
                                            <?php $j++; ?>
                                            <td>{{$j}}</td>
                                          @foreach ($s->params as $param)
                                            <td>{{$param}}</td>
                                          @endforeach
                                          @if (is_array($s->expected_return_values->return))
                                            <td>{{json_encode($s->expected_return_values->return)}}</td>
                                          @else
                                            <td>{{$s->expected_return_values->return}}</td>
                                          @endif
                                            <td>
                                                {{$s->result ? json_encode($s->GetCoderAnswer()) : "N/A"}}
                                            </td>
                                            <td class="text-capitalize">
                                              @if ($s->status == "accepted")
                                                  <span class="badge badge-success">{{$s->status}}</span>                                              
                                              @elseif ($s->status == "pending")
                                                  <span class="badge badge-secondary">{{$s->status}}</span>
                                              @else
                                                  <span class="badge badge-danger">{{$s->status}}</span>
                                              @endif
                                            </td>
                                            <td>
                                              @if ($s->message != "")
                                                {{$s->message}}
                                              @else
                                                N/A
                                              @endif
                                            </td>
                                      </tr>
                                  @endforeach
                              </tbody>
                          </table>
                          </div>
                        </div>
                      </div>  
                      <?php $submissionAt-- ?> 
                      @endforeach
                    </div>
                  </div>
                  <div class="tab-pane fade" id="report" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                    <form method="post" action="{{route("coder.report.question")}}" class="mt-4">
                      @csrf
                      <div class="form-group">
                        <label for="">Title</label>
                        <input type="text" name="title" class="form-control" required>
                      </div>
                      <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" cols="30" rows="10" class="form-control" required></textarea>
                        <input type="hidden" name="question_id" value="{{$question->id}}">
                      </div>
                      <div class="form-group">
                        <button class="btn btn-sm btn-danger">Report</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route("coder.submitSubmission", [
                          "courseId" => $question->contest_id, 
                          "questionId" => $question->id,
                          ])}}" method="post">
                          @csrf
                          <div class="form-group">
                            <select name="lang" onchange="changeLanguage(this)" class="form-control">
                                <option value="68">PHP (7.4.1)</option>
                                {{-- <option value="71">Python (3.8.1)</option> --}}
                            </select>
                          </div>
                          <textarea id="editor"></textarea>
                          <textarea name="hiddenInput" id="hiddenInput" style="display: none"></textarea>
                          <button type="submit" class="btn btn-secondary btn-sm mt-3">submit</button>
                        </form>
                      </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('codeeditor')
<script src="https://cdn.jsdelivr.net/npm/ace-builds@1.31.1/src-min-noconflict/ace.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/ace-builds@1.31.1/src-min-noconflict/ext-language_tools.min.js"></script>
<script>
    var editor = ace.edit("editor", {
        autoScrollEditorIntoView: true,
        maxLines: 50,
        minLines: 50,
        enableBasicAutocompletion: true,
        enableSnippets: true,
        enableLiveAutocompletion: true,
        theme: "ace/theme/dracula"
    });

    var languange = "php"
    editor.session.setMode("ace/mode/php");

    function loadLatestValue() {
      const content = localStorage.getItem(`{{$question->id}}-${languange}-content`);
      if (content) {
        editor.session.setValue(content);
        return
      }

      editor.session.setValue('');
    }

    function changeLanguage(e) {
      const langMap = {
        "68": "php",
        "71": "python"
      }

      languange = langMap[e.value]

      const lang = e.value
      
      editor.session.setMode(`ace/mode/${langMap[lang]}`);
      localStorage.removeItem("content");

      loadLatestValue()
    }

    editor.session.on('change', function(delta) {
      if ((`{{$solution}}`).length == 0) {
        var content=document.getElementById('hiddenInput');
        content.value=editor.session.getValue()
        session = editor.getSession();

        localStorage.setItem(`{{$question->id}}-${languange}-content`, content.value);
      }
    });

    if ((`{{$solution}}`).length == 0) {
      window.onload = loadLatestValue()
    } else {
      var regex = /^echo solution\([^;]*\);/gm;
      editor.session.setValue(atob(`{{$solution}}`).replace(regex, ''));
    }
</script>
@endpush
@endsection