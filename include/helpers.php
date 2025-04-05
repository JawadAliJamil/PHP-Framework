<?php
/* <a href="<?= getBaseUrl(); ?>/some-path">Link</a> */
/* <a href="<?= getAssetsUrl(); ?>/some-path">Link</a> */

// Add these new functions at the top of your file:

/**
 * Get current language code
 * @return string Current language code (e.g. 'en', 'ar')
 */
function current_lang() {
    return $_SESSION['lang'] ?? (defined('DEFAULT_LANGUAGE') ? DEFAULT_LANGUAGE : 'en');
}

/**
 * Generate URL with language prefix
 * @param string $path URL path
 * @param string|null $lang Specific language (uses current if null)
 * @return string Full URL with language prefix
 */
function lang_url($path = '', $lang = null) {
    $baseUrl = defined('BASE_URL') ? BASE_URL : (config('base_url') ?? '');
    $lang = $lang ?? current_lang();
    $path = ltrim($path, '/');
    
    // Skip language code for default language
    if ($lang === DEFAULT_LANGUAGE) {
        return rtrim($baseUrl, '/') . '/' . $path;
    }
    
    return rtrim($baseUrl, '/') . '/' . $lang . '/' . $path;
}

/**
 * Get language-switcher HTML as dropdown with full language names
 * @return string HTML dropdown for language selection
 */
function language_switcher() {
    $html = '<form method="post" action="" class="language-switcher form-block">';
    $html .= '<select name="language" onchange="this.form.submit()">';
    
    foreach (config('languages', []) as $code => $language) {
        $selected = (current_lang() == $code) ? ' selected' : '';
        $languageName = is_array($language) ? ($language['name'] ?? $code) : $language;
        
        $html .= sprintf(
            '<option value="%s"%s>%s</option>',
            htmlspecialchars($code),
            $selected,
            htmlspecialchars($languageName)
        );
    }
    
    $html .= '</select>';
    $html .= '</form>';
    
    return $html;
}

/**
 * Get base URL with current language prefix
 * @param array $config Configuration array
 * @return string Base URL with language
 */
function getBaseUrl($config) {
    $base = defined('BASE_URL') ? BASE_URL : ($config['base_url'] ?? '');
    $lang = current_lang();
    
    if ($lang === DEFAULT_LANGUAGE) {
        return rtrim($base, '/') . '/';
    }
    
    return rtrim($base, '/') . '/' . $lang . '/';
}

/**
 * Get assets URL (unchanged as assets typically don't need language prefix)
 */
function getAssetsUrl($config) {
    return htmlspecialchars($config['assets_url'] ?? '');
}

/**
 * Get theme URL (unchanged as theme assets typically don't need language prefix)
 */
function getThemeUrl($config) {
    return htmlspecialchars(($config['base_url'] ?? '') . 'theme/assets/');
}

// Update the existing getLangPrefix() function:
function getLangPrefix() {
    return current_lang() . '/';
}

// Update the isActiveLink() function to properly handle language prefixes:
function isActiveLink($linkPath) {
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $baseUrl = defined('BASE_URL') ? BASE_URL : (config('base_url') ?? '');
    $baseUrl = rtrim($baseUrl, '/');
    
    // Remove base URL from both paths
    $currentPath = str_replace($baseUrl, '', $currentPath);
    $linkPath = str_replace($baseUrl, '', $linkPath);
    
    // Rest of the function remains the same...
    $currentPath = preg_replace('/^\/[a-z]{2}(\/|$)/', '/', $currentPath);
    $linkPath = preg_replace('/^\/[a-z]{2}(\/|$)/', '/', $linkPath);
    
    $currentPath = trim($currentPath, '/');
    $linkPath = trim($linkPath, '/');
    
    return $currentPath === $linkPath;
}

