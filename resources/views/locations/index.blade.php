@extends('layouts.app')

@section('page_title', 'Locations Management')

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

@if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm py-2 px-3 mb-4 rounded-default" style="background-color: rgba(239, 68, 68, 0.12); color: #b91c1c; font-size: 14px; font-weight: 500;">
        <div class="d-flex align-items-center mb-1">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Please correct the following errors:</strong>
        </div>
        <ul class="mb-0 ps-4">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span class="h2 mb-0 text-dark">Location List</span>
        <button class="btn btn-primary btn-sm py-1 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addLocModal">
            <i class="bi bi-plus-lg me-1"></i> Add Location
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 14px;">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3 text-secondary py-3" style="font-size: 12px; font-weight: 600; width: 60px;">ID</th>
                        <th class="text-secondary py-3" style="font-size: 12px; font-weight: 600;">NAME</th>
                        <th class="text-secondary py-3" style="font-size: 12px; font-weight: 600;">DESCRIPTION</th>
                        <th class="text-secondary py-3" style="font-size: 12px; font-weight: 600; width: 100px;">STATUS</th>
                        <th class="pe-3 text-end py-3" style="font-size: 12px; font-weight: 600; width: 220px;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $loc)
                        <tr>
                            <td class="ps-3 text-secondary fw-semibold">#{{ $loc->id }}</td>
                            <td><span class="fw-semibold text-dark">{{ $loc->name }}</span></td>
                            <td class="text-secondary text-truncate" style="max-width: 300px;">{{ $loc->description ?? '-' }}</td>
                            <td>
                                <span class="badge-pill-custom @if($loc->is_active) badge-active @else badge-critical @endif">
                                    {{ $loc->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="pe-3 text-end">
                                <!-- Edit trigger -->
                                <button class="btn btn-outline-dark btn-sm px-2 py-1 me-1" 
                                        style="font-size: 12px; border-radius: var(--rounded-default); min-width: 70px;"
                                        data-bs-toggle="modal" data-bs-target="#editLocModal-{{ $loc->id }}">
                                    <i class="bi bi-pencil me-1"></i>Edit
                                </button>

                                <!-- Delete -->
                                <form action="{{ route('locations.destroy', $loc) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Are you sure you want to delete location \'{{ $loc->name }}\'?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-sm text-danger px-2 py-1">
                                        <i class="bi bi-trash3 me-1"></i>Delete
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Location Modal -->
                        <div class="modal fade" id="editLocModal-{{ $loc->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content" style="border-radius: var(--rounded-default); border: 0; box-shadow: var(--shadow-lg);">
                                    <div class="modal-header border-bottom">
                                        <h5 class="modal-title fw-bold text-dark">
                                            <i class="bi bi-pencil me-2 text-primary"></i>Edit Location
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('locations.update', $loc) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body text-start">
                                            <div class="mb-3">
                                                <label for="edit_name_{{ $loc->id }}" class="form-label label text-secondary">LOCATION NAME</label>
                                                <input type="text" name="name" id="edit_name_{{ $loc->id }}" class="form-control" value="{{ $loc->name }}" required>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="edit_desc_{{ $loc->id }}" class="form-label label text-secondary">DESCRIPTION</label>
                                                <textarea name="description" id="edit_desc_{{ $loc->id }}" class="form-control" rows="3">{{ $loc->description }}</textarea>
                                            </div>

                                            <div class="mb-3 form-check form-switch d-flex align-items-center gap-2">
                                                <input class="form-check-input mt-0" type="checkbox" name="is_active" value="1" id="edit_active_{{ $loc->id }}" {{ $loc->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label label text-secondary" style="margin-top: 2px;" for="edit_active_{{ $loc->id }}">IS ACTIVE</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top">
                                            <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">
                                                Save Changes <i class="bi bi-check-lg ms-1"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-secondary">No locations found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Location Modal -->
<div class="modal fade" id="addLocModal" tabindex="-1" aria-labelledby="addLocModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: var(--rounded-default); border: 0; box-shadow: var(--shadow-lg);">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold text-dark" id="addLocModalLabel">
                    <i class="bi bi-plus-circle me-2 text-primary"></i>Add New Location
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('locations.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label label text-secondary">LOCATION NAME</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="e.g. Server Room 3B" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label label text-secondary">DESCRIPTION</label>
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Brief details about the location..."></textarea>
                    </div>

                    <div class="mb-3 form-check form-switch d-flex align-items-center gap-2">
                        <input class="form-check-input mt-0" type="checkbox" name="is_active" value="1" id="is_active" checked>
                        <label class="form-check-label label text-secondary" style="margin-top: 2px;" for="is_active">IS ACTIVE</label>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        Create Location <i class="bi bi-check-lg ms-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
