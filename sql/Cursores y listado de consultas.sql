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
-- Cursores y listado de consultas.sql
--***************************************************************************/

SET SERVEROUTPUT ON;

--Listado de libros de la biblioteca

create or replace PROCEDURE c1_listado_libros as
    CURSOR C IS
    SELECT isbn,codigo,estado,disponible,titulo,autor,cdu,genero FROM ejemplares NATURAL JOIN libros
    WHERE fecha_baja IS NULL
    ORDER BY titulo;
BEGIN
    dbms_output.put_line('Listados de libros de la biblioteca:');
    dbms_output.put_line(rpad('Titulo:',20)||rpad('Autor:',20)||rpad('Codigo:',10)||
    rpad('ISBN:',18)||rpad('Genero:',12)||rpad('CDU:',8)||rpad('Estado:',12)||
    rpad('Disponible:',15));
    FOR fila IN C LOOP
        dbms_output.put_line(rpad(fila.titulo,20)||rpad(fila.autor,20)||rpad(fila.codigo,10)||
        rpad(fila.isbn,18)||rpad(fila.genero,12)||rpad(fila.cdu,8)||rpad(fila.estado,12)||
        rpad(fila.disponible,10));
    END LOOP;
END;
/

--Historial de prestamos de la biblioteca
create or replace PROCEDURE c2_historial_prestamos as
    CURSOR C IS
    SELECT num_carne,codigo, fecha_inicio, fecha_fin, fecha_entrega FROM prestamos ORDER BY fecha_inicio ASC;
BEGIN
    dbms_output.put_line('Historial de prestamos de la biblioteca:');
    dbms_output.put_line(rpad('Numero de Carne:',20)||rpad('Codigo:',10)||rpad('Fecha de inicio:',20)||
    rpad('Fecha de fin:',18)||rpad('Fecha de entrega:',20));
    FOR fila IN C LOOP
        IF fila.fecha_fin IS NULL AND fila.fecha_entrega IS NULL
            THEN dbms_output.put_line(rpad(fila.num_carne,20)||rpad(fila.codigo,10)||rpad(fila.fecha_inicio,20)||
            rpad('null',18)||rpad('null',12));
        ELSIF fila.fecha_fin IS NOT NULL AND fila.fecha_entrega IS NULL
            THEN dbms_output.put_line(rpad(fila.num_carne,20)||rpad(fila.codigo,10)||rpad(fila.fecha_inicio,20)||
            rpad(fila.fecha_fin,18)||rpad('null',12));
        ELSE dbms_output.put_line(rpad(fila.num_carne,20)||rpad(fila.codigo,10)||rpad(fila.fecha_inicio,20)||
            rpad(fila.fecha_fin,18)||rpad(fila.fecha_entrega,20));
        END IF;
    END LOOP;
END;
/

--Listado de aulas

create or replace PROCEDURE c3_listado_aulas as
    CURSOR C IS
    SELECT numero,nombre FROM aulas ORDER BY nombre ASC;
BEGIN
    dbms_output.put_line('Listado de aulas del instituto:');
    dbms_output.put_line(rpad('Nombre:',20)||rpad('Numero:',10));
    FOR fila IN C LOOP
        dbms_output.put_line(rpad(fila.nombre,20)||rpad(fila.numero,10));
    END LOOP;
END;
/

--Listado de materiales del instituto

create or replace PROCEDURE c4_listado_materiales as
    CURSOR C IS
    SELECT oid_m,descripcion, estado, fecha_alta, fecha_baja, unidades, nombre FROM materiales NATURAL JOIN departamentos 
    ORDER BY oid_m ASC;
BEGIN
    dbms_output.put_line('Listado de materiales del instituto:');
    dbms_output.put_line(rpad('ID:',10)||rpad('Descripción:',20)||rpad('Estado:',15)||rpad('Unidades:',15)
    ||rpad('Nombre:',20)||rpad('Fecha de alta:',20)||rpad('Fecha de baja:',20));
    FOR fila IN C LOOP
        dbms_output.put_line(rpad(fila.oid_m,10)||rpad(fila.descripcion,20)||rpad(fila.estado,15)||
        rpad(fila.unidades,15)||rpad(fila.nombre,20)||rpad(fila.fecha_alta,20)||rpad(fila.fecha_baja,20));
    END LOOP;
END;
/

--Historial de reservas de aulas

