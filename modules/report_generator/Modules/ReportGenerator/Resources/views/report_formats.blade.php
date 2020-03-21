@extends('reportgenerator::layouts.master')

@section('title', 'Report Formats')

@section('content')
    &nbsp;
    <table class="table">
        <thead class="thead-light">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Description</th>
                <th scope="col">System Feature</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody> @php ($i = 0)
            @foreach($report_formats as $report_format)
                <tr>
                  <th scope="row">{{ ++$i }}</th>
                  <td>{{ $report_format->title }}</td>
                  <td>{{ $report_format->description }}</td>
                  <td>{{ $report_format->system_feature->name }}</td>

                  <td>
                      <a class="btn btn-success btn-sm" href="{{ url('/reportgenerator/report_format/view/'.$report_format->id) }}" role="button"> View</a>
                      <a class="btn btn-primary btn-sm" href="#" role="button" data-toggle="modal" data-target="#edit-report-format" data-id="{{ $report_format->id }}" data-system-feature-name= "{{ $report_format->system_feature->name }}" data-title="{{ $report_format->title }}" data-description="{{ $report_format->description }}"> Edit</a>
                      <a class="btn btn-danger btn-sm" href="#" role="button" data-toggle="modal" data-target="#delete-report-format" data-id="{{ $report_format->id }}" data-title="{{ $report_format->title }}"> Delete</a>
                  </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
