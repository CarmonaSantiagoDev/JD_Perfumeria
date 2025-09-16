<?php
// Configuración de la base de datos (¡Asegúrate de ajustar estos valores!)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jd_perfumeria"; // Nombre de tu BD según el archivo SQL

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir datos del formulario
$nombre = $_POST['nombre'];
$marca_id = $_POST['marca_id'];
$categoria_id = $_POST['categoria_id'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$precio_oferta = $_POST['precio_oferta'] ?: NULL;
$stock = $_POST['stock'];
$stock_minimo = $_POST['stock_minimo'] ?: 0;
$volumen = $_POST['volumen'];
$tipo = $_POST['tipo'];
$popularidad = $_POST['popularidad'] ?: 0;
$notas_olfativas = $_POST['notas_olfativas'];
$genero = $_POST['genero'];
$temporada = $_POST['temporada'];
$activo = isset($_POST['activo']) ? 1 : 0;
$destacado = isset($_POST['destacado']) ? 1 : 0;

// Manejar la subida de la imagen
$target_dir = "../img/perfumes/";
$imageFileType = strtolower(pathinfo($_FILES["imagen_principal"]["name"], PATHINFO_EXTENSION));
$target_file = $target_dir . basename(uniqid() . "." . $imageFileType);
$uploadOk = 1;

// Verificar si el archivo es una imagen real
$check = getimagesize($_FILES["imagen_principal"]["tmp_name"]);
if($check === false) {
    echo "El archivo no es una imagen.";
    $uploadOk = 0;
}

// Verificar que el archivo no sea demasiado grande
if ($_FILES["imagen_principal"]["size"] > 5000000) { // 5MB
    echo "Lo siento, tu archivo es demasiado grande.";
    $uploadOk = 0;
}

// Permitir ciertos formatos de archivo
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
    echo "Lo siento, solo se permiten archivos JPG, JPEG, PNG & GIF.";
    $uploadOk = 0;
}

// Si la subida fue exitosa, insertar los datos en la base de datos
if ($uploadOk == 1) {
    if (move_uploaded_file($_FILES["imagen_principal"]["tmp_name"], $target_file)) {
        // La URL de la imagen que se guardará en la base de datos
        $imagen_url = str_replace("../", "", $target_file); 
        
        // Preparar la consulta SQL
        $sql = "INSERT INTO perfumes (nombre, marca_id, categoria_id, descripcion, precio, precio_oferta, stock, stock_minimo, volumen, tipo, popularidad, imagen_principal, notas_olfativas, genero, temporada, activo, destacado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siisddiisssisssii", 
            $nombre, $marca_id, $categoria_id, $descripcion, $precio, $precio_oferta, $stock, $stock_minimo, $volumen, $tipo, $popularidad, $imagen_url, $notas_olfativas, $genero, $temporada, $activo, $destacado);

        if ($stmt->execute()) {
            echo "Nuevo perfume agregado exitosamente.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Hubo un error al subir tu archivo.";
    }
} else {
    echo "El archivo no pudo ser subido.";
}

$conn->close();
?>