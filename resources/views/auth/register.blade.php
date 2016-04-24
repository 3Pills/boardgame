@extends('app')

@section('title') Register @stop
@section('content')
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-default ">
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
				{!! Form::open(['role' => 'form', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form-group">
						{!! Form::label('name', 'Username:', ['class' => 'control-label col-sm-3']) !!}
			    		<div class="col-sm-9">
							{!! Form::text('name', null, ['class' => 'form-control']) !!}
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('email', 'Email:', ['class' => 'control-label col-sm-3']) !!}
			    		<div class="col-sm-9">
							{!! Form::email('email', null, ['class' => 'form-control']) !!}
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('password', 'Password:', ['class' => 'control-label col-sm-3']) !!}
			    		<div class="col-sm-9">
							{!! Form::password('password', ['class' => 'form-control']) !!}
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('password', 'Confirm Password:', ['class' => 'control-label col-sm-3']) !!}
						<div class="col-sm-9">
							{!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-9 col-sm-offset-3">
							{!! Form::submit('Register', ['class' => 'btn btn-primary form-control']) !!}
						</div>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
@stop