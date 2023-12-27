<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Админ-панель</title>
    <!-- Подключаем Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #343a40;
            color: #fff;
        }

        table {
            color: #fff !important;
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

        .content {
            flex: 1;
            padding: 20px;
        }
    </style>
</head>

<body>

    <div class="admin-panel">
        <!-- Sidebar -->
        @include('admin.components.sidebar')

        <!-- Content -->
        <div class="content">
            @yield('content')
        </div>
    </div>

    <!-- Подключаем Bootstrap JS и jQuery (если необходимо) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>
