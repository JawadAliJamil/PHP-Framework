    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index">
    <meta name="googlebot" content="index">

    <!-- Title -->
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>

    <!-- Meta Tags -->
    <meta name="description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="keywords" content="<?= htmlspecialchars($pageKeywords, ENT_QUOTES, 'UTF-8') ?>">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:image" content="<?= htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:type" content="<?= htmlspecialchars($ogType, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:url" content="<?= htmlspecialchars($config['current_url'], ENT_QUOTES, 'UTF-8') ?>">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8') ?>">

    <!-- Canonical URL -->
     <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8') ?>">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?= assetVersion(ASSETS_URL . 'css/style.css', $config) ?>" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?= assetVersion(ASSETS_URL . 'css/skin.css', $config) ?>" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?= assetVersion(ASSETS_URL . 'css/' . t('direction') . '.css', $config) ?>" type="text/css" media="screen" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin="anonymous" />
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js" type="text/javascript"></script>
    <script type="text/javascript">
      WebFont.load({
        google: {
          families: ["Inter:100,200,300,regular,500,600,700,800,900"]
        }
      });
    </script>
    <script type="text/javascript">
      ! function(o, c) {
        var n = c.documentElement,
          t = " w-mod-";
        n.className += t + "js", ("ontouchstart" in o || o.DocumentTouch && c instanceof DocumentTouch) && (n.className += t + "touch")
      }(window, document);
    </script>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= ASSETS_URL ?>images/favicon.ico">
    <link href="<?= ASSETS_URL ?>images/webclip.png" rel="apple-touch-icon" />
    
    <!-- Schema Markup -->
    <script type="application/ld+json">
    <?= $schema_json ?>
    </script>
