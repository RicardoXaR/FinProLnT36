<!-- resources/views/goods/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Products Catalog</h1>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    
    <div class="row">
        @foreach($goods as $good)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ asset('storage/' . $good->photo_path) }}" class="card-img-top" alt="{{ $good->name }}">
                    <div class="card-body">
                        <span class="badge bg-primary mb-2">{{ $good->category->name }}</span>
                        <h5 class="card-title">{{ $good->name }}</h5>
                        <p class="card-text">Rp. {{ number_format($good->price, 0, ',', '.') }}</p>
                        
                        @if($good->quantity > 0)
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $good->id }}">
                                <div class="input-group mb-3">
                                    <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $good->quantity }}">
                                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                                </div>
                                <small class="text-muted">{{ $good->quantity }} items available</small>
                            </form>
                        @else
                            <p class="text-danger">The item is out of stock, please wait until the item is restocked.</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>