@extends('reportgenerator::layouts.master')

@section('title', 'Generated report')

@section('content')

<nav class="navbar navbar-expand-lg navbar-light bg-warning" style="background-color:#ccc !important">
    <!-- The button below opens the add system feature modal below. -->
    <h5 style="margin-right: 50px !important"><strong>Generated Report</strong></h5>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedButtons" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedButtons">
        <ul class="navbar-nav mr-auto" style="margin-left: 500px !important">
            @if(!$hide)
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#add-report-format">Save report as</button>&nbsp;
            @endif
            <button type="button" class="btn btn-info" onclick="printReport('report_div')">Print</button>&nbsp;
            <form method="get" action="{{ url('reportgenerator/pdf_report') }}">
                @foreach($column_names as $key => $column_name)
                    <input name="column_names[]" class="column_names" id="column_names[]" type="hidden" value="{{ $column_name }}" align="absmiddle" />
                @endforeach
                <button type="submit" class="btn btn-info" id="pdf-button">PDF</button>
            </form>&nbsp;
            <button type="button" class="btn btn-info">TXT</button>&nbsp;
            <button type="button" class="btn btn-info">CSV</button>&nbsp;
            <button type="button" class="btn btn-info">ODT</button>
        </ul>
    </div>
</nav><!-- /.navbar -->
<br />
<div id="report_div" style="overflow-x: auto">
<table width="100%" border="1">
    <tr>
        @foreach($data as $index => $datum)
            <td>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            @if($index == 0)
                                <th scope="col">#</th>
                            @endif
                            <th scope="col">{{ $column_names[$index] }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach($datum as $key => $item)
                                <?php $item = get_object_vars($item) ?> <!-- convert Std Object to array -->
                                @if($index == 0)
                                    <tr scope="row">
                                        <td>{{ $key + 1 }}: </td>
                                        <td class="table-wordwrap" style="white-space: nowrap" data-container="body" data-toggle="tooltip" title="{{ $item[$column_names[$index]] == '' ? 'No entry in the database' : $item[$column_names[$index]] }}">
                                            {{ empty($item[$column_names[$index]]) ? '---' : $item[$column_names[$index]] }}
                                        </td>
                                    </tr>
                                @else
                                    <tr scope="row">
                                        <td class="table-wordwrap" style="white-space: nowrap" data-container="body" data-toggle="tooltip" title="{{ $item[$column_names[$index]] == '' ? 'No entry in the database' : $item[$column_names[$index]] }}">
                                            {{ empty($item[$column_names[$index]]) ? '---' : $item[$column_names[$index]] }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </td>
        @endforeach
    </tr>
</table>
</div>
<!-- Add report format modal-->
<div class="modal fade" id="add-report-format" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"><b>New Report Format</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="add-system-feature-form" action="{{ url('reportgenerator/report_format') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                    <div class="form-group">
                        <label for="feature_name">Select system feature</label>
                        <select class="form-control" name="system_feature_id" id="feature_name">
                            @foreach($system_features as $system_feature)
                                <option value="{{ $system_feature->id }}">{{ $system_feature->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="title">Name of report</label>
                        <input type="text" name="title" class="form-control" placeholder="Enter report name">
                    </div>
                    <div class="form-group">
                        <label for="description">Short description</label>
                        <textarea class="form-control" name="description" aria-label="Describe new report"></textarea>
                    </div>
                    <input type="hidden" name="option_ids" value="{{ $option_ids }}" class="form-control">
                    <hr />
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="submit" class="btn btn-info">Save</button>
                </form>
            </div>
        </div>
    </div>
</div><!-- /#add-report-format -->

@endsection
