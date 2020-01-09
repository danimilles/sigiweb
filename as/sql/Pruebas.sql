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
-- Pruebas.sql
--***************************************************************************/

--DECLARACIÓN DE PAQUETES

--Alumnos (tablas usuarios y alumnos, no se pueden hacer pruebas separadas de las tablas; 
--cuando se crea un usuario, un trigger crea un alumno o un profesor)

CREATE OR REPLACE PACKAGE pruebas_alumnos AS 
   PROCEDURE inicializar;
   PROCEDURE insertar (nombre_prueba VARCHAR2, w_nombre usuarios.nombre%TYPE, w_apellidos usuarios.apellidos%TYPE, w_dni usuarios.dni%TYPE,
   w_fecha_nac usuarios.fechanacimiento%TYPE, w_sexo usuarios.sexo%TYPE, w_email usuarios.email%TYPE, w_telefono usuarios.telefono%TYPE,
   w_estudios alumnos.programa_academico%TYPE, w_curso alumnos.curso%TYPE, w_grupo alumnos.grupo%TYPE, salidaesperada BOOLEAN);
   PROCEDURE actualizar (nombre_prueba VARCHAR2, w_num_carne usuarios.num_carne%TYPE, w_nombre usuarios.nombre%TYPE,
   w_apellidos usuarios.apellidos%TYPE, w_dni usuarios.dni%TYPE, w_fecha_nac usuarios.fechanacimiento%TYPE, w_sexo usuarios.sexo%TYPE,
   w_email usuarios.email%TYPE, w_telefono usuarios.telefono%TYPE, w_estudios alumnos.programa_academico%TYPE, w_curso alumnos.curso%TYPE,
   w_grupo alumnos.grupo%TYPE, salidaesperada BOOLEAN);
   PROCEDURE eliminar (nombre_prueba VARCHAR2, w_num_carne usuarios.num_carne%TYPE, salidaesperada BOOLEAN);

END pruebas_alumnos;
/

--Profesores (tablas usuarios y profesores. no se pueden hacer pruebas separadas de las tablas; cuando se crea un usuario, 
--un trigger crea un alumno o un profesor)

CREATE OR REPLACE PACKAGE pruebas_profesores AS 
   PROCEDURE inicializar;
   PROCEDURE insertar (nombre_prueba VARCHAR2, w_nombre usuarios.nombre%TYPE, w_apellidos usuarios.apellidos%TYPE, w_dni usuarios.dni%TYPE,
   w_fecha_nac usuarios.fechanacimiento%TYPE, w_sexo usuarios.sexo%TYPE, w_email usuarios.email%TYPE, w_telefono usuarios.telefono%TYPE,
   w_nombre_d departamentos.nombre%TYPE, salidaesperada BOOLEAN);
   PROCEDURE actualizar (nombre_prueba VARCHAR2, w_num_carne usuarios.num_carne%TYPE, w_nombre usuarios.nombre%TYPE,
   w_apellidos usuarios.apellidos%TYPE, w_dni usuarios.dni%TYPE, w_fecha_nac usuarios.fechanacimiento%TYPE, w_sexo usuarios.sexo%TYPE,
   w_email usuarios.email%TYPE, w_telefono usuarios.telefono%TYPE, w_oid_d profesores.oid_d%TYPE, salidaesperada BOOLEAN);
   PROCEDURE eliminar (nombre_prueba VARCHAR2, w_num_carne usuarios.num_carne%TYPE, salidaesperada BOOLEAN);

END pruebas_profesores;
/

--Departamentos

CREATE OR REPLACE 
PACKAGE pruebas_departamentos AS 

    PROCEDURE inicializar;
    PROCEDURE insertar (nombre_prueba VARCHAR2, w_nombre departamentos.nombre%TYPE, salidaesperada BOOLEAN);
    PROCEDURE actualizar (nombre_prueba VARCHAR2, w_oid_d departamentos.oid_d%TYPE, w_nombre departamentos.nombre%TYPE,
    w_jefe departamentos.jefe_departamento%TYPE, salidaesperada BOOLEAN);
    PROCEDURE eliminar (nombre_prueba VARCHAR2, w_oid_d departamentos.oid_d%TYPE, salidaesperada BOOLEAN);

END pruebas_departamentos;
/

--Libros

CREATE OR REPLACE PACKAGE pruebas_libros AS 
   PROCEDURE inicializar;
   PROCEDURE insertar (nombre_prueba VARCHAR2, w_isbn libros.isbn%TYPE, w_titulo libros.titulo%TYPE, w_autor libros.autor%TYPE,
   w_cdu libros.cdu%TYPE, w_genero libros.genero%TYPE, salidaesperada BOOLEAN);
   PROCEDURE actualizar (nombre_prueba VARCHAR2, w_isbn libros.isbn%TYPE, w_titulo libros.titulo%TYPE, w_autor libros.autor%TYPE,
   w_cdu libros.cdu%TYPE, w_genero libros.genero%TYPE, w_copias libros.copias%TYPE, salidaesperada BOOLEAN);
   PROCEDURE eliminar (nombre_prueba VARCHAR2, w_isbn libros.isbn%TYPE, salidaesperada BOOLEAN);

END pruebas_libros;
/

--Aulas

CREATE OR REPLACE PACKAGE pruebas_aulas AS 
   PROCEDURE inicializar;
   PROCEDURE insertar (nombre_prueba VARCHAR2, w_numero aulas.numero%TYPE, w_nombre aulas.nombre%TYPE, salidaesperada BOOLEAN);
   PROCEDURE actualizar (nombre_prueba VARCHAR2, w_numero aulas.numero%TYPE, w_nombre aulas.nombre%TYPE, salidaesperada BOOLEAN);
   PROCEDURE eliminar (nombre_prueba VARCHAR2, w_numero aulas.numero%TYPE, salidaesperada BOOLEAN);

END pruebas_aulas;
/

--Materiales

CREATE OR REPLACE PACKAGE pruebas_materiales AS 
   PROCEDURE inicializar;
   PROCEDURE insertar (nombre_prueba VARCHAR2, w_descripcion materiales.descripcion%TYPE, w_estado materiales.estado%TYPE, 
   w_fecha_alta materiales.fecha_alta%TYPE, w_unidades materiales.unidades%TYPE, w_oid_d materiales.oid_d%TYPE, 
   salidaesperada BOOLEAN);
   PROCEDURE actualizar (nombre_prueba VARCHAR2, w_oid_m materiales.oid_m%TYPE, w_descripcion materiales.descripcion%TYPE, 
   w_estado materiales.estado%TYPE, w_fecha_alta materiales.fecha_alta%TYPE, w_fecha_baja materiales.fecha_baja%TYPE, 
   w_unidades materiales.unidades%TYPE, w_oid_d materiales.oid_d%TYPE, salidaesperada BOOLEAN);
   PROCEDURE eliminar (nombre_prueba VARCHAR2, w_oid_m materiales.oid_m%TYPE, salidaesperada BOOLEAN);

END pruebas_materiales;
/

--Ejemplares

CREATE OR REPLACE PACKAGE pruebas_ejemplares AS 
   PROCEDURE inicializar;
   PROCEDURE insertar (nombre_prueba VARCHAR2, w_estado ejemplares.estado%TYPE, w_isbn ejemplares.isbn%TYPE, salidaesperada BOOLEAN);
   PROCEDURE actualizar (nombre_prueba VARCHAR2, w_codigo ejemplares.codigo%TYPE, w_estado ejemplares.estado%TYPE, 
   w_disponible ejemplares.disponible%TYPE, w_fecha_baja ejemplares.fecha_baja%TYPE, w_isbn ejemplares.isbn%TYPE, salidaesperada BOOLEAN);
   PROCEDURE eliminar (nombre_prueba VARCHAR2, w_codigo ejemplares.codigo%TYPE, salidaesperada BOOLEAN);

END pruebas_ejemplares;
/

--Prestamos

CREATE OR REPLACE PACKAGE pruebas_prestamos AS 
   PROCEDURE inicializar;
   PROCEDURE insertar (nombre_prueba VARCHAR2, w_num_carne prestamos.num_carne%TYPE, w_codigo prestamos.codigo%TYPE, salidaesperada BOOLEAN);
   PROCEDURE actualizar (nombre_prueba VARCHAR2, w_oid_p prestamos.oid_p%TYPE, w_num_carne prestamos.num_carne%TYPE, 
   w_codigo prestamos.codigo%TYPE, w_fecha_inicio prestamos.fecha_inicio%TYPE, w_fecha_fin prestamos.fecha_fin%TYPE, 
   w_fecha_entrega prestamos.fecha_entrega%TYPE,salidaesperada BOOLEAN);
   PROCEDURE eliminar (nombre_prueba VARCHAR2, w_oid_p prestamos.oid_p%TYPE, salidaesperada BOOLEAN);

END pruebas_prestamos;
/

--ReservasMateriales

CREATE OR REPLACE PACKAGE pruebas_reservasmateriales AS 
   PROCEDURE inicializar;
   PROCEDURE insertar (nombre_prueba VARCHAR2, w_material reservasmateriales.oid_m%TYPE, w_num_carne reservasmateriales.num_carne%TYPE,
   w_fecha_reserva reservasmateriales.fecha_reserva%TYPE, w_tramo reservasmateriales.tramo%TYPE, salidaesperada BOOLEAN);
   PROCEDURE actualizar (nombre_prueba VARCHAR2, w_oid_rm reservasmateriales.oid_rm%TYPE, w_material reservasmateriales.oid_m%TYPE, 
   w_num_carne reservasmateriales.num_carne%TYPE, w_fecha_reserva reservasmateriales.fecha_reserva%TYPE, w_tramo reservasmateriales.tramo%TYPE,
   salidaesperada BOOLEAN);
   PROCEDURE eliminar (nombre_prueba VARCHAR2, w_oid_rm reservasmateriales.oid_rm%TYPE, salidaesperada BOOLEAN);

END pruebas_reservasmateriales;
/

--ReservasAulas

CREATE OR REPLACE PACKAGE pruebas_reservasaulas AS 
   PROCEDURE inicializar;
   PROCEDURE insertar (nombre_prueba VARCHAR2, w_aula reservasaulas.numero%TYPE, w_num_carne reservasaulas.num_carne%TYPE,
   w_fecha_reserva reservasaulas.fecha_reserva%TYPE, w_tramo reservasaulas.tramo%TYPE, salidaesperada BOOLEAN);
   PROCEDURE actualizar (nombre_prueba VARCHAR2, w_oid_ra reservasaulas.oid_ra%TYPE, w_aula reservasaulas.numero%TYPE, 
   w_num_carne reservasaulas.num_carne%TYPE, w_fecha_reserva reservasaulas.fecha_reserva%TYPE, w_tramo reservasaulas.tramo%TYPE,
   salidaesperada BOOLEAN);
   PROCEDURE eliminar (nombre_prueba VARCHAR2, w_oid_ra reservasaulas.oid_ra%TYPE, salidaesperada BOOLEAN);

END pruebas_reservasaulas;
/

--CUERPOS DE PAQUETES

--Alumnos

