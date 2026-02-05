@extends( _user_app() )
@section('title', $title)



@section('content')

<div class="row">
	<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
		<div class="dashboard-content-header">
		 	<div class="card bg-shadow">
		 		<div class="card-header-title">
		 			<div class="card-title">
		 				Select class & section to update marks.
		 			</div>
		 		</div>
		 		<div class="card-body">
		 			<form accept="{{route('user.offline-test.marks')}}" method="GET">
                        <div class="row">
                        	<div class="col-md-3 form-group">
                        		<label class="form-label">Class</label>
                        		<div>
                        			<select class="classes_section_filter select2" name="classes">
                        				<option value="">--Select--</option>
                        			</select>
                        		</div>	
                        	</div>
                        	<div class="col-md-3 form-group">
                        		 <label class="form-label">Section</label>
                        		 <div>
                        		 	<select class="section_filter select2" name="sections">
                        		 		<option value="">--Select--</option>
                        		 	</select>
                        		 </div>
                        	</div>
                        	<div class="col-md-3 form-group mt-4">
                        		<button type="submit" class="btn btn-primary">Filter</button>
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