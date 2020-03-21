@extends('reportgenerator::layouts.master')

@section('title', 'Report Generator')

@section('content')
    &nbsp;
    <div class="container">
      <div class="alert alert-success" style="display:none"></div>
        <div class="row">
            <div id="dropBox" class="col-sm-6">
                <div id="accordion">
                    <div class="card">
                        <form id="dropForm">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0"><b>Drop columns here</b>
                                <button type="button" name="submit" class="btn btn-info" id="generate-button">Generate report</button></h5>
                            </div>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body" id="second">
                                    <p class="note">Why am I still empty?</p>
                                </div>
                                <form><ul id="selected-list" style="padding-right: 10px"></ul></form>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div id="accordion">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0" id="card-header"><b>Select your desired columns here</b></h5>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <p class="note">Frequently used columns</p>
                                <div class="row" id="draggable-column">
                                    @foreach ($draggable_components as $draggable_component)
                                        <div class="col-sm-2 wordwrap draggable" id="{{ $draggable_component->option_id }}">
                                            <p data-toggle="tooltip" data-placement="top" title="{{ $draggable_component->title }}">
                                                {{ $draggable_component->title }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        &nbsp;<hr />
        <!-- This block will be used to generate reports from custom query supplied by advanced users. -->
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <div id="accordion">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0" id="card-header"><b>Enter SQL to generate report</b></h5>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <form class="sql-form" action="index.html" method="post">
                                    <div class="form-group">
                                        <label for="sql-query">Enter SQL query</label>
                                        <textarea class="form-control" aria-label="Enter query" disabled></textarea>
                                    </div>
                                    <button type="button" name="submit" class="btn btn-info" disabled>GO</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2"></div>
        </div>
    </div><!-- /.container -->
@endsection
