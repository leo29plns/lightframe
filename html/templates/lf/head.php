<?php
    $template['LF_ALTERNATE1'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/';
    $template['LF_ALTERNATE2'] = substr(trim($_SERVER['REQUEST_URI'], '/'), strlen($_ENV['LF_WEBROOT']) + strlen($_SESSION['LF_LOCALE'])) . '/';
    $template['LF_CANONICAL'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/' . trim($_SERVER['REQUEST_URI'], '/') . '/';
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['LF_LOCALE'] ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="{: favicon.ico :}">
    <!-- <link rel="apple-touch-icon" href="{: apple-touch-icon.png :}"> -->

    <meta name="author" content="<?= SITE_AUTHOR ?>">
    
    <meta property="og:title" content="<?= $template['LF_TITLE'] ?? '{{ title }}' ?>">
    <!-- <meta property="og:image" content="https://www.zaparthotels.fr/public/img/og/og-image.webp"> -->
    <meta property="og:url" content="<?= $template['LF_CANONICAL'] ?>">
    <meta property="og:description" content="<?= $template['LF_DESCRIPTION'] ?? '{{ description }}' ?>">
    <meta property="og:locale" content="<?= $_SESSION['LF_LOCALE'] ?>">
    <meta property="og:site_name" content="<?= SITE_NAME ?>">
    <!-- <meta property="twitter:card" content="summary_large_image"> -->
    <!-- <meta property="twitter:image" content="https://www.zaparthotels.fr/public/img/og/og-image.webp"> -->
    <meta property="twitter:title" content="<?= $template['LF_TITLE'] ?? '{{ title }}' ?> - <?= SITE_NAME ?>">
    <meta property="twitter:description" content="<?= $template['LF_DESCRIPTION'] ?? '{{ description }}' ?>">
    
    <!-- <meta name="theme-color" content="var(--lf-theme-color)"> -->
    <link rel="stylesheet" href="{: lf/css/reset.css :}">
    <link rel="stylesheet" href="{: lf/css/default.css :}">

    
    <meta name="description" content="<?= $template['LF_DESCRIPTION'] ?? '{{ description }}' ?>">
    <title><?= $template['LF_TITLE'] ?? '{{ title }}' ?> - <?= SITE_NAME ?></title>

    <link rel="canonical" href="<?= $template['LF_CANONICAL'] ?>">

<?php foreach ($_ENV['LF_LOCALES_FILE']['locales'] as $locale): ?>
    <link rel="alternate" hreflang="<?= $locale['code'] ?>" href="<?= $template['LF_ALTERNATE1'] . $locale['code'] . $template['LF_ALTERNATE2'] ?>">
<?php endforeach; ?>

<?php foreach ($template['LF_CSSFILES'] as $cssFile): ?>
    <link rel="stylesheet" href="<?= $_ENV['LF_WEBROOT'] . '/css/' . $cssFile ?>">
<?php endforeach; ?>

<?php require('html' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'commons' . DIRECTORY_SEPARATOR . 'head.php') ?>
</head>
<body>