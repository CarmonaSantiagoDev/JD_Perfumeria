<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=jd_perfumeria", "root", "");
    echo "Conexión exitosa";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>