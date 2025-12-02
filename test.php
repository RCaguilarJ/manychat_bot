<?php
// test.php

// 1. A dónde vamos a enviar los datos (Asegúrate que la ruta sea correcta)
// Actualizamos la ruta con el nombre correcto de tu carpeta
$url = 'http://localhost/manychat_bot/webhook.php'; 

// 2. Los datos falsos que queremos probar (Simulando a ManyChat)
$datos_prueba = [
    'accion' => 'registrar',
    'nombre' => 'Paciente de Prueba',
    'telefono' => '555-123-4567'
];

// 3. Empaquetamos los datos en JSON
$opciones = [
    'http' => [
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($datos_prueba),
    ],
];

// 4. Enviamos el paquete y esperamos respuesta
$contexto  = stream_context_create($opciones);
$respuesta = file_get_contents($url, false, $contexto);

// 5. Mostramos lo que nos respondió el webhook
echo "<h3>Respuesta del Webhook:</h3>";
echo "<pre>" . $respuesta . "</pre>";
?>