// Helper function to render the header with the global page title
function getHeaderContent($pageTitle, $applicationName, $config, $pageDescription, $pageKeywords, $ogImage, $ogType, $canonicalUrl, $schema_json) {
    // Include the header file
    include __DIR__ . '/../views/partials/header.php';
}

function renderHeader($pageTitle, $applicationName, $config, $pageDescription, $pageKeywords, $ogImage, $ogType, $canonicalUrl, $schema_json) {
    // Start output buffering
    ob_start();

    // Call getHeaderContent with the required arguments
    getHeaderContent($pageTitle, $applicationName, $config, $pageDescription, $pageKeywords, $ogImage, $ogType, $canonicalUrl, $schema_json);

    // Capture the header content
    return ob_get_clean();
}

// Helper function to render the topBar
function getTopBarContent() {
    include __DIR__ . '/../views/partials/topbar.php';
}

// Helper function to render the sidebar
function getSideBarContent() {
    include __DIR__ . '/../views/partials/sidebar.php';
}

// Helper function to render the footer
function getFooterContent() {
    include __DIR__ . '/../views/partials/footer.php';
}

function config($key = null, $default = null) {
    static $config;

    if (!$config) {
        $configPath = __DIR__ . '/config.php';
        if (!file_exists($configPath)) {
            throw new Exception("Config file not found: $configPath");
        }
        $config = include $configPath;
    }

    if ($key === null) {
        return $config;
    }

    return $config[$key] ?? $default;
}

/**
 * Darkens a HEX color by a percentage with validation
 * 
 * @param string $hexColor HEX color code (with or without #)
 * @param float $percent Percentage to darken (0-100)
 * @return string Darkened HEX color
 */
function darkenColor($hexColor, $percent) {
    // Validate input
    if (!is_string($hexColor) || !is_numeric($percent) || $percent < 0 || $percent > 100) {
        return '#000000'; // Return black if invalid input
    }

    // Remove # if present and validate length
    $hexColor = ltrim($hexColor, '#');
    if (!in_array(strlen($hexColor), [3, 6]) || !ctype_xdigit($hexColor)) {
        return '#000000'; // Return black if invalid HEX format
    }

    // Convert 3-digit HEX to 6-digit
    if (strlen($hexColor) === 3) {
        $hexColor = $hexColor[0].$hexColor[0].$hexColor[1].$hexColor[1].$hexColor[2].$hexColor[2];
    }

    // Extract RGB components
    $r = hexdec(substr($hexColor, 0, 2));
    $g = hexdec(substr($hexColor, 2, 2));
    $b = hexdec(substr($hexColor, 4, 2));

    // Darken each component
    // Darken the RGB values (with corrected parentheses)
    $r = max(0, min(255, $r - ($r * ($percent / 100))));
    $g = max(0, min(255, $g - ($g * ($percent / 100))));
    $b = max(0, min(255, $b - ($b * ($percent / 100))));

    // Convert back to HEX
    return sprintf('#%02x%02x%02x', $r, $g, $b);
}

/**
 * Gets a color from config with optional darkening
 * 
 * @param string $colorType 'theme_color' or 'alt_color'
 * @param float $darkenBy Percentage to darken (0-100)
 * @return string HEX color code
 */
function getColor($colorType, $darkenBy = 0) {
    // Validate color type
    if (!in_array($colorType, ['theme_color', 'alt_color'])) {
        $colorType = 'theme_color'; // Fallback to theme color
    }

    // Get color from config with fallback
    $defaultColor = $colorType === 'theme_color' ? '#000000' : '#ffffff';
    $color = config($colorType, $defaultColor);

    // Validate color format
    if (!is_string($color) || !preg_match('/^#([A-Fa-f0-9]{3}){1,2}$/', $color)) {
        $color = $defaultColor;
    }

    // Apply darkening if requested
    if ($darkenBy > 0) {
        return darkenColor($color, $darkenBy);
    }

    return $color;
}

