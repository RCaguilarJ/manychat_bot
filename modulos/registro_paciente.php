<?php
// modulos/registro_paciente.php

// 1. Validamos que lleguen los datos necesarios
if (isset($data['nombre']) && isset($data['telefono'])) {

    // 2. Preparamos la sentencia SQL con "etiquetas" (:nombre, :telefono)
    $sql = "INSERT INTO pacientes (nombre_completo, telefono) VALUES (:nombre, :telefono)";
    $stmt = $pdo->prepare($sql);

    try {
        // 3. EJECUTAMOS: Aquí conectamos la etiqueta con el dato real
        $stmt->execute([
            ':nombre' => $data['nombre'],
            ':telefono' => $data['telefono']
        ]);

        // 4. Si todo sale bien, respondemos con el ID del nuevo paciente
        // lastInsertId() es una función mágica que nos devuelve el ID que se acaba de crear
        echo json_encode([
            'status' => 'success',
            'mensaje' => 'Paciente registrado exitosamente',
            'id_paciente' => $pdo->lastInsertId()
        ]);

    } catch (PDOException $e) {
        // Si hay error (ej: teléfono duplicado)
        echo json_encode(['status' => 'error', 'mensaje' => 'Error al guardar en base de datos']);
    }

} else {
    echo json_encode(['status' => 'error', 'mensaje' => 'Faltan datos (nombre o telefono)']);
}
?>