<?php 
$pageTitle = 'Inicio | JD Perfumería'; 
include 'includes/head.php'; 


// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<body>
  <?php include 'includes/header.php'; ?>

  <!-- Hero -->
  <section class="hero">
    <div class="container hero-content">
      <h1>Descubre la esencia de tu personalidad</h1>
      <p>Perfumes exclusivos para cada estilo ✨</p>
      <div class="hero-buttons">
        <a href="catalogo.php" class="btn btn-hero">Ver catálogo</a>
        <a href="about.php" class="btn btn-hero btn-secundario">Conoce JD</a>
      </div>
    </div>
  </section>

  <!-- Características -->
  <section class="section">
    <div class="container">
      <h2 class="section-title">¿Por qué elegir JD Perfumería?</h2>
      <div class="caracteristicas-grid">
        <article class="caracteristica">
          <div class="caracteristica-icon"><i class="fas fa-gem"></i></div>
          <h3>Calidad Premium</h3>
          <p>Inspiraciones de alta calidad con esencias duraderas que capturan la esencia de las marcas originales.</p>
          <p><small>Productos vendidos por onza</small></p>
        </article>
        <article class="caracteristica">
          <div class="caracteristica-icon"><i class="fas fa-truck"></i></div>
          <h3>Envío Local</h3>
          <p>Servicio de entrega en Sincelejo y áreas cercanas con embalaje especial para proteger tu fragancia.</p>
          <p><small>Negocio local con entrega personalizada</small></p>
        </article>
        <article class="caracteristica">
          <div class="caracteristica-icon"><i class="fas fa-comments"></i></div>
          <h3>Asesoría Personalizada</h3>
          <p>Te ayudamos a encontrar la inspiración perfecta para cada ocasión y estilo personal.</p>
          <p><small>Recomendaciones basadas en tus preferencias</small></p>
        </article>
        <article class="caracteristica">
          <div class="caracteristica-icon"><i class="fas fa-shield-alt"></i></div>
          <h3>Garantía Total</h3>
          <p>100% de satisfacción garantizada.</p>
        </article>
      </div>
    </div>
  </section>

  <!-- Categorías -->
  <section class="section">
    <div class="container">
      <h2 class="section-title">Explora por Categoría</h2>
      <div class="categorias-grid">
        <div class="categoria-card masculino">
          <div class="categoria-content">
            <h3>Perfumes Masculinos</h3>
            <a href="catalogo.php?genero=masculino" class="btn btn-categoria">Ver colección</a>
          </div>
        </div>
        <div class="categoria-card femenino">
          <div class="categoria-content">
            <h3>Perfumes Femeninos</h3>
            <a href="catalogo.php?genero=femenino" class="btn btn-categoria">Ver colección</a>
          </div>
        </div>
        <div class="categoria-card unisex">
          <div class="categoria-content">
            <h3>Perfumes Unisex</h3>
            <a href="catalogo.php?genero=unisex" class="btn btn-categoria">Ver colección</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonios -->
  <section class="section testimonios">
    <div class="container">
      <h2 class="section-title">Lo que dicen nuestros clientes</h2>
      <div class="testimonios-grid">
        <div class="testimonio">
          <div class="testimonio-stars">⭐⭐⭐⭐⭐</div>
          <p>"¡Excelente servicio y productos de alta calidad! El envío fue rápido y el perfume superó mis expectativas."</p>
          <div class="testimonio-autor">
            <strong>María González</strong>
          </div>
        </div>
        <div class="testimonio">
          <div class="testimonio-stars">⭐⭐⭐⭐⭐</div>
          <p>"JD Perfumería me ayudó a encontrar el perfume perfecto. La asesoría fue personalizada y muy profesional."</p>
          <div class="testimonio-autor">
            <strong>Carlos Rodríguez</strong>
          </div>
        </div>
        <div class="testimonio">
          <div class="testimonio-stars">⭐⭐⭐⭐⭐</div>
          <p>"Rápido envío y productos auténticos. Definitivamente volveré a comprar aquí. ¡Altamente recomendado!"</p>
          <div class="testimonio-autor">
            <strong>Ana Martínez</strong>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Acceso Rápido para Usuarios -->
  <?php if (isset($_SESSION['usuario_id'])): ?>
  <section class="section quick-access">
    <div class="container">
      <h2 class="section-title">Acceso Rápido</h2>
      <div class="quick-access-grid">
        <?php if ($_SESSION['rol'] == 'admin'): ?>
          <a href="admin/dashboard.php" class="quick-access-card">
            <i class="fas fa-tachometer-alt"></i>
            <h3>Panel Admin</h3>
            <p>Gestiona productos y pedidos</p>
          </a>
          <a href="admin/agregar_perfume.php" class="quick-access-card">
            <i class="fas fa-plus-circle"></i>
            <h3>Agregar Producto</h3>
            <p>Añade nuevos perfumes</p>
          </a>
          <a href="admin/ver_pedidos.php" class="quick-access-card">
            <i class="fas fa-clipboard-list"></i>
            <h3>Ver Pedidos</h3>
            <p>Revisa todos los pedidos</p>
          </a>
        <?php else: ?>
          <a href="cliente/dashboard.php" class="quick-access-card">
            <i class="fas fa-user-circle"></i>
            <h3>Mi Cuenta</h3>
            <p>Gestiona tu perfil</p>
          </a>
          <a href="cliente/mis_pedidos.php" class="quick-access-card">
            <i class="fas fa-history"></i>
            <h3>Mis Pedidos</h3>
            <p>Revisa tu historial</p>
          </a>
          <a href="catalogo.php" class="quick-access-card">
            <i class="fas fa-store"></i>
            <h3>Seguir Comprando</h3>
            <p>Descubre más productos</p>
          </a>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- CTA -->
  <section class="section cta">
    <div class="container cta-inner">
      <div>
        <h2>¿Listo para descubrir tu fragancia perfecta?</h2>
        <p>Explora nuestro catálogo exclusivo y encuentra el perfume que define tu estilo.</p>
      </div>
      <div class="cta-actions">
        <a href="catalogo.php" class="btn btn-lg">Explorar Catálogo</a>
        <a href="contacto.php" class="btn btn-secundario btn-lg">Contactar</a>
      </div>
    </div>
  </section>

  <?php include 'includes/footer.php'; ?>

  <style>
    /* Estilos para la página de inicio */
    .hero {
      background: linear-gradient(rgba(11, 23, 54, 0.8), rgba(11, 23, 54, 0.8)), url('/JD_Perfumeria/img/backgrounds/hero-bg.jpg') center/cover;
      color: white;
      text-align: center;
      padding: 100px 20px;
      margin-bottom: 60px;
    }
    
    .hero-content h1 {
      font-size: 3.5rem;
      margin-bottom: 20px;
      font-family: 'Playfair Display', serif;
    }
    
    .hero-content p {
      font-size: 1.3rem;
      margin-bottom: 30px;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
    }
    
    .hero-buttons {
      display: flex;
      gap: 20px;
      justify-content: center;
      flex-wrap: wrap;
    }
    
    .btn-hero {
      padding: 15px 40px;
      font-size: 1.2rem;
      font-weight: bold;
      border-radius: 8px;
      text-decoration: none;
      display: inline-block;
      transition: transform 0.3s ease;
    }
    
    .btn-hero:hover {
      transform: translateY(-3px);
    }
    
    .section {
      padding: 60px 0;
    }
    
    .section-title {
      text-align: center;
      font-size: 2.5rem;
      color: var(--primary);
      margin-bottom: 40px;
      font-family: 'Playfair Display', serif;
    }
    
    .caracteristicas-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 30px;
    }
    
    .caracteristica {
      text-align: center;
      padding: 30px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      transition: transform 0.3s ease;
    }
    
    .caracteristica:hover {
      transform: translateY(-5px);
    }
    
    .caracteristica-icon {
      font-size: 3rem;
      color: var(--primary);
      margin-bottom: 20px;
    }
    
    .caracteristica h3 {
      color: var(--primary);
      margin-bottom: 15px;
    }
    
    .categorias-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
    }
    
    .categoria-card {
      border-radius: 10px;
      padding: 40px 20px;
      text-align: center;
      color: white;
      background-size: cover;
      background-position: center;
      min-height: 250px;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }
    
    .categoria-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0,0,0,0.5);
      z-index: 1;
    }
    
    .categoria-content {
      position: relative;
      z-index: 2;
    }
    
    .categoria-card h3 {
      font-size: 1.8rem;
      margin-bottom: 20px;
      font-family: 'Playfair Display', serif;
    }
    
    .masculino {
      background-image: url('/JD_Perfumeria/img/backgrounds/masculino-bg.jpg');
    }
    
    .femenino {
      background-image: url('/JD_Perfumeria/img/backgrounds/femenino-bg.jpg');
    }
    
    .unisex {
      background-image: url('/JD_Perfumeria/img/backgrounds/unisex-bg.jpg');
    }
    
    .btn-categoria {
      background: var(--accent);
      color: var(--text-dark);
      padding: 12px 25px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s ease;
    }
    
    .btn-categoria:hover {
      background: #d4af37;
    }
    
    .testimonios {
      background: #f8f9fa;
    }
    
    .testimonios-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 25px;
    }
    
    .testimonio {
      background: white;
      border-radius: 10px;
      padding: 25px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .testimonio-stars {
      color: var(--accent);
      font-size: 1.2rem;
      margin-bottom: 15px;
    }
    
    .testimonio-autor {
      margin-top: 15px;
      color: var(--secondary);
    }
    
    .quick-access {
      background: var(--light);
    }
    
    .quick-access-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }
    
    .quick-access-card {
      background: white;
      padding: 30px;
      border-radius: 10px;
      text-align: center;
      text-decoration: none;
      color: inherit;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .quick-access-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
      color: inherit;
    }
    
    .quick-access-card i {
      font-size: 2.5rem;
      color: var(--primary);
      margin-bottom: 15px;
    }
    
    .quick-access-card h3 {
      color: var(--primary);
      margin-bottom: 10px;
    }
    
    .cta {
      background: var(--secondary);
      color: white;
    }
    
    .cta-inner {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 40px;
      align-items: center;
    }
    
    .cta-actions {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    
    .btn-lg {
      padding: 15px 30px;
      font-size: 1.1rem;
      text-align: center;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      .hero-content h1 {
        font-size: 2.5rem;
      }
      
      .hero-content p {
        font-size: 1.1rem;
      }
      
      .hero-buttons {
        flex-direction: column;
        align-items: center;
      }
      
      .cta-inner {
        grid-template-columns: 1fr;
        text-align: center;
      }
      
      .categorias-grid {
        grid-template-columns: 1fr;
      }
      
      .caracteristicas-grid {
        grid-template-columns: 1fr;
      }
      
      .testimonios-grid {
        grid-template-columns: 1fr;
      }
      
      .quick-access-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</body>
</html>