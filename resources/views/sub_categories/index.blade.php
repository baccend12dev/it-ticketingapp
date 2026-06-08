@extends('layouts.app')

@section('page_title', 'Sub Categories Management')

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
        <span class="h2 mb-0 text-dark">Sub Category List</span>
        <button class="btn btn-primary btn-sm py-1 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addSubCatModal">
            <i class="bi bi-plus-lg me-1"></i> Add Sub Category
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 14px;">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3 text-secondary py-3" style="font-size: 12px; font-weight: 600; width: 60px;">ID</th>
                        <th class="text-secondary py-3" style="font-size: 12px; font-weight: 600;">SUB CATEGORY NAME</th>
                        <th class="text-secondary py-3" style="font-size: 12px; font-weight: 600;">PARENT CATEGORY</th>
                        <th class="text-secondary py-3" style="font-size: 12px; font-weight: 600;">DESCRIPTION</th>
                        <th class="text-secondary py-3" style="font-size: 12px; font-weight: 600; width: 100px;">STATUS</th>
                        <th class="pe-3 text-end py-3" style="font-size: 12px; font-weight: 600; width: 220px;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subCategories as $sc)
                        <tr>
                            <td class="ps-3 text-secondary fw-semibold">#{{ $sc->id }}</td>
                            <td><span class="fw-semibold text-dark">{{ $sc->name }}</span></td>
                            <td>
                                <span class="badge bg-secondary text-white border px-2 py-1 body-sm rounded-default" style="font-weight: 500; font-size: 11px;">
                                    {{ $sc->category ? $sc->category->name : 'N/A' }}
                                </span>
                            </td>
                            <td class="text-secondary text-truncate" style="max-width: 250px;">{{ $sc->description ?? '-' }}</td>
                            <td>
                                <span class="badge-pill-custom @if($sc->is_active) badge-active @else badge-critical @endif">
                                    {{ $sc->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="pe-3 text-end">
                                <!-- Edit trigger -->
                                <button class="btn btn-outline-dark btn-sm px-2 py-1 me-1" 
                                        style="font-size: 12px; border-radius: var(--rounded-default); min-width: 70px;"
                                        data-bs-toggle="modal" data-bs-target="#editSubCatModal-{{ $sc->id }}">
                                    <i class="bi bi-pencil me-1"></i>Edit
                                </button>

                                <!-- Delete -->
                                <form action="{{ route('sub-categories.destroy', $sc) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Are you sure you want to delete sub category \'{{ $sc->name }}\'?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-sm text-danger px-2 py-1">
                                        <i class="bi bi-trash3 me-1"></i>Delete
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit SubCategory Modal -->
                        <div class="modal fade" id="editSubCatModal-{{ $sc->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content" style="border-radius: var(--rounded-default); border: 0; box-shadow: var(--shadow-lg);">
                                    <div class="modal-header border-bottom">
                                        <h5 class="modal-title fw-bold text-dark">
                                            <i class="bi bi-pencil me-2 text-primary"></i>Edit Sub Category
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('sub-categories.update', $sc) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body text-start">
                                            <div class="mb-3">
                                                <label for="edit_category_id_{{ $sc->id }}" class="form-label label text-secondary">PARENT CATEGORY</label>
                                                <select name="category_id" id="edit_category_id_{{ $sc->id }}" class="form-select" required>
                                                    <option value="" disabled>Select category</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ $sc->category_id == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="edit_name_{{ $sc->id }}" class="form-label label text-secondary">SUB CATEGORY NAME</label>
                                                <input type="text" name="name" id="edit_name_{{ $sc->id }}" class="form-control" value="{{ $sc->name }}" required>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="edit_desc_{{ $sc->id }}" class="form-label label text-secondary">DESCRIPTION</label>
                                                <textarea name="description" id="edit_desc_{{ $sc->id }}" class="form-control" rows="3">{{ $sc->description }}</textarea>
                                            </div>

                                            <div class="mb-3 form-check form-switch d-flex align-items-center gap-2">
                                                <input class="form-check-input mt-0" type="checkbox" name="is_active" value="1" id="edit_active_{{ $sc->id }}" {{ $sc->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label label text-secondary" style="margin-top: 2px;" for="edit_active_{{ $sc->id }}">IS ACTIVE</label>
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
                            <td colspan="6" class="text-center py-4 text-secondary">No sub categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add SubCategory Modal -->
<div class="modal fade" id="addSubCatModal" tabindex="-1" aria-labelledby="addSubCatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: var(--rounded-default); border: 0; box-shadow: var(--shadow-lg);">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold text-dark" id="addSubCatModalLabel">
                    <i class="bi bi-plus-circle me-2 text-primary"></i>Add New Sub Category
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('sub-categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category_id" class="form-label label text-secondary">PARENT CATEGORY</label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="" disabled selected>Select category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label label text-secondary">SUB CATEGORY NAME</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="e.g. WiFi Connection Issue" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label label text-secondary">DESCRIPTION</label>
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Brief details about the subcategory..."></textarea>
                    </div>

                    <div class="mb-3 form-check form-switch d-flex align-items-center gap-2">
                        <input class="form-check-input mt-0" type="checkbox" name="is_active" value="1" id="is_active" checked>
                        <label class="form-check-label label text-secondary" style="margin-top: 2px;" for="is_active">IS ACTIVE</label>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        Create Sub Category <i class="bi bi-check-lg ms-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
