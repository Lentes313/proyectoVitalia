<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$db = (new Database())->conectar();
$categoria = filter_input(INPUT_GET, 'categoria', FILTER_SANITIZE_STRING);

if (empty($categoria)) {
    echo json_encode([]);
    exit;
}

$query = 'SELECT id, titulo, descripcion 
          FROM informacion_medica 
          WHERE categoria = ? 
          ORDER BY titulo';
$stmt = $db->prepare($query);
$stmt->execute([$categoria]);

$guias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agregar URL de descarga (simulado)
foreach ($guias as &$guia) {
    $guia['url_descarga'] = "assets/guias/{$guia['id']}.pdf";
}

echo json_encode($guias);
?>