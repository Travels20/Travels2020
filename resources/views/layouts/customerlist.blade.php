<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Customer List</title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>

<body class="d-flex flex-column min-vh-100">

    <div class="container mt-5">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Customer List</h2>
            
            <div class="d-flex gap-2">
                <a href="#" id="exportExcel">
                    <button class="btn btn-success fw-bold">
                        <i class="fas fa-file-excel"></i> Export
                    </button>
                </a>

                <a href="{{ route('customers.create') }}">
                    <button class="btn btn-primary fw-bold">
                        Add New
                    </button>
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table id="customerlistTable" class="table table-bordered table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th scope="col">S.No</th>
                        <th scope="col">TripID</th>
                        <th scope="col">Username</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Email</th>
                        <th scope="col">Tour Name</th>
                        <th colspan="2">Traveling Dates</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="customerTableBody">
                    {{-- Backend rendering example:
                    @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $customer->trip_id }}</td>
                            <td>{{ $customer->username }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->tour_name }}</td>
                            <td colspan="2">{{ $customer->travel_start }} - {{ $customer->travel_end }}</td>
                            <td>
                                <!-- Action buttons here -->
                            </td>
                        </tr>
                    @endforeach --}}
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery and DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Your custom JS -->
    <script src="{{ asset('customerDetails/js/listcustomer.js') }}"></script>

</body>
</html>
