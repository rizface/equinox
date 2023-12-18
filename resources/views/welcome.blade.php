@extends('index')

@section('dashboard')
<div class="row">
    <div class="col-4">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Test Cases</div>
            </div>
            <div class="card-body">
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
    <div class="col-8">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            {{$q->title}}
                        </div>
                    </div>
                    <div class="card-body">
                        {!! $q->description !!}
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div id="editor"></div>
                        <button class="btn btn-secondary btn-sm mt-3">submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection