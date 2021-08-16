<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
    <title>Emails</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<div class="container">
    <div class="topnav-custom">
        <a style="float: right" class="dropdown-item" href="{{ route('logout') }}"
           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
            {{ __('Logout') }}
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
    <div class="row">
        <!-- BEGIN INBOX -->
        <div class="col-md-12">
            <div class="grid email">
                <div class="grid-body">
                    <div class="row">
                        @if (\Illuminate\Support\Facades\Session::has('success'))
                            <div class="alert alert-success text-center">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                <p>{{ \Illuminate\Support\Facades\Session::get('success') }}</p>
                            </div>
                        @endif

                        @if (\Illuminate\Support\Facades\Session::has('alert-class'))
                            <div class="alert alert-danger text-center">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                <p>{{ \Illuminate\Support\Facades\Session::get('alert-class') }}</p>
                            </div>
                    @endif

                    <!-- BEGIN INBOX MENU -->
                        <div class="col-md-3">
                            <h2 class="grid-title"><i class="fa fa-inbox"></i> Inbox</h2>
                            <a class="btn btn-block btn-primary" data-toggle="modal" data-target="#compose-modal"><i class="fa fa-pencil"></i>&nbsp;&nbsp;NEW MESSAGE</a>

                            <hr>

                            <div>
                                <ul class="nav nav-pills nav-stacked">
                                    <li class="header">Folders</li>
                                    <li class="{{ app('request')->input('sent') || app('request')->input('box_id') ? "" : "active" }}"><a href="{{ route('inbox') }}"><i class="fa fa-inbox"></i> Inbox</a></li>
                                    <li class="{{ app('request')->input('sent') ? "active" : "" }}"><a href="{{ route('inbox', ['sent' => true]) }}"><i class="fa fa-mail-forward"></i> Sent</a></li>
                                    @foreach($boxes as $box)
                                        <li class="{{ app('request')->input('box_id') == $box->id ? "active" : "" }}"><a href="{{ route('inbox', ['box_id' => $box->id]) }}"><i class="fa fa-inbox"></i> {{ $box->name }}</a></li>
                                    @endforeach
                                    <br>
                                    <form action="{{ route('create_box') }}" method="post">
                                        @csrf
                                        <input id="box_field" type="text" name="box_name" placeholder="Create new box">
                                    </form>
                                </ul>
                            </div>
                        </div>
                        <!-- END INBOX MENU -->

                        <!-- BEGIN INBOX CONTENT -->
                        <div class="col-md-9">
                            <div class="row">
                                <div class="email-body">
                                    <div style="display: contents">
                                        <p><strong>Subject: </strong> {{ $email->subject }}</p>
                                        <form id="email_box_form" method="post" action="{{ route('email_box', ['id' => $email->id]) }}">
                                            @csrf
                                            <select name="box_id" id="box_dropdown" style="float: right">
                                                <option value="">Assign Box</option>
                                                @foreach($boxes as $box)
                                                    <option value="{{ $box->id }}">{{ $box->name }}</option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </div>
                                    <br>
                                    <p>{{ $email->content }}</p>
                                </div>
{{--                                <div class="email-attachments">--}}
{{--                                    <div class="title">Attachment</div>--}}
{{--                                    <img src="{{ $filePath }}">--}}
{{--                                </div>--}}
                            </div>

                        </div>
                        <!-- END INBOX CONTENT -->

                        <!-- BEGIN COMPOSE MESSAGE -->
                        <div class="modal fade" id="compose-modal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-wrapper">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-blue">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h4 class="modal-title"><i class="fa fa-envelope"></i> Compose New Message</h4>
                                        </div>
                                        <form action="{{ route('save_email') }}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <input name="to" type="email" required class="form-control" placeholder="To">
                                                </div>
                                                <div class="form-group">
                                                    <input name="subject" type="text" required class="form-control" placeholder="Subject">
                                                </div>
                                                <div class="form-group">
                                                    <textarea name="content" id="email_message" required class="form-control" placeholder="Message" style="height: 120px;"></textarea>
                                                </div>
                                                <div class="form-group">														<input type="file" name="attachment">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>
                                                <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-envelope"></i> Send Message</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END COMPOSE MESSAGE -->
                    </div>
                </div>
            </div>
        </div>
        <!-- END INBOX -->
    </div>
</div>