create or replace PROCEDURE c5_historial_reservas_aulas as
    CURSOR C IS
    SELECT num_carne,reservasaulas.numero,fecha_reserva,tramo,aulas.nombre
    FROM reservasaulas JOIN aulas ON reservasaulas.numero=aulas.numero 
    ORDER BY fecha_reserva ASC;
BEGIN
        dbms_output.put_line(CHR(13)||'Historial de reserva de aulas:');
        dbms_output.put_line(rpad('N Carne:',10)||rpad('N Aula:',10)||rpad('Nombre:',15)||rpad('Fecha reserva:',17)||
        rpad('Tramo horario:',15));
        FOR fila IN C LOOP
            dbms_output.put_line(rpad(fila.num_carne,10)||rpad(fila.numero,10)||
            rpad(fila.nombre,15)||rpad(fila.fecha_reserva,17)||rpad(fila.tramo,15));
        END LOOP;
END;
/

--Historial de reserva materiales

create or replace PROCEDURE c6_historial_reserva_mat as
    CURSOR C IS
    SELECT num_carne,reservasmateriales.oid_m,fecha_reserva,tramo,descripcion
    FROM reservasmateriales JOIN materiales ON reservasmateriales.oid_m=materiales.oid_m 
    ORDER BY fecha_reserva ASC;
BEGIN
        dbms_output.put_line(CHR(13)||'Historial de reserva de materiales:');
        dbms_output.put_line(rpad('N Carne:',10)||rpad('ID:',10)||rpad('Descripcion:',20)||rpad('Fecha reserva:',17)||
        rpad('Tramo horario:',15));
        FOR fila IN C LOOP
            dbms_output.put_line(rpad(fila.num_carne,10)||rpad(fila.oid_m,10)||rpad(fila.descripcion,20)||
            rpad(fila.fecha_reserva,17)||rpad(fila.tramo,15));
        END LOOP;
END;
/

--Lista de usuarios del sistema

create or replace PROCEDURE c7_lista_usuarios as
    CURSOR C IS
    SELECT num_carne,nombre,apellidos,dni,fechanacimiento,sexo,email,telefono,
    fecha_validez_carne FROM usuarios WHERE fecha_validez_carne>sysdate ORDER BY apellidos;
BEGIN
        dbms_output.put_line('Lista de usuarios del sistema:');
        dbms_output.put_line(rpad('Apellidos:',25)||rpad('Nombre:',12)||rpad('Carné:',10)||
        rpad('Dni:',12)||rpad('Fecha Nac:',12)||rpad('Sexo:',8)||rpad('Email:',30)||
        rpad('Fecha Exp:',12)||rpad('Telefono:',10));
        FOR fila IN C LOOP
            dbms_output.put_line(rpad(fila.apellidos,25)||rpad(fila.nombre,12)||rpad(fila.num_carne,10)||
            rpad(fila.dni,12)||rpad(fila.fechanacimiento,12)||rpad(fila.sexo,8)||rpad(fila.email,30)||
            rpad(fila.fecha_validez_carne,12)||rpad(fila.telefono,10));
        END LOOP;
END;
/

--Lista de usuarios con el carne caducado del sistema

create or replace PROCEDURE c8_usuarios_caducados as
    CURSOR C IS
    SELECT num_carne,nombre,apellidos,dni,fechanacimiento,sexo,email,telefono,
    fecha_validez_carne FROM usuarios WHERE fecha_validez_carne<sysdate ORDER BY apellidos;
BEGIN
        dbms_output.put_line('Lista de usuarios con el carne vencido:');
        dbms_output.put_line(rpad('Apellidos:',25)||rpad('Nombre:',12)||rpad('Carné:',10)||
        rpad('Dni:',12)||rpad('Fecha Nac:',12)||rpad('Sexo:',8)||rpad('Email:',30)||
        rpad('Fecha Exp:',12)||rpad('Telefono:',10));
        FOR fila IN C LOOP
            dbms_output.put_line(rpad(fila.apellidos,25)||rpad(fila.nombre,12)||rpad(fila.num_carne,10)||
            rpad(fila.dni,12)||rpad(fila.fechanacimiento,12)||rpad(fila.sexo,8)||rpad(fila.email,30)||
            rpad(fila.fecha_validez_carne,12)||rpad(fila.telefono,10));
        END LOOP;
END;
/

--Lista de alumnos del sistema

