@extends('app')

@section('title') User Chat @stop

@section('includes')
<script src="js/chats.js"/>
@stop

@section('content')
<div class="col-lg-4 col-lg-offset-4">
    <h1 id="greeting">Hello, <span id="username">{{$username}}</span></h1>

    <div id="chat-window" class="col-lg-12">

    </div>
    <div class="col-lg-12">
        <div id="typingStatus" class="col-lg-12" style="padding: 15px"></div>
        <input type="text" id="text" class="form-control col-lg-12" autofocus="" onblur="notTyping()">
    </div>
</div>
@stop