<?php
// includes/head.php
require_once __DIR__ . '/init.php';
$pageTitle = $pageTitle ?? APP_NAME;
?>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= e($pageTitle) ?></title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/custom-images.css">
    <link rel="icon" type="image/x-icon" href="/JD_Perfumeria/img/logos/favicon.ico">

    <link rel="icon" type="image/x-icon" href="img/logos/favicon.ico">
</head>