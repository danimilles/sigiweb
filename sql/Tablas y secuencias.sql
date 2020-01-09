--***************************************************************************/
-- INTRODUCCION A LA INGENIERIA DE SOFTWARE Y SISTEMAS DE INFORMACION
-- GRADO EN INGENIERIA INFORMATICA - INGENIERIA DEL SOFTWARE
-- UNIVERSIDAD DE SEVILLA
-- CURSO 2018-2019
-- GRUPO 3
--
-- (C)  DANIEL SANCHEZ BALEYRON
--      JORGE RAPOSO DÍAZ
--      JOSE RAMON FERNANDEZ DE LA ROSA
--
-- SISTEMA DE INTEGRAL DE GESTION DE INSTITUTOS - SIGI
-- Tablas y secuencias.sql
--***************************************************************************/

--Borrado de tablas

DROP TABLE horarios;
DROP TABLE reservasaulas;
DROP TABLE aulas;
DROP TABLE reservasmateriales;
DROP TABLE materiales;
DROP TABLE prestamos;
DROP TABLE alumnos;
ALTER TABLE departamentos DROP CONSTRAINT fk_depts_jefe;
DROP TABLE profesores;
DROP TABLE departamentos;
DROP TABLE ejemplares;
DROP TABLE libros;
DROP TABLE usuarios;


--Creación de tablas

CREATE TABLE usuarios (
num_carne           CHAR(6) PRIMARY KEY,
CONSTRAINT usuarios_carne CHECK(REGEXP_LIKE(num_carne, '[0-9][0-9][0-9][0-9][0-9][A, P]')),
pass                VARCHAR2(255) NOT NULL,
nombre              VARCHAR2(50) NOT NULL,
apellidos           VARCHAR2(50) NOT NULL,
dni                 CHAR(9) NULL UNIQUE,
CONSTRAINT usuarios_dni CHECK(REGEXP_LIKE(dni, '[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][A-Z]')),
fechanacimiento     DATE NOT NULL,
sexo                CHAR(1) NOT NULL,
CONSTRAINT tipo_sexo CHECK(sexo IN('M', 'F')),
email               VARCHAR2(100) NOT NULL UNIQUE,
CONSTRAINT usuarios_email CHECK(email LIKE '%@%'),
telefono            INTEGER,
fecha_inicio_carne    DATE NOT NULL,
fecha_validez_carne   DATE NOT NULL,
foto                BLOB default null
);

CREATE TABLE alumnos (
num_carne    CHAR(6) PRIMARY KEY,
PROGRAMA_ACADEMICO VARCHAR2(50),
CONSTRAINT programa CHECK(programa_academico IN('ESO', 'BACHILLERATO', 'FP Básica', 'CFGM Gestión Administrativa', 'CFGS Administración y Finanzas')),
curso       INTEGER,
CONSTRAINT cursos CHECK(curso > 0 AND curso < 5),
grupo       CHAR(1),
FOREIGN KEY (num_carne) REFERENCES usuarios(num_carne) ON DELETE CASCADE
);

CREATE TABLE departamentos (
oid_d INTEGER PRIMARY KEY,
nombre VARCHAR2(50) NOT NULL UNIQUE,
jefe_departamento CHAR(6) unique
);

CREATE TABLE profesores (
num_carne    CHAR(6) PRIMARY KEY,
oid_d       INTEGER,
FOREIGN KEY (num_carne) REFERENCES usuarios(num_carne) ON DELETE CASCADE,
FOREIGN KEY (oid_d) REFERENCES departamentos(oid_d)
);

CREATE TABLE libros (
isbn VARCHAR2(14) PRIMARY KEY,
titulo VARCHAR2(50) NOT NULL,
autor VARCHAR2(50) NOT NULL,
cdu INTEGER NOT NULL,
genero VARCHAR2(50) NOT NULL,
CONSTRAINT libros_genero CHECK(genero IN ('Aventura', 'Acción', 'Comedia', 'Suspense', 'Terror', 'Romance', 'Thriller', 'Drama', 'Fantasía', 'Docencia', 'Novela')),
copias INTEGER DEFAULT 0 NOT NULL,
CONSTRAINT libros_copias CHECK(copias >= 0)
);

CREATE TABLE ejemplares (
codigo INTEGER PRIMARY KEY,
estado VARCHAR2(15) NOT NULL,
CONSTRAINT ejemplar_estado CHECK(estado IN('Perfecto', 'Bueno', 'Deteriorado', 'Malo')),
disponible INTEGER DEFAULT 1 NOT NULL,
CONSTRAINT ejemplar_disponible CHECK(disponible=1 OR disponible=0),
fecha_baja DATE DEFAULT NULL,
isbn VARCHAR2(14) NOT NULL,
FOREIGN KEY (isbn) REFERENCES libros(isbn)
);

