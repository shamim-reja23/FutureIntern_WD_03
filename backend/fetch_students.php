<?php
// Display errors for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../vendor/autoload.php';  // Include PHPSpreadsheet library

use PhpOffice\PhpSpreadsheet\IOFactory;

// Redirect if user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit;
}

// Define the path to the Excel file
$file = '../project-folder/StudentData.xlsx';  // Adjust the relative path based on your folder structure

if (!file_exists($file)) {
    echo json_encode(['error' => 'Excel file not found at: ' . realpath($file)]);
    exit;
}


// Load the Excel file
try {
    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->toArray();
} catch (Exception $e) {
    echo json_encode(['error' => 'Error loading Excel file: ' . $e->getMessage()]);
    exit;
}

// Fetch user data
$username = $_SESSION['username'];  // Username from session
$userData = null;

// Iterate through rows to find matching user
foreach ($data as $row) {
    // Assuming username/email is in the 3rd column (index 2)
    if (isset($row[2]) && $row[2] === $username) {
        $userData = [
            'id' => $row[0] ?? 'N/A',      // ID column
            'name' => $row[1] ?? 'N/A',    // Name column
            'email' => $row[2] ?? 'N/A',   // Email column
            'photo' => $row[3] ?? 'N/A',   // Photo column
        ];
        break;
    }
}

if ($userData) {
    echo json_encode(['status' => 'success', 'data' => $userData]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No data found for the user.']);
}

?>
