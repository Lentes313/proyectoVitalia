<?php 
$tituloPagina = "Contacto - VitaliA";
include 'includes/header.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar formulario de contacto
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $mensaje = filter_input(INPUT_POST, 'mensaje', FILTER_SANITIZE_STRING);
    
    // Validar y enviar (simulado)
    if (!empty($nombre) && !empty($email) && !empty($mensaje)) {
        $mensajeExito = "Gracias por tu mensaje, $nombre. Nos pondremos en contacto contigo pronto.";
    } else {
        $error = "Por favor completa todos los campos del formulario.";
    }
}
?>

<main class="contenedor-formulario">
    <section class="formulario-contacto">
        <h2>Contáctanos</h2>
        
        <?php if (isset($mensajeExito)): ?>
            <div class="alerta exito"><?php echo $mensajeExito; ?></div>
        <?php elseif (isset($error)): ?>
            <div class="alerta error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="contact.php" method="POST">
            <div class="grupo-formulario">
                <label for="nombre">Nombre Completo</label>
                <input type="text" id="nombre" name="nombre" required 
                       value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>">
            </div>
            
            <div class="grupo-formulario">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" required 
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            
            <div class="grupo-formulario">
                <label for="mensaje">Mensaje</label>
                <textarea id="mensaje" name="mensaje" required><?php 
                    echo htmlspecialchars($_POST['mensaje'] ?? ''); 
                ?></textarea>
            </div>
            
            <button type="submit" class="boton-primario">Enviar Mensaje</button>
        </form>
        
        <div class="informacion-contacto">
            <h3>Otras formas de contacto</h3>
            <p><strong>Email:</strong> contacto@vitalia.com</p>
            <p><strong>Teléfono:</strong> +52 33 1234 5678</p>
            <p><strong>Dirección:</strong> Av. Tecnológico #123, Zapopan, Jalisco, México</p>
            
            <div class="horario">
                <h4>Horario de atención</h4>
                <p>Lunes a Viernes: 9:00 - 18:00 hrs</p>
                <p>Sábados: 10:00 - 14:00 hrs</p>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>