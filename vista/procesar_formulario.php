<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../bd/cn.php');
$nombre_completo = $_POST['nombre_completo'] ?? '';

date_default_timezone_set('America/Bogota');
$fecha_actual = date('Y-m-d H:i:s');

require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpWord\TemplateProcessor;

// Función para insertar si no existe y devolver id
function insertarSiNoExiste($conexion, $tabla, $campo, $valor, $extra = []) {
    $query = "SELECT id FROM $tabla WHERE $campo = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $valor);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        return $resultado->fetch_assoc()['id'];
    } else {
        $campos = "$campo";
        $placeholders = "?";
        $tipos = "s";
        $valores = [$valor];

        if (!empty($extra)) {
            foreach ($extra as $k => $v) {
                $campos .= ", $k";
                $placeholders .= ", ?";
                $tipos .= "s";
                $valores[] = $v;
            }
        }

        $sql = "INSERT INTO $tabla ($campos) VALUES ($placeholders)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param($tipos, ...$valores);
        $stmt->execute();
        return $stmt->insert_id;
    }
}

// Obtener solo el número del contrato (sin año)
$result = $conexion->query("SELECT MAX(CAST(no_contrato AS UNSIGNED)) AS ultimo FROM contrato_detallado");
$row = $result->fetch_assoc();
$ultimo = $row['ultimo'] ?? 0;
$nuevo_numero = $ultimo + 1;
$numero_contrato = str_pad($nuevo_numero, 4, '0', STR_PAD_LEFT);

