<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('combos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('tagline')->nullable();
            $table->string('badge')->nullable();
            $table->json('item_ids');
            $table->decimal('regular_price', 10, 2)->default(0);
            $table->decimal('combo_price', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $definitions = [
            [
                'title' => 'Pizza Feast Combo',
                'tagline' => 'Pizza, fries and a chilled drink',
                'badge' => 'Most Loved',
                'terms' => ['pizza', 'fries', 'mojito'],
            ],
            [
                'title' => 'Burger Bite Combo',
                'tagline' => 'Burger with crispy sides',
                'badge' => 'Quick Pick',
                'terms' => ['burger', 'fries', 'coca'],
            ],
            [
                'title' => 'Cafe Snack Combo',
                'tagline' => 'Sandwich with a refreshing beverage',
                'badge' => 'Cafe Special',
                'terms' => ['sandwich', 'mojito', 'shake'],
            ],
        ];

        foreach ($definitions as $definition) {
            $items = collect($definition['terms'])
                ->map(fn ($term) => $this->findFood($term))
                ->filter()
                ->unique('id')
                ->values();

            if ($items->count() < 2) {
                continue;
            }

            $regularPrice = $items->sum('price');
            $comboPrice = max(0, $regularPrice - min(60, $items->count() * 20));

            DB::table('combos')->insert([
                'title' => $definition['title'],
                'tagline' => $definition['tagline'],
                'badge' => $definition['badge'],
                'item_ids' => json_encode($items->pluck('id')->values()->all()),
                'regular_price' => $regularPrice,
                'combo_price' => $comboPrice,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('combos');
    }

    private function findFood(string $term)
    {
        return DB::table('foods')
            ->leftJoin('categories', 'foods.category_id', '=', 'categories.id')
            ->select('foods.id', 'foods.price')
            ->where('foods.name', 'like', "%{$term}%")
            ->orWhere('categories.name', 'like', "%{$term}%")
            ->orderBy('foods.price')
            ->first();
    }
};
