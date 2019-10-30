@extends('layouts.app')

@section('content')
<div class="col-md-9 wrapper">
    <div class="alert alert-jim">
        <h3 class="page-header">Created
            <small>Documents</small>
        </h3>
        <canvas id="createdDoc" width="400" height="200"></canvas>
        <h3 class="page-header">Accepted
            <small>Documents</small>
        </h3>
        <canvas id="acceptedDoc" width="400" height="200"></canvas>
    </div>
</div>
@include('sidebar')
<div class="modal fade" tabindex="-1" role="dialog" id="notificationModal" style="margin-top: 30px;z-index: 99999 ">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <h3 style="font-weight: bold" class="text-success">WHAT'S NEW?</h3>
                <?php
                $dateNow = date('Y-m-d');
                ?>
                @if($dateNow==='2019-07-30')
                    <div class="alert alert-info">
                        <p class="text-info" style="font-size:1.3em;text-align: center;">
                            <strong>There will be a server maintenance TODAY (July 30, 2019) at 1:15PM to 02:00PM. Server optimization!</strong>
                        </p>
                    </div>
                @endif

                <div class="alert alert-success ">
                    <span class="text-success">
                        <i class="fa fa-info"></i>
                        Good day everyone! We would like to inform you that we will be encoding of Online PPMPV2.0 as soon as possible, just prepare your PPMP
                        <ul>
                            <li>Please be guided this ppmp master list as you start using the latest version</li>
                            <ul>
                                <li><a href="{{ asset('resources/ppmp_division/msd.pdf') }}" download>MSD PDF</a></li>
                                <li><a href="{{ asset('resources/ppmp_division/lhsd.pdf') }}" download>LHSD PDF</a></li>
                                <li><a href="{{ asset('resources/ppmp_division/rd_ard.pdf') }}" download>RD ARD PDF</a></li>
                                <li><a href="{{ asset('resources/ppmp_division/rled.pdf') }}" download>RLED PDF</a></li>
                            </ul>
                        </ul>
                        <h3 class="text-center" style="color: #2f8030">Thank you! &#128512;</h3>
                    </span>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


@endsection

@section('js')
<script src="{{ asset('resources/plugin/Chart.js/Chart.min.js') }}"></script>
<script>
    //$('#notificationModal').modal('show');
    <?php echo 'var url = "'.asset('home/chart').'";';?>
    var jim = [];
    $.ajax({
        url: url,
        type: 'GET',
        success: function(data) {
            jim = jQuery.parseJSON(data);
            //chart created docs
            var ctx = document.getElementById("createdDoc");
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: jim.data1.months,
                    datasets: [{
                        label: '# of Created Documents',
                        data: jim.data1.count,
                        backgroundColor: [
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 206, 86, 1)',
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    }
                }
            });
            //end chart created docs
            //chart accepted docs
            var ctx = document.getElementById("acceptedDoc");
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: jim.data2.months,
                    datasets: [{
                        label: '# of Accepted Documents',
                        data: jim.data2.count,
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    }
                }
            });
            //end chart accepted docs
            $('.loading').hide();
        }
    });
</script>
@endsection