create or replace PROCEDURE c9_lista_alumnos as
    CURSOR C IS
    SELECT num_carne,nombre,apellidos,dni,fechanacimiento,sexo,email,telefono,
    fecha_validez_carne,programa_academico,curso,grupo FROM usuarios NATURAL JOIN alumnos 
    WHERE fecha_validez_carne>sysdate ORDER BY apellidos ASC;
BEGIN
        dbms_output.put_line('Lista de alumnos:');
        dbms_output.put_line(rpad('Apellidos:',25)||rpad('Nombre:',12)||rpad('Carné:',10)||
        rpad('Dni:',12)||rpad('Fecha Nac:',12)||rpad('Sexo:',8)||rpad('Email:',30)||
        rpad('Fecha Exp:',12)||rpad('Estudios:',15)||rpad('Curso:',8)||rpad('Grupo:',8)||
        rpad('Telefono:',10));
        FOR fila IN C LOOP
            dbms_output.put_line(rpad(fila.apellidos,25)||rpad(fila.nombre,12)||rpad(fila.num_carne,10)||
            rpad(fila.dni,12)||rpad(fila.fechanacimiento,12)||rpad(fila.sexo,8)||rpad(fila.email,30)||
            rpad(fila.fecha_validez_carne,12)||rpad(fila.programa_academico,15)||rpad(fila.curso,8)||
            rpad(fila.grupo,8)||rpad(fila.telefono,10));
        END LOOP;
END;
/

--Lista de profesores del sistema

create or replace PROCEDURE c10_lista_profesores as
    CURSOR C IS
    SELECT num_carne,usuarios.nombre,apellidos,dni,fechanacimiento,sexo,email,telefono,
    fecha_validez_carne,departamentos.nombre AS nom FROM usuarios NATURAL JOIN profesores JOIN departamentos 
    ON departamentos.oid_d=profesores.oid_d WHERE fecha_validez_carne>sysdate ORDER BY apellidos;
BEGIN
        dbms_output.put_line('Lista de profesores:');
        dbms_output.put_line(rpad('Apellidos:',25)||rpad('Nombre:',12)||rpad('Carné:',10)||
        rpad('Dni:',12)||rpad('Fecha Nac:',12)||rpad('Sexo:',8)||rpad('Email:',30)||
        rpad('Fecha Exp:',12)||rpad('Departamento:',15)||rpad('Telefono:',10));
        FOR fila IN C LOOP
            dbms_output.put_line(rpad(fila.apellidos,25)||rpad(fila.nombre,12)||rpad(fila.num_carne,10)||
            rpad(fila.dni,12)||rpad(fila.fechanacimiento,12)||rpad(fila.sexo,8)||rpad(fila.email,30)||
            rpad(fila.fecha_validez_carne,12)||rpad(fila.nom,15)||rpad(fila.telefono,10));
        END LOOP;
END;
/

--Lista de alumnos por curso

create or replace PROCEDURE c11_alumnos_curso as
    CURSOR C IS
    SELECT num_carne,nombre,apellidos,dni,fechanacimiento,sexo,email,telefono,
    fecha_validez_carne,programa_academico,curso,grupo FROM usuarios NATURAL JOIN alumnos
    WHERE fecha_validez_carne>sysdate ORDER BY programa_academico,curso,grupo,apellidos;
BEGIN
        dbms_output.put_line('Lista de alumnos por curso:');
        dbms_output.put_line(rpad('Apellidos:',25)||rpad('Nombre:',12)||rpad('Carné:',10)||
        rpad('Dni:',12)||rpad('Fecha Nac:',12)||rpad('Sexo:',8)||rpad('Email:',30)||
        rpad('Fecha Exp:',12)||rpad('Estudios:',15)||rpad('Curso:',8)||rpad('Grupo:',8)||
        rpad('Telefono:',10));
        FOR fila IN C LOOP
            IF fila.dni IS NULL THEN fila.dni:= 'null'; END IF;
            dbms_output.put_line(rpad(fila.apellidos,25)||rpad(fila.nombre,12)||rpad(fila.num_carne,10)||
            rpad(fila.dni,12)||rpad(fila.fechanacimiento,12)||rpad(fila.sexo,8)||rpad(fila.email,30)||
            rpad(fila.fecha_validez_carne,12)||rpad(fila.programa_academico,15)||rpad(fila.curso,8)||
            rpad(fila.grupo,8)||rpad(fila.telefono,10));
        END LOOP;
