@extends('layouts.master')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox">
				<div class="ibox-title">
					<h5>Activity Logs</h5>
					<div class="ibox-tools">
						<form method="POST" action="{{ route('activities.mark-all-read') }}">
							@csrf
							<button class="btn btn-xs btn-primary" type="submit">Mark all as read</button>
						</form>
					</div>
				</div>
				<div class="ibox-content">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>When</th>
								<th>Message</th>
								<th>Model</th>
								<th>Action</th>
								<th>Status</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						@foreach($activities as $activity)
							<tr>
								<td>{{ $activity->created_at->diffForHumans() }}</td>
								<td>{{ $activity->message }}</td>
								<td>{{ class_basename($activity->model_type) }} #{{ $activity->model_id }}</td>
								<td>{{ $activity->action }}</td>
								<td>
									@if($activity->is_read)
										<span class="label label-default">Read</span>
									@else
										<span class="label label-primary">Unread</span>
									@endif
								</td>
								<td class="text-right">
									@if(!$activity->is_read)
										<form method="POST" action="{{ route('activities.mark-read', $activity) }}" style="display:inline">
											@csrf
											<button class="btn btn-xs btn-success" type="submit">Mark read</button>
										</form>
									@else
										<form method="POST" action="{{ route('activities.mark-unread', $activity) }}" style="display:inline">
											@csrf
											<button class="btn btn-xs btn-warning" type="submit">Mark unread</button>
										</form>
									@endif
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
					{{ $activities->links() }}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection


