<table>
	<thead>
		<tr>
			<th>Sr No.</th>
			<th>Test Title</th>
			<th>Class</th>
			<th>Test Date</th>
			<th>Test Time</th>
            <th>Subject and Marks</th>
            <th>Created At</th>
		</tr>
	</thead>
	<tbody>
		@foreach($offlineTestDetails as $index=>$data)
		<tr>
			  <td>{{++$index}}</td>
	          <td>{{$data->test_name ?? ''}}</td>
	          <td>{{ $classMap[$data->classes_id] ?? '' }}</td>
	          <td>{{\Carbon\Carbon::parse($data->date)->format('d M, Y')}}</td>
	          <td>{{\Carbon\Carbon::parse($data->from_time)->format('h:ia') }} to {{ \Carbon\Carbon::parse($data->to_time)->format('h:ia') }}</td>
	          <td>{{$data->sections_data ?? ''}}</td>
	          <td>{{convertDate($data->created_at)}}</td>
		</tr>
		@endforeach
	</tbody>
</table>