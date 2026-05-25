@extends('layouts.app')

@push('css')
<style>
    /* Hero Section */
    .hero-container {
    height: 75vh;
    min-height: 420px;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    text-align: center;
    color: white;
}

.hero-bg {
    position: absolute;
    inset: 0;
    background-image: url("https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=1920&auto=format&fit=crop");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    z-index: -1;
}

.hero-bg::after {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
}

/* Main Title */
.hero-logo {
    font-family: 'Poppins', sans-serif;
    font-size: 4rem;
    font-weight: 800;

    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;

    background: linear-gradient(90deg, #f8f5f5, #ede8e7);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;

    text-shadow: 0 5px 25px rgba(0,0,0,0.4);
}


.hero-logo, .hero-tagline {
    animation: fadeUp 1s ease forwards;
}

@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
/* Tagline */
.hero-tagline {
    font-family: 'Poppins', sans-serif;
    font-size: 1.4rem;
    font-weight: 400;
    margin-top: 10px;
    letter-spacing: 0.5px;
    opacity: 0.9;
}
    /* Quick Options */
    .option-card {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px;
    border-radius: 16px;
    background: #fff;
    border: 1px solid #eee;
    text-decoration: none;
    color: #333;
    transition: 0.3s ease;
    height: 100%;
}

.option-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.1);
}

.option-img {
    width: 70px;
    height: 70px;
    object-fit: contain;
}

.option-text h5 {
    margin: 0;
    font-weight: 600;
    font-size: 1.1rem;
}

