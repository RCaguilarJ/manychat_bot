<?php
// modulos/verificar_horas.php - VERSIÓN CORREGIDA (SQL FIXED)

// 1. Recibir datos (fecha seleccionada por el usuario)
$fecha = $data['fecha'] ?? date('Y-m-d');

// Definir horario de trabajo (Ej: 9:00 a 14:00)
$hora_inicio = 9; 
$hora_fin = 14; 
$intervalo = 30; // minutos por cita

// 2. Buscar citas ocupadas en esa fecha
// CORRECCIÓN: Usamos 'fecha_cita' en lugar de 'fecha_hora'
$sql = "SELECT DATE_FORMAT(fecha_cita, '%H:%i') as hora FROM agenda WHERE DATE(fecha_cita) = :fecha";
$stmt = $pdo->prepare($sql);
$stmt->execute([':fecha' => $fecha]);
$citas_ocupadas = $stmt->fetchAll(PDO::FETCH_COLUMN);

// 3. Calcular horas libres
$horas_disponibles = [];
$hora_actual = new DateTime("$fecha $hora_inicio:00");
$hora_limite = new DateTime("$fecha $hora_fin:00");

while ($hora_actual < $hora_limite) {
    $hora_str = $hora_actual->format('H:i');
    
    // Si la hora NO está en la lista de ocupadas, la agregamos
    if (!in_array($hora_str, $citas_ocupadas)) {
        $horas_disponibles[] = $hora_str;
    }
    
    // Avanzamos al siguiente bloque de 30 mins
    $hora_actual->modify("+$intervalo minutes");
}

// 4. Responder a ManyChat
if (count($horas_disponibles) > 0) {
    $respuesta['status'] = 'success';
    // Convertimos el array en un texto: "09:00, 09:30, 10:30..."
    $respuesta['mensaje_horas'] = implode(", ", $horas_disponibles);
} else {
    $respuesta['status'] = 'full';
    $respuesta['mensaje_horas'] = "No hay horarios disponibles para este día.";
}

echo json_encode($respuesta);
?>