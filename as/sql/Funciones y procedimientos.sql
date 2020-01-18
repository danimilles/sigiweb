--***************************************************************************/
-- INTRODUCCION A LA INGENIERIA DE SOFTWARE Y SISTEMAS DE INFORMACION
-- GRADO EN INGENIERIA INFORMATICA - INGENIERIA DEL SOFTWARE
-- UNIVERSIDAD DE SEVILLA
-- CURSO 2018-2019
-- GRUPO 3
--
-- (C)  DANIEL SANCHEZ BALEYRON
--      JORGE RAPOSO D�AZ
--      JOSE RAMON FERNANDEZ DE LA ROSA
--
-- SISTEMA DE INTEGRAL DE GESTION DE INSTITUTOS - SIGI
-- Funciones y procedimientos.sql
--***************************************************************************/


--Funci�n para obtener el carn� de un alumno a partir de la secuencia sec_alumno

CREATE OR REPLACE FUNCTION carne_alumno 
(
  sec_carne_alum IN INTEGER
) RETURN VARCHAR2 AS 
    carne VARCHAR2(6);
BEGIN
    SELECT to_char(sec_carne_alum) INTO carne FROM dual;
    
    IF LENGTH(carne) = 1
        THEN SELECT CONCAT(CONCAT('0000', carne), 'A') INTO carne FROM dual;
    ELSE IF LENGTH(carne) = 2
        THEN SELECT CONCAT(CONCAT('000', carne), 'A') INTO carne FROM dual;
    ELSE IF LENGTH(carne) = 3
        THEN SELECT CONCAT(CONCAT('00', carne), 'A') INTO carne FROM dual;
    ELSE IF LENGTH(carne) = 4
        THEN SELECT CONCAT(CONCAT('0', carne), 'A') INTO carne FROM dual;
    ELSE 
        SELECT CONCAT(carne, 'A') INTO carne FROM dual;
    END IF;
    END IF;
    END IF;
    END IF;
    
  RETURN carne;
END carne_alumno;
/

--Procedimiento para dar de alta a un alumno

create or replace PROCEDURE alta_alumno 
(
  pass IN VARCHAR2
, nombre IN VARCHAR2 
, apellidos IN VARCHAR2 
, dni_al IN VARCHAR2 
, fecha_nacimiento IN VARCHAR2 
, sexo IN VARCHAR2 
, email IN VARCHAR2 
, telefono IN NUMBER 
, prog_academico IN VARCHAR2 
, curso_al IN NUMBER 
, grupo_al IN CHAR
) IS
carne CHAR(6);
BEGIN 
    INSERT INTO usuarios VALUES('11111A', pass, nombre, apellidos, dni_al, to_date(fecha_nacimiento, 'DD/MM/YYYY'), sexo, email, telefono, sysdate,
    sysdate + (365*4)+1, null);
    SELECT carne_alumno(sec_alumnos.CURRVAL) INTO carne FROM dual;
    UPDATE alumnos SET programa_academico = prog_academico WHERE num_carne = carne;
    UPDATE alumnos SET curso = curso_al WHERE num_carne = carne;
    UPDATE alumnos SET grupo = grupo_al WHERE num_carne = carne;
    COMMIT WORK;
END alta_alumno;
/

--Funcion para obtener el carne de un profesor a partir de sec_profesor

CREATE OR REPLACE FUNCTION carne_profesor 
(
  sec_carne_prof IN INTEGER
) RETURN VARCHAR2 AS 
    carne VARCHAR2(6);
BEGIN
    SELECT to_char(sec_carne_prof) INTO carne FROM dual;
    
    IF LENGTH(carne) = 1
        THEN SELECT CONCAT(CONCAT('0000', carne), 'P') INTO carne FROM dual;
    ELSE IF LENGTH(carne) = 2
        THEN SELECT CONCAT(CONCAT('000', carne), 'P') INTO carne FROM dual;
    ELSE IF LENGTH(carne) = 3
        THEN SELECT CONCAT(CONCAT('00', carne), 'P') INTO carne FROM dual;
    ELSE IF LENGTH(carne) = 4
        THEN SELECT CONCAT(CONCAT('0', carne), 'P') INTO carne FROM dual;
    ELSE SELECT CONCAT(carne, 'P') INTO carne FROM dual;
    END IF;
    END IF;
    END IF;
    END IF;
    
  RETURN carne;
