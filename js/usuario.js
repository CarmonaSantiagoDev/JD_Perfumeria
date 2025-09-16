// Sistema de usuario mejorado para JD Perfumería
class UsuarioPerfumeria {
  constructor() {
    this.carrito = JSON.parse(localStorage.getItem("carrito")) || [];
    this.historial = JSON.parse(localStorage.getItem("historialCompras")) || [];
    this.init();
  }

  init() {
    this.mostrarCarrito();
    this.mostrarHistorial();
    this.configurarEventListeners();
    this.validarCarrito();
  }

  // Mostrar items del carrito
  mostrarCarrito() {
    const carritoContainer = document.getElementById("carrito-items");
    const totalElement = document.getElementById("total-carrito");
    
    if (!carritoContainer) return;

    if (this.carrito.length === 0) {
      carritoContainer.innerHTML = `
        <div class="carrito-vacio">
          <p>🛒 Tu carrito está vacío</p>
          <a href="catalogo.html" class="btn-ir-catalogo">Ir al catálogo</a>
        </div>
      `;
      totalElement.textContent = "0";
      return;
    }

    carritoContainer.innerHTML = this.carrito.map(item => `
      <div class="carrito-item-usuario">
        <img src="${item.img}" alt="${item.nombre}" onerror="this.src='img/placeholder.jpg'">
        <div class="item-info-usuario">
          <h4>${item.nombre}</h4>
          <p class="marca">${item.marca}</p>
          <p class="precio">$${item.precio.toLocaleString()}</p>
        </div>
        <div class="cantidad-controls-usuario">
          <button class="btn-cantidad-usuario" onclick="usuario.cambiarCantidad(${item.id}, -1)">-</button>
          <span class="cantidad">${item.cantidad}</span>
          <button class="btn-cantidad-usuario" onclick="usuario.cambiarCantidad(${item.id}, 1)">+</button>
        </div>
        <button class="btn-eliminar-usuario" onclick="usuario.eliminarDelCarrito(${item.id})">🗑️</button>
      </div>
    `).join("");

    totalElement.textContent = this.calcularTotal().toLocaleString();
  }

  // Cambiar cantidad de items
  cambiarCantidad(id, cambio) {
    const item = this.carrito.find(item => item.id === id);
    if (item) {
      item.cantidad += cambio;
      if (item.cantidad <= 0) {
        this.eliminarDelCarrito(id);
      } else {
        this.guardarCarrito();
        this.mostrarCarrito();
      }
    }
  }

  // Eliminar item del carrito
  eliminarDelCarrito(id) {
    this.carrito = this.carrito.filter(item => item.id !== id);
    this.guardarCarrito();
    this.mostrarCarrito();
    this.mostrarNotificacion("Item eliminado del carrito", "info");
  }

  // Calcular total del carrito
  calcularTotal() {
    return this.carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
  }

  // Guardar carrito en localStorage
  guardarCarrito() {
    localStorage.setItem("carrito", JSON.stringify(this.carrito));
  }

  // Validar que el carrito tenga items
  validarCarrito() {
    if (this.carrito.length === 0) {
      const btnFinalizar = document.querySelector('.btn-principal');
      if (btnFinalizar) {
        btnFinalizar.disabled = true;
        btnFinalizar.textContent = "Carrito vacío";
      }
    }
  }

  // Mostrar historial de compras
  mostrarHistorial() {
    const historialContainer = document.getElementById("historial-items");
    if (!historialContainer) return;

    if (this.historial.length === 0) {
      historialContainer.innerHTML = `
        <div class="historial-vacio">
          <p>📦 Aún no tienes compras realizadas</p>
        </div>
      `;
      return;
    }

    historialContainer.innerHTML = this.historial.map((compra, index) => `
      <div class="historial-item">
        <div class="historial-header">
          <h4>Compra #${index + 1}</h4>
          <span class="fecha">${new Date(compra.fecha).toLocaleDateString('es-ES')}</span>
        </div>
        <div class="historial-items">
          ${compra.items.map(item => `
            <div class="historial-item-producto">
              <span>${item.nombre} x${item.cantidad}</span>
              <span class="precio">$${item.precio.toLocaleString()}</span>
            </div>
          `).join("")}
        </div>
        <div class="historial-total">
          <strong>Total: $${compra.total.toLocaleString()}</strong>
          <span class="estado ${compra.estado}">${this.getEstadoText(compra.estado)}</span>
        </div>
      </div>
    `).join("");
  }

