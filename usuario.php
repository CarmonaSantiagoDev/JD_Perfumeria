<?php
// usuario.php
?>
<!DOCTYPE html>
<html lang="es">
<?php $pageTitle = 'Mi Cuenta | JD Perfumería'; include 'includes/head.php'; ?>
<body>
  <?php include 'includes/header.php'; ?>

  <main>
    <!-- Hero -->
    <section class="hero">
      <div class="container hero-content">
        <h1>Mi Cuenta</h1>
        <p>Accede a tu perfil, pedidos y preferencias</p>
      </div>
    </section>

    <!-- Opciones de usuario -->
    <section class="section">
      <div class="container grid">
        <div class="card">
          <h3>👤 Perfil</h3>
          <p>Revisa y actualiza tus datos personales.</p>
          <a href="#" class="btn">Ver Perfil</a>
        </div>

        <div class="card">
          <h3>🛒 Mis Pedidos</h3>
          <p>Consulta el historial y estado de tus compras.</p>
          <a href="#" class="btn">Ver Pedidos</a>
        </div>

        <div class="card">
          <h3>❤️ Favoritos</h3>
          <p>Guarda y accede rápidamente a tus perfumes preferidos.</p>
          <a href="#" class="btn">Ver Favoritos</a>
        </div>

        <div class="card">
          <h3>⚙️ Configuración</h3>
          <p>Gestiona tus preferencias de cuenta y notificaciones.</p>
          <a href="#" class="btn">Configurar</a>
        </div>
      </div>
    </section>

    <!-- Panel de acceso y registro -->
    <section class="section">
      <div class="container usuario-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem;">
        <!-- Login -->
        <div class="card usuario-card">
          <h2>Iniciar Sesión</h2>
          <form id="login-form">
            <div class="form-group">
              <label for="email-login">Correo Electrónico *</label>
              <input type="email" id="email-login" name="email" required>
            </div>
            <div class="form-group">
              <label for="password-login">Contraseña *</label>
              <input type="password" id="password-login" name="password" required>
            </div>
            <div class="form-actions">
              <button type="submit" class="btn">Ingresar</button>
              <a href="#" class="link">¿Olvidaste tu contraseña?</a>
            </div>
          </form>
        </div>

        <!-- Registro -->
        <div class="card usuario-card">
          <h2>Crear Cuenta</h2>
          <form id="registro-form">
            <div class="form-group">
              <label for="nombre">Nombre Completo *</label>
              <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
              <label for="email-registro">Correo Electrónico *</label>
              <input type="email" id="email-registro" name="email" required>
            </div>
            <div class="form-group">
              <label for="password-registro">Contraseña *</label>
              <input type="password" id="password-registro" name="password" required>
            </div>
            <div class="form-group">
              <label for="confirm-password">Confirmar Contraseña *</label>
              <input type="password" id="confirm-password" name="confirm-password" required>
            </div>
            <div class="form-actions">
              <button type="submit" class="btn">Registrarse</button>
            </div>
          </form>
        </div>
      </div>
    </section>
  </main>

  <?php include 'includes/footer.php'; ?>

  <script>
    // Simulación de login
    document.getElementById('login-form').addEventListener('submit', function(e) {
      e.preventDefault();
      alert('¡Bienvenido de nuevo! (aquí iría la lógica de login con PHP/MySQL)');
      this.reset();
    });

    // Simulación de registro
    document.getElementById('registro-form').addEventListener('submit', function(e) {
      e.preventDefault();
      alert('¡Registro exitoso! (aquí iría la lógica de registro con PHP/MySQL)');
      this.reset();
    });
  </script>
</body>
</html>
