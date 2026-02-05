{{-- @extends( _user_app() ) --}}
@include('template.user.app.app')

<?php
	$title = Auth::check() ? Auth::user()->name : '';
?>

@section('title', strtoupper( $title ))



@section('content')

	

<div class="row">
	<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
		<div class=" dashboard-content-header">
		 	<h3>
		 		Welcome to Workbench
		 	</h3>
		</div>
	</div>
</div>


<div class="row">

	

</div>

@endsection
