<!DOCTYPE html>
<html>
<head>
    <title>Create Itinerary</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container">
        <h2>Create a Travel Itinerary</h2>

        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('itinerary.store') }}">
            @csrf
            <label>Title:</label><br>
            <input type="text" name="title" required><br><br>

            <label>Description:</label><br>
            <textarea name="description" required></textarea><br><br>

            <label>Travel Date:</label><br>
            <input type="date" name="travel_date" required><br><br>

            <button type="submit">Save</button>
        </form>
    </div>
</body>
</html>