  // Obtener texto del estado
  getEstadoText(estado) {
    const estados = {
      'pendiente': '⏳ Pendiente',
      'procesando': '🔄 Procesando',
      'enviado': '📦 Enviado',
      'entregado': '✅ Entregado'
    };
    return estados[estado] || estado;
  }

  // Configurar event listeners
  configurarEventListeners() {
    const formulario = document.getElementById("formulario-envio");
    const btnLimpiar = document.getElementById("btn-limpiar");

    if (formulario) {
      formulario.addEventListener("submit", (e) => this.procesarCompra(e));
    }

    if (btnLimpiar) {
      btnLimpiar.addEventListener("click", () => this.limpiarFormulario());
    }

    // Validación en tiempo real
    this.configurarValidacion();
  }

  // Configurar validación del formulario
  configurarValidacion() {
    const inputs = document.querySelectorAll('input[required], textarea[required], select[required]');
    
    inputs.forEach(input => {
      input.addEventListener('blur', () => this.validarCampo(input));
      input.addEventListener('input', () => this.removerError(input));
    });
  }

  // Validar campo individual
  validarCampo(campo) {
    const valor = campo.value.trim();
    let esValido = true;
    let mensaje = '';

    switch (campo.type) {
      case 'email':
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        esValido = emailRegex.test(valor);
        mensaje = 'Ingresa un email válido';
        break;
      
      case 'tel':
        const telefonoRegex = /^[\d\s\-\+\(\)]{8,}$/;
        esValido = telefonoRegex.test(valor);
        mensaje = 'Ingresa un teléfono válido';
        break;
      
      default:
        esValido = valor.length > 0;
        mensaje = 'Este campo es requerido';
    }

    if (!esValido) {
      this.mostrarError(campo, mensaje);
    }

    return esValido;
  }

  // Mostrar error en campo
  mostrarError(campo, mensaje) {
    this.removerError(campo);
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-mensaje';
    errorDiv.textContent = mensaje;
    
    campo.parentNode.appendChild(errorDiv);
    campo.classList.add('error');
  }

  // Remover error del campo
  removerError(campo) {
    const errorDiv = campo.parentNode.querySelector('.error-mensaje');
    if (errorDiv) {
      errorDiv.remove();
    }
    campo.classList.remove('error');
  }

  // Procesar compra
  procesarCompra(e) {
    e.preventDefault();
    
    if (this.carrito.length === 0) {
      this.mostrarNotificacion("Tu carrito está vacío", "error");
      return;
    }

    // Validar todos los campos
    const campos = document.querySelectorAll('input[required], textarea[required], select[required]');
    let formularioValido = true;

    campos.forEach(campo => {
      if (!this.validarCampo(campo)) {
        formularioValido = false;
      }
    });

    if (!formularioValido) {
      this.mostrarNotificacion("Por favor completa todos los campos requeridos", "error");
      return;
    }

    // Recopilar datos del formulario
    const datosCompra = {
      cliente: {
        nombre: document.getElementById("nombre").value,
        email: document.getElementById("email").value,
        telefono: document.getElementById("telefono").value,
        direccion: document.getElementById("direccion").value,
        ciudad: document.getElementById("ciudad").value,
        codigoPostal: document.getElementById("codigo-postal").value,
        metodoPago: document.getElementById("metodo-pago").value,
        notas: document.getElementById("notas").value
      },
      items: [...this.carrito],
      total: this.calcularTotal(),
      fecha: new Date().toISOString(),
      estado: 'pendiente',
      numeroCompra: this.generarNumeroCompra()
    };

    // Guardar en historial
    this.historial.unshift(datosCompra);
    localStorage.setItem("historialCompras", JSON.stringify(this.historial));

    // Limpiar carrito
    this.carrito = [];
    this.guardarCarrito();

    // Mostrar confirmación
    this.mostrarConfirmacionCompra(datosCompra);

    // Limpiar formulario
    this.limpiarFormulario();

    // Actualizar vista
    this.mostrarCarrito();
    this.mostrarHistorial();
  }

