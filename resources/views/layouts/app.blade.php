<?php
use App\Section;
use App\Release;
use Illuminate\Support\Facades\Session;
if(!Session::get('is_login')){
    \App\Http\Controllers\SystemController::logDefault('Logged In');
    Session::put('is_login',true);
}
$user = Auth::user();
$code = 'temp;'.$user->section;
$pending = \App\Tracking_Details::select(
            'date_in',
            'id',
            'route_no',
            'received_by',
            'code',
            'delivered_by',
            'action'
        )
        ->where('code',$code)
        ->where('status',0)
        ->count();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('resources/img/favicon.png') }}">
    <meta http-equiv="cache-control" content="max-age=0" />
    <title>Document Tracking System</title>
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('resources/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('resources/assets/css/bootstrap-theme.min.css') }}" rel="stylesheet">
    <link href="{{ asset('resources/assets/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="{{ asset('resources/assets/css/ie10-viewport-bug-workaround.css') }}" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="{{ asset('resources/assets/css/style.css') }}" rel="stylesheet">
    <!-- bootstrap datepicker -->
    <link href="{{ asset('resources/plugin_old/datepicker/datepicker3.css') }}" rel="stylesheet">

    <title>
        @yield('title','Home')
    </title>

    <!--DATE RANGE-->
    <link href="{{ asset('resources/plugin_old/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet">
    <!--CHOOSEN SELECT -->
    <link href="{{ asset('resources/plugin_old/chosen/chosen.css') }}" rel="stylesheet">
    <!-- bootstrap wysihtml5 - text editor -->
    <link href="{{ asset('resources/plugin_old/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('resources/plugin_old//Lobibox/lobibox.css') }}" rel="stylesheet">
    @yield('css')
    <style>
        body {
            background: url('{{ asset('resources/img/backdrop.png') }}'), -webkit-gradient(radial, center center, 0, center center, 460, from(#ccc), to(#ddd));
        }
        .loading {
            opacity:0.4;
            background:#ccc url('{{ asset('resources/img/spin.gif')}}') no-repeat center;
            position:fixed;
            width:100%;
            height:100%;
            top:0px;
            left:0px;
            z-index:999999999;
            display: none;
        }

    </style>
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="{{ asset('resources/assets/js/ie-emulation-modes-warning.js') }}"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<!-- Fixed navbar -->

<nav class="navbar navbar-default navbar-static-top">
    <div class="header" style="background-color:#2F4054;padding:10px;">
        <div class="col-md-4">
            <span class="title-info">Welcome,</span> <span class="title-desc">{{ Auth::user()->fname }} {{ Auth::user()->lname }}</span>
        </div>
        <div class="col-md-4">
            <span class="title-info">Section:</span>
            <span class="title-desc">
                <?php $section = Section::find(Auth::user()->section) ? Section::find(Auth::user()->section)->description: 'No Section'; ?>
                {{ $section }}
            </span>
        </div>
        <div class="col-md-4">
            <span class="title-info">Date:</span> <span class="title-desc">{{ date('M d, Y') }}</span>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="header" style="background-color:#00CC99;padding:10px;">
        <div class="container">
            <img src="{{ asset('resources/img/banner_dts2019.png') }}" class="img-responsive" />
        </div>
    </div>
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"></a>
        </div>

        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="{{ url('/home') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-file-code-o"></i>&nbsp;
                        Documents
                        @if($pending > 0)
                            <span class="badge" style="background:#eb9316;">{{ $pending }}</span>
                        @endif
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        @if($pending > 0)
                            <li style="background:#eb9316;"><a href="{{ asset('document/pending') }}"><i class="fa fa-warning"></i>&nbsp;&nbsp; Pending Document</a></li>
                        @else
                            <li><a href="{{ asset('document/pending') }}"><i class="fa fa-hourglass-1"></i>&nbsp;&nbsp; Pending Documents</a></li>
                        @endif
                        <li class=""><a href="{{ asset('document/accept')  }}"><i class="fa fa-plus"></i>&nbsp;&nbsp; Accept Document</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ asset('document') }}"><i class="fa fa-file"></i>&nbsp;&nbsp; My Documents</a></li>
                        @if(Auth::user()->user_priv==1 || Auth::user()->username=='2002000972')
                        <li><a href="{{ asset('document/list') }}"><i class="fa fa-file"></i>&nbsp;&nbsp; All Documents</a></li>
                        @endif
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-print"></i> View Logs <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ URL::to('document/logs') }}"><i class="fa fa-file-archive-o"></i>&nbsp;&nbsp; Personal Logs</a></li>
                        <li class=""><a href="{{ URL::to('document/section/logs') }}"><i class="fa fa-file-archive-o"></i>&nbsp;&nbsp; Section Logs</a></li>
                        @if(Auth::user()->user_priv==1)
                        <li class="divider"></li>
                        <li><a href="{{ URL::to('report') }}"><i class="fa fa-bar-chart"></i>&nbsp;&nbsp; Print Report</a></li>
                        <li class="dropdown-submenu">
                            <a href="#" data-toggle="dropdown"><i class="fa fa-file"></i>&nbsp;&nbsp; Reported Documents</a>
                            <ul class="dropdown-menu">
                                @for($year=2018;$year<=date('Y');$year++)
                                <li><a href="{{ url('reportedDocuments').'/'.$year }}"><i class="fa fa-sticky-note"></i> {{ $year }}</a></li>
                                @endfor
                            </ul>
                        </li>
                        @endif
                    </ul>
                </li>
                @if(Auth::user()->user_priv==1)
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-gear"></i> Settings<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ asset('/users')  }}"><i class="fa fa-users"></i>&nbsp;&nbsp; Users</a></li>
                            <li class="divider"></li>
                            <li><a href="{{ asset('/designation') }}"><i class="fa fa-arrow-right"></i>&nbsp;&nbsp; Designation</a></li>
                            <li><a href="{{ asset('/section') }}"><i class="fa fa-arrow-right"></i>&nbsp;&nbsp; Section</a></li>
                            <li><a href="{{ asset('/division') }}"><i class="fa fa-arrow-right"></i>&nbsp;&nbsp; Division</a></li>
                            <li class="divider"></li>
                            <li><a href="{{ asset('document/filter') }}"><i class="fa fa-filter"></i>&nbsp;&nbsp; Filter Documents</a></li>
                            <li><a href="{{ asset('users/feedback') }}"><i class="fa fa-bullhorn"></i>&nbsp;&nbsp; User Feedbacks <span class="badge">{{ \App\Feedback::where('is_read','0')->count() }}</span></a></li>
                        </ul>
                    </li>
                @endif
                @if(Auth::user()->user_priv==0)
                <li>
                    <a href="javascript:void(0)" data-link="{{ asset('feedback') }}" id="feedback" title="Write a feedback" data-trigger="focus" data-container="body"  data-placement="top" data-content="Help us improve our system by just sending feedback.">
                        <i class="fa fa-sign-out"></i> Feedback
                    </a>
                </li>
                @endif
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i>
                        Account
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ asset('/change/password')  }}"><i class="fa fa-unlock"></i>&nbsp;&nbsp; Change Password</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ url('/logout') }}"><i class="fa fa-sign-out"></i>&nbsp;&nbsp; Logout</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="active"><a href="#trackDoc" data-toggle="modal"><i class="fa fa-search"></i> Track Document</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="container">
    <div class="loading"></div>
    @yield('content')
    <div class="clearfix"></div>
