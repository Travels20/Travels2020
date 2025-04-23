
@extends('layouts.itineraryapp')
    <!-- Invoice Listing -->
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Invoice List</h2>
            <a href="{{ route('invoice.invoiceForm') }}">
                <button class="btn btn-primary fw-bold">Add New</button>
            </a>
        </div>

        <div class="table-responsive">
            <table id="invoiceTable" class="table table-bordered table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th scope="col">S.No</th>
                        <th scope="col">Username</th>
                        <th scope="col">Tour Name</th>
                        <th scope="col">Check-in Date</th>
                        <th scope="col">Check-out Date</th>
                        <th scope="col">Adults</th>
                        <th scope="col">Children</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="invoiceTableBody">
                </tbody>
            </table>
        </div>
    </div>

   