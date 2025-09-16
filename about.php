<?php
// about.php
?>
<!DOCTYPE html>
<html lang="es">
<?php $pageTitle = 'Nosotros | JD Perfumería'; include 'includes/head.php'; ?>
<body>
  <?php include 'includes/header.php'; ?>

  <main>
    <!-- Hero -->
    <section class="hero">
      <div class="hero-content">
        <h1>Acerca de JD Perfumería</h1>
        <p>Conoce nuestra historia, misión y pasión por las fragancias exclusivas.</p>
      </div>
    </section>

    <!-- Sección principal -->
    <section class="section about">
      <div class="container grid">
        <div class="card">
          <h2>Nuestra Historia</h2>
          <p>
            <strong>JD Perfumería</strong> es un negocio local de Sincelejo, creado con pasión por las fragancias y el deseo de ofrecer a nuestra comunidad inspiraciones de calidad a precios accesibles.
          </p>
          <p>Todas nuestras inspiraciones son elaboradas cuidadosamente y vendidas por onza, permitiéndote disfrutar de fragancias exquisitas sin invertir en botellas grandes.</p>
            <p>¡Ven y descubre tu fragancia perfecta!</p>
        </div>

        <div class="card">
          <h2>Misión</h2>
          <p>
            Brindar experiencias únicas a través de aromas que evocan recuerdos, emociones 
            y estilos de vida. Queremos que cada cliente encuentre su esencia perfecta.
          </p>
        </div>

        <div class="card">
          <h2>Visión</h2>
          <p>
            Convertirnos en la perfumería de referencia en la región, destacándonos por 
            la autenticidad, calidad premium y atención personalizada.
          </p>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="cta">
      <div class="container">
        <div class="cta-content">
          <h2>¿Quieres conocernos más?</h2>
          <p>Descubre nuestro catálogo exclusivo y encuentra tu fragancia ideal</p>
          <div class="cta-buttons">
            <a href="catalogo.php" class="btn">Explorar Catálogo</a>
            <a href="contacto.php" class="btn btn-secundario">Contáctanos</a>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include 'includes/footer.php'; ?>
</body>
</html>