CREATE TABLE prestamos (
oid_p INTEGER,
num_carne CHAR(6) NOT NULL,
codigo INTEGER NOT NULL,
fecha_inicio DATE DEFAULT sysdate NOT NULL,
fecha_fin DATE DEFAULT NULL,
fecha_entrega DATE DEFAULT NULL,
PRIMARY KEY(oid_p),
FOREIGN KEY (codigo) REFERENCES ejemplares(codigo),
FOREIGN KEY (num_carne) REFERENCES usuarios(num_carne)
);

CREATE TABLE materiales (
oid_m INTEGER PRIMARY KEY,
descripcion VARCHAR2(100) NOT NULL,
estado VARCHAR2(15) NOT NULL,
CONSTRAINT estados_materiales CHECK(estado IN('Perfecto', 'Bueno', 'Deteriorado', 'Malo')),
fecha_alta DATE DEFAULT sysdate NOT NULL,
fecha_baja DATE DEFAULT NULL,
CONSTRAINT materiales_fecha CHECK(fecha_alta<fecha_baja),
unidades INTEGER NOT NULL,
CONSTRAINT materiales_unidades CHECK(unidades>0),
oid_d INTEGER NOT NULL,
FOREIGN KEY (oid_d) REFERENCES departamentos(oid_d)
);

CREATE TABLE aulas (
numero INTEGER PRIMARY KEY,
nombre VARCHAR2(50) NOT NULL UNIQUE
);

CREATE TABLE reservasmateriales (
oid_rm INTEGER,
oid_m INTEGER NOT NULL,
num_carne CHAR(6) NOT NULL,
fecha_reserva DATE NOT NULL,
tramo INTEGER NOT NULL,
CONSTRAINT reservamaterial_tramos CHECK(tramo BETWEEN 1 AND 6),
PRIMARY KEY(oid_rm),
FOREIGN KEY (oid_m) REFERENCES materiales(oid_m),
FOREIGN KEY (num_carne) REFERENCES profesores(num_carne)
);

CREATE TABLE reservasaulas (
oid_ra INTEGER,
numero INTEGER NOT NULL,
num_carne CHAR(6) NOT NULL,
fecha_reserva DATE NOT NULL,
tramo INTEGER NOT NULL,
CONSTRAINT reservasaulas_tramo CHECK(tramo BETWEEN 1 AND 6),
PRIMARY KEY(oid_ra),
FOREIGN KEY (numero) REFERENCES aulas(numero),
FOREIGN KEY (num_carne) REFERENCES profesores(num_carne)
);

CREATE TABLE horarios (
oid_h integer PRIMARY KEY,
num_carne char(6) NOT NULL,
dia VARCHAR2(11) NOT NULL,
asignatura VARCHAR2(50) NOT NULL,
tramo integer not null,
CONSTRAINT horarios_dia CHECK(dia IN ('Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes')),
CONSTRAINT horarios_tramo CHECK(tramo between 1 and 6),
foreign key(num_carne) references usuarios(num_carne)
);

ALTER TABLE departamentos ADD CONSTRAINT fk_depts_jefe FOREIGN KEY (jefe_departamento) REFERENCES profesores(num_carne) ON DELETE SET NULL;

--Borrar secuencias

DROP SEQUENCE "SEC_ALUMNOS";
DROP SEQUENCE "SEC_DEPARTAMENTOS";
DROP SEQUENCE "SEC_EJEMPLARES";
DROP SEQUENCE "SEC_MATERIALES";
DROP SEQUENCE "SEC_PRESTAMOS";
DROP SEQUENCE "SEC_PROFESORES";
DROP SEQUENCE "SEC_RESERVA_AUL";
DROP SEQUENCE "SEC_RESERVA_MAT";
DROP SEQUENCE "SEC_HORARIOS";


--Creacion de secuencias

CREATE SEQUENCE  "SEC_ALUMNOS"  MINVALUE 1 MAXVALUE 99999 INCREMENT BY 1 START WITH 1 CACHE 20 ORDER  NOCYCLE ;
/

CREATE SEQUENCE  "SEC_DEPARTAMENTOS"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 ORDER  NOCYCLE ;
/

CREATE SEQUENCE  "SEC_EJEMPLARES"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 ORDER  NOCYCLE ;
/

CREATE SEQUENCE  "SEC_MATERIALES"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 ORDER  NOCYCLE ;
/

CREATE SEQUENCE  "SEC_PRESTAMOS"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 ORDER  NOCYCLE ;
/

