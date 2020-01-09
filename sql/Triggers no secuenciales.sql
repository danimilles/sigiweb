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
-- Triggers no secuenciales.sql
--***************************************************************************/

--Trigger para incluir el usuario automaticamente en alumnos o profesores

CREATE OR REPLACE TRIGGER tr_alumnos_profesores 
AFTER INSERT ON usuarios 
FOR EACH ROW
BEGIN
    IF substr(:NEW.num_carne, 6) = 'A'
        THEN INSERT INTO alumnos VALUES (:NEW.num_carne, NULL, NULL, NULL);
    ELSE 
        INSERT INTO profesores VALUES (:NEW.num_carne, NULL);
    END IF;
END;
/

--Trigger para aumentar o disminuir el numero de copias cuando se añade o quita
--un ejemplar de un libro

CREATE OR REPLACE TRIGGER tr_add_copia
BEFORE INSERT ON ejemplares
FOR EACH ROW
BEGIN
  UPDATE libros SET copias=(copias+1) WHERE libros.isbn=:NEW.isbn;
END;
/

--Trigger para obtener el formato correcto del isbn antes de insertar un libro

CREATE OR REPLACE TRIGGER tr_isbn
BEFORE INSERT ON libros 
FOR EACH ROW
BEGIN
    SELECT obtener_isbn_13(:NEW.isbn) INTO :NEW.isbn FROM dual;
END;
/

--Trigger que inserta automaticamente la fecha fin de un prestamo de un alumno (15 dias despues del inicio),
--si es un prestamo de un profesor no modifica la fecha fin (RN03)

CREATE OR REPLACE TRIGGER tr_fecha_devolucion 
BEFORE INSERT ON prestamos
FOR EACH ROW
DECLARE
    fecha DATE := :NEW.fecha_inicio + 15;
BEGIN
    IF substr(:NEW.num_carne, 6) = 'A'
      THEN :NEW.fecha_fin := fecha;
    ELSIF substr(:NEW.num_carne, 6) = 'P'
      THEN :NEW.fecha_fin := NULL;
    END IF;
END;
/

--Trigger que comprueba si el alumno que intenta hacer un prestamo tiene ya 3 prestamos vigentes
--y si es asi no lo deja, si es profesor no hace nada (RN02)

CREATE OR REPLACE TRIGGER tr_max_prestamos 
BEFORE INSERT ON prestamos 
FOR EACH ROW
DECLARE
    carne CHAR(6):= :NEW.num_carne;
    num_prestamos INTEGER;
BEGIN
    SELECT COUNT(*) INTO num_prestamos FROM prestamos WHERE (prestamos.num_carne = carne AND prestamos.fecha_entrega IS NULL);
    
    IF(num_prestamos > 2 AND substr(carne, 6) = 'A') THEN
        raise_application_error(-20004, 'No se pueden tener prestados más de 3 ejemplares al mismo tiempo.');
    END IF;
END;
/

--Trigger que comprueba si el ejemplar que se quiere prestar está disponible para ello

CREATE OR REPLACE TRIGGER tr_ejemplar_disponible 
BEFORE INSERT ON prestamos 
FOR EACH ROW
DECLARE 
    w_codigo INTEGER := :NEW.codigo;
    disponibilidad INTEGER;
BEGIN
    SELECT disponible INTO disponibilidad FROM ejemplares WHERE ejemplares.codigo = w_codigo;
    
    IF(disponibilidad = 0) THEN
        raise_application_error(-20005, 'El ejemplar no está disponible para ser prestado');
    END IF;
END;
/

--Trigger que comprueba si el alumno no ha devuelto un préstamo en fecha, o si está cumpliendo sanción por ello (RN08)

CREATE OR REPLACE TRIGGER tr_morosos 
BEFORE INSERT ON prestamos 
FOR EACH ROW
DECLARE
    CURSOR C IS
        SELECT fecha_fin, fecha_entrega FROM prestamos WHERE num_carne = :NEW.num_carne ORDER BY fecha_fin DESC;
    final_sancion DATE;
BEGIN
    IF(substr(:NEW.num_carne, 6) = 'A')
        THEN FOR fila IN C LOOP
            IF(fila.fecha_entrega IS NULL AND fila.fecha_fin < sysdate)
                THEN raise_application_error(-20007, 'El alumno con carné ' || :NEW.num_carne || 
                    ' tiene cumplido un préstamo y no ha devuelto el ejemplar, por lo que no puede reservar otro');
                ELSE IF(fila.fecha_fin < fila.fecha_entrega AND fila.fecha_entrega+7 >= sysdate)
                    THEN SELECT fila.fecha_entrega + 7 INTO final_sancion FROM dual;
                    raise_application_error(-20008, 'El alumno con carné ' || :NEW.num_carne || 
                    ' está sancionado por no haber devuelto un préstamo. La sanción acaba el día ' || final_sancion);
                END IF;
            END IF;
        END LOOP;
    END IF;
END;
/

--Triggers para actualizar disponibilidad del libro

CREATE OR REPLACE TRIGGER tr_presta_libro
AFTER INSERT ON prestamos
FOR EACH ROW
BEGIN
    UPDATE ejemplares SET disponible=0 WHERE ejemplares.codigo=:NEW.codigo;
END;
/

CREATE OR REPLACE TRIGGER tr_devuelve_libro
AFTER UPDATE ON prestamos
FOR EACH ROW
BEGIN
    IF :NEW.fecha_entrega IS NOT NULL THEN
        UPDATE ejemplares SET disponible=1 WHERE ejemplares.codigo=:NEW.codigo;
    END IF;
