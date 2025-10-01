<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
	public function index(Request $request)
	{
		$activities = Activity::latest()->paginate(20);
		return view('activities.index', compact('activities'));
	}

	public function markAllRead()
	{
		Activity::whereNull('is_read')->orWhere('is_read', false)->update(['is_read' => true]);
		return back();
	}

	public function markRead(Activity $activity)
	{
		$activity->update(['is_read' => true]);
		return back();
	}

	public function markUnread(Activity $activity)
	{
		$activity->update(['is_read' => false]);
		return back();
	}
}


