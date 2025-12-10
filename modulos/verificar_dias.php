<?php
// modulos/verificar_dias.php

// Array para guardar los nombres de los días en español (opcional, para que se vea bonito)
$dias_esp = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];

$respuesta = [];

// Generamos los próximos 7 días
for ($i = 0; $i < 7; $i++) {
    // Obtenemos la fecha sumando $i días a hoy
    $timestamp = strtotime("+$i days");
    
    // Formato para mostrar: "Lunes 04/12"
    $numero_dia_semana = date("w", $timestamp);
    $nombre_dia = $dias_esp[$numero_dia_semana];
    $fecha_corta = date("d/m", $timestamp);
    
    // Creamos la clave exacta que espera ManyChat (dia_1, dia_2...)
    // Nota: $i empieza en 0, así que sumamos 1 para que sea dia_1
    $clave = "dia_" . ($i + 1);
    
    // Guardamos el valor
    $respuesta[$clave] = "$nombre_dia $fecha_corta";
}

// Agregamos estatus de éxito
$respuesta['status'] = 'success';

// Enviamos el JSON
echo json_encode($respuesta);
?>