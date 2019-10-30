<?php
use App\Http\Controllers\DocumentController as Doc;
use App\User as User;
use App\Section;
use App\Http\Controllers\ReleaseController as Rel;
use App\Tracking_Releasev2;
use App\Http\Controllers\DocumentController as document;
?>
@if(count($document))
    <style>
        .trackFontSize{
            font-size: 8pt;
        }
    </style>
    <div class="alert alert-warning">
        <div class="text-warning">
            <i class="fa fa-warning"></i> Documents that not accepted within 30 minutes will be reported
        </div>
    </div>
    <table class="table table-hover table-striped">
        <thead>
        <tr>
            <th width="14.2%">Received By</th>
            <th width="13.2%">Date In</th>
            <th width="14.2%">Duration</th>
            <th width="14.2%">Subject</th>
            <th width="14.2%">Released To</th>
            <th width="13.2%">Released Date</th>
            <th width="16.2%">Released Remarks</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $data = array();
        ?>
        @foreach($document as $doc)
            <?php
            if($doc->received_by!=0){
                $data['id'][] = $doc->id;
                if($user = User::find($doc->received_by)){
                    $data['received_by'][] = $user->fname.' '.$user->lname;
                    $data['section'][] = (Section::find($user->section)) ? Section::find($user->section)->description:'';
                } else {
                    $data['received_by'][] = "No Name".' '.$doc->received_by;
                    $data['section'][] = "No Section";
                }
                $data['date'][] = $doc->date_in;
                $data['date_in'][] = date('M d, Y', strtotime($doc->date_in));
                $data['time_in'][] = date('h:i A', strtotime($doc->date_in));
                $data['remarks'][] = $doc->action;
                $data['status'][] = $doc->status;
                $released = Tracking_Releasev2::where("document_id","=",$doc->id)->first();
                if($released){
                    if($released_section_to = Section::find($released->released_section_to)){
                        $data['released_section_to'][] = $released_section_to->description;
                    } else {
                        $data['released_section_to'][] = "No Data";
                    }
                    $data['released_date_time'][] = $released->released_date;
                    $data['released_date'][] = date('M d, Y', strtotime($released->released_date));
                    $data['released_time'][] = date('h:i A', strtotime($released->released_date));
                    $data['released_remarks'][] = $released->remarks;
                    if($released->status == 'report' || ($released->status == "waiting" && document::checkMinutes($released->released_date) > 30 ) ){
                        $data['released_status'][] = "<small class='text-danger'><i class='fa fa-thumbs-down'></i> (Reported)</small>";
                        $data['released_alert'][] = "alert alert-danger";
                    } elseif($released->status == 'accept') {
                        $data['released_status'][] = "<small style='color: #228e2f'><i class='fa fa-thumbs-up'></i> (Accepted)</small>";
                        $data['released_alert'][]  = "alert alert-success";
                    }
                    elseif($released->status == 'return') {
                        $data['released_status'][] = "<small style='color:#7626a6'><i class='fa fa-reply-all'></i> (Returned)</small>";
                        $data['released_alert'][]  = "";
                    }
                    else {
                        $data['released_status'][] = "<small class='text-warning'><i class='fa fa-refresh'></i> (Waiting to accept)</small>";
                        $data['released_alert'][]  = "";
                    }
                } else {
                    $data['released_alert'][]  = "";
                    $data['released_section_to'][] = "";
                    $data['released_date_time'][] = "";
                    $data['released_date'][] = "";
                    $data['released_time'][] = "";
                    $data['released_remarks'][] = "";
                    $data['released_status'][] = "";
                }
            }
            ?>
        @endforeach
        @for($i=0;$i<count($data['received_by']);$i++)
            <?php
            $received_success = 'text-success';
            $released_info = 'text-info';
            if($data['section'][$i]=='Unconfirmed' || $data['section'][$i]=='Returned')
            {
                $class = 'text-danger text-strong';
            }
            ?>

            <tr>
                <td class="text-bold trackFontSize {{ $received_success }}">{{ $data['received_by'][$i] }}
                    <br>
                    <small class="text-warning">({{ $data['section'][$i] }})</small>
                </td>
                <td class="trackFontSize {{ $received_success }}">
                    {{ $data['date_in'][$i] }}
                    <br>
                    {{ $data['time_in'][$i] }}
                </td>
                <td class="trackFontSize {{ $received_success }}">
                    <?php
                    $date = date('Y-m-d H:i:s');
                    if($i == 0){
                        if(empty($data['released_date_time'][$i])){
                            if(isset($data['date'][$i+1])){
                                $start_date = $data['date'][$i];
                                $end_date = $data['date'][$i+1];
                            }
                            else{
                                $start_date = $data['date'][$i];
                                $end_date = $date;
                            }
                        }
                        else {
                            $start_date = $data['date'][$i];
                            $end_date = $data['released_date_time'][$i];
                        }
                    }
                    else{
                        if(empty($data['released_date_time'][$i-1])){
                            if(isset($data['date'][$i+1])){
                                if(empty($data['released_date_time'][$i])){
                                    $start_date = $data['date'][$i];
                                    $end_date = $data['date'][$i+1];
                                }
                                else {
                                    $start_date = $data['date'][$i];
                                    $end_date = $data['released_date_time'][$i];
                                }
                            }
                            else {
                                if(empty($data['released_date_time'][$i])){
                                    $start_date = $data['date'][$i];
                                    $end_date = $date;
                                }
                                else {
                                    $start_date = $data['date'][$i];
                                    $end_date = $data['released_date_time'][$i];
                                }
                            }
                        } else {
                            if(isset($data['date'][$i+1])){
                                if(empty($data['released_date_time'][$i])){
                                    $start_date = $data['released_date_time'][$i-1];
                                    $end_date = $data['date'][$i+1];
                                } else {
                                    $start_date = $data['released_date_time'][$i-1];
                                    $end_date = $data['released_date_time'][$i];
                                }
                            }
                            else{
                                if(empty($data['released_date_time'][$i])){
                                    $start_date = $data['released_date_time'][$i-1];
                                    $end_date = $date;
                                }
                                else {
                                    $start_date = $data['released_date_time'][$i-1];
                                    $end_date = $data['released_date_time'][$i];
                                }
                            }
                        }
                    }
                    ?>
                    @if($data['status'][$i]==1 && $i == count($data['received_by'])-1)
                        Cycle End
                    @else
                        {{ Rel::duration($start_date,$end_date) }}
                    @endif
                </td>
                <td class="trackFontSize {{ $received_success }}">{!! nl2br($data['remarks'][$i]) !!}</td>
                <td class="trackFontSize text-bold {{ $released_info }}">
                    {{ $data['released_section_to'][$i] }}
                    <br>
                    {!! $data['released_status'][$i] !!}
                </td>
                <td class="trackFontSize {{ $released_info }}">
                    {{ $data['released_date'][$i] }}
                    <br>
                    {{ $data['released_time'][$i] }}
                </td>
                <td class="trackFontSize {{ $released_info }}">{!! nl2br($data['released_remarks'][$i]) !!}</td>
            </tr>
        @endfor
        </tbody>
    </table>
@else
    <div class="alert alert-danger">
        <i class="fa fa-times"></i> No tracking history!
    </div>
@endif