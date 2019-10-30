@extends('layouts.app')

@section('content')
    <span id="url" data-link="{{ asset('/designation') }}"></span>
    <div class="alert alert-jim" id="inputText">
        <h2 class="page-header">Designations</h2>
        <form class="form-inline form-accept" action="{{ asset('search/designation') }}" id="search_designation" method="GET">
            <div class="form-group">
                <input type="text" class="form-control" name="search" placeholder="Quick Search" autofocus>
                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Search</button>
                <div class="btn-group">
                    <a class="btn btn-success dropdown-toggle" data-toggle="dropdown" data-link="{{ asset('/designation/create') }}" href="#new">
                        <i class="fa fa-plus"></i>  Add New
                    </a>
                </div>
            </div>
        </form>
        <div class="clearfix"></div>
        <div class="page-divider"></div>
        @if(count($designations) > 0)
            <div class="table-responsive">
                <table class="table table-list table-hover table-striped">
                    <thead>
                    <tr>
                        <th>Description</th>
                        <th width="20%">Option</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($designations as $d)
                        <tr>
                            <td class="title-info">{{ $d->description }}</td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm" data-id="{{ $d->id }}" onclick="edit_designation(this);"><i class="fa fa-pencil"></i> Update </button>
                                <button type="button" class="btn btn-danger btn-sm" data-id="{{ $d->id }}" onclick="delete_designation(this);"><i class="fa fa-trash"></i> Delete </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $designations->links() }}
        @else
            <div class="alert alert-danger">
                <strong><i class="fa fa-times fa-lg"></i>Record is empty.</strong>
            </div>
        @endif
    </div>
    <span data-link="{{ asset('/remove/designation') }}" id="delete"></span>
    <span data-link="{{ asset('/edit/designation') }}" id="edit"></span>
    <span id="token" data-token="{{ csrf_token() }}"></span>
@endsection
@section('plugin')
    <script src="{{ asset('resources/plugin/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('resources/plugin/daterangepicker/daterangepicker.js') }}"></script>
@endsection

@section('css')
    <link href="{{ asset('resources/plugin/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet">
@endsection
@section('js')
    @@parent
    <script>
        $('#search_designation').submit(function(){
           $(this).submit();
        });
    </script>
@endsection