function matchRoute($requestURI, $routes) {
    foreach ($routes as $pattern => $route) {
        $pattern = str_replace('/', '\/', $pattern);
        if (preg_match("/^$pattern$/", $requestURI, $matches)) {
            return [$route['file'], $route['title'] ?? null, $matches];
        }
    }
    return [null, null, null];
}

function getPageMetadata($requestURI, $routes, $config, $lang) {
    // Match the route to get title and other metadata
    list($file, $title, $params) = matchRoute($requestURI, $routes);

    // Set metadata for page based on route or fallback to config
    $pageTitle = !empty($title) ? ($title[$lang] ?? $title['en']) : $config['application_name'];
    $pageDescription = !empty($title) ? ($routes[$requestURI]['description'][$lang] ?? $routes[$requestURI]['description']['en'] ?? $config['page_description']) : $config['page_description'];
    $pageKeywords = !empty($title) ? ($routes[$requestURI]['keywords'][$lang] ?? $routes[$requestURI]['keywords']['en'] ?? $config['page_keywords']) : $config['page_keywords'];
    $ogImage = !empty($title) ? ($routes[$requestURI]['og_image'] ?? $config['page_og_image']) : $config['page_og_image'];
    $ogType = !empty($title) ? ($routes[$requestURI]['og_type'] ?? $config['page_og_type']) : $config['page_og_type'];
    $schemaType = !empty($title) ? ($routes[$requestURI]['schema_type'] ?? 'WebPage') : 'WebPage';

    return [
        'pageTitle' => $pageTitle,
        'pageDescription' => $pageDescription,
        'pageKeywords' => $pageKeywords,
        'ogImage' => $ogImage,
        'ogType' => $ogType,
        'schema_type' => $schemaType,
        'url' => $requestURI
    ];
}

function generateSchema($pageMetadata, $config, $l, $lang) {
    // Get the list of supported languages from the config
    $supportedLanguages = array_keys($config['languages']);

    // Determine if the current language is the default language
    $isDefaultLanguage = ($lang === $supportedLanguages[0]);

    // Generate the base URL for the schema (exclude language prefix for default language)
    $baseUrl = $config['base_url'] . ($isDefaultLanguage ? '' : $lang . '/');

    // Default schema for all pages
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => $pageMetadata['schema_type'] ?? 'WebPage',
        'name' => $pageMetadata['title'],
        'description' => $pageMetadata['description'],
        'url' => $baseUrl . ($pageMetadata['url'] ?? ''),
        'image' => $pageMetadata['og_image'],
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => $baseUrl . ($pageMetadata['url'] ?? '') // Use clean URL for default language
        ]
    ];

    // Add Organization schema (common for all pages)
    if (isset($config['organization'])) {
        $schema['publisher'] = [
            '@type' => 'Organization',
            'name' => $config['organization']['name'],
            'logo' => $config['organization']['logo'],
            'url' => $config['organization']['url'],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => $config['organization']['contactPoint']['telephone'],
                'contactType' => $config['organization']['contactPoint']['contactType'],
                'email' => $config['organization']['contactPoint']['email'],
                'areaServed' => $config['organization']['contactPoint']['areaServed']
            ],
            'sameAs' => $config['organization']['sameAs']
        ];
    }

    // Add Breadcrumb schema (common for all pages)
    $schema['breadcrumb'] = [
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => $l['home'], // Translated "Home"
                'item' => $baseUrl // Clean URL for default language
            ]
        ]
    ];

    // Add additional breadcrumb items based on the URL structure (skip for home page)
    if ($pageMetadata['url'] !== '/' && !empty($pageMetadata['url'])) {
        // Split the URL into segments
        $urlSegments = explode('/', trim($pageMetadata['url'], '/'));

        // Build the breadcrumb hierarchy
        $breadcrumbUrl = $baseUrl; // Start with the base URL
        foreach ($urlSegments as $index => $segment) {
            $breadcrumbUrl .= $segment . '/';
            $schema['breadcrumb']['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $index + 2, // Start from position 2
                'name' => $l[$segment] ?? ucfirst(str_replace('-', ' ', $segment)), // Use translation or fallback to a readable name
                'item' => rtrim($breadcrumbUrl, '/') // Remove trailing slash
            ];
        }
    }

    // Add LocalBusiness schema (if applicable)
    if ($pageMetadata['schema_type'] === 'LocalBusiness' && isset($config['localBusiness'])) {
        $schema['@type'] = 'LocalBusiness';
        $schema['name'] = $config['localBusiness']['name'];
        $schema['image'] = $config['localBusiness']['image'];
        $schema['address'] = [
            '@type' => 'PostalAddress',
            'streetAddress' => $config['localBusiness']['address']['streetAddress'],
            'addressLocality' => $config['localBusiness']['address']['addressLocality'],
            'addressRegion' => $config['localBusiness']['address']['addressRegion'],
            'postalCode' => $config['localBusiness']['address']['postalCode'],
            'addressCountry' => $config['localBusiness']['address']['addressCountry']
        ];
        $schema['geo'] = [
            '@type' => 'GeoCoordinates',
            'latitude' => $config['localBusiness']['geo']['latitude'],
            'longitude' => $config['localBusiness']['geo']['longitude']
        ];
        $schema['telephone'] = $config['localBusiness']['telephone'];
        $schema['openingHoursSpecification'] = [
            '@type' => 'OpeningHoursSpecification',
            'dayOfWeek' => $config['localBusiness']['openingHoursSpecification']['dayOfWeek'],
            'opens' => $config['localBusiness']['openingHoursSpecification']['opens'],
            'closes' => $config['localBusiness']['openingHoursSpecification']['closes']
        ];
    }

    return $schema;
}

