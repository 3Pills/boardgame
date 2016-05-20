@extends('app')

@section('title') Login @stop
@section('content')
<div class="panel panel-default" style="width:700px; margin:auto;">
	<div class="panel-heading">Login</div>
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
		<form method="POST" action="{{ url('/login') }}" role="form" class="form-horizontal" accept-charset="UTF-8">
			{{ csrf_field() }}
			<div class="form-group" style="margin-top:12px;">
				<label for="email" class="control-label col-sm-3">Email:</label>
				<div class="col-sm-9"> <input type="email" name="email" class="form-control"/> </div>
			</div>
			<div class="form-group">
				<label for="password" class="control-label col-sm-3">Password:</label>
				<div class="col-sm-9"> <input type="password" name="password" class="form-control"/> </div>
			</div>
			<div class="form-group">
				<label for="remember" class="control-label col-sm-3">Remember Me:</label>
				<div class="col-sm-9 control-label" style="text-align:left;"> <input type="checkbox" name="remember"/> </div>
			</div>
			<div class="form-group">
				<div class="col-sm-9 col-sm-offset-3">
                    <input type="submit" value="Login" class="btn btn-primary btn-block">            
				</div>
			</div>
			<hr/>
			<div class="row" style="margin-bottom: 12px;"> 
				<label class="col-sm-3 control-label">No Account?</label>
				<div class="col-sm-9">
					<a href="{{url('/register')}}" class="btn btn-default btn-block">Register</a> 
				</div>
			</div>
		</form>
	</div>
</div>
@stop