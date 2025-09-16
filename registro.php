<?php
// Asegurarse de que la sesión se inicie si es necesario
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jd_perfumeria";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Lógica de registro si el formulario fue enviado
$mensaje = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $telefono = $_POST['telefono'] ?? null;
    $direccion = $_POST['direccion'] ?? null;
    $ciudad = $_POST['ciudad'] ?? null;
    $codigo_postal = $_POST['codigo_postal'] ?? null;
    $pais = $_POST['pais'] ?? null;

    // Validar datos
    if (empty($nombre) || empty($email) || empty($password)) {
        $mensaje = 'Por favor, completa los campos obligatorios: Nombre, Email y Contraseña.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = 'El formato del email no es válido.';
    } else {
        // Encriptar la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Verificar si el email ya existe
        $sql = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $mensaje = 'Este email ya está registrado.';
        } else {
            // Insertar nuevo usuario en la base de datos
            // La columna 'rol' se establece por defecto a 'cliente'
            // La columna 'password_hash' se usa para almacenar la contraseña encriptada
            $sql = "INSERT INTO usuarios (nombre, email, password_hash, telefono, direccion, ciudad, codigo_postal, pais) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssis", $nombre, $email, $hashed_password, $telefono, $direccion, $ciudad, $codigo_postal, $pais);

            if ($stmt->execute()) {
                $mensaje = '¡Registro exitoso! Ahora puedes iniciar sesión.';
            } else {
                $mensaje = 'Error al registrar el usuario: ' . $stmt->error;
            }
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - JD Perfumería</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <main class="container">
        <h2>Registro de Usuario</h2>
        <?php if (!empty($mensaje)): ?>
            <p class="mensaje-alerta"><?php echo $mensaje; ?></p>
        <?php endif; ?>
        <form action="registro.php" method="POST" class="form-style">
            <div class="form-group">
                <label for="nombre">Nombre Completo:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono">
            </div>
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion">
            </div>
            <div class="form-group">
                <label for="ciudad">Ciudad:</label>
                <input type="text" id="ciudad" name="ciudad">
            </div>
            <div class="form-group">
                <label for="codigo_postal">Código Postal:</label>
                <input type="text" id="codigo_postal" name="codigo_postal">
            </div>
            <div class="form-group">
                <label for="pais">País:</label>
                <input type="text" id="pais" name="pais">
            </div>
            <button type="submit" class="btn">Registrarse</button>
        </form>
    </main>

    <?php include 'includes/footer.php'; ?>

    <style>
        /* Estilos específicos para el formulario de registro */
        .form-style {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: var(--radius);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: var(--radius);
            box-sizing: border-box; /* Para que el padding no afecte el ancho total */
        }
        
        .btn {
            width: 100%;
            text-align: center;
        }

        .mensaje-alerta {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: var(--radius);
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            text-align: center;
        }
    </style>

</body>
</html>