<?php
// webhook.php - VERSIÓN FINAL ACTUALIZADA

require_once 'conexion.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['accion'])) {
    $accion = $data['accion'];
    
    // 1. Registro de paciente
    if ($accion === 'registrar') {
        require_once 'modulos/registro_paciente.php';
        
    // 2. Consulta de estatus de folio
    } elseif ($accion === 'consultar') {
        require_once 'modulos/consulta_folio.php';

    // 3. Verificar días disponibles
    } elseif ($accion === 'verificar_dias') {
        require_once 'modulos/verificar_dias.php';

    // 4. Agendar cita (Guardar en BD)
    } elseif ($accion === 'agendar') {
        require_once 'modulos/agendar_cita.php';

    // 5. NUEVO: Obtener lista de estudios
    } elseif ($accion === 'obtener_estudios') {
        require_once 'modulos/obtener_estudios.php';

    } else {
        echo json_encode(['status' => 'error', 'mensaje' => 'Acción no válida: ' . $accion]);
    }
} else {
    echo json_encode(['status' => 'error', 'mensaje' => 'No se recibió ninguna acción']);
}
?>