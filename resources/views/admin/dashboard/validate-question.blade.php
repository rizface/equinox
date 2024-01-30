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
                </div>
              </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route("admin.submitSubmission", [
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
                          <input type="hidden" name="questionvalidation" value="true">
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
      const content = localStorage.getItem(`admin-{{$question->id}}-${languange}-content`);
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
        var content=document.getElementById('hiddenInput');
        content.value=editor.session.getValue()
        session = editor.getSession();

        localStorage.setItem(`admin-{{$question->id}}-${languange}-content`, content.value);
    });

    window.onload = loadLatestValue()
</script>
@endpush
@endsection