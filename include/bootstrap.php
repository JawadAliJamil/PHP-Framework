<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load the application config
$config = include __DIR__ . '/config.php';
if (!$config || !is_array($config)) {
    die('Failed to load config.');
}
// Define global variables
$applicationName = $config['application_name'] ?? 'Web Application';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get the request URI, cleaning it up
$requestURI = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Dynamically determine the base path
$baseDir = basename(__DIR__);

// Check if the request URI starts with the base directory
if (strpos($requestURI, $baseDir) === 0) {
    $requestURI = substr($requestURI, strlen($baseDir) + 1);
}

// Define BASE_URL and ASSETS_URL
define('BASE_URL', htmlspecialchars($config['base_url']));
define('ASSETS_URL', BASE_URL . 'theme/assets/');
define('BASE_PATH', __DIR__ . '/');

function getDomain(): string {
    $host = parse_url(BASE_URL, PHP_URL_HOST);
    return preg_replace('/^www\./', '', $host);
}

// Get the list of supported languages from the config
$supportedLanguages = array_keys($config['languages']);
define('DEFAULT_LANGUAGE', $supportedLanguages[0]);

// Parse the URL to extract language and request path
$urlParts = explode('/', $requestURI);

// Detect the user's language preference (default to the first language in the config)
$lang = $supportedLanguages[0]; // Default to the first language
if (in_array($urlParts[0], $supportedLanguages)) {
    $lang = $urlParts[0]; // Set language from the URL
    array_shift($urlParts); // Remove the language part from the URL
}

// Redirect /en/xxx to /xxx if default language is English
if ($lang === 'en' && strpos($_SERVER['REQUEST_URI'], '/en/') === 0) {
    $newUrl = str_replace('/en/', '/', $_SERVER['REQUEST_URI']);
    header("Location: $newUrl", true, 301);
    exit;
}

// Store language in session for persistence
$_SESSION['lang'] = $lang;

// Rebuild the request URI without the language part
$requestURI = implode('/', $urlParts);

// Define language-specific base URL
define('LANG_BASE_URL', rtrim(BASE_URL, '/') . '/' . $lang . '/');

// Validate the language (ensure the language file exists)
$langFile = __DIR__ . '/../lang/' . $lang . '.php';
if (!file_exists($langFile)) {
    $lang = $supportedLanguages[0]; // Fallback to the first language if the language file doesn't exist
    $langFile = __DIR__ . '/../lang/' . $supportedLanguages[0] . '.php';
    $_SESSION['lang'] = $lang; // Update session with fallback language
}

// Load the language file
$translations = include $langFile;

// Load routes from the /include/routes.php file
$routes = include __DIR__ . '/routes.php';
if (!$routes || !is_array($routes)) {
    die('Failed to load routes.');
}

// Include the helper functions file
require_once __DIR__ . '/helpers.php';


// Create a function for safer translation retrieval
function t($key) {
    global $translations; 
    return $translations[$key] ?? $key; // Return translation if exists, else return key name
}

// Assign translations to a shorter variable
$l = $translations;

// Get page metadata based on the current URI and routes
$metadata = getPageMetadata($requestURI, $routes, $config, $lang);

// Check if the current page is a 404 page
$is404 = false;
list($file, $title, $params) = matchRoute($requestURI, $routes);
if (!$file || !file_exists($file)) {
    $is404 = true;
}

// Assign metadata to variables for use in header.php
$pageTitle = $metadata['pageTitle'];
$pageDescription = $metadata['pageDescription'];
$pageKeywords = $metadata['pageKeywords'];
$ogImage = $metadata['ogImage'];
$ogType = $metadata['ogType'];
// For canonical URL - skip language code for default language
$canonicalUrl = ($lang === DEFAULT_LANGUAGE) 
    ? BASE_URL . $requestURI
    : BASE_URL . $lang . '/' . $requestURI;
$schemaType = $metadata['schema_type'];

// Generate schema markup for the current page (skip for 404 pages)
$schema_json = null;
if (!$is404) {
    $schema = generateSchema([
        'title' => $pageTitle,
        'description' => $pageDescription,
        'og_image' => $ogImage,
        'schema_type' => $schemaType,
        'url' => $requestURI
    ], $config, $l, $lang);

    // Encode the schema data as JSON-LD
    $schema_json = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
}

// Render the header
$headerContent = renderHeader($pageTitle, $applicationName, $config, $pageDescription, $pageKeywords, $ogImage, $ogType, $canonicalUrl, $schema_json);
?>