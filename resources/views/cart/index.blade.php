<!-- resources/views/cart/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Shopping Cart</h1>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if(count($cartItems) > 0)
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $id => $details)
                        @if(isset($goods[$id]))
                            <tr>
                                <td>{{ $goods[$id]->name }}</td>
                                <td>Rp. {{ number_format($goods[$id]->price, 0, ',', '.') }}</td>
                                <td>{{ $details['quantity'] }}</td>
                                <td>Rp. {{ number_format($goods[$id]->price * $details['quantity'], 0, ',', '.') }}</td>
                                <td>
                                    <form action="{{ route('cart.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                        <td>Rp. {{ number_format($total, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="mt-3">
            <a href="{{ route('goods.index') }}" class="btn btn-secondary">Continue Shopping</a>
            <a href="{{ route('cart.checkout') }}" class="btn btn-success">Proceed to Checkout</a>
        </div>
    @else
        <div class="alert alert-info">
            Your cart is empty! <a href="{{ route('goods.index') }}">Continue shopping</a>
        </div>
    @endif
</div>
@endsection