// Datos del formulario
$nombre_completo = $_POST['nombre_completo'] ?? '';
$documento_identidad = $_POST['documento_identidad'] ?? '';
$fecha_expedicion_documento = $_POST['fecha_expedicion_documento'] ?? '';
$ciudad_expedicion = $_POST['ciudad_expedicion'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$tipo_cuenta = $_POST['tipo_cuenta_bancaria'] ?? '';
$numero_cuenta = $_POST['numero_cuenta_bancaria'] ?? '';
$fecha_inicio = $_POST['fecha_suscripcion'] ?? '';
$fecha_final = $_POST['fecha_final_contrato'] ?? '';
$meses_contratados = (int)($_POST['meses_contratados'] ?? 1);
$valor_total = (float)($_POST['valor_total_contrato'] ?? 0);
$lugar_ejecucion = $_POST['lugar_ejecucion'] ?? '';
$direccion_lugar_ejecucion = $_POST['direccion_lugar_ejecucion'] ?? '';
$nuevo_grado = $_POST['grado_ordenador'] ?? '';
$nuevo_nombre = $_POST['nombre_ordenador'] ?? '';
$nueva_cedula = $_POST['cedula_ordenador'] ?? '';
$nuevo_lugar = $_POST['lugar_expedicion_cedula_ordenador'] ?? '';
$nuevo_cdp_numero = $_POST['nuevo_cdp_numero'] ?? null;
$nuevo_cdp_fecha = $_POST['nuevo_cdp_fecha'] ?? null;
$nuevo_cdp_valor = $_POST['nuevo_cdp_valor'] ?? null;


// Obtener unidad nueva o existente
$nueva_unidad = trim($_POST['nueva_unidad'] ?? '');
$unidad_existente = trim($_POST['unidad'] ?? ''); // ← Corregido: coincide con name="unidad" en el <select>

if (!empty($nueva_unidad)) {
    // Si se está creando una nueva unidad
    $unidad = $nueva_unidad;
    $localidad = trim($_POST['nueva_localidad'] ?? '');
    $nuevo_nombre_unidad = trim($_POST['nuevo_nombre_unidad'] ?? '');

    // Insertar nueva unidad en la tabla auxiliar
    $sqlInsertUnidad = "INSERT INTO unidad (unidad, nombre, localidad) VALUES (?, ?, ?)";
    $stmtInsertUnidad = $conexion->prepare($sqlInsertUnidad);
    $stmtInsertUnidad->bind_param("sss", $unidad, $nuevo_nombre_unidad, $localidad);
    $stmtInsertUnidad->execute();
    $id_unidad = $stmtInsertUnidad->insert_id;

} elseif (!empty($unidad_existente)) {
    // Se seleccionó una unidad existente, cuyo value es el ID
    $id_unidad = (int)$unidad_existente;

    $sqlUnidad = "SELECT unidad FROM unidad WHERE id = ?";
    $stmtUnidad = $conexion->prepare($sqlUnidad);
    $stmtUnidad->bind_param("i", $id_unidad);
    $stmtUnidad->execute();
    $resultUnidad = $stmtUnidad->get_result();

    if ($resultUnidad->num_rows > 0) {
        $rowUnidad = $resultUnidad->fetch_assoc();
        $unidad = $rowUnidad['unidad'];
    } else {
        $unidad = '';
        $id_unidad = null;
    }

} else {
    // No se seleccionó ni ingresó ninguna unidad
    $unidad = '';
    $id_unidad = null;
}
// Variables para plantilla Word
$numero_cdp = '';
$fecha_cdp = '';

// Si se ingresó un nuevo CDP
if (!empty($nuevo_cdp_numero) && !empty($nuevo_cdp_fecha) && !empty($nuevo_cdp_valor)) {
    $numero_cdp = $nuevo_cdp_numero;
    $fecha_cdp = $nuevo_cdp_fecha;

    // Insertar nuevo CDP si no existe
    $verificar_cdp = $conexion->prepare("SELECT id FROM cep WHERE numero = ?");
    $verificar_cdp->bind_param("s", $nuevo_cdp_numero);
    $verificar_cdp->execute();
    $verificar_cdp->store_result();

    if ($verificar_cdp->num_rows === 0) {
        $insertar_cdp = $conexion->prepare("INSERT INTO cep (numero, fecha, valor) VALUES (?, ?, ?)");
        $insertar_cdp->bind_param("ssd", $nuevo_cdp_numero, $nuevo_cdp_fecha, $nuevo_cdp_valor);
        $insertar_cdp->execute();
        $insertar_cdp->close();
    }

    $verificar_cdp->close();
} else {
    // Se usó un CDP existente
    $cdp_seleccionado = $_POST['cdp'] ?? '';

    if (!empty($cdp_seleccionado)) {
        $consulta_cdp = $conexion->prepare("SELECT numero, fecha FROM cep WHERE numero = ?");
        $consulta_cdp->bind_param("s", $cdp_seleccionado);
        $consulta_cdp->execute();
        $consulta_cdp->bind_result($numero_cdp, $fecha_cdp);
        $consulta_cdp->fetch();
        $consulta_cdp->close();
    }
}


// Rubro
// Variables para rubro y descripción
$rubro = '';
$descripcion_rubro = '';

// Si se crea nuevo rubro
if (!empty($_POST['nuevo_rubro']) && !empty($_POST['descripcion_rubro'])) {
    $rubro = $_POST['nuevo_rubro'];
    $descripcion_rubro = $_POST['descripcion_rubro'];
    
    // Aquí puedes insertar el nuevo rubro en la base de datos
    // Ejemplo:
    $stmt = $conexion->prepare("INSERT INTO rubro (rubro_presupuestal, descripcion_rubro) VALUES (?, ?)");
    $stmt->bind_param("ss", $rubro, $descripcion_rubro);
    $stmt->execute();
} 
// Si seleccionaron un rubro existente
elseif (!empty($_POST['rubro_presupuestal'])) {
    $rubro = $_POST['rubro_presupuestal'];
    
    // Consultar descripción en la BD
    $stmt = $conexion->prepare("SELECT descripcion_rubro FROM rubro WHERE rubro_presupuestal = ?");
    $stmt->bind_param("s", $rubro);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $descripcion_rubro = $res->fetch_assoc()['descripcion_rubro'];
    }
}

// Cargo
$cargo = !empty($_POST['nuevo_cargo']) ? $_POST['nuevo_cargo'] : ($_POST['cargo'] ?? '');
if (!empty($_POST['nuevo_cargo'])) {
    insertarSiNoExiste($conexion, 'profesiones', 'profesion', $cargo, [
        'fecha_creacion' => $fecha_actual,
        'fecha_actualizacion' => $fecha_actual
    ]);
}

// Banco
$entidad_bancaria = !empty($_POST['nueva_entidad_bancaria']) ? $_POST['nueva_entidad_bancaria'] : ($_POST['entidad_bancaria'] ?? '');
if (!empty($_POST['nueva_entidad_bancaria'])) {
    insertarSiNoExiste($conexion, 'banco', 'nombre', $entidad_bancaria);
}