END;
/

--Trigger que comprueba el isbn en ejemplares

CREATE OR REPLACE TRIGGER tr_isbn_e
BEFORE INSERT ON ejemplares
FOR EACH ROW
BEGIN
    SELECT obtener_isbn_13(:NEW.isbn) INTO :NEW.isbn FROM dual;
END;
/

--Triggers que comprueban si un material ya está reservado para una fecha y una hora
--y si la fecha de la reserva no es anterior a la actual

CREATE OR REPLACE TRIGGER tr_reserva_mat_f
BEFORE UPDATE ON reservasmateriales 
FOR EACH ROW
BEGIN
    IF(:NEW.fecha_reserva < sysdate)
    THEN raise_application_error(-20010, 'No se puede realizar la reserva, ya que se está intentando reservar para un día anterior
    a la fecha de hoy');
  END IF;
END;
/

create or replace TRIGGER tr_reserva_mat 
BEFORE INSERT ON reservasmateriales 
FOR EACH ROW
DECLARE
    CURSOR C IS
      SELECT * FROM reservasmateriales WHERE oid_m = :NEW.oid_m ORDER BY fecha_reserva DESC;
BEGIN
    IF(substr(:NEW.num_carne, 6) = 'A') THEN
        raise_application_error(-20015, 'Un alumno no puede reservar materiales o aulas');
    END IF;

    carne_valido(:NEW.num_carne);

    IF(:NEW.fecha_reserva < to_date(TO_CHAR(SYSDATE, 'yyyy/mm/dd'), 'yyyy/mm/dd'))
        THEN raise_application_error(-20010, 'No se puede realizar la reserva, ya que se está intentando reservar para un día anterior
        a la fecha de hoy');
    END IF;

    FOR fila IN C LOOP
        IF(fila.fecha_reserva = :NEW.fecha_reserva AND fila.tramo = :NEW.tramo)
            THEN raise_application_error(-20011, 'El material no se puede reservar porque ya ha sido reservado para el día ' ||
            to_char(:NEW.fecha_reserva, 'DD/MM/YYYY') || ' en el tramo ' || :NEW.tramo);
        END IF;
    END LOOP;
END;
/

--Triggers que comprueban si un aula ya está reservado para una fecha y una hora
--y si la fecha de la reserva no es anterior a la actual

CREATE OR REPLACE TRIGGER tr_reserva_aul_f
BEFORE UPDATE ON reservasaulas 
FOR EACH ROW
BEGIN
  IF(:NEW.fecha_reserva < to_date(TO_CHAR(SYSDATE, 'yyyy/mm/dd'), 'yyyy/mm/dd'))
    THEN raise_application_error(-20010, 'No se puede realizar la reserva, ya que se está intentando reservar para un día anterior
    a la fecha de hoy');
  END IF;
END;
/

CREATE OR REPLACE TRIGGER tr_reserva_aul 
BEFORE INSERT ON reservasaulas 
FOR EACH ROW
DECLARE
CURSOR C IS
    SELECT * FROM reservasaulas WHERE numero = :NEW.numero ORDER BY fecha_reserva DESC;
BEGIN
    IF(substr(:NEW.num_carne, 6) = 'A') THEN
        raise_application_error(-20015, 'Un alumno no puede reservar materiales o aulas');
    END IF;
    
    carne_valido(:NEW.num_carne);
    
     IF(:NEW.fecha_reserva < to_date(TO_CHAR(SYSDATE, 'yyyy/mm/dd'), 'yyyy/mm/dd'))
        THEN raise_application_error(-20010, 'No se puede realizar la reserva, ya que se está intentando reservar para un día anterior
        a la fecha de hoy');
    END IF;
    
    FOR fila IN C LOOP
        IF(fila.fecha_reserva = :NEW.fecha_reserva AND fila.tramo = :NEW.tramo)
            THEN raise_application_error(-20012, 'El aula no se puede reservar porque ya ha sido reservado para el día ' ||
            to_char(:NEW.fecha_reserva, 'DD/MM/YYYY') || ' en el tramo ' || :NEW.tramo);
        END IF;
    END LOOP;
END;
/

--Triggers para comprobar validez del carne en prestamos y reservas

CREATE OR REPLACE TRIGGER tr_carne_prestamo
BEFORE INSERT ON prestamos
FOR EACH ROW
BEGIN
    carne_valido(:NEW.num_carne);
END;
/

CREATE OR REPLACE TRIGGER tr_carne_aulas
BEFORE INSERT ON reservasaulas
FOR EACH ROW
BEGIN
    carne_valido(:NEW.num_carne);
END;
/

CREATE OR REPLACE TRIGGER tr_carne_materiales
BEFORE INSERT ON reservasmateriales
FOR EACH ROW
BEGIN
    carne_valido(:NEW.num_carne);
END;
/

CREATE OR REPLACE TRIGGER tr_dia_ocupado
BEFORE INSERT ON horarios
FOR EACH ROW
declare
    cont integer;
BEGIN
    SELECT COUNT(*) INTO cont FROM horarios WHERE (horarios.num_carne = :new.num_carne AND horarios.dia = :new.dia and horarios.tramo = :new.tramo);
    if (cont != 0)
        THEN delete from horarios where (horarios.num_carne = :new.num_carne AND horarios.dia = :new.dia and horarios.tramo = :new.tramo);
    end if;
END;
/