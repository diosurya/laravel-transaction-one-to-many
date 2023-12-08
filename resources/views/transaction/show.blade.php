@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Transaction Detail</h2>

        <div class="my-3">
            <div class="row">
                <div class="col-md-6 md-3">
                    <p><strong>Transaction Number:</strong> {{ $transaction->transaction_number }}</p>
                </div>
                <div class="col-md-6 col-md-6 md-3">
                    <p><strong>Customer Name:</strong> {{ $transaction->customer_name }}</p>
                </div>
                <div class="col-md-6 col-md-6 md-3">
                    <p><strong>Date:</strong> {{ $transaction->date }}</p>
                </div>
                <div class="col-md-6 col-md-6 md-3">
                    <p><strong>Total Price:</strong> {{ $transaction->total_price }}</p>
                </div>
                <div class="col-md-6 col-md-6 md-3">
                    <p><strong>Total Quantity:</strong> {{ $transaction->total_quantity }}</p>
                </div>
            </div>
        </div>

        <h4>Products:</h4>
        <table class="table" id="products-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaction->details as $detail)
                    <tr>
                        <td>{{ $detail->product->product_name }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ $detail->total_price }}</td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm edit-product" data-id="{{ $detail->id }}" data-quantity="{{ $detail->quantity }}">Edit</button>
                            <button type="button" class="btn btn-danger btn-sm delete-product" data-id="{{ $detail->id }}">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Edit Product -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProductForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="productId" id="editProductId">
                        <div class="mb-3">
                            <label for="editQuantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="editQuantity" name="quantity" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete Product -->
    <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteProductModalLabel">Delete Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="deleteProductForm">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="deleteProductId" id="deleteProductId">
                        <p>Are you sure you want to delete this product?</p>
                        <button type="submit" class="btn btn-danger">Delete Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#products-table').on('click', '.edit-product', function () {
                const productId = $(this).data('id');
                const quantity = $(this).data('quantity');

                $('#editProductModal #editProductId').val(productId);
                $('#editProductModal #editQuantity').val(quantity);
                $('#editProductModal').modal('show');
            });

            $('#products-table').on('click', '.delete-product', function () {
                const productId = $(this).data('id');
                $('#deleteProductModal #deleteProductId').val(productId);
                $('#deleteProductModal').modal('show');
            });

            $('#editProductForm').submit(function (e) {
                e.preventDefault();
                const productId = $('#editProductId').val();
                const quantity = $('#editQuantity').val();
                $.ajax({
                    url: '/transaction/editproduct/' + productId,
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { 
                        quantity: quantity, 
                    },
                    success: function (response) {
                        console.log(response);
                        $('#editProductModal').modal('hide');
                    },
                    error: function (error) {
                        console.error(error);
                    }
                });
            });

            $('#deleteProductForm').submit(function (e) {
                e.preventDefault();
                const productId = $('#deleteProductId').val();
                $.ajax({
                    url: '/transaction/deleteproduct/' + productId,
                    method: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        $('#deleteProductModal').modal('hide');
                        location.reload();
                    },
                    error: function (error) {
                        console.error(error);
                    }
                });
            });
        });

    </script>
@endsection

