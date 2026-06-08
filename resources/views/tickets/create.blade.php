@extends('layouts.app')

@section('page_title', 'Create Ticket')

@section('content')
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
                            <input type="text" name="title" class="form-control" id="title" placeholder="Contoh: Printer macet di lantai 1" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="call_ext" class="form-label label text-secondary">CALL EXTENSION / PHONE</label>
                            <input type="text" name="call_ext" class="form-control" id="call_ext" placeholder="Contoh: Ext 402 atau 0812-3456-xxxx" value="{{ auth()->user()->call_ext }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="department_id" class="form-label label text-secondary">DEPARTMENT</label>
                        <select name="department_id" class="form-select" id="department_id" required>
                            <option value="" disabled selected>Select department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="category_id" class="form-label label text-secondary">CATEGORY</label>
                            <select name="category_id" class="form-select" id="category_id" required>
                                <option value="" disabled selected>Select category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
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
                                <option value="" disabled selected>Select priority</option>
                                <option value="low">Low (Standard Request)</option>
                                <option value="medium">Medium (Individual Issue)</option>
                                <option value="high">High (Department Blocked)</option>
                                <option value="critical">Critical (Infrastructure Outage)</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="attachment" class="form-label label text-secondary">ATTACH FILE (MAX 10MB)</label>
                            <input type="file" name="attachment" class="form-control" id="attachment">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label label text-secondary">DETAILED DESCRIPTION</label>
                        <textarea name="description" class="form-control" id="description" rows="5" placeholder="Contoh: Kertas tersangkut di printer HR lantai 1..." required></textarea>
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

        categorySelect.addEventListener('change', function () {
            const selectedCategoryId = this.value;
            
            subCategorySelect.innerHTML = '<option value="" disabled selected>Select sub category</option>';
            subCategorySelect.disabled = true;

            if (selectedCategoryId && subCategories[selectedCategoryId]) {
                const subs = subCategories[selectedCategoryId];
                
                if (subs.length > 0) {
                    subs.forEach(function (sub) {
                        const option = document.createElement('option');
                        option.value = sub.id;
                        option.textContent = sub.name;
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
        });
    });
</script>
@endsection
