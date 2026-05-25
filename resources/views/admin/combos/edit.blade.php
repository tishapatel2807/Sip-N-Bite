@extends('admin.layouts.app')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.menu.index') }}" class="text-muted text-decoration-none small">
        <i class="fas fa-arrow-left me-2"></i> Back to Menu
    </a>
    <h3 class="fw-bold mt-2">Edit Combo</h3>
</div>

<div class="card border-0 shadow-sm p-4 rounded-4" style="max-width: 720px;">
    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="fw-bold mb-2">Please fix the following:</div>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.combos.update', $combo->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label small fw-bold">Combo Name</label>
            <input type="text" name="title" class="form-control"
                   value="{{ old('title', $combo->title) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">Short Detail</label>
            <input type="text" name="tagline" class="form-control"
                   value="{{ old('tagline', $combo->tagline) }}"
                   placeholder="e.g. Pizza, fries and a chilled drink">
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">Combo Image (Leave blank to keep current)</label>
            <input type="file" name="image" class="form-control" accept="image/*">

            @if($combo->image)
                <div class="mt-2">
                    <img src="{{ asset('storage/'.$combo->image) }}" 
                         class="rounded-3" width="100"
                         onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=100&q=80';">
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">Badge</label>
            <input type="text" name="badge" class="form-control"
                   value="{{ old('badge', $combo->badge) }}"
                   placeholder="e.g. Most Loved">
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">Combo Items</label>
            <select name="item_ids[]" class="form-select" multiple size="10" required>
                @foreach($foods as $food)
                    <option value="{{ $food->id }}"
                        @selected(in_array($food->id, old('item_ids', $combo->item_ids ?? [])))>
                        {{ $food->name }} - {{ $food->category?->name ?? 'No Category' }} - &#8377;{{ $food->price }}
                    </option>
                @endforeach
            </select>
            <div class="form-text">Select at least two food items for this combo.</div>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">Combo Price</label>
            <input type="number" name="combo_price" class="form-control"
                   min="0" max="99999" step="0.01"
                   value="{{ old('combo_price', $combo->combo_price) }}" required>
            <div class="form-text">Regular price is recalculated from selected items.</div>
        </div>

        <div class="mb-4 form-check">
            <input type="checkbox" class="form-check-input"
                   name="is_active"
                   id="is_active"
                   {{ old('is_active', $combo->is_active) ? 'checked' : '' }}>
            <label class="form-check-label fw-bold" for="is_active">
                Show this combo to customers
            </label>
        </div>

        <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold">
            Update Combo
        </button>
    </form>
</div>
@endsection
