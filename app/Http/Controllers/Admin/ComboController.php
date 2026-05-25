<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Combo;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComboController extends Controller
{
    public function edit(Combo $combo)
    {
        $foods = Food::with('category')
            ->orderBy('name')
            ->get();

        return view('admin.combos.edit', compact('combo', 'foods'));
    }

    public function update(Request $request, Combo $combo)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:100',
            'item_ids' => 'required|array|min:2',
            'item_ids.*' => 'exists:foods,id',
            'combo_price' => 'required|numeric|min:0|max:99999',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $items = Food::whereIn('id', $data['item_ids'])->get();
        $regularPrice = $items->sum('price');

        $updateData = [
            'title' => $data['title'],
            'tagline' => $data['tagline'] ?? null,
            'badge' => $data['badge'] ?? null,
            'item_ids' => array_values($data['item_ids']),
            'regular_price' => $regularPrice,
            'combo_price' => $data['combo_price'],
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            if ($combo->image) {
                Storage::disk('public')->delete($combo->image);
            }

            $updateData['image'] = $request->file('image')->store('combos', 'public');
        }

        $combo->update($updateData);

        return redirect()
            ->route('admin.menu.index')
            ->with('success', 'Combo updated successfully');
    }

    public function destroy(Combo $combo)
    {
        if ($combo->image) {
            Storage::disk('public')->delete($combo->image);
        }

        $combo->delete();

        return back()->with('success', 'Combo deleted successfully');
    }
}
