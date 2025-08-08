CREATE DATABASE IF NOT EXISTS gestion_contratacion;
USE gestion_contratacion;

CREATE TABLE rp (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(100) NOT NULL,
    fecha DATE,
    valor DECIMAL(15,2)
);

CREATE TABLE cep (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(100) NOT NULL,
    fecha DATE,
    valor DECIMAL(15,2)
);

CREATE TABLE tipo_banco (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(100) NOT NULL
);

CREATE TABLE objeto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

CREATE TABLE aseguradora (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nit VARCHAR(100) NOT NULL,
    nombre VARCHAR(100) NOT NULL
   
);

CREATE TABLE aseguradora_respo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nit VARCHAR(100) NOT NULL,
    nombre VARCHAR(100) NOT NULL
   
);

CREATE TABLE jefe_contratos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    cedula VARCHAR(100) NOT NULL
   
);

-- Tabla de asesor juridico
CREATE TABLE asesor_juridico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    cedula VARCHAR(100) NOT NULL
);

-- Tabla de unidad
CREATE TABLE unidad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    unidad VARCHAR(100) NOT NULL
);

-- Tabla de ordenador del gasto
CREATE TABLE ordenador_gasto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    grado VARCHAR(100) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    cedula VARCHAR(100) NOT NULL
    lugar_expedicion_cedula VARCHAR(100) NOT NULL
);


-- Tabla de bancos
CREATE TABLE banco (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);


-- Tabla profesiones
CREATE TABLE profesiones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    profesion VARCHAR(100),
    fecha_creacion DATE,
    fecha_actualizacion DATE
);

-- Tabla de modalidad de contratación
CREATE TABLE modalidad_contratacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    modalidad VARCHAR(100),
    fecha_creacion DATE,
    fecha_actualizacion DATE
);

-- Tabla tipo de contratación
CREATE TABLE tipo_contratacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_contratacion VARCHAR(100),
    fecha_creacion DATE,
    fecha_actualizacion DATE
);

-- Tabla rubro
CREATE TABLE rubro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rubro_presupuestal VARCHAR(100),
    descripcion_rubro TEXT,
    fecha_creacion DATE,
    fecha_actualizacion DATE
);

-- Tabla salud
CREATE TABLE salud (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entidad_salud VARCHAR(100),
    entidad_pension VARCHAR(100),
    entidad_arl VARCHAR(100),
    fecha_creacion DATE,
    fecha_actualizacion DATE
);

-- Tabla supervisor
CREATE TABLE supervisor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_supervisor VARCHAR(100),
    fecha_creacion DATE,
    fecha_actualizacion DATE
);

-- Tabla lugar Ejecucion
CREATE TABLE lugar_ejecucion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lugar VARCHAR(100),
    direccion VARCHAR(150)
);

-- Tabla pólizas
CREATE TABLE poliza (
    id INT AUTO_INCREMENT PRIMARY KEY,
    no_poliza_cumplimiento VARCHAR(50),
    fecha_inicio_cumplimiento DATE,
    fecha_terminacion_cumplimiento DATE,
    no_poliza_rcp VARCHAR(50),
    fecha_inicio_rcp DATE,
    fecha_terminacion_rcp DATE,
    fecha_aprobacion DATE,
    fecha_creacion DATE,
    fecha_actualizacion DATE
);

-- Tabla login
CREATE TABLE login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(100),
    contrasena VARCHAR(100),
    rol VARCHAR(50),
    fecha_creacion DATE,
    fecha_actualizacion DATE
);

CREATE TABLE contrato_detallado (
    no_registro VARCHAR(50),
    unidad VARCHAR(50),
    no_proceso VARCHAR(50),
    no_contrato VARCHAR(100),
    objeto TEXT,
    cdp VARCHAR(50),
    fecha_cdp DATE,
    rp VARCHAR(50),
    fecha_rp DATE,
    valor_pago_mensual DECIMAL(15,2),
    valor_total_contrato DECIMAL(15,2),
    fecha_suscripcion DATE,
    fecha_inicio_contrato DATE,
    fecha_aprobacion_poliza DATE,
    fecha_terminacion_contrato DATE,
    meses_contratados VARCHAR(50),
    supervisor VARCHAR(100),
    nombre_completo_contratista VARCHAR(100),
    documento_identidad VARCHAR(30),
    lugar_expedicion_documento VARCHAR(100),
    fecha_expedicion_documento DATE,
    fecha_nacimiento DATE,
    direccion_residencia VARCHAR(150),
    telefono VARCHAR(30),
    correo_electronico VARCHAR(100),
    profesion VARCHAR(100),
    cod_verificacion_rut VARCHAR(20),
    area_desempeno VARCHAR(100),
    unspc VARCHAR(50),
    tipo_cuenta_bancaria VARCHAR(50),
    numero_cuenta_bancaria VARCHAR(50),
    entidad_bancaria VARCHAR(100),
    entidad_salud VARCHAR(100),
    entidad_pension VARCHAR(100),
    entidad_arl VARCHAR(100),
    modalidad_contratacion VARCHAR(100),
    tipo_contratacion VARCHAR(150),
    rubro_presupuestal VARCHAR(100),
    descripcion_rubro TEXT,
    no_poliza_cumplimiento VARCHAR(50),
    fecha_inicio_poliza_cumplimiento DATE,
    fecha_terminacion_poliza_cumplimiento DATE,
    no_poliza_rcp VARCHAR(50),
    fecha_inicio_poliza_rcp DATE,
    fecha_terminacion_poliza_rcp DATE,
    fecha_aprobacion_poliza2 DATE,
    fecha_acta_inicio DATE,
    nombre_asesor_juridico VARCHAR(100),
    es_firmado TINYINT(1)
);