/**
 * Get versioned asset URL with cache busting
 * 
 * @param string $filePath Asset URL
 * @param array|null $config Optional configuration (falls back to global config)
 * @return string Versioned asset URL
 */
function assetVersion($filePath) {
    // Get config if not already available
    static $config = null;
    if ($config === null) {
        $config = config();
    }
    
    // Get base URL safely
    $baseUrl = isset($config['base_url']) ? rtrim($config['base_url'], '/') : '';
    
    // Convert URL to filesystem path
    $docRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
    $relativePath = str_replace($baseUrl, '', $filePath);
    $fullPath = $docRoot . $relativePath;

    // Add version if file exists
    if (file_exists($fullPath)) {
        return $filePath . '?v=' . filemtime($fullPath);
    }

    return $filePath;
}

/**
 * Set proper cache headers
 * 
 * @param string $file_path Filesystem path to asset
 * @return int Last modified timestamp
 */
function setCacheHeaders($file_path) {
    $last_modified = file_exists($file_path) ? filemtime($file_path) : time();
    
    header("Last-Modified: " . gmdate("D, d M Y H:i:s", $last_modified) . " GMT");
    header("Cache-Control: max-age=31536000, immutable"); // 1 year for versioned assets
    
    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && 
        strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $last_modified) {
        header("HTTP/1.1 304 Not Modified");
        exit;
    }
    
    return $last_modified;
}
// Keep all your existing functions below exactly as they are...
// (renderHeader, getTopBarContent, getSideBarContent, getFooterContent, 
// config, darkenColor, getColor, matchRoute, getPageMetadata, 
// generateSchema, assetVersion, setCacheHeaders)

// Add this at the very end of the file to handle language switching:
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['language'])) {
    $newLang = $_POST['language'];
    $supportedLanguages = array_keys(config('languages', []));
    
    if (in_array($newLang, $supportedLanguages)) {
        $_SESSION['lang'] = $newLang;
        
        // Redirect to same page with new language
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $currentPath = preg_replace('/^\/[a-z]{2}(\/|$)/', '/', $currentPath);
        header('Location: ' . lang_url($currentPath, $newLang));
        exit();
    }
}
?>