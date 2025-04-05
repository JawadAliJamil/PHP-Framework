<?php
// Include the bootstrap file
require_once __DIR__ . '/include/bootstrap.php';

// Match the request URI with the routes
list($file, $title, $params) = matchRoute($requestURI, $routes);

// Check if the file exists before including
if ($file && file_exists($file)) {
    // Include the matched file
    require $file;
} else {
    // Handle 404 error
    $pageTitle = '404 Not Found';
    require 'views/pages/404.php';
}
?>