END;
/

--Lista de profesores por departamento

create or replace PROCEDURE c12_profesores_departamento as
    CURSOR C IS
    SELECT num_carne,usuarios.nombre,apellidos,dni,fechanacimiento,sexo,email,telefono,
    fecha_validez_carne,departamentos.nombre AS nom FROM usuarios NATURAL JOIN profesores JOIN departamentos 
    ON departamentos.oid_d=profesores.oid_d WHERE fecha_validez_carne>sysdate ORDER BY departamentos.oid_d,apellidos;
BEGIN
        dbms_output.put_line('Lista de profesores por departamento:');
        dbms_output.put_line(rpad('Apellidos:',25)||rpad('Nombre:',12)||rpad('Carné:',10)||
        rpad('Dni:',12)||rpad('Fecha Nac:',12)||rpad('Sexo:',8)||rpad('Email:',30)||
        rpad('Fecha Exp:',12)||rpad('Departamento:',15)||rpad('Telefono:',10));
        FOR fila IN C LOOP
            dbms_output.put_line(rpad(fila.apellidos,25)||rpad(fila.nombre,12)||rpad(fila.num_carne,10)||
            rpad(fila.dni,12)||rpad(fila.fechanacimiento,12)||rpad(fila.sexo,8)||rpad(fila.email,30)||
            rpad(fila.fecha_validez_carne,12)||rpad(fila.nom,15)||rpad(fila.telefono,10));
        END LOOP;
END;
/

--Lista de materiales por departamento

create or replace PROCEDURE c13_materiales_departamento as
    CURSOR C IS
    SELECT oid_m,descripcion, estado, fecha_alta, fecha_baja, unidades,nombre FROM materiales 
    NATURAL JOIN departamentos ORDER BY oid_d,oid_m ASC;
BEGIN
    dbms_output.put_line('Listado de materiales del instituto:');
    dbms_output.put_line(rpad('ID:',10)||rpad('Descripción:',25)||rpad('Estado:',15)||
    rpad('Unidades:',15)||rpad('Departamento:',40)||rpad('Fecha de alta:',20)||
    rpad('Fecha de baja:',20));
    FOR fila IN C LOOP
        dbms_output.put_line(rpad(fila.oid_m,10)||rpad(fila.descripcion,25)||rpad(fila.estado,15)||
        rpad(fila.unidades,15)||rpad(fila.nombre,40)||rpad(fila.fecha_alta,20)||
        rpad(fila.fecha_baja,20));
    END LOOP;
END;
/

--Listado de prestamos fuera de fecha

create or replace PROCEDURE c14_prestamos_fuera_fecha as
    CURSOR C IS
    SELECT num_carne,codigo, fecha_inicio, fecha_fin, fecha_entrega FROM prestamos
    WHERE fecha_fin<sysdate AND fecha_entrega IS NULL ORDER BY fecha_inicio ASC;
BEGIN
    dbms_output.put_line('Prestamos fuera de fecha:');
    dbms_output.put_line(rpad('Numero de Carne:',20)||rpad('Codigo:',10)||rpad('Fecha de inicio:',20)||
    rpad('Fecha de fin:',18)||rpad('Fecha de entrega:',20));
    FOR fila IN C LOOP
         dbms_output.put_line(rpad(fila.num_carne,20)||rpad(fila.codigo,10)||rpad(fila.fecha_inicio,20)||
         rpad(fila.fecha_fin,18)||rpad(fila.fecha_entrega,20));
    END LOOP;
END;
/

--Consulta de alumnos que alguna vez han sido sancionados

create or replace PROCEDURE c15_alumnos_sancionados as
    CURSOR C IS
    SELECT num_carne,nombre,apellidos,dni,fechanacimiento,sexo,email,telefono,
    fecha_validez_carne,programa_academico,curso,grupo FROM usuarios NATURAL JOIN alumnos NATURAL JOIN prestamos
    WHERE fecha_validez_carne>sysdate AND fecha_entrega>fecha_fin ORDER BY apellidos ASC;
