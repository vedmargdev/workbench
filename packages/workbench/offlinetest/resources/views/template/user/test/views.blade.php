@extends( _user_app() )
@section('title',$title)
@section('content')
@php
    $subjectData = json_decode($offlineTest->sections_data, true);                                            
@endphp
<div class="row">
	<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
		<div class=" dashboard-content-header">
		 	<div class="row">
		 		<div class="col-md-6">
	                <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <a href="{{ route('user.offline-test') }}" title="Back to Alloted Room"
                                    class="btn btn-primary btn-add">
                                    <img src="https://vedmarg.s3.ap-south-1.amazonaws.com/images/icons/angle-left-white.png">
                                </a>
                                Offline Test 
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-responsive">
                            	@php
                                   $classes = $apiResponse['classes'] ?? [];
                                   $matchedClass = collect($classes)->firstWhere('id', $offlineTest->classes_id);
                                @endphp
                                <tbody>
                                    <tr>
                                        <th>Title</th>
                                        <td>{{$offlineTest->test_name ?? ''}}</td>
                                    </tr>
                                    
                                    <tr>
                                        <th>Date</th>
                                        <td>{{ \Carbon\Carbon::parse($offlineTest->date)->format('d M, Y') }}</td> 
                                    </tr>
                                    <tr>
                                        <th>Time</th>
                                        <td>{{ \Carbon\Carbon::parse($offlineTest->from_time)->format('h:ia') }} to {{ \Carbon\Carbon::parse($offlineTest->to_time)->format('h:ia') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Class</th>
                                        <td> {{ $matchedClass['name'] ?? 'N/A' }}</td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
		 		</div>
		 		<div class="col-md-6">
	                <div class="card">
                        <div class="card-header">
                            <div class="card-title">Test Details</div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-responsive">
                                <tbody>
                                	@if($subjectData)
                                        @foreach($subjectData as $subjectInfo)
                                        <tr>
	                                        <th><strong>Section</strong></th>
	                                        <td colspan="3">{{ $subjectInfo['section'] }}</td>
                                        </tr>
                                            @foreach($subjectInfo['subjects'] as $subject)
                                                @php
                                                  $subjectName = $subjectMap[$subject['subject_id']] ?? 'Unknown Subject';
                                                @endphp
		                                    <tr>
		                                        <th>Subject</th>
		                                        <td>{{ucfirst($subjectName)}}</td>
		                                         <th>Max Marks</th>
		                                        <td>{{ $subject['max_marks'] }}</td>
		                                    </tr>
		                                    {{-- <tr>
		                                        <th>Max Marks</th>
		                                        <td>{{ $subject['max_marks'] }}</td>
		                                    </tr> --}}
                                            @endforeach
                                        @endforeach
                                    @else
                                        N/A
                                    @endif
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
		 		</div>
		 	</div>
		 	
		</div>
	</div>
</div>
@endsection
