@extends('admin.layouts.app')

@section('content')
<style>
    .admin-food-image {
        width: 55px;
        height: 55px;
        object-fit: cover;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        display: block;
    }

    .admin-table-card {
        border-radius: 24px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        background: #ffffff;
    }

    .admin-table-card .table {
        margin-bottom: 0;
    }

    .admin-table-card thead th {
        border-bottom: 1px solid #e5e7eb;
        color: #4b5563;
    }

    .admin-table-card tbody tr:hover {
        background: #f8fafc;
    }

    .admin-small-text {
        color: #64748b;
        font-size: 0.92rem;
    }

    .admin-table-card td {
        vertical-align: middle;
    }

    .admin-page-title {
        letter-spacing: -0.03em;
    }

    .admin-page-actions .btn {
        min-height: 44px;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 admin-page-actions">
        <h3 class="fw-bold mb-0 admin-page-title">Manage Menu</h3>
    <a href="{{ route('admin.menu.create') }}" class="btn btn-primary rounded-pill px-4">
        <i class="fas fa-plus me-2"></i> Add Item
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card border-0 shadow-sm p-4 admin-table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="bg-light">
                <tr>
                    <th class="border-0 bg-transparent py-3">Image</th>
                    <th class="border-0 bg-transparent py-3">Name</th>
                    <th class="border-0 bg-transparent py-3">Category</th>
                    <th class="border-0 bg-transparent py-3">Price</th>
                    <th class="border-0 bg-transparent py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bestCombos as $combo)
                <tr>
                    <td>
                        <img src="{{ $combo->image ? Storage::url($combo->image) : ($combo->items->first()?->image ? Storage::url($combo->items->first()->image) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=100&q=80') }}"
                             class="admin-food-image rounded-3"
                             alt="{{ $combo->title }}"
                             onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=100&q=80';">
                    </td>
                    <td class="fw-semibold">{{ $combo->title }}</td>
                    <td class="admin-small-text">Combos</td>
                    <td class="fw-semibold text-primary">₹{{ $combo->combo_price }}</td>
                    <td>
                        <div class="d-flex gap-2 align-items-center">
                            <a href="{{ route('admin.combos.edit', $combo->id) }}" class="btn btn-sm btn-outline-info rounded-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.combos.destroy', $combo->id) }}" method="POST" onsubmit="return confirm('Delete this combo?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-3">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach

                @forelse($foods as $food)
                <tr>
                    <td>
                        <img src="{{ $food->image ? Storage::url($food->image) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=100&q=80' }}"
                             class="admin-food-image rounded-3"
                             alt="{{ $food->name }}"
                             onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=100&q=80';">
                    </td>
                    <td class="fw-semibold">{{ $food->name }}</td>
                    <td class="admin-small-text">{{ $food->category?->name ?? 'No Category' }}</td>
                    <td class="fw-semibold text-primary">₹{{ $food->price }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.menu.edit', $food->id) }}" class="btn btn-sm btn-outline-info rounded-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.menu.destroy', $food->id) }}" method="POST" onsubmit="return confirm('Delete this item?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-3">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">No items found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4 d-flex justify-content-center">
        {{ $foods->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
