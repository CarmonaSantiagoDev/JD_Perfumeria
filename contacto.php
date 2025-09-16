<?php
// contacto.php
?>
<!DOCTYPE html>
<html lang="es">
<?php $pageTitle = 'Contacto | JD Perfumería'; include 'includes/head.php'; ?>
<body>
  <?php include 'includes/header.php'; ?>

  <main>
    <!-- Hero -->
    <section class="hero">
      <div class="hero-content">
        <h1>Contacta con Nosotros</h1>
        <p>Estamos aquí para ayudarte a encontrar tu fragancia perfecta</p>
      </div>
    </section>

    <!-- Info de contacto -->
    <section class="section contacto-info">
      <div class="container grid">
        <div class="card">
          <div class="icon">📍</div>
          <h3>Nuestra Ubicación</h3>
          <p>Av. Principal 1234<br>Centro Comercial Plaza Mayor<br>Local 45, Primer Nivel<br>Ciudad de México, CDMX</p>
        </div>

        <div class="card">
          <div class="icon">📞</div>
          <h3>Teléfonos</h3>
          <p><strong>Ventas:</strong> +52 55 1234 5678<br>
          <strong>Atención:</strong> +52 55 1234 5679<br>
          <strong>WhatsApp:</strong> +52 55 1234 5680</p>
        </div>

        <div class="card">
          <div class="icon">✉️</div>
          <h3>Correo Electrónico</h3>
          <p><strong>General:</strong> info@jdperfumeria.com<br>
          <strong>Ventas:</strong> ventas@jdperfumeria.com<br>
          <strong>Soporte:</strong> soporte@jdperfumeria.com</p>
        </div>

        <div class="card">
          <div class="icon">🕒</div>
          <h3>Horarios de Atención</h3>
          <p><strong>Lunes a Viernes:</strong> 10:00 - 20:00<br>
          <strong>Sábados:</strong> 10:00 - 18:00<br>
          <strong>Domingos:</strong> 12:00 - 17:00</p>
        </div>
      </div>
    </section>

    <!-- Formulario -->
    <section class="section">
      <div class="container">
        <h2 class="section-title">Envíanos un Mensaje</h2>
        <form class="form">
          <div class="form-row">
            <div class="form-group">
              <label for="nombre">Nombre *</label>
              <input type="text" id="nombre" required>
            </div>
            <div class="form-group">
              <label for="email">Correo *</label>
              <input type="email" id="email" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="telefono">Teléfono</label>
              <input type="tel" id="telefono">
            </div>
            <div class="form-group">
              <label for="asunto">Asunto *</label>
              <select id="asunto" required>
                <option value="">Selecciona un asunto</option>
                <option value="consulta">Consulta General</option>
                <option value="producto">Producto</option>
                <option value="pedido">Pedido</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="mensaje">Mensaje *</label>
            <textarea id="mensaje" rows="6" required></textarea>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn">Enviar</button>
            <button type="reset" class="btn btn-secundario">Limpiar</button>
          </div>
        </form>
      </div>
    </section>
  </main>

  <?php include 'includes/footer.php'; ?>
</body>
</html>
