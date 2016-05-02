@extends('app')

@section('title') {{$user->name.'\''.($user->name[strlen($user->name) - 1] != 's' ? 's' : '')}} Profile @stop

@section('metadata')
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@stephenkoren7" />
<meta property="og:url" content="{{url('/user/').'/'.$user->url}}" />
<meta property="og:type" content="article" />
<meta property="og:title" content="{{$user->name.'\''.($user->name[strlen($user->name) - 1] != 's' ? 's' : '')}} Profile" />
<meta property="og:description" content="Profile for {{$user->name}} on Play Vidya Soon" />
<meta property="og:image" content="{{url('/assets/images/').(file_exists('./assets/images/avatars/'.$user->url.'.jpg') ? '/avatars/'.$user->url.'.jpg' : '/default-avatar.png')}}" />
@stop

@section('content')
<div class="row">
    <div style="float: left; width:192px;">
		<img src="{{url('/assets/images/').(file_exists('./assets/images/avatars/'.$user->url.'.jpg') ? '/avatars/'.$user->url.'.jpg' : '/default-avatar.png')}}" alt="avatar" class="profile-avatar"/>
	</div>
	<div>
		<h2>{!! $user->name !!}</h2>
		@if ($user == \Auth::user())
			<a href="{!!$user->url!!}/settings" class="btn btn-primary">Edit Profile</a>
		@endif
		<p>{!! nl2br($user->about) !!}</p>
	</div>
</div>
<hr/>
@stop