<style type="text/css">
    body{
        margin-top:20px;
        background:#eee;
    }
    /* EMAIL */
    .email {
        padding: 20px 10px 15px 10px;
        font-size: 1em;
    }

    .email .btn.search {
        font-size: 0.9em;
    }

    .email h2 {
        margin-top: 0;
        padding-bottom: 8px;
    }

    .email .nav.nav-pills > li > a {
        border-top: 3px solid transparent;
    }

    .email .nav.nav-pills > li > a > .fa {
        margin-right: 5px;
    }

    .email .nav.nav-pills > li.active > a,
    .email .nav.nav-pills > li.active > a:hover {
        background-color: #f6f6f6;
        border-top-color: #3c8dbc;
    }

    .email .nav.nav-pills > li.active > a {
        font-weight: 600;
    }

    .email .nav.nav-pills > li > a:hover {
        background-color: #f6f6f6;
    }

    .email .nav.nav-pills.nav-stacked > li > a {
        color: #666;
        border-top: 0;
        border-left: 3px solid transparent;
        border-radius: 0px;
    }

    .email .nav.nav-pills.nav-stacked > li.active > a,
    .email .nav.nav-pills.nav-stacked > li.active > a:hover {
        background-color: #f6f6f6;
        border-left-color: #3c8dbc;
        color: #444;
    }

    .email .nav.nav-pills.nav-stacked > li.header {
        color: #777;
        text-transform: uppercase;
        position: relative;
        padding: 0px 0 10px 0;
    }

    .email table {
        font-weight: 600;
    }

    .email table a {
        color: #666;
    }

    .email table tr.read > td {
        background-color: #f6f6f6;
    }

    .email table tr.read > td {
        font-weight: 400;
    }

    .email table tr td > i.fa {
        font-size: 1.2em;
        line-height: 1.5em;
        text-align: center;
    }

    .email table tr td > i.fa-star {
        color: #f39c12;
    }

    .email table tr td > i.fa-bookmark {
        color: #e74c3c;
    }

    .email table tr > td.action {
        padding-left: 0px;
        padding-right: 2px;
    }

    .grid {
        position: relative;
        width: 100%;
        background: #fff;
        color: #666666;
        border-radius: 2px;
        margin-bottom: 25px;
        box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.1);
    }



    .grid .grid-header:after {
        clear: both;
    }

    .grid .grid-header span,
    .grid .grid-header > .fa {
        display: inline-block;
        margin: 0;
        font-weight: 300;
        font-size: 1.5em;
        float: left;
    }

    .grid .grid-header span {
        padding: 0 5px;
    }

    .grid .grid-header > .fa {
        padding: 5px 10px 0 0;
    }

    .grid .grid-header > .grid-tools {
        padding: 4px 10px;
    }

    .grid .grid-header > .grid-tools a {
        color: #999999;
        padding-left: 10px;
        cursor: pointer;
    }

    .grid .grid-header > .grid-tools a:hover {
        color: #666666;
    }

    .grid .grid-body {
        padding: 15px 20px 15px 20px;
        font-size: 0.9em;
        line-height: 1.9em;
    }

    .grid .full {
        padding: 0 !important;
    }

    .grid .transparent {
        box-shadow: none !important;
        margin: 0px !important;
        border-radius: 0px !important;
    }

    .grid.top.black > .grid-header {
        border-top-color: #000000 !important;
    }

    .grid.bottom.black > .grid-body {
        border-bottom-color: #000000 !important;
    }

    .grid.top.blue > .grid-header {
        border-top-color: #007be9 !important;
    }

    .grid.bottom.blue > .grid-body {
        border-bottom-color: #007be9 !important;
    }

    .grid.top.green > .grid-header {
        border-top-color: #00c273 !important;
    }

    .grid.bottom.green > .grid-body {
        border-bottom-color: #00c273 !important;
    }

    .grid.top.purple > .grid-header {
        border-top-color: #a700d3 !important;
    }

    .grid.bottom.purple > .grid-body {
        border-bottom-color: #a700d3 !important;
    }

    .grid.top.red > .grid-header {
        border-top-color: #dc1200 !important;
    }

    .grid.bottom.red > .grid-body {
        border-bottom-color: #dc1200 !important;
    }

    .grid.top.orange > .grid-header {
        border-top-color: #f46100 !important;
    }

    .grid.bottom.orange > .grid-body {
        border-bottom-color: #f46100 !important;
    }

    .grid.no-border > .grid-header {
        border-bottom: 0px !important;
    }

    .grid.top > .grid-header {
        border-top-width: 4px !important;
        border-top-style: solid !important;
    }

    .grid.bottom > .grid-body {
        border-bottom-width: 4px !important;
        border-bottom-style: solid !important;
    }
</style>

<script type="text/javascript">
    $('document').ready(function(){
        $('#box_dropdown').on('change', function () {
            $('#email_box_form').submit();
        })
    });
</script>
</body>
</html>
