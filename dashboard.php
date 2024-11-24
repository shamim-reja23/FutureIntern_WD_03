<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
         <!-- Header -->
        <header class="d-flex justify-content-between align-items-center mb-4">
            <h1>Student Dashboard</h1>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </header>
        <!-- Welcome Message -->
        <div class="alert alert-info">
            Welcome, <strong><?php echo $_SESSION['username']; ?></strong>!
        </div>
        <!-- Student Info Card -->
        <div id="student-info" class="card p-3">
            <!-- Data will be inserted here dynamically -->
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Fetch student data
            $.get('./backend/fetch_students.php', function (data) {
                // console.log('Response from backend:', data);  // Debugging: Check the response

                const student = JSON.parse(data);
                // console.log(student);

                if (student.error) {
                    $('#student-info').html(`
                    <div class="alert alert-danger">${student.error}</div>
                    `);
                } else {
                    $('#student-info').html(`
                        <h5>Student Details</h5>
                        <p><strong>ID:</strong> ${student.data.id}</p>
                        <p><strong>Name:</strong> ${student.data.name}</p>
                        <p><strong>Email:</strong> ${student.data.email}</p>
                        <p><strong>Photo:</strong> 
                            <a href="${student.data.photo}" target="_blank" class="btn btn-link">View Photo</a>
                        </p>
                    `);
                }
            }).fail(function (xhr, status, error) {
                console.error('Error fetching data:', error);
                $('#student-info').html('<div class="alert alert-danger">Error fetching data. Please try again.</div>');
            });
        });
    </script>
</body>
</html>

