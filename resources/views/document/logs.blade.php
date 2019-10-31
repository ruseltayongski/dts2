<?php
use App\Users;
use App\Section;
use App\Http\Controllers\DocumentController as Doc;
use App\Division;
use App\Release;
use App\Http\Controllers\ReleaseController as Rel;

$code = Session::get('doc_type_code');
?>
@extends('layouts.app')

@section('content')
    <style>
        .input-group {
            margin:5px 0;
        }
        label {
            padding:2px 0px;
        }
        .btn-xs {
            margin:2px 0px;
        }
    </style>
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
        <h2 class="page-header">Document Logs</h2>
        <form class="form-inline" method="POST" action="{{ asset('document/logs') }}" onsubmit="return searchDocument()">
            {{ csrf_field() }}
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-search"></i>
                    </div>
                    <input type="text" class="form-control" name="keywordLogs" value="{{ isset($keywordLogs) ? $keywordLogs: null }}" placeholder="Input keyword...">
                </div>
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control" id="reservation" name="daterange" value="{{ isset($daterange) ? $daterange: null }}" placeholder="Input date range here..." required>
                </div>
                <div class="input-group">
                    <select data-placeholder="Select Document Type" name="doc_type" class="chosen-select-static" tabindex="5" required>
                        <option value=""></option>
                        <option value="ALL" <?php if($code=='ALL') echo 'selected';?>>All Documents</option>
                        <optgroup label="Disbursement Voucher">
                            <option <?php if($code=='SAL') echo 'selected'; ?> value="SAL">Salary, Honoraria, Stipend, Remittances, CHT Mobilization</option>
                            <option <?php if($code=='TEV') echo 'selected'; ?> value="TEV">TEV</option>
                            <option <?php if($code=='BILLS') echo 'selected'; ?> value="BILLS">Bills, Cash Advance Replenishment, Grants/Fund Transfer</option>
                            <option <?php if($code=='PAYMENT') echo 'selected'; ?> value="PAYMENT">Supplier (Payment of Transactions with PO)</option>
                            <option <?php if($code=='INFRA') echo 'selected'; ?> value="INFRA">Infra - Contractor</option>
                        </optgroup>
                        <optgroup label="Letter/Mail/Communication">
                            <option value="INCOMING">Incoming</option>
                            <option>Outgoing</option>
                            <option>Service Record</option>
                            <option>SALN</option>
                            <option>Plans (includes Allocation List)</option>
                            <option value="ROUTE">Routing Slip</option>
                        </optgroup>
                        <optgroup label="Management System Documents">
                            <option>Memorandum</option>
                            <option>ISO Documents</option>
                            <option>Appointment</option>
                            <option>Resolutions</option>
                        </optgroup>
                        <optgroup label="Miscellaneous">
                            <option value="WORKSHEET">Activity Worksheet</option>
                            <option value="JUST_LETTER">Justification</option>
                            <option>Certifications</option>
                            <option>Certificate of Appearance</option>
                            <option>Certificate of Employment</option>
                            <option>Certificate of Clearance</option>
                        </optgroup>
                        <optgroup label="Personnel Related Documents">
                            <option <?php if($code=='OFFICE_ORDER') echo 'selected'; ?> value="OFFICE_ORDER">Office Order</option>
                            <option>DTR</option>
                            <option <?php if($code=='APP_LEAVE') echo 'selected'; ?> value="APP_LEAVE">Application for Leave</option>
                            <option>Certificate of Overtime Credit</option>
                            <option>Compensatory Time Off</option>
                        </optgroup>
                        <option <?php if($code=='PO') echo 'selected'; ?> value="PO">Purchase Order</option>
                        <option <?php if($code=='PRC') echo 'selected'; ?> value="PRC">Purchase Request - Cash Advance Purchase</option>
                        <option <?php if($code=='PRR') echo 'selected'; ?> value="PRR">Purchase Request - Regular Purchase</option>
                        <option>Reports</option>
                        <option <?php if($code=='GENERAL') echo 'selected'; ?> value="GENERAL">General Documents</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success" onclick="checkDocTye()"><i class="fa fa-search"></i> Filter</button>
                @if(count($documents))
                    <a target="_blank" href="{{ asset('pdf/logs/'.$doc_type) }}" class="btn btn-warning"><i class="fa fa-print"></i> Print Logs</a>
                @endif
            </div>
        </form>
        <div class="clearfix"></div>
        <div class="page-divider"></div>
        <?php $status = session('status'); ?>
        @if($status=='releaseAdded')
        <div class="alert alert-success">
            <i class="fa fa-check"></i> Successfully released!
        </div>
        @endif


        @if($status=='reportCancelled')
            <div class="alert alert-success">
                <i class="fa fa-check"></i> Successfully cancelled!
            </div>
        @endif
        @if($status=='releaseUpdated')
            <div class="alert alert-success">
                <i class="fa fa-check"></i> Successfully updated!
            </div>
        @endif
        <div class="alert alert-danger error hide">
            <i class="fa fa-warning"></i> Please select Document Type!
        </div>
        @if(count($documents))
            <table class="table table-list table-hover table-striped">
                <thead>
                <tr>
                    <th width="8%"></th>
                    <th width="18%">Route No</th>
                    <th width="15%">Document Created By</th>
                    <th width="10%">Created Remarks</th>
                    <th width="10%">Accepted Remarks</th>
                    <th width="10%">Released To</th>
                </tr>
                </thead>
                <tbody>
                @foreach($documents as $doc)
                    <?php $doc_type = \App\Http\Controllers\DocumentController::docTypeName($doc->doc_type); ?>
                    <tr>
                        <td>
                            <a href="#track" data-link="{{ asset('document/track/'.$doc->route_no) }}" data-route="{{ $doc->route_no }}" data-toggle="modal" class="btn btn-sm btn-success col-sm-12"><i class="fa fa-line-chart"></i> Track</a>
                        </td>
                        <td>
                            <a class="title-info" data-route="{{ $doc->route_no }}" data-link="{{ asset('/document/info/'.$doc->route_no) }}" href="#document_info" data-toggle="modal">{{ $doc->route_no }}</a>
                            <br>
                            <small class="text-success">({{ $doc_type }})</small>
                        </td>
                        <td>
                            <?php $user = Users::find($doc->prepared_by);?>
                            <span >{{ $user->fname }} {{ $user->lname }}</span>
                            <br>
                            <small class="text-success">({{ Section::find($user->section)->description }})</small>
                        </td>
                        <td>
                            @if($doc->doc_type == 'PRR_S')
                                <span>{{ $doc->tracking_master_purpose }}</span>
                            @else
                                <span>{{ $doc->tracking_master_remarks }}</span>
                            @endif
                            <br>
                            <small class="text-success">({{ date('M d, Y',strtotime($doc->tracking_master_prepared_by)) }}, <i>{{ date('h:i:s A',strtotime($doc->tracking_master_prepared_by)) }}</i>)</small>
                        </td>
                        <td>
                            <span >{{ $doc->tracking_details_remarks }}</span><br>
                            <small class="text-success">({{ date('M d, Y',strtotime($doc->date_in)) }}, <i>{{ date('h:i:s A',strtotime($doc->date_in)) }}</i>)</small>
                        </td>
                        <td>
                            <?php
                                $released_checker = \App\Tracking_Releasev2::where("document_id","=",$doc->tracking_details_id)
                                                                            ->where("released_by","=",Auth::user()->id)
                                                                            ->first();
                            ?>
                            @if($released_checker)
                                {{ Section::find($released_checker->released_section_to)->description }}
                                <br />
                                <button data-toggle="modal" data-target="#releaseTo" data-id="{{ $doc->tracking_details_id }}" data-route_no="{{ $doc->route_no }}" onclick="" type="button" class="btn btn-info btn-xs"><i class="fa fa-send"></i> Change</button>
                                <a href="{{ asset('document/report/'.$doc->tracking_details_id .'/cancel') }}" class="btn btn-xs btn-danger"><i class="fa fa-times"></i> Cancel</a>
                            @else
                                @if($doc->status==1)
                                    <div class="text-info">
                                        [ Cycle End ]
                                    </div>
                                @endif
                                <button data-toggle="modal" data-target="#releaseTo" data-id="{{ $doc->tracking_details_id }}" data-route_no="{{ $doc->route_no }}" onclick="putRoute($(this))" type="button" class="btn btn-info btn-sm"><i class="fa fa-send"></i> Release To</button> <!-- if wala na exist sa release -->
                            @endif
                        </td>
                        <?php
                            $out = Doc::deliveredDocument($doc->route_no,$doc->received_by,$doc->doc_type);
                            $class ='';
                        ?>
                        @if($out)
                        <?php
                            if($out->received_by==0){
                                $class = 'danger';
                            }
                        ?>
                        <td class="text-<?php echo $class?>">
                            @if($out->received_by==0)
                                <?php
                                    $string = $out->code;
                                    $temp1   = explode(';',$string);
                                    $temp2   = array_slice($temp1, 1, 1);
                                    $section_id = implode(',', $temp2);
                                    echo Section::find($section_id)->description;
                                ?>
                                <br />
                                <button data-toggle="modal" data-target="#releaseTo" data-id="{{ $out->id }}" data-route_no="{{ $out->route_no }}" onclick="changeRoute($(this), '<?php echo $out->id ?>')" type="button" class="btn btn-info btn-xs"><i class="fa fa-send"></i> Change</button>
                                <a href="{{ asset('document/report/'.$out->id .'/cancel') }}" class="btn btn-xs btn-danger"><i class="fa fa-times"></i> Cancel</a>
                            @else
                                <?php $user = Users::find($out->received_by);?>
                                {{ $user->fname }}
                                {{ $user->lname }}
                                <br>
                                <em>({{ Section::find($user->section)->description }})</em>
                            @endif
                        </td>
                        @else
                            <?php $check_released = \App\Tracking_Releasev2::where("route_no","=",$doc->route_no)->where("released_by","=",Auth::user()->id)->orderBy('id','desc')->first() ?>
                            @if($check_released)
                                <?php
                                    $now = date('Y-m-d H:i:s');
                                    $time = Rel::hourDiff($rel->date_reported,$now);
                                ?>
                                <td class="text-info">
                                    {{ date('M d, Y',strtotime($rel->date_reported)) }}<br>
                                    {{ date('h:i:s A',strtotime($rel->date_reported)) }}<br>
                                </td>
                                <td class="text-info">
                                    {{ Section::find($rel->section_id)->description }}
                                    <br />
                                    @if($rel->status==0)
                                        <button data-toggle="modal" data-target="#releaseTo" data-route_no="{{ $doc->route_no }}" onclick="changeRoute($(this), '<?php echo $rel->id ?>')" type="button" class="btn btn-info btn-xs"><i class="fa fa-send"></i> Change</button> <!-- if na exist sa release -->
                                        <a href="{{ asset('document/report/'.$rel->id .'/cancel/status') }}" class="btn btn-xs btn-danger"><i class="fa fa-times"></i> Cancel</a>
                                    @endif
                                </td>
                            @else
                                <td class="text-center" style="vertical-align: middle;">
                                    @if($doc->status==1)
                                        <div class="text-info">
                                            [ Cycle End ]
                                        </div>
                                    @endif
                                    <button data-toggle="modal" data-target="#releaseTo" data-id="{{ $doc->tracking_details_id }}" data-route_no="{{ $doc->route_no }}" onclick="putRoute($(this))" type="button" class="btn btn-info btn-sm"><i class="fa fa-send"></i> Release To</button> <!-- if wala na exist sa release -->
                                </td>
                            @endif
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $documents->links() }}
        @else
            <div class="alert alert-warning">
                <strong><i class="fa fa-warning fa-lg"></i> No documents found! </strong>
            </div>
        @endif
    </div>
    <input type="hidden" id="getsection" value="{{ asset('getsections/') }}">
    @include('modal.release_modal')

@endsection
    @section('plugin')


@endsection


@section('css')

@endsection


