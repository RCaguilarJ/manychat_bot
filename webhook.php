<?php
// webhook.php

// 1. Incluimos la conexión. Si falla, el script se detiene aquí.
require_once 'conexion.php';

// 2. Recibimos los datos crudos (JSON) de ManyChat
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// 3. Verificamos si hay una acción definida
if (isset($data['accion'])) {
    $accion = $data['accion'];
    
    // 4. Semáforo de decisiones
    if ($accion === 'registrar') {
        require_once 'modulos/registro_paciente.php';
        
    } elseif ($accion === 'consultar') {
        require_once 'modulos/consulta_folio.php';
        
    } else {
        // Acción desconocida
        echo json_encode(['status' => 'error', 'mensaje' => 'Acción no válida']);
    }
} else {
    // No llegó ninguna acción
    echo json_encode(['status' => 'error', 'mensaje' => 'No se recibió ninguna acción']);
}
?>