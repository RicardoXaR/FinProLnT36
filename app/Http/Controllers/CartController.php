<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Good;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = session()->get('cart', []);
        $total = 0;
        $goods = [];

        foreach ($cartItems as $id => $details) {
            $good = Good::find($id);
            if ($good) {
                $goods[$id] = $good;
                $total += $good->price * $details['quantity'];
            }
        }

        return view('cart.index', compact('cartItems', 'goods', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:goods,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $good = Good::findOrFail($request->id);

        if ($good->quantity <= 0) {
            return redirect()->back()->with('error', 'The item is out of stock, please wait until the item is restocked.');
        }

        if ($request->quantity > $good->quantity) {
            return redirect()->back()->with('error', 'Requested quantity exceeds available stock.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$request->id])) {
            $cart[$request->id]['quantity'] += $request->quantity;
        } else {
            $cart[$request->id] = [
                'quantity' => $request->quantity
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Item removed from cart successfully!');
    }

    public function checkout()
    {
        $cartItems = session()->get('cart', []);
        if (empty($cartItems)) {
            return redirect()->route('goods.index')->with('error', 'Your cart is empty!');
        }

        $total = 0;
        $goods = [];

        foreach ($cartItems as $id => $details) {
            $good = Good::find($id);
            if ($good) {
                $goods[$id] = $good;
                $total += $good->price * $details['quantity'];
            }
        }

        return view('cart.checkout', compact('cartItems', 'goods', 'total'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|min:10|max:100',
            'postal_code' => 'required|string|size:5|regex:/^[0-9]+$/',
        ]);

        $cartItems = session()->get('cart', []);
        if (empty($cartItems)) {
            return redirect()->route('goods.index')->with('error', 'Your cart is empty!');
        }

        // Create invoice
        $invoice = Invoice::create([
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
            'user_id' => Auth::id(),
            'shipping_address' => $request->shipping_address,
            'postal_code' => $request->postal_code,
            'total_price' => 0,
        ]);

        $totalPrice = 0;

        // Create invoice items and update stock
        foreach ($cartItems as $id => $details) {
            $good = Good::find($id);
            if ($good && $good->quantity >= $details['quantity']) {
                $subtotal = $good->price * $details['quantity'];
                
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'good_id' => $good->id,
                    'quantity' => $details['quantity'],
                    'subtotal' => $subtotal,
                ]);
                
                // Update good quantity
                $good->quantity -= $details['quantity'];
                $good->save();
                
                $totalPrice += $subtotal;
            }
        }

        // Update total price
        $invoice->total_price = $totalPrice;
        $invoice->save();

        // Clear cart
        session()->forget('cart');

        return redirect()->route('invoices.show', $invoice->id)->with('success', 'Order placed successfully!');
    }
}