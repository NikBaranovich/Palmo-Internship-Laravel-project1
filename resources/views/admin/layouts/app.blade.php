<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }

        .admin-panel {
            display: flex;
        }

        .sidebar {
            width: 250px;
            background-color: #333;
            color: #fff;
            padding: 20px;
            box-sizing: border-box;
            position: fixed;
            height: 100%;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu a {
            text-decoration: none;
            color: #fff;
            display: block;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
        }

        .menu a:hover {
            background-color: #555;
        }

        .content {
            flex: 1;
            padding: 20px;
            margin-left: 250px;
        }

        table {
            color: #423838 !important;
        }

        .admin-panel {
            height: 100vh;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3037;
            padding: 20px;
            border-top-right-radius: 15px;
            border-bottom-right-radius: 15px;
        }
    </style>
    <title>Admin Panel</title>
</head>

<body>
    <div class="admin-panel">
        @include('admin.components.sidebar')
        <main class="content">
            {{-- <h1>Welcome to the Admin Panel</h1> --}}

            <!-- Content -->
            @yield('content')
        </main>
    </div>
</body>

</html>
