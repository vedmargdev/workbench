@extends( _user_app() )
@section('title', strtoupper( $title ))
@section('content')

	

<div class="row">
	<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
		<div class=" dashboard-content-header">
		 	
			<div class="card bg-shadow">
				<div class="card-header">
	 				<h5>Bulk Marks Update</h5>
	 			</div>
	 			<div class="card-body">
	 				<div class="marksheet--bulk-table thick--scrollbar">
	 					<div class="table-responsive">
	 						@if(!empty($offlineTest))
	 						<table class="table table-bordered table-striped">
	 							<thead class="header">
	 								<tr>
	 									<th style="width: 50px;"></th>
	 									<th style="width: 100px;"></th>
	 									<th>{{ucfirst($offlineTest->test_name ?? '')}}</th>
	 								</tr>
	 								<tr>
	 									<th>#</th>
	 									<th>Student</th>
	 									<th></th>
	 								</tr>
	 								<tr class="subject-rows">
	 									<th></th>
	 									<th></th>
	 									@foreach($filteredSectionsData as $data)
		 									@foreach($data['subjects'] as $subject)
		 									<th>
	                                        {{ $subject['subject_id'] }}
	                                          <div class="d-flex justify-content-between head--fields">
	                                          	<div class="head--field-column">
	                                          		<div>
	                                          			<input type="text" name="" value="mm">
	                                          		</div>
	                                          		<div>
	                                          			<input type="text" name="" value="{{ $subject['max_marks'] }}">
	                                          		</div>
	                                          	</div>
	                                          </div>  
	                                        </th> 
	                                        @endforeach
	 									@endforeach
	 								</tr>
	 							</thead>
	 							<tbody>
	 									@foreach($filteredStudents as $index=>$data)
	 									<tr>
		 									<td style="max-width: 50px;">{{$index+1}}</td>
		 									<td>
		 										<input type="hidden" name="student_id" value="{{$data['id']}}">
		 										<strong>Name: </strong>
		 										{{$data['name'] ?? ''}}
		 										<br>
		 										<strong>Father Name: </strong>
		 										{{$data['father_name'] ?? ''}}<br>
		 										<strong>Admission No.:</strong>
		 										{{$data['admission_no'] ?? ''}}<br>
		 										<strong>Roll No.:</strong>
		 										{{$data['roll_no'] ?? ''}}
		 									</td>
		 									<td>
		 									     <input type="text" name="marks"> 
		 									</td>
	 									</tr>
	 									@endforeach
	 							</tbody>
	 						</table>
	 						@else
	 						<span>Exam Not found</span>
	 						@endif
	 						
	 					</div>
	 				</div>
	 			</div>
			</div> 			
		</div>
	</div>
</div>


<div class="row">

	

</div>

@endsection
