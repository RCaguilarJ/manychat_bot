<?php
// modulos/verificar_dias.php
$respuesta = [];
$fecha_actual = new DateTime();

// Generamos los próximos 7 días
for ($i = 1; $i <= 7; $i++) {
    // Avanzar 1 día
    $fecha_actual->modify('+1 day');
    
    // Si es domingo, nos lo saltamos (opcional)
    if ($fecha_actual->format('N') == 7) {
        $fecha_actual->modify('+1 day');
    }
    
    // Formato para el botón (Ej: "Lun 12 Oct")
    // Usamos setlocale para intentar español, o arrays manuales si falla
    $dias_ES = ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"];
    $meses_ES = ["", "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
    
    $dia_sem = $dias_ES[$fecha_actual->format('w')];
    $dia_num = $fecha_actual->format('d');
    $mes = $meses_ES[$fecha_actual->format('n')];
    
    // Guardamos texto para el botón (Ej: "Lun 12 Oct")
    $respuesta["dia_$i"] = "$dia_sem $dia_num $mes";
    
    // También enviamos el valor real para guardar en la BD (Ej: "2025-10-12")
    $respuesta["fecha_real_$i"] = $fecha_actual->format('Y-m-d');
}

$respuesta['status'] = 'success';
echo json_encode($respuesta);
?>