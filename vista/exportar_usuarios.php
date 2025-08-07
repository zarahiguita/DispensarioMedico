<?php
// Configurar las cabeceras para la descarga del archivo Excel
header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");
header("Content-Disposition: attachment; filename=tabla_usuario.xls");

include("../conexion/cn.phpxion/cn.php");;

// Consulta SQL para obtener los datos de la tabla 'tabla_usuario'
$sql = "SELECT id, (SELECT nombre_supervisor FROM tabla_supervisor  WHERE tabla_supervisor.id = tabla_empleados.id_supervisor) AS nombre_supervisor, (SELECT tabla_profesiones.profesiones FROM tabla_profesiones WHERE tabla_profesiones.id = tabla_empleados.id_profesiones) AS nombre_profesiones, (SELECT tabla_modalidad_contratacion.modalidad_de_contratacion FROM tabla_modalidad_contratacion WHERE tabla_empleados.id_modalidad_contratacion = tabla_modalidad_contratacion.id) AS
nombre_modalidad_contratacion,(SELECT tabla_tipo_contratacion.tipo_de_contratacion FROM tabla_tipo_contratacion WHERE tabla_empleados.id_tipo_contratacion = tabla_tipo_contratacion.id) AS nombre_tipo_contratacion, (SELECT tabla_salud.entidad_salud FROM tabla_salud  WHERE tabla_empleados.id_salud =tabla_salud.id) AS nombre_salud,(SELECT tabla_salud.entidad_de_pension FROM tabla_salud  WHERE tabla_empleados.id_salud =tabla_salud.id) AS nombre_pension, (SELECT tabla_cuenta_bancaria.no_cuenta FROM tabla_cuenta_bancaria WHERE tabla_empleados.id_cuentabancaria = tabla_cuenta_bancaria.id) AS no_cuenta, (SELECT tabla_cuenta_bancaria.tipo_de_cuenta FROM tabla_cuenta_bancaria WHERE tabla_empleados.id_cuentabancaria = tabla_cuenta_bancaria.id) As tipo_cuenta, (SELECT tabla_cuenta_bancaria.entidad_bancaria FROM tabla_cuenta_bancaria WHERE tabla_empleados.id_cuentabancaria = tabla_cuenta_bancaria.id) AS entidad_bancaria, (SELECT tabla_rubro.rubro_presupuestal FROM tabla_rubro WHERE tabla_empleados.id_rubro = tabla_rubro.id) AS rubor_presupuestal, (SELECT tabla_rubro.descripcion_rubro FROM tabla_rubro WHERE tabla_empleados.id_rubro = tabla_rubro.id) AS descripcion_rubro, no_proceso, no_registro, nombre_del_contratista, documento_de_identidad, unidad, lugar_de_expediccion_documento, fecha_expediccion_documento, fecha_nacimiento, direccion_de_residencia, telefono, correo_electronico, cod_verificacion_RUT, area_desempeñada FROM tabla_empleados;";
$resultado = $conexion->query($sql);

