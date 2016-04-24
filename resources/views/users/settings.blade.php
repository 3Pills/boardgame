@extends('app')

@section('title') {!!$user->name!!} Settings @stop

@section('content')
	<a href="../{!!$user->url!!}">Back</a>
    <hr/>
	@if ($errors->any())
		<div class="alert alert-danger">
			<strong>Error!</strong> The following errors encountered in your input:<br>
			<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
			</ul>
		</div>
	@endif
	{!! Form::open(['role' => 'form', 'method' => 'PUT', 'class' => 'form-horizontal', 'files' => true]) !!}
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					{!! Form::label('name', 'Username:') !!}
					{!! Form::text('name', $user->name, ['class' => 'form-control']) !!}
				</div>
				<div class="form-group">
					{!! Form::label('email', 'Email:') !!}
					{!! Form::email('email', $user->email, ['class' => 'form-control']) !!}
				</div>
				<div class="form-group">
					{!! Form::label('url', 'Profile URL:') !!}
					{!! Form::text('url', $user->url, ['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					{!! Form::label('profile_picture', 'Profile Picture:') !!}
					<br>
					{!! HTML::image(file_exists('./images/avatars/'.$user->url.'.jpg') ? './images/avatars/'.$user->url.'.jpg' : './images/default-avatar.png', 'avatar', ['class' => 'img-rounded', 'style' => 'width:192px;height:192px;']) !!}
					<p>Maximum File Size: 2MB. Dimensions will be cropped to 192x192</p>
					{!! Form::file('profile_picture', []) !!}
				</div>
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('about', 'About:') !!}
			{!! Form::textarea('about', $user->about, ['class' => 'form-control']) !!}
		</div>
		<br>
		<div class="form-group">
			{!! Form::submit('Update', ['class' => 'btn btn-primary form-control']) !!}
		</div>
	{!! Form::close() !!}
@stop