// Supervisor
$supervisor = !empty($_POST['nuevo_supervisor']) ? $_POST['nuevo_supervisor'] : ($_POST['nombre_supervisor'] ?? '');
if (!empty($_POST['nuevo_supervisor'])) {
    insertarSiNoExiste($conexion, 'supervisor', 'nombre_supervisor', $supervisor, [
        'fecha_creacion' => $fecha_actual,
        'fecha_actualizacion' => $fecha_actual
    ]);
}

// Asesor Jurídico
// Inicializar variables
$asesor_juridico = '';
$cedula_asesor = '';

// Si se ingresó uno nuevo, se usa
if (!empty($_POST['nuevo_nombre_asesor_juridico']) && !empty($_POST['cedula_asesor_juridico'])) {
    $asesor_juridico = $_POST['nuevo_nombre_asesor_juridico'];
    $cedula_asesor = $_POST['cedula_asesor_juridico'];

    insertarSiNoExiste($conexion, 'asesor_juridico', 'nombre', $asesor_juridico, [
        'cedula' => $cedula_asesor
    ]);
}
// Si se seleccionó uno del combo
elseif (!empty($_POST['nombre_asesor_juridico'])) {
    $asesor_juridico = $_POST['nombre_asesor_juridico'];

    // Buscar la cédula en la BD
    $stmt = $conexion->prepare("SELECT cedula FROM asesor_juridico WHERE nombre = ?");
    $stmt->bind_param("s", $asesor_juridico);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $cedula_asesor = $res->fetch_assoc()['cedula'];
    }
}


// Ordenador del gasto
$nuevo_grado = trim($_POST['nuevo_grado'] ?? '');
$nuevo_nombre = trim($_POST['nuevo_nombre_ordenador'] ?? '');
$nueva_cedula = trim($_POST['nuevo_cedula_ordenador'] ?? '');
$nuevo_lugar = trim($_POST['nuevo_lugar_expedicion_cedula'] ?? '');

$datos_ordenador = ['grado' => '', 'nombre' => '', 'cedula' => '', 'lugar_expedicion_cedula' => ''];

if (!empty($nuevo_grado) && !empty($nuevo_nombre) && !empty($nueva_cedula) && !empty($nuevo_lugar)) {
    // Caso: se crea un nuevo ordenador
    $existeOrdenador = $conexion->prepare("SELECT id FROM ordenador_gasto WHERE grado = ? AND nombre = ?");
    $existeOrdenador->bind_param("ss", $nuevo_grado, $nuevo_nombre);
    $existeOrdenador->execute();
    $resOrdenador = $existeOrdenador->get_result();

    if ($resOrdenador->num_rows === 0) {
        $insOrdenador = $conexion->prepare("INSERT INTO ordenador_gasto (grado, nombre, cedula, lugar_expedicion_cedula) VALUES (?, ?, ?, ?)");
        $insOrdenador->bind_param("ssss", $nuevo_grado, $nuevo_nombre, $nueva_cedula, $nuevo_lugar);
        if ($insOrdenador->execute()) {
            $datos_ordenador = [
                'grado' => $nuevo_grado,
                'nombre' => $nuevo_nombre,
                'cedula' => $nueva_cedula,
                'lugar_expedicion_cedula' => $nuevo_lugar
            ];
        } else {
            die("Error insertando ordenador_gasto: " . $insOrdenador->error);
        }
    } else {
        // Ya existe, igual usamos los datos del POST
        $datos_ordenador = [
            'grado' => $nuevo_grado,
            'nombre' => $nuevo_nombre,
            'cedula' => $nueva_cedula,
            'lugar_expedicion_cedula' => $nuevo_lugar
        ];
    }
} elseif (!empty($_POST['ordenador_gasto'])) {
    // Caso: se seleccionó un ordenador existente desde el combo
    $ordenador = $_POST['ordenador_gasto'];
    [$grado, $nombre] = array_map('trim', explode('-', $ordenador, 2));
    $stmt = $conexion->prepare("SELECT grado, nombre, cedula, lugar_expedicion_cedula FROM ordenador_gasto WHERE grado = ? AND nombre = ?");
    $stmt->bind_param("ss", $grado, $nombre);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $datos_ordenador = $res->fetch_assoc();
    }
} else {
    // Ni nuevo ni seleccionado => error
    die("Faltan datos para guardar o seleccionar el ordenador del gasto.");
}


