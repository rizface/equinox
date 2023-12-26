@extends('index')

@section('dashboard')
<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Trapped Water</div>
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
                    <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Submission(s)</a>
                  </li>
                </ul>
                <div class="tab-content" id="custom-content-below-tabContent">
                  <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                    <div class="mt-4">
                        {!! $q->description !!}  
                    </div>
                  </div>
                  <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Param 1</th>
                                <th>Param 2</th>
                                <th>Return Value</th>
                            </tr>
                        </thead>
            
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>[1,3]</td>
                                <td>[2]</td>
                                <td>2.0</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>[1,2]</td>
                                <td>[3,4]</td>
                                <td>2.5</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>[1,2]</td>
                                <td>[3,4]</td>
                                <td>2.5</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>[1,2]</td>
                                <td>[3,4]</td>
                                <td>2.5</td>
                            </tr>
                        </tbody>
                    </table>  
                </div>
                </div>
              </div>
        </div>
    </div>
    <div class="col-6">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <select onchange="changeLanguage(this)" class="form-control">
                                <option value="php">PHP</option>
                                <option value="python">Python</option>
                            </select>
                        </div>
                        <div id="editor"></div>
                        <button class="btn btn-secondary btn-sm mt-3">submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection