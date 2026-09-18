<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'action' => ['nullable', 'string', 'max:100'],
            'search' => ['nullable', 'string', 'max:255'],
        ]);
        $logs = ActivityLog::query()->with('user:id,name')
            ->when($filters['action'] ?? null, fn ($query, $action) => $query->where('action', $action))
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->where(fn ($query) => $query
                ->where('action', 'like', '%'.$search.'%')
                ->orWhere('description', 'like', '%'.$search.'%')
                ->orWhereHas('user', fn ($query) => $query->where('name', 'like', '%'.$search.'%'))))
            ->latest('id')->paginate(30)->withQueryString();

        return view('admin.activity-logs.index', compact('logs'));
    }
}