</div> <!-- /container -->
<footer class="footer">
    <div class="container">
        <p class="pull-right">
            <?php
                use App\Http\Controllers\DocumentController as Doc;
                $online = Doc::countOnlineUsers();
            ?>
            <a href="#online" data-toggle="modal" class="online" style="color:#fff;" data-url="{{ asset('online') }}">
            @if($online<=1)
                {{ $online }} Online User | <i class="fa fa-user"></i>
            @else
                {{ $online }} Online Users | <i class="fa fa-users"></i>
            @endif
            </a>
        </p>
        <p>All Rights Reserved 2017 | Version 5.0</p>

    </div>
</footer>
@include('modal')
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="{{ asset('resources/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('resources/assets/js/jquery-validate.js') }}"></script>
<script src="{{ asset('resources/assets/js/bootstrap.min.js') }}"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="{{ asset('resources/assets/js/ie10-viewport-bug-workaround.js') }}"></script>
<script>var loadingState = '<center><img src="{{ asset('resources/img/spin.gif') }}" width="150" style="padding:20px;"></center>'; </script>
<!-- bootstrap datepicker -->
<script src="{{ asset('resources/plugin_old/datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('resources/assets/js/script.js') }}?v=1"></script>
<script src="{{ asset('resources/assets/js/form-justification.js') }}"></script>
<script src="{{ asset('resources/plugin_old/daterangepicker/moment.min.js') }}"></script>
<!-- DATE RANGE SELECT -->
<script src="{{ asset('resources/plugin_old/daterangepicker/daterangepicker.js') }}"></script>
<!-- NUMERAL JS -->
<script src="{{ asset('resources/assets/js/Numeral-js/src/numeral.js') }}"></script>
<!-- SELECT CHOOSEN -->
<script src="{{ asset('resources/plugin_old/chosen/chosen.jquery.js') }}"></script>
<!-- CKEDITOR -->
<script src="{{ asset('resources/plugin_old/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('resources/plugin_old/ckeditor/adapters/jquery.js') }}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset('resources/plugin_old/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
<script src="{{ asset('resources/plugin_old/Lobibox/Lobibox.js') }}"></script>
@yield('plugin_old')
<?php
use App\Tracking_Details;
$incoming = Tracking_Details::select(
        'date_in',
        'id',
        'route_no',
        'received_by',
        'code',
        'delivered_by',
        'action'
)
        ->where('code',$code)
        ->where('status',0)
        ->where('alert','>=',1)
        ->where('alert','<=',2)
        ->orderBy('tracking_details.date_in','desc')
        ->get();
