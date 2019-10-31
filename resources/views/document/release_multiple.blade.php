@extends('layouts.app')
@section('content')
    <div class="col-md-12 wrapper">
        <div class="alert alert-jim">
            @if (session('status'))
                <?php
                $status = session('status');
                ?>
                @if(isset($status['success']))
                    <div class="alert alert-success">
                        <ul>
                            @foreach ($status['success'] as $success)
                                <li>{!! $success !!}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(isset($status['errors']))
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($status['errors'] as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endif
            <h2 class="page-header">Release Documents</h2>
            <form class="form-submit" method="post">
                {{ csrf_field() }}
                <div class="modal fade" tabindex="-1" role="dialog" id="releaseTo" style="margin-top: 30px;z-index: 99999;">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <h4 class="text-success"><i class="fa fa-send"></i> Select Destination</h4>
                                <hr />
                                {{ csrf_field() }}
                                <input type="hidden" name="route_no" id="route_no">
                                <input type="hidden" name="op" id="op" value="0">
                                <input type="hidden" name="currentID" id="currentID" value="0">
                                <div class="form-group">
                                    <label>Division</label>
                                    <select name="division" class="chosen-select filter-division" required>
                                        <option value="">Select division...</option>
                                        <?php $division = \App\Division::where('description','!=','Default')->orderBy('description','asc')->get(); ?>
                                        @foreach($division as $div)
                                            <option value="{{ $div->id }}">{{ $div->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Section</label>
                                    <select name="section" class="chosen-select filter_section" required>
                                        <option value="">Select section...</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Remarks</label>
                                    <textarea name="remarks" class="form-control" rows="5" style="resize: vertical;" placeholder="Please enter your remark(s) of return..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                                <button type="submit" class="btn btn-success btn-submit" onclick="checkDestinationForm()"><i class="fa fa-send"></i> Release Document</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th>Accepted By</th>
                        <th>Date In</th>
                        <th>Route No / Barcode</th>
                        <th>Remarks</th>
                    </tr>
                    </thead>
                    <tbody>
                    @for($i=0;$i<10;$i++)
                        <tr>
                            <td>
                                {{ Auth::user()->fname }} {{ Auth::user()->lname }}
                            </td>
                            <td>
                                {{ date('M d, Y h:i:s A') }}
                            </td>
                            <td>
                                <input type="text" name="route_no[]" class="form-control route_no" disabled placeholder="Enter route #">
                            </td>
                            <td>
                                <input type="text" name="remarks[]" class="form-control remarks" disabled placeholder="Enter remarks">
                            </td>
                        </tr>
                    @endfor
                    <tr>
                        <td colspan="4" class="text-right">
                            <button data-toggle="modal" data-target="#releaseTo" type="button" class="btn btn-primary btn-lg btn-accept btn-submit"><i class="fa fa-bank"></i> Select Section</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="clearfix"></div><br>
                <div class="alert alert-danger error-accept hide">Please input route number!</div>
            </form>
            <hr />
            <div class="accepted-list">

            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $('.filter-division').on('change',function(){
            checkDestinationForm();
            var id = $(this).val();
            var url = "<?php echo asset('getsections/');?>";
            $('.loading').show();
            $('.filter_section').html('<option value="">Select section...</option>');
            $.ajax({
                url: url+'/'+id,
                type: "GET",
                success: function(sections){
                    jQuery.each(sections,function(i,val){
                        $('.filter_section').append($('<option>', {
                            value: val.id,
                            text: val.description
                        }));
                        $('.filter_section').chosen().trigger('chosen:updated');
                        $('.filter_section').siblings('.chosen-container').css({border:'2px solid red'});
                    });
                    $('.loading').hide();
                }
            })
        });

        function checkDestinationForm(){
            var division = $('.filter-division').val();
            var section = $('.filter_section').val();
            if(division.length == 0){
                $('.filter-division').siblings('.chosen-container').css({border:'2px solid red'});
            }else{
                $('.filter-division').siblings('.chosen-container').css({border:'none'});
            }

            if(section.length == 0){
                $('.filter_section').siblings('.chosen-container').css({border:'2px solid red'});
            }else{
                $('.filter_section').siblings('.chosen-container').css({border:'none'});
            }
        }

        $(window).load(function(){
            $('.route_no').prop("disabled", false); // Element(s) are now enabled.
            $('.remarks').prop("disabled", false); // Element(s) are now enabled.
        });

    </script>
@endsection