CREATE OR REPLACE
PACKAGE BODY pruebas_alumnos AS

  PROCEDURE inicializar AS
  BEGIN
   DELETE FROM prestamos;
   DELETE FROM alumnos;
   DELETE FROM reservasmateriales;
   DELETE FROM reservasaulas;
   DELETE FROM materiales;
   DELETE FROM aulas;
   DELETE FROM ejemplares;
   DELETE FROM libros;
   DELETE FROM profesores;
   DELETE FROM departamentos;
   DELETE FROM usuarios;
   
  END inicializar;

  PROCEDURE insertar (nombre_prueba VARCHAR2, w_nombre usuarios.nombre%TYPE, w_apellidos usuarios.apellidos%TYPE, w_dni usuarios.dni%TYPE,
  w_fecha_nac usuarios.fechanacimiento%TYPE, w_sexo usuarios.sexo%TYPE, w_email usuarios.email%TYPE, w_telefono usuarios.telefono%TYPE,
  w_estudios alumnos.programa_academico%TYPE, w_curso alumnos.curso%TYPE, w_grupo alumnos.grupo%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    alumno alumnos%rowtype;
    usuario usuarios%rowtype;
    w_num_carne CHAR(6);
  BEGIN
    alta_alumno(w_nombre, w_apellidos, w_dni, w_fecha_nac, w_sexo, w_email, w_telefono, w_estudios, w_curso, w_grupo);
    w_num_carne := carne_alumno(sec_alumnos.CURRVAL);
    SELECT * INTO usuario FROM usuarios WHERE num_carne = w_num_carne;
    SELECT * INTO alumno FROM alumnos WHERE num_carne = w_num_carne;
    IF(usuario.nombre <> w_nombre OR alumno.programa_academico <> w_estudios)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;

  END insertar;

  PROCEDURE actualizar (nombre_prueba VARCHAR2, w_num_carne usuarios.num_carne%TYPE, w_nombre usuarios.nombre%TYPE,
   w_apellidos usuarios.apellidos%TYPE, w_dni usuarios.dni%TYPE, w_fecha_nac usuarios.fechanacimiento%TYPE, w_sexo usuarios.sexo%TYPE,
   w_email usuarios.email%TYPE, w_telefono usuarios.telefono%TYPE, w_estudios alumnos.programa_academico%TYPE, w_curso alumnos.curso%TYPE,
   w_grupo alumnos.grupo%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    alumno alumnos%rowtype;
    usuario usuarios%rowtype;
  BEGIN
    UPDATE usuarios SET nombre = w_nombre, apellidos = w_apellidos, dni = w_dni, fechanacimiento = w_fecha_nac, sexo = w_sexo, email = w_email, telefono = w_telefono WHERE num_carne = w_num_carne;
    UPDATE alumnos SET programa_academico = w_estudios, curso = w_curso, grupo = w_grupo WHERE num_carne = w_num_carne;
    SELECT * INTO usuario FROM usuarios WHERE num_carne = w_num_carne;
    SELECT * INTO alumno FROM alumnos WHERE num_carne = w_num_carne;
    IF(usuario.nombre<>w_nombre OR usuario.apellidos<>w_apellidos OR usuario.dni<>w_dni OR usuario.fechanacimiento<>w_fecha_nac OR usuario.sexo<>w_sexo OR usuario.email<>w_email OR usuario.telefono<>w_telefono OR alumno.programa_academico<>w_estudios OR alumno.curso<>w_curso OR alumno.grupo<>w_grupo)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;
        
  END actualizar;

  PROCEDURE eliminar (nombre_prueba VARCHAR2, w_num_carne usuarios.num_carne%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    n_alumnos INTEGER;
    n_usuarios INTEGER;
  BEGIN
    DELETE FROM alumnos WHERE num_carne = w_num_carne;
    DELETE FROM usuarios WHERE num_carne = w_num_carne;
    SELECT COUNT(*) INTO n_alumnos FROM alumnos WHERE num_carne = w_num_carne;
    SELECT COUNT(*) INTO n_usuarios FROM usuarios WHERE num_carne = w_num_carne;
    IF(n_alumnos <> 0 OR n_usuarios <> 0)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;
        
  END eliminar;

END pruebas_alumnos;
/

--Profesores

CREATE OR REPLACE
PACKAGE BODY pruebas_profesores AS

  PROCEDURE inicializar AS
  BEGIN
   DELETE FROM prestamos;
   DELETE FROM alumnos;
   DELETE FROM reservasmateriales;
   DELETE FROM reservasaulas;
   DELETE FROM materiales;
   DELETE FROM aulas;
   DELETE FROM ejemplares;
   DELETE FROM libros;
   DELETE FROM profesores;
   DELETE FROM departamentos;
   DELETE FROM usuarios;
   INSERT INTO departamentos VALUES (1, 'Prueba', NULL);
   INSERT INTO departamentos VALUES (1, 'Prueba2', NULL);
  END inicializar;

  PROCEDURE insertar (nombre_prueba VARCHAR2, w_nombre usuarios.nombre%TYPE, w_apellidos usuarios.apellidos%TYPE, w_dni usuarios.dni%TYPE,
   w_fecha_nac usuarios.fechanacimiento%TYPE, w_sexo usuarios.sexo%TYPE, w_email usuarios.email%TYPE, w_telefono usuarios.telefono%TYPE,
   w_nombre_d departamentos.nombre%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    usuario usuarios%rowtype;
    w_num_carne CHAR(6);
  BEGIN
    alta_profesor(w_nombre, w_apellidos, w_dni, w_fecha_nac, w_sexo, w_email, w_telefono, w_nombre_d);
    w_num_carne := carne_profesor(sec_profesores.CURRVAL);
    SELECT * INTO usuario FROM usuarios WHERE num_carne = w_num_carne;
    IF(usuario.nombre <> w_nombre)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;

  END insertar;

  PROCEDURE actualizar (nombre_prueba VARCHAR2, w_num_carne usuarios.num_carne%TYPE, w_nombre usuarios.nombre%TYPE,
   w_apellidos usuarios.apellidos%TYPE, w_dni usuarios.dni%TYPE, w_fecha_nac usuarios.fechanacimiento%TYPE, w_sexo usuarios.sexo%TYPE,
   w_email usuarios.email%TYPE, w_telefono usuarios.telefono%TYPE, w_oid_d profesores.oid_d%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    profesor profesores%rowtype;
    usuario usuarios%rowtype;
  BEGIN
    UPDATE usuarios SET nombre = w_nombre, apellidos = w_apellidos, dni = w_dni, fechanacimiento = w_fecha_nac, sexo = w_sexo, email = w_email, telefono = w_telefono WHERE num_carne = w_num_carne;
    UPDATE profesores SET oid_d = w_oid_d WHERE num_carne = w_num_carne;
    SELECT * INTO usuario FROM usuarios WHERE num_carne = w_num_carne;
    SELECT * INTO profesor FROM profesores WHERE num_carne = w_num_carne;
    IF(usuario.nombre<>w_nombre OR usuario.apellidos<>w_apellidos OR usuario.dni<>w_dni OR usuario.fechanacimiento<>w_fecha_nac OR usuario.sexo<>w_sexo OR usuario.email<>w_email OR usuario.telefono<>w_telefono OR profesor.oid_d<>w_oid_d)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;
        
  END actualizar;

  PROCEDURE eliminar (nombre_prueba VARCHAR2, w_num_carne usuarios.num_carne%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    n_profesores INTEGER;
    n_usuarios INTEGER;
  BEGIN
    DELETE FROM profesores WHERE num_carne = w_num_carne;
    DELETE FROM usuarios WHERE num_carne = w_num_carne;
    SELECT COUNT(*) INTO n_profesores FROM profesores WHERE num_carne = w_num_carne;
    SELECT COUNT(*) INTO n_usuarios FROM usuarios WHERE num_carne = w_num_carne;
    IF(n_profesores <> 0 OR n_usuarios <> 0)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;
        
  END eliminar;

END pruebas_profesores;
/

--Departamentos

CREATE OR REPLACE
PACKAGE BODY pruebas_departamentos AS

  PROCEDURE inicializar AS
  BEGIN
    DELETE FROM profesores;
    DELETE FROM departamentos;
    DELETE FROM alumnos;
    DELETE FROM usuarios;
    INSERT INTO usuarios (num_carne, nombre, apellidos, dni, fechanacimiento, sexo, email, telefono, fecha_inicio_carne, fecha_validez_carne) 
    VALUES('00000P', 'Antonio', 'García', '12345678Z', TO_DATE('10/11/75', 'DD/MM/RR'), 'M', 'antonogarcia@mail.com', 686455075, sysdate, sysdate+1);
    alta_alumno('Jorge', 'Raposo Díaz', '45235667D', TO_DATE('10/11/99', 'DD/MM/RR'), 'M', 'jorgeraposo1999@gmail.com', NULL, 'BACHILLERATO', 2, 'A');
  END inicializar;

  PROCEDURE insertar (nombre_prueba VARCHAR2, w_nombre departamentos.nombre%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    w_oid_d INTEGER;
    departamento departamentos%rowtype;
  BEGIN
    INSERT INTO departamentos (nombre) VALUES (w_nombre);
    w_oid_d := sec_departamentos.CURRVAL;
    SELECT * INTO departamento FROM departamentos WHERE oid_d = w_oid_d;
    IF(departamento.nombre <> w_nombre)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;

  END insertar;

  PROCEDURE actualizar (nombre_prueba VARCHAR2, w_oid_d departamentos.oid_d%TYPE, w_nombre departamentos.nombre%TYPE,
    w_jefe departamentos.jefe_departamento%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    departamento departamentos%rowtype;
  BEGIN
    UPDATE departamentos SET nombre = w_nombre, jefe_departamento = w_jefe WHERE oid_d = w_oid_d;
    SELECT * INTO departamento FROM departamentos WHERE oid_d = w_oid_d;
    IF(departamento.nombre <> w_nombre OR departamento.jefe_departamento <> w_jefe)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;

  END actualizar;

  PROCEDURE eliminar (nombre_prueba VARCHAR2, w_oid_d departamentos.oid_d%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    n_departamentos INTEGER;
  BEGIN
    DELETE FROM departamentos WHERE oid_d = w_oid_d;
    SELECT COUNT(*) INTO n_departamentos FROM departamentos WHERE oid_d = w_oid_d;
    IF(n_departamentos <> 0)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;
        
  END eliminar;

END pruebas_departamentos;
/

--Libros

CREATE OR REPLACE
PACKAGE BODY pruebas_libros AS

  PROCEDURE inicializar AS
  BEGIN
   DELETE FROM prestamos;
   DELETE FROM ejemplares;
   DELETE FROM libros;
  END inicializar;

  PROCEDURE insertar (nombre_prueba VARCHAR2, w_isbn libros.isbn%TYPE, w_titulo libros.titulo%TYPE, w_autor libros.autor%TYPE,
   w_cdu libros.cdu%TYPE, w_genero libros.genero%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    libro libros%rowtype;
    w_isbn_13 CHAR(14) := obtener_isbn_13(w_isbn);
  BEGIN
    INSERT INTO libros (isbn,titulo,autor,cdu,genero) VALUES (w_isbn, w_titulo, w_autor, w_cdu, w_genero);
    SELECT * INTO libro FROM libros WHERE isbn = w_isbn_13;
    IF(libro.titulo <> w_titulo)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;

  END insertar;

  PROCEDURE actualizar (nombre_prueba VARCHAR2, w_isbn libros.isbn%TYPE, w_titulo libros.titulo%TYPE, w_autor libros.autor%TYPE,
   w_cdu libros.cdu%TYPE, w_genero libros.genero%TYPE, w_copias libros.copias%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    libro libros%rowtype;
    w_isbn_13 CHAR(14) := obtener_isbn_13(w_isbn);
  BEGIN
    UPDATE libros SET titulo = w_titulo, autor = w_autor, cdu = w_cdu, genero = w_genero, copias = w_copias WHERE isbn = w_isbn_13;
    SELECT * INTO libro FROM libros WHERE isbn = w_isbn_13;
    IF(libro.titulo<>w_titulo OR libro.autor<>w_autor OR libro.cdu<>w_cdu OR libro.genero<>w_genero OR libro.copias<>w_copias)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;
        
  END actualizar;

  PROCEDURE eliminar (nombre_prueba VARCHAR2, w_isbn libros.isbn%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    n_libros INTEGER;
    w_isbn_13 CHAR(14) := obtener_isbn_13(w_isbn);
  BEGIN
    DELETE FROM libros WHERE isbn = w_isbn_13;
    SELECT COUNT(*) INTO n_libros FROM libros WHERE isbn = w_isbn_13;
    IF(n_libros <> 0)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;
        
  END eliminar;

END pruebas_libros;
/

--Aulas

CREATE OR REPLACE
PACKAGE BODY pruebas_aulas AS

  PROCEDURE inicializar AS
  BEGIN
   DELETE FROM reservasaulas;
   DELETE FROM aulas;
   INSERT INTO aulas (nombre,numero) VALUES ('3o Eso',70);
  END inicializar;

  PROCEDURE insertar (nombre_prueba VARCHAR2, w_numero aulas.numero%TYPE, w_nombre aulas.nombre%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    aula aulas%rowtype;
  BEGIN
    INSERT INTO aulas VALUES (w_numero, w_nombre);
    SELECT * INTO aula FROM aulas WHERE numero = w_numero;
    IF(aula.nombre <> w_nombre)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;

  END insertar;

  PROCEDURE actualizar (nombre_prueba VARCHAR2, w_numero aulas.numero%TYPE, w_nombre aulas.nombre%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    aula aulas%rowtype;
  BEGIN
    UPDATE aulas SET nombre = w_nombre WHERE numero = w_numero;
    SELECT * INTO aula FROM aulas WHERE numero = w_numero;
    IF(aula.nombre<>w_nombre)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;
        
  END actualizar;

  PROCEDURE eliminar (nombre_prueba VARCHAR2, w_numero aulas.numero%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    n_aulas INTEGER;
  BEGIN
    DELETE FROM aulas WHERE numero = w_numero;
    SELECT COUNT(*) INTO n_aulas FROM aulas WHERE numero = w_numero;
    IF(n_aulas <> 0)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;
        
  END eliminar;

END pruebas_aulas;
/

--ReservasMateriales

CREATE OR REPLACE
PACKAGE BODY pruebas_materiales AS

  PROCEDURE inicializar AS
  BEGIN
   DELETE FROM reservasmateriales;
   DELETE FROM materiales;
   DELETE FROM alumnos;
   DELETE FROM profesores;
   DELETE FROM usuarios;
   DELETE FROM departamentos;
   INSERT INTO departamentos VALUES (1, 'Prueba', NULL);
   INSERT INTO departamentos VALUES (1, 'Prueba2', NULL);
  END inicializar;

  PROCEDURE insertar (nombre_prueba VARCHAR2, w_descripcion materiales.descripcion%TYPE, w_estado materiales.estado%TYPE, 
   w_fecha_alta materiales.fecha_alta%TYPE, w_unidades materiales.unidades%TYPE, w_oid_d materiales.oid_d%TYPE, 
   salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    material materiales%rowtype;
    w_oid_m INTEGER;
  BEGIN
    INSERT INTO materiales (descripcion, estado, fecha_alta, unidades, oid_d) VALUES (w_descripcion, w_estado, w_fecha_alta, w_unidades, w_oid_d);
    w_oid_m := sec_materiales.CURRVAL;
    SELECT * INTO material FROM materiales WHERE oid_m = w_oid_m;
    IF(material.descripcion <> w_descripcion)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;

  END insertar;

  PROCEDURE actualizar (nombre_prueba VARCHAR2, w_oid_m materiales.oid_m%TYPE, w_descripcion materiales.descripcion%TYPE, 
   w_estado materiales.estado%TYPE, w_fecha_alta materiales.fecha_alta%TYPE, w_fecha_baja materiales.fecha_baja%TYPE, 
   w_unidades materiales.unidades%TYPE, w_oid_d materiales.oid_d%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    material materiales%rowtype;
  BEGIN
    UPDATE materiales SET descripcion = w_descripcion, estado = w_estado, fecha_alta = w_fecha_alta, fecha_baja = w_fecha_baja, unidades = w_unidades, oid_d = w_oid_d WHERE oid_m = w_oid_m;
    SELECT * INTO material FROM materiales WHERE oid_m = w_oid_m;
    IF(material.descripcion<>w_descripcion OR material.estado<>w_estado OR material.fecha_alta<>w_fecha_alta OR material.fecha_baja<>w_fecha_baja OR material.unidades<>w_unidades OR material.oid_d<>w_oid_d)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;
        
  END actualizar;

  PROCEDURE eliminar (nombre_prueba VARCHAR2, w_oid_m materiales.oid_m%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    n_materiales INTEGER;
  BEGIN
    DELETE FROM materiales WHERE oid_m = w_oid_m;
    SELECT COUNT(*) INTO n_materiales FROM materiales WHERE oid_m = w_oid_m;
    IF(n_materiales <> 0)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;
        
  END eliminar;

END pruebas_materiales;
/

--Ejemplares

CREATE OR REPLACE
PACKAGE BODY pruebas_ejemplares AS

  PROCEDURE inicializar AS
  BEGIN
   DELETE FROM prestamos;
   DELETE FROM ejemplares;
   DELETE FROM libros;
   INSERT INTO libros (isbn,titulo,autor,cdu,genero) VALUES ('978-3161484209', 'Quijote', 'Cervantes', '1,2', 'Aventura');
   INSERT INTO libros (isbn,titulo,autor,cdu,genero) VALUES ('9684842090', 'Quijote', 'Cervantes', '1,2', 'Aventura');
  END inicializar;

  PROCEDURE insertar (nombre_prueba VARCHAR2, w_estado ejemplares.estado%TYPE, w_isbn ejemplares.isbn%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    ejemplar ejemplares%rowtype;
    w_codigo INTEGER;
  BEGIN
    INSERT INTO ejemplares (estado, isbn) VALUES (w_estado, w_isbn);
    w_codigo := sec_ejemplares.CURRVAL;
    SELECT * INTO ejemplar FROM ejemplares WHERE codigo = w_codigo;
    IF(ejemplar.codigo <> w_codigo)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;

  END insertar;

  PROCEDURE actualizar (nombre_prueba VARCHAR2, w_codigo ejemplares.codigo%TYPE, w_estado ejemplares.estado%TYPE, 
   w_disponible ejemplares.disponible%TYPE, w_fecha_baja ejemplares.fecha_baja%TYPE, w_isbn ejemplares.isbn%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    ejemplar ejemplares%rowtype;
  BEGIN
    UPDATE ejemplares SET estado = w_estado, disponible = w_disponible, fecha_baja = w_fecha_baja, isbn = w_isbn WHERE codigo = w_codigo;
    SELECT * INTO ejemplar FROM ejemplares WHERE codigo = w_codigo;
    IF(ejemplar.estado<>w_estado OR ejemplar.disponible<>w_disponible OR ejemplar.fecha_baja<>w_fecha_baja OR ejemplar.isbn<>w_isbn)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;
        
  END actualizar;

  PROCEDURE eliminar (nombre_prueba VARCHAR2, w_codigo ejemplares.codigo%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    n_ejemplares INTEGER;
  BEGIN
    DELETE FROM ejemplares WHERE codigo = w_codigo;
    SELECT COUNT(*) INTO n_ejemplares FROM ejemplares WHERE codigo = w_codigo;
    IF(n_ejemplares <> 0)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;
        
  END eliminar;

END pruebas_ejemplares;
/

--Prestamos

CREATE OR REPLACE
PACKAGE BODY pruebas_prestamos AS

  PROCEDURE inicializar AS
  BEGIN
   DELETE FROM prestamos;
   DELETE FROM ejemplares;
   DELETE FROM libros;
   DELETE FROM alumnos;
   DELETE FROM profesores;
   DELETE FROM usuarios;
   INSERT INTO libros (isbn,titulo,autor,cdu,genero) VALUES ('978-3161484209', 'Quijote', 'Cervantes', 1, 'Aventura');
   INSERT INTO ejemplares (estado, isbn) VALUES ('Perfecto', '978-3161484209');
   alta_alumno('Jorge', 'Raposo Díaz', '45235667D', TO_DATE('10/11/99', 'DD/MM/RR'), 'M', 'jorgeraposo1999@gmail.com', NULL, 'BACHILLERATO', 2, 'A');
  END inicializar;

  PROCEDURE insertar (nombre_prueba VARCHAR2, w_num_carne prestamos.num_carne%TYPE, w_codigo prestamos.codigo%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    prestamo prestamos%rowtype;
    w_oid_p INTEGER;
  BEGIN
    presta_libro(w_num_carne, w_codigo);
    w_oid_p := sec_prestamos.CURRVAL;
    SELECT * INTO prestamo FROM prestamos WHERE oid_p = w_oid_p;
    IF(prestamo.oid_p <> w_oid_p)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;

  END insertar;

  PROCEDURE actualizar (nombre_prueba VARCHAR2, w_oid_p prestamos.oid_p%TYPE, w_num_carne prestamos.num_carne%TYPE, 
   w_codigo prestamos.codigo%TYPE, w_fecha_inicio prestamos.fecha_inicio%TYPE, w_fecha_fin prestamos.fecha_fin%TYPE, 
   w_fecha_entrega prestamos.fecha_entrega%TYPE,salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    prestamo prestamos%rowtype;
  BEGIN
    UPDATE prestamos SET num_carne = w_num_carne, codigo = w_codigo, fecha_inicio = w_fecha_inicio, fecha_fin = w_fecha_fin, fecha_entrega = w_fecha_entrega WHERE oid_p = w_oid_p;
    SELECT * INTO prestamo FROM prestamos WHERE oid_p = w_oid_p;
    IF(prestamo.num_carne<>w_num_carne OR prestamo.codigo<>w_codigo OR prestamo.fecha_inicio<>w_fecha_inicio OR prestamo.fecha_fin<>w_fecha_fin OR prestamo.fecha_entrega<>w_fecha_entrega)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;
        
  END actualizar;

  PROCEDURE eliminar (nombre_prueba VARCHAR2, w_oid_p prestamos.oid_p%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    n_prestamos INTEGER;
  BEGIN
    DELETE FROM prestamos WHERE oid_p = w_oid_p;
    SELECT COUNT(*) INTO n_prestamos FROM prestamos WHERE oid_p = w_oid_p;
    IF(n_prestamos <> 0)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;
        
  END eliminar;

END pruebas_prestamos;
/

--ReservasMateriales

CREATE OR REPLACE
PACKAGE BODY pruebas_reservasmateriales AS

  PROCEDURE inicializar AS
    cod_dept INTEGER;
  BEGIN
    DELETE FROM reservasmateriales;
    DELETE FROM materiales;
    DELETE FROM profesores;
    DELETE FROM departamentos;
    DELETE FROM alumnos;
    DELETE FROM usuarios;
    INSERT INTO departamentos (nombre) VALUES ('Prueba');
    cod_dept := sec_departamentos.CURRVAL;
    alta_profesor('Fernando', 'Martínez Fonseca', '98765431X', TO_DATE('10/11/99', 'DD/MM/RR'), 'M', 'fermartinezfon@mail.org', 600500200, 'Prueba');
    INSERT INTO materiales (descripcion, estado, fecha_alta, unidades, oid_d) VALUES ('Carrito de 5 ordenadores', 'Bueno', sysdate, 1, cod_dept);
  END inicializar;

  PROCEDURE insertar (nombre_prueba VARCHAR2, w_material reservasmateriales.oid_m%TYPE, w_num_carne reservasmateriales.num_carne%TYPE,
   w_fecha_reserva reservasmateriales.fecha_reserva%TYPE, w_tramo reservasmateriales.tramo%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    reserva reservasmateriales%rowtype;
    w_oid_rm INTEGER;
  BEGIN
    reserva_material(w_num_carne, w_material,w_fecha_reserva, w_tramo);
    w_oid_rm := sec_reserva_mat.CURRVAL;
    SELECT * INTO reserva FROM reservasmateriales WHERE oid_rm = w_oid_rm;
    IF(reserva.oid_m <> w_material)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;

  END insertar;

  PROCEDURE actualizar (nombre_prueba VARCHAR2, w_oid_rm reservasmateriales.oid_rm%TYPE, w_material reservasmateriales.oid_m%TYPE, 
   w_num_carne reservasmateriales.num_carne%TYPE, w_fecha_reserva reservasmateriales.fecha_reserva%TYPE, w_tramo reservasmateriales.tramo%TYPE,
   salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    reserva reservasmateriales%rowtype;
  BEGIN
    UPDATE reservasmateriales SET oid_m = w_material, num_carne = w_num_carne, fecha_reserva = w_fecha_reserva, tramo = w_tramo WHERE oid_rm = w_oid_rm;
    SELECT * INTO reserva FROM reservasmateriales WHERE oid_rm = w_oid_rm;
    IF(reserva.oid_rm <> w_oid_rm OR reserva.num_carne <> w_num_carne OR reserva.fecha_reserva <> w_fecha_reserva OR reserva.tramo <> w_tramo)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;

  END actualizar;

  PROCEDURE eliminar (nombre_prueba VARCHAR2, w_oid_rm reservasmateriales.oid_rm%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    n_reservas INTEGER;
  BEGIN
    DELETE FROM reservasmateriales WHERE oid_rm = w_oid_rm;
    SELECT COUNT(*) INTO n_reservas FROM reservasmateriales WHERE oid_rm = w_oid_rm;
    IF (n_reservas <> 0)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;

  END eliminar;

END pruebas_reservasmateriales;
/

--ReservasAulas

CREATE OR REPLACE
PACKAGE BODY pruebas_reservasaulas AS

  PROCEDURE inicializar AS
    cod_dept INTEGER;
  BEGIN
    DELETE FROM reservasaulas;
    DELETE FROM aulas;
    DELETE FROM materiales;
    DELETE FROM profesores;
    DELETE FROM departamentos;
    DELETE FROM alumnos;
    DELETE FROM usuarios;
    INSERT INTO departamentos (nombre) VALUES ('Prueba');
    cod_dept := sec_departamentos.CURRVAL;
    alta_profesor('María Luisa', 'Carmona Enríquez', '98744432X', TO_DATE('10/11/99', 'DD/MM/RR'), 'M', 'malucarmona42@mail.org', 600500200, 'Prueba');
    INSERT INTO aulas VALUES (12, 'Sala de usos múltiples');
  END inicializar;

  PROCEDURE insertar (nombre_prueba VARCHAR2, w_aula reservasaulas.numero%TYPE, w_num_carne reservasaulas.num_carne%TYPE,
   w_fecha_reserva reservasaulas.fecha_reserva%TYPE, w_tramo reservasaulas.tramo%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    reserva reservasaulas%rowtype;
    w_oid_ra INTEGER;
  BEGIN
    reserva_aula(w_num_carne, w_aula, w_fecha_reserva, w_tramo);
    w_oid_ra := sec_reserva_aul.CURRVAL;
    SELECT * INTO reserva FROM reservasaulas WHERE oid_ra = w_oid_ra;
    IF (reserva.numero <> w_aula)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
    
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;

  END insertar;

  PROCEDURE actualizar (nombre_prueba VARCHAR2, w_oid_ra reservasaulas.oid_ra%TYPE, w_aula reservasaulas.numero%TYPE, 
   w_num_carne reservasaulas.num_carne%TYPE, w_fecha_reserva reservasaulas.fecha_reserva%TYPE, w_tramo reservasaulas.tramo%TYPE,
   salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    reserva reservasaulas%rowtype;
  BEGIN
    UPDATE reservasaulas SET numero = w_aula, num_carne = w_num_carne, fecha_reserva = w_fecha_reserva, tramo = w_tramo WHERE oid_ra = w_oid_ra;
    SELECT * INTO reserva FROM reservasaulas WHERE oid_ra = w_oid_ra;
    IF (reserva.numero <> w_aula OR reserva.num_carne <> w_num_carne OR reserva.fecha_reserva <> w_fecha_reserva OR reserva.tramo <> w_tramo)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
     
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;

  END actualizar;

  PROCEDURE eliminar (nombre_prueba VARCHAR2, w_oid_ra reservasaulas.oid_ra%TYPE, salidaesperada BOOLEAN) AS
    salida BOOLEAN := TRUE;
    n_reservas INTEGER;
  BEGIN
    DELETE FROM reservasaulas WHERE oid_ra = w_oid_ra;
    SELECT COUNT(*) INTO n_reservas FROM reservasaulas WHERE oid_ra = w_oid_ra;
    IF (n_reservas <> 0)
        THEN salida := FALSE;
    END IF;
    COMMIT WORK;
     
    dbms_output.put_line(nombre_prueba || ': ' || assert_equals(salida, salidaesperada));
    
    EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line(nombre_prueba || ': ' || assert_equals(FALSE, salidaesperada));
        ROLLBACK;

  END eliminar;

END pruebas_reservasaulas;
/

--PRUEBAS A PAQUETES

--Alumnos

SET SERVEROUTPUT ON
DECLARE
    num_carne_carla CHAR(6);
    num_carne_carla2 CHAR(6);
    dni_carla_repetido CHAR(9);
    email_carla_repetido VARCHAR2(50);
BEGIN
    pruebas_alumnos.inicializar;
    pruebas_alumnos.insertar('Prueba 1 - Inserción de alumno', 'Carla', 'Toro', '43255759D', '05/11/02', 'F', 'a@gmail.com', '675378263', 'BACHILLERATO', '1', 'A', TRUE);
    num_carne_carla := carne_alumno(sec_alumnos.CURRVAL);
    pruebas_alumnos.insertar('Prueba 2 - Inserción de alumno con nombre NULL', NULL, 'Toro', '33255759D', '05/11/02', 'F', 'a2@gmail.com', '675378263', 'BACHILLERATO', '1', 'A',FALSE);
    pruebas_alumnos.insertar('Prueba 3 - Inserción de alumno con apellidos NULL', 'Carla', NULL, '23255759D', '05/11/02', 'F', 'a3@gmail.com', '675378263', 'BACHILLERATO', '1', 'A',FALSE);
    pruebas_alumnos.insertar('Prueba 4 - Inserción de alumno con dni NULL', 'Carla', 'Toro', NULL, '05/11/02', 'F', 'a4@gmail.com', '675378263', 'BACHILLERATO', '1', 'A',TRUE);
    pruebas_alumnos.insertar('Prueba 5 - Inserción de alumno con fecha de nacimiento NULL', 'Carla', 'Toro', '13255759D', NULL, 'F', 'a5@gmail.com', '675378263', 'BACHILLERATO', '1', 'A',FALSE);
    pruebas_alumnos.insertar('Prueba 6 - Inserción de alumno con sexo NULL', 'Carla', 'Toro', '03255759D', '05/11/02', NULL, 'a6@gmail.com', '675378263', 'BACHILLERATO', '1', 'A',FALSE);
    pruebas_alumnos.insertar('Prueba 7 - Inserción de alumno con email NULL', 'Carla', 'Toro', '53255759D', '05/11/02', 'F', NULL, '675378263', 'BACHILLERATO', '1', 'A',FALSE);
    pruebas_alumnos.insertar('Prueba 8 - Inserción de alumno con telefono NULL', 'Carla', 'Toro', '63255759D', '05/11/02', 'F', 'a7@gmail.com', NULL, 'BACHILLERATO', '1', 'A',TRUE);
    num_carne_carla2 := carne_alumno(sec_alumnos.CURRVAL);
    SELECT dni INTO dni_carla_repetido FROM usuarios WHERE num_carne = num_carne_carla2;
    SELECT email INTO email_carla_repetido FROM usuarios WHERE num_carne = num_carne_carla2;
    pruebas_alumnos.insertar('Prueba 9 - Inserción de alumno con dni existente en el sistema', 'Carla', 'Toro', '43255759D', '05/11/02', 'F', 'a8@gmail.com', '675378263', 'BACHILLERATO', '1', 'A',FALSE);
    pruebas_alumnos.insertar('Prueba 10 - Inserción de alumno con email existente en el sistema', 'Carla', 'Toro', '83255759D', '05/11/02', 'F', 'a@gmail.com', '675378263', 'BACHILLERATO', '1', 'A',FALSE);
    pruebas_alumnos.insertar('Prueba 11 - Inserción de alumno con sexo no valido', 'Antoio', 'Toro', '93255759D', '05/11/02', 'X', 'a10@gmail.com', '675378263', 'BACHILLERATO', '1', 'A',FALSE);
    pruebas_alumnos.insertar('Prueba 12 - Inserción de alumno con dni no valido', 'Rafael', 'Toro', '3255749D', '05/11/02', 'M', 'a11@gmail.com', '675378263', 'BACHILLERATO', '1', 'A',FALSE);
    pruebas_alumnos.insertar('Prueba 13 - Inserción de alumno con email no valido', 'Rocio', 'Toro', '53255739D', '05/11/02', 'F', 'agmail.com', '675378263', 'BACHILLERATO', '1', 'A',FALSE);
    pruebas_alumnos.insertar('Prueba 14 - Inserción de alumno con curso no valido', 'Carlos', 'Toro', '43355729D', '05/11/02', 'M', 'a12@gmail.com', '675378263', 'BACHILLERATO', '7', 'A',FALSE);
    pruebas_alumnos.insertar('Prueba 15 - Inserción de alumno con estudios no validos', 'Carlota', 'Toro', '43255710D', '05/11/02', 'F', 'a13@gmail.com', '675378263', 'INFORMATICA', '1', 'A', FALSE);
    pruebas_alumnos.actualizar('Prueba 16 - Actualizar nombre de alumno', num_carne_carla, 'Maria', 'Toro', '43255759D', '05/11/02', 'F', 'a@gmail.com', '675378263', 'BACHILLERATO', '1', 'A', TRUE);
    pruebas_alumnos.actualizar('Prueba 17 - Actualizar nombre de alumno a null', num_carne_carla, NULL, 'Toro', '43255759D', '05/11/02', 'F', 'a@gmail.com', '675378263', 'BACHILLERATO', '1', 'A', FALSE);
    pruebas_alumnos.actualizar('Prueba 18 - Actualizar apellido de alumno', num_carne_carla, 'Carla', 'Ramos', '43255759D', '05/11/02', 'F', 'a@gmail.com', '675378263', 'BACHILLERATO', '1', 'A', TRUE);
    pruebas_alumnos.actualizar('Prueba 19 - Actualizar apellidos de alumno a null', num_carne_carla, 'Carla', NULL, '43255759D', '05/11/02', 'F', 'a@gmail.com', '675378263', 'BACHILLERATO', '1', 'A', FALSE);
    pruebas_alumnos.actualizar('Prueba 20 - Actualizar dni de alumno', num_carne_carla, 'Carla', 'Toro', '43255749D', '05/11/02', 'F', 'a@gmail.com', '675378263', 'BACHILLERATO', '1', 'A', TRUE);
    pruebas_alumnos.actualizar('Prueba 21 - Actualizar dni de alumno a null', num_carne_carla, 'Carla', 'Toro', NULL, '05/11/02', 'F', 'a@gmail.com', '675378263', 'BACHILLERATO', '1', 'A', TRUE);
    pruebas_alumnos.actualizar('Prueba 22 - Actualizar dni de alumno a uno existente', num_carne_carla, 'Carla', 'Toro', dni_carla_repetido, '05/11/02', 'F', 'a@gmail.com', '675378263', 'BACHILLERATO', '1', 'A', FALSE);
    pruebas_alumnos.actualizar('Prueba 23 - Actualizar dni de alumno a uno no valido', num_carne_carla, 'Carla', 'Toro', '1234', '05/11/02', 'F', 'a@gmail.com', '675378263', 'BACHILLERATO', '1', 'A', FALSE);
    pruebas_alumnos.actualizar('Prueba 24 - Actualizar fecha de nacimiento de alumno', num_carne_carla, 'Carla', 'Toro', '43255759D', '02/05/01', 'F', 'a@gmail.com', '675378263', 'BACHILLERATO', '1', 'A', TRUE);
    pruebas_alumnos.actualizar('Prueba 25 - Actualizar fecha de nacimiento de alumno a null', num_carne_carla, 'Carla', 'Toro', '43255759D', NULL, 'F', 'a@gmail.com', '675378263', 'BACHILLERATO', '1', 'A', FALSE);
    pruebas_alumnos.actualizar('Prueba 26 - Actualizar sexo de alumno', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', 'M', 'a@gmail.com', '675378263', 'BACHILLERATO', '1', 'A', TRUE);
    pruebas_alumnos.actualizar('Prueba 27 - Actualizar sexo de alumno a null', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', NULL, 'a@gmail.com', '675378263', 'BACHILLERATO', '1', 'A', FALSE);
    pruebas_alumnos.actualizar('Prueba 28 - Actualizar sexo de alumno a uno no valido', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', 'L', 'a@gmail.com', '675378263', 'BACHILLERATO', '1', 'A', FALSE);
    pruebas_alumnos.actualizar('Prueba 29 - Actualizar email de alumno', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', 'F', 'act@gmail.com', '675378263', 'BACHILLERATO', '1', 'A', TRUE);
    pruebas_alumnos.actualizar('Prueba 30 - Actualizar email de alumno a null', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', 'F', NULL, '675378263', 'BACHILLERATO', '1', 'A', FALSE);
    pruebas_alumnos.actualizar('Prueba 31 - Actualizar email de alumno a uno existente', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', 'F', email_carla_repetido, '675378263', 'BACHILLERATO', '1', 'A', FALSE);
    pruebas_alumnos.actualizar('Prueba 32 - Actualizar email de alumno a uno existente', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', 'F', 'email.com', '675378263', 'BACHILLERATO', '1', 'A', FALSE);
    pruebas_alumnos.actualizar('Prueba 33 - Actualizar telefono de alumno', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', 'F', 'a@gmail.com', '775378263', 'BACHILLERATO', '1', 'A', TRUE);
    pruebas_alumnos.actualizar('Prueba 34 - Actualizar telefono de alumno a null', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', 'F', 'a@gmail.com', NULL, 'BACHILLERATO', '1', 'A', TRUE);
    pruebas_alumnos.actualizar('Prueba 35 - Actualizar curso de alumno', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', 'F', 'a@gmail.com', '675378263', 'BACHILLERATO', '2', 'A', TRUE);
    pruebas_alumnos.actualizar('Prueba 36 - Actualizar curso de alumno a uno no valido', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', 'F', 'a@gmail.com', '675378263', 'BACHILLERATO', '0', 'A', FALSE);
    pruebas_alumnos.actualizar('Prueba 37 - Actualizar estudios de alumno', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', 'F', 'a@gmail.com', '675378263', 'ESO', '1', 'A', TRUE);
    pruebas_alumnos.actualizar('Prueba 38 - Actualizar estudios de alumno a uno no valido', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', 'F', 'a@gmail.com', '675378263', 'IISSI', '1', 'A', FALSE);
    pruebas_alumnos.actualizar('Prueba 39 - Actualizar grupo de alumno', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', 'F', 'a@gmail.com', '675378263', 'BACHILLERATO', '1', 'B', TRUE);
    pruebas_alumnos.actualizar('Prueba 40 - Actualizar grupo de alumno a uno no valido', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', 'F', 'a@gmail.com', '675378263', 'BACHILLERATO', '1', 'BA', FALSE);
    pruebas_alumnos.eliminar('Prueba 41 - Eliminar un alumno', num_carne_carla, TRUE);
END;
/

--Profesores

DECLARE
    num_carne_carla CHAR(6);
    num_carne_carla2 CHAR(6);
    dni_carla_repetido CHAR(9);
    email_carla_repetido VARCHAR2(50);
    n_oid INTEGER;
    
BEGIN
    pruebas_profesores.inicializar;
    n_oid:=sec_departamentos.CURRVAL;
    pruebas_profesores.insertar('Prueba 42 - Inserción de profesor', 'Carla', 'Toro', '43255759D', '05/11/02', 'F', 'a@gmail.com', '675378263', 'Prueba', TRUE);
    num_carne_carla := carne_profesor(sec_profesores.CURRVAL);
    pruebas_profesores.insertar('Prueba 43 - Inserción de profesor con nombre NULL', NULL, 'Toro', '33255759D', '05/11/02', 'F', 'a2@gmail.com', '675378263', 'Prueba',FALSE);
    pruebas_profesores.insertar('Prueba 44 - Inserción de profesor con apellidos NULL', 'Carla', NULL, '23255759D', '05/11/02', 'F', 'a3@gmail.com', '675378263', 'Prueba',FALSE);
    pruebas_profesores.insertar('Prueba 45 - Inserción de profesor con dni NULL', 'Carla', 'Toro', NULL, '05/11/02', 'F', 'a4@gmail.com', '675378263', 'Prueba',TRUE);
    pruebas_profesores.insertar('Prueba 46 - Inserción de profesor con fecha de nacimiento NULL', 'Carla', 'Toro', '13255759D', NULL, 'F', 'a5@gmail.com', '675378263', 'Prueba',FALSE);
    pruebas_profesores.insertar('Prueba 47 - Inserción de profesor con sexo NULL', 'Carla', 'Toro', '03255759D', '05/11/02', NULL, 'a6@gmail.com', '675378263', 'Prueba',FALSE);
    pruebas_profesores.insertar('Prueba 48 - Inserción de profesor con email NULL', 'Carla', 'Toro', '53255759D', '05/11/02', 'F', NULL, '675378263', 'Prueba',FALSE);
    pruebas_profesores.insertar('Prueba 49 - Inserción de profesor con telefono NULL', 'Carla', 'Toro', '63255759D', '05/11/02', 'F', 'a7@gmail.com', NULL, 'Prueba',TRUE);
    num_carne_carla2 := carne_profesor(sec_profesores.CURRVAL);
    SELECT dni INTO dni_carla_repetido FROM usuarios WHERE num_carne = num_carne_carla2;
    SELECT email INTO email_carla_repetido FROM usuarios WHERE num_carne = num_carne_carla2;
    pruebas_profesores.insertar('Prueba 50 - Inserción de profesor con dni existente en el sistema', 'Carla', 'Toro', '43255759D', '05/11/02', 'F', 'a8@gmail.com', '675378263', 'Prueba',FALSE);
    pruebas_profesores.insertar('Prueba 51 - Inserción de profesor con email existente en el sistema', 'Carla', 'Toro', '83255759D', '05/11/02', 'F', 'a@gmail.com', '675378263', 'Prueba',FALSE);
    pruebas_profesores.insertar('Prueba 52 - Inserción de profesor con sexo no valido', 'Antoio', 'Toro', '93255759D', '05/11/02', 'X', 'a10@gmail.com', '675378263', 'Prueba',FALSE);
    pruebas_profesores.insertar('Prueba 53 - Inserción de profesor con dni no valido', 'Rafael', 'Toro', '3255749D', '05/11/02', 'M', 'a11@gmail.com', '675378263', 'Prueba',FALSE);
    pruebas_profesores.insertar('Prueba 54 - Inserción de profesor con email no valido', 'Rocio', 'Toro', '53255739D', '05/11/02', 'F', 'agmail.com', '675378263', 'Prueba',FALSE);
    pruebas_profesores.insertar('Prueba 55 - Inserción de profesor con departamento no valido', 'Carlos', 'Toro', '43355729D', '05/11/02', 'M', 'a12@gmail.com', '675378263', 'Prueba5',FALSE);
    pruebas_profesores.insertar('Prueba 56 - Inserción de profesor con departamento null', 'Carlota', 'Toro', '43255710D', '05/11/02', 'F', 'a13@gmail.com', '675378263', NULL, FALSE);
    pruebas_profesores.actualizar('Prueba 57 - Actualizar nombre de profesor', num_carne_carla, 'Maria', 'Toro', '43255759D', '05/11/02', 'F', 'a14@gmail.com', '675378263', n_oid, TRUE);
    pruebas_profesores.actualizar('Prueba 58 - Actualizar nombre de profesor a null', num_carne_carla, NULL, 'Toro', '43255759D', '05/11/02', 'F', 'a@gmail.com', '675378263', n_oid, FALSE);
    pruebas_profesores.actualizar('Prueba 59 - Actualizar apellido de profesor', num_carne_carla, 'Carla', 'Ramos', '43255759D', '05/11/02', 'F', 'a@gmail.com', '675378263', n_oid, TRUE);
    pruebas_profesores.actualizar('Prueba 60 - Actualizar apellidos de profesor a null', num_carne_carla, 'Carla', NULL, '43255759D', '05/11/02', 'F', 'a@gmail.com', '675378263', n_oid, FALSE);
    pruebas_profesores.actualizar('Prueba 61 - Actualizar dni de profesor', num_carne_carla, 'Carla', 'Toro', '43255749D', '05/11/02', 'F', 'a@gmail.com', '675378263', n_oid, TRUE);
    pruebas_profesores.actualizar('Prueba 62 - Actualizar dni de profesor a null', num_carne_carla, 'Carla', 'Toro', NULL, '05/11/02', 'F', 'a@gmail.com', '675378263', n_oid, TRUE);
    pruebas_profesores.actualizar('Prueba 63 - Actualizar dni de profesor a uno existente', num_carne_carla, 'Carla', 'Toro', dni_carla_repetido, '05/11/02', 'F', 'a@gmail.com', '675378263', n_oid, FALSE);
    pruebas_profesores.actualizar('Prueba 64- Actualizar dni de profesor a uno no valido', num_carne_carla, 'Carla', 'Toro', '1234', '05/11/02', 'F', 'a@gmail.com', '675378263', n_oid, FALSE);
    pruebas_profesores.actualizar('Prueba 65 - Actualizar fecha de nacimiento de profesor', num_carne_carla, 'Carla', 'Toro', '43255759D', '02/05/01', 'F', 'a@gmail.com', '675378263', n_oid, TRUE);
    pruebas_profesores.actualizar('Prueba 66 - Actualizar fecha de nacimiento de profesor a null', num_carne_carla, 'Carla', 'Toro', '43255759D', NULL, 'F', 'a@gmail.com', '675378263', n_oid, FALSE);
    pruebas_profesores.actualizar('Prueba 67 - Actualizar sexo de profesor', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', 'M', 'a@gmail.com', '675378263', n_oid, TRUE);
    pruebas_profesores.actualizar('Prueba 68 - Actualizar sexo de profesor a null', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', NULL, 'a@gmail.com', '675378263', n_oid, FALSE);
    pruebas_profesores.actualizar('Prueba 69 - Actualizar sexo de profesor a uno no valido', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', 'L', 'a@gmail.com', '675378263', n_oid, FALSE);
    pruebas_profesores.actualizar('Prueba 70 - Actualizar email de profesor', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', 'F', 'act@gmail.com', '675378263', n_oid, TRUE);
    pruebas_profesores.actualizar('Prueba 71 - Actualizar email de profesor a null', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', 'F', NULL, '675378263', n_oid, FALSE);
    pruebas_profesores.actualizar('Prueba 72 - Actualizar email de profesor a uno existente', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', 'F', email_carla_repetido, '675378263', n_oid, FALSE);
    pruebas_profesores.actualizar('Prueba 73 - Actualizar email de profesor a uno existente', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', 'F', 'email.com', '675378263', n_oid, FALSE);
    pruebas_profesores.actualizar('Prueba 74 - Actualizar telefono de profesor', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', 'F', 'a@gmail.com', '775378263', n_oid, TRUE);
    pruebas_profesores.actualizar('Prueba 75 - Actualizar telefono de profesor a null', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', 'F', 'a@gmail.com', NULL, n_oid, TRUE);
    n_oid:=sec_departamentos.CURRVAL-1;
    pruebas_profesores.actualizar('Prueba 76 - Actualizar departamento de profesor', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', 'F', 'a@gmail.com', '675378263', n_oid,TRUE);
    pruebas_profesores.actualizar('Prueba 77 - Actualizar departamento de profesor a null', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', 'F', 'a@gmail.com', '675378263', NULL, TRUE);
    pruebas_profesores.actualizar('Prueba 78 - Actualizar departamento de profesor a no valido', num_carne_carla, 'Carla', 'Toro', '43255759D', '05/11/02', 'F', 'a@gmail.com', '675378263',n_oid-200, FALSE);
    pruebas_profesores.eliminar('Prueba 79 - Eliminar un profesor', num_carne_carla, TRUE);
END;
/

--Departamentos

DECLARE
    num_carne_prof CHAR(6);
    num_carne_alum CHAR(6);
    cod_dept_mat INTEGER;
    cod_dept_ing INTEGER;
BEGIN
    pruebas_departamentos.inicializar;
    num_carne_prof := carne_profesor(sec_profesores.CURRVAL);
    num_carne_alum := carne_alumno(sec_alumnos.CURRVAL);
    pruebas_departamentos.insertar('Prueba 80 - Insertar un departamento', 'Departamento de Matemáticas', TRUE);
    cod_dept_mat := sec_departamentos.CURRVAL;
    pruebas_departamentos.insertar('Prueba 81 - Insertar un departamento', 'Departamento de Inglés', TRUE);
    cod_dept_ing := sec_departamentos.CURRVAL;
    pruebas_departamentos.insertar('Prueba 82 - Insertar un departamento con nombre null', NULL, FALSE);
    pruebas_departamentos.insertar('Prueba 83 - Insertar un departamento con nombre repetido', 'Departamento de Inglés', FALSE);
    pruebas_departamentos.actualizar('Prueba 84 - Actualizar el nombre de un departamento', cod_dept_mat, 'Departamento de Ciencias', NULL, TRUE);
    pruebas_departamentos.actualizar('Prueba 85 - Actualizar el nombre de un departamento a null', cod_dept_ing, NULL, NULL, FALSE);
    pruebas_departamentos.actualizar('Prueba 86 - Actualizar el nombre de un departamento a un nombre repetido', cod_dept_mat, 'Departamento de Inglés', NULL, FALSE);
    pruebas_departamentos.actualizar('Prueba 87 - Actualizar el jefe de un departamento', cod_dept_mat, 'Departamento de Ciencias', num_carne_prof, TRUE);
    pruebas_departamentos.actualizar('Prueba 88 - Actualizar el jefe de un departamento a null', cod_dept_mat, 'Departamento de Ciencias', NULL, TRUE);
    pruebas_departamentos.actualizar('Prueba 89 - Actualizar el jefe de un departamento con un alumno', cod_dept_mat, 'Departamento de Ciencias', num_carne_alum, FALSE);
    pruebas_departamentos.eliminar('Prueba 90 - Eliminar un departamento', cod_dept_ing, TRUE);
END;
/

--Aulas

BEGIN
    pruebas_aulas.inicializar;
    pruebas_aulas.insertar('Prueba 91 - Inserción de aula', 68, '1o Eso', TRUE);
    pruebas_aulas.insertar('Prueba 92 - Inserción de aula con numero null', NULL, '2o ESO', FALSE);
    pruebas_aulas.insertar('Prueba 93 - Inserción de aula con nombre null', 69, NULL,FALSE);
    pruebas_aulas.insertar('Prueba 94 - Inserción de aula con numero ya en uso', 68, '1o Eso',FALSE);
    pruebas_aulas.insertar('Prueba 95 - Inserción de aula con nombre ya en uso', 63, '1o Eso',FALSE);
    pruebas_aulas.actualizar('Prueba 96 - Actualizacion de nombre de aula', 68, '2o ESO', TRUE);
    pruebas_aulas.actualizar('Prueba 97 - Actualizacion de aula con nombre null', 68, NULL,FALSE);
    pruebas_aulas.actualizar('Prueba 98 - Actualizacion de aula con nombre ya en uso', 68, '3o Eso',FALSE); 
    pruebas_aulas.eliminar('Prueba 99 - Eliminar aula', 68, TRUE);
END;
/

--Materiales

DECLARE 
    n_oid_d INTEGER;
    n_oid_m INTEGER;
BEGIN
    pruebas_materiales.inicializar;
    n_oid_d:=sec_departamentos.CURRVAL;
    pruebas_materiales.insertar('Prueba 100 - Inserción de material', 'Balon', 'Perfecto',sysdate,18,n_oid_d, TRUE);
    n_oid_m:=sec_materiales.CURRVAL;
    pruebas_materiales.insertar('Prueba 101 - Inserción de material con descripcion null', NULL, 'Perfecto',sysdate, 18, n_oid_d,FALSE);
    pruebas_materiales.insertar('Prueba 102 - Inserción de material con un estado no valido', 'Bola', 'Pefecto',sysdate, 18, n_oid_d,FALSE);
    pruebas_materiales.insertar('Prueba 103 - Inserción de material con un estado null', 'Bola', NULL,sysdate, 18, n_oid_d,FALSE);
    pruebas_materiales.insertar('Prueba 104 - Inserción de material con unidades negativas', 'Bola', 'Perfecto',sysdate, -18, n_oid_d,FALSE);
    pruebas_materiales.insertar('Prueba 105 - Inserción de material con unidades null', 'Bola','Perfecto',sysdate,NULL, n_oid_d,FALSE);
    pruebas_materiales.insertar('Prueba 106 - Inserción de material con fecha null', 'Bola','Perfecto',NULL,18, n_oid_d,FALSE);
    pruebas_materiales.insertar('Prueba 107 - Inserción de material con OID_D no valido', 'Bola','Perfecto',sysdate,18, 19,FALSE);
    pruebas_materiales.insertar('Prueba 108 - Inserción de material con OID_D null', 'Bola','Perfecto',sysdate,18, NULL,FALSE);
    pruebas_materiales.actualizar('Prueba 109 - Actualizar descripcion de material',n_oid_m, 'Bolo','Perfecto',sysdate,NULL,18, n_oid_d,TRUE);
    pruebas_materiales.actualizar('Prueba 110 - Actualizar descripcion de material a null',n_oid_m, NULL,'Perfecto',sysdate,NULL,18, n_oid_d,FALSE);
    pruebas_materiales.actualizar('Prueba 111 - Actualizar estado de material',n_oid_m, 'Bolo','Bueno',sysdate,NULL,18, n_oid_d,TRUE);
    pruebas_materiales.actualizar('Prueba 112 - Actualizar estado de material a no valido',n_oid_m, 'Bolo','Buenisimo',sysdate,NULL,18, n_oid_d,FALSE);
    pruebas_materiales.actualizar('Prueba 113 - Actualizar estado de material a null',n_oid_m, 'Bolo',NULL,sysdate,NULL,18, n_oid_d,FALSE);
    pruebas_materiales.actualizar('Prueba 114 - Actualizar fecha de alta de material',n_oid_m, 'Bolo','Perfecto',sysdate-2,NULL,18, n_oid_d,TRUE);
    pruebas_materiales.actualizar('Prueba 115 - Actualizar fecha de alta de material a null',n_oid_m, 'Bolo','Perfecto',NULL,NULL,18, n_oid_d,FALSE);
    pruebas_materiales.actualizar('Prueba 116 - Actualizar fecha de baja de material',n_oid_m, 'Bolo','Perfecto',sysdate,sysdate+1,18, n_oid_d,TRUE);
    pruebas_materiales.actualizar('Prueba 117 - Actualizar fecha de baja de material a menor que fecha de alta',
    n_oid_m, 'Bolo','Perfecto',sysdate,sysdate-1,18, n_oid_d,FALSE);
    pruebas_materiales.actualizar('Prueba 118 - Actualizar fecha de baja de material a null',n_oid_m, 'Bolo','Perfecto',sysdate,NULL,18, n_oid_d,TRUE);
    pruebas_materiales.actualizar('Prueba 119 - Actualizar fecha de alta de material a mayor que fecha de baja',n_oid_m,
    'Bolo','Perfecto',sysdate+1,sysdate,18, n_oid_d,FALSE);
    pruebas_materiales.actualizar('Prueba 120 - Actualizar unidades de material',n_oid_m, 'Bolo','Perfecto',sysdate,NULL,20, n_oid_d,TRUE);
    pruebas_materiales.actualizar('Prueba 121 - Actualizar unidades de material a null',n_oid_m, 'Bolo','Perfecto',sysdate,NULL,NULL, n_oid_d,FALSE);
    pruebas_materiales.actualizar('Prueba 122 - Actualizar unidades de material a negativo',n_oid_m, 'Bolo','Perfecto',sysdate,NULL,-20, n_oid_d,FALSE);
    n_oid_d:=sec_departamentos.CURRVAL-1;
    pruebas_materiales.actualizar('Prueba 123 - Actualizar departamento de material',n_oid_m, 'Bolo','Perfecto',sysdate,NULL,20, n_oid_d,TRUE);
    pruebas_materiales.actualizar('Prueba 124 - Actualizar departamento de material a null',n_oid_m, 'Bolo','Perfecto',sysdate,NULL,20, NULL,FALSE);
    pruebas_materiales.actualizar('Prueba 125 - Actualizar departamento de material a no valido',n_oid_m, 'Bolo','Perfecto',sysdate,NULL,20, n_oid_d-200,FALSE);
    pruebas_materiales.eliminar('Prueba 126 - Eliminar material',n_oid_m,TRUE);   
END;
/

--Libros

BEGIN
    pruebas_libros.inicializar;
    pruebas_libros.insertar('Prueba 127 - Insertar un libro', '978-0553573404', 'Juego de Tronos', 'George RR Martin', 11, 'Fantasía', TRUE);
    pruebas_libros.insertar('Prueba 128 - Insertar un libro', '8445001841', 'El hombre en el castillo', 'Philip K Dick ', 11, 'Novela', TRUE);
    pruebas_libros.insertar('Prueba 129 - Insertar un libro con el nombre a null', '1234567890', NULL, 'Philip K Dick', 11, 'Novela', FALSE);
    pruebas_libros.insertar('Prueba 130 - Insertar un libro con el título a null', '1234567890', 'El hombre en el castillo', NULL, 11, 'Novela', FALSE);
    pruebas_libros.insertar('Prueba 131 - Insertar un libro con el CDU a null', '1234567890', 'El hombre en el castillo', 'Philip K Dick ', NULL, 'Novela', FALSE);
    pruebas_libros.insertar('Prueba 132 - Insertar un libro con el género a null', '1234567890', 'El hombre en el castillo', 'Philip K Dick ', 11, NULL, FALSE);
    pruebas_libros.insertar('Prueba 133 - Insertar un libro con un género que no es género', '1234567890', 'El hombre en el castillo', 'Philip K Dick ', 11, 'Aguacate', FALSE);
    pruebas_libros.insertar('Prueba 134 - Insertar un libro con un ISBN que ya está en la BBDD', '978-0553573404', 'El hombre en el castillo', 'Philip K Dick ', 11, 'Novela', FALSE);
    pruebas_libros.actualizar('Prueba 135 - Actualizar el título de un libro', '978-0553573404', 'Canción de Hielo y Fuego I: Juego de Tronos', 'George RR Martin', 11, 'Fantasía', 0, TRUE);
    pruebas_libros.actualizar('Prueba 136 - Actualizar el autor de un libro', '978-0553573404', 'Canción de Hielo y Fuego I: Juego de Tronos', 'George R. R. Martin', 11, 'Fantasía', 0, TRUE);
    pruebas_libros.actualizar('Prueba 137 - Actualizar el CDU de un libro', '978-0553573404', 'Canción de Hielo y Fuego I: Juego de Tronos', 'George R. R. Martin', 21, 'Fantasía', 0, TRUE);
    pruebas_libros.actualizar('Prueba 138 - Actualizar el género de un libro', '978-0553573404', 'Canción de Hielo y Fuego I: Juego de Tronos', 'George R. R. Martin', 21, 'Novela', 0, TRUE);
    pruebas_libros.actualizar('Prueba 139 - Actualizar las copias de un libro', '978-0553573404', 'Canción de Hielo y Fuego I: Juego de Tronos', 'George R. R. Martin', 21, 'Novela', 1, TRUE);
    pruebas_libros.actualizar('Prueba 140 - Actualizar el título de un libro a null', '8445001841', NULL, 'Philip K Dick ', 11, 'Novela', 0, FALSE);
    pruebas_libros.actualizar('Prueba 141 - Actualizar el autor de un libro a null', '8445001841', 'El hombre en el castillo', NULL, 11, 'Novela', 0, FALSE);
    pruebas_libros.actualizar('Prueba 142 - Actualizar el CDU de un libro a null', '8445001841', 'El hombre en el castillo', 'Philip K Dick', NULL, 'Novela', 0, FALSE);
    pruebas_libros.actualizar('Prueba 143 - Actualizar el género de un libro a null', '8445001841', 'El hombre en el castillo', 'Philip K Dick ', 11, NULL, 0, FALSE);
    pruebas_libros.actualizar('Prueba 144 - Actualizar el género de un libro con un género que no es género', '8445001841', 'El hombre en el castillo', 'Philip K Dick ', 11, 'Melocotón', 0, FALSE);
    pruebas_libros.actualizar('Prueba 145 - Actualizar las copias de un libro a null', '8445001841', 'El hombre en el castillo', 'Philip K Dick ', 11, 'Novela', NULL, FALSE);
    pruebas_libros.actualizar('Prueba 146 - Actualizar las copias de un libro con un número negativo', '8445001841', 'El hombre en el castillo', 'Philip K Dick ', 11, 'Novela', -1, FALSE);
    pruebas_libros.eliminar('Prueba 147 - Eliminar libro', '8445001841', TRUE);
END;
/

--Ejemplares

DECLARE
    n_oid INTEGER;
BEGIN
    pruebas_ejemplares.inicializar;
    pruebas_ejemplares.insertar('Prueba 148 - Inserción de ejemplar con isbn-13','Perfecto','978-3161484209', TRUE);
    n_oid:=sec_ejemplares.CURRVAL;
    pruebas_ejemplares.insertar('Prueba 149 - Inserción de ejemplar con isbn-10','Perfecto','9684842090', TRUE);
    pruebas_ejemplares.insertar('Prueba 150 - Inserción de ejemplar con estado no valido','erfecto','978-3161484209', FALSE);
    pruebas_ejemplares.insertar('Prueba 151 - Inserción de ejemplar con estado null',NULL,'978-3161484209', FALSE);
    pruebas_ejemplares.insertar('Prueba 152 - Inserción de ejemplar con isbn no valido','Perfecto','978-161484209', FALSE);
    pruebas_ejemplares.insertar('Prueba 153 - Inserción de ejemplar con isbn null','Perfecto',NULL, FALSE);
    pruebas_ejemplares.actualizar('Prueba 154 - Actualizacion de estado',n_oid,'Bueno',1,NULL,'978-3161484209', TRUE);
    pruebas_ejemplares.actualizar('Prueba 155 - Actualizacion de estado a uno no valido',n_oid,'Beno',1,NULL,'978-3161484209', FALSE);
    pruebas_ejemplares.actualizar('Prueba 156 - Actualizacion de estado a null',n_oid,NULL,1,NULL,'978-3161484209', FALSE);
    pruebas_ejemplares.actualizar('Prueba 157 - Actualizacion de disponible a false',n_oid,'Bueno',0,NULL,'978-3161484209', TRUE);
    pruebas_ejemplares.actualizar('Prueba 158 - Actualizacion de disponible a true',n_oid,'Bueno',1,NULL,'978-3161484209', TRUE);
    pruebas_ejemplares.actualizar('Prueba 159 - Actualizacion de disponible a null',n_oid,'Bueno',NULL,NULL,'978-3161484209', FALSE);
    pruebas_ejemplares.actualizar('Prueba 160 - Actualizacion de disponible a uno no valido',n_oid,'Bueno',6,NULL,'978-3161484209', FALSE);
    pruebas_ejemplares.actualizar('Prueba 161 - Actualizacion de isbn a null',n_oid,'Bueno',1,NULL,NULL, FALSE);
    pruebas_ejemplares.actualizar('Prueba 162 - Actualizacion de isbn a uno no valido',n_oid,'Bueno',1,NULL,'9783161484209', FALSE);
    pruebas_ejemplares.actualizar('Prueba 163 - Actualizacion de fecha baja',n_oid,'Bueno',1,sysdate,'978-3161484209', TRUE);
    pruebas_ejemplares.actualizar('Prueba 164 - Actualizacion de fecha baja a null',n_oid,'Bueno',1,NULL,'978-3161484209', TRUE);
    pruebas_ejemplares.eliminar('Prueba 165 - Eliminar un ejemplar',n_oid, TRUE);
END;
/

--ReservasMateriales
DECLARE
    n_oid_m INTEGER;
    n_oid_rm INTEGER;
    carne CHAR(6);
BEGIN
    pruebas_reservasmateriales.inicializar;
    n_oid_m:=sec_materiales.CURRVAL;
    carne:=carne_profesor(sec_profesores.CURRVAL);
    pruebas_reservasmateriales.insertar('Prueba 166 - Inserción de reserva de material',n_oid_m,carne,sysdate+2,3, TRUE);
    n_oid_rm:=sec_reserva_mat.CURRVAL;
    pruebas_reservasmateriales.insertar('Prueba 167 - Inserción de reserva de material con material null',NULL,carne,sysdate+2,3, FALSE);
    pruebas_reservasmateriales.insertar('Prueba 168 - Inserción de reserva de material con material no valido',n_oid_m-200,carne,sysdate+2,3, FALSE);
    pruebas_reservasmateriales.insertar('Prueba 169 - Inserción de reserva de material con carne null',n_oid_m,NULL,sysdate+2,3, FALSE);
    pruebas_reservasmateriales.insertar('Prueba 170 - Inserción de reserva de material con carne no valido',n_oid_m,'99999A',sysdate+2,3, FALSE);
    pruebas_reservasmateriales.insertar('Prueba 171 - Inserción de reserva de material con fecha de reserva null',n_oid_m,carne,NULL,3, FALSE);
    pruebas_reservasmateriales.insertar('Prueba 172 - Inserción de reserva de material con fecha de reserva no valida',n_oid_m,carne,sysdate-2,3, FALSE);
    pruebas_reservasmateriales.insertar('Prueba 173 - Inserción de reserva de material con tramo null',n_oid_m,carne,sysdate-2,NULL, FALSE);
    pruebas_reservasmateriales.insertar('Prueba 174 - Inserción de reserva de material con tramo no valido',n_oid_m,carne,sysdate-2,8, FALSE);
    pruebas_reservasmateriales.insertar('Prueba 175 - Inserción de reserva de material ya reservado',n_oid_m,carne,sysdate+2,3, FALSE);
    pruebas_reservasmateriales.actualizar('Prueba 176 - Actualizar fecha de reserva de material',n_oid_rm,n_oid_m,carne,sysdate+3,3, TRUE);
    pruebas_reservasmateriales.actualizar('Prueba 177 - Actualizar tramo de reserva de material',n_oid_rm,n_oid_m,carne,sysdate+3,4, TRUE);
    pruebas_reservasmateriales.actualizar('Prueba 178 - Actualizar fecha de reserva de material a null',n_oid_rm,n_oid_m,carne,NULL,4, FALSE);
    pruebas_reservasmateriales.actualizar('Prueba 179 - Actualizar fecha de reserva de material a no valida',n_oid_rm,n_oid_m,carne,sysdate-3,4, FALSE);
    pruebas_reservasmateriales.actualizar('Prueba 180 - Actualizar tramo de reserva de material a null',n_oid_rm,n_oid_m,carne,sysdate+3,NULL, FALSE);
    pruebas_reservasmateriales.actualizar('Prueba 181 - Actualizar tramo de reserva de material a no valido',n_oid_rm,n_oid_m,carne,sysdate+3,8, FALSE);
    pruebas_reservasmateriales.eliminar('Prueba 182 - Actualizar eliminar reserva de material',n_oid_rm, TRUE);
END;
/

--ReservasAulas

DECLARE
    numero INTEGER;
    n_oid_ra INTEGER;
    carne CHAR(6);
BEGIN
    pruebas_reservasaulas.inicializar;
    numero:=12;
    carne:=carne_profesor(sec_profesores.CURRVAL);
    pruebas_reservasaulas.insertar('Prueba 183 - Inserción de reserva de aula',numero,carne,sysdate+2,3, TRUE);
    n_oid_ra:=sec_reserva_aul.CURRVAL;
    pruebas_reservasaulas.insertar('Prueba 184 - Inserción de reserva de aula con aula null',NULL,carne,sysdate+2,3, FALSE);
    pruebas_reservasaulas.insertar('Prueba 185 - Inserción de reserva de aula con aula no valida',numero-200,carne,sysdate+2,3, FALSE);
    pruebas_reservasaulas.insertar('Prueba 186 - Inserción de reserva de aula con carne null',numero,NULL,sysdate+2,3, FALSE);
    pruebas_reservasaulas.insertar('Prueba 187 - Inserción de reserva de aula con carne no valido',numero,'99999A',sysdate+2,3, FALSE);
    pruebas_reservasaulas.insertar('Prueba 188 - Inserción de reserva de aula con fecha de reserva null',numero,carne,NULL,3, FALSE);
    pruebas_reservasaulas.insertar('Prueba 189 - Inserción de reserva de aula con fecha de reserva no valida',numero,carne,sysdate-2,3, FALSE);
    pruebas_reservasaulas.insertar('Prueba 190 - Inserción de reserva de aula con tramo null',numero,carne,sysdate-2,NULL, FALSE);
    pruebas_reservasaulas.insertar('Prueba 191 - Inserción de reserva de aula con tramo no valido',numero,carne,sysdate-2,8, FALSE);
    pruebas_reservasaulas.insertar('Prueba 192 - Inserción de reserva de aula ya reservado',numero,carne,sysdate+2,3, FALSE);
    pruebas_reservasaulas.actualizar('Prueba 193 - Actualizar fecha de reserva de aula',n_oid_ra,numero,carne,sysdate+3,3, TRUE);
    pruebas_reservasaulas.actualizar('Prueba 194 - Actualizar tramo de reserva de aula',n_oid_ra,numero,carne,sysdate+3,4, TRUE);
    pruebas_reservasaulas.actualizar('Prueba 195 - Actualizar fecha de reserva de aula a null',n_oid_ra,numero,carne,NULL,4, FALSE);
    pruebas_reservasaulas.actualizar('Prueba 196 - Actualizar fecha de reserva de aula a no valida',n_oid_ra,numero,carne,sysdate-3,4, FALSE);
    pruebas_reservasaulas.actualizar('Prueba 197 - Actualizar tramo de reserva de aula a null',n_oid_ra,numero,carne,sysdate+3,NULL, FALSE);
    pruebas_reservasaulas.actualizar('Prueba 198 - Actualizar tramo de reserva de aula a no valido',n_oid_ra,numero,carne,sysdate+3,8, FALSE);
    pruebas_reservasaulas.eliminar('Prueba 199 - Actualizar eliminar reserva de aula',n_oid_ra, TRUE);
END;
/

--Prestamos

DECLARE
    num_carne_alum CHAR(6);
    num_carne_alum_baja CHAR(6);
    num_carne_alum_claudia CHAR(6);
    num_carne_alum_jorge CHAR(6);
    num_carne_prof CHAR(6);
    codigo_ejemplar1 INTEGER;
    codigo_ejemplar2 INTEGER;
    codigo_ejemplar3 INTEGER;
    codigo_ejemplar4 INTEGER;
    codigo_ejemplar5 INTEGER;
    codigo_ejemplar6 INTEGER;
    oid_p_moroso INTEGER;
    oid_p1 INTEGER;
BEGIN
    pruebas_prestamos.inicializar;
    num_carne_alum := carne_alumno(sec_alumnos.CURRVAL);
    codigo_ejemplar1 := sec_ejemplares.CURRVAL;
    pruebas_prestamos.insertar('Prueba 200 - Inserción de un préstamo', num_carne_alum, codigo_ejemplar1, TRUE);
    pruebas_prestamos.insertar('Prueba 201 - Inserción de un préstamo de un libro ya prestado', num_carne_alum, codigo_ejemplar1, FALSE);
    add_libro(5,'978-2161484209', 'Celestina', 'Rojas', 2, 'Aventura', 'Bueno');
    codigo_ejemplar2 := sec_ejemplares.CURRVAL-4;
    pruebas_prestamos.insertar('Prueba 202 - Inserción de un préstamo de un alumno no valido', 'AA', codigo_ejemplar2, FALSE);
    codigo_ejemplar3 := sec_ejemplares.CURRVAL-3;
    codigo_ejemplar4 := sec_ejemplares.CURRVAL-2;
    codigo_ejemplar5 := sec_ejemplares.CURRVAL-1;
    codigo_ejemplar6 := sec_ejemplares.CURRVAL;
    pruebas_prestamos.insertar('Prueba 203 - Inserción de un préstamo con codigo de libro no valido', num_carne_alum, 9999, FALSE);
    presta_libro(num_carne_alum, codigo_ejemplar3);
    oid_p1 := sec_prestamos.CURRVAL;
    presta_libro(num_carne_alum, codigo_ejemplar4);
    pruebas_prestamos.insertar('Prueba 204 - Inserción de un préstamo de alumno sin disponibilidad de otro préstamo', num_carne_alum, codigo_ejemplar5, FALSE);
    alta_alumno('José', 'Díaz', '28983254B', TO_DATE('10/11/99', 'DD/MM/RR'), 'M', '999@gmail.com', '655667788', 'BACHILLERATO', 2, 'D');
    num_carne_alum_baja := carne_alumno(sec_alumnos.CURRVAL);
    UPDATE usuarios SET fecha_inicio_carne = '25/12/18' WHERE num_carne = num_carne_alum_baja;
    UPDATE usuarios SET fecha_validez_carne = '26/12/18' WHERE num_carne = num_carne_alum_baja;
    pruebas_prestamos.insertar('Prueba 205 - Inserción de un préstamo de alumno con carnet caducado', num_carne_alum_baja, codigo_ejemplar6, FALSE);
    pruebas_prestamos.insertar('Prueba 206 - Inserción de un préstamo con alumno null', NULL, codigo_ejemplar6, FALSE);
    alta_alumno('Jorge', 'Díaz', '28983254C', TO_DATE('10/11/98', 'DD/MM/RR'), 'M', '9998@gmail.com', '655667788', 'BACHILLERATO', 2, 'D');
    num_carne_alum_jorge := carne_alumno(sec_alumnos.CURRVAL);
    pruebas_prestamos.insertar('Prueba 207 - Inserción de un préstamo con ejemplar null', num_carne_alum_jorge, NULL, FALSE);
    alta_alumno('Claudia', 'Díaz', '18983254C', TO_DATE('10/10/98', 'DD/MM/RR'), 'M', '99989@gmail.com', '655667788', 'BACHILLERATO', 2, 'D');
    num_carne_alum_claudia := carne_alumno(sec_alumnos.CURRVAL);
    presta_libro(num_carne_alum_claudia, codigo_ejemplar5);
    oid_p_moroso := sec_prestamos.CURRVAL;
    UPDATE prestamos SET fecha_inicio = '14/12/18' WHERE oid_p = oid_p_moroso; 
    UPDATE prestamos SET fecha_fin = '29/12/18' WHERE oid_p = oid_p_moroso;
    pruebas_prestamos.insertar('Prueba 208 - Inserción de un préstamo de un alumno penalizado', num_carne_alum_claudia, codigo_ejemplar6, FALSE);
    INSERT INTO departamentos VALUES (1, 'Prestamos', NULL);
    alta_profesor('Profesor', 'Uno', '12345678Z', TO_DATE('10/11/75', 'DD/MM/RR'), 'M', 'prof1@mail.com', NULL, 'Prestamos');    
    num_carne_prof := carne_profesor(sec_profesores.CURRVAL);
    pruebas_prestamos.insertar('Prueba 209 - Inserción de un préstamo de un profesor', num_carne_prof, codigo_ejemplar6, TRUE);
    pruebas_prestamos.actualizar('Prueba 210 - Actualizar la fecha de inicio de un prestamo', oid_p1, num_carne_alum, 
    codigo_ejemplar3, '20/12/18', sysdate+15, NULL,TRUE);
    pruebas_prestamos.actualizar('Prueba 211 - Actualizar la fecha de fin de un prestamo', oid_p1, num_carne_alum, 
    codigo_ejemplar3, '20/12/18', '25/12/18', NULL,TRUE);
    pruebas_prestamos.actualizar('Prueba 212 - Actualizar la fecha de entrega de un prestamo', oid_p1, num_carne_alum, 
    codigo_ejemplar3, '20/12/18', '25/12/18', '24/12/18',TRUE);
    pruebas_prestamos.actualizar('Prueba 213 - Actualizar la fecha de inicio de un prestamo a null', oid_p1, num_carne_alum, 
    codigo_ejemplar3, NULL, '25/12/18', '24/12/18',FALSE);
    pruebas_prestamos.actualizar('Prueba 214 - Actualizar la fecha de fin de un prestamo a null', oid_p1, num_carne_alum, 
    codigo_ejemplar3, '20/12/18', NULL, '24/12/18',TRUE);
    pruebas_prestamos.actualizar('Prueba 215 - Actualizar la fecha de entrega de un prestamo a null', oid_p1, num_carne_alum, 
    codigo_ejemplar3, '20/12/18', '25/12/18', NULL,TRUE);
    pruebas_prestamos.eliminar('Prueba 216 - Eliminar un prestamo',oid_p1,TRUE);
END;
/