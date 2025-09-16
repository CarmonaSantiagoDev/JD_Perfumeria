<?php
// Iniciar la sesión para todas las páginas que incluyan este archivo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lista de páginas que no requieren redirección al login
$paginas_permitidas_sin_login = [
    'login.php',
    'registro.php', 
    'index.php',
    'about.php',
    'catalogo.php',
    'contacto.php'
];

// Obtener la página actual
$pagina_actual = basename($_SERVER['PHP_SELF']);

// Redirigir al login si no está logueado y no está en una página permitida
if (!isset($_SESSION['usuario_id']) && !in_array($pagina_actual, $paginas_permitidas_sin_login)) {
    header("Location: login.php");
    exit();
}

// Evitar que usuarios logueados accedan al login o registro
if (isset($_SESSION['usuario_id']) && ($pagina_actual === 'login.php' || $pagina_actual === 'registro.php')) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JD Perfumería</title>
    <link rel="stylesheet" href="/JD_Perfumeria/css/style.css">
    <link rel="stylesheet" href="/JD_Perfumeria/css/product_card.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <!-- Agregar Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos específicos para el header */
        .site-header {
            background-color: var(--primary);
            color: var(--text-light);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .header-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
            position: relative;
        }
        
        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: var(--text-light);
            gap: 15px;
        }
        
        .logo-img {
            height: 45px;
            width: auto;
            transition: transform 0.3s ease;
        }
        
        .logo:hover .logo-img {
            transform: scale(1.05);
        }
        
        .logo span {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: bold;
            letter-spacing: 0.5px;
            transition: color 0.3s ease;
        }
        
        .logo:hover span {
            color: var(--accent);
        }
        
        /* Menú de navegación principal */
        .main-nav ul {
            list-style: none;
            display: flex;
            gap: 2rem;
            align-items: center;
            margin: 0;
            padding: 0;
        }
        
        .main-nav a {
            color: var(--text-light);
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            transition: color 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0;
        }
        
        .main-nav a:hover {
            color: var(--accent);
        }
        
        /* Menú de usuario con submenú */
        .user-menu {
            position: relative;
        }
        
        .user-toggle {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            background: transparent;
            border: none;
            font: inherit;
            padding: .25rem .5rem;
        }

        /* rotate arrow when parent has .open */
        .user-menu.open .user-toggle .fa-chevron-down {
            transform: rotate(180deg);
        }

        .user-toggle .fa-chevron-down {
            transition: transform .25s ease;
            font-size: .9rem;
        }
        
        /* -----------------------------
           Animated dropdown (slide + fade)
           ----------------------------- */
        .user-submenu {
            position: absolute;
            top: 100%;
            right: 0;
            background: #fff;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border-radius: 10px;
            min-width: 260px;
            z-index: 1200;
            overflow: hidden;
            margin-top: 12px;

            /* hidden state */
            max-height: 0;
            opacity: 0;
            transform: translateY(-8px);
            transition: max-height .32s cubic-bezier(.2,.9,.2,1), opacity .24s ease, transform .28s cubic-bezier(.2,.9,.2,1);
            pointer-events: none;
        }

        /* visible state */
        .user-menu.open .user-submenu {
            max-height: 480px; /* suficiente para el contenido */
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .user-submenu li {
            display: block;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .user-submenu li:last-child {
            border-bottom: none;
        }
        
        .user-submenu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            color: var(--text-dark);
            text-decoration: none;
            transition: background 0.18s ease, transform 0.18s ease;
            font-size: 0.95rem;
        }
        
        .user-submenu a:hover {
            background: #f3f5ff;
            color: var(--primary);
            transform: translateX(4px);
        }

        /* subtle entrance for items (optional but nice) */
        .user-menu.open .user-submenu li {
            /* no heavy animation per item to avoid complexity—keeps CSS simple */
        }
        
        /* Contador de carrito */
        .menu-carrito {
            position: relative;
        }
        
        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--accent);
            color: var(--text-dark);
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 11px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Botón de menú hamburguesa (oculto en desktop) */
        .menu-toggle {
            display: none;
            flex-direction: column;
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
        }
        
        .menu-toggle span {
            width: 25px;
            height: 3px;
            background: var(--text-light);
            margin: 3px 0;
            transition: 0.3s;
        }
        
        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            .header-inner {
                padding: 0 1rem;
            }
            
            .main-nav ul {
                gap: 1.5rem;
            }
        }
        
        @media (max-width: 768px) {
            .menu-toggle {
                display: flex;
            }
            
            .main-nav {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--primary);
                padding: 0 1rem;
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s ease;
            }
            
            .main-nav.active {
                max-height: 500px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            }
            
            .main-nav ul {
                flex-direction: column;
                align-items: flex-start;
                padding: 1rem 0;
                gap: 0;
            }
            
            .main-nav li {
                width: 100%;
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }
            
            .main-nav a {
                padding: 12px 0;
                width: 100%;
            }
            
            .user-menu {
                width: 100%;
            }
            
            /* On mobile the submenu becomes a flowing block inside the nav, but still animated */
            .user-submenu {
                position: static;
                width: 100%;
                box-shadow: none;
                border-radius: 0;
                margin-top: 0.5rem;

                /* keep animation params but reset transform */
                transform: none;
                max-height: 0;
                opacity: 0;
                pointer-events: none;
            }

            .user-menu.open .user-submenu {
                max-height: 800px; /* enough for stacked mobile content */
                opacity: 1;
                pointer-events: auto;
            }
            
            .user-submenu a {
                padding-left: 2rem;
            }
            
            /* Animación del botón hamburguesa */
            .menu-toggle.active span:nth-child(1) {
                transform: rotate(-45deg) translate(-5px, 6px);
            }
            
            .menu-toggle.active span:nth-child(2) {
                opacity: 0;
            }
            
            .menu-toggle.active span:nth-child(3) {
                transform: rotate(45deg) translate(-5px, -6px);
            }
        }
        
        @media (max-width: 480px) {
            .logo span {
                font-size: 1.2rem;
            }
            
            .logo-img {
                height: 35px;
            }
        }
    </style>
