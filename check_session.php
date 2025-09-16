<?php
// check_session.php - Sistema centralizado de gestión de sesiones
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lista de páginas públicas (accesibles sin login)
$public_pages = [
    'login.php',
    'registro.php', 
    'index.php',
    'about.php',
    'catalogo.php',
    'contacto.php',
    'logout.php'
];

// Obtener la página actual
$current_page = basename($_SERVER['PHP_SELF']);

// Si el usuario no está logueado y intenta acceder a página privada
if (!isset($_SESSION['usuario_id']) && !in_array($current_page, $public_pages)) {
    header("Location: login.php");
    exit();
}

// Si el usuario está logueado y intenta acceder a login/registro
if (isset($_SESSION['usuario_id']) && ($current_page === 'login.php' || $current_page === 'registro.php')) {
    header("Location: index.php");
    exit();
}
?>