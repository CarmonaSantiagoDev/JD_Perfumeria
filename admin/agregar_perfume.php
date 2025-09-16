<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nuevo Perfume - Panel Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <header class="site-header">
        <div class="container header-inner">
            <a href="../index.php" class="logo">
                <img src="../img/logos/logo-jd.png" alt="JD Perfumería Logo" class="logo-img">
                <span class="logo-text">JD Perfumería</span>
            </a>
            </div>
    </header>

    <main class="container">
        <h2>Agregar Nuevo Perfume</h2>
        <form action="procesar_agregar_perfume.php" method="POST" enctype="multipart/form-data">
            <div>
                <label for="nombre">Nombre del Perfume:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            
            <div>
                <label for="marca_id">ID de la Marca:</label>
                <input type="number" id="marca_id" name="marca_id" required>
            </div>
            
            <div>
                <label for="categoria_id">ID de la Categoría:</label>
                <input type="number" id="categoria_id" name="categoria_id" required>
            </div>

            <div>
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion"></textarea>
            </div>
            
            <div>
                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" step="0.01" required>
            </div>

            <div>
                <label for="precio_oferta">Precio de Oferta:</label>
                <input type="number" id="precio_oferta" name="precio_oferta" step="0.01">
            </div>

            <div>
                <label for="stock">Cantidad en Stock:</label>
                <input type="number" id="stock" name="stock" required>
            </div>
            
            <div>
                <label for="stock_minimo">Stock Mínimo:</label>
                <input type="number" id="stock_minimo" name="stock_minimo">
            </div>

            <div>
                <label for="volumen">Volumen (ej: 100ml):</label>
                <input type="text" id="volumen" name="volumen">
            </div>

            <div>
                <label for="tipo">Tipo (ej: Eau de Parfum):</label>
                <input type="text" id="tipo" name="tipo">
            </div>

            <div>
                <label for="popularidad">Popularidad:</label>
                <input type="number" id="popularidad" name="popularidad">
            </div>

            <div>
                <label for="notas_olfativas">Notas Olfativas:</label>
                <textarea id="notas_olfativas" name="notas_olfativas"></textarea>
            </div>

            <div>
                <label for="genero">Género (masculino/femenino/unisex):</label>
                <select id="genero" name="genero" required>
                    <option value="masculino">Masculino</option>
                    <option value="femenino">Femenino</option>
                    <option value="unisex">Unisex</option>
                </select>
            </div>

            <div>
                <label for="temporada">Temporada:</label>
                <input type="text" id="temporada" name="temporada">
            </div>

            <div>
                <label for="activo">Activo:</label>
                <input type="checkbox" id="activo" name="activo" value="1" checked>
            </div>

            <div>
                <label for="destacado">Destacado:</label>
                <input type="checkbox" id="destacado" name="destacado" value="1">
            </div>

            <div>
                <label for="imagen_principal">Imagen Principal del Producto:</label>
                <input type="file" id="imagen_principal" name="imagen_principal" required>
            </div>
            
            <button type="submit">Agregar Perfume</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2025 JD Perfumería. Todos los derechos reservados.</p>
    </footer>

</body>
</html>