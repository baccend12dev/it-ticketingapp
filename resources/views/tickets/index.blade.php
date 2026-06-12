@extends('layouts.app')

@section('page_title', 'My Tickets')

@section('content')

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm py-2 px-3 mb-4 rounded-default" style="background-color: rgba(34, 197, 94, 0.12); color: #15803d; font-size: 14px; font-weight: 500;">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm py-2 px-3 mb-4 rounded-default" style="background-color: rgba(239, 68, 68, 0.12); color: #b91c1c; font-size: 14px; font-weight: 500;">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
    </div>
@endif

<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
        <span class="h2 mb-0 text-dark">Ticket Directory</span>
        <a href="{{ route('tickets.create') }}" class="btn btn-primary btn-sm py-1 d-flex align-items-center">
            <i class="bi bi-plus-lg me-1"></i> New Ticket
        </a>
    </div>
    
    <!-- Filter bar -->
    <div class="card-body bg-light border-bottom p-3">
        <div class="d-flex flex-wrap gap-2">
            <button class="btn btn-primary btn-sm active filter-btn" data-status="all" style="height: 32px; padding: 0 12px !important; font-size: 13px;">All Tickets</button>
            <button class="btn btn-outline-secondary btn-sm filter-btn" data-status="active" style="height: 32px; padding: 0 12px !important; border-radius: var(--rounded-default); font-size: 13px; color: var(--color-secondary); border-color: #d1d5db;">Active</button>
            <button class="btn btn-outline-secondary btn-sm filter-btn" data-status="pending" style="height: 32px; padding: 0 12px !important; border-radius: var(--rounded-default); font-size: 13px; color: var(--color-secondary); border-color: #d1d5db;">Pending Approval</button>
            <button class="btn btn-outline-secondary btn-sm filter-btn" data-status="resolved" style="height: 32px; padding: 0 12px !important; border-radius: var(--rounded-default); font-size: 13px; color: var(--color-secondary); border-color: #d1d5db;">Resolved</button>
            <button class="btn btn-outline-secondary btn-sm filter-btn" data-status="canceled" style="height: 32px; padding: 0 12px !important; border-radius: var(--rounded-default); font-size: 13px; color: var(--color-secondary); border-color: #d1d5db;">Canceled</button>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 14px;">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3 text-secondary py-3" style="font-size: 12px; font-weight: 600; width: 100px;">TICKET ID</th>
                        <th class="text-secondary py-3" style="font-size: 12px; font-weight: 600;">SUBJECT / CATEGORY</th>
                        <th class="text-secondary py-3" style="font-size: 12px; font-weight: 600;">STATUS</th>
                        <th class="text-secondary py-3" style="font-size: 12px; font-weight: 600;">PRIORITY</th>
                        <th class="text-secondary py-3" style="font-size: 12px; font-weight: 600;">SUBMITTED</th>
                        <th class="pe-3 text-end py-3" style="font-size: 12px; font-weight: 600; width: 100px;">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        @php
                            $formattedId = 1000 + $ticket->id;
                        @endphp
                        <tr data-row-status="{{ strtolower($ticket->status) }}">
                            <td class="ps-3 fw-bold text-secondary">#TK-{{ $formattedId }}</td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $ticket->title }}</div>
                                <span class="caption text-muted">
                                    {{ $ticket->category ? $ticket->category->name : 'N/A' }} 
                                    &bull; {{ $ticket->subCategory ? $ticket->subCategory->name : 'N/A' }} 
                                    &bull; {{ $ticket->department ? $ticket->department->name : 'N/A' }} 
                                    &bull; {{ $ticket->location ? $ticket->location->name : 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-pill-custom 
                                    @if($ticket->status === 'active') badge-active 
                                    @elseif($ticket->status === 'pending') badge-pending 
                                    @elseif($ticket->status === 'canceled') badge-critical 
                                    @else badge-resolved @endif">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-pill-custom 
                                    @if($ticket->priority === 'critical') badge-critical 
                                    @elseif($ticket->priority === 'high') badge-critical 
                                    @elseif($ticket->priority === 'medium') badge-pending 
                                    @else badge-resolved @endif">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td>
                                <div class="body-sm text-dark">{{ $ticket->user ? $ticket->user->name : 'System' }}</div>
                                <span class="caption text-muted">{{ $ticket->created_at->diffForHumans() }}</span>
                            </td>
                            <td class="pe-3 text-end">
                                <div class="d-inline-flex gap-1 align-items-center">
                                    <button class="btn btn-ghost btn-sm px-2 py-1" 
                                            data-bs-toggle="modal" data-bs-target="#ticketModal-{{ $ticket->id }}"
                                            title="View Details" aria-label="View Details"><i class="bi bi-eye"></i></button>
                                    
                                    <div class="dropdown d-inline">
                                        <button class="btn btn-ghost btn-sm px-2 py-1" 
                                                style="border: 0; padding: 0.25rem 0.5rem;" 
                                                type="button" 
                                                id="statusDropdown-{{ $ticket->id }}" 
                                                data-bs-toggle="dropdown" 
                                                aria-expanded="false" 
                                                title="Change Status">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2" 
                                            aria-labelledby="statusDropdown-{{ $ticket->id }}" 
                                            style="border-radius: var(--rounded-default); font-size: 13px; min-width: 150px; z-index: 1050;">
                                            <li><h6 class="dropdown-header text-secondary font-weight-bold" style="font-size: 11px;">CHANGE STATUS</h6></li>
                                            <li>
                                                <form action="{{ route('tickets.update-status', $ticket) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="pending">
                                                    <button type="submit" class="dropdown-item d-flex align-items-center gap-2 {{ $ticket->status === 'pending' ? 'active' : '' }}">
                                                        <i class="bi bi-hourglass-split text-warning"></i> Pending
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('tickets.update-status', $ticket) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="active">
                                                    <button type="submit" class="dropdown-item d-flex align-items-center gap-2 {{ $ticket->status === 'active' ? 'active' : '' }}">
                                                        <i class="bi bi-play-circle text-success"></i> Active
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('tickets.update-status', $ticket) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="resolved">
                                                    <button type="submit" class="dropdown-item d-flex align-items-center gap-2 {{ $ticket->status === 'resolved' ? 'active' : '' }}">
                                                        <i class="bi bi-check-circle-fill text-primary"></i> Resolved
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('tickets.update-status', $ticket) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="canceled">
                                                    <button type="submit" class="dropdown-item d-flex align-items-center gap-2 {{ $ticket->status === 'canceled' ? 'active' : '' }}">
                                                        <i class="bi bi-x-circle text-danger"></i> Canceled
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
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
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-secondary">No tickets found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterBtns = document.querySelectorAll('.filter-btn');
        const rows = document.querySelectorAll('tbody tr[data-row-status]');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                // Remove active class from all buttons
                filterBtns.forEach(b => {
                    b.classList.remove('btn-primary', 'active');
                    b.classList.add('btn-outline-secondary');
                    // Reset styling
                    b.style.color = 'var(--color-secondary)';
                    b.style.borderColor = '#d1d5db';
                    b.style.backgroundColor = 'transparent';
                });

                // Add active class to clicked button
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-primary', 'active');
                this.style.color = '';
                this.style.borderColor = '';
                this.style.backgroundColor = '';

                const status = this.getAttribute('data-status');
                rows.forEach(row => {
                    if (status === 'all' || row.getAttribute('data-row-status') === status) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });

        // Handle URL status filtering
        const urlParams = new URLSearchParams(window.location.search);
        const statusParam = urlParams.get('status');
        if (statusParam) {
            const targetBtn = document.querySelector(`.filter-btn[data-status="${statusParam}"]`);
            if (targetBtn) {
                targetBtn.click();
            }
        }

        // Handle auto-opening ticket details modal if ticket_id parameter is present
        const ticketIdParam = urlParams.get('ticket_id');
        if (ticketIdParam) {
            const targetModalEl = document.getElementById(`ticketModal-${ticketIdParam}`);
            if (targetModalEl) {
                const modal = new bootstrap.Modal(targetModalEl);
                modal.show();
            }
        }
    });
</script>
@endsection
