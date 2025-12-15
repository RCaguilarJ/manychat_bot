<?php
// modulos/obtener_estudios.php - VERSIÓN BÚSQUEDA INTELIGENTE

try {
    $respuesta = [];
    
    // Verificamos si llegó un término de búsqueda desde ManyChat
    $termino = isset($data['termino']) ? trim($data['termino']) : '';

    if (!empty($termino)) {
        // MODO BÚSQUEDA: Si el usuario escribió algo (ej. "sanguinea"), filtramos
        $sql = "SELECT id, nombre_estudio FROM estudios WHERE nombre_estudio LIKE :termino LIMIT 5";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':termino' => "%$termino%"]);
    } else {
        // MODO LISTA: Si no escribieron nada, mostramos los primeros 10
        $sql = "SELECT id, nombre_estudio FROM estudios LIMIT 10";
        $stmt = $pdo->query($sql);
    }

    $lista_estudios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $cantidad = count($lista_estudios);

    if ($cantidad > 0) {
        $respuesta['status'] = 'success';
        $respuesta['cantidad'] = $cantidad; // Dato clave para tu condición en ManyChat

        // Creamos las variables dinámicas: estudio_1_nombre, estudio_1_id...
        foreach ($lista_estudios as $index => $estudio) {
            $num = $index + 1;
            $respuesta["estudio_{$num}_nombre"] = $estudio['nombre_estudio'];
            $respuesta["estudio_{$num}_id"] = $estudio['id'];
        }
        
        // Mensaje opcional para depuración
        $respuesta['mensaje'] = "Encontré $cantidad resultados.";

    } else {
        $respuesta['status'] = 'success'; // Respondemos success para no romper el flujo
        $respuesta['cantidad'] = 0;
        $respuesta['mensaje'] = "No se encontraron estudios con ese nombre.";
    }

    echo json_encode($respuesta);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Error SQL: ' . $e->getMessage()]);
}
?>