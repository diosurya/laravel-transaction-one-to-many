
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-2"><h1>Transactions</h1></div>
            <div class="col-md-6 text-end mb-2">
                <a href="{{ route('transaction.create') }}" class="btn btn-primary">Add Transaction</a>
            </div>
        </div>
        <div class="card bg-white px-5 pt-3 mb-3">
            <div class="card-body">
                <table class="table pt-2" id="transactions-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Date</th>
                            <th>Total Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        
    </div>
    <script>
        $(document).ready(function () {
            $('#transactions-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('transaction.dataTable') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'customer_name', name: 'customer_name' },
                    { data: 'date', name: 'date' },
                    { data: 'total_price', name: 'total_price' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
            });
        });
    </script>
@endsection