BEGIN
        dbms_output.put_line('Lista de alumnos que alguna vez han sido sancionados:');
        dbms_output.put_line(rpad('Apellidos:',25)||rpad('Nombre:',12)||rpad('Carné:',10)||
        rpad('Dni:',12)||rpad('Fecha Nac:',12)||rpad('Sexo:',8)||rpad('Email:',30)||
        rpad('Fecha Exp:',12)||rpad('Estudios:',15)||rpad('Curso:',8)||rpad('Grupo:',8)||
        rpad('Telefono:',10));
        FOR fila IN C LOOP
            dbms_output.put_line(rpad(fila.apellidos,25)||rpad(fila.nombre,12)||rpad(fila.num_carne,10)||
            rpad(fila.dni,12)||rpad(fila.fechanacimiento,12)||rpad(fila.sexo,8)||rpad(fila.email,30)||
            rpad(fila.fecha_validez_carne,12)||rpad(fila.programa_academico,15)||rpad(fila.curso,8)||
            rpad(fila.grupo,8)||rpad(fila.telefono,10));
        END LOOP;
END;
/

--Estadistica: Alumnos que mas piden en prestamos

create or replace PROCEDURE c16_alumnos_mas_activos as
    CURSOR C IS
    SELECT num_carne,apellidos,nombre,programa_academico,curso,grupo,COUNT(*) AS nom FROM prestamos 
    NATURAL JOIN alumnos NATURAL JOIN usuarios
    GROUP BY num_carne,apellidos,nombre,programa_academico,curso,grupo ORDER BY nom DESC;
BEGIN
        dbms_output.put_line('Alumnos que mas libros piden en prestamo:');
        dbms_output.put_line(rpad('Apellidos:',25)||rpad('Nombre:',12)||rpad('Carné:',10)
        ||rpad('Estudios:',15)||rpad('Curso:',8)||rpad('Grupo:',8)||rpad('N Prestamos:',8));
        FOR fila IN C LOOP
            dbms_output.put_line(rpad(fila.apellidos,25)||rpad(fila.nombre,12)||rpad(fila.num_carne,10)||
            rpad(fila.programa_academico,15)||rpad(fila.curso,8)||rpad(fila.grupo,8)||rpad(fila.nom,8));
        END LOOP;
END;
/

--Estadisticas: Libros mas prestados por sexos

create or replace PROCEDURE c17_libros_por_sexo as
    CURSOR M IS
    SELECT isbn, titulo, autor, genero, cdu, COUNT(*) AS cuenta FROM usuarios NATURAL JOIN alumnos NATURAL JOIN prestamos
    NATURAL JOIN ejemplares NATURAL JOIN libros
    WHERE sexo = 'M' GROUP BY isbn, titulo, autor, genero, cdu ORDER BY cuenta DESC;
    CURSOR w IS
    SELECT isbn, titulo, autor, genero, cdu, COUNT(*) AS cuenta FROM usuarios NATURAL JOIN alumnos NATURAL JOIN prestamos
    NATURAL JOIN ejemplares NATURAL JOIN libros
    WHERE sexo = 'F' GROUP BY isbn, titulo, autor, genero, cdu ORDER BY cuenta DESC;
BEGIN
        dbms_output.put_line('Libros mas populares entre alumnos de sexo masculino:');
        dbms_output.put_line(rpad('Titulo:',25)||rpad('Autor:',12)||rpad('Género:',10)
        ||rpad('ISBN:',15)||rpad('CDU:',8)||rpad('Veces prestado:',15));
        FOR fila_m IN M LOOP
            dbms_output.put_line(rpad(fila_m.titulo,25)||rpad(fila_m.autor,12)||rpad(fila_m.genero,10)
            ||rpad(fila_m.isbn,15)||rpad(fila_m.cdu,8)||rpad(fila_m.cuenta,15));
        END LOOP;
        dbms_output.put_line(CHR(13) || 'Libros mas populares entre alumnos de sexo femenino:');
        dbms_output.put_line(rpad('Titulo:',25)||rpad('Autor:',12)||rpad('Género:',10)
        ||rpad('ISBN:',15)||rpad('CDU:',8)||rpad('Veces prestado:',15));
        FOR fila_w IN w LOOP
            dbms_output.put_line(rpad(fila_w.titulo,25)||rpad(fila_w.autor,12)||rpad(fila_w.genero,10)
            ||rpad(fila_w.isbn,15)||rpad(fila_w.cdu,8)||rpad(fila_w.cuenta,15));
        END LOOP;
END;
/

--Estadisticas: Libros mas prestados

