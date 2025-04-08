<?php 
$tituloPagina = "Mi Historial - VitaliA";
include 'includes/header.php'; 
require_once 'config/database.php';
require_once 'includes/auth_functions.php';

verificarAutenticacion();

$db = $database->conectar();
$usuario_id = $_SESSION['usuario_id'];

// Obtener historial de chat
$queryChat = 'SELECT id, mensaje, respuesta, fecha_chat 
              FROM historial_chat 
              WHERE usuario_id = ? 
              ORDER BY fecha_chat DESC 
              LIMIT 50';
$stmtChat = $db->prepare($queryChat);
$stmtChat->execute([$usuario_id]);
$historialChat = $stmtChat->fetchAll(PDO::FETCH_ASSOC);

// Obtener historial de emergencias
$queryEmergencias = 'SELECT h.id, h.descripcion, h.fecha_reporte, 
                    GROUP_CONCAT(i.ruta_imagen SEPARATOR "||") as imagenes
                    FROM historial_emergencias h
                    LEFT JOIN imagenes_emergencia i ON h.id = i.emergencia_id
                    WHERE h.usuario_id = ?
                    GROUP BY h.id
                    ORDER BY h.fecha_reporte DESC';
$stmtEmergencias = $db->prepare($queryEmergencias);
$stmtEmergencias->execute([$usuario_id]);
$historialEmergencias = $stmtEmergencias->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="contenedor-historial">
    <h1>Mi Historial</h1>
    
    <div class="tabs-historial">
        <button class="tab-button active" data-tab="consultas">Consultas al Chat</button>
        <button class="tab-button" data-tab="emergencias">Emergencias Reportadas</button>
    </div>
    
    <div class="contenido-tab active" id="consultas">
        <?php if (empty($historialChat)): ?>
            <p>No hay consultas registradas en tu historial.</p>
        <?php else: ?>
            <div class="lista-consultas">
                <?php foreach ($historialChat as $consulta): ?>
                    <div class="consulta">
                        <div class="fecha-consulta">
                            <?php echo date('d/m/Y H:i', strtotime($consulta['fecha_chat'])); ?>
                        </div>
                        <div class="contenido-consulta">
                            <div class="mensaje-usuario">
                                <strong>Tú:</strong> <?php echo htmlspecialchars($consulta['mensaje']); ?>
                            </div>
                            <div class="respuesta-asistente">
                                <strong>VitaliA:</strong> <?php echo htmlspecialchars($consulta['respuesta']); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="contenido-tab" id="emergencias">
        <?php if (empty($historialEmergencias)): ?>
            <p>No has reportado emergencias.</p>
        <?php else: ?>
            <div class="lista-emergencias">
                <?php foreach ($historialEmergencias as $emergencia): ?>
                    <div class="emergencia">
                        <div class="fecha-emergencia">
                            <?php echo date('d/m/Y H:i', strtotime($emergencia['fecha_reporte'])); ?>
                        </div>
                        <div class="descripcion-emergencia">
                            <?php echo htmlspecialchars($emergencia['descripcion']); ?>
                        </div>
                        
                        <?php if (!empty($emergencia['imagenes'])): ?>
                            <div class="imagenes-emergencia">
                                <?php 
                                $imagenes = explode('||', $emergencia['imagenes']);
                                foreach ($imagenes as $imagen): 
                                    if (!empty($imagen)):
                                ?>
                                    <img src="uploads/<?php echo htmlspecialchars($imagen); ?>" 
                                         alt="Imagen de emergencia" 
                                         class="imagen-emergencia">
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            // Ocultar todos los contenidos de tabs
            document.querySelectorAll('.contenido-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Desactivar todos los botones
            tabButtons.forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Mostrar el contenido seleccionado
            document.getElementById(tabId).classList.add('active');
            this.classList.add('active');
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>