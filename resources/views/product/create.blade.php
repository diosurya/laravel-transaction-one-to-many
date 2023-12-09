@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Product</h2>

    <div class="card bg-white px-5 pt-3 mb-3">
        <div class="card-body">
            <form action="{{ route('product.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="product_name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="product_name" name="product_name" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="text" class="form-control" id="price" name="price" required>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
    
</div>
@endsection