create or replace PROCEDURE c18_libros_mas_prestados as
    CURSOR C IS
    SELECT isbn, titulo, autor, cdu, genero, COUNT(*) AS cuenta 
    FROM libros NATURAL JOIN ejemplares NATURAL JOIN prestamos 
    NATURAL JOIN alumnos GROUP BY isbn, titulo, autor, cdu, genero ORDER BY cuenta DESC ;
BEGIN
        dbms_output.put_line('Libros más prestados:');
        dbms_output.put_line(rpad('Titulo:',25)||rpad('Autor:',12)||rpad('Género:',10)
        ||rpad('ISBN:',15)||rpad('CDU:',8)||rpad('Veces prestado:',15));
        FOR fila IN C LOOP dbms_output.put_line(rpad(fila.titulo,25)||rpad(fila.autor,12)||rpad(fila.genero,10)||
            rpad(fila.isbn,15)||rpad(fila.cdu,8)||rpad(fila.cuenta,8));
        END LOOP;
END;
/

--Estadisticas: Géneros mas prestados

create or replace PROCEDURE c19_generos_mas_prestados as
    CURSOR C IS
    SELECT genero, COUNT(*) AS cuenta FROM libros NATURAL JOIN ejemplares NATURAL JOIN prestamos 
    GROUP BY genero ORDER BY cuenta DESC ;
BEGIN
        dbms_output.put_line('Géneros más prestados:');
        dbms_output.put_line(rpad('Género:',15)||rpad('Veces prestado:',15));
        FOR fila IN C LOOP dbms_output.put_line(rpad(fila.genero,15)||rpad(fila.cuenta,8));
        END LOOP;
END;
/

--Estadisticas: Libros mas populares por edades

create or replace PROCEDURE c20_libros_por_edad as
    N INTEGER;
BEGIN
    N:=12;
    
    WHILE N<67 LOOP
        dbms_output.put_line(CHR(13)||'Libros mas populares entre alumnos de '||(N-1)||' y '||(N+1)||' años:');
        dbms_output.put_line(rpad('Titulo:',25)||rpad('Autor:',12)||rpad('Género:',10)
        ||rpad('ISBN:',15)||rpad('CDU:',8)||rpad('Veces prestado:',15));
        
        FOR fila_m IN (SELECT isbn, titulo, autor, genero, cdu, COUNT(*) AS cuenta
        FROM usuarios NATURAL JOIN alumnos NATURAL JOIN prestamos
        NATURAL JOIN ejemplares NATURAL JOIN libros
        WHERE (sysdate-fechanacimiento)/365 BETWEEN (N-1) AND (N+1)
        GROUP BY isbn, titulo, autor, genero, cdu ORDER BY cuenta DESC) LOOP
            dbms_output.put_line(rpad(fila_m.titulo,25)||rpad(fila_m.autor,12)||rpad(fila_m.genero,10)
            ||rpad(fila_m.isbn,15)||rpad(fila_m.cdu,8)||rpad(fila_m.cuenta,8));
        END LOOP;
        
        N:=N+3;
     END LOOP;
END;
/

--Estadística: Libros mas prestados por curso

create or replace PROCEDURE c21_libros_por_curso as
    estudios VARCHAR2(50);