// Insertar contrato_detallado
$sql = "INSERT INTO contrato_detallado (
    no_contrato, nombre_completo_contratista, documento_identidad, fecha_expedicion_documento, lugar_expedicion_documento, 
    direccion_residencia, rubro_presupuestal, descripcion_rubro, profesion, tipo_cuenta_bancaria, numero_cuenta_bancaria, 
    entidad_bancaria, fecha_suscripcion, fecha_terminacion_contrato, supervisor, nombre_asesor_juridico, meses_contratados, 
    valor_total_contrato, unidad
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssssssssssssssssids",
    $numero_contrato, $nombre_completo, $documento_identidad, $fecha_expedicion_documento, $ciudad_expedicion,
    $direccion, $rubro, $descripcion_rubro, $cargo, $tipo_cuenta, $numero_cuenta, $entidad_bancaria,
    $fecha_inicio, $fecha_final, $supervisor, $asesor_juridico, $meses_contratados, $valor_total, $unidad
);

// Funciones auxiliares para el Word
function formatearPesos($valor) {
    return '$' . number_format($valor, 0, ',', '.');
}

function numfmt($numero) {
    if (!class_exists('NumberFormatter')) return $numero;
    $fmt = new NumberFormatter("es", NumberFormatter::SPELLOUT);
    return $fmt->format($numero);
}

