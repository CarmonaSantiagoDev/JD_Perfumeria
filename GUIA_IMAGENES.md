# 🖼️ Guía Completa para Agregar Imágenes a JD Perfumería

## 📁 **Paso 1: Crear la Estructura de Carpetas**

Crea estas carpetas en tu proyecto:

```
JD_Perfumeria/
├── img/
│   ├── perfumes/           # Imágenes de los perfumes
│   ├── backgrounds/        # Imágenes de fondo
│   ├── logos/             # Logos y branding
│   └── icons/             # Iconos adicionales
```

## 🎯 **Paso 2: Imágenes de Perfumes**

### **Ubicación:** `img/perfumes/`

### **Nombres de archivo requeridos:**
- `aqua-di-gio.jpg` - Para Aqua Di Gio
- `chanel-n5.jpg` - Para Chanel Nº5
- `dior-sauvage.jpg` - Para Dior Sauvage
- `miss-dior.jpg` - Para Miss Dior
- `bleu-chanel.jpg` - Para Bleu de Chanel
- `la-vie-est-belle.jpg` - Para La Vie Est Belle
- `acqua-di-parma.jpg` - Para Acqua di Parma
- `black-opium.jpg` - Para Black Opium

### **Especificaciones técnicas:**
- **Formato:** .jpg, .png o .webp
- **Tamaño recomendado:** 400x400 píxeles
- **Peso máximo:** 200KB por imagen
- **Calidad:** Alta (para mostrar detalles del producto)

### **Cómo obtener las imágenes:**
1. **Fotos propias:** Toma fotos de los perfumes reales
2. **Stock photos:** Usa sitios como Unsplash, Pexels
3. **Fabricantes:** Descarga desde sitios oficiales (si tienes autorización)
4. **Crear placeholders:** Usa herramientas online para generar imágenes

## 🌅 **Paso 3: Imágenes de Fondo**

### **Ubicación:** `img/backgrounds/`

### **Imágenes necesarias:**

#### **Hero Background (`hero-bg.jpg`)**
- **Tamaño:** 1920x1080 píxeles mínimo
- **Contenido sugerido:** 
  - Perfumes elegantes en mesa
  - Textura de mármol o madera
  - Ambiente sofisticado y lujoso
- **Peso máximo:** 500KB

#### **Categorías:**
- **`masculino-bg.jpg`** (400x350px) - Estilo masculino, colores azules/grises
- **`femenino-bg.jpg`** (400x350px) - Estilo femenino, colores rosas/púrpuras  
- **`unisex-bg.jpg`** (400x350px) - Estilo neutro, colores morados/verdes

#### **Call to Action (`cta-bg.jpg`)**
- **Tamaño:** 1200x400 píxeles mínimo
- **Contenido sugerido:** Perfumes en primer plano con fondo desenfocado

## 🎭 **Paso 4: Logo Personalizado**

### **Ubicación:** `img/logos/`

### **Archivos necesarios:**
- **`logo-jd.png`** - Logo principal (200x200px)
- **`favicon.ico`** - Icono del navegador (32x32px)

### **Especificaciones del logo:**
- **Formato:** .png (con transparencia)
- **Tamaño:** 200x200 píxeles
- **Estilo:** Elegante, relacionado con perfumes
- **Colores:** Que combinen con el tema del sitio

## 🔧 **Paso 5: Incluir el CSS Personalizado**

Agrega esta línea en el `<head>` de todos tus archivos HTML:

```html
<link rel="stylesheet" href="css/custom-images.css">
```

### **Ejemplo completo:**
```html
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>JD Perfumería</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/custom-images.css">
  <link rel="icon" type="image/x-icon" href="img/logos/favicon.ico">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Montserrat&display=swap" rel="stylesheet">
</head>
```

## 🎨 **Paso 6: Personalizar Colores y Estilos**

### **Editar `css/custom-images.css`:**

```css
/* Cambiar imagen de fondo del hero */
.hero {
  background: var(--gradient-hero), 
              url('../img/backgrounds/tu-imagen-hero.jpg') no-repeat center/cover;
}

/* Cambiar logo */
.logo-icon {
  background-image: url('../img/logos/tu-logo.png');
}
```

## 📱 **Paso 7: Optimización para Móviles**

### **Responsive Images:**
- **Desktop:** Imágenes de alta resolución
- **Tablet:** Reducir a 70% del tamaño original
- **Mobile:** Reducir a 50% del tamaño original

### **Lazy Loading:**
El sitio ya incluye lazy loading automático para mejor performance.

## 🛠️ **Paso 8: Herramientas Recomendadas**

### **Para editar imágenes:**
- **Gratuitas:** GIMP, Paint.NET, Canva
- **Online:** Pixlr, Fotor, BeFunky
- **Profesionales:** Photoshop, Affinity Photo

### **Para optimizar:**
- **Compresión:** TinyPNG, ImageOptim
- **Redimensionar:** Bulk Resize Photos, ResizeImage.net

### **Para crear placeholders:**
- **Generadores:** Placeholder.com, Lorem Picsum
- **Diseño:** Canva, Figma

## ✅ **Paso 9: Verificación**

### **Checklist de verificación:**
- [ ] Todas las carpetas están creadas
- [ ] Imágenes de perfumes están en `img/perfumes/`
- [ ] Imágenes de fondo están en `img/backgrounds/`
- [ ] Logo está en `img/logos/`
- [ ] CSS personalizado está incluido en todos los HTML
- [ ] Favicon está configurado
- [ ] Imágenes se muestran correctamente
- [ ] Sitio funciona en móvil y desktop

## 🚀 **Paso 10: Agregar Nuevos Perfumes**

### **Para agregar un nuevo perfume:**

1. **Coloca la imagen** en `img/perfumes/`
2. **Actualiza el array** en `js/script.js`:

```javascript
{
  id: 9,
  nombre: "Nuevo Perfume",
  marca: "Marca",
  precio: 1200,
  stock: 10,
  img: "img/perfumes/nuevo-perfume.jpg", // 📸 Tu nueva imagen
  categoria: "masculino",
  descripcion: "Descripción del perfume",
  popularidad: 4,
  volumen: "100ml",
  tipo: "Eau de Toilette"
}
```

## 💡 **Consejos Adicionales**

### **Calidad de imagen:**
- **Perfumes:** Usa fotos profesionales o de alta calidad
- **Fondos:** Evita imágenes muy complejas que distraigan del contenido
- **Logos:** Mantén consistencia con tu branding

### **Performance:**
- Comprime todas las imágenes antes de subirlas
- Usa formatos modernos como .webp cuando sea posible
- Considera usar diferentes tamaños para diferentes dispositivos

### **SEO:**
- Nombra los archivos descriptivamente
- Incluye alt text en las imágenes
- Usa nombres que describan el contenido

## 🆘 **Solución de Problemas**

### **Imagen no se muestra:**
1. Verifica la ruta del archivo
2. Asegúrate de que el nombre coincida exactamente
3. Verifica que el archivo existe en la carpeta correcta

### **Imagen se ve pixelada:**
1. Usa una imagen de mayor resolución
2. Verifica que no se esté estirando demasiado

### **Sitio se ve lento:**
1. Comprime las imágenes
2. Reduce el tamaño de los archivos
3. Usa lazy loading (ya implementado)

---

## 🎉 **¡Listo!**

Siguiendo esta guía, tendrás JD Perfumería completamente personalizado con tus propias imágenes. El sitio mantendrá su funcionalidad profesional mientras refleja tu estilo único.

**¿Necesitas ayuda con algún paso específico?** ¡No dudes en preguntar! 