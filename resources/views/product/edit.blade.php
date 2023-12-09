@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Product</h2>
        <div class="card bg-white px-5 pt-3 mb-3">
            <div class="card-body">
                <form action="{{ route('product.update', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT')
        
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" value="{{ $product->product_name }}" required>
                    </div>
        
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="price" name="price" value="{{ $product->price }}" required>
                    </div>
        
                    <button type="submit" class="btn btn-primary">Update Product</button>
                </form>
            </div>
        </div>
        
    </div>
@endsection
