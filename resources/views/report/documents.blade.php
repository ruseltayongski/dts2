<?php
    use App\Http\Controllers\SectionController as Sec;
    use App\Http\Controllers\AdminController as Admin;
?>
@extends('layouts.app')

@section('content')

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="alert alert-jim" id="inputText">
        <h2 class="page-header">Print Report</h2>
        <form class="form-inline" method="POST" action="{{ asset('document/report') }}" onsubmit="return searchDocument()">
            {{ csrf_field() }}
            @foreach($division as $div)
            <?php
                $sections = Sec::getSections($div->id);
                $totalAccepted = 0;
                $totalCreated = 0;
            ?>
            <table class="table table-striped table-hover" style="border: 1px solid #d6e9c6">
                <thead>
                    <tr>
                        <th colspan="3" class="bg-success text-bold text-success text-uppercase" style="padding: 15px 10px;">{{ $div->description }}</th>
                    </tr>
                    <tr>
                        <th class="col-sm-6">Sections</th>
                        <th class="col-sm-3">Created Documents</th>
                        <th class="col-sm-3">Accepted Documents</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sections as $sect)
                    <?php
                        $accepted = Admin::countAccepted($sect->id);
                        $created = Admin::countCreated($sect->id);

                        $totalAccepted += $accepted;
                        $totalCreated += $created;
                    ?>
                    <tr>
                        <td>{{ $sect->description }}</td>
                        <td>
                            @if($created==0)
                                Nothing
                            @elseif($created==1)
                                1 Document
                            @else
                                {{ $created }} Documents
                            @endif
                        </td>
                        <td>
                            @if($accepted==0)
                                Nothing
                            @elseif($accepted==1)
                                1 Document
                            @else
                                {{ $accepted }} Documents
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td class="bg-warning text-bold text-uppercase">TOTAL</td>
                        <td class="bg-warning text-bold text-uppercase">{{ $totalCreated }}</td>
                        <td class="bg-warning text-bold text-uppercase">{{ $totalAccepted }}</td>
                    </tr>
                </tbody>
            </table>
            @endforeach
        </form>
        <div class="clearfix"></div>
        <div class="page-divider"></div>
        <div class="alert alert-danger error hide">
            <i class="fa fa-warning"></i> Please select Document Type!
        </div>
    </div>
@endsection
@section('plugin')

@endsection

@section('css')

@endsection

