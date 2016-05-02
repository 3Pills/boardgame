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
	<form method="POST" action="{{ url('user/'.$user->url.'/settings')}}" role="form" class="form-horizontal" accept-charset="UTF-8" enctype="multipart/form-data">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<div class="form-group" style="margin-top:12px;">
						<label for="name" class="control-label col-sm-3">Username:</label>
						<div class="col-sm-9"> <input type="text" name="name" value="{{$user->name}}" class="form-control"/> </div>
					</div>
				</div>
				<div class="form-group">
					<div class="form-group">
						<label for="email" class="control-label col-sm-3">Email:</label>
						<div class="col-sm-9"> <input type="text" name="email" value="{{$user->email}}" class="form-control"/> </div>
					</div>
				</div>
				<div class="form-group">
					<div class="form-group">
						<label for="url" class="control-label col-sm-3">Profile URL:</label>
						<div class="col-sm-9"> <input type="text" name="url" value="{{$user->url}}" class="form-control"/> </div>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="profile_picture">Profile Picture:</label>
					<br>
					<img src="{{url('/assets/images/').(file_exists('./assets/images/avatars/'.$user->url.'.jpg') ? '/avatars/'.$user->url.'.jpg' : '/default-avatar.png')}}" alt="avatar" class="img-rounded" style="width:192px;height:192px;"/>
					<p>Maximum File Size: 2MB. Dimensions will be cropped to 192x192</p>
					<div class="col-sm-9"> <input type="file" name="profile_picture"/> </div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="form-group">
				<label for="about" class="control-label">About:</label>
				<div> <textarea name="about" rows="7" class="form-control">{{$user->about}}</textarea> </div>
			</div>
		</div>
		<br>
		<div class="form-group">
            <input type="submit" value="Update" class="btn btn-primary btn-block">   
		</div>
	</form>
@stop