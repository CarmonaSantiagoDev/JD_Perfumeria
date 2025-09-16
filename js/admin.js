const perfumes = JSON.parse(localStorage.getItem("perfumes")) || [
  { nombre: "Aqua Di Gio", precio: 1200, stock: 10, img: "img/aqua.jpg" },
  { nombre: "Chanel Nº5", precio: 1500, stock: 5, img: "img/chanel.jpg" },
  { nombre: "Sauvage", precio: 1300, stock: 8, img: "img/dior.jpg" }
];

// Mostrar catálogo dinámicamente
function mostrarCatalogo() {
  const catalogo = document.getElementById("catalogo");
  if (!catalogo) return;
  catalogo.innerHTML = "";
  perfumes.forEach((p, i) => {
    catalogo.innerHTML += `
      <div class="card">
        <img src="${p.img}" alt="${p.nombre}" width="150">
        <h3>${p.nombre}</h3>
        <p>Precio: $${p.precio}</p>
        <p>Stock: ${p.stock}</p>
        <button class="add-cart" data-index="${i}">Añadir al carrito</button>
      </div>
    `;
  });
  document.querySelectorAll(".add-cart").forEach(btn => {
    btn.onclick = () => {
      const idx = btn.getAttribute("data-index");
      if (perfumes[idx].stock > 0) {
        alert(`${perfumes[idx].nombre} añadido al carrito`);
        perfumes[idx].stock--;
        mostrarCatalogo();
      } else {
        alert("Sin stock");
      }
    };
  });
}

document.addEventListener("DOMContentLoaded", mostrarCatalogo);