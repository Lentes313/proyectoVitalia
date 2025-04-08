<?php 
$tituloPagina = "Alertas de Emergencia - VitaliA";
include 'includes/header.php'; 
require_once 'config/database.php';
require_once 'includes/auth_functions.php';

verificarAutenticacion();

$db = $database->conectar();
$usuario_id = $_SESSION['usuario_id'];

// Obtener contactos de emergencia del usuario
$queryContactos = 'SELECT id, nombre, telefono, correo, relacion 
                   FROM contactos_emergencia 
                   WHERE usuario_id = ? 
                   ORDER BY nombre';
$stmtContactos = $db->prepare($queryContactos);
$stmtContactos->execute([$usuario_id]);
$contactos = $stmtContactos->fetchAll(PDO::FETCH_ASSOC);

// Procesar envío de alerta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
    $contactos_seleccionados = $_POST['contactos'] ?? [];
    
    // Validar
    if (empty($descripcion)) {
        $error = "Por favor describe la emergencia.";
    } elseif (empty($contactos_seleccionados)) {
        $error = "Debes seleccionar al menos un contacto.";
    } else {
        // Insertar emergencia en el historial
        $queryInsert = 'INSERT INTO historial_emergencias (usuario_id, descripcion, fecha_reporte) 
                        VALUES (?, ?, NOW())';
        $stmtInsert = $db->prepare($queryInsert);
        $stmtInsert->execute([$usuario_id, $descripcion]);
        $emergencia_id = $db->lastInsertId();
        
        // Notificar a cada contacto seleccionado (simulado)
        foreach ($contactos_seleccionados as $contacto_id) {
            // Aquí iría el código real para enviar notificaciones
            $queryContacto = 'SELECT nombre, telefono, correo FROM contactos_emergencia WHERE id = ?';
            $stmtContacto = $db->prepare($queryContacto);
            $stmtContacto->execute([$contacto_id]);
            $contacto = $stmtContacto->fetch(PDO::FETCH_ASSOC);
            
            // Simular envío de alerta
            $mensajeAlerta = "ALERTA DE EMERGENCIA\n\n";
            $mensajeAlerta .= "Tu contacto {$_SESSION['nombre']} ha activado una alerta de emergencia.\n";
            $mensajeAlerta .= "Descripción: $descripcion\n\n";
            $mensajeAlerta .= "Por favor contacta con ellos inmediatamente o llama a los servicios de emergencia.";
            
            // En un sistema real, aquí se enviaría por SMS, email, etc.
            $alertasEnviadas[] = [
                'contacto' => $contacto['nombre'],
                'telefono' => $contacto['telefono'],
                'correo' => $contacto['correo'],
                'mensaje' => $mensajeAlerta
            ];
        }
        
        $exito = "Alerta de emergencia enviada a tus contactos seleccionados.";
    }
}
?>

<main class="contenedor-emergencia">
    <h1>Alertas de Emergencia</h1>
    
    <?php if (isset($exito)): ?>
        <div class="alerta exito"><?php echo $exito; ?></div>
        
        <div class="resumen-alerta">
            <h3>Resumen de alerta enviada</h3>
            <p><strong>Descripción:</strong> <?php echo htmlspecialchars($descripcion); ?></p>
            
            <h4>Contactos notificados:</h4>
            <ul>
                <?php foreach ($alertasEnviadas as $alerta): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($alerta['contacto']); ?></strong><br>
                        Tel: <?php echo htmlspecialchars($alerta['telefono']); ?><br>
                        Email: <?php echo htmlspecialchars($alerta['correo']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <div class="acciones-post-emergencia">
            <a href="dashboard.php" class="boton-primario">Volver al Panel</a>
            <a href="emergency.php" class="boton-secundario">Nueva Alerta</a>
        </div>
    <?php else: ?>
        <?php if (isset($error)): ?>
            <div class="alerta error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="formulario-emergencia">
            <form action="emergency.php" method="POST">
                <div class="grupo-formulario">
                    <label for="descripcion">Describe la emergencia</label>
                    <textarea id="descripcion" name="descripcion" required 
                              placeholder="Proporciona detalles sobre la situación de emergencia..."></textarea>
                </div>
                
                <div class="grupo-formulario">
                    <label>Selecciona contactos para alertar</label>
                    
                    <?php if (empty($contactos)): ?>
                        <div class="alerta informacion">
                            No tienes contactos de emergencia registrados. 
                            <a href="agregar_contacto.php">Agrega al menos un contacto</a> 
                            para poder usar esta función.
                        </div>
                    <?php else: ?>
                        <div class="lista-contactos">
                            <?php foreach ($contactos as $contacto): ?>
                                <div class="contacto-emergencia">
                                    <input type="checkbox" 
                                           id="contacto_<?php echo $contacto['id']; ?>" 
                                           name="contactos[]" 
                                           value="<?php echo $contacto['id']; ?>">
                                    <label for="contacto_<?php echo $contacto['id']; ?>">
                                        <strong><?php echo htmlspecialchars($contacto['nombre']); ?></strong><br>
                                        <?php echo htmlspecialchars($contacto['relacion']); ?><br>
                                        Tel: <?php echo htmlspecialchars($contacto['telefono']); ?><br>
                                        Email: <?php echo htmlspecialchars($contacto['correo']); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="grupo-formulario">
                    <button type="submit" class="boton-emergencia" <?php echo empty($contactos) ? 'disabled' : ''; ?>>
                        Activar Alerta de Emergencia
                    </button>
                    <p class="ayuda">
                        Al activar la alerta, se notificará a todos los contactos seleccionados 
                        con los detalles de la emergencia.
                    </p>
                </div>
            </form>
        </div>
        
        <div class="geolocalizacion">
            <h3>Tu ubicación actual</h3>
            <div id="mapa-emergencia" class="mapa"></div>
            <p class="ayuda">
                En una emergencia real, tu ubicación se compartirá automáticamente con tus contactos.
            </p>
        </div>
    <?php endif; ?>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simular mapa (en implementación real usarías Google Maps o similar)
    const mapa = document.getElementById('mapa-emergencia');
    mapa.innerHTML = '<div class="mapa-simulado"><p>Mapa de ubicación aparecería aquí</p></div>';
    
    // En una implementación real, aquí iría el código para obtener la geolocalización
    /*
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            position => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                // Mostrar mapa con la ubicación
            },
            error => {
                console.error("Error al obtener ubicación:", error);
            }
        );
    }
    */
});
</script>

<?php include 'includes/footer.php'; ?>