// Verificar si hay datos
if ($resultado->num_rows > 0) {
    // Crear la tabla en formato HTML para Excel
    echo "<table border='1'>";
    echo "<tr><th>No. REGISTRO 
</th><th>UNIDAD 
</th><th>NO. PROCESO
</th><th>NO. CONTRATO 
</th><th>OBJETO
</th><th>CDP
</th><th>FECHA CDP
</th><th>RP
</th><th>FECHA RP
</th><th> VALOR DE PAGO MENSUAL 
</th><th> VALOR TOTAL DEL CONTRATO  
</th><th>FECHA DE SUSCRIPCIÓN
</th><th>FECHA DE INICIO DEL CONTRATO 
</th><th>FECHA DE APROBACION DE POLIZA 
</th><th>FECHA DE TERMINACION DEL CONTRATO 
</th><th>MESES CONTRATADOS
</th><th>SUPERVISOR
</th><th>NOMBRE COMPLETO DEL CONTRATISTA
</th><th>DOCUMENTO DE IDENTIDAD
</th><th>LUGAR DE EXPEDICION DE DOCUMENTOO
</th><th>FECHA DE EXPEDICION DE DOCUEMENTO
</th><th>FECHA DE NACIMIENTO
</th><th>DIRECCION DE RESIDENCIA
</th><th>TELEFONO
</th><th>CORREO ELECTRONICO
</th><th>PROFESION
</th><th>CODIGO VERIFICACION DE RUT
</th><th>AREA DONDE SE DESEMPEÑA
</th><th>UNSPC
</th><th>TIPO DE CUENTA BANCARIA
</th><th>NUMERO DE CUENTA BANCARIA
</th><th>ENTIDAD BANCARIA
</th><th>ENTIDAD SALUD
</th><th>ENTIDAD PENSION
</th><th>ENTIDAD ARL
</th><th>MODALIDAD DE CONTRATACION
</th><th>TIPO DE CONTRATACION
</th><th>RUBRO PRESUPUESTAL
</th><th>DESCRIPCION DEL RUBRO
</th><th>No.POLIZA DE CUMPLIMIENTO
</th><th>FECHA INICIO POLIZA CUMPLIMIENTO
</th><th>FECHA TERMINACION POLIZA CUMPLIMIENTO
</th><th>No.POLIZA RCP
</th><th>FECHA INICIO POLIZA RCP
</th><th>FECHA TERMINACION POLIZA RCP
</th><th>FECHA APROBACION DE POLIZA 
</th><th>FECHA ACTA DE INICIO

</th></tr>";

    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$fila['no_registro']}</td>";
        echo "<td>{$fila['unidad']}</td>";
        echo "<td>{$fila['no_proceso']}</td>";
        echo "<td>{$fila['no_contratos']}</td>";
        echo "<td>{$fila['objecto']}</td>";
        echo "<td>{$fila['CDP']}</td>";
        echo "<td>{$fila['fecha_cdp']}</td>";
        echo "<td>{$fila['RP']}</td>";
        echo "<td>{$fila['fecha_rp']}</td>";
        echo "<td>{$fila['valor_pago_mensual']}</td>";
        echo "<td>{$fila['valor_total_del_contrato']}</td>";
        echo "<td>{$fila['fecha_suscripcion']}</td>";
        echo "<td>{$fila['fecha_inicio_contrato']}</td>";
        echo "<td>{$fila['fecha_aprobacion_poliza']}</td>";
        echo "<td>{$fila['fecha_terminacion_contrato']}</td>";
        echo "<td>{$fila['meses_contratados']}</td>";
        echo "<td>{$fila['nombre_supervisor']}</td>";
        echo "<td>{$fila['nombre_del_contratista']}</td>";
        echo "<td>{$fila['documento_identidad']}</td>";
        echo "<td>{$fila['lugar_de_expediccion_documento']}</td>";
        echo "<td>{$fila['fecha_expediccion_documento']}</td>";
        echo "<td>{$fila['direccion_de_residencia']}</td>";
        echo "<td>{$fila['telefono']}</td>";
        echo "<td>{$fila['correo_electronico']}</td>";
        echo "<td>{$fila['profesion']}</td>";
        echo "<td>{$fila['cod_verificacion_RUT']}</td>";
        echo "<td>{$fila['area_desempeñada']}</td>";
        echo "<td>{$fila['tipo_de_cuenta']}</td>";
        echo "<td>{$fila['no_cuenta']}</td>";
        echo "<td>{$fila['entidad_bancaria']}</td>";
        echo "<td>{$fila['entidad_salud']}</td>";
        echo "<td>{$fila['entidad_de_pension']}</td>";
        echo "<td>{$fila['modalidad_de_contratacion']}</td>";
        echo "<td>{$fila['tipo_de_contratacion']}</td>";
        echo "<td>{$fila['rubro_presupuestal']}</td>";
        echo "<td>{$fila['descripcion_rubro']}</td>";
        echo "<td>{$fila['no.poliza_cumplimiento']}</td>";
        echo "<td>{$fila['fecha_inicio_de_poliza_cumplimiento']}</td>";
        echo "<td>{$fila['fecha_terminacion_poliza_de_cumplimiento']}</td>";
        echo "<td>{$fila['no_poliza_RCP']}</td>";
        echo "<td>{$fila['fecha_inicio_poliza_RCP']}</td>";
        echo "<td>{$fila['fecha_terminacion_poliza_RCP']}</td>";
        echo "<td>{$fila['fecha_aprobacion_poliza']}</td>";
        echo "<td>{$fila['fecha_acta_inicio']}</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No se encontraron registros.";
}

// Cerrar conexión
$conexion->close();
?>