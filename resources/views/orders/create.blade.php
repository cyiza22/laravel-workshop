<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management</title>
    
</head>
<body>
    <!-- resources/views/orders/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create New Order</h1>

    <form action="{{ route('orders.create') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="product_id">Select Product</label>
            <select name="product_id" id="product_id" class="form-control">
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} - ${{ $product->price }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Place Order & Notify Me</button>
    </form>
</div>
@endsection

</body>