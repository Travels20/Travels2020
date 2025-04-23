@extends('admin.Layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{__("Itinerary Management")}}</h1>
            <div class="title-actions">
                @if(empty($recovery))
                    <a href="{{ route("itinerary.admin.itineraryform") }}" class="btn btn-primary">{{__("Add new itinerary")}}</a>
                @endif
            </div>
        </div>

        @include('admin.message')
        
    </div>
@endsection
