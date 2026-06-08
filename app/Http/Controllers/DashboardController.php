<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the operational dashboard.
     */
    public function index()
    {
        $user = auth()->user();

        // Stats queries
        $totalQuery = \App\Models\Ticket::query();
        $activeQuery = \App\Models\Ticket::where('status', 'active');
        $pendingQuery = \App\Models\Ticket::where('status', 'pending');
        $resolvedQuery = \App\Models\Ticket::where('status', 'resolved');

        // Filter for regular users
        if (!$user->isAdmin() && !$user->isIT()) {
            $totalQuery->where('user_id', $user->id);
            $activeQuery->where('user_id', $user->id);
            $pendingQuery->where('user_id', $user->id);
            $resolvedQuery->where('user_id', $user->id);
        }

        // Dynamic ticketing statistics
        $stats = [
            'total' => $totalQuery->count(),
            'active' => $activeQuery->count(),
            'pending' => $pendingQuery->count(),
            'resolved' => $resolvedQuery->count(),
        ];

        // Recent tickets query with full relations
        $ticketsQuery = \App\Models\Ticket::with(['user', 'department', 'location', 'category', 'subCategory']);

        if (!$user->isAdmin() && !$user->isIT()) {
            $ticketsQuery->where('user_id', $user->id);
        }

        $recentTickets = $ticketsQuery->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'recentTickets'));
    }
}
