@extends('reportgenerator::layouts.master')

@section('title', 'System Features')

@section('content')
    &nbsp;
    <table class="table">
        <thead class="thead-light">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Description</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody> @php ($i = 0)
            @foreach($system_features as $system_feature)
                <tr>
                  <th scope="row">{{ ++$i }}</th>
                  <td>{{ $system_feature->name }}</td>
                  <td>{{ $system_feature->description }}</td>
                  <td>
                      <a class="btn btn-primary btn-sm" href="#" role="button" data-toggle="modal" data-target="#edit-system-feature" data-id="{{ $system_feature->id }}" data-feature_name="{{ $system_feature->name }}" data-description="{{ $system_feature->description }}"> Edit</a>
                      <a class="btn btn-danger btn-sm" href="#" role="button" data-toggle="modal" data-target="#delete-system-feature" data-id="{{ $system_feature->id }}" data-feature_name="{{ $system_feature->name }}" data-description="{{ $system_feature->description }}"> Delete</a>
                  </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
