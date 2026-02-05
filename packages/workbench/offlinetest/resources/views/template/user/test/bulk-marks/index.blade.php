@extends( _user_app() )
@section('title',$title)



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
		 			<form accept="" method="post">
                        <div class="row">
                        	<div class="col-md-3">
                        		<label class="form-label">Class</label>
                        		<div>
                        			<select></select>
                        		</div>	
                        	</div>
                        	<div class="col-md-3">
                        		 <label class="form-label">Section</label>
                        		 <div>
                        		 	<select></select>
                        		 </div>
                        	</div>
                        	<div class="col-md-3">
                        		<button type="submit" class="btn btn-primary">Filter</button>
                        	</div>
                        </div>
		 				{{-- <div class="row submit_close_button">
		                    <div class="col-md-12" id="btnSubmit">
		                        <button type="submit" class="btn btn-primary">Filter</button>
		                    </div>
                        </div> --}}
		 			</form>
		 		</div>
		 	</div>
		</div>
	</div>
</div>
@endsection
