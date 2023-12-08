@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Create Transaction</h2>
        <form action="{{ route('transaction.store') }}" method="post" id="transaction-form">
            @csrf

            <div class="form-group">
                <label for="customer_name">Customer Name</label>
                <input type="text" name="customer_name" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="date">Date</label>
                <input type="date" name="date" class="form-control" required>
            </div>

            <div class="form-group">
                <table class="table" id="products-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="product-row">
                            <td>
                                <select name="products[][product_id]" class="form-control product-select" required>
                                    <option value="">Select Product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->product_name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="products[][quantity]" class="form-control product-quantity" required>
                            </td>
                            <td>
                                <input type="text" name="products[][total_price]" class="form-control product-total-price" readonly>
                            </td>
                            <td>
                                <button type="button" class="btn btn-success add-product">Add Product</button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"></td>
                            <td>Total: <span id="total-amount">0</span></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>


    <script>
        $(document).ready(function () {
            $('#products-table').on('click', '.add-product', function () {
                const productRow = $(this).closest('tr').clone();
                productRow.find('.product-select, .product-quantity, .product-total-price').val('');
                $('#products-table tbody').append(productRow);
                
                $('#products-table tbody .add-product').prop('disabled', false);
                
                $('#products-table tbody .add-product').last().prop('disabled', true);
            });
    
            $('#products-table tbody').on('input', '.product-quantity', function () {
                const row = $(this).closest('tr');
                const quantity = $(this).val();
                const productPrice = row.find('.product-select option:selected').data('price');
    
                if (quantity && productPrice) {
                    const totalPrice = quantity * productPrice;
                    row.find('.product-total-price').val(totalPrice.toFixed(2));
                } else {
                    row.find('.product-total-price').val('');
                }
    
                updateTotalAmount();
            });
    
            function updateTotalAmount() {
                let totalAmount = 0;
                $('#products-table tbody .product-total-price').each(function () {
                    const value = parseFloat($(this).val());
                    if (!isNaN(value)) {
                        totalAmount += value;
                    }
                });
                $('#total-amount').text(totalAmount.toFixed(2));
            }
    
            $('#transaction-form').submit(function () {
                $('#products-table tbody .add-product').prop('disabled', false);
    
                const productsData = [];
                $('#products-table tbody tr').each(function () {
                    const productId = $(this).find('.product-select').val();
                    const quantity = $(this).find('.product-quantity').val();
                    const totalPrice = $(this).find('.product-total-price').val();
    
                    if (productId && quantity && totalPrice) {
                        productsData.push({
                            product_id: productId,
                            quantity: quantity,
                            total_price: totalPrice,
                        });
                    }
                });
    
                $('<input>').attr({
                    type: 'hidden',
                    name: 'products_data',
                    value: JSON.stringify(productsData),
                }).appendTo('#transaction-form');
    
                return true;
            });
        });
    </script>
@endsection