BEGIN
    dbms_output.put_line('Estadísticas sobre préstamos de libros por curso');
    FOR I IN 1..5 LOOP
        IF I = 1
            THEN estudios := 'ESO';
        ELSIF I = 2
            THEN estudios := 'BACHILLERATO';
        ELSIF I = 3
            THEN estudios := 'FP Básica';
        ELSIF I = 4
            THEN estudios := 'CFGM Gestión Administrativa';
        ELSE estudios := 'CFGS Administración y Finanzas';
        END IF;
        IF I = 1
        THEN FOR N IN 1..4 LOOP
                dbms_output.put_line(CHR(13) || 'Libros más reservados de ' || N || 'º de ' || estudios || ':');
                dbms_output.put_line(rpad('Titulo:',25)||rpad('Autor:',12)||rpad('Género:',10)
                ||rpad('ISBN:',15)||rpad('CDU:',8)||rpad('Veces prestado:',15));
                FOR fila IN (SELECT isbn, titulo, autor, genero, cdu, COUNT(*) AS cuenta FROM usuarios 
                NATURAL JOIN alumnos NATURAL JOIN prestamos NATURAL JOIN ejemplares NATURAL JOIN libros
                WHERE programa_academico = estudios AND curso = N GROUP BY isbn, titulo, autor, genero, cdu ORDER BY cuenta DESC) LOOP
                    dbms_output.put_line(rpad(fila.titulo,25)||rpad(fila.autor,12)||rpad(fila.genero,10)
                    ||rpad(fila.isbn,15)||rpad(fila.cdu,8)||rpad(fila.cuenta,15));
                END LOOP;
            END LOOP;
        ELSE FOR N IN 1..2 LOOP
            dbms_output.put_line(CHR(13) || 'Libros más reservados de ' || N || 'º de ' || estudios || ':');
                dbms_output.put_line(rpad('Titulo:',25)||rpad('Autor:',12)||rpad('Género:',10)
                ||rpad('ISBN:',15)||rpad('CDU:',8)||rpad('Veces prestado:',15));
                FOR fila IN (SELECT isbn, titulo, autor, genero, cdu, COUNT(*) AS cuenta FROM usuarios 
                NATURAL JOIN alumnos NATURAL JOIN prestamos NATURAL JOIN ejemplares NATURAL JOIN libros
                WHERE programa_academico = estudios AND curso = N GROUP BY isbn, titulo, autor, genero, cdu ORDER BY cuenta DESC) LOOP
                    dbms_output.put_line(rpad(fila.titulo,25)||rpad(fila.autor,12)||rpad(fila.genero,10)
                    ||rpad(fila.isbn,15)||rpad(fila.cdu,8)||rpad(fila.cuenta,15));
                END LOOP;
        END LOOP;
        END IF;
    END LOOP;
END;
/


--Procedure para ver los datos asociados a un usuario