</head>
<body>

<header class="site-header">
    <div class="container header-inner">
        <a href="index.php" class="logo">
            <img src="/JD_Perfumeria/img/logos/logo-jd.png" alt="Logo de JD Perfumería" class="logo-img">
            <span>JD Perfumería</span>
        </a>

        <!-- Botón de menú móvil -->
        <button class="menu-toggle" aria-label="Abrir menú">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav class="main-nav">
            <ul>
                <li><a href="/JD_Perfumeria/index.php"><i class="fas fa-home"></i> Inicio</a></li>
                <li><a href="/JD_Perfumeria/catalogo.php"><i class="fas fa-store"></i> Catálogo</a></li>
                <li><a href="/JD_Perfumeria/about.php"><i class="fas fa-info-circle"></i> Nosotros</a></li>
                <li><a href="/JD_Perfumeria/contacto.php"><i class="fas fa-envelope"></i> Contacto</a></li>
                
                <?php 
                // Verificar si el usuario está logueado de forma segura
                $usuarioLogueado = isset($_SESSION['usuario_id']);
                $nombreUsuario = $usuarioLogueado ? ($_SESSION['usuario_nombre'] ?? 'Usuario') : '';
                // CORRECCIÓN: Usar 'rol' en lugar de 'roll'
                $rolUsuario = $usuarioLogueado ? ($_SESSION['rol'] ?? 'cliente') : 'cliente';
                $carritoCount = isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0;
                ?>
                
                <?php if ($usuarioLogueado): ?>
                    <li class="menu-carrito">
                        <a href="/JD_Perfumeria/carrito.php">
                            <i class="fas fa-shopping-cart"></i> Carrito
                            <?php if ($carritoCount > 0): ?>
                                <span class="cart-count"><?php echo $carritoCount; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    
                    <li class="user-menu" aria-haspopup="true">
                        <!-- NOTE: usamos button-like anchor pero prevenimos default en JS -->
                        <a href="#" class="user-toggle" aria-expanded="false">
                            <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($nombreUsuario); ?>
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="user-submenu" role="menu" aria-hidden="true">
                            <?php if ($rolUsuario == 'admin'): ?>
                                <li role="none"><a role="menuitem" href="/JD_Perfumeria/admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> Panel Admin</a></li>
                                <li role="none"><a role="menuitem" href="/JD_Perfumeria/admin/ver_perfumes.php"><i class="fas fa-cubes"></i> Gestionar Productos</a></li>
                                <li role="none"><a role="menuitem" href="/JD_Perfumeria/admin/ver_pedidos.php"><i class="fas fa-clipboard-list"></i> Ver Pedidos</a></li>
                                <li role="none"><a role="menuitem" href="/JD_Perfumeria/admin/agregar_perfume.php"><i class="fas fa-plus-circle"></i> Agregar Producto</a></li>
                            <?php else: ?>
                                <li role="none"><a role="menuitem" href="/JD_Perfumeria/cliente/dashboard.php"><i class="fas fa-user-circle"></i> Mi Cuenta</a></li>
                                <li role="none"><a role="menuitem" href="/JD_Perfumeria/cliente/mis_pedidos.php"><i class="fas fa-history"></i> Mis Pedidos</a></li>
                                <li role="none"><a role="menuitem" href="/JD_Perfumeria/cliente/editar_perfil.php"><i class="fas fa-user-edit"></i> Editar Perfil</a></li>
                            <?php endif; ?>
                            <li role="none"><a role="menuitem" href="/JD_Perfumeria/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li><a href="/JD_Perfumeria/login.php"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</a></li>
                    <li><a href="/JD_Perfumeria/registro.php"><i class="fas fa-user-plus"></i> Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<!-- Script para el menú móvil y funcionalidades (robusto y accesible) -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.querySelector('.menu-toggle');
    const mainNav = document.querySelector('.main-nav');

    // Mobile menu toggle
    if (menuToggle && mainNav) {
        menuToggle.addEventListener('click', () => {
            mainNav.classList.toggle('active');
            menuToggle.classList.toggle('active');
        });
    }

    // Delegated handling for user menus (reliable even with multiple user-menu instances)
    document.addEventListener('click', (e) => {
        // If clicked on a user-toggle (or inside it)
        const toggle = e.target.closest('.user-toggle');
        if (toggle) {
            e.preventDefault(); // prevent jump-to-top for href="#"
            const userMenu = toggle.closest('.user-menu');
            const submenu = userMenu?.querySelector('.user-submenu');

            if (!userMenu || !submenu) return;

            // Toggle open state
            const isOpen = userMenu.classList.toggle('open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            submenu.setAttribute('aria-hidden', isOpen ? 'false' : 'true');

            // Close other open user menus so only one stays open
            document.querySelectorAll('.user-menu.open').forEach(um => {
                if (um !== userMenu) {
                    um.classList.remove('open');
                    const t = um.querySelector('.user-toggle');
                    const s = um.querySelector('.user-submenu');
                    if (t) t.setAttribute('aria-expanded', 'false');
                    if (s) s.setAttribute('aria-hidden', 'true');
                }
            });

            return;
        }

        // Click outside any user-menu -> close all
        if (!e.target.closest('.user-menu')) {
            document.querySelectorAll('.user-menu.open').forEach(um => {
                um.classList.remove('open');
                const t = um.querySelector('.user-toggle');
                const s = um.querySelector('.user-submenu');
                if (t) t.setAttribute('aria-expanded', 'false');
                if (s) s.setAttribute('aria-hidden', 'true');
            });
        }
    });

    // Close menus if user clicks a submenu link (let navigation happen, but close menu visually)
    document.querySelectorAll('.user-submenu a').forEach(link => {
        link.addEventListener('click', () => {
            const um = link.closest('.user-menu');
            if (um) {
                um.classList.remove('open');
                const t = um.querySelector('.user-toggle');
                const s = um.querySelector('.user-submenu');
                if (t) t.setAttribute('aria-expanded', 'false');
                if (s) s.setAttribute('aria-hidden', 'true');
            }
        });
    });

    // Close on scroll (use a small throttle)
    let lastScroll = 0;
    window.addEventListener('scroll', () => {
        const now = Date.now();
        if (now - lastScroll < 100) return;
        lastScroll = now;
        document.querySelectorAll('.user-menu.open').forEach(um => {
            um.classList.remove('open');
            const t = um.querySelector('.user-toggle');
            const s = um.querySelector('.user-submenu');
            if (t) t.setAttribute('aria-expanded', 'false');
            if (s) s.setAttribute('aria-hidden', 'true');
        });
    }, { passive: true });

    // Close on ESC
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.user-menu.open').forEach(um => {
                um.classList.remove('open');
                const t = um.querySelector('.user-toggle');
                const s = um.querySelector('.user-submenu');
                if (t) t.setAttribute('aria-expanded', 'false');
                if (s) s.setAttribute('aria-hidden', 'true');
            });
        }
    });

    // Close on resize
    window.addEventListener('resize', () => {
        document.querySelectorAll('.user-menu.open').forEach(um => {
            um.classList.remove('open');
            const t = um.querySelector('.user-toggle');
            const s = um.querySelector('.user-submenu');
            if (t) t.setAttribute('aria-expanded', 'false');
            if (s) s.setAttribute('aria-hidden', 'true');
        });
    });
});
</script>