END carne_profesor;
/

--Procedimiento para dar de alta a un profesor (RF-03)

CREATE OR REPLACE PROCEDURE alta_profesor 
(
  pass IN VARCHAR2
, nombre IN VARCHAR2 
, apellidos IN VARCHAR2 
, dni_pr IN VARCHAR2 
, fecha_nac IN DATE 
, sexo IN VARCHAR2 
, email IN VARCHAR2 
, telefono IN NUMBER 
, dept IN VARCHAR2
) IS
carne CHAR(6);
oiddept INTEGER;
BEGIN
    INSERT INTO usuarios VALUES('00000P', pass, nombre, apellidos, dni_pr, fecha_nac, sexo, email, telefono, sysdate, sysdate + (365*4)+1, null);
    SELECT carne_profesor(sec_profesores.CURRVAL) INTO carne FROM dual;
    SELECT oid_d INTO oiddept FROM departamentos WHERE nombre = dept;
    UPDATE profesores SET oid_d = oiddept WHERE num_carne = carne;
    COMMIT WORK;
END alta_profesor;
/

--Funcion que comprueba el formato del isbn y devuelve el isbn-13

CREATE OR REPLACE FUNCTION obtener_isbn_13
(
    isbn IN VARCHAR2
) RETURN VARCHAR2 AS 
    isbn_13 VARCHAR2(14);
    I NUMBER := 1;
    N NUMBER;
    pares NUMBER := 0;
    impares NUMBER := 0;
BEGIN
    IF (LENGTH(isbn) < 10 OR NOT REGEXP_LIKE(isbn, '[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]') OR
    (LENGTH(isbn) > 10 AND NOT REGEXP_LIKE(isbn, '[0-9][0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]')))
        THEN raise_application_error(-20003, 'El ISBN ' || isbn || ' no est� en el formato correcto');
    END IF;
    
    IF LENGTH(isbn) = 10 
        THEN SELECT CONCAT('978',isbn) INTO isbn_13 FROM dual;
        
        WHILE I <= 12 LOOP
            SELECT to_number(substr(isbn_13, I, 1)) INTO N FROM dual;
            IF MOD(N,2) = 0
                THEN SELECT N+pares INTO pares FROM dual;
                ELSE SELECT N+impares INTO impares FROM dual;
            END IF;
            I := I + 1;
        END LOOP;
        
        SELECT MOD(10 - MOD((impares + 3*pares),10), 10) INTO N FROM dual;
        SELECT CONCAT(CONCAT(CONCAT(substr(isbn_13, 1, 3), '-'),substr(isbn_13,4,9)),to_char(N)) INTO isbn_13 FROM dual;
    END IF;
    
    IF LENGTH(isbn) = 14
        THEN isbn_13:=isbn;
    END IF;
    
  RETURN isbn_13;
END obtener_isbn_13;
/

--Procedimiento que a�ade n ejemplares de un libro

CREATE OR REPLACE PROCEDURE add_n_ejemplares 
(
    w_cantidad IN INTEGER, 
    w_estado IN VARCHAR2, 
    w_isbn IN VARCHAR2
) IS
BEGIN
    FOR I IN 1..w_cantidad LOOP
        INSERT INTO ejemplares (estado, isbn) VALUES (w_estado, w_isbn);
    END LOOP;
    COMMIT WORK;
END;
/

--Procedimiento para a�adir un libro y n ejemplares del mismo

CREATE OR REPLACE PROCEDURE add_libro 
(
    w_cantidad IN INTEGER,
    w_isbn IN VARCHAR2,
    w_titulo IN VARCHAR2,
    w_autor IN VARCHAR2,
    w_cdu IN INTEGER,
    w_genero IN VARCHAR2,
    w_estado IN VARCHAR2
) IS
BEGIN
    INSERT INTO libros (isbn,titulo,autor,cdu,genero) VALUES (w_isbn, w_titulo, w_autor, w_cdu,w_genero);
    add_n_ejemplares(w_cantidad,w_estado,w_isbn);
    COMMIT WORK;
