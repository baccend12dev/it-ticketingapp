@extends('layouts.app')

@section('page_title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <!-- Welcome Panel -->
    <div class="col-12">
        <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, rgba(0, 110, 47, 0.05), rgba(34, 197, 94, 0.02)); border-left: 5px solid var(--color-primary) !important;">
            <div class="card-body p-4">
                <h1 class="h1 text-dark mb-1">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-secondary mb-0">System status is operational. You are signed in as <strong class="text-dark">{{ strtoupper(auth()->user()->role) }}</strong>.</p>
            </div>
        </div>
    </div>
</div>

<!-- Metric Cards Grid -->
<div class="row g-4 mb-4">
    <!-- Total Tickets -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 clickable-card" onclick="window.location='{{ route('tickets.index') }}'">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle p-3 bg-light text-secondary me-3" style="font-size: 1.5rem; line-height: 1; width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-ticket-perforated"></i>
                </div>
                <div>
                    <h6 class="text-muted label mb-1">TOTAL TICKETS</h6>
                    <h2 class="mb-0 text-dark fw-bold">{{ $stats['total'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Tickets -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 clickable-card" onclick="window.location='{{ route('tickets.index', ['status' => 'active']) }}'">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle p-3 me-3" style="font-size: 1.5rem; line-height: 1; width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; background-color: rgba(34, 197, 94, 0.1); color: var(--color-primary);">
                    <i class="bi bi-activity"></i>
                </div>
                <div>
                    <h6 class="text-muted label mb-1">ACTIVE TICKETS</h6>
                    <h2 class="mb-0 text-dark fw-bold">{{ $stats['active'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Tickets -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 clickable-card" onclick="window.location='{{ route('tickets.index', ['status' => 'pending']) }}'">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle p-3 me-3" style="font-size: 1.5rem; line-height: 1; width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; background-color: rgba(245, 158, 11, 0.1); color: #b45309;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div>
                    <h6 class="text-muted label mb-1">PENDING APPROVAL</h6>
                    <h2 class="mb-0 text-dark fw-bold">{{ $stats['pending'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Resolved Tickets -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 clickable-card" onclick="window.location='{{ route('tickets.index', ['status' => 'resolved']) }}'">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle p-3 me-3" style="font-size: 1.5rem; line-height: 1; width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; background-color: rgba(59, 130, 246, 0.1); color: #1d4ed8;">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <h6 class="text-muted label mb-1">RESOLVED ISSUES</h6>
                    <h2 class="mb-0 text-dark fw-bold">{{ $stats['resolved'] }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Active Support Queue -->
    <div class="col-12 col-xl-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="h2 mb-0 text-dark">Active Support Queue</span>
                <a href="{{ route('tickets.index') }}" class="btn btn-ghost btn-sm p-1">View All <i class="bi bi-chevron-right ms-1"></i></a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 14px;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3 text-secondary py-3" style="font-size: 12px; font-weight: 600;">ID</th>
                                <th class="text-secondary py-3" style="font-size: 12px; font-weight: 600;">SUBJECT / CAT</th>
                                <th class="text-secondary py-3" style="font-size: 12px; font-weight: 600;">PRIORITY</th>
                                <th class="text-secondary py-3" style="font-size: 12px; font-weight: 600;">STATUS</th>
                                <th class="text-secondary py-3" style="font-size: 12px; font-weight: 600;">SUBMITTED BY</th>
                                <th class="pe-3 text-end py-3" style="font-size: 12px; font-weight: 600;">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentTickets as $ticket)
                                @php
                                    $formattedId = 1000 + $ticket->id;
                                @endphp
                                <tr>
                                    <td class="ps-3 fw-bold text-secondary">#TK-{{ $formattedId }}</td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $ticket->title }}</div>
                                        <span class="caption text-muted">{{ $ticket->category ? $ticket->category->name : 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge-pill-custom 
                                            @if(strtolower($ticket->priority) === 'critical' || strtolower($ticket->priority) === 'high') badge-critical 
                                            @elseif(strtolower($ticket->priority) === 'medium') badge-pending 
                                            @else badge-resolved @endif">
                                            {{ ucfirst($ticket->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge-pill-custom 
                                            @if(strtolower($ticket->status) === 'active') badge-active 
                                            @elseif(strtolower($ticket->status) === 'pending') badge-pending 
                                            @elseif(strtolower($ticket->status) === 'canceled') badge-critical 
                                            @else badge-resolved @endif">
                                            {{ ucfirst($ticket->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="body-sm text-dark">{{ $ticket->user ? $ticket->user->name : 'System' }}</div>
                                        <span class="caption text-muted">{{ $ticket->created_at->diffForHumans() }}</span>
                                    </td>
                                    <td class="pe-3 text-end">
                                        <button class="btn btn-ghost btn-sm px-2 py-1" 
                                                data-bs-toggle="modal" data-bs-target="#ticketModal-{{ $ticket->id }}"
                                                title="View Details" aria-label="View Details"><i class="bi bi-eye"></i></button>
                                    </td>
                                </tr>

                                <!-- Ticket Detail Modal -->
                                <div class="modal fade" id="ticketModal-{{ $ticket->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content" style="border-radius: var(--rounded-default); border: 0; box-shadow: var(--shadow-lg);">
                                            <div class="modal-header border-bottom">
                                                <h5 class="modal-title fw-bold text-dark">
                                                    <i class="bi bi-ticket-detailed me-2 text-primary"></i>Ticket Details #TK-{{ $formattedId }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-start">
                                                <div class="mb-3">
                                                    <strong class="text-secondary label d-block mb-1">SUBJECT</strong>
                                                    <div class="fw-semibold text-dark fs-5">{{ $ticket->title }}</div>
                                                </div>

                                                <div class="row g-3 mb-3">
                                                    <div class="col-6">
                                                        <strong class="text-secondary label d-block mb-1">STATUS</strong>
                                                        <div>
                                                            <span class="badge-pill-custom @if($ticket->status === 'active') badge-active @elseif($ticket->status === 'pending') badge-pending @elseif($ticket->status === 'canceled') badge-critical @else badge-resolved @endif">
                                                                {{ ucfirst($ticket->status) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <strong class="text-secondary label d-block mb-1">PRIORITY</strong>
                                                        <div>
                                                            <span class="badge-pill-custom @if($ticket->priority === 'critical' || $ticket->priority === 'high') badge-critical @elseif($ticket->priority === 'medium') badge-pending @else badge-resolved @endif">
                                                                {{ ucfirst($ticket->priority) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row g-3 mb-3">
                                                    <div class="col-6">
                                                        <strong class="text-secondary label d-block mb-1">CATEGORY</strong>
                                                        <div class="text-dark body-sm">{{ $ticket->category ? $ticket->category->name : 'N/A' }}</div>
                                                    </div>
                                                    <div class="col-6">
                                                        <strong class="text-secondary label d-block mb-1">SUB CATEGORY</strong>
                                                        <div class="text-dark body-sm">{{ $ticket->subCategory ? $ticket->subCategory->name : 'N/A' }}</div>
                                                    </div>
                                                </div>

                                                <div class="row g-3 mb-3">
                                                    <div class="col-6">
                                                        <strong class="text-secondary label d-block mb-1">DEPARTMENT</strong>
                                                        <div class="text-dark body-sm">{{ $ticket->department ? $ticket->department->name : 'N/A' }}</div>
                                                    </div>
                                                    <div class="col-6">
                                                        <strong class="text-secondary label d-block mb-1">LOCATION</strong>
                                                        <div class="text-dark body-sm">{{ $ticket->location ? $ticket->location->name : 'N/A' }}</div>
                                                    </div>
                                                </div>

                                                <div class="row g-3 mb-3">
                                                    <div class="col-6">
                                                        <strong class="text-secondary label d-block mb-1">SUBMITTED BY</strong>
                                                        <div class="text-dark body-sm">{{ $ticket->user ? $ticket->user->name : 'System' }} ({{ $ticket->user ? $ticket->user->email : 'N/A' }})</div>
                                                        <span class="caption text-muted d-block">{{ $ticket->created_at->format('M d, Y h:i A') }} ({{ $ticket->created_at->diffForHumans() }})</span>
                                                    </div>
                                                    <div class="col-6">
                                                        <strong class="text-secondary label d-block mb-1">CALL EXTENSION / PHONE</strong>
                                                        <div class="text-dark body-sm fw-semibold">{{ $ticket->call_ext ?? '-' }}</div>
                                                    </div>
                                                </div>

                                                @if($ticket->attachment)
                                                    <div class="mb-3">
                                                        <strong class="text-secondary label d-block mb-1">ATTACHMENT</strong>
                                                        @php
                                                            $extension = pathinfo($ticket->attachment, PATHINFO_EXTENSION);
                                                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                                                        @endphp
                                                        @if($isImage)
                                                            <div class="mb-2">
                                                                <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank">
                                                                    <img src="{{ asset('storage/' . $ticket->attachment) }}" class="img-fluid rounded border shadow-sm" style="max-height: 200px; object-fit: contain; background-color: #f8f9fa; padding: 4px;" alt="Ticket Attachment">
                                                                </a>
                                                            </div>
                                                        @endif
                                                        <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank" class="btn btn-outline-success btn-sm py-1 px-3 d-inline-flex align-items-center" style="font-size: 12px; border-radius: var(--rounded-default); border-color: var(--color-primary); color: var(--color-primary); background: transparent;">
                                                            <i class="bi bi-file-earmark-arrow-down me-1 fs-6"></i> Download Attachment File
                                                        </a>
                                                    </div>
                                                @endif

                                                <div class="mb-0">
                                                    <strong class="text-secondary label d-block mb-1">DESCRIPTION</strong>
                                                    <div class="p-3 bg-light rounded-default text-dark body-sm" style="white-space: pre-line;">{{ $ticket->description }}</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top">
                                                <button type="button" class="btn btn-primary btn-sm py-1" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Tools -->
    <div class="col-12 col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <span class="h2 mb-0 text-dark">Quick Operations</span>
            </div>
            <div class="card-body">
                <p class="text-muted body-sm mb-4">Perform key IT management tasks quickly using the shortcuts below.</p>
                <div class="d-grid gap-3">
                    <a href="{{ route('tickets.create') }}" class="btn btn-primary w-100">
                        <i class="bi bi-plus-circle me-2"></i> Create New Ticket
                    </a>
                    
                    <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary w-100 py-2 d-flex align-items-center justify-content-center" style="border-radius: var(--rounded-default); color: var(--color-secondary); border-color: #d1d5db;">
                        <i class="bi bi-ticket-detailed me-2"></i> View Support Queue
                    </a>

                    @if(auth()->user()->isAdmin() || auth()->user()->isIT())
                        <a href="{{ route('users.index') }}" class="btn btn-outline-dark w-100 py-2 d-flex align-items-center justify-content-center" style="border-radius: var(--rounded-default); border-color: #d1d5db;">
                            <i class="bi bi-people me-2"></i> User Directory
                        </a>
                    @endif
                    
                    <a href="{{ route('settings') }}" class="btn btn-outline-dark w-100 py-2 d-flex align-items-center justify-content-center" style="border-radius: var(--rounded-default); border-color: #d1d5db;">
                        <i class="bi bi-gear me-2"></i> Platform Settings
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
