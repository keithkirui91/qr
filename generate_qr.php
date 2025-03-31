<?php
session_start();
include("conn.php");

// Check DB connection
if (!$mysqli) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Ensure form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data
    $no = mysqli_real_escape_string($mysqli, $_POST['no'] ?? '');
    $nif = mysqli_real_escape_string($mysqli, $_POST['nif'] ?? '');
    $importer = mysqli_real_escape_string($mysqli, $_POST['importer'] ?? '');
    $exporter = mysqli_real_escape_string($mysqli, $_POST['exporter'] ?? '');
    $truck_no = mysqli_real_escape_string($mysqli, $_POST['truck_no'] ?? '');
    $volume = mysqli_real_escape_string($mysqli, $_POST['volume'] ?? '');
    $product = mysqli_real_escape_string($mysqli, $_POST['product'] ?? '');
    $destination = mysqli_real_escape_string($mysqli, $_POST['destination'] ?? '');
    $date = mysqli_real_escape_string($mysqli, $_POST['date'] ?? '');

    // Check for empty required fields
    if (empty($no) || empty($nif) || empty($importer) || empty($exporter) || empty($truck_no) || empty($volume) || empty($product) || empty($destination) || empty($date)) {
        die("Error: All fields are required.");
    }

    // Generate QR code data
    $qr_data = "NO: $no, NIF: $nif, Importer: $importer, Exporter: $exporter, Truck No: $truck_no, Volume: $volume, Product: $product, Destination: $destination, Date: $date";
    $api_url = 'https://api.qrserver.com/v1/create-qr-code/';
    $params = ['data' => $qr_data, 'size' => '150x150'];
    $url = $api_url . '?' . http_build_query($params);

    // Create directory if not exists
    $qr_code_dir = 'qr_codes/';
    if (!is_dir($qr_code_dir)) {
        mkdir($qr_code_dir, 0777, true);
    }

    // Save QR code image
    $qr_code_file = $qr_code_dir . 'qr_' . time() . '.png';
    $qr_image = file_get_contents($url);

    if (!$qr_image) {
        die("Failed to generate QR code.");
    }

    file_put_contents($qr_code_file, $qr_image);

    // Insert into database
    $query = "INSERT INTO shipments (no, nif, importer, exporter, truck_no, volume, product, destination, date, qr_code_file) 
              VALUES ('$no', '$nif', '$importer', '$exporter', '$truck_no', '$volume', '$product', '$destination', '$date', '$qr_code_file')";

    if (mysqli_query($mysqli, $query)) {
        header("Location: dispqr.html?qr_code=" . urlencode($qr_code_file));
        exit();
    } else {
        echo "Database error: " . mysqli_error($mysqli);
    }
}
?>
