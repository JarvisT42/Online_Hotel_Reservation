<!DOCTYPE html>
<html>

<head>
    <!-- DataTables CSS -->
    <!-- <link rel="stylesheet" href="dataTables.dataTables.min.css"> -->
    <link rel="stylesheet" href="boots/dataTables.bootstrap5.min.css">

</head>

<body>
    <table id="myTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <tr> <!-- Add sample data for testing -->
                <td>John Doe</td>
                <td>john@example.com</td>
            </tr>
            <tr>
                <td>Jane Smith</td>
                <td>jane@example.com</td>
            </tr>
        </tbody>
    </table>

    <!-- Load jQuery FIRST -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Then load DataTables -->
    <script src="boots/dataTables.min.js"></script>
    <script src="boots/dataTables.bootstrap5.min.js"></script>
    <!-- Initialize DataTable -->
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
</body>

</html>