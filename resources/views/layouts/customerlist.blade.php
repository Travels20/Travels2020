
    <div class="container mt-5">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Customer List</h2>
            
            <div class="d-flex gap-2">
                <a href="#" id="exportExcel">
                    <button class="btn btn-success fw-bold">
                        <i class="fas fa-file-excel"></i> Export
                    </button>
                </a>

                <a href="{{ route('customers.customerform') }}">
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

  