.option-text p {
    margin: 0;
    font-size: 0.9rem;
    color: #777;
}
    /* Category Filter */
    .category-filter {
        display: flex;
        gap: 1.5rem;
        overflow-x: auto;
        padding-bottom: 1rem;
        margin-bottom: 2rem;
        border-bottom: 1px solid var(--zomato-border);
    }

    .cat-item {
        color: var(--zomato-grey);
        font-weight: 500;
        padding: 0.5rem 0;
        text-decoration: none;
        white-space: nowrap;
        position: relative;
    }

    .cat-item.active {
        color: var(--zomato-red);
    }

    .cat-item.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        height: 2px;
        background: var(--zomato-red);
    }

    /* Food Card */
    .food-card {
        border-radius: 1rem;
        overflow: hidden;
        transition: box-shadow 0.2s;
        cursor: pointer;
        padding: 0.8rem;
        border: 1px solid transparent;
        height: 100%;
    }

    .food-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}


    .food-img-wrapper {
    height: 250px;
    border-radius: 12px;
    overflow: hidden;
}

    .food-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

    .food-info {
        padding-top: 0.8rem;
    }

    .rating-badge {
        background: var(--zomato-rating-green);
        color: white;
        padding: 0.2rem 0.4rem;
        border-radius: 0.4rem;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .price-text {
        color: var(--zomato-grey);
        font-size: 0.9rem;
    }

    .combo-section {
        margin: 0 0 2.75rem;
        padding-top: 1rem;
    }

    .combo-heading {
        max-width: 680px;
    }

    .combo-eyebrow {
        color: var(--zomato-red);
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .combo-card {
        height: 100%;
        border: 1px solid #eeeeee;
        border-radius: 1rem;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
    }

    .combo-card:hover {
        transform: translateY(-6px);
        border-color: rgba(239,79,95,0.28);
        box-shadow: 0 18px 42px rgba(0,0,0,0.1);
    }

    .combo-media {
        position: relative;
        height: 250px;
        overflow: hidden;
        background: #f8f8f8;
        display: grid;
        grid-template-columns: 1.25fr 0.75fr;
        grid-template-rows: 1fr 1fr;
        gap: 4px;
    }

    .combo-media.single-image {
        display: block;
    }

    .combo-image {
        height: 100%;
        width: 100%;
        object-fit: cover;
        object-position: center;
        background: #fff;
        display: block;
    }

    .combo-media.single-image .combo-image {
        height: 250px;
    }

    .combo-image:first-child {
        grid-row: span 2;
    }

    .combo-image:only-child {
        grid-column: span 2;
        grid-row: span 2;
    }

    .combo-badge {
        position: absolute;
        top: 14px;
        left: 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fff;
        color: var(--zomato-red);
        border-radius: 999px;
        padding: 6px 11px;
        font-size: 0.75rem;
        font-weight: 800;
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }

    .combo-count {
        position: absolute;
        right: 14px;
        bottom: 14px;
        background: rgba(0,0,0,0.68);
        color: #fff;
        border-radius: 999px;
        padding: 6px 11px;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .combo-items {
        min-height: 48px;
        color: #666;
        font-size: 0.9rem;
        line-height: 1.45;
    }

    .combo-detail-list {
        display: grid;
        gap: 8px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .combo-detail-list li {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #555;
        font-size: 0.88rem;
    }

    .combo-detail-list i {
        color: var(--zomato-red);
        width: 16px;
    }

    .combo-old-price {
        color: #999;
        text-decoration: line-through;
        font-size: 0.9rem;
    }

    .combo-save {
        color: #16a34a;
        font-size: 0.82rem;
        font-weight: 800;
    }
</style>

@endpush

@section('content')

<div class="container py-5">
    <h2 class="fw-bold mb-4">Our Menu</h2>

    <!-- Category Filter -->
   <div class="category-filter">
    <a href="{{ route('menu', ['category' => 'all']) }}" 
       class="cat-item {{ !request('category') || request('category') == 'all' ? 'active' : '' }}">
        All
    </a>

    @foreach($categories as $cat)
        @if(strtolower($cat->name) !== 'combos')
            <a href="{{ route('menu', ['category' => $cat->name]) }}" 
               class="cat-item {{ request('category') == $cat->name ? 'active' : '' }}">
                {{ $cat->name }}
            </a>
        @endif
    @endforeach

    <a href="{{ route('menu', ['category' => 'combos']) }}"
       class="cat-item {{ request('category') == 'combos' ? 'active' : '' }}">
        Combos
    </a>
</div>

    @if(request('category') === 'combos' && $bestCombos->isNotEmpty())
        <div class="combo-section">
            <div class="combo-heading mb-4">
                <div class="combo-eyebrow mb-2">Combos</div>
                <h3 class="fw-bold mb-2">Best Combos</h3>
                <p class="text-muted mb-0">
                    Complete meal pairings grouped under the Combos category for easy value picks.
                </p>
            </div>

            <div class="row g-4">
                @foreach($bestCombos as $combo)
                    <div class="col-lg-4 col-md-6">
                        <div class="combo-card">
                            <div class="combo-media {{ !empty($combo['image']) ? 'single-image' : 'multi-image' }}">
                                    @if(!empty($combo['image']))
                                    <img src="{{ asset('storage/'.$combo['image']) }}"
                                         class="combo-image"
                                         alt="{{ $combo['title'] }}"
                                         onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80';">
                                @else
                                    @foreach($combo['items']->take(3) as $comboItem)
                                        <img src="{{ $comboItem->image ? asset('storage/'.$comboItem->image) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80' }}"
                                             class="combo-image"
                                             alt="{{ $comboItem->name }}"
                                             onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80';">
                                    @endforeach
                                @endif

                                <span class="combo-badge">
                                    <i class="fas fa-bolt"></i>
                                    {{ $combo['badge'] }}
                                </span>

                                <span class="combo-count">
                                    {{ $combo['items']->count() }} items
                                </span>
                            </div>

                            <div class="p-3">
                                <h5 class="fw-bold mb-1">{{ $combo['title'] }}</h5>
                                <p class="combo-items mb-3">
                                    {{ $combo['items']->pluck('name')->join(' + ') }}
                                </p>

                                <ul class="combo-detail-list mb-3">
                                    <li>
                                        <i class="fas fa-check-circle"></i>
                                        Includes {{ $combo['items']->pluck('category.name')->filter()->unique()->join(', ') ?: 'selected menu items' }}
                                    </li>
                                    <li>
                                        <i class="fas fa-clock"></i>
                                        Ready in about {{ $combo['items']->max('delivery_time') ?? 25 }} minutes
                                    </li>
                                    <li>
                                        <i class="fas fa-tags"></i>
                                        Save &#8377;{{ $combo['saving'] }} compared to regular price
                                    </li>
                                </ul>

                                <div class="d-flex justify-content-between align-items-end">
                                    <div>
                                        <div>
                                            <span class="fw-bold fs-5">&#8377;{{ $combo['combo_price'] }}</span>
                                            <span class="combo-old-price ms-2">&#8377;{{ $combo['regular_price'] }}</span>
                                        </div>
                                        <div class="combo-save">Combo saving: &#8377;{{ $combo['saving'] }}</div>
                                    </div>

                                    <a href="{{ route('food.detail', ['id' => $combo['first_item_id'], 'combo' => true]) }}"
                                       class="btn btn-sm btn-zomato px-3">
                                        View Combo
                                    </a>
                                </div>

                                <div class="small text-muted mt-3">{{ $combo['tagline'] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if(request('category') !== 'combos')
    <!-- Food Grid -->
    <div class="row g-4">
        @forelse($foods as $food)
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="food-card" onclick="window.location.href='{{ route('food.detail', $food->id) }}'">
                <div class="food-img-wrapper">
                    <img src="{{ $food->image ? asset('storage/'.$food->image) : 'https://via.placeholder.com/300' }}"
                         class="food-img" alt="{{ $food->name }}">
                </div>

                <div class="food-info">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h4 class="mb-0 fw-bold fs-6">{{ $food->name }}</h4>
                        <span class="rating-badge">
                            {{ $food->reviews_count ? number_format($food->reviews_avg_rating, 1) : 'New' }}
                            <i class="fas fa-star" style="font-size: 10px;"></i>
                        </span>
                    </div>

                    <p class="text-muted small mb-1">
                        {{ $food->delivery_time ?? 25 }} min •
                        {{ $food->reviews_count ? $food->reviews_count . ' review' . ($food->reviews_count > 1 ? 's' : '') : 'No reviews yet' }}
                    </p>
                    <p class="text-muted small">{{ $food->description }}</p>
                    <p class="fw-bold">₹{{ $food->price }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center">
            <h5>No food items found</h5>
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $foods->links() }}
    </div>
    @endif

</div>

@endsection
