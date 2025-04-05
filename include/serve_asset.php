<?php
// Include the helper functions file
require_once __DIR__ . '/helpers.php';

// Get the asset path from the query string
$asset_path = $_GET['path'] ?? '';

// Validate the asset path
if (empty($asset_path)) {
    header("HTTP/1.1 400 Bad Request");
    die('Asset path is required.');
}

// Define the base assets directory
$base_dir = $_SERVER['DOCUMENT_ROOT'] . '/theme/assets/';

// Construct the full file path
$file_path = realpath($base_dir . $asset_path);

// Validate the file path
if (!$file_path || !file_exists($file_path)) {
    header("HTTP/1.1 404 Not Found");
    die('Asset not found: ' . $base_dir . $asset_path);
}

// Get the file's last modification time for versioning
$file_version = filemtime($file_path);

// Set cache headers
setCacheHeaders($file_path);

// Determine the MIME type based on the file extension
$mime_types = [
    'css' => 'text/css',
    'js' => 'application/javascript',
    'webp' => 'image/webp',
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
];

$file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
$mime_type = $mime_types[$file_extension] ?? 'application/octet-stream';

// Output the file with the correct MIME type
header("Content-Type: $mime_type");
readfile($file_path);
error_log("Resolved file path: $file_path");
?>