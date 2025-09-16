<?php
// login.php - Versión mejorada y corregida
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si ya está logueado, redirigir al inicio
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

// Procesar formulario solo si se envió
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Configuración de la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "jd_perfumeria";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        $_SESSION['error'] = "Error de conexión a la base de datos. Contacte al administrador.";
        header("Location: login.php");
        exit();
    }

    // Obtener y limpiar datos
    $email = trim($conn->real_escape_string($_POST['email']));
    $password_input = $_POST['password'];

    // Validar campos
    if (empty($email) || empty($password_input)) {
        $_SESSION['error'] = "Por favor, complete todos los campos";
        header("Location: login.php");
        exit();
    }

    // Preparar consulta
    $query = 'SELECT id, nombre, password_hash, rol FROM usuarios WHERE email = ?';
    $stmt = $conn->prepare($query);
    
    if ($stmt === false) {
        $_SESSION['error'] = "Error en el sistema. Intente más tarde.";
        header("Location: login.php");
        exit();
    }

    // Vincular y ejecutar
    $stmt->bind_param("s", $email);
    
    if (!$stmt->execute()) {
        $_SESSION['error'] = "Error en la autenticación. Intente nuevamente.";
        header("Location: login.php");
        exit();
    }

    // Obtener resultado
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verificar contraseña
        if (password_verify($password_input, $user['password_hash'])) {
            // Establecer variables de sesión CORRECTAMENTE
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['usuario_nombre'] = $user['nombre'];
            $_SESSION['rol'] = $user['rol']; // ← ¡IMPORTANTE! Debe ser 'rol' no 'roll'
            $_SESSION['usuario_email'] = $email;
            
            // Regenerar ID de sesión por seguridad
            session_regenerate_id(true);
            
            // Redirigir según el rol
            if ($user['rol'] == 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        }
    }
    
    // Si llegamos aquí, las credenciales son incorrectas
    $_SESSION['error'] = "Credenciales incorrectas";
    header("Location: login.php");
    exit();
}

// Obtener mensaje de error si existe
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - JD Perfumería</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0b1736;
            --primary-light: #1e3a8a;
            --accent: #3b82f6;
            --text-light: #ffffff;
            --text-dark: #333333;
            --background: #f8fafc;
            --success: #10b981;
            --danger: #ef4444;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #0b1736 0%, #1e3a8a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        
        .login-header {
            background: var(--primary);
            color: var(--text-light);
            text-align: center;
            padding: 30px 20px;
        }
        
        .login-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .login-header p {
            opacity: 0.9;
            font-size: 16px;
        }
        
        .login-form {
            padding: 40px 30px 30px;
        }
        
        .error-message {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: center;
            border-left: 4px solid var(--danger);
            font-weight: 500;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--primary);
            font-size: 15px;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            font-size: 18px;
        }
        
        .input-with-icon input {
            width: 100%;
            padding: 16px 20px 16px 50px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            color: var(--text-dark);
        }
        
        .input-with-icon input:focus {
            border-color: var(--accent);
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        
        .btn-login {
            width: 100%;
            padding: 16px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 17px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            background: var(--primary-light);
        }
        
        .login-footer {
            text-align: center;
            padding: 25px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 15px;
            background: #f8fafc;
        }
        
        .login-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-footer a:hover {
            color: var(--accent);
            text-decoration: underline;
        }
        
        @media (max-width: 480px) {
            .login-container {
                margin: 10px;
                border-radius: 12px;
            }
            
            .login-header {
                padding: 25px 15px;
            }
            
            .login-header h1 {
                font-size: 24px;
            }
            
            .login-form {
                padding: 30px 20px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>JD Perfumería</h1>
            <p>Iniciar sesión en tu cuenta</p>
        </div>
        
        <div class="login-form">
            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" required placeholder="usuario@ejemplo.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" required placeholder="Ingresa tu contraseña">
                    </div>
                </div>
                
                <button type="submit" class="btn-login">Iniciar sesión</button>
            </form>
        </div>
        
        <div class="login-footer">
            <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
            <p><small><a href="test_conexion.php" target="_blank">Verificar conexión a base de datos</a></small></p>
        </div>
    </div>
</body>
</html>