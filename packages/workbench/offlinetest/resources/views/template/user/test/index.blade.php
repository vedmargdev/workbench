@extends( _user_app() )



@section('title', $title)
@section('content')

	

<div class="row">
	<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
		<div class="dashboard-content-header">
		 	<div class="heading">
		 		<h3>{{$title}}</h3>
		 	</div>
		 	<div class="card bg-shadow">
	 		    <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div class="card-title">
                            @if( Request::get('trashed') )
                                <a class="btn btn-primary btn-add" href="{{ route('user.offline-test') }}">
                                    <img src="{{ asset_url('images/icons/angle-left-white.png') }}">
                                </a>
                                <a class="btn btn-add bg-danger disabled" id="restoreBulkButton">
                                    <img src="{{ asset_url('images/icons/restore-white.png') }}">
                                    <small class="total"></small>
                                </a>
                            @else
                                <a class="btn btn-primary btn-add" onclick="showPopupBoxOfflineTest('#offlineTestPopup')">
                                    <img src="{{ asset_url('images/icons/plus-white.png') }}">
                                </a>
                                <a  class="btn btn-primary btn-add toggleFilterBtn"
                                    title="Filter allocated room">
                                    <img src="{{ asset_url('images/icons/filter-white.png') }}">
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['download' => 'excel']) }}"
                                    class="btn btn-primary btn-add">
                                    <img src="{{ asset_url('images/icons/download-white.png') }}">
                                </a>
                                <a class="btn btn-add bg-danger disabled" id="deleteBulkButton">
                                    <img src="{{ asset_url('images/icons/delete-white.png') }}">
                                    <small class="total"></small>
                                </a>
                            @endif
                        </div>
                        <div class="">
                            @if( !Request::get('trashed') )
                                <span>
                                    <strong>Total:</strong> (<span id="totalOfflineTest">{{count($offlineTestDetails)}}</span>)
                                </span>
                                &nbsp;
                            @endif
                            <a class="trash-link" href="{{route('user.offline-test')}}?trashed=1">
                                Deleted:
                                (<span id="totalDeletedOfflineTest">{{$total_trashed}}</span>)
                            </a>
                        </div>
                    </div>
                    @if (!Request::get('trashed'))
                            <div class="filters--container mt-3" style="display:{{ Request::only(['test_title','from_date','to_date','classes', 'sections','subject']) ? 'block' :'none' }};"
                                data-select2-id="select2-data-37-g4ae">
                                <form method="GET" action="{{ route('user.offline-test') }}"
                                    accept-charset="UTF-8">
                                    <div class="row ">
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 form-group">
                                            <label class="form-label">Test name:</label>
                                            <input type="text" name="test_title" value="{{Request::get('test_title')}}">
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 form-group">
                                            <label for="" class="form-label">Class.</label>
                                            <select class="classes_section_filter select2" name="classes">
                                                <option value="">--Select--</option>
                               
                                            </select>
                                        </div>
                                        {{-- <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 form-group">
                                            <label for="" class="form-label">Section.</label>
                                            <select class=" select2">
                                
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 form-group">
                                            <label for="" class="form-label">Subject.</label>
                                            
                                        </div> --}}
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 form-group">
                                            <label class="form-label">From Exam Date:</label>
                                            <input type="date" name="from_date" value="{{Request::get('from_date')}}">
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 form-group">
                                            <label class="form-label">To Exam Date:</label>
                                            <input type="date" name="to_date" value="{{Request::get('to_date')}}">
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 form-group mt-4">
                                            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                                            <a class="btn btn-default btn-sm btn-primary" href="{{ route('user.offline-test') }}">Clear</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif
                </div>
                <div class="card-body">
                	<div class="table-contatiners">
                		<div class="table-responsive">
                			<table  class="table table-bordered datatable-checkbox datatable" id="allDataTable">
                				<thead>
                                    <tr>
                                        <th scope="col">
                                            @if( !Request::get('trashed') )
                                                <label for="select-box">
                                                    <input type="checkbox" id="select-all-flog-checkbox-">
                                                </label>
                                            @elseif( Request::get('trashed') )
                                                <label for="select-box">
                                                    <input type="checkbox" id="select-all-flog-checkbox-">
                                                </label>
                                            @endif
                                          S.No
                                        </th>
                                        <th scope="col">Test title</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Time</th>
                                        <th scope="col">Class</th>
                                        {{-- <th scope="col">Section</th> --}}
                                        <th scope="col">Section</th>
                                        @if( Request::get('trashed') )
                                            <th>Deleted By</th>
                                            <th>Deleted Remark</th>
                                            <th>Deleted At</th>
                                        @else
                                            <th>Created By</th> 
                                            <th>Created At</th>   
                                        @endif
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $classes = $apiResponse['classes'] ?? [];
                                    @endphp
                                	@if($offlineTestDetails->isNotEmpty())
                                	  <?php $index=0;?>
                                	  @foreach($offlineTestDetails as $data) 
                                            @php
                                                $subjectData = json_decode($data->sections_data, true);
                                                $totalSection=count($subjectData);
                                                $sections = json_decode($data->section ?? '[]', true);
                                                $matchedClass = collect($classes)->firstWhere('id', $data->classes_id);
                                            @endphp
                                	   <tr id="row-{{ $data->id }}">
                                	   	    <td class="sr_no">
                                                     @if( !Request::get('trashed'))
                                                        <label for="select-box{{ $index }}">
                                                            <input type="checkbox" class="select--flog-checkbox-" name="select--flog-checkbox-" value="{{ $data->uuid }}">
                                                        </label>
                                                    @elseif( Request::get('trashed') )
                                                        <label for="select-box{{ $index }}">
                                                            <input type="checkbox" class="select--flog-checkbox-" name="select--flog-checkbox-" value="{{ $data->uuid }}">
                                                        </label>
                                                    @endif
                                                  {{ ++$index }}
                                            </td>
                                            <td>{{$data->test_name ?? ''}}</td>
                                            <td>{{ \Carbon\Carbon::parse($data->date)->format('d M, Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data->from_time)->format('h:ia') }} to {{ \Carbon\Carbon::parse($data->to_time)->format('h:ia') }}
                                            </td>
                                            <td> {{ $matchedClass['name'] ?? 'N/A' }}</td>
                                           {{--  <td>
                                             {{ implode(', ', $sections) }}
                                            </td> --}}
                                            <td>
                                                <strong>Total Section:</strong>
                                               {{$totalSection}}
                                            </td>
                                            @if( Request::get('trashed') )
                                                <td>{{ ucfirst($data->deleted_by_user->name ?? '')}}</td>
                                                <td>{{ ucfirst($data->deleted_reasons ?? '')}}</td>
                                                <td>
                                                    {{ convertDate($data->deleted_at) }}
                                                </td>
                                            @else
                                                <td class="created-by">
                                                    {{ ucfirst($data->created_by_user->name ?? '')}}
                                                </td>
                                                <td class="created_at">
                                                    {{ convertDate($data->created_at) }}
                                                </td>    
                                            @endif
                                            <td>
                                                    @if(!Request::get('trashed'))
                                                        <a class="btn btn-add"
                                                                href="{{ route('user.offline-test.views', [$data->uuid]) }}"
                                                                target="_blank">
                                                                <img src="{{ asset_url('images/icons/eye-white.png') }}">
                                                        </a>
                                                        <a class="btn btn-add edit-offline-test" href="javascript:void(0)" data-uuid="{{ $data->uuid }}" title="Edit ">
                                                                <img src="{{ asset_url('images/icons/edit-white.png') }}">
                                                        </a>
                                            
                                                        <a class="btn bg-danger btn-add delete-offline-test" href="javascript:void(0)" data-uuid="{{ $data->uuid }}" title="Delete">
                                                                <img src="{{ asset_url('images/icons/delete-white.png') }}">
                                                        </a>
                                                        <a class="btn btn-add"
                                                                href="{{ route('user.offline-test.section', [$data->uuid]) }}"
                                                                target="_blank">
                                                                marks
                                                        </a>
                                                    @else
                                                        <a class="btn bg-danger btn-add restore-offline-test" href               ="javascript:void(0)" data-uuid="{{ $data->uuid }}" title="Edit ">
                                                                    <img src="{{ asset_url('images/icons/restore-white.png') }}">
                                                        </a> 
                                                    @endif 
                                                </td>
                                	   </tr>
                                	  @endforeach
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
@include(_template('user.test.create-popup'))
@include(_template('user.test.delete-popup'))
@endsection

@section('app-js')
    <script type="text/javascript" src="{{ asset('js/offline-test.js?v=' .time()) }}"></script>
@endsection