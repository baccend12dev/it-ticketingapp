<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Department;
use App\Models\Location;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets.
     */
    public function index()
    {
        $user = Auth::user();

        // Admin and IT can see all tickets; regular users see only their own
        if ($user->isAdmin() || $user->isIT()) {
            $tickets = Ticket::with(['user', 'department', 'location', 'category', 'subCategory'])
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $tickets = Ticket::with(['user', 'department', 'location', 'category', 'subCategory'])
                ->where('user_id', $user->id)
                ->orderBy('id', 'desc')
                ->get();
        }

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $locations = Location::where('is_active', true)->orderBy('name')->get();
        
        // Eager load active subcategories with categories
        $categories = Category::where('is_active', true)
            ->with(['subCategories' => function ($query) {
                $query->where('is_active', true)->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        return view('tickets.create', compact('departments', 'locations', 'categories'));
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'priority' => 'required|in:low,medium,high,critical',
            'call_ext' => 'nullable|string|max:50',
            'attachment' => 'nullable|file|max:10240', // max 10MB
            'description' => 'required|string',
        ]);

        // Handle attachment
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '', $file->getClientOriginalName());
            $attachmentPath = $file->storeAs('attachments', $filename, 'public');
        }

        $ticket = Ticket::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => 'pending',
            'user_id' => Auth::id(),
            'department_id' => $validated['department_id'],
            'location_id' => null,
            'category_id' => $validated['category_id'],
            'sub_category_id' => $validated['sub_category_id'],
            'call_ext' => $validated['call_ext'] ?? null,
            'attachment' => $attachmentPath,
        ]);

        // Auto update user record with the latest department and call extension info
        $user = Auth::user();
        $user->update([
            'call_ext' => $validated['call_ext'] ?? $user->call_ext,
            'department_id' => $validated['department_id'],
        ]);

        // Standardize Ticket ID output for UX (e.g. #TK-1000 + ID)
        $formattedId = 1000 + $ticket->id;

        return redirect()->route('tickets.index')->with('success', "Ticket #TK-{$formattedId} has been submitted successfully!");
    }

    /**
     * Update the status of a ticket.
     */
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,pending,resolved,canceled',
        ]);

        $ticket->update([
            'status' => $validated['status'],
        ]);

        $formattedId = 1000 + $ticket->id;
        $statusLabel = ucfirst($validated['status']);

        return redirect()->back()->with('success', "Ticket #TK-{$formattedId} status has been updated to {$statusLabel}.");
    }

    /**
     * Show the public ticket submission form.
     */
    public function showPublicForm()
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $locations = Location::where('is_active', true)->orderBy('name')->get();
        
        $categories = Category::where('is_active', true)
            ->with(['subCategories' => function ($query) {
                $query->where('is_active', true)->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        return view('tickets.create_public', compact('departments', 'locations', 'categories'));
    }

    /**
     * Store a ticket submitted via the public form.
     */
    public function storePublicTicket(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'priority' => 'required|in:low,medium,high,critical',
            'call_ext' => 'nullable|string|max:50',
            'attachment' => 'nullable|file|max:10240', // max 10MB
            'description' => 'required|string',
        ]);

        // Find or create the user by email
        $user = \App\Models\User::firstOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['name'],
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
            ]
        );

        if (!$user->isActive()) {
            return redirect()->back()->withInput()->withErrors([
                'email' => 'Your email is registered but inactive in the system.'
            ]);
        }

        // Handle attachment
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '', $file->getClientOriginalName());
            $attachmentPath = $file->storeAs('attachments', $filename, 'public');
        }

        $ticket = Ticket::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => 'pending',
            'user_id' => $user->id,
            'department_id' => $validated['department_id'],
            'location_id' => null,
            'category_id' => $validated['category_id'],
            'sub_category_id' => $validated['sub_category_id'],
            'call_ext' => $validated['call_ext'] ?? null,
            'attachment' => $attachmentPath,
        ]);

        // Auto update user record with the latest department and call extension info
        $user->update([
            'name' => $validated['name'],
            'call_ext' => $validated['call_ext'] ?? $user->call_ext,
            'department_id' => $validated['department_id'],
        ]);

        $formattedId = 1000 + $ticket->id;

        return redirect()->route('tickets.public-create')->with('success', "Ticket #TK-{$formattedId} has been submitted successfully! IT Support will contact you shortly.");
    }

    /**
     * Get pending and active tickets in JSON format with CORS headers.
     */
    public function apiPendingActive(Request $request)
    {
        // Handle preflight OPTIONS request
        if ($request->isMethod('options')) {
            return response('', 204)->withHeaders([
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, X-API-Key, Authorization',
                'Access-Control-Max-Age' => '86400',
            ]);
        }

        // Simple Token Check for Security (can be set in .env)
        $token = $request->header('X-API-Key') ?? $request->query('api_key');
        $expectedToken = env('DESKTOP_API_KEY', 'default_it_desktop_key_2026');

        if ($token !== $expectedToken) {
            return response()->json([
                'error' => 'Unauthorized. Invalid or missing X-API-Key.'
            ], 401)->withHeaders([
                'Access-Control-Allow-Origin' => '*',
            ]);
        }

        $tickets = Ticket::with(['user', 'department', 'location', 'category', 'subCategory'])
            ->whereIn('status', ['pending', 'active'])
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($ticket) {
                return [
                    'id' => $ticket->id,
                    'formatted_id' => '#TK-' . (1000 + $ticket->id),
                    'title' => $ticket->title,
                    'description' => $ticket->description,
                    'priority' => $ticket->priority,
                    'status' => $ticket->status,
                    'call_ext' => $ticket->call_ext,
                    'created_at' => $ticket->created_at->toIso8601String(),
                    'created_at_humans' => $ticket->created_at->diffForHumans(),
                    'reporter' => [
                        'name' => $ticket->user?->name ?? 'System',
                        'email' => $ticket->user?->email,
                    ],
                    'department' => $ticket->department?->name ?? '-',
                    'location' => $ticket->location?->name ?? '-',
                    'category' => $ticket->category?->name ?? '-',
                    'sub_category' => $ticket->subCategory?->name ?? '-',
                    'web_url' => route('tickets.index') . '?ticket_id=' . $ticket->id,
                ];
            });

        return response()->json([
            'count' => $tickets->count(),
            'tickets' => $tickets
        ])->withHeaders([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, X-API-Key, Authorization',
        ]);
    }
}

