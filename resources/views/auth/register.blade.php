@extends('app')

@section('title') Register @stop
@section('content')
<div class="panel panel-default" style="width:700px; margin:auto;">
	<div class="panel-heading">Register</div>
	<div class="panel-body">
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
		<form method="POST" action="{{ url('/register') }}" role="form" class="form-horizontal" accept-charset="UTF-8">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="form-group" style="margin-top:12px;">
				<label for="name" class="control-label col-sm-3">Username:</label>
				<div class="col-sm-9"> <input type="text" name="name" class="form-control"/> </div>
			</div>
			<div class="form-group">
				<label for="email" class="control-label col-sm-3">Email:</label>
				<div class="col-sm-9"> <input type="email" name="email" class="form-control"/> </div>
			</div>
			<div class="form-group">
				<label for="password" class="control-label col-sm-3">Password:</label>
				<div class="col-sm-9"> <input type="password" name="password" class="form-control"/> </div>
			</div>
			<div class="form-group">
				<label for="password_confirmation" class="control-label col-sm-3">Confirm Password:</label>
				<div class="col-sm-9"> <input type="password" name="password_confirmation" class="form-control"/> </div>
			</div>

			<div class="form-group">
				<div class="col-sm-9 col-sm-offset-3">
                    <input type="submit" value="Register" class="btn btn-primary btn-block">       
				</div>
			</div>
		{!! Form::close() !!}
	</div>
</div>
@stop