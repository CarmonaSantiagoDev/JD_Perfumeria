<?php

include 'includes/header.php';

// Obtener el ID del pedido de la URL
$pedido_id = isset($_GET['pedido_id']) ? htmlspecialchars($_GET['pedido_id']) : 'No disponible';

?>

<main class="container confirmation-container">
    <div class="confirmation-box">
        <div class="icon-success">✓</div>
        <h2 class="confirmation-title">¡Pedido Confirmado!</h2>
        <p class="confirmation-message">
            Gracias por tu compra. Tu pedido **#<?php echo $pedido_id; ?>** ha sido recibido.
            <br>
            Nos pondremos en contacto contigo a través de WhatsApp para coordinar el pago y la entrega a domicilio.
        </p>
        <a href="index.php" class="btn btn-primary btn-back-home">Volver al Inicio</a>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<style>
/* Estilos para la página de confirmación */
.confirmation-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 60vh;
    text-align: center;
}

.confirmation-box {
    background-color: #fff;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    max-width: 600px;
}

.icon-success {
    font-size: 5em;
    color: #28a745;
    margin-bottom: 20px;
    animation: bounceIn 0.8s ease-in-out;
}

.confirmation-title {
    font-size: 2.5em;
    color: var(--primary);
    margin-bottom: 10px;
}

.confirmation-message {
    font-size: 1.1em;
    color: #555;
    line-height: 1.6;
    margin-bottom: 30px;
}

.btn-back-home {
    padding: 12px 25px;
    font-size: 1em;
}

@keyframes bounceIn {
    0% {
        transform: scale(0.1);
        opacity: 0;
    }
    60% {
        transform: scale(1.1);
        opacity: 1;
    }
    100% {
        transform: scale(1);
    }
}
</style>