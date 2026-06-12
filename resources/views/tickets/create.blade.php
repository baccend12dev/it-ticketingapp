@extends('layouts.app')

@section('page_title', 'Create Ticket')

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

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <span class="h2 mb-0 text-dark">Submit Support Ticket</span>
            </div>
            <div class="card-body">
                <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="title" class="form-label label text-secondary">TICKET SUBJECT</label>
                            <input type="text" name="title" class="form-control" id="title" placeholder="Contoh: Printer macet di lantai 1" value="{{ old('title') }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="call_ext" class="form-label label text-secondary">CALL EXTENSION / PHONE</label>
                            <input type="text" name="call_ext" class="form-control" id="call_ext" placeholder="Contoh: Ext 402 atau 0812-3456-xxxx" value="{{ old('call_ext', auth()->user()->call_ext) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="department_id" class="form-label label text-secondary">DEPARTMENT</label>
                        <select name="department_id" class="form-select" id="department_id" required>
                            <option value="" disabled {{ old('department_id') === null ? 'selected' : '' }}>Select department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="category_id" class="form-label label text-secondary">CATEGORY</label>
                            <select name="category_id" class="form-select" id="category_id" required>
                                <option value="" disabled {{ old('category_id') === null ? 'selected' : '' }}>Select category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="sub_category_id" class="form-label label text-secondary">SUB CATEGORY</label>
                            <select name="sub_category_id" class="form-select" id="sub_category_id" required disabled>
                                <option value="" disabled selected>Select category first</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="priority" class="form-label label text-secondary">PRIORITY LEVEL</label>
                            <select name="priority" class="form-select" id="priority" required>
                                <option value="" disabled {{ old('priority') === null ? 'selected' : '' }}>Select priority</option>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low (Standard Request)</option>
                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium (Individual Issue)</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High (Department Blocked)</option>
                                <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Critical (Infrastructure Outage)</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="attachment" class="form-label label text-secondary">ATTACH FILE (MAX 10MB)</label>
                            <input type="file" name="attachment" class="form-control" id="attachment">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label label text-secondary">DETAILED DESCRIPTION</label>
                        <textarea name="description" class="form-control" id="description" rows="5" placeholder="Contoh: Kertas tersangkut di printer HR lantai 1..." required>{{ old('description') }}</textarea>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <button type="submit" class="btn btn-primary">
                            Submit Ticket <i class="bi bi-send ms-2"></i>
                        </button>
                        <a href="{{ route('tickets.index') }}" class="btn btn-ghost">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categorySelect = document.getElementById('category_id');
        const subCategorySelect = document.getElementById('sub_category_id');
        
        const subCategories = {
            @foreach($categories as $category)
                "{{ $category->id }}": [
                    @foreach($category->subCategories as $sub)
                        { id: "{{ $sub->id }}", name: "{{ $sub->name }}" },
                    @endforeach
                ],
            @endforeach
        };

        function updateSubCategories(categoryId, selectedSubId = null) {
            subCategorySelect.innerHTML = '<option value="" disabled selected>Select sub category</option>';
            subCategorySelect.disabled = true;

            if (categoryId && subCategories[categoryId]) {
                const subs = subCategories[categoryId];
                
                if (subs.length > 0) {
                    subs.forEach(function (sub) {
                        const option = document.createElement('option');
                        option.value = sub.id;
                        option.textContent = sub.name;
                        if (selectedSubId && selectedSubId == sub.id) {
                            option.selected = true;
                        }
                        subCategorySelect.appendChild(option);
                    });
                    subCategorySelect.disabled = false;
                } else {
                    const option = document.createElement('option');
                    option.value = "";
                    option.textContent = "No subcategories available";
                    option.disabled = true;
                    subCategorySelect.appendChild(option);
                }
            }
        }

        categorySelect.addEventListener('change', function () {
            updateSubCategories(this.value);
        });

        // Retain selection on validation failure
        @if(old('category_id'))
            updateSubCategories("{{ old('category_id') }}", "{{ old('sub_category_id') }}");
        @endif
    });
</script>
@endsection
