SELECT
  e.no_registro AS `No. REGISTRO`,-- por que el numero de registro esta en empleado y no en contrato
  e.unidad AS `UNIDAD`, -- igual por que esta aqui y no en contrato
  e.no_proceso AS `NO. PROCESO`,-- igual por que esta aqui y no en contrato
  c.no_contrato AS `NO. CONTRATO`, -- igual por que esta aqui y no en contrato
  e.area_desempenada AS `OBJETO`, -- Tenemos que ver de donde se saca o donde lo guardamos
  c.cdp AS `CDP`,
  c.fecha_rp AS `FECHA CDP`,
  c.rp AS `RP`,
  c.fecha_rp AS `FECHA RP`,
  c.valor_mensual AS `VALOR DE PAGO MENSUAL`,
  c.valor_total AS `VALOR TOTAL DEL CONTRATO`,
  c.fecha_suscripcion AS `FECHA DE SUSCRIPCIÓN`,
  c.fecha_inicio AS `FECHA DE INICIO DEL CONTRATO`,
  p.fecha_aprobacion AS `FECHA DE APROBACION DE POLIZA`,
  c.fecha_terminacion AS `FECHA DE TERMINACION DEL CONTRATO`,
  c.meses_contratados AS `MESES CONTRATADOS`,
  s.nombre_supervisor AS `SUPERVISOR`,
  e.nombre_contratista AS `NOMBRE COMPLETO DEL CONTRATISTA`,
  e.documento_identidad AS `DOCUMENTO DE IDENTIDAD`,
  e.lugar_expedicion_documento AS `LUGAR DE EXPEDICIÓN DE DOCMENTO`,
  e.fecha_expedicion_documento AS `FECHA EXPEDICION DOCUMENTO DE IDENTIDAD`,
  e.fecha_nacimiento AS `FECHA DE NACIMIENTO`,
  e.direccion_residencia AS `DIRECCION DE RESIDENCIA`,
  e.telefono AS `TELEFONO`,
  e.correo_electronico AS `CORREO ELECTRONICO`,
  pr.profesion AS `PROFESION`,
  e.cod_verificacion_rut AS `CODIGO VERIFICACIÓN DE RUT`,
  e.area_desempenada AS `AREA DONDE SE DESEMPEÑA`,
  '' AS `UNSPC`, -- Este campo no está en las tablas
  cb.tipo_cuenta AS `TIPO DE CUENTA BANCARIA`,
  cb.no_cuenta AS `NUMERO DE CUENTA BANCARIA`,
  b.nombre AS `ENTIDAD BANCARIA`,
  sa.entidad_salud AS `ENTIDAD DE SALUD`,
  sa.entidad_pension AS `ENTIDAD DE PENSIÓN`,
  sa.entidad_arl AS `ENTIDAD DE ARL`,
  mc.modalidad AS `MODALIDAD DE CONTRATACION`,
  tc.tipo_contratacion AS `TIPO DE CONTRATACION`,
  r.rubro_presupuestal AS `RUBRO PRESUPUESTAL`,
  r.descripcion_rubro AS `DESCRIPCION DEL RUBRO`,
  p.no_poliza_cumplimiento AS `No. POLIZA DE CUMPLIMIENTO`,
  p.fecha_inicio_cumplimiento AS `FECHA INICIO POLIZA CUMPLIMIENTO`,
  p.fecha_terminacion_cumplimiento AS `FECHA TERMINACION POLIZA CUMPLIMIENTO`,
  p.no_poliza_rcp AS `No. POLIZA RCP`,
  p.fecha_inicio_rcp AS `FECHA INICIO POLIZA RCP`,
  p.fecha_terminacion_rcp AS `FECHA TERMINACION POLIZA RCP`,
  p.fecha_aprobacion AS `FECHA DE APROBACION DE POLIZA`,
  c.fecha_acta_inicio AS `FECHA ACTA INICIO`
FROM contratos c
JOIN empleados e ON c.id_empleados = e.id
LEFT JOIN empleados_x_profesion ep ON ep.id_empleado = e.id
LEFT JOIN profesiones pr ON pr.id = ep.id_profesion
LEFT JOIN cuenta_bancaria cb ON c.id_cuenta_bancaria = cb.id
LEFT JOIN banco b ON cb.id_banco = b.id
LEFT JOIN salud sa ON e.id_salud = sa.id
LEFT JOIN supervisor s ON e.id_supervisor = s.id
LEFT JOIN modalidad_contratacion mc ON c.id_modalidad_contratacion = mc.id
LEFT JOIN tipo_contratacion tc ON c.id_tipo_contratacion = tc.id
LEFT JOIN rubro r ON c.id_rubro = r.id
LEFT JOIN poliza p ON e.id_poliza = p.id
LEFT JOIN rubro cdp ON c.id_rubro = cdp.id
ORDER BY c.no_contrato;