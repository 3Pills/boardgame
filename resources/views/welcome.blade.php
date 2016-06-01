@extends('app')

@section('title') Welcome! @stop

@section('content')

<h1>Welcome to the website!</h1>
<p>This site will eventually host a pretty good JavaScript-based multiplayer game. For now just register an account and trust me on this one. It's gonna be pretty real tbh.</p>

<h2>Global Shoutbox</h2>
<div class="shoutbox" style="background:#333;">
	<div id="chat-text" class="chat-text" style="height:200px;">
		<div class="text-console">Welcome to the shoutbox!</div>
	</div>
	<div id="chat-text" class="chat-text">
		<input type="text" name="chat-input" id="chat-input" class="chat-input" style="width:93%"/><button style="width:7%; display:inline-block;color:#000;">Chat</button>
	</div>
    @if(\Auth::check() && \Auth::user()->role != 0) 

    @endif
</div>
@stop

@section('scripts-deferred')
<script type="text/javascript">
	function pullData() {
	    loadChatData();
		setTimeout(pullData,10000);
	}

	function loadChatData(msgData) {
		for (var k in msgData) {
			if (!isNaN(k)) {
				latest_chat = moment.utc(msgData[k].time).format();
				if (msgData.user_data !== undefined) {
        			$('#chat-text').append('<div id='+'1'+' class=chat-player-'+'1'+'> ['+moment.utc(msgData[k].created_at).toDate().toLocaleTimeString() + '] '+ msgData.user_data[msgData[k].user_id].name + ': '+msgData[k].msg+'</div>');
				}
			}
		}
		//$('.collapse').collapse("show");
		//setTimeout(function() {$('.collapse').collapse("hide")}, 4000);
	}
	pullData();
</script>

@stop