  // Generar número de compra
  generarNumeroCompra() {
    const fecha = new Date();
    const año = fecha.getFullYear();
    const mes = String(fecha.getMonth() + 1).padStart(2, '0');
    const dia = String(fecha.getDate()).padStart(2, '0');
    const hora = String(fecha.getHours()).padStart(2, '0');
    const minuto = String(fecha.getMinutes()).padStart(2, '0');
    
    return `JD-${año}${mes}${dia}-${hora}${minuto}`;
  }

  // Mostrar confirmación de compra
  mostrarConfirmacionCompra(datosCompra) {
    const modal = document.createElement("div");
    modal.className = "modal-confirmacion";
    modal.innerHTML = `
      <div class="modal-content-confirmacion">
        <div class="confirmacion-header">
          <h3>🎉 ¡Compra Realizada con Éxito!</h3>
          <button class="cerrar-confirmacion">×</button>
        </div>
        
        <div class="confirmacion-body">
          <p><strong>Número de compra:</strong> ${datosCompra.numeroCompra}</p>
          <p><strong>Total:</strong> $${datosCompra.total.toLocaleString()}</p>
          <p><strong>Estado:</strong> ${this.getEstadoText(datosCompra.estado)}</p>
          
          <div class="confirmacion-items">
            <h4>Productos:</h4>
            ${datosCompra.items.map(item => `
              <p>• ${item.nombre} x${item.cantidad} - $${item.precio.toLocaleString()}</p>
            `).join("")}
          </div>
          
          <div class="confirmacion-envio">
            <h4>Datos de envío:</h4>
            <p>${datosCompra.cliente.nombre}</p>
            <p>${datosCompra.cliente.direccion}</p>
            <p>${datosCompra.cliente.ciudad}, ${datosCompra.cliente.codigoPostal}</p>
            <p>📧 ${datosCompra.cliente.email} | 📱 ${datosCompra.cliente.telefono}</p>
          </div>
          
          <p class="confirmacion-mensaje">
            Te enviaremos un email de confirmación y te contactaremos para coordinar el envío.
          </p>
        </div>
        
        <div class="confirmacion-actions">
          <button class="btn-principal" onclick="window.location.href='index.html'">Volver al inicio</button>
        </div>
      </div>
    `;

    document.body.appendChild(modal);

    // Event listeners
    modal.querySelector(".cerrar-confirmacion").addEventListener("click", () => modal.remove());
    modal.addEventListener("click", (e) => {
      if (e.target === modal) modal.remove();
    });
  }

  // Limpiar formulario
  limpiarFormulario() {
    const formulario = document.getElementById("formulario-envio");
    if (formulario) {
      formulario.reset();
      
      // Remover errores
      const campos = formulario.querySelectorAll('input, textarea, select');
      campos.forEach(campo => {
        campo.classList.remove('error');
        const errorDiv = campo.parentNode.querySelector('.error-mensaje');
        if (errorDiv) errorDiv.remove();
      });
    }
  }

  // Mostrar notificación
  mostrarNotificacion(mensaje, tipo = "info") {
    const notificacion = document.createElement("div");
    notificacion.className = `notificacion ${tipo}`;
    notificacion.textContent = mensaje;
    
    document.body.appendChild(notificacion);
    
    setTimeout(() => notificacion.classList.add("mostrar"), 100);
    
    setTimeout(() => {
      notificacion.classList.remove("mostrar");
      setTimeout(() => notificacion.remove(), 300);
    }, 3000);
  }
}

// Inicializar sistema de usuario
let usuario;
document.addEventListener("DOMContentLoaded", () => {
  usuario = new UsuarioPerfumeria();
});