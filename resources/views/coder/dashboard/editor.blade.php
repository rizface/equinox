@extends('coder.dashboard.index')

@section('dashboard')
<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <div class="card-title">{{$question->title}}</div>
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
                </ul>
                <div class="tab-content" id="custom-content-below-tabContent">
                  <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                    <div class="mt-4">
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
                                        <td>{{$param}}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                  </div>
                  <div class="tab-pane fade" id="submissions" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                    <div class="accordion mt-2" id="accordionExample">
                      <?php $submissionAt = 1 ?>
                      @foreach ($submissions as $key => $submission)
                      <div class="card">
                        <div class="card-header" id="headingOne">
                          <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#submission{{$submissionAt}}" aria-expanded="true" aria-controls="collapseOne">
                              Submission {{$submissionAt}}
                            </button>
                          </h2>
                        </div>
                    
                        <div id="submission{{$submissionAt}}" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
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
                                      <th>Accepted</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php $i = 0 ?>
                                  @foreach ($submission  as $s)
                                      <tr>
                                              <?php $i++; ?>
                                              <td>{{$i}}</td>
                                          @foreach ($s->params as $param)
                                              <td>{{$param}}</td>
                                          @endforeach
                                            <td>{{$s->expected_return_values->return}}</td>
                                            <td>{{
                                              $s->result ? $s->result : "N/A"  
                                            }}</td>
                                            <td class="text-capitalize">
                                              @if ($s->status == "accepted")
                                                  <span class="badge badge-success">{{$s->status}}</span>
                                              @endif
                                              
                                              @if ($s->status == "pending")
                                                  <span class="badge badge-secondary">{{$s->status}}</span>
                                              @endif
                                            </td>
                                            <td class="text-capitalize">
                                              @if ($s->accepted == true)
                                                  <span class="badge badge-success">Yes</span>
                                              @endif
                                              
                                              @if ($s->accepted == null)
                                                <span class="badge badge-secondary">N/A</span>
                                              @elseif ($s->accepted == false)
                                                <span class="badge badge-danger">No</span>
                                              @endif
                                            </td>
                                      </tr>
                                  @endforeach
                              </tbody>
                          </table>
                          </div>
                        </div>
                      </div>  
                      <?php $submissionAt++ ?> 
                      @endforeach
                    </div>
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
                                <option value="71">Python (3.8.1)</option>
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

    function phpInstruction() {
      return `<!--write your solution below this comment using function called solution-->`
    }

    function pythonInstruction() {
      return `# write your solution below this comment using function called solution`
    }

    editor.session.setMode("ace/mode/php");
    editor.session.setValue(phpInstruction())

    function changeLanguage(e) {
      const langMap = {
        "68": "php",
        "71": "python"
      }

      const langInstruction = {
        "68": phpInstruction,
        "71": pythonInstruction,
      }

      const lang = e.value
      
      editor.session.setMode(`ace/mode/${langMap[lang]}`);
      editor.session.setValue(langInstruction[lang]())
    }

    editor.session.on('change', function(delta) {
      var content=document.getElementById('hiddenInput');
      content.value=editor.session.getValue()
    });
</script>
@endpush
@endsection