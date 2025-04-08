<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../includes/auth_functions.php';

session_start();
verificarAutenticacion();

$db = (new Database())->conectar();
$usuario_id = $_SESSION['usuario_id'];

// Obtener contactos principales del usuario
$queryContactos = 'SELECT nombre, telefono, correo 
                   FROM contactos_emergencia 
                   WHERE usuario_id = ? 
                   ORDER BY id LIMIT 3';
$stmtContactos = $db->prepare($queryContactos);
$stmtContactos->execute([$usuario_id]);
$contactos = $stmtContactos->fetchAll(PDO::FETCH_ASSOC);

if (empty($contactos)) {
    echo json_encode(['error' => 'No tienes contactos de emergencia registrados']);
    exit;
}

// Registrar la emergencia en el historial
$queryInsert = 'INSERT INTO historial_emergencias (usuario_id, descripcion, fecha_reporte) 
                VALUES (?, "Alerta de emergencia rápida activada", NOW())';
$stmtInsert = $db->prepare($queryInsert);
$stmtInsert->execute([$usuario_id]);
$emergencia_id = $db->lastInsertId();

// Simular envío de alertas
$alertasEnviadas = [];
foreach ($contactos as $contacto) {
    $mensaje = "ALERTA DE EMERGENCIA\n\n";
    $mensaje .= "Tu contacto {$_SESSION['nombre']} ha activado una alerta de emergencia rápida.\n\n";
    $mensaje .= "Por favor contacta con ellos inmediatamente o llama a los servicios de emergencia local.";
    
    // En producción, aquí enviarías por SMS/email
    $alertasEnviadas[] = [
        'contacto' => $contacto['nombre'],
        'telefono' => $contacto['telefono'],
        'correo' => $contacto['correo']
    ];
}

echo json_encode([
    'exito' => true,
    'emergencia_id' => $emergencia_id,
    'alertas_enviadas' => $alertasEnviadas,
    'mensaje' => 'Alerta enviada a tus contactos principales'
]);
?>