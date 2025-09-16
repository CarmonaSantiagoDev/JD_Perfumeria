# 🎭 JD Perfumería - Sitio Web Profesional

## 📋 Descripción del Proyecto

**JD Perfumería** es una página web moderna y profesional diseñada para una perfumería que permite a los clientes explorar un catálogo de perfumes exclusivos, realizar reservas y gestionar envíos de manera eficiente.

## ✨ Características Principales

### 🎨 **Diseño y UX**
- **Diseño moderno y elegante** con paleta de colores sofisticada
- **Completamente responsiva** para todos los dispositivos
- **Animaciones suaves** y transiciones profesionales
- **Tipografía elegante** con fuentes Google Fonts (Playfair Display + Montserrat)
- **Interfaz intuitiva** y fácil de navegar

### 🛍️ **Funcionalidades del Catálogo**
- **Catálogo dinámico** con 8+ perfumes premium
- **Sistema de filtros** por categoría (Masculino, Femenino, Unisex)
- **Ordenamiento inteligente** por nombre, precio y popularidad
- **Búsqueda en tiempo real** por nombre o marca
- **Sistema de favoritos** para guardar preferencias
- **Gestión de stock** en tiempo real

### 🛒 **Sistema de Carrito Avanzado**
- **Carrito persistente** con localStorage
- **Control de cantidades** con botones +/- 
- **Cálculo automático** de totales
- **Modal interactivo** para revisar compras
- **Sincronización** entre páginas

### 👤 **Sistema de Usuario Completo**
- **Formulario de compra** con validación en tiempo real
- **Gestión de datos** de envío y contacto
- **Historial de compras** con estados de seguimiento
- **Confirmaciones automáticas** de pedidos
- **Números de compra únicos** para tracking

### 📱 **Responsividad y Performance**
- **Mobile-first design** optimizado para móviles
- **Lazy loading** de imágenes para mejor performance
- **CSS Grid y Flexbox** para layouts modernos
- **Optimización SEO** con metadatos apropiados

## 🚀 Tecnologías Utilizadas

- **HTML5** - Estructura semántica moderna
- **CSS3** - Estilos avanzados con variables CSS y Grid/Flexbox
- **JavaScript ES6+** - Funcionalidades interactivas y gestión de estado
- **LocalStorage** - Persistencia de datos del usuario
- **Google Fonts** - Tipografías premium
- **Responsive Design** - Adaptable a todos los dispositivos

## 📁 Estructura del Proyecto

```
JD_Perfumeria/
├── index.html              # Página principal con hero y características
├── catalogo.html           # Catálogo de perfumes con filtros
├── usuario.html            # Panel de usuario y formulario de compra
├── about.html              # Página "Acerca de nosotros"
├── contacto.html           # Página de contacto
├── css/
│   └── style.css          # Estilos principales del sitio
├── js/
│   ├── script.js          # Lógica del catálogo y carrito
│   ├── usuario.js         # Sistema de usuario y compras
│   └── admin.js           # Panel de administración
├── img/                    # Imágenes de perfumes y assets
└── README.md              # Este archivo de documentación
```

## 🎯 Funcionalidades Implementadas

### **Página Principal (index.html)**
- ✅ Hero section atractivo con call-to-action
- ✅ Sección de características principales
- ✅ Categorías destacadas con enlaces directos
- ✅ Testimonios de clientes
- ✅ Footer completo con información de contacto

### **Catálogo (catalogo.html)**
- ✅ Grid responsivo de productos
- ✅ Sistema de filtros por categoría
- ✅ Ordenamiento por precio y popularidad
- ✅ Búsqueda en tiempo real
- ✅ Carrito modal interactivo
- ✅ Sistema de favoritos

### **Usuario (usuario.html)**
- ✅ Panel de carrito persistente
- ✅ Formulario de compra completo
- ✅ Validación de campos en tiempo real
- ✅ Historial de compras
- ✅ Confirmaciones de pedido
- ✅ Gestión de estados de compra

## 🔧 Instalación y Uso

### **Requisitos**
- Navegador web moderno (Chrome, Firefox, Safari, Edge)
- Servidor web local (opcional, para desarrollo)

### **Instalación**
1. Clona o descarga el proyecto
2. Abre `index.html` en tu navegador
3. ¡Listo para usar!

### **Desarrollo Local**
```bash
# Si tienes Python instalado
python -m http.server 8000

# Si tienes Node.js instalado
npx serve .

# Si tienes PHP instalado
php -S localhost:8000
```

## 🎨 Personalización

### **Colores del Tema**
El sitio utiliza variables CSS para fácil personalización:

```css
:root {
  --primary-color: #1a1a1a;      /* Color principal */
  --secondary-color: #d4af37;     /* Color dorado */
  --accent-color: #f8f8f8;       /* Color de fondo */
  --text-color: #333;            /* Color de texto */
  --white: #ffffff;              /* Color blanco */
}
```

### **Agregar Nuevos Perfumes**
Edita el array `perfumes` en `js/script.js`:

```javascript
const perfumes = [
  {
    id: 9,
    nombre: "Nuevo Perfume",
    marca: "Marca",
    precio: 1200,
    stock: 10,
    img: "img/nuevo-perfume.jpg",
    categoria: "masculino",
    descripcion: "Descripción del perfume",
    popularidad: 4
  }
  // ... más perfumes
];
```

## 📱 Responsividad

El sitio está optimizado para:
- **Desktop** (1200px+)
- **Tablet** (768px - 1199px)
- **Mobile** (320px - 767px)

## 🔒 Seguridad y Privacidad

- **Datos locales**: Toda la información se almacena en el navegador del usuario
- **Sin cookies**: No se utilizan cookies de tracking
- **Validación**: Formularios con validación del lado del cliente
- **Sanitización**: Entradas de usuario validadas y sanitizadas

## 🚀 Próximas Mejoras

### **Funcionalidades Planificadas**
- [ ] Sistema de autenticación de usuarios
- [ ] Panel de administración completo
- [ ] Base de datos para persistencia
- [ ] Sistema de pagos online
- [ ] Notificaciones por email
- [ ] API REST para integración
- [ ] PWA (Progressive Web App)

### **Optimizaciones Técnicas**
- [ ] Lazy loading de imágenes
- [ ] Service Workers para offline
- [ ] Compresión de assets
- [ ] CDN para recursos estáticos
- [ ] Analytics y métricas

## 🤝 Contribución

### **Cómo Contribuir**
1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

### **Reportar Bugs**
- Usa el sistema de Issues de GitHub
- Describe el problema detalladamente
- Incluye pasos para reproducir
- Adjunta capturas de pantalla si es necesario

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 👨‍💻 Autor

**JD Perfumería**
- 📧 Email: info@jdperfumeria.com
- 📱 Teléfono: +54 11 1234-5678
- 📍 Ubicación: Buenos Aires, Argentina

## 🙏 Agradecimientos

- **Google Fonts** por las tipografías premium
- **Comunidad de desarrolladores** por el conocimiento compartido
- **Clientes** por la confianza en JD Perfumería

---

## 📞 Contacto

¿Tienes preguntas o sugerencias? ¡Nos encantaría escucharte!

- **Website**: [jdperfumeria.com](https://jdperfumeria.com)
- **Email**: info@jdperfumeria.com
- **Teléfono**: +54 11 1234-5678

---

*Desarrollado con ❤️ para JD Perfumería* 