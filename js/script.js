// Variable para almacenar los datos del catálogo
let catalogoData = [];

// Función para obtener los datos del catálogo desde la API
async function obtenerCatalogoData() {
  const response = await fetch('http://localhost/jd_perfumeria/api/perfumes.php');
  return await response.json();
}

// Función para renderizar el catálogo completo
async function renderizarCatalogo(filtro = null) {
    const catalogoContainer = document.getElementById('catalogo-container');
    catalogoContainer.innerHTML = ''; // Limpia el contenedor para evitar duplicaciones

    // Si los datos aún no se han cargado, los obtiene de la API
    if (catalogoData.length === 0) {
        try {
            const response = await fetch('http://localhost/jd_perfumeria/api/perfumes.php');
            const result = await response.json();
            if (result.success) {
                catalogoData = result.data;
            } else {
                console.error('Error al obtener los datos de la API:', result.message);
                return;
            }
        } catch (error) {
            console.error('Error de conexión a la API:', error);
            // Implementa un sistema de respaldo si lo necesitas, como hiciste antes.
            return;
        }
    }

    // Filtra los datos si se aplica un filtro
    let productosAMostrar = catalogoData;
    if (filtro) {
        // Lógica de filtrado
        productosAMostrar = catalogoData.filter(perfume => perfume.categoria === filtro);
    }

    // Renderiza cada producto
    productosAMostrar.forEach(producto => {
        const perfumeCard = document.createElement('div');
        perfumeCard.classList.add('perfume-card');
        perfumeCard.innerHTML = `
            <h3>${producto.nombre}</h3>
            <p>Categoría: ${producto.categoria}</p>
            <p>Precio: $${producto.precio}</p>
        `;
        catalogoContainer.appendChild(perfumeCard);
    });
}

// Llama a la función principal una sola vez cuando la página cargue
document.addEventListener('DOMContentLoaded', () => {
    renderizarCatalogo();
});