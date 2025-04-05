<?php
return [
    'application_name' => 'Application Name', // Replace with your application name
    'logo' => 'https://domain.com/theme/assets/logo.png', // Add your logo link
    'base_url' => 'https://domain.com/', // Ensure this ends with a slash /
    'assets_url' => '/theme/assets/',
    'theme_color' => '#EB3737',
    'alt_color' => '#FFFFFF',
    
    // Languages
    'languages' => [
        'en' => 'English', // First Language
        'ar' => 'العربية', // Second Language
        'ur' => 'اردو',    // Third Language
        // Add more languages as needed
    ],
    
    'page_description' => 'Welcome to the Application Name. Discover our variety of services.', // Description for the page
    'page_keywords' => 'Application Name, products, services, online services', // Keywords for SEO
    'page_og_image' => 'https://domain.com/theme/assets/images/og-image.jpg', // Open Graph image
    'page_og_type' => 'website', // Open Graph type
    'current_url' => 'https://domain.com/', // Fallback canonical URL

    // Schema-related configuration
    'organization' => [
        'name' => 'Application Name',
        'logo' => 'https://domain.com/theme/assets/logo.png',
        'url' => 'https://domain.com',
        'contactPoint' => [
            'telephone' => '+1-234-274-5150',
            'contactType' => 'customer service',
            'email' => 'info@domain.com',
            'areaServed' => 'Worldwide'
        ],
        'sameAs' => [
            'https://facebook.com/Application Name',
            'https://twitter.com/Application Name',
            'https://linkedin.com/company/Application Name'
        ]
    ],
    'localBusiness' => [
        'name' => 'Application Name Café',
        'image' => 'https://domain.com/theme/assets/cafe.jpg',
        'address' => [
            'streetAddress' => '123 Main St',
            'addressLocality' => 'New York',
            'addressRegion' => 'NY',
            'postalCode' => '10001',
            'addressCountry' => 'USA'
        ],
        'geo' => [
            'latitude' => '40.7128',
            'longitude' => '-74.0060'
        ],
        'telephone' => '+1-123-456-7890',
        'openingHoursSpecification' => [
            'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            'opens' => '08:00',
            'closes' => '22:00'
        ]
    ]
];
?>

