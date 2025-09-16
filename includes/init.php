<?php
// includes/init.php
declare(strict_types=1);

mb_internal_encoding('UTF-8');
date_default_timezone_set('America/Argentina/Buenos_Aires');

define('APP_NAME', 'JD Perfumería');

// Durante desarrollo:
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Helper para escapar HTML rápido
if (!function_exists('e')) {
  function e(?string $str): string {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
  }
}
