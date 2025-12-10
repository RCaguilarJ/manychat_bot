<?php
// modulos/agendar_cita.php

// 1. Verificar que lleguen los datos mínimos (telefono y fecha)
if (isset($data['numero_telefonico']) && isset($data['fecha_hora'])) {

    $telefono = $data['numero_telefonico'];
    $fecha_cita = $data['fecha_hora']; // Ya viene en formato "YYYY-MM-DD HH:MM:00"
    $estudio_id = isset($data['estudio_id']) ? $data['estudio_id'] : null;

    // Concatenamos el nombre completo para guardarlo en la tabla 'pacientes'
    // (Si tu tabla pacientes tiene columnas separadas, ajusta esto)
    $nombre_completo = trim($data['nombre_paciente'] . ' ' . $data['apellido_paterno'] . ' ' . $data['apellido_materno']);
    $fecha_nac = $data['fecha_nacimiento']; // "YYYY-MM-DD..."

    try {
        $pdo->beginTransaction(); // Iniciamos una transacción para que todo se guarde junto

        // PASO A: BUSCAR O CREAR PACIENTE
        // ---------------------------------------------------------
        $stmt = $pdo->prepare("SELECT id FROM pacientes WHERE telefono = :tel LIMIT 1");
        $stmt->execute([':tel' => $telefono]);
        $paciente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($paciente) {
            // Si ya existe, usamos su ID
            $paciente_id = $paciente['id'];
        } else {
            // Si NO existe, lo registramos ahora mismo con los datos que llegaron
            $sql_nuevo = "INSERT INTO pacientes (nombre_completo, telefono, fecha_nacimiento) 
                          VALUES (:nom, :tel, :fnac)";
            $stmt_nuevo = $pdo->prepare($sql_nuevo);
            $stmt_nuevo->execute([
                ':nom' => $nombre_completo,
                ':tel' => $telefono,
                ':fnac' => substr($fecha_nac, 0, 10) // Aseguramos formato YYYY-MM-DD
            ]);
            $paciente_id = $pdo->lastInsertId();
        }

        // PASO B: CREAR LA CITA (AGENDA)
        // ---------------------------------------------------------
        $sql_agenda = "INSERT INTO agenda (paciente_id, fecha_cita) VALUES (:pid, :fecha)";
        $stmt_agenda = $pdo->prepare($sql_agenda);
        $stmt_agenda->execute([
            ':pid' => $paciente_id,
            ':fecha' => $fecha_cita
        ]);
        
        $folio_id = $pdo->lastInsertId(); // ¡Este es el FOLIO!

        // PASO C: RELACIONAR EL ESTUDIO (Si enviaron un ID de estudio)
        // ---------------------------------------------------------
        if ($estudio_id) {
            $sql_detalle = "INSERT INTO agenda_detalle (agenda_id, estudio_id, estatus) 
                            VALUES (:folio, :estudio, 'Pendiente')";
            $stmt_detalle = $pdo->prepare($sql_detalle);
            $stmt_detalle->execute([
                ':folio' => $folio_id,
                ':estudio' => $estudio_id
            ]);
        }

        $pdo->commit(); // Guardamos todo

        // Respondemos a ManyChat
        echo json_encode([
            'status' => 'success',
            'mensaje' => 'Cita agendada correctamente',
            'id_paciente' => $paciente_id,
            'folio' => $folio_id
        ]);

    } catch (PDOException $e) {
        $pdo->rollBack(); // Si algo falla, deshacemos los cambios
        echo json_encode(['status' => 'error', 'mensaje' => 'Error SQL: ' . $e->getMessage()]);
    }

} else {
    echo json_encode(['status' => 'error', 'mensaje' => 'Faltan datos obligatorios (telefono o fecha)']);
}
?>