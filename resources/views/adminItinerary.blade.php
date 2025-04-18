<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Travels2020</title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            margin: 0;
        }
        .wrapper {
            display: flex;
            flex-grow: 1;
        }
        .sidebar {
            width: 250px;
            background: #343a40;
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 15px;
            z-index: 1000;
            transition: transform 0.3s ease-in-out;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            cursor: pointer;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            flex-grow: 1;
            width: 100%;
            transition: margin-left 0.3s ease-in-out;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        .mobile-toggle, .close-icon {
            display: none;
        }
        .hidden {
            display: none;
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .content {
                margin-left: 0;
            }
            .mobile-toggle {
                display: block;
                position: fixed;
                top: 15px;
                left: 15px;
                font-size: 24px;
                color: black;
                z-index: 1100;
                cursor: pointer;
            }
            .close-icon {
                display: block;
                position: fixed;
                top: 15px;
                left: 225px;
                font-size: 24px;
                color: white;
                z-index: 1100;
                cursor: pointer;
                display: none;
            }
            .overlay.active {
                display: block;
            }
        }
    </style>
</head>

<body>
    <!-- Hamburger Icon (menu) -->
    <i class="fas fa-bars mobile-toggle" id="menuIcon" onclick="openSidebar()"></i>

    <!-- Close Icon -->
    <i class="fas fa-times close-icon" id="closeIcon" onclick="closeSidebar()"></i>

    <!-- Dark overlay -->
    <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

    <div class="wrapper">
        <div class="sidebar" id="sidebar">
            <img src="{{ asset('images/travels20logo.png') }}" alt="Logo" style="width: 90%; height: auto; margin-bottom: 20px;">
            <a onclick="showSection('itinerary')">Itineraries</a>
            <a onclick="showSection('customer')">Customer List</a>
            <a onclick="showSection('invoice')">Invoice</a>
        </div>

        <div class="content">
            <div id="itinerary" class="section">
                @include('layouts.listitinerary')
            </div>
            <div id="customer" class="section hidden">
                @include('layouts.customerlist')
            </div>
            <div id="invoice" class="section hidden">
                @include('layouts.invoicelist')
            </div>
        </div>
    </div>

    <!-- JS for sidebar toggle -->
    <script>
        function openSidebar() {
            document.getElementById("sidebar").classList.add("open");
            document.getElementById("overlay").classList.add("active");
            document.getElementById("menuIcon").style.display = "none";
            document.getElementById("closeIcon").style.display = "block";
        }

        function closeSidebar() {
            document.getElementById("sidebar").classList.remove("open");
            document.getElementById("overlay").classList.remove("active");
            document.getElementById("menuIcon").style.display = "block";
            document.getElementById("closeIcon").style.display = "none";
        }

        function showSection(section) {
            document.querySelectorAll(".section").forEach(el => el.classList.add("hidden"));
            document.getElementById(section).classList.remove("hidden");

            if (window.innerWidth <= 768) {
                closeSidebar();
            }
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
