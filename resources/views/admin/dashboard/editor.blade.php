@extends('coder.dashboard.index')

@section('dashboard')
<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Submissions</div>
            </div>
            <div class="card-body">
              <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Submission</th>
                      <th>Action</th>
                    </tr>  
                  </thead>
                  <tbody>
                    @foreach ($submissions as $key => $item)
                    <tr>
                      <td>Submission {{$key+1}}</td>
                      <td><a href="?batch_token={{$item->batch_token}}">View Solution</a></td>
                    </tr>
                    @endforeach
                  </tbody>  
              </table>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <textarea id="editor"></textarea>
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

    var lang = {
      68: "php"
    }

    var regex = /^echo solution\([^;]*\);/gm;
    editor.session.setValue(atob(`{{$solution}}`).replace(regex, ''));

    if ((`{{$solution}}`).length != 0) {
      const solutionLang = lang[`{{$solutionLang}}`]
      console.log(solutionLang);
      editor.session.setMode(`ace/mode/${solutionLang}`);
      editor.setReadOnly(true);
    }
</script>
@endpush
@endsection