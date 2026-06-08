<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Support Ticket - OTTO IT Support</title>
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --color-primary: #006e2f;
            --color-background: #f3fcef;
            --color-surface: #ffffff;
            --color-border-light: #e5e7eb;
            --rounded-default: 0.5rem;
            --shadow-md: 0 4px 8px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.08);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--color-background);
            color: #161d16;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .card {
            background-color: var(--color-surface);
            border: 1px solid var(--color-border-light);
            border-radius: var(--rounded-default);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 700px;
        }

        .card-header {
            background: linear-gradient(135deg, #006e2f, #22c55e);
            border-bottom: 0;
            padding: 1.5rem;
            border-top-left-radius: calc(var(--rounded-default) - 1px);
            border-top-right-radius: calc(var(--rounded-default) - 1px);
            color: white;
            text-align: center;
        }

        .card-body {
            padding: 2rem;
        }

        .form-control, .form-select {
            border-radius: var(--rounded-default) !important;
            border: 1px solid #d1d5db !important;
            font-size: 14px !important;
            padding: 0.6rem 0.75rem !important;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--color-primary) !important;
            box-shadow: 0 0 0 2px rgba(0, 110, 47, 0.25) !important;
            outline: none !important;
        }

        .btn-primary {
            background-color: var(--color-primary) !important;
            border-color: var(--color-primary) !important;
            color: white !important;
            font-weight: 600 !important;
            height: 44px;
            padding: 0 1.5rem !important;
            border-radius: var(--rounded-default) !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease-in-out;
        }

        .btn-primary:hover {
            background-color: #005321 !important;
            border-color: #005321 !important;
        }

        .btn-link {
            color: var(--color-primary) !important;
            font-weight: 500;
            text-decoration: none;
        }

        .btn-link:hover {
            text-decoration: underline;
        }

        .label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="card-header">
        <h1 class="h3 mb-1 fw-bold"><i class="bi bi-shield-check me-2"></i>OTTO IT Support</h1>
        <p class="mb-0 opacity-75 small">Submit a ticket using your email address (unregistered emails will be auto-registered)</p>
    </div>
    <div class="card-body">
        
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm py-2 px-3 mb-4 d-flex align-items-center" style="background-color: rgba(34, 197, 94, 0.12); color: #15803d; border-radius: var(--rounded-default); font-size: 14px; font-weight: 500;">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i> 
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger border-0 shadow-sm py-2 px-3 mb-4" style="background-color: rgba(239, 68, 68, 0.12); color: #b91c1c; border-radius: var(--rounded-default); font-size: 14px; font-weight: 500;">
                <div class="d-flex align-items-center mb-1">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <strong>Please correct the following errors:</strong>
                </div>
                <ul class="mb-0 ps-4">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tickets.public-store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row g-3 mb-3">
                <div class="col-12 col-md-4">
                    <label for="name" class="form-label label text-secondary">YOUR FULL NAME</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                        <input type="text" name="name" class="form-control border-start-0" id="name" placeholder="Contoh: Budi Santoso" value="{{ old('name') }}" required>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <label for="email" class="form-label label text-secondary">YOUR EMAIL ADDRESS</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control border-start-0" id="email" placeholder="Contoh: budi.santoso@perusahaan.com" value="{{ old('email') }}" required>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <label for="call_ext" class="form-label label text-secondary">CALL EXTENSION / PHONE</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-telephone text-muted"></i></span>
                        <input type="text" name="call_ext" class="form-control border-start-0" id="call_ext" placeholder="Contoh: Ext 402 atau 0812-3456-xxxx" value="{{ old('call_ext') }}">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label label text-secondary">TICKET SUBJECT</label>
                <input type="text" name="title" class="form-control" id="title" placeholder="Contoh: Laptop lambat / Tidak bisa connect VPN" value="{{ old('title') }}" required>
            </div>

            <div class="mb-3">
                <label for="department_id" class="form-label label text-secondary">DEPARTMENT</label>
                <select name="department_id" class="form-select" id="department_id" required>
                    <option value="" disabled selected>Select department</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-12 col-md-6">
                    <label for="category_id" class="form-label label text-secondary">CATEGORY</label>
                    <select name="category_id" class="form-select" id="category_id" required>
                        <option value="" disabled selected>Select category</option>
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
                        <option value="" disabled selected>Select priority</option>
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
                <textarea name="description" class="form-control" id="description" rows="4" placeholder="Contoh: Di ruang meeting lantai 2, muncul error code 0x800f081f saat menghubungkan ke VPN..." required>{{ old('description') }}</textarea>
            </div>

            <div class="d-flex align-items-center justify-content-between pt-2">
                <a href="{{ route('login') }}" class="btn btn-link"><i class="bi bi-arrow-left me-1"></i> Back to Portal Login</a>
                <button type="submit" class="btn btn-primary">
                    Submit Ticket <i class="bi bi-send ms-2"></i>
                </button>
            </div>
        </form>
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
</body>
</html>
