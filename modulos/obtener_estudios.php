<?php
// modulos/obtener_estudios.php

try {
    // Buscamos los estudios (puedes cambiar el LIMIT si quieres mostrar más)
    $sql = "SELECT id, nombre_estudio FROM estudios LIMIT 10";
    $stmt = $pdo->query($sql);
    $lista_estudios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($lista_estudios) > 0) {
        $respuesta = ['status' => 'success'];

        // Recorremos la lista para crear variables dinámicas
        // Ejemplo: estudio_1_nombre = "Rayos X", estudio_1_id = "45"
        foreach ($lista_estudios as $index => $estudio) {
            $num = $index + 1; // Para empezar en 1 (estudio_1)
            
            $respuesta["estudio_{$num}_nombre"] = $estudio['nombre_estudio'];
            $respuesta["estudio_{$num}_id"] = $estudio['id'];
        }

        echo json_encode($respuesta);

    } else {
        echo json_encode(['status' => 'error', 'mensaje' => 'No hay estudios registrados en la base de datos.']);
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Error SQL: ' . $e->getMessage()]);
}
?>