<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartAddon;
use App\Models\Food;
use App\Models\Combo;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with(['food', 'addons'])->where('user_id', Auth::id())->get();
        return view('cart.index', compact('cartItems'));
    }

    public function add(Request $request)
    {
        // Check if this is a combo or food item
        if ($request->has('combo_id') && $request->has('is_combo')) {
            $request->validate([
                'combo_id' => 'required|exists:combos,id',
                'quantity' => 'required|integer|min:1',
                'is_combo' => 'required|boolean',
            ]);

            $combo = Combo::findOrFail($request->combo_id);
            $items = $combo->items;

            // Add each item in the combo to the cart
            foreach ($items as $item) {
                Cart::create([
                    'user_id' => Auth::id(),
                    'food_id' => $item->id,
                    'quantity' => $request->quantity,
                ]);
            }

            return redirect()->route('cart.index')->with('success', 'Combo added to cart');
        } else {
            // Regular food item
            $request->validate([
                'food_id' => 'required|exists:foods,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $cart = Cart::create([
                'user_id' => Auth::id(),
                'food_id' => $request->food_id,
                'quantity' => $request->quantity,
            ]);

            if ($request->has('addons')) {
                foreach ($request->addons as $addonId) {
                    CartAddon::create([
                        'cart_id' => $cart->id,
                        'addon_id' => $addonId,
                    ]);
                }
            }

            return redirect()->route('cart.index')->with('success', 'Item added to cart');
        }
    }

    public function remove($id)
    {
        Cart::where('user_id', Auth::id())->where('id', $id)->delete();
        return back()->with('success', 'Item removed');
    }
}