END;
/

--Procedimiento para renovar un prestamo (RN04)

CREATE OR REPLACE PROCEDURE renueva_prestamo
(
    w_oid_p IN INTEGER
) IS
    w_fecha_inicio DATE;
    w_fecha_fin DATE;
BEGIN
        SELECT fecha_fin INTO w_fecha_fin FROM prestamos WHERE prestamos.oid_p=w_oid_p;
        SELECT fecha_inicio INTO w_fecha_inicio FROM prestamos WHERE prestamos.oid_p=w_oid_p;
        
        IF (w_fecha_fin IS NULL)
            THEN raise_application_error(-20001, 'Un profesor no puede renovar un prestamo ya que no tiene fecha de devolucion');
        ELSIF (w_fecha_fin-w_fecha_inicio)>16 
            THEN raise_application_error(-20002, 'El prestamo ya se ha renovado una vez.');
        ELSIF (w_fecha_fin-w_fecha_inicio)<16 
            THEN UPDATE prestamos SET fecha_fin=fecha_fin+15 WHERE prestamos.oid_p=w_oid_p;
            COMMIT WORK;
        END IF;
END;
/

--Procedimiento para devolver un libro

CREATE OR REPLACE PROCEDURE devuelve_libro
(
    w_codigo IN prestamos.codigo%TYPE
) IS 
    w_oid INTEGER;
BEGIN
    SELECT oid_p INTO w_oid FROM prestamos WHERE fecha_entrega IS NULL AND codigo=w_codigo;
    UPDATE prestamos SET fecha_entrega = sysdate WHERE oid_p=w_oid;
    COMMIT WORK;
END devuelve_libro;
/

--Procedimiento para dar de baja un libro

CREATE OR REPLACE PROCEDURE baja_libro
(
    w_codigo IN ejemplares.codigo%TYPE
) IS 
    w_disponible INTEGER;
    w_isbn VARCHAR2(14);
BEGIN
    SELECT disponible INTO w_disponible FROM ejemplares WHERE codigo=w_codigo;
    SELECT isbn INTO w_isbn FROM ejemplares WHERE codigo = w_codigo;
    
    IF w_disponible=0 THEN
        raise_application_error(-20006, 'El libro no puede darse de baja porque est� prestado');
    END IF;
    
    UPDATE ejemplares SET fecha_baja = sysdate WHERE ejemplares.codigo = w_codigo;
    UPDATE ejemplares SET disponible=0 WHERE ejemplares.codigo=w_codigo;
    UPDATE libros SET copias=(copias-1) WHERE libros.isbn=w_isbn;
    COMMIT WORK;
END;
/

--Procedimiento para comrobar que el carne introducido es valido

CREATE OR REPLACE PROCEDURE carne_valido 
(
    n_carne IN CHAR
) IS
    w_fecha_validez DATE;
BEGIN
    SELECT fecha_validez_carne INTO w_fecha_validez FROM usuarios WHERE num_carne=n_carne;
    
    IF w_fecha_validez<sysdate THEN
        raise_application_error(-20009, 'El carne del usuario ha vencido');
    END IF;
END;
/

--Prodecimiento para renovar carne de usuario

CREATE OR REPLACE PROCEDURE renueva_carne
(
    n_carne IN CHAR
) IS
BEGIN
    UPDATE usuarios SET fecha_inicio_carne=sysdate WHERE num_carne=n_carne;
    UPDATE usuarios SET fecha_validez_carne=sysdate+(365*4)+1 WHERE num_carne=n_carne;
    COMMIT WORK;
END;
/

--Procedimiento para asignar un profesor como jefe de su departamento

CREATE OR REPLACE PROCEDURE asignar_jefe_departamento 
(
  w_prof IN VARCHAR2 
) AS
    dept INTEGER;
