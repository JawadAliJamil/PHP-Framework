<!DOCTYPE html>
<html lang="en">
<head>
    <?= $headerContent; ?>
    <!-- Output the header content here -->
    <meta charset="UTF-8">
</head>

<body>
    <div class="language-switcher">
        <ul>
            <?php foreach ($config['languages'] as $code => $name): ?>
                <li>
                    <a href="<?= BASE_URL ?><?= $code ?>/<?= $requestURI ?>">
                        <?= $name ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <h1>Functions</h1>
    <ul>
        <li>App Name; <?= htmlspecialchars($applicationName, ENT_QUOTES, 'UTF-8') ?></li>
        <li>Base URL 1: <?= BASE_URL; ?></li>
        <li>Base URL 2: <?= getBaseUrl($config); ?></li>
        <li>Domain: <?= getDomain(); ?></li>
        <li>Assets URL 1: <?= ASSETS_URL; ?></li>
        <li>Assets URL 2: <?= getAssetsUrl($config); ?></li>
        <li>Theme URL: <?= getThemeUrl($config); ?></li>
        <li>Theme Color: <?= getColor('theme_color'); ?><div style="background-color:<?= getColor('theme_color'); ?>;width:20px;height: 20px;display: inline-block;"></div></li>
        <li>Alt Color: <?= getColor('alt_color'); ?><div style="background-color:<?= getColor('alt_color'); ?>;width:20px;height: 20px;display: inline-block;"></div></li>
        <li>Dark Theme Color: <?= getColor('theme_color', 10); ?><div style="background-color:<?= getColor('theme_color', 30); ?>;width:20px;height: 20px;display: inline-block;"></div></li>
        <li>Dark Theme Color: <?= getColor('alt_color', 20); ?><div style="background-color:<?= getColor('alt_color', 30); ?>;width:20px;height: 20px;display: inline-block;"></div></li>
    </ul>
    <img src="/asset?path=logo.png" alt="Logo">
    <img src="<?= assetVersion(ASSETS_URL . 'logo.png', $config) ?>" alt="Logo">
    <?= $l['home'] ?>
    <?= t('yahoo'); ?>
    <pre><code>
    &lt;?php getFooterContent(); ?&gt;
    </code></pre>
</body>
</html>