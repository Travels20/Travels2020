@extends('layouts.itineraryapp')
    <!-- Tour Listing -->
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Tour List</h2>
            <a href="{{ route('itinerary.itineraryform') }}">
                <button class="btn btn-primary fw-bold">
                    Add New
                </button>
            </a>
        </div>

        <div class="table-responsive">
            <table id="itinerarylistTable" class="table table-bordered table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th scope="col">S.No</th>
                        <th scope="col">Trip Id</th>
                        <th scope="col">Username</th>
                        <th scope="col">Tour Name</th>
                        <th scope="col">Check-in Date</th>
                        <th scope="col">Check-out Date</th>
                        <th colspan="2">No of Passengers</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="itineraryTableBody">
                  
                </tbody>
            </table>
        </div>
    </div>

   