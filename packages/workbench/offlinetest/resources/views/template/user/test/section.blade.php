@extends( _user_app() )

<?php
	$title = Auth::check() ? Auth::user()->name : '';
?>

@section('title', strtoupper( $title ))
@section('content')
<div class="row">
	<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
		<div class=" dashboard-content-header">
		 	<div class="card bg-shadow">
	                    <div class="card-header-title">
				 			<div class="card-title">
				 				Select class & section to update marks.
				 			</div>
		 		        </div>
		 		        <div class="card-body">
				 				    @php
                                        $classes = $apiResponse['classes'] ?? '';
                                        $offlineTestClassId = (int) ($offlineTest->classes_id ?? 0);
                                        $matchedClass = collect($classes)->firstWhere('id', $offlineTestClassId);
                                    @endphp
                                <form method="GET" action="{{route('user.offline-test.marks')}}" id="section_search">
                                	@csrf
                                	<input type="hidden" name="uuid" value="{{$offlineTest->uuid}}">
	                                <div class="row">
			                        	<div class="col-md-3 form-group">
			                        		<label class="form-label">Class</label>
			                        		<input type="text" value="{{ $matchedClass['name'] ?? 'N/A' }}"  readonly />	
			                        		<input type="hidden" name="classes_id" value="{{$offlineTest->classes_id}}">
			                        	</div>
			                        	<div class="col-md-3 form-group">
			                        		 <label class="form-label">Section</label>
			                        		 <div>
			                        		 	<select class="section_filter select2" name="sections">
			                        		 		<option value="">--Select--</option>
			                        		 		@foreach($sections as $section)
			                        		 		<option value="{{$section}}" >{{ucfirst($section)}}</option>
			                        		 		@endforeach
			                        		 	</select>
			                        		 </div>
			                        	</div>
			                        	<div class="col-md-3 form-group mt-4">
			                              <button type="submit" class="btn btn-sm btn-primary">Filter</button>
			                            </div>
			                        </div>
                                </form>    
		                       
		 		        </div>
		 	</div>
		</div>
	</div>
</div>
@endsection
@section('app-js')
    <script type="text/javascript" src="{{ asset('js/offline-test.js?v=' .time()) }}"></script>
@endsection