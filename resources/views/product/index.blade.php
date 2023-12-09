@extends('layouts.app')

@section('content')
<script>

    function confirmDeleteProduct(id) {
        $('#confirmDeleteBtn').data('product-id', id);
        $('#confirmDeleteModal').modal('show');
    }
    function showNotification(message, type) {
        var notifHTML = '<div class="alert alert-dismissible alert-' + type + ' mt-3">' +
            message +
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
            '</div>';

        $('.notif').html(notifHTML);
    }
    function formatRupiah(price) {
        return 'Rp ' + price.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

</script>
    <div class="container">
        
        <div class="row">
            <div class="col-md-6 mb-2"><h1>Product List</h1></div>
            <div class="col-md-6 text-end mb-2">
                <a href="{{ route('product.create') }}" class="btn btn-primary">Add Product</a>
            </div>
            <div class="col-md-12 notif">
                @if(session('success'))
                    <div class="alert alert-dismissible alert-success mt-3">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
        </div>
        <div class="card bg-white px-5 pt-3 mb-3">
            <div class="card-body">
                <table id="products-table" class="table pt-2">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>


<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="confirmDeleteModalLabel">Modal title</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            Are you sure you want to delete this product?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
        </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $('#products-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('product.dataTable') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'product_name', name: 'product_name' },
                { 
                    data: 'price', 
                    name: 'price',
                    render: function (data, type, full, meta) {
                        return formatRupiah(data);
                    }
                },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
        });

        $('#confirmDeleteBtn').on('click', function() {
            var id = $(this).data('product-id');
            $.ajax({
                url: '/product/' + id,
                type: 'DELETE',
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function (response) {
                    $('#products-table').DataTable().ajax.reload();
                    $('#confirmDeleteModal').modal('hide');
                    showNotification(response.success, 'success');
                },
                error: function (error) {
                    $('#confirmDeleteModal').modal('hide');
                    showNotification(error.responseJSON.message, 'danger');
                }
            });
        });
    });
</script>


@endsection