BEGIN
    carne_valido(w_prof);
    SELECT oid_d INTO dept FROM departamentos WHERE jefe_departamento = w_prof;
    UPDATE departamentos SET jefe_departamento = null WHERE oid_d = dept;
    SELECT oid_d INTO dept FROM profesores WHERE num_carne = w_prof;
    UPDATE departamentos SET jefe_departamento = w_prof WHERE oid_d = dept;
    COMMIT WORK;
    exception when others then
        SELECT oid_d INTO dept FROM profesores WHERE num_carne = w_prof;
        UPDATE departamentos SET jefe_departamento = w_prof WHERE oid_d = dept;
    COMMIT WORK;
END;
/

--Procedimiento para dar de baja un material. Si ese material est� reservado no se da de baja.

CREATE OR REPLACE PROCEDURE baja_material
(
    w_oid_m IN materiales.oid_m%TYPE
) IS 
    w_fecha_reserva DATE;
    existe INTEGER;
BEGIN
    SELECT COUNT(*) INTO existe FROM reservasmateriales WHERE reservasmateriales.oid_m = w_oid_m;
  
    IF (existe > 0)
    THEN SELECT fecha_reserva INTO w_fecha_reserva FROM reservasmateriales WHERE oid_m = w_oid_m;
  
        IF (w_fecha_reserva >= sysdate) THEN
            raise_application_error(-20013, 'El material no se puede dar de baja ya que est� reservado');
        END IF;
    END IF;
    
  UPDATE materiales SET fecha_baja = sysdate WHERE materiales.oid_m = w_oid_m;
END;
/

--Procedimiento para prestar un libro (RF08)

CREATE OR REPLACE PROCEDURE presta_libro 
(
    w_num_carne IN usuarios.num_carne%TYPE,
    w_codigo IN ejemplares.codigo%TYPE
) IS 
BEGIN
    carne_valido(w_num_carne);
    INSERT INTO prestamos (num_carne, codigo) VALUES (w_num_carne, w_codigo);
    COMMIT WORK;
END;
/

--Procedimiento para reservar un aula (RF07)

CREATE OR REPLACE PROCEDURE reserva_aula
(
    w_num_carne IN usuarios.num_carne%TYPE,
    w_numero IN aulas.numero%TYPE,
    w_fecha IN reservasaulas.fecha_reserva%TYPE,
    w_tramo IN reservasaulas.tramo%TYPE
) IS 
BEGIN
    carne_valido(w_num_carne);
    INSERT INTO reservasaulas(num_carne, numero, fecha_reserva, tramo) VALUES (w_num_carne, w_numero, w_fecha, w_tramo);
    COMMIT WORK;
END;
/

--Procedimiento para reservar un material (RF06)

CREATE OR REPLACE PROCEDURE reserva_material 
(
    w_num_carne IN usuarios.num_carne%TYPE,
    w_oid IN materiales.oid_m%TYPE,
    w_fecha IN reservasmateriales.fecha_reserva%TYPE,
    w_tramo IN reservasmateriales.tramo%TYPE
) IS 
BEGIN
    carne_valido(w_num_carne);
    INSERT INTO reservasmateriales(num_carne, oid_m, fecha_reserva, tramo) VALUES (w_num_carne, w_oid, w_fecha, w_tramo);
    COMMIT WORK;
END;
/

--Procedimiento para promocionar de curso un alumno

CREATE OR REPLACE PROCEDURE promocionar_curso 
(
    w_carne IN VARCHAR2,
    w_progacademico IN VARCHAR2,
    w_curso IN INTEGER,
    w_grupo IN CHAR
) IS
    carne CHAR(6);
BEGIN
    carne_valido(w_carne);
    UPDATE alumnos SET programa_academico = w_progacademico,curso = w_curso,grupo = w_grupo WHERE num_carne = w_carne;
    COMMIT WORK;
END;
/

--Funci�n ASSERT_EQUALS, necesaria para las pruebas

CREATE OR REPLACE FUNCTION assert_equals 
(
    salida BOOLEAN,
    salida_esperada BOOLEAN
) RETURN VARCHAR2 AS 
BEGIN
    IF (salida = salida_esperada) THEN
        RETURN 'EXITO';
    ELSE
        RETURN 'FALLO';
    END IF;
END;
/