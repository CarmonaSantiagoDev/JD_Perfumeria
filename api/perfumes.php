<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Configuración de la base de datos
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "jd_perfumeria";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Conexión a la base de datos fallida']);
    exit;
}

// Verificar que se proporcionó un ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de perfume no proporcionado']);
    exit;
}

$perfume_id = intval($_GET['id']);

// Obtener los detalles del perfume
$sql = "SELECT p.*, c.nombre as categoria_nombre 
        FROM perfumes p 
        LEFT JOIN categorias c ON p.categoria_id = c.id 
        WHERE p.id = ? AND p.activo = TRUE";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $perfume_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Perfume no encontrado']);
    exit;
}

$perfume = $result->fetch_assoc();

// Asegurarse de que los valores numéricos sean números, no strings
$perfume['precio'] = floatval($perfume['precio']);
$perfume['precio_oferta'] = floatval($perfume['precio_oferta']);

echo json_encode($perfume);

$conn->close();
?>