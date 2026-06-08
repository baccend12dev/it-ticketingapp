@extends('layouts.app')

@section('page_title', 'Users Management')

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
    <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <span class="h2 mb-0 text-dark">User Directory</span>
        <div class="d-flex align-items-center gap-2 flex-grow-1 flex-md-grow-0 justify-content-end">
            <form action="{{ route('users.index') }}" method="GET" class="d-flex align-items-center mb-0">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <span class="input-group-text bg-light border-end-0 border-light-subtle" style="border-color: #d1d5db;"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 py-1" style="border-color: #d1d5db;" placeholder="Cari ext, email, nama..." value="{{ request('search') }}">
                    @if(request('search'))
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary border-start-0 d-flex align-items-center justify-content-center" style="border-color: #d1d5db; background-color: #f3f4f6;"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </form>
            <button class="btn btn-primary btn-sm py-1 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="bi bi-person-plus me-1"></i> Add User
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 14px;">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3 text-secondary py-3" style="font-size: 12px; font-weight: 600;">NAME</th>
                        <th class="text-secondary py-3" style="font-size: 12px; font-weight: 600;">EMAIL</th>
                        <th class="text-secondary py-3" style="font-size: 12px; font-weight: 600;">DEPARTMENT</th>
                        <th class="text-secondary py-3" style="font-size: 12px; font-weight: 600;">CALL EXTENSION</th>
                        <th class="text-secondary py-3" style="font-size: 12px; font-weight: 600;">ROLE</th>
                        <th class="text-secondary py-3" style="font-size: 12px; font-weight: 600;">STATUS</th>
                        <th class="pe-3 text-end py-3" style="font-size: 12px; font-weight: 600; width: 220px;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center fw-bold text-secondary text-uppercase" style="width: 32px; height: 32px; font-size: 12px; border: 1px solid var(--color-border-light);">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <span class="fw-semibold text-dark">{{ $user->name }}</span>
                                        @if($user->id === auth()->id())
                                            <span class="badge bg-secondary text-white rounded-pill body-sm ms-1" style="font-size: 9px; padding: 0.15rem 0.35rem;">YOU</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-secondary">{{ $user->email }}</td>
                            <td class="text-secondary">
                                @if($user->role === 'user')
                                    {{ $user->department->name ?? '-' }}
                                @else
                                    <span class="text-muted small">N/A</span>
                                @endif
                            </td>
                            <td class="text-secondary">
                                @if($user->role === 'user')
                                    {{ $user->call_ext ?? '-' }}
                                @else
                                    <span class="text-muted small">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge-pill-custom 
                                    @if($user->role === 'admin') badge-critical 
                                    @elseif($user->role === 'it') badge-active 
                                    @else badge-resolved @endif">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-pill-custom @if($user->is_active) badge-active @else badge-critical @endif">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="pe-3 text-end">
                                <!-- Toggle Active/Inactive Status -->
                                <form action="{{ route('users.toggle-status', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm px-2 py-1 me-1 {{ $user->is_active ? 'btn-outline-secondary' : 'btn-outline-success' }}" 
                                            style="font-size: 12px; border-radius: var(--rounded-default); min-width: 90px;" 
                                            {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                        @if($user->is_active)
                                            <i class="bi bi-pause-fill me-1"></i>Deactivate
                                        @else
                                            <i class="bi bi-play-fill me-1"></i>Activate
                                        @endif
                                    </button>
                                </form>

                                @if(auth()->user()->isAdmin())
                                    <!-- Delete Account -->
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to delete user \'{{ $user->name }}\'? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-sm text-danger px-2 py-1" 
                                                {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                            <i class="bi bi-trash3 me-1"></i>Delete
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: var(--rounded-default); border: 0; box-shadow: var(--shadow-lg);">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold text-dark" id="addUserModalLabel">
                    <i class="bi bi-person-plus me-2 text-primary"></i>Register New Account
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modal_name" class="form-label label text-secondary">FULL NAME</label>
                        <input type="text" name="name" id="modal_name" class="form-control" placeholder="Contoh: Budi Santoso" value="{{ old('name') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_email" class="form-label label text-secondary">EMAIL ADDRESS</label>
                        <input type="email" name="email" id="modal_email" class="form-control" placeholder="Contoh: budi.santoso@perusahaan.com" value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="modal_password" class="form-label label text-secondary">PASSWORD (OPTIONAL, DEFAULTS TO 'password')</label>
                        <input type="password" name="password" id="modal_password" class="form-control" placeholder="Contoh: rahasia123 (Minimal 6 karakter)">
                    </div>

                    <div class="mb-3">
                        <label for="modal_role" class="form-label label text-secondary">ACCOUNT ROLE</label>
                        <select name="role" id="modal_role" class="form-select" required>
                            <option value="" disabled>Select account role</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator (Full Access)</option>
                            <option value="it" {{ old('role') == 'it' ? 'selected' : '' }}>IT Support (Ticket Agent)</option>
                            <option value="user" {{ old('role') == 'user' || is_null(old('role')) ? 'selected' : '' }}>Regular User (End User)</option>
                        </select>
                    </div>

                    <div id="role_user_fields" style="display: none;">
                        <div class="mb-3">
                            <label for="modal_call_ext" class="form-label label text-secondary">CALL EXTENSION / PHONE</label>
                            <input type="text" name="call_ext" id="modal_call_ext" class="form-control" placeholder="Contoh: Ext 402 atau 102" value="{{ old('call_ext') }}">
                        </div>

                        <div class="mb-3">
                            <label for="modal_department_id" class="form-label label text-secondary">DEPARTMENT</label>
                            <select name="department_id" id="modal_department_id" class="form-select">
                                <option value="" selected>Select department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        Create User <i class="bi bi-check-lg ms-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const roleSelect = document.getElementById('modal_role');
        const userFields = document.getElementById('role_user_fields');

        function toggleUserFields() {
            if (roleSelect.value === 'user') {
                userFields.style.display = 'block';
            } else {
                userFields.style.display = 'none';
                // Clear values when hidden
                document.getElementById('modal_call_ext').value = '';
                document.getElementById('modal_department_id').value = '';
            }
        }

        roleSelect.addEventListener('change', toggleUserFields);

        // Run on load in case of validation redirect / old inputs
        toggleUserFields();
    });
</script>
@endsection