?>
@if(count($incoming) > 0)
<script>
    $('#notification').modal('show');
</script>
@endif
<script>
    $('#reservation').daterangepicker();
    $('.daterange').daterangepicker();
    $('.chosen-select').chosen({width: "100%"});
    $('.chosen-select-static').chosen();

    function checkDocTye(){
        var doc = $('select[name="doc_type"]').val();
        if(doc.length == 0){
            $('.error').removeClass('hide');
        }
    }
</script>
<script>
    $('.form-submit').on('submit',function(){
        $('.btn-submit').attr('disabled',true);
    });
    function searchDocument(){
        $('.loading').show();
        setTimeout(function(){
            return true;
        },1000);
    }

    $("a[href='#feedback']").on('click',function(){
        alert("Hello");
    });

    (function(){
//        $('#feedback').popover('show');
//        setTimeout(function(){
//            $('#feedback').popover('hide');
//        },2000);

        $('#feedback').click(function(){
            $('#feedback').popover('hide');
            $('#document_form').modal('show');
            $('.modal_content').html(loadingState);
            $('.modal-title').html($(this).html());
            var url = $(this).data('link');

            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    $('.modal_content').html(data);
                    $('#create').attr('action', url);
                    $('input').attr('autocomplete', 'off');
                }
            });
        });
    })();

    $('.online').on('click',function(){
        var url = $(this).data('url');
        $('.onlineContent').html(loadingState);
        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                setTimeout(function(){
                    var content='';

                    jQuery.each(data, function(i,val){
                        content += '<tr>' +
                                '<td class="text-success">' +
                                '<i class="fa fa-user text-bold"></i> ' +
                                val.lname+', '+val.fname+
                                '<br>' +
                                '<small class="text-muted">' +
                                '<em>(' +
                                val.description +
                                ')</em></small>' +
                                ''
                                '</td>'+
                                '</tr>';
                    });
                    $('.onlineContent').html(content);
                },1000);

            }
        });
    });

    function removePending(e,route_no)
    {
        console.log(route_no);
        $('.loading').show();
        var link = e.data('link');
        $.ajax({
            url: link,
            type: 'GET',
            success: function(){
                setTimeout(function(){
                    $('.'+route_no).hide();
                    $('.loading').hide();
                },1000);
            }
        });
    }

    function infoPending(e)
    {
        $('.loading').show();
        var link = e.data('link');
        $.ajax({
            url: link,
            type: 'GET',
            success: function(data){
                setTimeout(function(){
                    $('.pendingInfo').html(data);
                    $('.loading').hide();
                },1000);
            }
        });
    }
</script>

@section('js')

@show

</body>
</html>