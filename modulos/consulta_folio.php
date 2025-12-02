<?php
// modulos/consulta_folio.php

if (isset($data['folio'])) {
    $folio = $data['folio'];

    // Buscamos los estudios asociados a ese folio (agenda_id)
    // Hacemos un JOIN para traer el nombre del estudio también
    $sql = "SELECT e.nombre_estudio, d.estatus 
            FROM agenda_detalle d
            JOIN estudios e ON d.estudio_id = e.id
            WHERE d.agenda_id = :folio";

    $stmt = $pdo->prepare($sql);
    
    try {
        $stmt->execute([':folio' => $folio]);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($resultados) > 0) {
            // Si encontramos estudios, preparamos un mensaje bonito
            $mensaje = "Hola, aquí está el estado de tus estudios para el folio #$folio:\n";
            
            foreach ($resultados as $fila) {
                // Ej: "- Sangre: Listo"
                $mensaje .= "- " . $fila['nombre_estudio'] . ": " . $fila['estatus'] . "\n";
            }

            echo json_encode([
                'status' => 'success',
                'mensaje' => $mensaje
            ]);

        } else {
            echo json_encode([
                'status' => 'error',
                'mensaje' => 'No encontramos estudios para ese folio. Verifica el número.'
            ]);
        }

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'mensaje' => 'Error al consultar la base de datos']);
    }

} else {
    echo json_encode(['status' => 'error', 'mensaje' => 'Falta el número de folio']);
}
?>