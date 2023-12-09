@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-2"> <h2>List Products API</h1></div>
            <div class="col-md-6 text-end mb-2">
                <button class="btn btn-primary" id="syncBtn"><i class="fas fa-sync"></i> Import</button>
            </div>
            <div class="col-md-12">
                <div id="loadingMessage" class="alert alert-info mt-3" style="display: none;">
                    Syncing, please wait...
                </div>
                <div id="successMessage" class="alert alert-dismissible alert-success mt-3" style="display: none;">
                    Sync completed successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
        
                <div id="errorMessage" class="alert alert-dismissible alert-danger mt-3" style="display: none;">
                    Sync failed. Please check the console for details.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <div class="card bg-white px-5 pt-3 mb-3">
            <div class="card-body">
                <table id="productsTable" class="table pt-2">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#productsTable').DataTable({
                ajax: "{{ route('sync.getData') }}",
                columns: [
                    { data: 'id' },
                    { data: 'title', name: 'name_product' },
                    { data: 'price' },
                ]
            });

            $('#syncBtn').click(function () {
                $('#loadingMessage').show();
                $('#successMessage').hide();
                $('#errorMessage').hide();

                $.ajax({
                    url: "{{ route('sync.sync') }}",
                    type: 'GET',
                    success: function () {
                        $('#successMessage').show();
                        dataTable.ajax.reload();
                        $('#loadingMessage').hide();
                    },
                    error: function (xhr, status, error) {
                        $('#errorMessage').show();
                        console.error(xhr.responseText);
                        $('#loadingMessage').hide();
                    },
                    complete: function () {
                        $('#loadingMessage').hide();
                    }
                });
                $('#loadingMessage').hide();
            });
        });
    </script>
@endsection
