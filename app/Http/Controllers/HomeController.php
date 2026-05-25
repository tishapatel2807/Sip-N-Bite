<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Combo;
use App\Models\Food;

class HomeController extends Controller
{
    public function index(Request $request)
{
    $categories = Category::orderBy('name', 'asc')->get()->unique('name');
    $query = Food::with('category');

    if ($request->has('category') && $request->category != 'all') {
        $query->whereHas('category', function($q) use ($request) {
            $q->where('name', $request->category);
        });
    }

    // 👇 SHOW ONLY 4 ITEMS ON HOME
    $foods = $query->take(4)->get();

    return view('home', compact('categories', 'foods'));
}

    public function menu(Request $request)
{
    $categories = Category::orderBy('name', 'asc')->get()->unique('name');

    $query = Food::with('category')
        ->withCount('reviews')
        ->withAvg('reviews', 'rating');

    if ($request->has('category') && $request->category != 'all') {
        $query->whereHas('category', function($q) use ($request) {
            $q->where('name', $request->category);
        });
    }

    // 👇 FULL MENU WITH PAGINATION
    $foods = $query->paginate(8);

    $customizableCategories = Category::with('addonGroups.addons')
        ->where('is_customizable', true)
        ->get();

    $bestCombos = $this->bestCombos();

    return view('menu', compact('categories', 'foods', 'customizableCategories', 'bestCombos'));
}

    public function foodDetail(Request $request, $id)
{
    $food = Food::with(['category', 'reviews.user'])->findOrFail($id);

    $isCombo = $request->boolean('combo');
    $combo = null;
    $comboData = null;

    // If viewing as a combo, find the combo that contains this food item
    if ($isCombo) {
        $combo = Combo::where('is_active', true)
            ->get()
            ->first(function ($c) use ($id) {
                return in_array($id, $c->item_ids ?? []);
            });

        // If combo found, we'll display combo details instead
        if ($combo) {
            // Get the combo data with all items
            $comboData = [
                'title' => $combo->title,
                'tagline' => $combo->tagline,
                'badge' => $combo->badge,
                'items' => $combo->items,
                'regular_price' => $combo->regular_price ?: $combo->items->sum('price'),
                'combo_price' => $combo->combo_price,
                'saving' => ($combo->regular_price ?: $combo->items->sum('price')) - $combo->combo_price,
                'image' => $combo->image ?: ($combo->items->first()?->image ?? null),
            ];
        }
    }

    $groups = [];
    if (! $isCombo && $food->category && ($food->is_customizable || $food->category->is_customizable)) {
        $groups = $food->category->addonGroups()
            ->with(['addons' => function ($query) use ($food) {
                $query->where('category_id', $food->category_id);
            }])
            ->get()
            ->filter(function ($group) {
                return $group->addons->isNotEmpty();
            });
    }

    return view('food_detail', compact('food', 'groups', 'isCombo', 'combo', 'comboData'));
}

    public function submitReview(Request $request, $id)
{
    $food = Food::findOrFail($id);

    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ]);

    $food->reviews()->updateOrCreate(
        ['user_id' => auth()->id()],
        ['rating' => $request->rating, 'comment' => $request->comment]
    );

    return back()->with('success', 'Your review has been saved successfully.');
}

private function bestCombos()
{
    return Combo::where('is_active', true)
        ->latest()
        ->get()
        ->map(function ($combo) {
            $items = $combo->items;

            if ($items->count() < 2) {
                return null;
            }

            $regularPrice = $combo->regular_price ?: $items->sum('price');
            $comboPrice = $combo->combo_price;

            return [
                'title' => $combo->title,
                'tagline' => $combo->tagline,
                'badge' => $combo->badge,
                'items' => $items,
                'regular_price' => $regularPrice,
                'combo_price' => $comboPrice,
                'saving' => $regularPrice - $comboPrice,
                'image' => $combo->image ?: $items->first()->image,
                'first_item_id' => $items->first()->id,
            ];
        })
        ->filter()
        ->values();
}
}