CREATE OR REPLACE PROCEDURE c22_vista_usuario
(n_carne IN VARCHAR2) IS
BEGIN
    carne_valido(n_carne);
    dbms_output.put_line('Datos del usuario:');
    --Si el usuario es un profesor, muestra datos, reservas, y prestamos.
    IF substr(n_carne, 6) = 'P' THEN
        --Impresion de datos del usuario
        dbms_output.put_line('Tipo de usuario: Profesor');
        dbms_output.put_line(rpad('Apellidos:',25)||rpad('Nombre:',12)||rpad('Carné:',10)||
        rpad('Dni:',12)||rpad('Fecha Nac:',12)||rpad('Sexo:',8)||rpad('Email:',30)||
        rpad('Fecha Exp:',12)||rpad('Departamento:',15)||rpad('Telefono:',10));
        FOR fila IN (SELECT num_carne,usuarios.nombre,apellidos,dni,fechanacimiento,sexo,email,telefono,
        fecha_validez_carne,departamentos.nombre AS nom FROM usuarios NATURAL JOIN profesores JOIN departamentos 
        ON departamentos.oid_d=profesores.oid_d WHERE num_carne=n_carne ORDER BY apellidos) LOOP
            dbms_output.put_line(rpad(fila.apellidos,25)||rpad(fila.nombre,12)||rpad(fila.num_carne,10)||
            rpad(fila.dni,12)||rpad(fila.fechanacimiento,12)||rpad(fila.sexo,8)||rpad(fila.email,30)||
            rpad(fila.fecha_validez_carne,12)||rpad(fila.nom,15)||rpad(fila.telefono,10));
        END LOOP;
        --Impresion de historial de prestamos
        dbms_output.put_line(CHR(13)||'Historial de prestamos del usuario:');
        dbms_output.put_line(rpad('Codigo:',10)||rpad('Fecha inicio:',15)||rpad('Fecha entrega:',15));
        FOR fila IN (SELECT codigo, fecha_inicio, fecha_entrega 
        FROM prestamos WHERE num_carne=n_carne ORDER BY fecha_inicio ASC) LOOP
            dbms_output.put_line(rpad(fila.codigo,10)||rpad(fila.fecha_inicio,15)||rpad(fila.fecha_entrega,15));
        END LOOP;
        --Impresion de historial de reserva de aulas
        dbms_output.put_line(CHR(13)||'Historial de reserva de aulas:');
        dbms_output.put_line(rpad('N Aula:',10)||rpad('Nombre:',15)||rpad('Fecha reserva:',17)||
        rpad('Tramo horario:',15));
        FOR fila IN (SELECT reservasaulas.numero,fecha_reserva,tramo,aulas.nombre
        FROM reservasaulas JOIN aulas ON reservasaulas.numero=aulas.numero 
        WHERE num_carne=n_carne ORDER BY fecha_reserva ASC) LOOP
            dbms_output.put_line(rpad(fila.numero,10)||rpad(fila.nombre,15)||rpad(fila.fecha_reserva,17)||
            rpad(fila.tramo,15));
        END LOOP;
        --Impresion de historial de reserva de materiales
        dbms_output.put_line(CHR(13)||'Historial de reserva de material:');
        dbms_output.put_line(rpad('ID:',10)||rpad('Descripcion:',20)||rpad('Fecha reserva:',17)||
        rpad('Tramo horario:',15));
        FOR fila IN (SELECT reservasmateriales.oid_m,fecha_reserva,tramo,descripcion
        FROM reservasmateriales JOIN materiales ON reservasmateriales.oid_m=materiales.oid_m 
        WHERE num_carne=n_carne ORDER BY fecha_reserva ASC) LOOP
            dbms_output.put_line(rpad(fila.oid_m,10)||rpad(fila.descripcion,20)||rpad(fila.fecha_reserva,17)||
            rpad(fila.tramo,15));
        END LOOP;
    --Si el usuario es un alumno, muestra datosy prestamos.
    ELSIF substr(n_carne, 6) = 'A' THEN
        --Impresion de datos del usuario
        dbms_output.put_line('Tipo de usuario: Alumno');
        dbms_output.put_line(rpad('Apellidos:',25)||rpad('Nombre:',12)||rpad('Carné:',10)||
        rpad('Dni:',12)||rpad('Fecha Nac:',12)||rpad('Sexo:',8)||rpad('Email:',30)||
        rpad('Fecha Exp:',12)||rpad('Estudios:',15)||rpad('Curso:',8)||rpad('Grupo:',8)||
        rpad('Telefono:',10));
        FOR fila IN (SELECT num_carne,nombre,apellidos,dni,fechanacimiento,sexo,email,telefono,
        fecha_validez_carne,programa_academico,curso,grupo FROM usuarios NATURAL JOIN alumnos 
        WHERE num_carne=n_carne ORDER BY apellidos) LOOP
            dbms_output.put_line(rpad(fila.apellidos,25)||rpad(fila.nombre,12)||rpad(fila.num_carne,10)||
            rpad(fila.dni,12)||rpad(fila.fechanacimiento,12)||rpad(fila.sexo,8)||rpad(fila.email,30)||
            rpad(fila.fecha_validez_carne,12)||rpad(fila.programa_academico,15)||rpad(fila.curso,8)||
            rpad(fila.grupo,8)||rpad(fila.telefono,10));
        END LOOP;
        --Impresion de historial de prestamos
        dbms_output.put_line(CHR(13)||'Historial de prestamos del usuario:');
        dbms_output.put_line(rpad('Codigo:',10)||rpad('Fecha inicio:',15)||rpad('Fecha fin:',15)||
        rpad('Fecha entrega:',15));
        FOR fila IN (SELECT codigo, fecha_inicio, fecha_fin, fecha_entrega 
        FROM prestamos WHERE num_carne=n_carne ORDER BY fecha_inicio ASC) LOOP
            dbms_output.put_line(rpad(fila.codigo,10)||rpad(fila.fecha_inicio,15)||rpad(fila.fecha_fin,15)||
            rpad(fila.fecha_entrega,15));
        END LOOP;
    END IF;
END;
/


--Ejecutamos las consultas y se crea un txt con las consultas para poder imprimirlas en papel:

SPOOL 'consultas.txt' REPLACE

exec c1_listado_libros;
exec c2_historial_prestamos;
exec c3_listado_aulas;
exec c4_listado_materiales;
exec c5_historial_reservas_aulas;
exec c6_historial_reserva_mat;
exec c7_lista_usuarios;
exec c8_usuarios_caducados;
exec c9_lista_alumnos;
exec c10_lista_profesores;
exec c11_alumnos_curso;
exec c12_profesores_departamento;
exec c13_materiales_departamento;
exec c14_prestamos_fuera_fecha;
exec c15_alumnos_sancionados;
exec c16_alumnos_mas_activos;
exec c17_libros_por_sexo;
exec c18_libros_mas_prestados;
exec c19_generos_mas_prestados;
exec c20_libros_por_edad;
exec c21_libros_por_curso;
exec c22_vista_usuario('00001P');
exec c22_vista_usuario('00001A');

SPOOL OFF