if ($stmt->execute()) {
    $template = new TemplateProcessor('plantilla_contratos.docx');

    $valor_mensual = $meses_contratados > 0 ? $valor_total / $meses_contratados : 0;
    $fecha_obj = new DateTime($fecha_final);

     // Array para traducir meses de inglés a español
$meses_es = [
    'January' => 'Enero',
    'February' => 'Febrero',
    'March' => 'Marzo',
    'April' => 'Abril',
    'May' => 'Mayo',
    'June' => 'Junio',
    'July' => 'Julio',
    'August' => 'Agosto',
    'September' => 'Septiembre',
    'October' => 'Octubre',
    'November' => 'Noviembre',
    'December' => 'Diciembre'
];

    // En Word solo mostrar el número del contrato (sin año)
    $template->setValue('nro_contrato', $numero_contrato);
    // Si quieres mostrar el año por separado, puedes usar date('Y')
    $template->setValue('anio_contrato', date('Y'));

    $template->setValue('nombre_contratista', $nombre_completo);
    $template->setValue('cedula_contratista', $documento_identidad);
    $template->setValue('ciudad_expedicion_cedula_contratista', $ciudad_expedicion);
    $template->setValue('direccion_contratista', $direccion);
    $template->setValue('rubro_presupuestal', $rubro);
    $template->setValue('descripcion_rubro', $descripcion_rubro);
    $template->setValue('cargo_contratista', $cargo);
    $template->setValue('anio_vigencia_contrato', date('Y'));
    $template->setValue('tipo_cuenta_bancaria', $tipo_cuenta);
    $template->setValue('nro_cuenta_bancaria', $numero_cuenta);
    $template->setValue('entidad_bancaria', $entidad_bancaria);
    $template->setValue('lugar_ejecuccion', $lugar_ejecucion);
    $template->setValue('direccion_lugar_ejecuccion', $direccion_lugar_ejecucion);
    $template->setValue('nombre_supervisor', $supervisor);
    $template->setValue('numero_cdp', $numero_cdp);
    $template->setValue('fecha_cdp', $fecha_cdp);

    // Asesor jurídico
    $template->setValue('nomb_asesor_juridico', $asesor_juridico);
    $template->setValue('cedula_asesor_juridico', $cedula_asesor);

    $template->setValue('unidad', $unidad);

    // Datos ordenador gasto
    $template->setValue('grado_ordenador_gasto', $datos_ordenador['grado']);
    $template->setValue('nombre_ordenador_gasto', $datos_ordenador['nombre']);
    $template->setValue('cedula_ordenador_gasto', $datos_ordenador['cedula']);
    $template->setValue('lugar_ordenador_gasto', $datos_ordenador['lugar_expedicion_cedula']);

    // Fechas contrato
    $template->setValue('dia_final_contrato', $fecha_obj->format('d'));
    $template->setValue('mes_final_contrato', $meses_es[$fecha_obj->format('F')] ?? $fecha_obj->format('F'));
    $template->setValue('anio_final_contrato', $fecha_obj->format('Y'));

    $fechaFirma = new DateTime($fecha_inicio);

   function numALetras($numero) {
    if (!class_exists('NumberFormatter')) return $numero;
    $fmt = new NumberFormatter("es", NumberFormatter::SPELLOUT);
    return $fmt->format($numero);
}

$dia_letras = ucfirst(numALetras((int)$fechaFirma->format('d')));
$dia_num = $fechaFirma->format('d');
 function numeroALetrasSimple($num) {
    $letras_unidades = [
        0 => '', 1 => 'uno', 2 => 'dos', 3 => 'tres', 4 => 'cuatro', 5 => 'cinco',
        6 => 'seis', 7 => 'siete', 8 => 'ocho', 9 => 'nueve'
    ];
    $letras_decenas = [
        10 => 'diez', 11 => 'once', 12 => 'doce', 13 => 'trece', 14 => 'catorce', 15 => 'quince',
        16 => 'dieciséis', 17 => 'diecisiete', 18 => 'dieciocho', 19 => 'diecinueve'
    ];
    $letras_decenas_multiplo = [
        2 => 'veinte', 3 => 'treinta', 4 => 'cuarenta', 5 => 'cincuenta',
        6 => 'sesenta', 7 => 'setenta', 8 => 'ochenta', 9 => 'noventa'
    ];

    if ($num < 10) {
        return $letras_unidades[$num];
    } elseif ($num < 20) {
        return $letras_decenas[$num];
    } elseif ($num < 100) {
        $decena = (int)($num / 10);
        $unidad = $num % 10;
        $texto = $letras_decenas_multiplo[$decena];
        if ($unidad > 0) {
            $texto .= ' y ' . $letras_unidades[$unidad];
        }
        return $texto;
    }
    return (string)$num; // para valores mayores que 99 puedes expandirlo si lo necesitas
}

    $dia_num = (int)$fechaFirma->format('d');
    $dia_letras = ucfirst(numeroALetrasSimple($dia_num));
    $template->setValue('dia_firma_contrato_texto', "$dia_letras ($dia_num)");

    $template->setValue('mes_firma_contrato', $meses_es[$fechaFirma->format('F')] ?? $fechaFirma->format('F'));
    $template->setValue('anio_firma_contrato', $fechaFirma->format('Y'));


    $template->setValue('valor_contrato_texto', formatearPesos($valor_total));

    $template->setValue('total_pago', formatearPesos($valor_total));

    $template->setValue('numero_depositos_texto', ucfirst(numeroALetrasSimple($meses_contratados)) . " ({$meses_contratados})");



    $template->cloneRow('No', $meses_contratados);
    $inicio = new DateTime($fecha_inicio);

for ($i = 1; $i <= $meses_contratados; $i++) {
    $mes_ingles = $inicio->format('F'); // Nombre del mes en inglés
    $anio = $inicio->format('Y');

    // Traducir mes
    $mes_es = $meses_es[$mes_ingles] ?? $mes_ingles;

    $template->setValue("No#{$i}", $i);
    $template->setValue("Mes#{$i}", $mes_es . ' ' . $anio);
    $template->setValue("Valor#{$i}", formatearPesos($valor_mensual));

    $inicio->modify('+1 month');
}



// Al final de tu código PhpWord
$nombre_archivo = 'Contrato_' . $numero_contrato . '.docx';
$ruta_archivo = __DIR__ . '/contratos_generados/' . $nombre_archivo;

// Guardar el archivo en la ruta correcta
$template->saveAs($ruta_archivo);

// Redirigir al usuario a contrato_generado.php
header("Location: contrato_generado.php?archivo=" . urlencode($nombre_archivo) . "&nombre_completo=" . urlencode($nombre_completo));
exit;


} else {
    echo "Error al insertar contrato: " . $stmt->error;
}
?>