CREATE SEQUENCE  "SEC_PROFESORES"  MINVALUE 1 MAXVALUE 99999 INCREMENT BY 1 START WITH 1 CACHE 20 ORDER  NOCYCLE ;
/

CREATE SEQUENCE  "SEC_RESERVA_AUL"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 ORDER  NOCYCLE ;
/

CREATE SEQUENCE  "SEC_RESERVA_MAT"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 ORDER  NOCYCLE ;
/

CREATE SEQUENCE  "SEC_HORARIOS"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 ORDER  NOCYCLE ;
/

--Trigger secuenciales

CREATE OR REPLACE TRIGGER tr_sec_departamentos 
BEFORE INSERT ON departamentos
FOR EACH ROW
DECLARE
    valor_sec INTEGER;
BEGIN
  SELECT sec_departamentos.NEXTVAL INTO valor_sec FROM dual;
  :NEW.oid_d := valor_sec;
END;
/

CREATE OR REPLACE TRIGGER tr_sec_ejemplares 
BEFORE INSERT ON ejemplares 
FOR EACH ROW
DECLARE
    valor_sec INTEGER;
BEGIN
  SELECT sec_ejemplares.NEXTVAL INTO valor_sec FROM dual;
  :NEW.codigo := valor_sec;
END;
/

CREATE OR REPLACE TRIGGER tr_sec_materiales 
BEFORE INSERT ON materiales 
FOR EACH ROW
DECLARE
 valor_sec INTEGER;
BEGIN
  SELECT sec_materiales.NEXTVAL INTO valor_sec FROM dual;
  :NEW.oid_m := valor_sec;
END;
/

CREATE OR REPLACE TRIGGER tr_sec_prestamos 
BEFORE INSERT ON prestamos
FOR EACH ROW
DECLARE
    valor_sec INTEGER;
BEGIN
  SELECT sec_prestamos.NEXTVAL INTO valor_sec FROM dual;
  :NEW.oid_p := valor_sec;
END;
/

CREATE OR REPLACE TRIGGER tr_sec_reservasaulas 
BEFORE INSERT ON reservasaulas 
FOR EACH ROW
DECLARE
 valor_sec INTEGER;
BEGIN
  SELECT sec_reserva_aul.NEXTVAL INTO valor_sec FROM dual;
  :NEW.oid_ra := valor_sec;
END;
/

CREATE OR REPLACE TRIGGER tr_sec_reservasmat 
BEFORE INSERT ON reservasmateriales 
FOR EACH ROW
DECLARE
    valor_sec INTEGER;
BEGIN
  SELECT sec_reserva_mat.NEXTVAL INTO valor_sec FROM dual;
  :NEW.oid_rm := valor_sec;
END;
/

CREATE OR REPLACE TRIGGER tr_sec_horarios
BEFORE INSERT ON horarioS
FOR EACH ROW
DECLARE
    valor_sec INTEGER;
BEGIN
  SELECT sec_horarioS.NEXTVAL INTO valor_sec FROM dual;
  :NEW.oid_h := valor_sec;
END;
/

CREATE OR REPLACE TRIGGER tr_sec_usuarios 
BEFORE INSERT ON usuarios 
FOR EACH ROW
DECLARE
    valor_sec INTEGER;
    sec_string VARCHAR2(6);
BEGIN
    IF substr(:NEW.num_carne, 6) = 'A'
        THEN SELECT sec_alumnos.NEXTVAL INTO valor_sec FROM dual;
        SELECT to_char(valor_sec) INTO sec_string FROM dual;
    ELSE 
        SELECT sec_profesores.NEXTVAL INTO valor_sec FROM dual;
        SELECT to_char(valor_sec) INTO sec_string FROM dual;
    END IF;
    
    IF LENGTH(sec_string) = 1
        THEN SELECT CONCAT(CONCAT('0000', sec_string), substr(:NEW.num_carne, 6)) INTO sec_string FROM dual;
    ELSE IF LENGTH(sec_string) = 2
        THEN SELECT CONCAT(CONCAT('000', sec_string), substr(:NEW.num_carne, 6)) INTO sec_string FROM dual;
    ELSE IF LENGTH(sec_string) = 3
        THEN SELECT CONCAT(CONCAT('00', sec_string), substr(:NEW.num_carne, 6)) INTO sec_string FROM dual;
    ELSE IF LENGTH(sec_string) = 4
        THEN SELECT CONCAT(CONCAT('0', sec_string), substr(:NEW.num_carne, 6)) INTO sec_string FROM dual;
    ELSE 
        SELECT CONCAT(sec_string, substr(:NEW.num_carne, 6)) INTO sec_string FROM dual;
    END IF;
    END IF;
    END IF;
    END IF;
    
    :NEW.num_carne := sec_string;
END;
/