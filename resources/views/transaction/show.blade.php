@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-1">
                <h2>Transaction Detail</h2>
            </div>
            <div class="col-md-6 text-end mb-1">
                <h5> {{ $transaction->transaction_number }}</h5>
            </div>
        </div>

        <div class="card bg-white px-5 pt-3 mb-3">
            <div class="card-body">
                <div class="my-3">
                    <div class="row">
                        <div class="col-md-6 col-md-6 mb-3">
                            <h6>Customer Name</h6>
                            <h5 class="fw-bold">{{ $transaction->customer_name }}</h5>
                        </div>
                        <div class="col-md-6 col-md-6 mb-3">
                            <h6>Date</h6>
                            <h5 class="fw-bold">{{ $transaction->date }}</h5>
                        </div>
                        <div class="col-md-6 col-md-6 mb-3">
                            <h6>Total Price</h6>
                            <h5 class="fw-bold">{{ $transaction->total_price }}</h5>
                        </div>
                        <div class="col-md-6 col-md-6 mb-3">
                            <h6>Total Quantity</h6>
                            <h5 class="fw-bold">{{ $transaction->total_quantity }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-white px-5 py-4">
            <div class="card-body">
                <h4 class="card-title">Products</h4>
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
                                    <button type="button" class="btn btn-warning btn-sm edit-product" data-id="{{ $detail->id }}" data-quantity="{{ $detail->quantity }}"><i class="fas fa-pencil"></i></button>
                                    <button type="button" class="btn btn-danger btn-sm delete-product" data-id="{{ $detail->id }}"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
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

